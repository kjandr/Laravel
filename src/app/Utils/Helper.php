<?php

namespace App\Utils;

use Exception;

class Helper
{
    public static function versionLt(string $a, string $b): bool {
        $pa = array_map('intval', explode('.', $a));
        $pb = array_map('intval', explode('.', $b));
        $len = max(count($pa), count($pb));
        for ($i = 0; $i < $len; $i++) {
            $na = $pa[$i] ?? 0;
            $nb = $pb[$i] ?? 0;
            if ($na < $nb) return true;
            if ($na > $nb) return false;
        }
        return false;
    }

    public static function mergeConf(array &$conf, array $newValues): bool {
        $changed = false;
        foreach ($newValues as $key => $newVal) {
            $oldVal = $conf[$key] ?? null;
            if (is_array($newVal) && !array_is_list($newVal) &&
                is_array($oldVal) && !array_is_list($oldVal)) {
                if (self::mergeConf($oldVal, $newVal)) $changed = true;
                $conf[$key] = $oldVal;
            } else {
                if (json_encode($oldVal) !== json_encode($newVal)) {
                    $conf[$key] = $newVal;
                    $changed = true;
                }
            }
        }
        return $changed;
    }

    public static function decodeAndMapAliases(string $values, array $fieldMap): array {
        $aliasToKey = [];
        foreach ($fieldMap as $originalKey => $meta) {
            $aliasToKey[$meta['alias']] = $originalKey;
        }
        $parsed = json_decode($values, true);
        return self::mapKeysDeep($parsed, $aliasToKey);
    }

    private static function mapKeysDeep($obj, array $aliasMap) {
        if (is_array($obj)) {
            if (array_is_list($obj)) {
                return array_map(fn($item) => self::mapKeysDeep($item, $aliasMap), $obj);
            } else {
                $result = [];
                foreach ($obj as $key => $value) {
                    $mappedKey = $aliasMap[$key] ?? $key;
                    $result[$mappedKey] = self::mapKeysDeep($value, $aliasMap);
                }
                return $result;
            }
        }
        return $obj;
    }

    public static function uuidToBytes(string $uuid): string {
        $cleaned = str_replace('-', '', $uuid);
        return hex2bin($cleaned);
    }

    public static function createBufferReaders(string $buffer): array {
        $offset = 0;
        return [
            'readUInt8' => function() use (&$offset, $buffer) {
                $val = ord($buffer[$offset]);
                $offset += 1;
                return $val;
            },
            'readInt16' => function() use (&$offset, $buffer) {
                $val = unpack('n', substr($buffer, $offset, 2))[1];
                if ($val >= 0x8000) $val -= 0x10000;
                $offset += 2;
                return $val;
            },
            'readUInt16' => function() use (&$offset, $buffer) {
                $val = unpack('n', substr($buffer, $offset, 2))[1];
                $offset += 2;
                return $val;
            },
            'readInt32' => function() use (&$offset, $buffer) {
                $val = unpack('N', substr($buffer, $offset, 4))[1];
                if ($val >= 0x80000000) $val -= 0x100000000;
                $offset += 4;
                return $val;
            },
            'readUInt32' => function() use (&$offset, $buffer) {
                $val = unpack('N', substr($buffer, $offset, 4))[1];
                $offset += 4;
                return $val;
            },
            'readFloat16' => function($scale) use (&$offset, $buffer) {
                $val = unpack('n', substr($buffer, $offset, 2))[1];
                if ($val >= 0x8000) $val -= 0x10000;
                $offset += 2;
                return $val / $scale;
            },
            'readFloat32Auto' => function() use (&$offset, $buffer) {
                $res = unpack('N', substr($buffer, $offset, 4))[1];
                $offset += 4;
                $e = ($res >> 23) & 0xFF;
                $sigI = $res & 0x7FFFFF;
                $neg = ($res >> 31) !== 0;
                $sig = 0.0;
                if ($e !== 0 || $sigI !== 0) {
                    $sig = $sigI / (8388608.0 * 2.0) + 0.5;
                    $e = $e - 126;
                }
                if ($neg) $sig = -$sig;
                return $sig * pow(2, $e);
            },
            'readArray' => function($length) use (&$offset, $buffer) {
                $arr = [];
                for ($i = 0; $i < $length; $i++) {
                    $arr[] = ord($buffer[$offset++]);
                }
                return $arr;
            }
        ];
    }

    public static function convertArrayToString(array $byteArray): string {
        return implode('', array_map(fn($b) => chr($b), array_filter($byteArray, fn($b) => $b !== 0)));
    }

    public static function convertIndexToEnum(int $index, array $enumArray) {
        return ($index >= 0 && $index < count($enumArray)) ? $enumArray[$index] : $enumArray[0];
    }

    public static function createBufferWriters(): array {
        $parts = [];
        return [
            'getParts' => fn() => $parts,
            'getBuffer' => fn() => implode('', $parts),
            'writeUInt8' => function($v) use (&$parts) { $parts[] = chr($v & 0xFF); },
            'writeUInt16' => function($v) use (&$parts) { $parts[] = pack('n', $v); },
            'writeInt16' => function($v) use (&$parts) { $parts[] = pack('n', $v & 0xFFFF); },
            'writeUInt32' => function($v) use (&$parts) { $parts[] = pack('N', $v); },
            'writeInt32' => function($v) use (&$parts) { $parts[] = pack('N', $v); },
            'writeFloat16' => function($v, $scale) use (&$parts) {
                $parts[] = pack('n', (int)round($v * $scale));
            },
            'writeFloat32Auto' => function($n) use (&$parts) {
                if (abs($n) < 1.5e-38) $n = 0.0;
                $parts[] = pack('G', $n);
            },
            'writeByteArray' => function($arr, $expectedLength) use (&$parts) {
                if (count($arr) !== $expectedLength) {
                    throw new Exception("Array muss genau $expectedLength Elemente haben, hat aber " . count($arr));
                }
                foreach ($arr as $v) {
                    $parts[] = chr($v & 0xFF);
                }
            }
        ];
    }

    public static function convertEnumToIndex($value, array $enumArray): int {
        if (is_int($value)) return $value;
        $index = array_search((string)$value, $enumArray, true);
        return $index !== false ? $index : 0;
    }

    public static function convertStringToArray(string $str, int $length = 16): array {
        $arr = array_fill(0, $length, 0);
        $bytes = array_map('ord', str_split($str));
        foreach ($bytes as $i => $byte) {
            if ($i < $length) $arr[$i] = $byte;
        }
        return $arr;
    }
}
