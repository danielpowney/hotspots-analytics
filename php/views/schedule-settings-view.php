<?php 

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'common.php';

class HA_Schedule_Settings_View {
	
	/**
	 * Schedule settings description
	 */
	public static function section_schedule_desc() {
	}
	
	/**
	 * Schedule settings fields
	 */
	public static function field_scheduled_start_date() {
		$schedule_settings = (array) get_option( HA_Common::SCHEDULE_SETTINGS_KEY );
		$scheduled_start_date = $schedule_settings[HA_Common::SCHEDULED_START_DATE_OPTION];
		
		// from server or to user - get_date_from_gmt
		// from user or to server  	get_gmt_from_date
		
		$scheduled_start_time_part = '00:00';
		$scheduled_start_date_part = '';
		if (isset($scheduled_start_date) && ! empty ($scheduled_start_date)) {
			$date_parts = preg_split("/\s/", get_date_from_gmt($scheduled_start_date));
			if (count($date_parts) == 2) {
				$scheduled_start_date_part = $date_parts[0];
				$time_parts = preg_split("/:/", $date_parts[1]);
				if (count($time_parts) >= 2)
					$scheduled_start_time_part = $time_parts[0] . ':' . $time_parts[1];
			}
		}
		?>
		<input type="text" class="date-field" name="<?php echo HA_Common::SCHEDULE_SETTINGS_KEY; ?>[<?php echo HA_Common::SCHEDULED_START_DATE_OPTION; ?>]" value="<?php echo $scheduled_start_date_part; ?>" />&nbsp;(yyyy-MM-dd)<br />
		<input type="text" class="time-field" name="scheduled_start_time_part" value="<?php echo $scheduled_start_time_part; ?>" />&nbsp;(HH:mm - 24 hour format)
		<p class="description">Schedule a start date and time to save mouse clicks and touch screen taps. Leave date input empty to turn off. If turned on, the save mouse clicks and touch screen taps option is ignored until the scheduled start date passes. This option must be enabled for the scheduling to work. The timezone can be configured from the WordPress Settings -> General.</p>
		<?php
	}
	
	public static function field_scheduled_end_date() {
		$schedule_settings = (array) get_option( HA_Common::SCHEDULE_SETTINGS_KEY );
		$scheduled_end_date = $schedule_settings[HA_Common::SCHEDULED_END_DATE_OPTION];
		
		// from server or to user - get_date_from_gmt
		// from user or to server  	get_gmt_from_date
		
		$scheduled_end_time_part = '23:59';
		$scheduled_end_date_part = '';
		if (isset($scheduled_end_date) && ! empty ($scheduled_end_date)) {
			$date_parts = preg_split("/\s/", get_date_from_gmt($scheduled_end_date));
			if (count($date_parts) == 2) {
				$scheduled_end_date_part = $date_parts[0];
				$time_parts = preg_split("/:/", $date_parts[1]);
				if (count($time_parts) >= 2)
					$scheduled_end_time_part = $time_parts[0] . ':' . $time_parts[1];
			}
		}
		?>
		<input type="text" class="date-field" name="<?php echo HA_Common::SCHEDULE_SETTINGS_KEY; ?>[<?php echo HA_Common::SCHEDULED_END_DATE_OPTION; ?>]" value="<?php echo $scheduled_end_date_part ?>" />&nbsp;(yyyy-MM-dd)<br />
		<input type="text" class="time-field" name="scheduled_end_time_part" value="<?php echo $scheduled_end_time_part; ?>" />&nbsp;(HH:mm - 24 hour format)
		<p class="description">Schedule an end date and time to save mouse clicks and touch screen taps. Leave date input empty to turn off. If turned on, the save mouse clicks and touch screen taps option is ignored once the scheduled end date passes. This option must be enabled for the scheduling to work. The timezone can be configured from the WordPress Settings -> General.</p>
		<?php
	}	
	
