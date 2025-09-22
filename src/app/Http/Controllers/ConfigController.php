<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// Imports (entsprechend deinen require's)
use App\Utils\DeserializeMcconf;
use App\Utils\DeserializeEbikeconf;
use App\Utils\DeserializeAppconf;

use App\Utils\SerializeMcconf;
use App\Utils\SerializeEbikeconf;
use App\Utils\SerializeAppconf;

use App\Utils\Crypto;
use App\Utils\Helper;

use App\Data\ConfMcFields;
use App\Data\ConfEbikeFields;
use App\Data\ConfAppFields;

use App\Utils\LoadDevices;

class ConfigController extends Controller
{
    private array $SIGNATURES = [
        "mc"    => [ "v1" => 4165796136 ],
        "ebike" => [ "v1" => 1315649970 ],
        "app"   => [ "v1" => 3733512279 ],
    ];

    private array $GET_CONFIG_MAP;
    private array $SET_CONFIG_MAP;

    public function __construct()
    {
        $this->GET_CONFIG_MAP = [
            "mc" => [
                "deserialize" => [DeserializeMcconf::class, 'v1'],
                "serialize"   => [SerializeMcconf::class, 'v1'],
                "fieldMap"    => ConfMcFields::FIELD_MAP,
                "metadata"    => ConfMcFields::METADATA,
                "signatures"  => $this->SIGNATURES["mc"],
            ],
            "ebike" => [
                "deserialize" => [DeserializeEbikeconf::class, 'v1'],
                "serialize"   => [SerializeEbikeconf::class, 'v1'],
                "fieldMap"    => ConfEbikeFields::FIELD_MAP,
                "metadata"    => ConfEbikeFields::METADATA,
                "signatures"  => $this->SIGNATURES["ebike"],
            ],
            "app" => [
                "deserialize" => [DeserializeAppconf::class, 'v1'],
                "serialize"   => [SerializeAppconf::class, 'v1'],
                "fieldMap"    => ConfAppFields::FIELD_MAP,
                "metadata"    => ConfAppFields::METADATA,
                "signatures"  => $this->SIGNATURES["app"],
            ]
        ];

        $this->SET_CONFIG_MAP = $this->GET_CONFIG_MAP; // 1:1 Kopie
    }

    private function decryptConf(string $buffer, string $version): array
    {
        if (Helper::versionLt($version, "2.5.5.3")) {
            return ["plain" => $buffer, "salt" => null];
        }
        return Crypto::decrypt($buffer);
    }

    private function encryptedConf(string $buffer, ?string $salt, string $version): array
    {
        if (Helper::versionLt($version, "2.5.5.3")) {
            return ["encrypted" => $buffer, "salt" => null];
        }
        return Crypto::encrypted($buffer, $salt);
    }

    private function processEncryptedConf(array $params): array
    {
        $encrypted = base64_decode($params["confB64"]);
        $res = $this->decryptConf($encrypted, $params["version"]);

        $plain = $res["plain"];
        $salt  = $res["salt"];

        if (!$plain || strlen($plain) < 4) {
            throw new \Exception("Ungültige oder zu kurze Konfigurationsdaten.");
        }

        $signature = unpack("N", substr($plain, 0, 4))[1];
        if ($signature !== $params["signatures"]["v1"]) {
            throw new \Exception("Unbekannte Signatur: ".$signature);
        }

        $deserialize = $params["deserialize"];
        $serialize   = $params["serialize"];

        $conf = call_user_func($deserialize, $plain);

        $serializeToB64 = function ($confToSerialize) use ($serialize, $signature, $params, $salt, $plain) {
            $serialized = call_user_func($serialize, $confToSerialize, $signature);
            $uuidBytes  = Helper::uuidToBytes($params["uuid"]);
            $final      = $serialized.$uuidBytes;
            $enc        = $this->encryptedConf($final, $salt, $params["version"]);
            return base64_encode($enc["encrypted"]);
        };

        return [ "conf" => $conf, "serializeToB64" => $serializeToB64 ];
    }

