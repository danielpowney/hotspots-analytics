<?php 

if (!class_exists('WP_List_Table')) {
	require_once( ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'class-wp-list-table.php' );
}
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'common.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'query-helper.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'services' . DIRECTORY_SEPARATOR . 'local-data-services.php';


/**
 * A table for filtering users
 *
 * @author dpowney
 *
 */
class HA_Users_Table extends WP_List_Table {

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct( array(
				'singular'=> 'User',
				'plural' => 'Users',
				'ajax'	=> false
		) );
	}

	/** (non-PHPdoc)
	 * @see WP_List_Table::extra_tablenav()
	 */
	function extra_tablenav( $which ) {
		if ( $which == "top" ) {
			$query_helper = new HA_Query_Helper();
			
			$filters = array(
					'last_days' => true,
					'ip_address' => true,
					'username' => true,
					'role' => true,
					'event_type' => true
			);
			
			$query_helper->get_session_filters($filters);
				
			$query_helper->show_filters($filters);
			
		}
		if ( $which == "bottom" ){
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see WP_List_Table::get_columns()
	 */
	function get_columns() {
		return $columns= array(
				'session_id' => __('Session ID'),
				'ip_address' => __('IP Address'),
				'username' => __('Username'),
				'role' => __('Role'),
				'record_date' => __('Record Date'),
				'id' => __('Id'),
				'user_id' => __('User Id'),
				'user_env_id' => __('User Env Id'),
				'count_total' => ('Total Events'),
				'count_mouse_clicks' => ('Mouse Clicks'),
				'count_touchscreen_taps' => ('Touchscreen Taps'),
				'count_ajax_actions' => ('AJAX Actions'),
				'count_page_views' => ('Page Views'),
				'count_custom' => ('Custom Events'),
				'action' => __('Action'),
				'device' => __('Device'),
				'browser' => __('Browser'),
				'os' => __('Operating System'),
				'page_width' => __('Page Width')
		);
	}

	/**
	 * (non-PHPdoc)
	 * @see WP_List_Table::prepare_items()
	 */
	function prepare_items() {
		
		$query_helper = new HA_Query_Helper();
		$query_helper->get_session_filters(array('last_days' => true, 'ip_address' => true, 'username' => true, 'role' => true, 'event_type' => true));
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$query_helper->get_http_filters('POST');
		} else {
			$query_helper->get_http_filters('GET');
		}
		$query_helper->set_session_filters();

		// Register the columns
		$columns = $this->get_columns();

		$hidden = array('user_env_id', 'user_id', 'id');
			
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);
		
		$items_per_page = 25;
		// Ensure paging is reset on filter submit by checking HTTP method as well
		$page_num = !empty($_GET["paged"]) && ($_SERVER['REQUEST_METHOD'] != 'POST') ? mysql_real_escape_string($_GET["paged"]) : '';
		if (empty($page_num) || !is_numeric($page_num) || $page_num<=0 ) {
			$page_num = 1;
		}
		
		global $ha_admin_controller;
		$data = $ha_admin_controller->get_data_services()->table_query('users_table_data', $query_helper->get_filters(), $items_per_page, $page_num);
		
		if ( isset( $data['pagination_args'] ) ) {
			$this->set_pagination_args( $data['pagination_args'] );
		}
		if ( isset( $data['items'] ) ) {
			$this->items =   $data['items'];
		}
	}

	/**
	 * Default column
	 * @param unknown_type $item
	 * @param unknown_type $column_name
	 * @return unknown|mixed
	 */
	function column_default( $item, $column_name ) {
		switch( $column_name ) {
			case 'action' :
			case 'event_type':
				return $item[$column_name];
				break;
			case 'count_custom':
				echo intval($item['count_total']) - intval($item['count_mouse_clicks']) - intval($item['count_touchscreen_taps']) - intval($item['count_ajax_actions']) - intval($item['count_page_views']);
				break;
			case 'record_date':
				echo date("F j, Y, g:i a", strtotime($item[$column_name]));
				break;
			case 'page_width':
				echo $item[$column_name] . 'px';
				break;
			default:
				echo $item[$column_name];
				break;
		}
	}
		
	function column_action( $item ){
		$ip_address = $item[HA_Common::IP_ADDRESS_COLUMN];
		$session_id = $item[HA_Common::SESSION_ID_COLUMN];
		echo '<a href="admin.php?page=' . HA_Common::USERS_PAGE_SLUG . '&tab=' . HA_Common::USER_ACTIVITY_TAB . '&ip_address=' . $ip_address . '&session_id=' . $session_id . '">View User Activity</a>';
	}
}

?>