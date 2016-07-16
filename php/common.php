<?php 
/**
 * Common class
 * 
 * @author dpowney
 *
 */
class HA_Common {
	
	// URL query params which are ignored by the plugin
	public static $ignore_query_params = array( 'drawHeatMap', 'KEY', 'XDEBUG_SESSION_START', 'userEventId', 'pageWidth', 'device', 'browser', 'os' );
	
	
	const
	PLUGIN_ID 							= 'ha',
	CONFIG_DATA 						= 'config_data',
	PLUGIN_VERSION						= '4.0.10',
	
	// WordPress menu pages
	MENU_PAGE_SLUG						= 'ha_menu_page',
	HEATMAPS_PAGE_SLUG					= 'ha_heatmaps_page',
	USERS_PAGE_SLUG						= 'ha_users_page',
	REPORTS_PAGE_SLUG					= 'ha_reports_page',
	SETTINGS_PAGE_SLUG					= 'ha_settings_page',
	
	// WordPress tabs
	GENERAL_SETTINGS_TAB 				= 'ha_general_settings_tab',
	HEAT_MAP_SETTINGS_TAB				= 'ha_heat_map_settings_tab',
	SCHEDULE_SETTINGS_TAB				= 'ha_schedule_settings_tab',
	DATABASE_SETTINGS_TAB				= 'ha_database_settings_tab',
	CUSTOM_EVENTS_SETTINGS_TAB			= 'ha_custom_events_settings_tab',
	URL_FILTERS_SETTINGS_TAB 			= 'ha_url_filters_settings_tab',
	HEAT_MAPS_TAB 						= 'ha_heat_maps_tab',
	USERS_TAB							= 'ha_users_tab',
	ELEMENT_SETTINGS_TAB				= 'ha_elements_tab',
	REPORTS_TAB							= 'ha_reports_tab',
	USER_ACTIVITY_TAB 					= 'ha_user_activity_tab',
	EVENT_STATISTICS_TABLE_REPORT_TAB	= 'ha_event_statistics_table_report_tab',
	EVENT_TOTALS_BAR_GRAPH_REPORT_TAB	= 'ha_event_totals_bar_graph_report_tab',
	EVENT_COMPARISON_LINE_GRAPH_REPORT_TAB	= 'ha_event_comparison_line_graph_report_tab',
	EVENT_LINE_GRAPH_REPORT_TAB			= 'ha_event_line_graph_report_tab',
	
	// WordPress settings
	GENERAL_SETTINGS_KEY 				= 'ha_general_settings',
	ADVANCED_SETTINGS_KEY				= 'ha_advanced_settings',
	URL_FILTERS_SETTINGS_KEY 			= 'ha_url_filters_settings',
	HEAT_MAP_SETTINGS_KEY 				= 'ha_heat_map_settings_key',
	SCHEDULE_SETTINGS_KEY				= 'ha_schedule_settings_key',
	DATABASE_SETTINGS_KEY				= 'ha_database_settings_key',
	
	// WordPress otions
	SAVE_CLICK_TAP_OPTION 				= 'ha_save_click_tap',
	DRAW_HEAT_MAP_ENABLED_OPTION 		= 'ha_draw_heat_map_enabled',
	DEBUG_OPTION 						= 'ha_debug_option',
	HOT_VALUE_OPTION 					= 'ha_hot_value',
	SPOT_OPACITY_OPTION 				= 'ha_spot_opacity',
	SPOT_RADIUS_OPTION 					= 'ha_spot_radius',
	FILTER_TYPE_OPTION					= 'ha_filter_type',
	APPLY_URL_FILTERS_OPTION			= 'ha_apply_url_filters',
	USE_HEATMAPJS_OPTION				= 'ha_use_heatmapjs',
	IGNORE_WIDTH_OPTION					= 'ha_ignore_width',
	IGNORE_DEVICE_OPTION				= 'ha_ignore_device',
	IGNORE_BROWSER_OPTION				= 'ha_ignore_browser',
	IGNORE_OS_OPTION					= 'ha_ignore_os',
	URL_DB_LIMIT_OPTION					= 'ha_url_db_limit',
	WIDTH_ALLOWANCE_OPTION				= 'ha_width_allowance',
	SCHEDULED_START_DATE_OPTION			= 'ha_schedule_start_date',
	SCHEDULED_END_DATE_OPTION			= 'ha_schedule_end_date',
	SCHEDULED_SAVE_CLICK_TAP			= 'ha_schedule_save_click_tap',
	HIDE_ROLES_OPTION					= 'ha_hide_roles',
	SAVE_AJAX_ACTIONS_OPTION			= 'ha_save_ajax_actions',
	SAVE_CUSTOM_EVENTS_OPTION			= 'ha_save_custom_events',
	SAVE_PAGE_VIEWS_OPTION				= 'ha_save_page_views',
	URL_FILTERS_LIST_OPTION				= 'ha_url_filters_list',
	
