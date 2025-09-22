<?php

namespace App\Utils;

class SerializeAppconf
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

        // Signature
        $writeUInt32($signature);

        // Allgemeine Konfiguration
        $writeUInt8($conf['controller_id']);
        $writeUInt32($conf['timeout_msec']);
        $writeFloat32Auto($conf['timeout_brake_current']);
        $writeUInt8($conf['send_can_status']);
        $writeUInt16($conf['send_can_status_rate_hz']);
        $writeUInt8($conf['can_baud_rate']);
        $writeUInt8($conf['pairing_done']);
        $writeUInt8($conf['permanent_uart_enabled']);
        $writeUInt8($conf['shutdown_mode']);
        $writeUInt8($conf['can_mode']);
        $writeUInt8($conf['uavcan_esc_index']);
        $writeUInt8($conf['uavcan_raw_mode']);
        $writeFloat32Auto($conf['uavcan_raw_rpm_max']);
        $writeUInt8($conf['servo_out_enable']);
        $writeUInt8($conf['kill_sw_mode']);
        $writeUInt8($conf['app_to_use']);

        // PPM APP Konfiguration
        $ppm = $conf['app_ppm_conf'];
        $writeUInt8($ppm['ctrl_type']);
        $writeFloat32Auto($ppm['pid_max_erpm']);
        $writeFloat32Auto($ppm['hyst']);
        $writeFloat32Auto($ppm['pulse_start']);
        $writeFloat32Auto($ppm['pulse_end']);
        $writeFloat32Auto($ppm['pulse_center']);
        $writeUInt8($ppm['median_filter']);
        $writeUInt8($ppm['safe_start']);
        $writeFloat32Auto($ppm['throttle_exp']);
        $writeFloat32Auto($ppm['throttle_exp_brake']);
        $writeUInt8($ppm['throttle_exp_mode']);
        $writeFloat32Auto($ppm['ramp_time_pos']);
        $writeFloat32Auto($ppm['ramp_time_neg']);
        $writeUInt8($ppm['multi_esc']);
        $writeUInt8($ppm['tc']);
        $writeFloat32Auto($ppm['tc_max_diff']);
        $writeFloat16($ppm['max_erpm_for_dir'], 1);
        $writeFloat32Auto($ppm['smart_rev_max_duty']);
        $writeFloat32Auto($ppm['smart_rev_ramp_time']);

        // ADC APP Konfiguration
        $adc = $conf['app_adc_conf'];
        $writeUInt8($adc['ctrl_type']);
        $writeFloat32Auto($adc['hyst']);
        $writeFloat32Auto($adc['voltage_start']);
        $writeFloat32Auto($adc['voltage_end']);
        $writeFloat32Auto($adc['voltage_center']);
        $writeFloat32Auto($adc['voltage2_start']);
        $writeFloat32Auto($adc['voltage2_end']);
        $writeUInt8($adc['use_filter']);
        $writeUInt8($adc['safe_start']);
        $writeUInt8($adc['cc_button_inverted']);
        $writeUInt8($adc['rev_button_inverted']);
        $writeUInt8($adc['voltage_inverted']);
        $writeUInt8($adc['voltage2_inverted']);
        $writeFloat32Auto($adc['throttle_exp']);
        $writeFloat32Auto($adc['throttle_exp_brake']);
        $writeUInt8($adc['throttle_exp_mode']);
        $writeFloat32Auto($adc['ramp_time_pos']);
        $writeFloat32Auto($adc['ramp_time_neg']);
        $writeUInt8($adc['multi_esc']);
        $writeUInt8($adc['tc']);
        $writeFloat32Auto($adc['tc_max_diff']);
        $writeUInt16($adc['update_rate_hz']);

        // UART-Baudrate
        $writeUInt32($conf['app_uart_baudrate']);

        // CHUK APP Konfiguration
        $chuk = $conf['app_chuk_conf'];
        $writeUInt8($chuk['ctrl_type']);
        $writeFloat32Auto($chuk['hyst']);
        $writeFloat32Auto($chuk['ramp_time_pos']);
        $writeFloat32Auto($chuk['ramp_time_neg']);
        $writeFloat32Auto($chuk['stick_erpm_per_s_in_cc']);
        $writeFloat32Auto($chuk['throttle_exp']);
        $writeFloat32Auto($chuk['throttle_exp_brake']);
        $writeUInt8($chuk['throttle_exp_mode']);
        $writeUInt8($chuk['multi_esc']);
        $writeUInt8($chuk['tc']);
        $writeFloat32Auto($chuk['tc_max_diff']);
        $writeUInt8($chuk['use_smart_rev']);
        $writeFloat32Auto($chuk['smart_rev_max_duty']);
        $writeFloat32Auto($chuk['smart_rev_ramp_time']);

        // NRF APP Konfiguration
        $nrf = $conf['app_nrf_conf'];
        $writeUInt8($nrf['speed']);
        $writeUInt8($nrf['power']);
        $writeUInt8($nrf['crc_type']);
        $writeUInt8($nrf['retry_delay']);
        $writeUInt8($nrf['retries']);
        $writeUInt8($nrf['channel']);
        $writeUInt8($nrf['address'][0]);
        $writeUInt8($nrf['address'][1]);
        $writeUInt8($nrf['address'][2]);
        $writeUInt8($nrf['send_crc_ack']);

        // Balance APP Konfiguration
        $bal = $conf['app_balance_conf'];
        $writeFloat32Auto($bal['kp']);
        $writeFloat32Auto($bal['ki']);
        $writeFloat32Auto($bal['kd']);
        $writeUInt16($bal['hertz']);
        $writeUInt16($bal['loop_time_filter']);
        $writeFloat32Auto($bal['fault_pitch']);
        $writeFloat32Auto($bal['fault_roll']);
        $writeFloat32Auto($bal['fault_duty']);
        $writeFloat32Auto($bal['fault_adc1']);
        $writeFloat32Auto($bal['fault_adc2']);
        $writeUInt16($bal['fault_delay_pitch']);
        $writeUInt16($bal['fault_delay_roll']);
        $writeUInt16($bal['fault_delay_duty']);
        $writeUInt16($bal['fault_delay_switch_half']);
        $writeUInt16($bal['fault_delay_switch_full']);
        $writeUInt16($bal['fault_adc_half_erpm']);
        $writeFloat16($bal['tiltback_duty_angle'], 100);
        $writeFloat16($bal['tiltback_duty_speed'], 100);
        $writeFloat16($bal['tiltback_duty'], 1000);
        $writeFloat16($bal['tiltback_hv_angle'], 100);
        $writeFloat16($bal['tiltback_hv_speed'], 100);
        $writeFloat32Auto($bal['tiltback_hv']);
        $writeFloat16($bal['tiltback_lv_angle'], 100);
        $writeFloat16($bal['tiltback_lv_speed'], 100);
        $writeFloat32Auto($bal['tiltback_lv']);
        $writeFloat16($bal['tiltback_return_speed'], 100);
        $writeFloat32Auto($bal['tiltback_constant']);
        $writeUInt16($bal['tiltback_constant_erpm']);
        $writeFloat32Auto($bal['tiltback_variable']);
        $writeFloat32Auto($bal['tiltback_variable_max']);
        $writeFloat16($bal['noseangling_speed'], 100);
        $writeFloat32Auto($bal['startup_pitch_tolerance']);
        $writeFloat32Auto($bal['startup_roll_tolerance']);
        $writeFloat32Auto($bal['startup_speed']);
        $writeFloat32Auto($bal['deadzone']);
        $writeUInt8($bal['multi_esc']);
        $writeFloat32Auto($bal['yaw_kp']);
        $writeFloat32Auto($bal['yaw_ki']);
        $writeFloat32Auto($bal['yaw_kd']);
        $writeFloat32Auto($bal['roll_steer_kp']);
        $writeFloat32Auto($bal['roll_steer_erpm_kp']);
        $writeFloat32Auto($bal['brake_current']);
        $writeUInt16($bal['brake_timeout']);
        $writeFloat32Auto($bal['yaw_current_clamp']);
        $writeUInt16($bal['kd_pt1_lowpass_frequency']);
        $writeUInt16($bal['kd_pt1_highpass_frequency']);
        $writeFloat32Auto($bal['kd_biquad_lowpass']);
        $writeFloat32Auto($bal['kd_biquad_highpass']);
        $writeFloat32Auto($bal['booster_angle']);
        $writeFloat32Auto($bal['booster_ramp']);
        $writeFloat32Auto($bal['booster_current']);
        $writeFloat32Auto($bal['torquetilt_start_current']);
        $writeFloat32Auto($bal['torquetilt_angle_limit']);
        $writeFloat32Auto($bal['torquetilt_on_speed']);
        $writeFloat32Auto($bal['torquetilt_off_speed']);
        $writeFloat32Auto($bal['torquetilt_strength']);
        $writeFloat32Auto($bal['torquetilt_filter']);
        $writeFloat32Auto($bal['turntilt_strength']);
        $writeFloat32Auto($bal['turntilt_angle_limit']);
        $writeFloat32Auto($bal['turntilt_start_angle']);
        $writeUInt16($bal['turntilt_start_erpm']);
        $writeFloat32Auto($bal['turntilt_speed']);
        $writeUInt16($bal['turntilt_erpm_boost']);
        $writeUInt16($bal['turntilt_erpm_boost_end']);

        // PAS APP Konfiguration
        $pas = $conf['app_pas_conf'];
        $writeUInt8($pas['ctrl_type']);
        $writeUInt8($pas['sensor_type']);
        $writeFloat16($pas['current_scaling'], 1000);
        $writeFloat16($pas['pedal_rpm_start'], 10);
        $writeFloat16($pas['pedal_rpm_end'], 10);
        $writeUInt8($pas['invert_pedal_direction']);
        $writeUInt16($pas['magnets']);
        $writeUInt8($pas['use_filter']);
        $writeFloat16($pas['ramp_time_pos'], 100);
        $writeFloat16($pas['ramp_time_neg'], 100);
        $writeUInt16($pas['update_rate_hz']);

        // IMU Konfiguration
        $imu = $conf['imu_conf'];
        $writeUInt8($imu['type']);
        $writeUInt8($imu['mode']);
        $writeUInt16($imu['sample_rate_hz']);
        $writeFloat32Auto($imu['accel_confidence_decay']);
        $writeFloat32Auto($imu['mahony_kp']);
        $writeFloat32Auto($imu['mahony_ki']);
        $writeFloat32Auto($imu['madgwick_beta']);
        $writeFloat32Auto($imu['rot_roll']);
        $writeFloat32Auto($imu['rot_pitch']);
        $writeFloat32Auto($imu['rot_yaw']);
        $writeFloat32Auto($imu['accel_offsets'][0]);
        $writeFloat32Auto($imu['accel_offsets'][1]);
        $writeFloat32Auto($imu['accel_offsets'][2]);
        $writeFloat32Auto($imu['gyro_offsets'][0]);
        $writeFloat32Auto($imu['gyro_offsets'][1]);
        $writeFloat32Auto($imu['gyro_offsets'][2]);

        return $writers['getBuffer']();
    }
}
