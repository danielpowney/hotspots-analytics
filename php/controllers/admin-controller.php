<?php 

require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'common.php';
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'general-settings-view.php';
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'schedule-settings-view.php';
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'heatmap-settings-view.php';
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'url-filters-settings-view.php';
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'database-settings-view.php';
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'admin-page-view.php';
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'services' . DIRECTORY_SEPARATOR . 'local-data-services.php';

/**
 * Admin class
 *
 * @author dpowney
 *
 */
class HA_Admin_Controller {
	
	public $settings_tabs = array();
	public $users_tabs = array();
	public $reports_tabs = array();
	
	
	public $general_settings = array();
	public $heat_map_settings = array();
	public $schedule_settings = array();
	public $database_settings = array();
	private $url_filters_settings = array();
	
	private $data_services = null;
	
	public function set_data_services(&$data_services) {
		$this->data_services = $data_services;
	}
	public function get_data_services() {
		return $this->data_services;
	}

	/**
	 * Constructor
	 *
	 * @since 2.4
	 */
	function __construct() {
		// Settings
		add_action('init', array( &$this, 'load_settings' ) );
		add_action('init', array( &$this, 'start_session'), 1);
		add_action('wp_logout', array( &$this, '') );
		add_action('wp_login', array( &$this, '') );

		add_action( 'admin_init', array( &$this, 'register_general_settings' ) );
		add_action( 'admin_init', array( &$this, 'register_schedule_settings' ) );
		add_action( 'admin_init', array( &$this, 'register_heat_map_settings' ) );
		add_action( 'admin_init', array( &$this, 'register_url_filters_settings' ) );
		add_action( 'admin_init', array( &$this, 'register_database_settings' ) );

		$this->settings_tabs[HA_Common::GENERAL_SETTINGS_TAB] = 'General';
		$this->settings_tabs[HA_Common::SCHEDULE_SETTINGS_TAB] = 'Schedule';
		$this->settings_tabs[HA_Common::HEAT_MAP_SETTINGS_TAB] = 'Heatmap';
		$this->settings_tabs[HA_Common::URL_FILTERS_SETTINGS_TAB] = 'URL Filters';
		$this->settings_tabs[HA_Common::DATABASE_SETTINGS_TAB] = 'Database';
		$this->settings_tabs[HA_Common::CUSTOM_EVENTS_SETTINGS_TAB] = 'Custom Events';
		
		$this->users_tabs[HA_Common::USERS_TAB] = 'Users';
		$this->users_tabs[HA_Common::USER_ACTIVITY_TAB] = 'User Activity';
		
		$this->reports_tabs[HA_Common::EVENT_COMPARISON_LINE_GRAPH_REPORT_TAB] = 'Event Comparison Line Graph';
		$this->reports_tabs[HA_Common::EVENT_LINE_GRAPH_REPORT_TAB] = 'Event Line Graph';
		$this->reports_tabs[HA_Common::EVENT_STATISTICS_TABLE_REPORT_TAB] = 'Event Statistics Table';
		$this->reports_tabs[HA_Common::EVENT_TOTALS_BAR_GRAPH_REPORT_TAB] = 'Event Totals Bar Graph';
		
		// Create settings page, add JavaScript and CSS
		if( is_admin() ) {
			add_action( 'admin_menu', array( $this, 'add_admin_menus' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );
		}
		
		// Setup AJAX calls
		$this->add_ajax_actions();
		
		$this->data_services = new HA_Local_Data_Services();
		
		do_action('ha_admin_controller_assets', $this);
	}

	/**
	 * Start session
	 */
	function start_session() {
		if(!session_id()) {
			session_start();
		}
	}

	/**
	 * End session
	 */
	function end_session() {
		session_destroy ();
	}

	/**
	 * Activates the plugin by setting up DB tables and adding options
	 *
	 */
	public static function activate_plugin() {
		
		global $wpdb, $charset_collate;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
		/*
		 * User table: Id, IP Address, Session ID, Username, Role, HTTP User Agent, Last Updt Date
		 *
		 * User Environment table: ID, User ID, Browser, Device, Operating System, Last Updt Date
		 *
		 * User Event table: ID, User ID, User Env ID, Type, Record Date, URL, Description,
		 * X Coord, Y Coord, Last Updt Date, Page Width, Data
		 */
		
		// Create database tables
		$query = 'CREATE TABLE '.$wpdb->prefix.HA_Common::USER_TBL_NAME.' (
		'.HA_Common::ID_COLUMN.' int(11) NOT NULL AUTO_INCREMENT,
		'.HA_Common::IP_ADDRESS_COLUMN.' varchar(255),
		'.HA_Common::SESSION_ID_COLUMN.' varchar(255),
		'.HA_Common::USER_ROLE_COLUMN.' varchar(255) DEFAULT "",
		'.HA_Common::USERNAME_COLUMN.' varchar(255) DEFAULT "",
		'.HA_Common::LAST_UPDT_DATE_COLUMN.' DATETIME,
		PRIMARY KEY  ('.HA_Common::ID_COLUMN.')
		) '  . $charset_collate;
		dbDelta( $query );
		
		$query = 'CREATE TABLE '.$wpdb->prefix.HA_Common::USER_ENV_TBL_NAME.' (
		'.HA_Common::ID_COLUMN.' int(11) NOT NULL AUTO_INCREMENT,
		'.HA_Common::USER_ID_COLUMN.' int(11) NOT NULL,
		'.HA_Common::BROWSER_COLUMN.' varchar(255),
		'.HA_Common::OS_COLUMN.' varchar(255),
		'.HA_Common::DEVICE_COLUMN.' varchar(255),
		'.HA_Common::LAST_UPDT_DATE_COLUMN.' DATETIME,
		PRIMARY KEY  ('.HA_Common::ID_COLUMN.'),
		KEY ix_user_env (' . HA_Common::USER_ID_COLUMN . ','. HA_Common::BROWSER_COLUMN . ',' 
		. HA_Common::OS_COLUMN . ',' . HA_Common::DEVICE_COLUMN . ')
		) ' . $charset_collate;
		dbDelta( $query );
		
		// User event table
		$query = 'CREATE TABLE '.$wpdb->prefix.HA_Common::USER_EVENT_TBL_NAME.' (
		'.HA_Common::ID_COLUMN.' int(11) NOT NULL AUTO_INCREMENT,
		'.HA_Common::USER_ID_COLUMN.' int(11) NOT NULL,
		'.HA_Common::USER_ENV_ID_COLUMN.' int(11) NOT NULL,
		'.HA_Common::EVENT_TYPE_COLUMN.' varchar(50) NOT NULL,
		'.HA_Common::URL_COLUMN.' varchar(255) NOT NULL,
		'.HA_Common::X_COORD_COLUMN.' int(11),
		'.HA_Common::Y_COORD_COLUMN.' int(11),
		'.HA_Common::PAGE_WIDTH_COLUMN.' int(11),
		'.HA_Common::RECORD_DATE_COLUMN.' DATETIME,
		'.HA_Common::DATA_COLUMN.' varchar(255),
		'.HA_Common::DESCRIPTION_COLUMN.' varchar(255),
		'.HA_Common::LAST_UPDT_DATE_COLUMN.' DATETIME,
		PRIMARY KEY  ('.HA_Common::ID_COLUMN.'),
		KEY ix_event (' . HA_Common::X_COORD_COLUMN . ','. HA_Common::Y_COORD_COLUMN . ',' 
		. HA_Common::EVENT_TYPE_COLUMN . ',' . HA_Common::PAGE_WIDTH_COLUMN . ',' 
		. HA_Common::URL_COLUMN . ',' . HA_Common::USER_ENV_ID_COLUMN . ')			
		) ' . $charset_collate;
		dbDelta( $query );
		
		// Custom event table
		$query = 'CREATE TABLE '.$wpdb->prefix.HA_Common::CUSTOM_EVENT_TBL_NAME.' (
		'.HA_Common::ID_COLUMN.' int(11) NOT NULL AUTO_INCREMENT,
		'.HA_Common::EVENT_TYPE_COLUMN.' varchar(255),
		'.HA_Common::DESCRIPTION_COLUMN.' varchar(255),
		'.HA_Common::CUSTOM_EVENT_COLUMN.' varchar(255),
		'.HA_Common::URL_COLUMN.' varchar(255),
		'.HA_Common::IS_FORM_SUBMIT_COLUMN. ' tinyint(1) DEFAULT 0,
		'.HA_Common::IS_MOUSE_CLICK_COLUMN. ' tinyint(1) DEFAULT 1,
		'.HA_Common::IS_TOUCHSCREEN_TAP_COLUMN. ' tinyint(1) DEFAULT 0,
		PRIMARY KEY  ('.HA_Common::ID_COLUMN.'),
		KEY ix_custom_event (' . HA_Common::URL_COLUMN . ','. HA_Common::EVENT_TYPE_COLUMN . ')		
		) ' . $charset_collate;
		dbDelta( $query );

	}

	/**
	 * Uninstall plugin
	 *
	 */
	public static function uninstall_plugin() {
		// Delete options
		delete_option( HA_Common::GENERAL_SETTINGS_KEY ) ;
		delete_option( HA_Common::URL_FILTERS_SETTINGS_KEY );
		delete_option( HA_Common::HEAT_MAP_SETTINGS_KEY );
		delete_option( HA_Common::SCHEDULE_SETTINGS_KEY );
		delete_option( HA_Common::DATABASE_SETTINGS_KEY );

		// Plugin version
		delete_option( HA_Common::PLUGIN_VERSION_OPTION );
		
		// Drop tables
		global $wpdb;
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . HA_Common::USER_ENV_TBL_NAME );
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . HA_Common::USER_EVENT_TBL_NAME );
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . HA_Common::USER_TBL_NAME );
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . HA_Common::CUSTOM_EVENT_TBL_NAME );
	}

	/**
	 * Retrieve settings from DB and sets default options if not set
	 */
	function load_settings() {
		$this->general_settings = (array) get_option( HA_Common::GENERAL_SETTINGS_KEY );
		$this->url_filters_settings = (array) get_option( HA_Common::URL_FILTERS_SETTINGS_KEY );
		$this->heat_map_settings = (array) get_option( HA_Common::HEAT_MAP_SETTINGS_KEY );
		$this->database_settings = (array) get_option( HA_Common::DATABASE_SETTINGS_KEY );
		$this->schedule_settings = (array) get_option( HA_Common::SCHEDULE_SETTINGS_KEY );
		

		// Merge with defaults
		$this->general_settings = array_merge( array(
				HA_Common::SAVE_CLICK_TAP_OPTION => true,
				HA_Common::DRAW_HEAT_MAP_ENABLED_OPTION => true,
				HA_Common::DEBUG_OPTION => false,
				HA_Common::SAVE_AJAX_ACTIONS_OPTION => true,
				HA_Common::SAVE_CUSTOM_EVENTS_OPTION => true,
				HA_Common::SAVE_PAGE_VIEWS_OPTION => true
		), $this->general_settings );

		$this->schedule_settings = array_merge( array(
				HA_Common::SCHEDULED_START_DATE_OPTION => '',
				HA_Common::SCHEDULED_END_DATE_OPTION => '',
		), $this->schedule_settings );

		$this->database_settings = array_merge( array(
				HA_Common::URL_DB_LIMIT_OPTION => ''
		), $this->database_settings );

		$this->heat_map_settings = array_merge( array(
				HA_Common::USE_HEATMAPJS_OPTION => false,
				HA_Common::HOT_VALUE_OPTION => 20,
				HA_Common::SPOT_OPACITY_OPTION => 0.2,
				HA_Common::SPOT_RADIUS_OPTION => 8,
				HA_Common::IGNORE_WIDTH_OPTION => false,
				HA_Common::IGNORE_DEVICE_OPTION => false,
				HA_Common::IGNORE_OS_OPTION => false,
				HA_Common::IGNORE_BROWSER_OPTION => false,
				HA_Common::WIDTH_ALLOWANCE_OPTION => 6,
				HA_Common::HIDE_ROLES_OPTION => null
		), $this->heat_map_settings );

		$this->url_filters_settings = array_merge( array(
				HA_Common::APPLY_URL_FILTERS_OPTION => false,
				HA_Common::FILTER_TYPE_OPTION => HA_Common::WHITELIST_VALUE,
				HA_Common::URL_FILTERS_LIST_OPTION => ''
		), $this->url_filters_settings );

		update_option(HA_Common::GENERAL_SETTINGS_KEY, $this->general_settings);
		update_option(HA_Common::SCHEDULE_SETTINGS_KEY, $this->schedule_settings);
		update_option(HA_Common::DATABASE_SETTINGS_KEY, $this->database_settings);
		update_option(HA_Common::HEAT_MAP_SETTINGS_KEY, $this->heat_map_settings);
		update_option(HA_Common::URL_FILTERS_SETTINGS_KEY, $this->url_filters_settings);
	}

	/**
	 * Creates the Settings page with the following tabs: General, Heatmaps, URl Filters and Advanced
	 *
	 * @since 2.0
	 */
	public function add_admin_menus() {
		add_menu_page( __( 'Hotspots', HA_Common::PLUGIN_ID ), __( 'Hotspots', HA_Common::PLUGIN_ID ), 'manage_options', HA_Common::HEATMAPS_PAGE_SLUG, array( &$this, 'heatmaps_page' ), 'dashicons-marker', null );
		
		add_submenu_page(HA_Common::HEATMAPS_PAGE_SLUG,'','','manage_options',HA_Common::HEATMAPS_PAGE_SLUG, array( &$this, 'heatmaps_page' ));
		add_submenu_page(HA_Common::HEATMAPS_PAGE_SLUG,'Heatmaps','Heatmaps','manage_options',HA_Common::HEATMAPS_PAGE_SLUG, array( &$this, 'heatmaps_page' ));
		add_submenu_page(HA_Common::HEATMAPS_PAGE_SLUG,'Users','Users','manage_options',HA_Common::USERS_PAGE_SLUG, array( &$this, 'users_page' ));
		add_submenu_page(HA_Common::HEATMAPS_PAGE_SLUG,'Reports','Reports','manage_options',HA_Common::REPORTS_PAGE_SLUG, array( &$this, 'reports_page' ));
		add_submenu_page(HA_Common::HEATMAPS_PAGE_SLUG, 'Settings','Settings','manage_options', HA_Common::SETTINGS_PAGE_SLUG, array( &$this, 'settings_page' ));
	}

	/**
	 * Displays the Heatmaps page
	 */
	function heatmaps_page() {
		echo HA_Admin_Page_View::heatmaps_page();
	}
	
	/**
	 * Displays the Users page
	 */
	function users_page() {
		echo HA_Admin_Page_View::users_page($this->users_tabs);
	}
	
	/**
	 * Displays the Reports page
	 */
	function reports_page() {
		echo HA_Admin_Page_View::reports_page($this->reports_tabs);
	}
	
	/**
	 * Displays the Settings plugin page
	 */
	function settings_page() {
		echo HA_Admin_Page_View::settings_page($this->settings_tabs);
	}

	/**
	 * Admin assets
	 *
	 * @since 1.2.8
	 */
	public function assets() {
		$config_array = array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'ajaxNonce' => wp_create_nonce( HA_Common::PLUGIN_ID.'-nonce' )
		);
		wp_enqueue_script( 'jquery' );

		$root_relative_path = '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
		
		if ( is_admin() ) {
			wp_enqueue_style( HA_Common::PLUGIN_ID.'-admin-style', plugins_url( $root_relative_path . 'css' . DIRECTORY_SEPARATOR . 'admin.css', __FILE__ ) );
			wp_enqueue_script( HA_Common::PLUGIN_ID.'-admin-script', plugins_url( $root_relative_path . 'js' . DIRECTORY_SEPARATOR . 'admin.js', __FILE__ ), array( 'jquery' ) );
			wp_localize_script( HA_Common::PLUGIN_ID.'-admin-script', HA_Common::CONFIG_DATA, $config_array );

			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_script('jquery-ui-timepicker');
			wp_enqueue_style('jquery-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

			// flot
			wp_enqueue_script( 'flot', plugins_url( $root_relative_path . 'js' . DIRECTORY_SEPARATOR . 'flot' . DIRECTORY_SEPARATOR . 'jquery.flot.js', __FILE__ ), array( 'jquery' ) );
			wp_enqueue_script( 'flot-categories', plugins_url( $root_relative_path . 'js' . DIRECTORY_SEPARATOR . 'flot' . DIRECTORY_SEPARATOR . 'jquery.flot.categories.js', __FILE__ ), array( 'jquery', 'flot' ) );
			wp_enqueue_script( 'flot-time', plugins_url( $root_relative_path . 'js' . DIRECTORY_SEPARATOR . 'flot' . DIRECTORY_SEPARATOR . 'jquery.flot.time.js', __FILE__ ), array( 'jquery', 'flot' ) );
			wp_enqueue_script( 'flot-selection', plugins_url( $root_relative_path . 'js' . DIRECTORY_SEPARATOR . 'flot' . DIRECTORY_SEPARATOR . 'jquery.flot.selection.js', __FILE__ ), array( 'jquery', 'flot', 'flot-time' ) );

		}

		wp_enqueue_script('common');
		wp_enqueue_script('wp-lists');
		wp_enqueue_script('postbox');
		
		
	}
	
	/**
	 * Register the General settings
	 */
	function register_general_settings() {
		register_setting( HA_Common::GENERAL_SETTINGS_KEY, HA_Common::GENERAL_SETTINGS_KEY, array( 'HA_General_Settings_View', 'sanitize_general_settings' ) );
	
		add_settings_section( 'section_general', 'General Settings', array( 'HA_General_Settings_View', 'section_general_desc' ), HA_Common::GENERAL_SETTINGS_KEY );
	
		add_settings_field( HA_Common::SAVE_CLICK_TAP_OPTION, 'Save mouse clicks and touchscreen taps', array( 'HA_General_Settings_View', 'field_save_click_tap' ), HA_Common::GENERAL_SETTINGS_KEY, 'section_general' );
		add_settings_field( HA_Common::DRAW_HEAT_MAP_ENABLED_OPTION, 'Enable drawing heatmap', array( 'HA_General_Settings_View', 'field_draw_heat_map_enabled' ), HA_Common::GENERAL_SETTINGS_KEY, 'section_general' );
		add_settings_field( HA_Common::DEBUG_OPTION, 'Debug', array( 'HA_General_Settings_View', 'field_debug' ), HA_Common::GENERAL_SETTINGS_KEY, 'section_general' );
	
		add_settings_field( HA_Common::SAVE_CUSTOM_EVENTS_OPTION, 'Save Custom Events', array( 'HA_General_Settings_View', 'field_save_custom_events' ), HA_Common::GENERAL_SETTINGS_KEY, 'section_general' );
		add_settings_field( HA_Common::SAVE_AJAX_ACTIONS_OPTION, 'Save AJAX Actions', array( 'HA_General_Settings_View', 'field_save_ajax_actions' ), HA_Common::GENERAL_SETTINGS_KEY, 'section_general' );
		add_settings_field( HA_Common::SAVE_PAGE_VIEWS_OPTION, 'Save Page Views', array( 'HA_General_Settings_View', 'field_save_page_views' ), HA_Common::GENERAL_SETTINGS_KEY, 'section_general' );
	}
	
	/**
	 * Register the Schedule settings
	 */
	function register_schedule_settings() {
		register_setting( HA_Common::SCHEDULE_SETTINGS_KEY, HA_Common::SCHEDULE_SETTINGS_KEY, array( 'HA_Schedule_Settings_View', 'sanitize_schedule_settings' ) );
	
		add_settings_section( 'section_schedule', 'Schedule Settings', array( 'HA_Schedule_Settings_View', 'section_schedule_desc' ), HA_Common::SCHEDULE_SETTINGS_KEY );
	
		add_settings_field( HA_Common::SCHEDULED_START_DATE_OPTION, 'Scheduled start date & time', array( 'HA_Schedule_Settings_View', 'field_scheduled_start_date' ), HA_Common::SCHEDULE_SETTINGS_KEY, 'section_schedule' );
		add_settings_field( HA_Common::SCHEDULED_END_DATE_OPTION, 'Scheduled end date & time', array( 'HA_Schedule_Settings_View', 'field_scheduled_end_date' ), HA_Common::SCHEDULE_SETTINGS_KEY, 'section_schedule' );
	}
	
	/**
	 * Register the Heat Map settings
	 */
	function register_heat_map_settings() {
	
		register_setting( HA_Common::HEAT_MAP_SETTINGS_KEY, HA_Common::HEAT_MAP_SETTINGS_KEY, array( 'HA_Heatmap_Settings_View', 'sanitize_heat_map_settings' ) );
	
		add_settings_section( 'section_heat_map', 'Heatmap Settings', array( 'HA_Heatmap_Settings_View', 'section_heat_map_desc' ), HA_Common::HEAT_MAP_SETTINGS_KEY );
		add_settings_field( HA_Common::USE_HEATMAPJS_OPTION, 'Use heatmap.js', array( 'HA_Heatmap_Settings_View', 'field_heatmapjs' ), HA_Common::HEAT_MAP_SETTINGS_KEY, 'section_heat_map' );
		add_settings_field( HA_Common::HOT_VALUE_OPTION, 'Hot value', array( 'HA_Heatmap_Settings_View', 'field_hot_value' ), HA_Common::HEAT_MAP_SETTINGS_KEY, 'section_heat_map' );
		add_settings_field( HA_Common::SPOT_RADIUS_OPTION, 'Spot radius', array( 'HA_Heatmap_Settings_View', 'field_spot_radius' ), HA_Common::HEAT_MAP_SETTINGS_KEY, 'section_heat_map' );
		add_settings_field( HA_Common::SPOT_OPACITY_OPTION, 'Spot opacity', array( 'HA_Heatmap_Settings_View', 'field_spot_opacity' ), HA_Common::HEAT_MAP_SETTINGS_KEY, 'section_heat_map' );
		add_settings_field( HA_Common::IGNORE_WIDTH_OPTION, 'Ignore width', array( 'HA_Heatmap_Settings_View', 'field_ignore_width' ), HA_Common::HEAT_MAP_SETTINGS_KEY, 'section_heat_map' );
		add_settings_field( HA_Common::IGNORE_DEVICE_OPTION, 'Ignore device', array( 'HA_Heatmap_Settings_View', 'field_ignore_device' ), HA_Common::HEAT_MAP_SETTINGS_KEY, 'section_heat_map' );
		add_settings_field( HA_Common::IGNORE_BROWSER_OPTION, 'Ignore browser', array( 'HA_Heatmap_Settings_View', 'field_ignore_browser' ), HA_Common::HEAT_MAP_SETTINGS_KEY, 'section_heat_map' );
		add_settings_field( HA_Common::IGNORE_OS_OPTION, 'Ignore operating system', array( 'HA_Heatmap_Settings_View', 'field_ignore_os' ), HA_Common::HEAT_MAP_SETTINGS_KEY, 'section_heat_map' );
		add_settings_field( HA_Common::WIDTH_ALLOWANCE_OPTION, 'Width allowance', array( 'HA_Heatmap_Settings_View', 'field_width_allowance' ), HA_Common::HEAT_MAP_SETTINGS_KEY, 'section_heat_map' );
		add_settings_field( HA_Common::HIDE_ROLES_OPTION, 'Hide roles', array( 'HA_Heatmap_Settings_View', 'field_hide_roles' ), HA_Common::HEAT_MAP_SETTINGS_KEY, 'section_heat_map' );
	
	}
	
	/**
	 * Register the URL Filter settings
	 */
	function register_url_filters_settings() {
		register_setting( HA_Common::URL_FILTERS_SETTINGS_KEY, HA_Common::URL_FILTERS_SETTINGS_KEY, array( 'HA_URL_Filters_Settings_View', 'sanitize_url_filters_settings' ) );
	
		add_settings_section( 'section_url_filters', 'URL Filter Settings', array( 'HA_URL_Filters_Settings_View', 'section_url_filters_desc' ), HA_Common::URL_FILTERS_SETTINGS_KEY );
	
		add_settings_field( HA_Common::APPLY_URL_FILTERS_OPTION, 'Apply URL filters', array( 'HA_URL_Filters_Settings_View', 'field_apply_url_filters' ), HA_Common::URL_FILTERS_SETTINGS_KEY, 'section_url_filters' );
		add_settings_field( HA_Common::FILTER_TYPE_OPTION, 'Filter type', array( 'HA_URL_Filters_Settings_View', 'field_filter_type' ), HA_Common::URL_FILTERS_SETTINGS_KEY, 'section_url_filters' );
		add_settings_field( HA_Common::URL_FILTERS_LIST_OPTION, 'URL List', array( 'HA_URL_Filters_Settings_View', 'field_url_filters_list' ), HA_Common::URL_FILTERS_SETTINGS_KEY, 'section_url_filters' );
	}
	
	/**
	 * Register the Database settings
	 */
	function register_database_settings() {
	
		register_setting( HA_Common::DATABASE_SETTINGS_KEY, HA_Common::DATABASE_SETTINGS_KEY, array( 'HA_Database_Settings_View', 'sanitize_database_settings' ) );
	
		add_settings_section( 'section_database', 'Database Settings', array( 'HA_Database_Settings_View', 'section_database_desc' ), HA_Common::DATABASE_SETTINGS_KEY );
	
		add_settings_field( HA_Common::URL_DB_LIMIT_OPTION, 'URL database limit', array( 'HA_Database_Settings_View', 'field_url_db_limit' ), HA_Common::DATABASE_SETTINGS_KEY, 'section_database' );
	}	
	
	/**
	 * Register AJAX actions
	 *
	 * @since 2.4
	 */
	public function add_ajax_actions() {
	
		if (is_admin()) {
			add_action( 'wp_ajax_nopriv_save_user_event', array( $this, 'save_user_event' ) );
			add_action( 'wp_ajax_save_user_event', array( $this, 'save_user_event' ) );
			
			add_action( 'wp_ajax_nopriv_retrieve_user_events',  array( $this, 'retrieve_user_events' ) );
			add_action( 'wp_ajax_retrieve_user_events',  array( $this, 'retrieve_user_events' ) );
		}
	}
	
	/**
	 * Retrieves all mouse clicks/touch screen taps
	 *
	 * @since 1.0
	 */
	public function retrieve_user_events() {
		
		$ajax_nonce = $_GET['nonce'];
		
		$response = array();
		if ( wp_verify_nonce( $ajax_nonce, HA_Common::PLUGIN_ID .'-nonce' ) ) {
			
			$response = array('status' => 'OK', 'message' => '');
			
			// GET parameters
			$url = isset($_GET['url']) ? HA_Common::normalize_url(urldecode($_GET['url'])) : null;
			$ignore_width = isset($_GET['ignoreWidth']) && $_GET['ignoreWidth'] == "true" ? true : false;
			$width_allowance = isset($_GET['widthAllowance']) && is_numeric($_GET['widthAllowance']) ? intval($_GET['widthAllowance']) : null;;
			$page_width = isset($_GET['pageWidth']) && is_numeric($_GET['pageWidth']) ? intval($_GET['pageWidth']) : null;
			$user_event_id = isset($_GET['userEventId']) && is_numeric($_GET['userEventId']) ? intval($_GET['userEventId']) : null;
			$ignore_device = isset($_GET['ignoreDevice']) && $_GET['ignoreDevice'] == "true" ? true : false;
			$device = isset($_GET['device']) && strlen(trim($_GET['device'])) > 0 ? urldecode($_GET['device']) : null;;
			$ignore_os = isset($_GET['ignoreOs']) && $_GET['ignoreOs'] == "true" ? true : false;
			$os = isset($_GET['os']) && strlen(trim($_GET['os'])) > 0 ? urldecode($_GET['os']) : null;
			$ignore_browser = isset($_GET['ignoreBrowser']) && $_GET['ignoreBrowser'] == "true" ? true : false;
			$browser = isset($_GET['browser']) && strlen(trim($_GET['browser'])) > 0 ? urldecode($_GET['browser']) : null;;
			$hide_roles = isset($_GET['hideRoles']) && is_array($_GET['hideRoles']) ? $_GET['hideRoles'] : array();
			$spot_radius = isset($_GET['spotRadius']) && is_numeric($_GET['spotRadius']) ? intval($_GET['spotRadius']) : null;
			$event_types = isset($_GET['eventTypes']) && is_array($_GET['eventTypes']) ? $_GET['eventTypes'] : array();
			
			// validate data
			if (!$url || !count($event_types) > 0) {
				$response['status'] = 'Error';
				$response['message'] = 'Required data missing from request';
				echo json_encode($response);
				return;
			}
			
			global $wpdb;
			
			// base query - all user events for a given url
			$query = 'SELECT u_event.' . HA_Common::ID_COLUMN . ', u_event.'.HA_Common::X_COORD_COLUMN.', u_event.'.HA_Common::Y_COORD_COLUMN.', u_event.'
			. HA_Common::URL_COLUMN.', u_event.' . HA_Common::EVENT_TYPE_COLUMN . ', u_event.'.HA_Common::PAGE_WIDTH_COLUMN.' FROM ' . $wpdb->prefix . HA_Common::USER_EVENT_TBL_NAME
			.' AS u_event, ' . $wpdb->prefix .  HA_Common::USER_ENV_TBL_NAME . ' AS u_env, ' . $wpdb->prefix . HA_Common::USER_TBL_NAME . ' AS u WHERE u.' . HA_Common::ID_COLUMN
			. ' = u_event.' . HA_Common::USER_ID_COLUMN . ' AND u.' . HA_Common::ID_COLUMN . ' = u_env.' . HA_Common::USER_ID_COLUMN
			. ' AND u_event.' .HA_Common::URL_COLUMN.' = "' .$url . '"';
			
			$query_filters = array(
					'ignore_width' => $ignore_width,
					'width_allowance' => $width_allowance,
					'page_width' => $page_width,
					'user_event_id' => $user_event_id,
					'ignore_device' => $ignore_device,
					'device' => $device,
					'ignore_os' => $ignore_os,
					'os' => $os,
					'ignore_browser' => $ignore_browser,
					'browser' => $browser,
					'hide_roles' => $hide_roles,
					'event_types' => $event_types,
					'exact_match' => false
			);
			
			$query = HA_Query_Helper::apply_query_filters($query, $query_filters);
			
			$rows = $wpdb->get_results($query);
	
			$index = 0;
			foreach ($rows as $row) {
				$user_event_id = $row->id;
				$x_coord = $row->x_coord;
				$y_coord = $row->y_coord;
			
				$url = HA_Common::normalize_url( $row->url );
				$page_width = $row->page_width;
				$heat_value = HA_Common::calculate_heat_value($x_coord, $y_coord, $user_event_id, $rows, $spot_radius);
			
				$response[$index++] = array(
						'user_event_id' => $user_event_id,
						'x_coord' => $x_coord,
						'y_coord' => $y_coord,
						'page_width' => $page_width,
						'url' => $url,
						'heat_value' => $heat_value,
						'event_type' => $row->event_type
				);
			}
		}
	
		echo json_encode($response);
		
		die();
	}
	
	/**
	 * Saves mouse click or touchscreen tap information database
	 *
	 * @since 2.0
	 */
	public function save_user_event() {
		
		$ajaxNonce = $_POST['nonce'];
	
		$response = array();
		if ( wp_verify_nonce( $ajaxNonce, HA_Common::PLUGIN_ID.'-nonce' ) ) {
			
			$response = array('status' => 'OK', 'message' => '');
			
			// POST parameters
			$x_coord = isset($_POST['xCoord']) && is_numeric($_POST['xCoord']) ? intval($_POST['xCoord']) : -1;
			$y_coord = isset($_POST['yCoord']) && is_numeric($_POST['yCoord']) ? intval($_POST['yCoord']) : -1;
			$url = isset($_POST['url']) ? HA_Common::normalize_url(urldecode($_POST['url'])) : null;
			$page_width = isset($_POST['pageWidth']) && is_numeric($_POST['pageWidth']) ? intval($_POST['pageWidth']) : null;
			$ip_address = isset($_POST['ipAddress']) ? $_POST['ipAddress'] : null;
			$user_id = isset($_POST['userId']) ? $_POST['userId'] : null;
			$user_environment_id = isset($_POST['userEnvironmentId']) ? $_POST['userEnvironmentId'] : null;
			$event_type = isset($_POST['eventType']) ? $_POST['eventType'] : null;
			$description = isset($_POST['description']) ? urldecode($_POST['description']) : '';
			$data = isset($_POST['data']) ? urldecode($_POST['data']) : '';
			
			// validate data
			if (!$url || !$page_width || !$ip_address || !$event_type) {
				$response['status'] = 'Error';
				$response['message'] = 'Required data missing from request';
				echo json_encode($response);
				return;
			}
			
			$ip_address = HA_Common::get_IP_address();
			
			// if user_id is null, create it
			if ($user_id == null) {
				$user_details = HA_Common::get_user_details(HA_Common::get_ip_address(), session_id(), true, $this->data_services);
				$user_id = $user_details['user_id'];
			}
			// if user_environment_id is null, create it
			if ($user_environment_id == null) {
				$user_environment_details = HA_Common::get_user_environment_details($user_id, true, $this->data_services);
				$user_environment_id = $user_environment_details['user_environment_id'];
			}
			
			// insert data into database
			$user_event_id = '';
			try {
				global $wpdb;
			
				$rowsAffected = $wpdb->insert( $wpdb->prefix . HA_Common::USER_EVENT_TBL_NAME,
						array(
								HA_Common::USER_ID_COLUMN => $user_id,
								HA_Common::USER_ENV_ID_COLUMN => $user_environment_id,
								HA_Common::X_COORD_COLUMN => $x_coord,
								HA_Common::Y_COORD_COLUMN => $y_coord,
								HA_Common::URL_COLUMN => $url,
								HA_Common::PAGE_WIDTH_COLUMN => $page_width,
								HA_Common::LAST_UPDT_DATE_COLUMN => current_time('mysql'),
								HA_Common::RECORD_DATE_COLUMN => current_time('mysql'),
								HA_Common::DESCRIPTION_COLUMN => $description,
								HA_Common::DATA_COLUMN => $data,
								HA_Common::EVENT_TYPE_COLUMN => $event_type
						)
				);
			
				$user_event_id = $wpdb->insert_id;
			} catch (Exception $e) {
				$response['status'] = 'Error';
				$response['message'] = 'An unexpected error occured';
				echo json_encode($response);
				return;
			}
			
			$debug = isset($_POST['debug']) && $_POST['debug'] == 'true' ? true : false;
			$draw_heat_map_enabled = isset($_POST['drawHeatMapEnabled']) && $_POST['drawHeatMapEnabled'] == 'true' ? true : false;
			$width_allowance= isset($_POST['widthAllowance']) && is_numeric($_POST['widthAllowance']) ? intval($_POST['widthAllowance']) : null;
			$spot_radius= isset($_POST['spotRadius']) && is_numeric($_POST['spotRadius']) ? intval($_POST['spotRadius']) : null;
			
			// debug
			if ($event_type !== null && ($event_type == HA_Common::MOUSE_CLICK_EVENT_TYPE || $event_type == HA_Common::TOUCHSCREEN_TAP_EVENT_TYPE)
					&& $debug && $draw_heat_map_enabled && $width_allowance && $spot_radius) {
			
				// retrieve all clicks and taps and calculate heat value
				$query = 'SELECT ' . HA_Common::ID_COLUMN . ', ' . HA_Common::X_COORD_COLUMN . ', '
				. HA_Common::Y_COORD_COLUMN.', ' . HA_Common::URL_COLUMN . ', '
				. HA_Common::PAGE_WIDTH_COLUMN . ' FROM ' . $wpdb->prefix.HA_Common::USER_EVENT_TBL_NAME
				. ' WHERE '.HA_Common::URL_COLUMN.' = "' . $url .'" AND (' . HA_Common::EVENT_TYPE_COLUMN
				. ' = "' . HA_Common::MOUSE_CLICK_EVENT_TYPE . '" OR ' . HA_Common::EVENT_TYPE_COLUMN
				. ' = "' . HA_Common::TOUCHSCREEN_TAP_EVENT_TYPE . '")';
			
				// allow a range either side to be the same
				$diff_left = $page_width - $width_allowance;
				$diff_right = $page_width + $width_allowance;
			
				$query .= ' AND ' . HA_Common::PAGE_WIDTH_COLUMN . ' >= ' . $diff_left . ' AND '.HA_Common::PAGE_WIDTH_COLUMN
				. ' <= '. $diff_right;
				$rows = $wpdb->get_results($query);
			
				$heat_value = HA_Common::calculate_heat_value($x_coord, $y_coord, $user_event_id, $rows, $spot_radius);
			
				$response = array_merge($response, array('user_event_id' => $user_event_id, 'heat_value' => $heat_value));
			} else {
				$response = array_merge($response, array('user_event_id' => $user_event_id));
			}
			
			echo json_encode($response);
		}
		
		die();
	}
}
?>