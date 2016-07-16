<?php 

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'common.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'uaparser' . DIRECTORY_SEPARATOR . 'uaparser.php';
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'services' . DIRECTORY_SEPARATOR . 'local-data-services.php';


/**
 * Frontend Controller
 *
 * @author dpowney
 *
 */
class HA_Frontend_Controller {

	private $ignore_ajax_actions = array('save_user_event', 'retrieve_user_events');
	
	/**
	 * Constructor
	 *
	 * @since 2.4
	 */
	function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'assets' ) );
	}


	/**
	 * Javascript and CSS used by the
	 *
	 * @since 2.0
	 */
	public function assets(){
		wp_enqueue_script( 'jquery' );

		$root_relative_path = '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;

		wp_enqueue_style( 'ha_frontend-style' , plugins_url( $root_relative_path . 'css' . DIRECTORY_SEPARATOR . 'frontend.css', __FILE__ ) );
		wp_enqueue_script( 'ha_heatmap', plugins_url( $root_relative_path . 'js' .  DIRECTORY_SEPARATOR . 'heatmap' . DIRECTORY_SEPARATOR . 'heatmap.js', __FILE__ ), array(), false, true );
		wp_enqueue_script( 'ha_utils', plugins_url( $root_relative_path . 'js' . DIRECTORY_SEPARATOR . 'utils.js', __FILE__ ), array( 'jquery', 'ha_heatmap' ), false, true );
		wp_enqueue_script( 'ha_drawing', plugins_url( $root_relative_path . 'js' . DIRECTORY_SEPARATOR . 'drawing.js', __FILE__ ), array( 'jquery', 'ha_heatmap', 'ha_utils' ), false, true );
		wp_enqueue_script( 'ha_events', plugins_url( $root_relative_path . 'js' . DIRECTORY_SEPARATOR . 'events.js', __FILE__ ), array( 'jquery', 'ha_heatmap', 'ha_utils', 'ha_drawing' ), false, true );
		wp_enqueue_script( 'ha_frontend-script', plugins_url( $root_relative_path . 'js' . DIRECTORY_SEPARATOR . 'frontend.js', __FILE__ ), array( 'jquery', 'ha_heatmap', 'ha_utils', 'ha_drawing', 'ha_events' ), false, true );

		// for loading dialog
		wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_style('jquery-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
		
		$config_array = $this->construct_config_array();

		wp_localize_script( 'ha_frontend-script', HA_Common::CONFIG_DATA, $config_array );
	}


	/**
	 * Constructs the frontend config array
	 * @return config array
	 */
	function construct_config_array() {

		$current_url = HA_Common::get_current_url();	

		$config_array = array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'ajax_nonce' => wp_create_nonce( HA_Common::PLUGIN_ID.'-nonce' ),
				'ignore_ajax_actions' => $this->ignore_ajax_actions,
				'plugin_version' => HA_Common::PLUGIN_VERSION
		);

		$ip_address = HA_Common::get_ip_address();
		$session_id = session_id();
		
		// get or create user details and user environment details
		$user_details = HA_Common::get_user_details($ip_address, $session_id, false, null);
		$user_environment_details = HA_Common::get_user_environment_details($user_details['user_id'], false, null);
		
		$config_array = array_merge($config_array, $user_environment_details);
		$config_array = array_merge($config_array, $user_details);
		
		$config_array = array_merge($config_array, $this->get_custom_events($current_url));
		$config_array = array_merge($config_array, $this->get_schedule_check());
		$config_array = array_merge($config_array, $this->get_url_excluded($current_url));
		$config_array = array_merge($config_array, $this->get_general_settings());
		$config_array = array_merge($config_array, $this->get_heat_map_settings());
		$config_array = array_merge($config_array, $this->get_url_db_limit_check($current_url));
		$config_array = array_merge($config_array, $this->get_url_filters_settings());

		return $config_array;
	}

	
	/**
	 * Gets user id and user environment id from tables based on session ID and IP address
	 * @return 
	 */

	/**
	 * Check URL db limit option
	 */
	function get_url_db_limit_check($url) {
		$database_settings = get_option(HA_Common::DATABASE_SETTINGS_KEY);


		// check URL db limit option
		$url_db_limit = $database_settings[ HA_Common::URL_DB_LIMIT_OPTION ];
		$url_db_limit_reached = 0;
		if ( $url_db_limit != '' ) {
			global $wpdb;
			$query = 'SELECT * FROM '. $wpdb->prefix.HA_Common::USER_EVENT_TBL_NAME . ' WHERE ' . HA_Common::URL_COLUMN . ' = "' . $url . '"';
			$wpdb->query( $query );
			$count = $wpdb->num_rows;
			if ( $count >= $url_db_limit ) {
				$url_db_limit_reached = 1;
			}
		}

		return array('url_db_limit_reached' => $url_db_limit_reached);
	}

	/**
	 * Gets URL filters settings
	 * @return
	 */
	function get_url_filters_settings() {
		$url_filter_settings = get_option(HA_Common::URL_FILTERS_SETTINGS_KEY);

		return array('filter_type' => $url_filter_settings[ HA_Common::FILTER_TYPE_OPTION ]);
	}

	/**
	 * Check options if applying filters
	 */
	function get_url_excluded($current_url) {
		$url_filter_settings = get_option(HA_Common::URL_FILTERS_SETTINGS_KEY);
		$apply_URL_filters = $url_filter_settings[ HA_Common::APPLY_URL_FILTERS_OPTION ];

		$general_settings = get_option(HA_Common::GENERAL_SETTINGS_KEY);
		$draw_heat_map_enabled = $general_settings[ HA_Common::DRAW_HEAT_MAP_ENABLED_OPTION ];
		$save_click_or_tap_enabled = $general_settings[ HA_Common::SAVE_CLICK_TAP_OPTION ];

		$url_excluded = 0;
		// Also check if at least one of the options is true to improve performance
		if ( $apply_URL_filters == true && ( $draw_heat_map_enabled == true || $save_click_or_tap_enabled == true ) ) {
			// check if enabled
			$filter_type = $url_filter_settings[ HA_Common::FILTER_TYPE_OPTION ];

			// get url list
			$url_filters_list = preg_split("/[\r\n,]+/", $url_filter_settings[HA_Common::URL_FILTERS_LIST_OPTION], -1, PREG_SPLIT_NO_EMPTY);

			if ( $filter_type == HA_Common::BLACKLIST_VALUE ) { // excludes
				foreach ($url_filters_list as $url) {
					$url = trim($url, '&#13;&#10;');
					
					// If it's in the blacklist, we disable the options
					if ( $url == $current_url ) {
						$url_excluded = 1;
						break;
					}
				}
			} else { // whitelist (includes)
				// check if the current url is in the whitelist
				$found = false;
				foreach ($url_filters_list as $url) {
					$url = trim($url, '&#13;&#10;');
					
					// If it's not in the whitelist, we disable the options
					if ( $url == $current_url ) {
						$found = true;
						break;
					}
				}

				if ( $found == false ) {
					$url_excluded = 1;
				}
			}
		}
		return array('url_excluded' => $url_excluded);
	}

	/**
	 * Returns custom events for the current URL
	 */
	function get_custom_events($url) {
		global $wpdb;
		$custom_events = array();
		$query = 'SELECT * FROM ' . $wpdb->prefix.HA_Common::CUSTOM_EVENT_TBL_NAME . ' WHERE ' . HA_Common::URL_COLUMN. ' = "' . $url . '" OR ' . HA_Common::URL_COLUMN . ' = ""';
		$rows = $wpdb->get_results($query);
		foreach ($rows as $row) {
			array_push($custom_events, array(
					'custom_event' => $row->custom_event, 
					'description' => $row->description, 
					'event_type' => $row->event_type, 
					'is_form_submit' => $row->is_form_submit,
					'is_touchscreen_tap' => $row->is_touchscreen_tap,
					'is_mouse_click' => $row->is_mouse_click));
		}

		return array('custom_events' => $custom_events);
	}

	/**
	 * Check if there's a scheduled start date or end date which overrides save clicks and taps option
	 * @return
	 */
	function get_schedule_check() {
		$schedule_settings = get_option(HA_Common::SCHEDULE_SETTINGS_KEY);

		$schedule_check = 1;
		// from server or to user - get_date_from_gmt
		// from user or to server  	get_gmt_from_date
		$today = strtotime( get_gmt_from_date( get_date_from_gmt( date("Y-m-d H:i:s") ) ) );

		// scheduled start date
		$scheduled_start_date = $schedule_settings[ HA_Common::SCHEDULED_START_DATE_OPTION ];
		if ( isset($scheduled_start_date) && ! empty( $scheduled_start_date ) ) {

			$scheduled_start_date_parts = explode(' ', get_date_from_gmt( $scheduled_start_date) );
			if (count($scheduled_start_date_parts) == 2) {
				list($year, $month, $day) = explode('-', $scheduled_start_date_parts[0]);
				list($hour, $minute, $seconds) = explode(':', $scheduled_start_date_parts[1]);

				$scheduled_start_date = strtotime(get_gmt_from_date(date("Y-m-d H:i:s",  gmmktime($hour, $minute, $seconds, $month, $day, $year) ) ) );
				if ($today < $scheduled_start_date) {
					$schedule_check = 0;
				}
			}
			// else no scheduled start date or invalid date/time format
		}

		// scheduled end date
		$scheduled_end_date = $schedule_settings[ HA_Common::SCHEDULED_END_DATE_OPTION ];
		if ( $scheduled_start_date != 0 && isset($scheduled_end_date) && ! empty($scheduled_end_date) ) {

			$scheduled_end_date_parts = explode(' ', get_date_from_gmt( $scheduled_end_date) );
			if (count($scheduled_end_date_parts) == 2) {
				list($year, $month, $day) = explode('-',$scheduled_end_date_parts[0]);
				list($hour, $minute, $seconds) = explode(':', $scheduled_end_date_parts[1]);
					
				$scheduled_end_date = strtotime(get_gmt_from_date(date("Y-m-d H:i:s",  gmmktime($hour, $minute, $seconds, $month, $day, $year) ) ) );
				if ($today > $scheduled_end_date) {
					$schedule_check = 0;
				}
			}
			// else no scheduled end date or invalid date/time format
		}
		return array('schedule_check' => $schedule_check);
	}

	function get_general_settings() {
		$general_settings = get_option(HA_Common::GENERAL_SETTINGS_KEY);

		return array(
				'draw_heat_map_enabled' => $general_settings[ HA_Common::DRAW_HEAT_MAP_ENABLED_OPTION ],
				'save_click_or_tap_enabled' => $general_settings[ HA_Common::SAVE_CLICK_TAP_OPTION ],
				'debug' => $general_settings[ HA_Common::DEBUG_OPTION ],
				'save_ajax_actions' =>  $general_settings[ HA_Common::SAVE_AJAX_ACTIONS_OPTION ],
				'save_custom_events' => $general_settings[ HA_Common::SAVE_CUSTOM_EVENTS_OPTION ],
				'save_page_views' => $general_settings[ HA_Common::SAVE_PAGE_VIEWS_OPTION ]
		);

	}

	function get_heat_map_settings() {
		$heat_map_settings = get_option(HA_Common::HEAT_MAP_SETTINGS_KEY);

		return array(
				'hot_value' => $heat_map_settings[HA_Common::HOT_VALUE_OPTION],
				'spot_opacity' =>  $heat_map_settings[ HA_Common::SPOT_OPACITY_OPTION ],
				'spot_radius' =>  $heat_map_settings[ HA_Common::SPOT_RADIUS_OPTION ],
				'use_heatmapjs' => $heat_map_settings[ HA_Common::USE_HEATMAPJS_OPTION ],
				'ignore_width' => $heat_map_settings[ HA_Common::IGNORE_WIDTH_OPTION ],
				'width_allowance' => $heat_map_settings[ HA_Common::WIDTH_ALLOWANCE_OPTION ],
				'ignore_device' => $heat_map_settings[ HA_Common::IGNORE_DEVICE_OPTION ],
				'ignore_os' => $heat_map_settings[ HA_Common::IGNORE_OS_OPTION ],
				'ignore_browser' => $heat_map_settings[ HA_Common::IGNORE_BROWSER_OPTION ],
				'hide_roles' => $heat_map_settings[ HA_Common::HIDE_ROLES_OPTION ]
		);
	}

}

?>