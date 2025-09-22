<?php

namespace App\Utils;

class DeserializeAppconf
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

        // Signature
        $conf['signature'] = $readUInt32();

        // Basisfelder
        $conf['controller_id']          = $readUInt8();
        $conf['timeout_msec']           = $readUInt32();
        $conf['timeout_brake_current']  = $readFloat32Auto();
        $conf['send_can_status']        = $readUInt8();
        $conf['send_can_status_rate_hz']= $readUInt16();
        $conf['can_baud_rate']          = $readUInt8();
        $conf['pairing_done']           = $readUInt8();
        $conf['permanent_uart_enabled'] = $readUInt8();
        $conf['shutdown_mode']          = $readUInt8();
        $conf['can_mode']               = $readUInt8();
        $conf['uavcan_esc_index']       = $readUInt8();
        $conf['uavcan_raw_mode']        = $readUInt8();
        $conf['uavcan_raw_rpm_max']     = $readFloat32Auto();
        $conf['servo_out_enable']       = $readUInt8();
        $conf['kill_sw_mode']           = $readUInt8();
        $conf['app_to_use']             = $readUInt8();

        // app_ppm_conf
        $conf['app_ppm_conf'] = [
            'ctrl_type'           => $readUInt8(),
            'pid_max_erpm'        => $readFloat32Auto(),
            'hyst'                => $readFloat32Auto(),
            'pulse_start'         => $readFloat32Auto(),
            'pulse_end'           => $readFloat32Auto(),
            'pulse_center'        => $readFloat32Auto(),
            'median_filter'       => $readUInt8(),
            'safe_start'          => $readUInt8(),
            'throttle_exp'        => $readFloat32Auto(),
            'throttle_exp_brake'  => $readFloat32Auto(),
            'throttle_exp_mode'   => $readUInt8(),
            'ramp_time_pos'       => $readFloat32Auto(),
            'ramp_time_neg'       => $readFloat32Auto(),
            'multi_esc'           => $readUInt8(),
            'tc'                  => $readUInt8(),
            'tc_max_diff'         => $readFloat32Auto(),
            'max_erpm_for_dir'    => $readFloat16(1),
            'smart_rev_max_duty'  => $readFloat32Auto(),
            'smart_rev_ramp_time' => $readFloat32Auto(),
        ];

        // app_adc_conf
        $conf['app_adc_conf'] = [
            'ctrl_type'           => $readUInt8(),
            'hyst'                => $readFloat32Auto(),
            'voltage_start'       => $readFloat32Auto(),
            'voltage_end'         => $readFloat32Auto(),
            'voltage_center'      => $readFloat32Auto(),
            'voltage2_start'      => $readFloat32Auto(),
            'voltage2_end'        => $readFloat32Auto(),
            'use_filter'          => $readUInt8(),
            'safe_start'          => $readUInt8(),
            'cc_button_inverted'  => $readUInt8(),
            'rev_button_inverted' => $readUInt8(),
            'voltage_inverted'    => $readUInt8(),
            'voltage2_inverted'   => $readUInt8(),
            'throttle_exp'        => $readFloat32Auto(),
            'throttle_exp_brake'  => $readFloat32Auto(),
            'throttle_exp_mode'   => $readUInt8(),
            'ramp_time_pos'       => $readFloat32Auto(),
            'ramp_time_neg'       => $readFloat32Auto(),
            'multi_esc'           => $readUInt8(),
            'tc'                  => $readUInt8(),
            'tc_max_diff'         => $readFloat32Auto(),
            'update_rate_hz'      => $readUInt16(),
        ];

        // UART
        $conf['app_uart_baudrate'] = $readUInt32();

        // app_chuk_conf
        $conf['app_chuk_conf'] = [
            'ctrl_type'            => $readUInt8(),
            'hyst'                 => $readFloat32Auto(),
            'ramp_time_pos'        => $readFloat32Auto(),
            'ramp_time_neg'        => $readFloat32Auto(),
            'stick_erpm_per_s_in_cc'=> $readFloat32Auto(),
            'throttle_exp'         => $readFloat32Auto(),
            'throttle_exp_brake'   => $readFloat32Auto(),
            'throttle_exp_mode'    => $readUInt8(),
            'multi_esc'            => $readUInt8(),
            'tc'                   => $readUInt8(),
            'tc_max_diff'          => $readFloat32Auto(),
            'use_smart_rev'        => $readUInt8(),
            'smart_rev_max_duty'   => $readFloat32Auto(),
            'smart_rev_ramp_time'  => $readFloat32Auto(),
        ];

        // app_nrf_conf
        $conf['app_nrf_conf'] = [
            'speed'        => $readUInt8(),
            'power'        => $readUInt8(),
            'crc_type'     => $readUInt8(),
            'retry_delay'  => $readUInt8(),
            'retries'      => $readUInt8(),
            'channel'      => $readUInt8(),
            'address'      => [$readUInt8(), $readUInt8(), $readUInt8()],
            'send_crc_ack' => $readUInt8(),
        ];

        // app_balance_conf
        $conf['app_balance_conf'] = [
            'kp'                      => $readFloat32Auto(),
            'ki'                      => $readFloat32Auto(),
            'kd'                      => $readFloat32Auto(),
            'hertz'                   => $readUInt16(),
            'loop_time_filter'        => $readUInt16(),
            'fault_pitch'             => $readFloat32Auto(),
            'fault_roll'              => $readFloat32Auto(),
            'fault_duty'              => $readFloat32Auto(),
            'fault_adc1'              => $readFloat32Auto(),
            'fault_adc2'              => $readFloat32Auto(),
            'fault_delay_pitch'       => $readUInt16(),
            'fault_delay_roll'        => $readUInt16(),
            'fault_delay_duty'        => $readUInt16(),
            'fault_delay_switch_half' => $readUInt16(),
            'fault_delay_switch_full' => $readUInt16(),
            'fault_adc_half_erpm'     => $readUInt16(),
            'tiltback_duty_angle'     => $readFloat16(100),
            'tiltback_duty_speed'     => $readFloat16(100),
            'tiltback_duty'           => $readFloat16(1000),
            'tiltback_hv_angle'       => $readFloat16(100),
            'tiltback_hv_speed'       => $readFloat16(100),
            'tiltback_hv'             => $readFloat32Auto(),
            'tiltback_lv_angle'       => $readFloat16(100),
            'tiltback_lv_speed'       => $readFloat16(100),
            'tiltback_lv'             => $readFloat32Auto(),
            'tiltback_return_speed'   => $readFloat16(100),
            'tiltback_constant'       => $readFloat32Auto(),
            'tiltback_constant_erpm'  => $readUInt16(),
            'tiltback_variable'       => $readFloat32Auto(),
            'tiltback_variable_max'   => $readFloat32Auto(),
            'noseangling_speed'       => $readFloat16(100),
            'startup_pitch_tolerance' => $readFloat32Auto(),
            'startup_roll_tolerance'  => $readFloat32Auto(),
            'startup_speed'           => $readFloat32Auto(),
            'deadzone'                => $readFloat32Auto(),
            'multi_esc'               => $readUInt8(),
            'yaw_kp'                  => $readFloat32Auto(),
            'yaw_ki'                  => $readFloat32Auto(),
            'yaw_kd'                  => $readFloat32Auto(),
            'roll_steer_kp'           => $readFloat32Auto(),
            'roll_steer_erpm_kp'      => $readFloat32Auto(),
            'brake_current'           => $readFloat32Auto(),
            'brake_timeout'           => $readUInt16(),
            'yaw_current_clamp'       => $readFloat32Auto(),
            'kd_pt1_lowpass_frequency'=> $readUInt16(),
            'kd_pt1_highpass_frequency'=> $readUInt16(),
            'kd_biquad_lowpass'       => $readFloat32Auto(),
            'kd_biquad_highpass'      => $readFloat32Auto(),
            'booster_angle'           => $readFloat32Auto(),
            'booster_ramp'            => $readFloat32Auto(),
            'booster_current'         => $readFloat32Auto(),
            'torquetilt_start_current'=> $readFloat32Auto(),
            'torquetilt_angle_limit'  => $readFloat32Auto(),
            'torquetilt_on_speed'     => $readFloat32Auto(),
            'torquetilt_off_speed'    => $readFloat32Auto(),
            'torquetilt_strength'     => $readFloat32Auto(),
            'torquetilt_filter'       => $readFloat32Auto(),
            'turntilt_strength'       => $readFloat32Auto(),
            'turntilt_angle_limit'    => $readFloat32Auto(),
            'turntilt_start_angle'    => $readFloat32Auto(),
            'turntilt_start_erpm'     => $readUInt16(),
            'turntilt_speed'          => $readFloat32Auto(),
            'turntilt_erpm_boost'     => $readUInt16(),
            'turntilt_erpm_boost_end' => $readUInt16(),
        ];

        // app_pas_conf
        $conf['app_pas_conf'] = [
            'ctrl_type'              => $readUInt8(),
            'sensor_type'            => $readUInt8(),
            'current_scaling'        => $readFloat16(1000),
            'pedal_rpm_start'        => $readFloat16(10),
            'pedal_rpm_end'          => $readFloat16(10),
            'invert_pedal_direction' => $readUInt8(),
            'magnets'                => $readUInt16(),
            'use_filter'             => $readUInt8(),
            'ramp_time_pos'          => $readFloat16(100),
            'ramp_time_neg'          => $readFloat16(100),
            'update_rate_hz'         => $readUInt16(),
        ];

        // imu_conf
        $conf['imu_conf'] = [
            'type'                   => $readUInt8(),
            'mode'                   => $readUInt8(),
            'sample_rate_hz'         => $readUInt16(),
            'accel_confidence_decay' => $readFloat32Auto(),
            'mahony_kp'              => $readFloat32Auto(),
            'mahony_ki'              => $readFloat32Auto(),
            'madgwick_beta'          => $readFloat32Auto(),
            'rot_roll'               => $readFloat32Auto(),
            'rot_pitch'              => $readFloat32Auto(),
            'rot_yaw'                => $readFloat32Auto(),
            'accel_offsets' => [
                $readFloat32Auto(),
                $readFloat32Auto(),
                $readFloat32Auto()
            ],
            'gyro_offsets' => [
                $readFloat32Auto(),
                $readFloat32Auto(),
                $readFloat32Auto()
            ],
        ];

        return $conf;
    }
}
