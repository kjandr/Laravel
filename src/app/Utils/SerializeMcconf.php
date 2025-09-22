<?php

namespace App\Utils;

use App\Data\ConfMcFields;

class SerializeMcconf
{
    /**
     * "V1" – schlankes Layout, symmetrisch zu DeserializeMcconf::v1()
     * (enthält jetzt korrekt auch die 16-Byte-serial_no)
     */
    public static function v1(array $conf, int $signature): string
    {
        $w = Helper::createBufferWriters();

        $writeUInt8       = $w['writeUInt8'];
        $writeUInt16      = $w['writeUInt16'];
        $writeInt16       = $w['writeInt16'];
        $writeUInt32      = $w['writeUInt32'];
        $writeInt32       = $w['writeInt32'];
        $writeFloat16     = $w['writeFloat16'];
        $writeFloat32Auto = $w['writeFloat32Auto'];
        $writeByteArray   = $w['writeByteArray'];
        $getBuffer        = $w['getBuffer'];

        // 1) signature
        $writeUInt32($signature);

        // 2) serial_no (16-Byte)
        $serial = (string)($conf['serial_no'] ?? '');
        $writeByteArray(Helper::convertStringToArray($serial, 16), 16);

        // 3.. Felder
        $writeFloat32Auto($conf['l_current_max']);
        $writeFloat32Auto($conf['l_in_current_max']);
        $writeFloat32Auto($conf['l_in_current_min']);
        $writeFloat32Auto($conf['l_max_erpm']);

        $writeFloat32Auto($conf['l_battery_cut_start']);
        $writeFloat32Auto($conf['l_battery_cut_end']);

        $writeFloat16($conf['l_temp_fet_start'],   10);
        $writeFloat16($conf['l_temp_fet_end'],     10);
        $writeFloat16($conf['l_temp_motor_start'], 10);
        $writeFloat16($conf['l_temp_motor_end'],   10);

        $writeFloat32Auto($conf['l_watt_max']);

        $writeFloat32Auto($conf['foc_current_kp']);
        $writeFloat32Auto($conf['foc_current_ki']);
        $writeFloat32Auto($conf['foc_f_zv']);

        $writeUInt8($conf['foc_encoder_inverted']);
        $writeFloat32Auto($conf['foc_encoder_offset']);
        $writeFloat32Auto($conf['foc_encoder_ratio']);

        $writeUInt8(
            Helper::convertEnumToIndex(
                $conf['foc_sensor_mode'],
                ConfMcFields::METADATA['foc_sensor_mode']['enums']
            )
        );

        $writeFloat32Auto($conf['foc_motor_l']);
        $writeFloat32Auto($conf['foc_motor_r']);
        $writeFloat32Auto($conf['foc_motor_flux_linkage']);

        $writeFloat32Auto($conf['foc_observer_gain']);

        $writeUInt8(
            Helper::convertEnumToIndex(
                $conf['m_motor_temp_sens_type'],
                ConfMcFields::METADATA['m_motor_temp_sens_type']['enums']
            )
        );

        // beim Schreiben +10 (spiegelt das Lesen -10)
        $writeUInt8(
            Helper::convertEnumToIndex(
                $conf['si_battery_cells'],
                ConfMcFields::METADATA['si_battery_cells']['enums']
            ) + 10
        );

        $writeFloat32Auto($conf['si_battery_ah']);
        $writeFloat32Auto($conf['foc_sl_erpm']);

        return $getBuffer();
    }