	PLUGIN_VERSION_OPTION				= 'plugin_version',
	
	// filters
	START_DATE_SEARCH_FILTER			= 'ha_start_date_filter',
	END_DATE_SEARCH_FILTER				= 'ha_end_date_filter',
	URL_SEARCH_FILTER					= 'ha_url_filter',
	IP_ADDRESS_FILTER					= 'ha_ip_address_filter',
	
	// inputs
	URL_SEARCH_INPUT					= 'ha_url',
	ELEMENT_SELECTOR_INPUT				= 'ha_element_selector',
	NAME_INPUT							= 'ha_name',
	
	// values
	NO_ROLE_VALUE						= "none",
	WHITELIST_VALUE						= 'whitelist',
	BLACKLIST_VALUE						= 'blacklist',
	
	MOUSE_CLICK_EVENT_TYPE				= 'mouse_click',
	TOUCHSCREEN_TAP_EVENT_TYPE			= 'touchscreen_tap',
	PAGE_VIEW_EVENT_TYPE				= 'page_view',
	AJAX_ACTION_EVENT_TYPE				= 'ajax_action',
	
	/*
	 * User table: Id, IP Address, Session ID, Username, Role, HTTP User Agent, Last Updt Date
	*
	* User Environment table: ID, User ID, Page Width
	* Browser, Device, Operating System, Last Updt Date
	*
	* User Event table: ID, User ID, User Env ID, Type, Record Date, URL, Description,
	* X Coord, Y Coord, Last Updt Date, Is Tap, Data
	*/
	
	// tables
	USER_TBL_NAME 						= 'ha_user',
	USER_ENV_TBL_NAME					= 'ha_user_environment',
	USER_EVENT_TBL_NAME					= 'ha_user_event',
	
	CUSTOM_EVENT_TBL_NAME				= 'ha_custom_event',
	
	// columns
	ID_COLUMN							= 'id',
	SESSION_ID_COLUMN					= 'session_id',
	USER_ROLE_COLUMN					= 'role',
	USERNAME_COLUMN						= 'username',
	LAST_UPDT_DATE_COLUMN				= 'last_updt_date',
	IP_ADDRESS_COLUMN					= 'ip_address',
	
	USER_ID_COLUMN						= 'user_id',
	PAGE_WIDTH_COLUMN					= 'page_width',
	BROWSER_COLUMN						= 'browser',
	DEVICE_COLUMN						= 'device',
	OS_COLUMN							= 'os',
	HTTP_USER_AGENT_COLUMN				= 'http_user_agent',
	
