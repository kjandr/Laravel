<?php

namespace App\Utils;

use App\Data\ConfMcFields;

class DeserializeMcconf
{
    /**
     * "V1" – dein schlankes Layout.
     * Liest exakt die Felder in der Reihenfolge, wie du sie genutzt hast.
     */
    public static function v1(string $buffer): array
    {
        $r = Helper::createBufferReaders($buffer);

        $readUInt8       = $r['readUInt8'];
        $readInt16       = $r['readInt16'];
        $readUInt16      = $r['readUInt16'];
        $readInt32       = $r['readInt32'];
        $readUInt32      = $r['readUInt32'];
        $readFloat16     = $r['readFloat16'];
        $readFloat32Auto = $r['readFloat32Auto'];
        $readArray       = $r['readArray'];

        $conf = [];

        // 1) Signatur
        $conf['signature'] = $readUInt32();

        // 2) serial_no (16-Byte, als String)
        $conf['serial_no'] = Helper::convertArrayToString($readArray(16));

        // 3..: Felder
        $conf['l_current_max']    = $readFloat32Auto();
        $conf['l_in_current_max'] = $readFloat32Auto();
        $conf['l_in_current_min'] = $readFloat32Auto();
        $conf['l_max_erpm']       = $readFloat32Auto();

        $conf['l_battery_cut_start'] = $readFloat32Auto();
        $conf['l_battery_cut_end']   = $readFloat32Auto();

        $conf['l_temp_fet_start']   = $readFloat16(10);
        $conf['l_temp_fet_end']     = $readFloat16(10);
        $conf['l_temp_motor_start'] = $readFloat16(10);
        $conf['l_temp_motor_end']   = $readFloat16(10);

        $conf['l_watt_max'] = $readFloat32Auto();

        $conf['foc_current_kp'] = $readFloat32Auto();
        $conf['foc_current_ki'] = $readFloat32Auto();
        $conf['foc_f_zv']       = $readFloat32Auto();

        $conf['foc_encoder_inverted'] = $readUInt8();
        $conf['foc_encoder_offset']   = $readFloat32Auto();
        $conf['foc_encoder_ratio']    = $readFloat32Auto();

        $conf['foc_sensor_mode'] = Helper::convertIndexToEnum(
            $readUInt8(),
            ConfMcFields::METADATA['foc_sensor_mode']['enums']
        );

        $conf['foc_motor_l']            = $readFloat32Auto();
        $conf['foc_motor_r']            = $readFloat32Auto();
        $conf['foc_motor_flux_linkage'] = $readFloat32Auto();

        $conf['foc_observer_gain'] = $readFloat32Auto();

        $conf['m_motor_temp_sens_type'] = Helper::convertIndexToEnum(
            $readUInt8(),
            ConfMcFields::METADATA['m_motor_temp_sens_type']['enums']
        );

        // Achtung: hier wird beim Lesen " - 10 " angewandt:
        $conf['si_battery_cells'] = Helper::convertIndexToEnum(
            $readUInt8() - 10,
            ConfMcFields::METADATA['si_battery_cells']['enums']
        );

        $conf['si_battery_ah'] = $readFloat32Auto();

        $conf['foc_sl_erpm'] = $readFloat32Auto();

        return $conf;
    }

