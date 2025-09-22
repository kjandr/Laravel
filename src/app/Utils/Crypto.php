<?php

namespace App\Utils;

class Crypto
{
    public static function decipher(int $numCycles, array &$v, array $k): void
    {
        $delta = 0x8F4AE5CA;
        $v0 = $v[0] & 0xFFFFFFFF;
        $v1 = $v[1] & 0xFFFFFFFF;
        $sum = ($delta * $numCycles) & 0xFFFFFFFF;

        for ($i = 0; $i < $numCycles; $i++) {
            $tmp1 = ((($v0 << 4) ^ ($v0 >> 5)) + $v0) & 0xFFFFFFFF;
            $key1 = ($sum + ($k[($sum >> 11) & 3] & 0xFFFFFFFF)) & 0xFFFFFFFF;
            $v1 = ($v1 - ($tmp1 ^ $key1)) & 0xFFFFFFFF;

            $sum = ($sum - $delta) & 0xFFFFFFFF;

            $tmp2 = ((($v1 << 4) ^ ($v1 >> 5)) + $v1) & 0xFFFFFFFF;
            $key2 = ($sum + ($k[$sum & 3] & 0xFFFFFFFF)) & 0xFFFFFFFF;
            $v0 = ($v0 - ($tmp2 ^ $key2)) & 0xFFFFFFFF;
        }

        $v[0] = $v0;
        $v[1] = $v1;
    }

    public static function encipher(int $numCycles, array &$v, array $k): void
    {
        $delta = 0x8F4AE5CA;
        $v0 = $v[0] & 0xFFFFFFFF;
        $v1 = $v[1] & 0xFFFFFFFF;
        $sum = 0;

        for ($i = 0; $i < $numCycles; $i++) {
            $tmp1 = ((($v1 << 4) ^ ($v1 >> 5)) + $v1) & 0xFFFFFFFF;
            $key1 = ($sum + ($k[$sum & 3] & 0xFFFFFFFF)) & 0xFFFFFFFF;
            $v0 = ($v0 + ($tmp1 ^ $key1)) & 0xFFFFFFFF;

            $sum = ($sum + $delta) & 0xFFFFFFFF;

            $tmp2 = ((($v0 << 4) ^ ($v0 >> 5)) + $v0) & 0xFFFFFFFF;
            $key2 = ($sum + ($k[($sum >> 11) & 3] & 0xFFFFFFFF)) & 0xFFFFFFFF;
            $v1 = ($v1 + ($tmp2 ^ $key2)) & 0xFFFFFFFF;
        }

        $v[0] = $v0;
        $v[1] = $v1;
    }

    public static function decrypt(string $buffer): array
    {
        $plain = $buffer;

        // Standard-Key
        $key = config('app.decrypt_keys');

        $len = strlen($plain);
        $saltOffset = $len - 1 - 4;
        $saltData = substr($plain, $saltOffset, 4);
        $salt = unpack("N", $saltData)[1];
        $key[1] = $salt;

        $blockCount = intdiv(($len - 1 - 4), 8);
        $plainArr = str_split($plain, 1);

        for ($i = 0; $i < $blockCount; $i++) {
            $inOff = $i * 8;
            $v0 = unpack("N", substr($plain, $inOff, 4))[1];
            $v1 = unpack("N", substr($plain, $inOff + 4, 4))[1];
            $v = [$v0, $v1];

            self::decipher(50, $v, $key);

            $block = pack("N", $v[0]) . pack("N", $v[1]);
            for ($j = 0; $j < 8; $j++) {
                $plainArr[$inOff + $j] = $block[$j];
            }
        }

        $plainStr = implode('', $plainArr);

        return ['plain' => $plainStr, 'salt' => $salt];
    }

    public static function encrypted(string $buffer, int $salt): array
    {
        $key = config('app.encrypted_keys');

        $serverSalt = random_int(0, 0xFFFFFFFF);
        $key[1] = $salt;
        $key[2] = $serverSalt;

        $origLen = strlen($buffer);
        $mod = ($origLen - 1) % 8;
        $paddedLen = $mod !== 0 ? (8 - $mod) + $origLen : $origLen;
        $totalLen = $paddedLen + 4;

        $newBuffer = str_pad($buffer, $paddedLen, "\0", STR_PAD_RIGHT);

        for ($i = 0; $i < ($paddedLen - 1) / 8; $i++) {
            $indRead = $i * 8;
            $v0 = unpack("N", substr($newBuffer, $indRead, 4))[1];
            $v1 = unpack("N", substr($newBuffer, $indRead + 4, 4))[1];
            $v = [$v0, $v1];

            self::encipher(50, $v, $key);

            $newBuffer = substr_replace($newBuffer, pack("N", $v[0]) . pack("N", $v[1]), $indRead, 8);
        }

        $newBuffer .= pack("N", $serverSalt);

        return ['encrypted' => $newBuffer, 'salt' => $serverSalt];
    }
}
