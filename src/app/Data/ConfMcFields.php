<?php

namespace App\Data;

class ConfMcFields
{
    /**
     * Metadaten für Motor-Controller Felder
     */
    public const METADATA = [

        'foc_encoder_inverted'   => [ 'type' => 'bool',   'suffix' => '' ],
        'foc_encoder_offset'     => [ 'type' => 'double', 'scale' => 1,        'suffix' => '°',     'min' => 0,    'max' => 360,    'decimals' => 2 ],
        'foc_encoder_ratio'      => [ 'type' => 'double', 'scale' => 1,        'suffix' => '-',     'min' => 0,    'max' => 10000,  'decimals' => 2 ],
        'foc_sensor_mode'        => [ 'type' => 'enum',   'enums' => ["Sensorless","Encoder","Hall Sensors","HFI","VSS"] ],
        'l_battery_cut_end'      => [ 'type' => 'double', 'scale' => 1,        'suffix' => 'V',     'min' => 0,    'max' => 59.5,   'decimals' => 2 ],
        'l_battery_cut_start'    => [ 'type' => 'double', 'scale' => 1,        'suffix' => 'V',     'min' => 0,    'max' => 59.5,   'decimals' => 2 ],
        'si_battery_ah'          => [ 'type' => 'double', 'scale' => 1,        'suffix' => 'Ah',    'min' => 0,    'max' => 255,    'decimals' => 3 ],
        'si_battery_cells'       => [ 'type' => 'enum',   'enums' => ["10","11","12","13","14"], 'suffix' => 'cells' ],
        'foc_current_ki'         => [ 'type' => 'double', 'scale' => 1,        'suffix' => 'A',     'min' => 0,    'max' => 100000, 'decimals' => 2 ],
        'foc_current_kp'         => [ 'type' => 'double', 'scale' => 1,        'suffix' => 'A',     'min' => 0,    'max' => 100000, 'decimals' => 4 ],
        'foc_motor_flux_linkage' => [ 'type' => 'double', 'scale' => 1000,     'suffix' => 'mWb',   'min' => 0.0,  'max' => 1000,   'decimals' => 3 ],
        'foc_motor_l'            => [ 'type' => 'double', 'scale' => 1000000,  'suffix' => 'µH',    'min' => 0,    'max' => 10,     'decimals' => 2 ],
        'foc_motor_r'            => [ 'type' => 'double', 'scale' => 1000,     'suffix' => 'mΩ',    'min' => 0,    'max' => 1000.0, 'decimals' => 1 ],
        'l_current_max'          => [ 'type' => 'double', 'scale' => 1,        'suffix' => 'A',     'min' => 0,    'max' => 1000.0, 'decimals' => 2 ],
        'l_max_erpm'             => [ 'type' => 'double', 'scale' => 1,        'suffix' => 'erpm',  'min' => 0,    'max' => 200000, 'decimals' => 2 ],
        'l_watt_max'             => [ 'type' => 'double', 'scale' => 1,        'suffix' => 'W',     'min' => 0,    'max' => 5000.0, 'decimals' => 1 ],
        'foc_f_zv'               => [ 'type' => 'double', 'scale' => 0.001,    'suffix' => 'kHz',   'min' => 0.0,  'max' => 150000, 'decimals' => 2 ],
        'l_in_current_max'       => [ 'type' => 'double', 'scale' => 1,        'suffix' => 'A',     'min' => 0,    'max' => 1000,   'decimals' => 2 ],
        'l_in_current_min'       => [ 'type' => 'double', 'scale' => 1,        'suffix' => 'A',     'min' => -1000,'max' => 0.0,    'decimals' => 2 ],
        'foc_observer_gain'      => [ 'type' => 'double', 'scale' => 0.000001, 'suffix' => 'gain',  'min' => 0,    'max' => 20000000000.0, 'decimals' => 2 ],
        'm_motor_temp_sens_type' => [ 'type' => 'enum',   'enums' => ["NTC 10K at 25°C","PTC 1K at 100 °C","KTY83/122","NTC 100K at 25°C","KTY84/130"] ],
        'l_temp_fet_start'       => [ 'type' => 'double', 'scale' => 1,        'suffix' => '°C',    'min' => 40,   'max' => 180,    'decimals' => 1 ],
        'l_temp_fet_end'         => [ 'type' => 'double', 'scale' => 1,        'suffix' => '°C',    'min' => 40,   'max' => 180,    'decimals' => 1 ],
        'l_temp_motor_start'     => [ 'type' => 'double', 'scale' => 1,        'suffix' => '°C',    'min' => 40,   'max' => 180,    'decimals' => 1 ],
        'l_temp_motor_end'       => [ 'type' => 'double', 'scale' => 1,        'suffix' => '°C',    'min' => 40,   'max' => 180,    'decimals' => 1 ],
        'foc_sl_erpm'            => [ 'type' => 'double', 'scale' => 1,        'suffix' => 'erpm',  'min' => 300,  'max' => 100000, 'decimals' => 2 ],
    ];

