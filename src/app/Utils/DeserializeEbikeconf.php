<?php

namespace App\Utils;

use App\Data\ConfEbikeFields;

class DeserializeEbikeconf
{
    public static function v1(string $buffer): array
    {
        $readers = Helper::createBufferReaders($buffer);
        $readUInt8       = $readers['readUInt8'];
        $readInt16       = $readers['readInt16'];
        $readUInt16      = $readers['readUInt16'];
        $readInt32       = $readers['readInt32'];
        $readUInt32      = $readers['readUInt32'];
        $readFloat16     = $readers['readFloat16'];
        $readFloat32Auto = $readers['readFloat32Auto'];
        $readArray       = $readers['readArray'];

        $conf = [];

        $conf['signature'] = $readUInt32();

        $conf['controllerSerial'] = Helper::convertArrayToString($readArray(16));
        $conf['motorSerial']      = Helper::convertArrayToString($readArray(16));

        $conf['torqueFactor']    = $readArray(11);
        $conf['trottleFactor']   = $readArray(11);
        $conf['senseTorque']     = $readArray(11);
        $conf['maxSpeedTorque']  = $readArray(11);
        $conf['maxSpeedTrottle'] = $readArray(11);

        $conf['torqueFactor2']    = $readArray(11);
        $conf['trottleFactor2']   = $readArray(11);
        $conf['senseTorque2']     = $readArray(11);
        $conf['maxSpeedTorque2']  = $readArray(11);
        $conf['maxSpeedTrottle2'] = $readArray(11);

        $conf['maxWatt']        = $readUInt16();
        $conf['batteryCurrent'] = $readUInt8();
        $conf['wheelSize']      = $readUInt16();
        $conf['motorCurrent']   = $readUInt8();

        $conf['display_parameter'] = $readUInt8() ? 1 : 0;
        $conf['maxAssistSteps']    = $readUInt8();

        $conf['maxMotorCurrent']  = $readArray(11);
        $conf['maxMotorCurrent2'] = $readArray(11);

        $conf['wattPadelecMode'] = Helper::convertIndexToEnum(
            $readUInt8(),
            ConfEbikeFields::METADATA['wattPadelecMode']['enums']
        );

        $conf['senseCadence']  = $readArray(11);
        $conf['senseCadence2'] = $readArray(11);

        $conf['crank_length'] = $readUInt8();

        return $conf;
    }
}