    // GET-Handler (entspricht createGetConfigHandler)
    public function getConfig(Request $req, string $type)
    {
        Log::info("➡️ getConfig aufgerufen", [
            'type' => $type,
            'uuid' => $req->input("uuid"),
            'version' => $req->input("version"),
            'conf' => substr($req->input("conf") ?? '', 0, 40) . '...' // nur Vorschau
        ]);

        try {
            $map = $this->GET_CONFIG_MAP[$type];

            $uuid    = $req->input("uuid");
            $version = $req->input("version");
            $confB64 = $req->input("conf");

            if (!$uuid) {
                return response()->json([ "error" => "`uuid` fehlt oder ist ungültig." ], 400);
            }
            if (!is_string($confB64)) {
                return response()->json([ "error" => "`conf` muss ein Base64-String sein." ], 400);
            }

            $processed = $this->processEncryptedConf([
                "confB64"    => $confB64,
                "version"    => $version,
                "signatures" => $map["signatures"],
                "deserialize"=> $map["deserialize"],
                "serialize"  => $map["serialize"],
                "uuid"       => $uuid
            ]);

            $conf = $processed["conf"];
            $serializeToB64 = $processed["serializeToB64"];

            // display-Map bauen (wie im JS-Code)
            $display = [];
            foreach ($map["fieldMap"] as $origKey => $fm) {
                $alias = $fm["alias"];
                $metaKeys = $fm["meta"];
                if (array_key_exists($origKey, $conf) && isset($map["metadata"][$origKey])) {
                    $entry = [ "value" => $conf[$origKey] ];
                    foreach ($metaKeys as $m) {
                        if (isset($map["metadata"][$origKey][$m])) {
                            $entry[$m] = $map["metadata"][$origKey][$m];
                        }
                    }
                    $display[$alias] = $entry;
                }
            }

            // DB-Werte (hier Dummy, da DB-Code 1:1 Portierung sehr umfangreich)
            $restoredFromDb = false;

            $resultConfB64 = $serializeToB64($conf);

            return response()->json([
                "uuid"    => $uuid,
                "version" => $version,
                "conf"    => $resultConfB64,
                "display" => $display,
                "restore" => $restoredFromDb,
                "status"  => "success",
                "message" => $restoredFromDb
                    ? "Konfiguration erfolgreich aus DB ergänzt"
                    : "Konfiguration erfolgreich gelesen"
            ]);
        } catch (\Exception $e) {
            return response()->json([ "error" => $e->getMessage() ], 500);
        }
    }

    // SET-Handler (entspricht createSetConfigHandler)
    public function setConfig(Request $req, string $type)
    {
        try {
            $map = $this->SET_CONFIG_MAP[$type];

            $uuid    = $req->input("uuid");
            $version = $req->input("version");
            $confB64 = $req->input("conf");
            $values  = $req->input("values");
            $hw      = $req->input("hw");

            if (!$uuid) {
                return response()->json([ "error" => "`uuid` fehlt oder ist ungültig." ], 400);
            }
            if (!is_string($confB64)) {
                return response()->json([ "error" => "`conf` muss ein Base64-String sein." ], 400);
            }

            $processed = $this->processEncryptedConf([
                "confB64"    => $confB64,
                "version"    => $version,
                "signatures" => $map["signatures"],
                "deserialize"=> $map["deserialize"],
                "serialize"  => $map["serialize"],
                "uuid"       => $uuid
            ]);

            $conf = $processed["conf"];
            $serializeToB64 = $processed["serializeToB64"];

            // Werte übernehmen
            $newValues = Helper::decodeAndMapAliases($values, $map["fieldMap"]);
            Helper::mergeConf($conf, $newValues);

            // DB speichern (Dummy, analog zu writeConfigToDb in JS)

            $resultConfB64 = $serializeToB64($conf);

            return response()->json([
                "uuid"    => $uuid,
                "version" => $version,
                "conf"    => $resultConfB64,
                "status"  => "success",
                "message" => "Konfiguration erfolgreich gespeichert"
            ]);
        } catch (\Exception $e) {
            return response()->json([ "error" => $e->getMessage() ], 500);
        }
    }
}