	USER_ENV_ID_COLUMN					= 'user_env_id',
	X_COORD_COLUMN						= 'x_coord',
	Y_COORD_COLUMN						= 'y_coord',
	RECORD_DATE_COLUMN					= 'record_date',
	URL_COLUMN							= 'url',
	DESCRIPTION_COLUMN					= 'description',
	DATA_COLUMN							= 'data',
	EVENT_TYPE_COLUMN					= 'event_type',
	CUSTOM_EVENT_COLUMN					= 'custom_event',
	IS_MOUSE_CLICK_COLUMN				= 'is_mouse_click',
	IS_TOUCHSCREEN_TAP_COLUMN			= 'is_touchscreen_tap',
	TOTAL_COLUMN						= 'total',
	AVG_PER_USER_COLUMN					= 'avg_per_user',
	AVG_PER_URL_COLUMN					= 'avg_per_url',
	
	
	ELEMENT_SELECTOR_COLUMN				= 'element_selector',
	NAME_COLUMN							= 'name',
	IS_FORM_SUBMIT_COLUMN				= 'is_form_submit';
	
	
	/**
	 * Gets the client IP address
	 */
	public static function get_ip_address() {
		$ip_address = '';
		if ( isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'])
			$ip_address = $_SERVER['HTTP_CLIENT_IP'];
		else if ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'])
			$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if ( isset($_SERVER['HTTP_X_FORWARDED']) && $_SERVER['HTTP_X_FORWARDED'] )
			$ip_address = $_SERVER['HTTP_X_FORWARDED'];
		else if ( isset($_SERVER['HTTP_FORWARDED_FOR']) && $_SERVER['HTTP_FORWARDED_FOR'] )
			$ip_address = $_SERVER['HTTP_FORWARDED_FOR'];
		else if ( isset($_SERVER['HTTP_FORWARDED']) && $_SERVER['HTTP_FORWARDED'] )
			$ip_address = $_SERVER['HTTP_FORWARDED'];
		else if ( ISSET($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] )
			$ip_address = $_SERVER['REMOTE_ADDR'];
	
		return $ip_address;
	}
	
	public static function update_session() {
	
	}
	
	/**
	 * Gets the current URL
	 *
	 * @return current URL
	 */
	public static function get_current_url() {
		$url = 'http';
		if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
			$url .= "s";
		}
		$url .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$url .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
	
		return HA_Common::normalize_url($url);
	}
	
	/**
	 * Normalizes the URL (some of the best parts of RFC 3986)
	 *
	 * @param unknown_type $url
	 * @return string
	 */
	public static function normalize_url($url) {
		
		// TODO return error for bad URLs
		
		// Process from RFC 3986 http://en.wikipedia.org/wiki/URL_normalization
	
		// Limiting protocols.
		if ( !parse_url( $url, PHP_URL_SCHEME ) ) {
			$url = 'http://' . $url;
		}
	
		$parsed_url = parse_url( $url );
		if ($parsed_url === false)
			return '';
	
		// user and pass components are ignored
	
		// TODO Removing or adding “www” as the first domain label.
		$host = preg_replace('/^www\./', '', $parsed_url['host']);
	
		// Converting the scheme and host to lower case
		$scheme = strtolower($parsed_url['scheme']);
		$host = strtolower($host);
	
		$path = $parsed_url['path'];
		// TODO Capitalizing letters in escape sequences
		// TODO Decoding percent-encoded octets of unreserved characters
	
		// Removing the default port
		$port = '';
		if (isset($parsed_url['port']))
			$port = $parsed_url['port'];
		if ($port == 80)
			$port = '';
	
		// Removing the fragment # (do not get fragment component)
	
		// Removing directory index (i.e. index.html, index.php)
		$path = str_replace('index.html', '', $path);
		$path = str_replace('index.php', '', $path);
	
		// Adding trailing /
		$path_last_char = $path[strlen($path)-1];
		if ( $path_last_char != '/' )
			$path = $path . '/';
	
		// TODO Removing dot-segments.
	
		// TODO Replacing IP with domain name.
	
		// TODO Removing duplicate slashes
		$path = preg_replace("~\\\\+([\"\'\\x00\\\\])~", "$1", $path);
	
		// construct URL
		$url =  $scheme . '://' . $host . $path;
	
		// Add query params if they exist
		// Sorting the query parameters.
		// Removing unused query variables
		// Removing default query parameters.
		// Removing the "?" when the query is empty.
		$query = '';
		if (isset($parsed_url['query']))
			$query = $parsed_url['query'];
		if ($query) {
			$query_parts = explode('&', $query);
			$params = array();
			foreach ($query_parts as $param) {
				$items = explode('=', $param, 2);
				$name = $items[0];
				$value = '';
				if (count($items) == 2)
					$value = $items[1];
				$params[$name] = $value;
			}
			ksort($params);
			$count_params = count($params);
			if ($count_params > 0) {
				$url .= '?';
				$index = 0;
				foreach ($params as $name => $value) {
					$url .= $name;
					if (strlen($value) != 0)
						$url .= '=' . $value;
					if ($index++ < ($count_params - 1))
						$url .= '&';
				}
			}
		}
	
		// Remove some query params which we do not want
		$url = HA_Common::remove_query_string_params( $url, apply_filters( 'ha_ignore_query_params', HA_Common::$ignore_query_params ) );
	
		return apply_filters( 'ha_url' , $url );
	}
	