    /**
     * "full" – dein komplettes, altes (großes) Layout
     * Portierung aus deiner Funktion deserializeMcconf_old()
     */
    public static function full(string $buffer): array
    {
        $r = Helper::createBufferReaders($buffer);

        $readUInt8       = $r['readUInt8'];
        $readInt16       = $r['readInt16'];
        $readUInt16      = $r['readUInt16'];
        $readInt32       = $r['readInt32'];
        $readUInt32      = $r['readUInt32'];
        $readFloat16     = $r['readFloat16'];
        $readFloat32Auto = $r['readFloat32Auto'];
        $readArray       = $r['readArray'];

        $conf = [];

        // 1. signature
        $conf['signature'] = $readUInt32();

        // 2–5. uint8
        $conf['pwm_mode']    = $readUInt8();
        $conf['comm_mode']   = $readUInt8();
        $conf['motor_type']  = $readUInt8();
        $conf['sensor_mode'] = $readUInt8();

        // 6–12. float32_auto
        $conf['l_current_max']     = $readFloat32Auto();
        $conf['l_current_min']     = $readFloat32Auto();
        $conf['l_in_current_max']  = $readFloat32Auto();
        $conf['l_in_current_min']  = $readFloat32Auto();
        $conf['l_abs_current_max'] = $readFloat32Auto();
        $conf['l_min_erpm']        = $readFloat32Auto();
        $conf['l_max_erpm']        = $readFloat32Auto();

        // 13. f16/10000
        $conf['l_erpm_start'] = $readFloat16(10000);

        // 14–15. f32
        $conf['l_max_erpm_fbrake']    = $readFloat32Auto();
        $conf['l_max_erpm_fbrake_cc'] = $readFloat32Auto();

        // 16–17. f32
        $conf['l_min_vin'] = $readFloat32Auto();
        $conf['l_max_vin'] = $readFloat32Auto();

        // 18–19. f32
        $conf['l_battery_cut_start'] = $readFloat32Auto();
        $conf['l_battery_cut_end']   = $readFloat32Auto();

        // 20. u8
        $conf['l_slow_abs_current'] = $readUInt8();

        // 21–24. f16/10
        $conf['l_temp_fet_start']   = $readFloat16(10);
        $conf['l_temp_fet_end']     = $readFloat16(10);
        $conf['l_temp_motor_start'] = $readFloat16(10);
        $conf['l_temp_motor_end']   = $readFloat16(10);

        // 25–27. f16/10000
        $conf['l_temp_accel_dec'] = $readFloat16(10000);
        $conf['l_min_duty']       = $readFloat16(10000);
        $conf['l_max_duty']       = $readFloat16(10000);

        // 28–29. f32
        $conf['l_watt_max'] = $readFloat32Auto();
        $conf['l_watt_min'] = $readFloat32Auto();

        // 30–32. f16/10000
        $conf['l_current_max_scale'] = $readFloat16(10000);
        $conf['l_current_min_scale'] = $readFloat16(10000);
        $conf['l_duty_start']        = $readFloat16(10000);

        // 33–35. f32
        $conf['sl_min_erpm']                         = $readFloat32Auto();
        $conf['sl_min_erpm_cycle_int_limit']         = $readFloat32Auto();
        $conf['sl_max_fullbreak_current_dir_change'] = $readFloat32Auto();

        // 36. f16/10
        $conf['sl_cycle_int_limit'] = $readFloat16(10);
        // 37. f16/10000
        $conf['sl_phase_advance_at_br'] = $readFloat16(10000);
        // 38–39. f32
        $conf['sl_cycle_int_rpm_br'] = $readFloat32Auto();
        $conf['sl_bemf_coupling_k']  = $readFloat32Auto();

        // 40. hall_table (8×u8)
        $conf['hall_table'] = [];
        for ($i = 0; $i < 8; $i++) { $conf['hall_table'][] = $readUInt8(); }

        // 41. f32
        $conf['hall_sl_erpm'] = $readFloat32Auto();

        // 42–45. f32
        $conf['foc_current_kp'] = $readFloat32Auto();
        $conf['foc_current_ki'] = $readFloat32Auto();
        $conf['foc_f_zv']       = $readFloat32Auto();
        $conf['foc_dt_us']      = $readFloat32Auto();

        // 46. u8
        $conf['foc_encoder_inverted'] = $readUInt8();

        // 47–52. f32
        $conf['foc_encoder_offset']      = $readFloat32Auto();
        $conf['foc_encoder_ratio']       = $readFloat32Auto();
        $conf['foc_encoder_sin_gain']    = $readFloat32Auto();
        $conf['foc_encoder_cos_gain']    = $readFloat32Auto();
        $conf['foc_encoder_sin_offset']  = $readFloat32Auto();
        $conf['foc_encoder_cos_offset']  = $readFloat32Auto();

        // 53. f32
        $conf['foc_encoder_sincos_filter_constant'] = $readFloat32Auto();

        // 54. u8
        $conf['foc_sensor_mode'] = $readUInt8();

        // 55–56. f32
        $conf['foc_pll_kp'] = $readFloat32Auto();
        $conf['foc_pll_ki'] = $readFloat32Auto();

        // 57–60. f32
        $conf['foc_motor_l']            = $readFloat32Auto();
        $conf['foc_motor_ld_lq_diff']   = $readFloat32Auto();
        $conf['foc_motor_r']            = $readFloat32Auto();
        $conf['foc_motor_flux_linkage'] = $readFloat32Auto();

        // 61–62. f32
        $conf['foc_observer_gain']      = $readFloat32Auto();
        $conf['foc_observer_gain_slow'] = $readFloat32Auto();

        // 63. f16/1000
        $conf['foc_observer_offset'] = $readFloat16(1000);

        // 64–65. f32
        $conf['foc_duty_dowmramp_kp'] = $readFloat32Auto();
        $conf['foc_duty_dowmramp_ki'] = $readFloat32Auto();

        // 66. f32
        $conf['foc_openloop_rpm'] = $readFloat32Auto();

        // 67. f16/1000
        $conf['foc_openloop_rpm_low'] = $readFloat16(1000);

        // 68–69. f32
        $conf['foc_d_gain_scale_start']   = $readFloat32Auto();
        $conf['foc_d_gain_scale_max_mod'] = $readFloat32Auto();

        // 70–73. f16/100
        $conf['foc_sl_openloop_hyst']      = $readFloat16(100);
        $conf['foc_sl_openloop_time_lock'] = $readFloat16(100);
        $conf['foc_sl_openloop_time_ramp'] = $readFloat16(100);
        $conf['foc_sl_openloop_time']      = $readFloat16(100);

        // 74. foc_hall_table (8×u8)
        $conf['foc_hall_table'] = [];
        for ($i = 0; $i < 8; $i++) { $conf['foc_hall_table'][] = $readUInt8(); }

        // 75–76. f32
        $conf['foc_hall_interp_erpm'] = $readFloat32Auto();
        $conf['foc_sl_erpm']          = $readFloat32Auto();

        // 77–78. u8
        $conf['foc_sample_v0_v7']        = $readUInt8();
        $conf['foc_sample_high_current'] = $readUInt8();

        // 79. f16/1000
        $conf['foc_sat_comp'] = $readFloat16(1000);

        // 80. u8
        $conf['foc_temp_comp'] = $readUInt8();

        // 81. f16/100
        $conf['foc_temp_comp_base_temp'] = $readFloat16(100);

        // 82. f16/10000
        $conf['foc_current_filter_const'] = $readFloat16(10000);

        // 83–84. u8
        $conf['foc_cc_decoupling'] = $readUInt8();
        $conf['foc_observer_type'] = $readUInt8();

        // 85–88. f32
        $conf['foc_hfi_voltage_start'] = $readFloat32Auto();
        $conf['foc_hfi_voltage_run']   = $readFloat32Auto();
        $conf['foc_hfi_voltage_max']   = $readFloat32Auto();
        $conf['foc_sl_erpm_hfi']       = $readFloat32Auto();

        // 89. u16
        $conf['foc_hfi_start_samples'] = $readUInt16();

        // 90. f32
        $conf['foc_hfi_obs_ovr_sec'] = $readFloat32Auto();

        // 91–92. u8
        $conf['foc_hfi_samples']         = $readUInt8();
        $conf['foc_offsets_cal_on_boot'] = $readUInt8();

        // 93–95. f32 array
        $conf['foc_offsets_current'] = [];
        for ($i = 0; $i < 3; $i++) { $conf['foc_offsets_current'][] = $readFloat32Auto(); }

        // 96–98. f16/10000 array
        $conf['foc_offsets_voltage'] = [];
        for ($i = 0; $i < 3; $i++) { $conf['foc_offsets_voltage'][] = $readFloat16(10000); }

        // 99–101. f16/10000 array
        $conf['foc_offsets_voltage_undriven'] = [];
        for ($i = 0; $i < 3; $i++) { $conf['foc_offsets_voltage_undriven'][] = $readFloat16(10000); }

        // 102. u8
        $conf['foc_phase_filter_enable'] = $readUInt8();

        // 103. f32
        $conf['foc_phase_filter_max_erpm'] = $readFloat32Auto();

        // 104. u8
        $conf['foc_mtpa_mode'] = $readUInt8();

        // 105. f32
        $conf['foc_fw_current_max'] = $readFloat32Auto();

        // 106–108. f16 fixed
        $conf['foc_fw_duty_start']      = $readFloat16(10000);
        $conf['foc_fw_ramp_time']       = $readFloat16(1000);
        $conf['foc_fw_q_current_factor']= $readFloat16(10000);

        // 109–110. i16
        $conf['gpd_buffer_notify_left'] = $readInt16();
        $conf['gpd_buffer_interpol']    = $readInt16();

        // 111. f16/10000
        $conf['gpd_current_filter_const'] = $readFloat16(10000);

        // 112–113. f32
        $conf['gpd_current_kp'] = $readFloat32Auto();
        $conf['gpd_current_ki'] = $readFloat32Auto();

        // 114. u8
        $conf['sp_pid_loop_rate'] = $readUInt8();

        // 115–117. f32
        $conf['s_pid_kp'] = $readFloat32Auto();
        $conf['s_pid_ki'] = $readFloat32Auto();
        $conf['s_pid_kd'] = $readFloat32Auto();

        // 118. f16/10000
        $conf['s_pid_kd_filter'] = $readFloat16(10000);

        // 119. f32
        $conf['s_pid_min_erpm'] = $readFloat32Auto();

        // 120. u8
        $conf['s_pid_allow_braking'] = $readUInt8();

        // 121. f32
        $conf['s_pid_ramp_erpms_s'] = $readFloat32Auto();

        // 122–124. f32
        $conf['p_pid_kp'] = $readFloat32Auto();
        $conf['p_pid_ki'] = $readFloat32Auto();
        $conf['p_pid_kd'] = $readFloat32Auto();

        // 125–126. f32
        $conf['p_pid_kd_proc']   = $readFloat32Auto();
        $conf['p_pid_kd_filter'] = $readFloat32Auto();

        // 127. f32
        $conf['p_pid_ang_div'] = $readFloat32Auto();

        // 128. f16/10
        $conf['p_pid_gain_dec_angle'] = $readFloat16(10);

        // 129. f32
        $conf['p_pid_offset'] = $readFloat32Auto();

        // 130–133. f32
        $conf['cc_startup_boost_duty'] = $readFloat32Auto();
        $conf['cc_min_current']        = $readFloat32Auto();
        $conf['cc_gain']               = $readFloat32Auto();
        $conf['cc_ramp_step_max']      = $readFloat32Auto();

        // 134. i32
        $conf['m_fault_stop_time_ms'] = $readInt32();

        // 135–136. f32
        $conf['m_duty_ramp_step']       = $readFloat32Auto();
        $conf['m_current_backoff_gain'] = $readFloat32Auto();

        // 137. u32
        $conf['m_encoder_counts'] = $readUInt32();

        // 138–140. u8
        $conf['m_sensor_port_mode'] = $readUInt8();
        $conf['m_invert_direction'] = $readUInt8();
        $conf['m_drv8301_oc_mode']  = $readUInt8();

        // 141. u8
        $conf['m_drv8301_oc_adj'] = $readUInt8();

        // 142–144. f32
        $conf['m_bldc_f_sw_min'] = $readFloat32Auto();
        $conf['m_bldc_f_sw_max'] = $readFloat32Auto();
        $conf['m_dc_f_sw']       = $readFloat32Auto();

        // 145. f32
        $conf['m_ntc_motor_beta'] = $readFloat32Auto();

        // 146–147. u8
        $conf['m_out_aux_mode'] = $readUInt8();

        $conf['m_motor_temp_sens_type'] = Helper::convertIndexToEnum(
            $readUInt8(),
            ConfMcFields::METADATA['m_motor_temp_sens_type']['enums']
        );

        // 148. f32
        $conf['m_ptc_motor_coeff'] = $readFloat32Auto();

        // 149–150. u8
        $conf['m_hall_extra_samples'] = $readUInt8();
        $conf['si_motor_poles']       = $readUInt8();

        // 151–152. f32
        $conf['si_gear_ratio']     = $readFloat32Auto();
        $conf['si_wheel_diameter'] = $readFloat32Auto();

        // 153–154. u8
        $conf['si_battery_type']  = $readUInt8();
        $conf['si_battery_cells'] = Helper::convertIndexToEnum(
            $readUInt8() - 10,
            ConfMcFields::METADATA['si_battery_cells']['enums']
        );

        // 155–156. f32
        $conf['si_battery_ah']       = $readFloat32Auto();
        $conf['si_motor_nl_current'] = $readFloat32Auto();

        // 157. BMS type
        $conf['bms'] = [];
        $conf['bms']['type'] = $readUInt8();

        // 158–161. BMS f16
        $conf['bms']['t_limit_start']   = $readFloat16(100);
        $conf['bms']['t_limit_end']     = $readFloat16(100);
        $conf['bms']['soc_limit_start'] = $readFloat16(1000);
        $conf['bms']['soc_limit_end']   = $readFloat16(1000);

        // 162. BMS fwd_can_mode
        $conf['bms']['fwd_can_mode'] = $readUInt8();

        return $conf;
    }
}