    /**
     * Feld-Mapping für Motor-Controller
     */
    public const FIELD_MAP = [

        'foc_encoder_inverted'   => [ 'alias' => 'encInv',         'meta' => ['type','suffix'] ],
        'foc_encoder_offset'     => [ 'alias' => 'encOffset',      'meta' => ['type','scale','min','max','decimals','suffix'] ],
        'foc_encoder_ratio'      => [ 'alias' => 'encRatio',       'meta' => ['type','scale','min','max','decimals','suffix'] ],
        'foc_sensor_mode'        => [ 'alias' => 'sensorMode',     'meta' => ['type','enums'] ],
        'l_battery_cut_end'      => [ 'alias' => 'batCutEnd',      'meta' => ['type','scale','min','max','decimals','suffix'] ],
        'l_battery_cut_start'    => [ 'alias' => 'batCutStart',    'meta' => ['type','scale','min','max','decimals','suffix'] ],
        'si_battery_ah'          => [ 'alias' => 'batAh',          'meta' => ['type','scale','min','max','decimals','suffix'] ],
        'si_battery_cells'       => [ 'alias' => 'batCells',       'meta' => ['type','enums','suffix'] ],
        'foc_current_ki'         => [ 'alias' => 'curKi',          'meta' => ['type','scale','min','max','decimals','suffix'] ],
        'foc_current_kp'         => [ 'alias' => 'curKp',          'meta' => ['type','scale','min','max','decimals','suffix'] ],
        'foc_motor_flux_linkage' => [ 'alias' => 'fluxLinkage',    'meta' => ['type','scale','min','max','decimals','suffix'] ],
        'foc_motor_l'            => [ 'alias' => 'motL',           'meta' => ['type','scale','min','max','decimals','suffix'] ],
        'foc_motor_r'            => [ 'alias' => 'motR',           'meta' => ['type','scale','min','max','decimals','suffix'] ],
        'l_current_max'          => [ 'alias' => 'curMotMax',      'meta' => ['type','scale','min','max','decimals','suffix'] ],
        'l_max_erpm'             => [ 'alias' => 'erpmMax',        'meta' => ['type','scale','min','max','decimals','suffix'] ],
        'l_watt_max'             => [ 'alias' => 'wattMax',        'meta' => ['type','scale','min','max','decimals','suffix'] ],
        'foc_f_zv'               => [ 'alias' => 'freqFoc',        'meta' => ['type','scale','min','max','decimals','suffix'] ],
        'l_in_current_max'       => [ 'alias' => 'curBatMax',      'meta' => ['type','scale','min','max','decimals','suffix'] ],
        'l_in_current_min'       => [ 'alias' => 'curBatMin',      'meta' => ['type','scale','min','max','decimals','suffix'] ],
        'foc_observer_gain'      => [ 'alias' => 'observer',       'meta' => ['type','scale','min','max','decimals','suffix'] ],
        'm_motor_temp_sens_type' => [ 'alias' => 'motorTempType',  'meta' => ['type','enums'] ],
        'l_temp_fet_start'       => [ 'alias' => 'tempFetStart',   'meta' => ['type','scale','min','max','decimals','suffix'] ],
        'l_temp_fet_end'         => [ 'alias' => 'tempFetEnd',     'meta' => ['type','scale','min','max','decimals','suffix'] ],
        'l_temp_motor_start'     => [ 'alias' => 'tempMotorStart', 'meta' => ['type','scale','min','max','decimals','suffix'] ],
        'l_temp_motor_end'       => [ 'alias' => 'tempMotorEnd',   'meta' => ['type','scale','min','max','decimals','suffix'] ],
        'foc_sl_erpm'            => [ 'alias' => 'SlErpm',         'meta' => ['type','scale','min','max','decimals','suffix'] ],
    ];
}