    /**
     * "full" – komplettes, altes (großes) Layout
     * Portierung aus deiner Funktion serializeMcconf_old()
     */
    public static function full(array $conf, int $signature): string
    {
        $w = Helper::createBufferWriters();

        $writeUInt8       = $w['writeUInt8'];
        $writeUInt16      = $w['writeUInt16'];
        $writeInt16       = $w['writeInt16'];
        $writeUInt32      = $w['writeUInt32'];
        $writeInt32       = $w['writeInt32'];
        $writeFloat16     = $w['writeFloat16'];
        $writeFloat32Auto = $w['writeFloat32Auto'];
        $writeByteArray   = $w['writeByteArray'];
        $getBuffer        = $w['getBuffer'];

        // 1) signature
        $writeUInt32($signature);

        // 2–5) u8
        $writeUInt8($conf['pwm_mode']);
        $writeUInt8($conf['comm_mode']);
        $writeUInt8($conf['motor_type']);
        $writeUInt8($conf['sensor_mode']);

        // 6–12) f32
        $writeFloat32Auto($conf['l_current_max']);
        $writeFloat32Auto($conf['l_current_min']);
        $writeFloat32Auto($conf['l_in_current_max']);
        $writeFloat32Auto($conf['l_in_current_min']);
        $writeFloat32Auto($conf['l_abs_current_max']);
        $writeFloat32Auto($conf['l_min_erpm']);
        $writeFloat32Auto($conf['l_max_erpm']);

        // 13) f16/10000
        $writeFloat16($conf['l_erpm_start'], 10000);

        // 14–15) f32
        $writeFloat32Auto($conf['l_max_erpm_fbrake']);
        $writeFloat32Auto($conf['l_max_erpm_fbrake_cc']);

        // 16–17) f32
        $writeFloat32Auto($conf['l_min_vin']);
        $writeFloat32Auto($conf['l_max_vin']);

        // 18–19) f32
        $writeFloat32Auto($conf['l_battery_cut_start']);
        $writeFloat32Auto($conf['l_battery_cut_end']);

        // 20) u8
        $writeUInt8($conf['l_slow_abs_current']);

        // 21–24) f16/10
        $writeFloat16($conf['l_temp_fet_start'],   10);
        $writeFloat16($conf['l_temp_fet_end'],     10);
        $writeFloat16($conf['l_temp_motor_start'], 10);
        $writeFloat16($conf['l_temp_motor_end'],   10);

        // 25–27) f16/10000
        $writeFloat16($conf['l_temp_accel_dec'], 10000);
        $writeFloat16($conf['l_min_duty'],       10000);
        $writeFloat16($conf['l_max_duty'],       10000);

        // 28–29) f32
        $writeFloat32Auto($conf['l_watt_max']);
        $writeFloat32Auto($conf['l_watt_min']);

        // 30–32) f16/10000
        $writeFloat16($conf['l_current_max_scale'], 10000);
        $writeFloat16($conf['l_current_min_scale'], 10000);
        $writeFloat16($conf['l_duty_start'],        10000);

        // 33–35) f32
        $writeFloat32Auto($conf['sl_min_erpm']);
        $writeFloat32Auto($conf['sl_min_erpm_cycle_int_limit']);
        $writeFloat32Auto($conf['sl_max_fullbreak_current_dir_change']);

        // 36) f16/10
        $writeFloat16($conf['sl_cycle_int_limit'], 10);

        // 37) f16/10000
        $writeFloat16($conf['sl_phase_advance_at_br'], 10000);

        // 38–39) f32
        $writeFloat32Auto($conf['sl_cycle_int_rpm_br']);
        $writeFloat32Auto($conf['sl_bemf_coupling_k']);

        // 40) hall_table (8×u8)
        for ($i = 0; $i < 8; $i++) { $writeUInt8($conf['hall_table'][$i]); }

        // 41) f32
        $writeFloat32Auto($conf['hall_sl_erpm']);

        // 42–45) f32
        $writeFloat32Auto($conf['foc_current_kp']);
        $writeFloat32Auto($conf['foc_current_ki']);
        $writeFloat32Auto($conf['foc_f_zv']);
        $writeFloat32Auto($conf['foc_dt_us']);

        // 46) u8
        $writeUInt8($conf['foc_encoder_inverted']);

        // 47–52) f32
        $writeFloat32Auto($conf['foc_encoder_offset']);
        $writeFloat32Auto($conf['foc_encoder_ratio']);
        $writeFloat32Auto($conf['foc_encoder_sin_gain']);
        $writeFloat32Auto($conf['foc_encoder_cos_gain']);
        $writeFloat32Auto($conf['foc_encoder_sin_offset']);
        $writeFloat32Auto($conf['foc_encoder_cos_offset']);

        // 53) f32
        $writeFloat32Auto($conf['foc_encoder_sincos_filter_constant']);

        // 54) u8
        $writeUInt8($conf['foc_sensor_mode']);

        // 55–56) f32
        $writeFloat32Auto($conf['foc_pll_kp']);
        $writeFloat32Auto($conf['foc_pll_ki']);

        // 57–60) f32
        $writeFloat32Auto($conf['foc_motor_l']);
        $writeFloat32Auto($conf['foc_motor_ld_lq_diff']);
        $writeFloat32Auto($conf['foc_motor_r']);
        $writeFloat32Auto($conf['foc_motor_flux_linkage']);

        // 61–62) f32
        $writeFloat32Auto($conf['foc_observer_gain']);
        $writeFloat32Auto($conf['foc_observer_gain_slow']);

        // 63) f16/1000
        $writeFloat16($conf['foc_observer_offset'], 1000);

        // 64–65) f32
        $writeFloat32Auto($conf['foc_duty_dowmramp_kp']);
        $writeFloat32Auto($conf['foc_duty_dowmramp_ki']);

        // 66) f32
        $writeFloat32Auto($conf['foc_openloop_rpm']);

        // 67) f16/1000
        $writeFloat16($conf['foc_openloop_rpm_low'], 1000);

        // 68–69) f32
        $writeFloat32Auto($conf['foc_d_gain_scale_start']);
        $writeFloat32Auto($conf['foc_d_gain_scale_max_mod']);

        // 70–73) f16/100
        $writeFloat16($conf['foc_sl_openloop_hyst'],       100);
        $writeFloat16($conf['foc_sl_openloop_time_lock'],  100);
        $writeFloat16($conf['foc_sl_openloop_time_ramp'],  100);
        $writeFloat16($conf['foc_sl_openloop_time'],       100);

        // 74) foc_hall_table (8×u8)
        for ($i = 0; $i < 8; $i++) { $writeUInt8($conf['foc_hall_table'][$i]); }

        // 75–76) f32
        $writeFloat32Auto($conf['foc_hall_interp_erpm']);
        $writeFloat32Auto($conf['foc_sl_erpm']);

        // 77–78) u8
        $writeUInt8($conf['foc_sample_v0_v7']);
        $writeUInt8($conf['foc_sample_high_current']);

        // 79) f16/1000
        $writeFloat16($conf['foc_sat_comp'], 1000);

        // 80) u8
        $writeUInt8($conf['foc_temp_comp']);

        // 81) f16/100
        $writeFloat16($conf['foc_temp_comp_base_temp'], 100);

        // 82) f16/10000
        $writeFloat16($conf['foc_current_filter_const'], 10000);

        // 83–84) u8
        $writeUInt8($conf['foc_cc_decoupling']);
        $writeUInt8($conf['foc_observer_type']);

        // 85–88) f32
        $writeFloat32Auto($conf['foc_hfi_voltage_start']);
        $writeFloat32Auto($conf['foc_hfi_voltage_run']);
        $writeFloat32Auto($conf['foc_hfi_voltage_max']);
        $writeFloat32Auto($conf['foc_sl_erpm_hfi']);

        // 89) u16
        $writeUInt16($conf['foc_hfi_start_samples']);

        // 90) f32
        $writeFloat32Auto($conf['foc_hfi_obs_ovr_sec']);

        // 91–92) u8
        $writeUInt8($conf['foc_hfi_samples']);
        $writeUInt8($conf['foc_offsets_cal_on_boot']);

        // 93–95) f32 array
        for ($i = 0; $i < 3; $i++) { $writeFloat32Auto($conf['foc_offsets_current'][$i]); }

        // 96–98) f16/10000
        for ($i = 0; $i < 3; $i++) { $writeFloat16($conf['foc_offsets_voltage'][$i], 10000); }

        // 99–101) f16/10000
        for ($i = 0; $i < 3; $i++) { $writeFloat16($conf['foc_offsets_voltage_undriven'][$i], 10000); }

        // 102) u8
        $writeUInt8($conf['foc_phase_filter_enable']);

        // 103) f32
        $writeFloat32Auto($conf['foc_phase_filter_max_erpm']);

        // 104) u8
        $writeUInt8($conf['foc_mtpa_mode']);

        // 105) f32
        $writeFloat32Auto($conf['foc_fw_current_max']);

        // 106–108) f16 fixed
        $writeFloat16($conf['foc_fw_duty_start'],       10000);
        $writeFloat16($conf['foc_fw_ramp_time'],         1000);
        $writeFloat16($conf['foc_fw_q_current_factor'], 10000);

        // 109–110) i16
        $writeInt16($conf['gpd_buffer_notify_left']);
        $writeInt16($conf['gpd_buffer_interpol']);

        // 111) f16/10000
        $writeFloat16($conf['gpd_current_filter_const'], 10000);

        // 112–113) f32
        $writeFloat32Auto($conf['gpd_current_kp']);
        $writeFloat32Auto($conf['gpd_current_ki']);

        // 114) u8
        $writeUInt8($conf['sp_pid_loop_rate']);

        // 115–117) f32
        $writeFloat32Auto($conf['s_pid_kp']);
        $writeFloat32Auto($conf['s_pid_ki']);
        $writeFloat32Auto($conf['s_pid_kd']);

        // 118) f16/10000
        $writeFloat16($conf['s_pid_kd_filter'], 10000);

        // 119) f32
        $writeFloat32Auto($conf['s_pid_min_erpm']);

        // 120) u8
        $writeUInt8($conf['s_pid_allow_braking']);

        // 121) f32
        $writeFloat32Auto($conf['s_pid_ramp_erpms_s']);

        // 122–124) f32
        $writeFloat32Auto($conf['p_pid_kp']);
        $writeFloat32Auto($conf['p_pid_ki']);
        $writeFloat32Auto($conf['p_pid_kd']);

        // 125–126) f32
        $writeFloat32Auto($conf['p_pid_kd_proc']);
        $writeFloat32Auto($conf['p_pid_kd_filter']);

        // 127) f32
        $writeFloat32Auto($conf['p_pid_ang_div']);

        // 128) f16/10
        $writeFloat16($conf['p_pid_gain_dec_angle'], 10);

        // 129) f32
        $writeFloat32Auto($conf['p_pid_offset']);

        // 130–133) f32
        $writeFloat32Auto($conf['cc_startup_boost_duty']);
        $writeFloat32Auto($conf['cc_min_current']);
        $writeFloat32Auto($conf['cc_gain']);
        $writeFloat32Auto($conf['cc_ramp_step_max']);

        // 134) i32
        $writeInt32($conf['m_fault_stop_time_ms']);

        // 135–136) f32
        $writeFloat32Auto($conf['m_duty_ramp_step']);
        $writeFloat32Auto($conf['m_current_backoff_gain']);

        // 137) u32
        $writeUInt32($conf['m_encoder_counts']);

        // 138–140) u8
        $writeUInt8($conf['m_sensor_port_mode']);
        $writeUInt8($conf['m_invert_direction']);
        $writeUInt8($conf['m_drv8301_oc_mode']);

        // 141) u8
        $writeUInt8($conf['m_drv8301_oc_adj']);

        // 142–144) f32
        $writeFloat32Auto($conf['m_bldc_f_sw_min']);
        $writeFloat32Auto($conf['m_bldc_f_sw_max']);
        $writeFloat32Auto($conf['m_dc_f_sw']);

        // 145) f32
        $writeFloat32Auto($conf['m_ntc_motor_beta']);

        // 146–147) u8
        $writeUInt8($conf['m_out_aux_mode']);
        $writeUInt8(
            Helper::convertEnumToIndex(
                $conf['m_motor_temp_sens_type'],
                ConfMcFields::METADATA['m_motor_temp_sens_type']['enums']
            )
        );

        // 148) f32
        $writeFloat32Auto($conf['m_ptc_motor_coeff']);

        // 149–150) u8
        $writeUInt8($conf['m_hall_extra_samples']);
        $writeUInt8($conf['si_motor_poles']);

        // 151–152) f32
        $writeFloat32Auto($conf['si_gear_ratio']);
        $writeFloat32Auto($conf['si_wheel_diameter']);

        // 153–154) u8
        $writeUInt8($conf['si_battery_type']);
        $writeUInt8(
            Helper::convertEnumToIndex(
                $conf['si_battery_cells'],
                ConfMcFields::METADATA['si_battery_cells']['enums']
            ) + 10
        );

        // 155–156) f32
        $writeFloat32Auto($conf['si_battery_ah']);
        $writeFloat32Auto($conf['si_motor_nl_current']);

        // 157) u8
        $writeUInt8($conf['bms']['type']);

        // 158–161) f16
        $writeFloat16($conf['bms']['t_limit_start'],    100);
        $writeFloat16($conf['bms']['t_limit_end'],      100);
        $writeFloat16($conf['bms']['soc_limit_start'], 1000);
        $writeFloat16($conf['bms']['soc_limit_end'],   1000);

        // 162) u8
        $writeUInt8($conf['bms']['fwd_can_mode']);

        return $getBuffer();
    }
}
