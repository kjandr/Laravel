<?php

namespace App\Data;

class ConfEbikeFields
{
    /**
     * Metadaten für Ebike Felder
     */

    // 1) Definiere hier in einem Array alle statischen Metadaten je Feld:
    public const METADATA = [
        "controllerSerial" => [ "type" => "string" ],
        "motorSerial"      => [ "type" => "string" ],

        "torqueFactor"     => [ "type" => "array", "size" => 11, "scale" => 1, "suffix" => "%",    "min" => 0, "max" => 100, "decimals" => 0 ],
        "trottleFactor"    => [ "type" => "array", "size" => 11, "scale" => 1, "suffix" => "%",    "min" => 0, "max" => 100, "decimals" => 0 ],
        "senseTorque"      => [ "type" => "array", "size" => 11, "scale" => 1, "suffix" => "level","min" => 0, "max" => 10,  "decimals" => 0 ],
        "maxSpeedTorque"   => [ "type" => "array", "size" => 11, "scale" => 1, "suffix" => "km/h", "min" => 0, "max" => 100, "decimals" => 0 ],
        "maxSpeedTrottle"  => [ "type" => "array", "size" => 11, "scale" => 1, "suffix" => "km/h", "min" => 0, "max" => 100, "decimals" => 0 ],

        "torqueFactor2"    => [ "type" => "array", "size" => 11, "scale" => 1, "suffix" => "%",    "min" => 0, "max" => 100, "decimals" => 0 ],
        "trottleFactor2"   => [ "type" => "array", "size" => 11, "scale" => 1, "suffix" => "%",    "min" => 0, "max" => 100, "decimals" => 0 ],
        "senseTorque2"     => [ "type" => "array", "size" => 11, "scale" => 1, "suffix" => "level","min" => 0, "max" => 10,  "decimals" => 0 ],
        "maxSpeedTorque2"  => [ "type" => "array", "size" => 11, "scale" => 1, "suffix" => "km/h", "min" => 0, "max" => 100, "decimals" => 0 ],
        "maxSpeedTrottle2" => [ "type" => "array", "size" => 11, "scale" => 1, "suffix" => "km/h", "min" => 0, "max" => 100, "decimals" => 0 ],

        "maxWatt"          => [ "type" => "int", "scale" => 1, "suffix" => "W", "min" => 0, "max" => 5000 ],
        "batteryCurrent"   => [ "type" => "int", "scale" => 1, "suffix" => "A", "min" => 0, "max" => 100 ],
        "wheelSize"        => [ "type" => "int", "scale" => 1, "suffix" => "mm", "min" => 300, "max" => 9999 ],
        "motorCurrent"     => [ "type" => "int", "scale" => 1, "suffix" => "A", "min" => 0, "max" => 140 ],

        "display_parameter"=> [ "type" => "bool", "suffix" => "" ],
        "maxAssistSteps"   => [ "type" => "int", "scale" => 1, "suffix" => "level", "min" => 0, "max" => 10 ],

        "maxMotorCurrent"  => [ "type" => "array", "size" => 11, "scale" => 1, "suffix" => "A", "min" => 0, "max" => 10 ],
        "maxMotorCurrent2" => [ "type" => "array", "size" => 11, "scale" => 1, "suffix" => "A", "min" => 0, "max" => 10 ],

        "wattPadelecMode"  => [ "type" => "enum", "enums" => ["off","250","350"], "suffix" => "" ],

        "senseCadence"     => [ "type" => "array", "size" => 11, "scale" => 1, "suffix" => "sense", "min" => 0, "max" => 10 ],
        "senseCadence2"    => [ "type" => "array", "size" => 11, "scale" => 1, "suffix" => "sense", "min" => 0, "max" => 10 ],

        "crank_length"     => [ "type" => "int", "scale" => 1, "suffix" => "mm", "min" => 100, "max" => 220 ]
    ];

    // 2) Mapping: Original-Key → Alias
    public const FIELD_MAP = [
        "controllerSerial" => [ "alias" => "controllerSerial", "meta" => ["type"] ],
        "motorSerial"      => [ "alias" => "motorSerial",      "meta" => ["type"] ],
        "torqueFactor"     => [ "alias" => "torqueFactor",     "meta" => ["type","size","scale","suffix","min","max","decimals"] ],
        // … alle weiteren Felder identisch wie in JS
    ];
}