	/**
	 * Removes query string parameters from URL
	 * @param $url
	 * @param $param
	 * @return string
	 *
	 * @since 1.2
	 */
	public static function remove_query_string_params( $url, $params ) {
		foreach ( $params as $param ) {
			$url = preg_replace( '/(.*)(\?|&)' . $param . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&' );
			$url = substr( $url, 0, -1 );
		}
		return $url;
	}
	
	/**
	 * Helper function to get the highest common factor. Can be used recursively.
	 *
	 * @param unknown_type $a
	 * @param unknown_type $b
	 * @return unknown
	 */
	public static function highest_common_factor($a, $b) {
		if ($b==0)
			return $a;
		return HA_Common::highest_common_factor($b, $a % $b);
	}
	
	/**
	 * Converts a decimal to a fraction that can be returned as a ratio
	 *
	 * @param decimal i.e. 1.75
	 */
	public static function convert_decimalto_ratio($decimal) {
		$decimal = strval($decimal);
	
		$decimal_array = explode('.', $decimal);
	
		// if a whole number
		if (count($decimal_array) !== 2) {
			return $decimal . ':1';
		} else {
			$left_decimal_part = $decimal_array[0]; // 1
			$right_decimal_part = $decimal_array[1]; // 75
	
			$numerator = $left_decimal_part . $right_decimal_part; // 175
			$denominator = pow(10,strlen($right_decimal_part)); // 100
			$factor = HA_Common::highest_common_factor($numerator, $denominator); // 25
			$denominator /= $factor;
			$numerator /= $factor;
	
			return $numerator . ':' . $denominator;
		}
	}
	
	/**
	 * Checks if date format is valid yyyy-mm-dd
	 * @param unknown_type $date
	 * @return boolean
	 */
	public static function check_date_format($date) {
		list($yyyy, $mm, $dd) = explode('-',$date);
		return checkdate($mm,$dd,$yyyy);
	}
	
	/**
	 * Calculates the heat value given closeness of existing mouse clicks
	 * or touch screen taps
	 *
	 * @param x
	 * @param y
	 * @param id
	 * @param rows
	 * @param spot_radius
	 */
	public static function calculate_heat_value($x_coord, $y_coord, $id, $rows, $spot_radius) {
		$heat_value = 0;
	
		foreach ($rows as $row) {
			$current_x = $row->x_coord;
			$current_y = $row->y_coord;
			$current_id = $row->id;
	
			// skip if comparing the same click or tap
			if ($id == $current_id) {
				continue;
			}
	
			// Check if the spot is touching other spots
			$diff_x = $x_coord - $current_x;
			$diff_y = $y_coord - $current_y;
			$hot_x = ($diff_x > - $spot_radius && $diff_x < $spot_radius);
			$hot_y = ($diff_y > - $spot_radius && $diff_y < $spot_radius);
			if ($hot_x && $hot_y) {
				$heat_value++;
			}
		}
		return $heat_value;
	}
	
	/**
	 * Gets logged in WordPress user username and role
	 *
	 * @return role and username
	 */
	public static function get_wp_user_details() {
		global $wp_roles;
		$current_user = wp_get_current_user();
		$roles = $current_user->roles;
		$role = array_shift($roles);
		$username = $current_user->user_login;
	
		return array('user_role' => $role, 'username' => $username);
	}
	
	/**
	 * Gets the user details by ip address and session id
	 * @param unknown_type $ip_address
	 * @param unknown_type $session_id
	 * @param unknown_type $create_if_empty
	 */
	public static function get_user_details( $ip_address, $session_id, $create_if_empty, $data_services ) {
		
		$wp_user_details = HA_Common::get_wp_user_details();
		$current_time = current_time('mysql');
		$user_role =  $wp_user_details['user_role'];
		$username = $wp_user_details['username'];
		$user_id = '';
		
		if ( $data_services != null) {
			
			$params = array(
					'ip_address' => $ip_address,
					'session_id' => $session_id,
					'create_if_empty' => $create_if_empty,
					'current_time' => $current_time,
					'user_role' => $user_role,
					'username' => $username
			);
		
			$data = $data_services->custom_query('add_retrieve_user_details', $params);
			$user_id = $data->user_id;
		}
		
		return array(
				'user_id' => $user_id,
				'ip_address' => $ip_address,
				'session_id' => $session_id,
				'user_role' => $user_role,
				'username' => $username
		);
	}
	
	/**
	 * Gets the current user environment details by user id
	 * @param unknown_type $user_id
	 * @param unknown_type $create_if_empty
	 * @return multitype:unknown
	 */
	public static function get_user_environment_details($user_id, $create_if_empty, $data_services) {
		
		$ua = $_SERVER['HTTP_USER_AGENT'];
		$parser = new UAParser();
		$result = $parser->parse($ua);
		
		$browser =  $result->ua->family . ' ' . $result->ua->major;
		if ($result->ua->minor) {
			$browser .=  '.' . $result->ua->minor;
		}
		$device = $result->device->family;
		$os = $result->os->family . ' ' . $result->os->major;
		if ($result->os->minor) {
			$os .= '.' . $result->os->minor;
		}
		
		$user_environment_id = '';
		$current_time = current_time('mysql');
		
		// don't insert if user_id has not been provided
		if ( $data_services != null && $user_id != null ) {
			$params = array(
					'user_id' => $user_id,
					'create_if_empty' => $create_if_empty,
					'browser' => $browser,
					'os' => $os,
					'device' => $device,
					'current_time' => $current_time
			);
			
			$data = $data_services->custom_query('add_retrieve_user_environment_details', $params);			
			$user_environment_id = $data->user_environment_id;
		}
	
		return array(
				'user_environment_id' => $user_environment_id,
				'os' => $os,
				'device' => $device,
				'browser' => $browser
		);
	}
	
	/**
	 * A more accurate hum_time_diff function than the one inbuilt with WordPress
	 *
	 * @param $from_date
	 * @param $to_date
	 * @return $human_time_diff
	 */
	public static function human_time_diff($from_date, $to_date) {
		$human_time_diff = '';
		$time_diff = $to_date - $from_date;
		$mins_diff = intval( ( $time_diff ) / 60 );
		$seconds_diff = ( $time_diff ) % 60;
		$hours_diff = 0;
		if ($mins_diff > 0)
			$hours_diff = intval( $mins_diff / 60);
	
		// days are not necessary
	
		// hours first
		if ($hours_diff > 0) {
			// must subtract here otherwise the minutes is not right
			$mins_diff -= $hours_diff * 60;
	
			$human_time_diff .= $hours_diff . ' hour';
			if ($human_time_diff != 1)
				$human_time_diff .= 's';
			if ($seconds_diff > 0 || $mins_diff > 0) {
				if (($seconds_diff > 0 && $hours_diff == 0)
						|| ($seconds_diff == 0 && $hours_diff > 0))
					$human_time_diff .= ' and ';
				else
					$human_time_diff .= ', ';
			}
		}
	
		// then minutes
		if ($mins_diff > 0) {
			$human_time_diff .= $mins_diff . ' minute';
			if ($mins_diff != 1)
				$human_time_diff .= 's';
			if ($seconds_diff > 0 )
				$human_time_diff .= ' and ';
		}
	
		// then seconds
		if ($seconds_diff > 0) {
			$human_time_diff .= $seconds_diff .= ' second';
			if ($seconds_diff != 1)
				$human_time_diff .= 's';
		}
	
		if (strlen($human_time_diff) == 0) {
			$human_time_diff .= '< 1 second';
		}
	
		return $human_time_diff;
	}
}

?>