	/**
	 * Sanitize and validate Schedule settings
	 *
	 * @param unknown_type $input
	 * @return boolean
	 */
	public static function sanitize_schedule_settings($input) {
		// from server or to user - get_date_from_gmt
		// from user or to server  	get_gmt_from_date
		$schedule_start_date = null;
		if (isset( $input[HA_Common::SCHEDULED_START_DATE_OPTION]) && strlen($input[HA_Common::SCHEDULED_START_DATE_OPTION]) > 0) {
			if (HA_Common::check_date_format($input[HA_Common::SCHEDULED_START_DATE_OPTION]) == false) {
				add_settings_error( HA_Common::SCHEDULE_SETTINGS_KEY, 'schedule_start_date_error', 'Scheduled start date invalid format', 'error');
				$input[HA_Common::SCHEDULED_START_DATE_OPTION] = '';
			} else {
				list($year, $month, $day) = explode('-', $input[HA_Common::SCHEDULED_START_DATE_OPTION]);// default yyyy-mm-dd format
	
				// add time part
				$scheduled_start_time_part = $_POST['scheduled_start_time_part'];
				$hour = 0;
				$minute = 0;
				if ( ! preg_match("/([01]?[0-9]|2[0-3]):([0-5][0-9])/", $scheduled_start_time_part)) {
					add_settings_error( HA_Common::SCHEDULE_SETTINGS_KEY, 'scheduled_start_time_part_invalid_format_error', 'Invalid scheduled start time format. Time must be in 24 hour format HH:mm (i.e. 12:30).' , 'error');
					// Default to 0, 0, 0
				} else {
					// set time parts
					list($hour, $minute) = explode(':', $scheduled_start_time_part);
				}
	
				$schedule_start_date = get_gmt_from_date( date("Y-m-d H:i:s", gmmktime($hour, $minute, 0, $month, $day, $year) ) );
				$today = get_gmt_from_date( get_date_from_gmt( date("Y-m-d H:i:s") ) );
	
				if (strtotime($schedule_start_date) <= strtotime($today)) {
					add_settings_error( HA_Common::SCHEDULE_SETTINGS_KEY, 'schedule_start_date_past_error', 'Scheduled start date must be in the future', 'error');
					$input[HA_Common::SCHEDULED_START_DATE_OPTION] = '';
				}
	
				$input[HA_Common::SCHEDULED_START_DATE_OPTION] = $schedule_start_date;
			}
		} else {
			$input[HA_Common::SCHEDULED_START_DATE_OPTION] = "";
		}
	
		if (isset( $input[HA_Common::SCHEDULED_END_DATE_OPTION]) && strlen($input[HA_Common::SCHEDULED_END_DATE_OPTION]) > 0) {
			if (HA_Common::check_date_format($input[HA_Common::SCHEDULED_END_DATE_OPTION]) == false) {
				add_settings_error( HA_Common::SCHEDULE_SETTINGS_KEY, 'schedule_end_date_error', 'Scheduled end date invalid format', 'error');
				$input[HA_Common::SCHEDULED_START_DATE_OPTION] = '';
			} else {
				list($year, $month, $day) = explode('-', $input[HA_Common::SCHEDULED_END_DATE_OPTION]);// default yyyy-mm-dd format
	
				// add time part
				$scheduled_end_time_part = $_POST['scheduled_end_time_part'];
				$hour = 23;
				$minute = 59;
				if ( ! preg_match("/([01]?[0-9]|2[0-3]):([0-5][0-9])/", $scheduled_end_time_part)) {
					add_settings_error( HA_Common::SCHEDULE_SETTINGS_KEY, 'scheduled_end_time_part_invalid_format_error', 'Invalid scheduled end time format. Time must be in 24 hour format HH:mm (i.e. 12:30).' , 'error');
					// Default to 0, 0, 0
				} else {
					// set time parts
					list($hour, $minute) = explode(':', $scheduled_end_time_part);
				}
	
	
				$schedule_end_date = get_gmt_from_date(date("Y-m-d H:i:s", gmmktime($hour, $minute, 0, $month, $day, $year) ) );
				$today = get_gmt_from_date( get_date_from_gmt( date("Y-m-d H:i:s") ) );
	
				if (strtotime($schedule_end_date) <= strtotime($today)) {
					add_settings_error( HA_Common::SCHEDULE_SETTINGS_KEY, 'schedule_end_date_past_error', 'Scheduled end date must be in the future', 'error');
					$input[HA_Common::SCHEDULED_END_DATE_OPTION] = '';
				} else if ($schedule_start_date != null && strtotime($schedule_end_date) <= strtotime($schedule_start_date)) {
					add_settings_error( HA_Common::SCHEDULE_SETTINGS_KEY, 'schedule_end_date_after_start_date_error', 'Scheduled end date must be after the scheduled start date', 'error');
					$input[HA_Common::SCHEDULED_END_DATE_OPTION] = '';
				}
	
				$input[HA_Common::SCHEDULED_END_DATE_OPTION] = $schedule_end_date;
			}
		} else {
			$input[HA_Common::SCHEDULED_END_DATE_OPTION] = "";
		}
	
		return $input;
	}
}

?>