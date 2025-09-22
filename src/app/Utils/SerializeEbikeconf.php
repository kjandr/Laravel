<?php

namespace App\Utils;

use App\Data\ConfEbikeFields;

class SerializeEbikeconf
{
    public static function v1(array $conf, int $signature): string
    {
        $writers = Helper::createBufferWriters();
        $writeUInt8       = $writers['writeUInt8'];
        $writeUInt16      = $writers['writeUInt16'];
        $writeInt16       = $writers['writeInt16'];
        $writeUInt32      = $writers['writeUInt32'];
        $writeInt32       = $writers['writeInt32'];
        $writeFloat16     = $writers['writeFloat16'];
        $writeFloat32Auto = $writers['writeFloat32Auto'];
        $writeByteArray   = $writers['writeByteArray'];

        // 1) Signature
        $writeUInt32($signature);

        // 2) Serial-Nummern
        $writeByteArray(Helper::convertStringToArray($conf['controllerSerial'], 16), 16);
        $writeByteArray(Helper::convertStringToArray($conf['motorSerial'], 16), 16);

        // 3) Arrays mit je 11 Werten
        $writeByteArray($conf['torqueFactor'], 11);
        $writeByteArray($conf['trottleFactor'], 11);
        $writeByteArray($conf['senseTorque'], 11);
        $writeByteArray($conf['maxSpeedTorque'], 11);
        $writeByteArray($conf['maxSpeedTrottle'], 11);

        $writeByteArray($conf['torqueFactor2'], 11);
        $writeByteArray($conf['trottleFactor2'], 11);
        $writeByteArray($conf['senseTorque2'], 11);
        $writeByteArray($conf['maxSpeedTorque2'], 11);
        $writeByteArray($conf['maxSpeedTrottle2'], 11);

        // 4) Zahlen
        $writeUInt16($conf['maxWatt']);
        $writeUInt8($conf['batteryCurrent']);
        $writeUInt16($conf['wheelSize']);
        $writeUInt8($conf['motorCurrent']);

        $writeUInt8($conf['display_parameter'] ? 1 : 0);
        $writeUInt8($conf['maxAssistSteps']);

        $writeByteArray($conf['maxMotorCurrent'], 11);
        $writeByteArray($conf['maxMotorCurrent2'], 11);

        $writeUInt8(
            Helper::convertEnumToIndex(
                $conf['wattPadelecMode'],
                ConfEbikeFields::METADATA['wattPadelecMode']['enums']
            )
        );

        $writeByteArray($conf['senseCadence'], 11);
        $writeByteArray($conf['senseCadence2'], 11);

        $writeUInt8($conf['crank_length']);

        return $writers['getBuffer']();
    }
}
