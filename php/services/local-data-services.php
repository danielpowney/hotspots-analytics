<?php 

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'common.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'data-services.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'query-helper.php';

/**
 * Implements data services for local wpdb
 *
 * @author dpowney
 *
 */
class HA_Local_Data_Services implements HA_Data_Services {

	/**
	 * Provides table queries
	 */
	public function table_query($action, $filters, $items_per_page, $page_num) {
		switch ($action) {
			case 'heatmaps_table_data' :
				return $this->heatmaps_table_data($filters, $items_per_page, $page_num);
				break;
			case 'users_table_data' :
				return $this->users_table_data($filters, $items_per_page, $page_num);
				break;
			case 'user_activity_table_data' :
				return $this->user_activity_table_data($filters, $items_per_page, $page_num);
				break;
			case 'event_statistics_table_report_data' :
				return $this->event_statistics_table_report_data($filters, $items_per_page, $page_num);
				break;
			default :
				break;
		}

	}
	
	/**
	 * Provides custom queries
	 */
	public function custom_query($action, $params) {

		switch ($action) {
			case 'distinct_url_from_user_events' :
				return $this->distinct_url_from_user_events();
				break;
			case 'distinct_event_type_from_user_events' :
				return $this->distinct_event_type_from_user_events();
				break;
			case 'distinct_role_from_user' :
				return $this->distinct_role_from_user();
				break;
			case 'distinct_page_width_from_user_events' :
				return $this->distinct_page_width_from_user_events();
				break;
			case 'distinct_device_from_user_env' :
				return $this->distinct_device_from_user_env();
				break;
			case 'distinct_os_from_user_env' :
				return $this->distinct_os_from_user_env();
				break;
			case 'distinct_browser_from_user_env' :
				return $this->distinct_browser_from_user_env();
				break;
			case 'clear_database' :
				return $this->clear_database();
				break;
			case 'add_retrieve_user_details' :
				return $this->add_retrieve_user_details($params);
				break;
			case 'add_retrieve_user_environment_details' :
				return $this->add_retrieve_user_environment_details($params);
			default :
				break;
		}
	}
	
	/**
	 * Provides simple queries
	 */
	public function simple_query($action, $filters) {
		
		switch($action) {
			case 'user_activity_summary_data' :
				return $this->user_activity_summary_data($filters);
				break;
			case 'event_comparison_line_graph_report_data' :
				return $this->event_comparison_line_graph_report_data($filters);
				break;
			case 'event_line_graph_report_data' :
				return $this->event_line_graph_report_data($filters);
				break;
			case 'event_totals_bar_graph_report_data' :
				return $this->event_totals_bar_graph_report_data($filters);
				break;
			default :
				break;
		}
	}
	
	private function distinct_url_from_user_events() {
		global $wpdb;
		$query = 'SELECT DISTINCT ' . HA_Common::URL_COLUMN . ' FROM '.$wpdb->prefix. HA_Common::USER_EVENT_TBL_NAME;
		return $wpdb->get_results($query);
	}
	private function distinct_event_type_from_user_events() {
		global $wpdb;
		$query = 'SELECT DISTINCT ' . HA_Common::EVENT_TYPE_COLUMN . ' FROM ' . $wpdb->prefix . HA_Common::USER_EVENT_TBL_NAME;
		return $wpdb->get_results($query);
	}
	private function distinct_role_from_user() {
		global $wpdb;
		$query = 'SELECT DISTINCT ' . HA_Common::USER_ROLE_COLUMN . ' FROM ' . $wpdb->prefix . HA_Common::USER_TBL_NAME;
		return $wpdb->get_results($query);
	}
	private function distinct_page_width_from_user_events() {
		global $wpdb;
		$query = 'SELECT DISTINCT ' . HA_Common::PAGE_WIDTH_COLUMN . ' FROM ' . $wpdb->prefix . HA_Common::USER_EVENT_TBL_NAME;
		return $wpdb->get_results($query);
	}
	private function distinct_device_from_user_env() {
		global $wpdb;
		$query = 'SELECT DISTINCT ' . HA_Common::DEVICE_COLUMN . ' FROM ' . $wpdb->prefix . HA_Common::USER_EVENT_TBL_NAME . ' AS u_event, ' . $wpdb->prefix .  HA_Common::USER_ENV_TBL_NAME . ' AS u_env WHERE u_event.' . HA_Common::USER_ENV_ID_COLUMN . ' = u_env.' . HA_Common::ID_COLUMN;
		return $wpdb->get_results($query);
	}
	private function distinct_os_from_user_env() {
		global $wpdb;
		$query = 'SELECT DISTINCT ' . HA_Common::OS_COLUMN . ' FROM ' . $wpdb->prefix . HA_Common::USER_EVENT_TBL_NAME . ' AS u_event, ' . $wpdb->prefix .  HA_Common::USER_ENV_TBL_NAME . ' AS u_env WHERE u_event.' . HA_Common::USER_ENV_ID_COLUMN . ' = u_env.' . HA_Common::ID_COLUMN;
		return $wpdb->get_results($query);
	}
	private function distinct_browser_from_user_env() {
		global $wpdb;
		$query = 'SELECT DISTINCT ' . HA_Common::BROWSER_COLUMN . ' FROM ' . $wpdb->prefix . HA_Common::USER_EVENT_TBL_NAME . ' AS u_event, ' . $wpdb->prefix .  HA_Common::USER_ENV_TBL_NAME . ' AS u_env WHERE u_event.' . HA_Common::USER_ENV_ID_COLUMN . ' = u_env.' . HA_Common::ID_COLUMN;
		return $wpdb->get_results($query);
	}
	private function heatmaps_table_data($filters, $items_per_page, $page_num) {
		global $wpdb;

		$query = 'SELECT COUNT(*) as count, u_event.' . HA_Common::URL_COLUMN
		. ' AS ' . HA_Common::URL_COLUMN . ', u_event.' . HA_Common::RECORD_DATE_COLUMN . ' AS '
		. HA_Common::RECORD_DATE_COLUMN . ', u_event.' . HA_Common::EVENT_TYPE_COLUMN . ' AS '
		. HA_Common::EVENT_TYPE_COLUMN . ', u_event.' . HA_Common::PAGE_WIDTH_COLUMN . ' AS '
		. HA_Common::PAGE_WIDTH_COLUMN . ', u_event.' . HA_Common::DESCRIPTION_COLUMN . ' AS '
		. HA_Common::DESCRIPTION_COLUMN . ', u_event.' . HA_Common::ID_COLUMN . ' AS ' . HA_Common::ID_COLUMN
		. ', u_env.' . HA_Common::ID_COLUMN . ' AS ' . HA_Common::USER_ENV_ID_COLUMN . ' FROM ' . $wpdb->prefix
		. HA_Common::USER_EVENT_TBL_NAME . ' AS u_event, ' . $wpdb->prefix . HA_Common::USER_ENV_TBL_NAME
		. ' AS u_env WHERE u_event.' . HA_Common::USER_ENV_ID_COLUMN . ' = u_env.' . HA_Common::ID_COLUMN . ' AND ('
		. HA_Common::EVENT_TYPE_COLUMN . ' = "' . HA_Common::MOUSE_CLICK_EVENT_TYPE . '" OR '
		. HA_Common::EVENT_TYPE_COLUMN . ' = "' . HA_Common::TOUCHSCREEN_TAP_EVENT_TYPE . '")';

		$query = HA_Query_Helper::apply_query_filters($query, $filters);

		$query .= ' GROUP BY u_event.' . HA_Common::URL_COLUMN . ', u_event.' . HA_Common::PAGE_WIDTH_COLUMN . ', u_event.' . HA_Common::EVENT_TYPE_COLUMN;
		$query .= ' ORDER BY count DESC';

		// pagination
		$item_count = $wpdb->query($query); //return the total number of affected rows

		$total_pages = ceil($item_count/$items_per_page);
		// adjust the query to take pagination into account
		if (!empty($page_num) && !empty($items_per_page)) {
			$offset=($page_num-1)*$items_per_page;
			$query .= ' LIMIT ' .(int)$offset. ',' .(int)$items_per_page;
		}

		$pagination_args = array( "total_items" => $item_count, "total_pages" => $total_pages, "per_page" => $items_per_page );
		$items = $wpdb->get_results($query, ARRAY_A);

		return array('pagination_args' => $pagination_args, 'items' => $items);
	}
	private function users_table_data($filters, $items_per_page, $page_num) {
		global $wpdb;

		$query = 'SELECT u_env.' . HA_Common::DEVICE_COLUMN . ' AS ' . HA_Common::DEVICE_COLUMN . ', COUNT(*) AS count_total, '
		. 'count(case when u_event.' . HA_Common::EVENT_TYPE_COLUMN . ' = "' . HA_Common::MOUSE_CLICK_EVENT_TYPE . '" THEN 1 ELSE null end) AS count_mouse_clicks, '
		. 'count(case when u_event.' . HA_Common::EVENT_TYPE_COLUMN . ' = "' . HA_Common::PAGE_VIEW_EVENT_TYPE . '" THEN 1 ELSE null end) AS count_page_views, '
		. 'count(case when u_event.' . HA_Common::EVENT_TYPE_COLUMN . ' = "' . HA_Common::AJAX_ACTION_EVENT_TYPE . '" THEN 1 ELSE null end) AS count_ajax_actions, '
		. 'count(case when u_event.' . HA_Common::EVENT_TYPE_COLUMN . ' = "' . HA_Common::TOUCHSCREEN_TAP_EVENT_TYPE . '" THEN 1 ELSE null end) AS count_touchscreen_taps, '
		. 'u_env.' . HA_Common::BROWSER_COLUMN . ' AS ' . HA_Common::BROWSER_COLUMN . ','
		. 'u_env.' . HA_Common::OS_COLUMN . ' AS ' . HA_Common::OS_COLUMN . ','
		. 'u.' . HA_Common::SESSION_ID_COLUMN . ' AS ' . HA_Common::SESSION_ID_COLUMN . ', u.'
		. HA_Common::IP_ADDRESS_COLUMN . ' AS ' . HA_Common::IP_ADDRESS_COLUMN . ', u.' . HA_Common::USERNAME_COLUMN
		. ' AS ' . HA_Common::USERNAME_COLUMN . ', u_event.' . HA_Common::RECORD_DATE_COLUMN . ' AS '
		. HA_Common::RECORD_DATE_COLUMN . ', u.' . HA_Common::USER_ROLE_COLUMN . ' AS '
		. HA_Common::USER_ROLE_COLUMN . ', u_event.' . HA_Common::PAGE_WIDTH_COLUMN . ' AS '
		. HA_Common::PAGE_WIDTH_COLUMN . ', u_env.' . HA_Common::ID_COLUMN . ' AS '
		. HA_Common::USER_ENV_ID_COLUMN . ', u.' . HA_Common::ID_COLUMN . ' AS '
		. HA_Common::USER_ID_COLUMN . ' FROM ' . $wpdb->prefix . HA_Common::USER_TBL_NAME . ' AS u, ' . $wpdb->prefix
		. HA_Common::USER_EVENT_TBL_NAME . ' AS u_event, ' . $wpdb->prefix . HA_Common::USER_ENV_TBL_NAME
		. ' AS u_env WHERE u_event.' . HA_Common::USER_ENV_ID_COLUMN . ' = u_env.' . HA_Common::ID_COLUMN . ' AND u.'
		. HA_Common::ID_COLUMN . ' = u_event.' . HA_Common::USER_ID_COLUMN;

		$query = HA_Query_Helper::apply_query_filters($query, $filters);

		$query .= ' GROUP BY u.' . HA_Common::IP_ADDRESS_COLUMN . ', u.' . HA_Common::SESSION_ID_COLUMN;
		$query .= ' ORDER BY u_event.' . HA_Common::RECORD_DATE_COLUMN . ' DESC';

		// pagination
		$item_count = $wpdb->query($query); //return the total number of affected rows

		$total_pages = ceil($item_count/$items_per_page);
		// adjust the query to take pagination into account
		if (!empty($page_num) && !empty($items_per_page)) {
			$offset=($page_num-1)*$items_per_page;
			$query .= ' LIMIT ' .(int)$offset. ',' .(int)$items_per_page;
		}

		$pagination_args = array( "total_items" => $item_count, "total_pages" => $total_pages, "per_page" => $items_per_page );
		$items = $wpdb->get_results($query, ARRAY_A);

		return array('pagination_args' => $pagination_args, 'items' => $items);
	}
	private function user_activity_table_data($filters, $items_per_page, $page_num) {
		global $wpdb;

		$query = 'SELECT u_env.' . HA_Common::DEVICE_COLUMN . ' AS ' . HA_Common::DEVICE_COLUMN . ','
		. 'u_env.' . HA_Common::BROWSER_COLUMN . ' AS ' . HA_Common::BROWSER_COLUMN . ','
		. 'u_env.' . HA_Common::OS_COLUMN . ' AS ' . HA_Common::OS_COLUMN . ','
		. 'u.' . HA_Common::SESSION_ID_COLUMN . ' AS ' . HA_Common::SESSION_ID_COLUMN . ', u.'
		. HA_Common::IP_ADDRESS_COLUMN . ' AS ' . HA_Common::IP_ADDRESS_COLUMN . ', u.' . HA_Common::USERNAME_COLUMN
		. ' AS ' . HA_Common::USERNAME_COLUMN . ', u_event.' . HA_Common::RECORD_DATE_COLUMN . ' AS '
		. HA_Common::RECORD_DATE_COLUMN . ', u_event.' . HA_Common::EVENT_TYPE_COLUMN . ' AS '
		. HA_Common::EVENT_TYPE_COLUMN . ', u_event.' . HA_Common::URL_COLUMN . ' AS '
		. HA_Common::URL_COLUMN . ', u_event.' . HA_Common::DESCRIPTION_COLUMN . ' AS '
		. HA_Common::DESCRIPTION_COLUMN . ', u_event.' . HA_Common::DATA_COLUMN . ' AS '
		. HA_Common::DATA_COLUMN . ', u.' . HA_Common::USER_ROLE_COLUMN . ' AS '
		. HA_Common::USER_ROLE_COLUMN . ', u_event.' . HA_Common::PAGE_WIDTH_COLUMN . ' AS '
		. HA_Common::PAGE_WIDTH_COLUMN . ', u_event.' . HA_Common::ID_COLUMN . ' AS ' . HA_Common::ID_COLUMN
		. ', u_env.' . HA_Common::ID_COLUMN . ' AS ' . HA_Common::USER_ENV_ID_COLUMN . ', u.' . HA_Common::ID_COLUMN . ' AS '
		. HA_Common::USER_ID_COLUMN . ' FROM ' . $wpdb->prefix . HA_Common::USER_TBL_NAME . ' AS u, ' . $wpdb->prefix
		. HA_Common::USER_EVENT_TBL_NAME . ' AS u_event, ' . $wpdb->prefix . HA_Common::USER_ENV_TBL_NAME
		. ' AS u_env WHERE u_event.' . HA_Common::USER_ENV_ID_COLUMN . ' = u_env.' . HA_Common::ID_COLUMN . ' AND u.'
		. HA_Common::ID_COLUMN . ' = u_event.' . HA_Common::USER_ID_COLUMN;

		$query = HA_Query_Helper::apply_query_filters($query, $filters);

		$query .= ' ORDER BY u_event.' . HA_Common::RECORD_DATE_COLUMN . ' DESC';

		// pagination
		$item_count = $wpdb->query($query); //return the total number of affected rows

		$total_pages = ceil($item_count/$items_per_page);
		// adjust the query to take pagination into account
		if (!empty($page_num) && !empty($items_per_page)) {
			$offset=($page_num-1)*$items_per_page;
			$query .= ' LIMIT ' .(int)$offset. ',' .(int)$items_per_page;
		}

		$pagination_args = array( "total_items" => $item_count, "total_pages" => $total_pages, "per_page" => $items_per_page );
		$items = $wpdb->get_results($query, ARRAY_A);

		return array('pagination_args' => $pagination_args, 'items' => $items);

	}
	private function user_activity_summary_data($filters) {
		global $wpdb;
	
		$query = 'SELECT MIN(u_event.' . HA_Common::RECORD_DATE_COLUMN . ') AS oldest_record_date, u.' . HA_Common::IP_ADDRESS_COLUMN . ', u.' . HA_Common::SESSION_ID_COLUMN . ', u.' . HA_Common::USERNAME_COLUMN
		. ', u.' . HA_Common::USER_ROLE_COLUMN . ', MAX(u_event.' . HA_Common::RECORD_DATE_COLUMN . ') as latest_record_date, COUNT(*) AS count_total'
		. ', count(case when ' . HA_Common::EVENT_TYPE_COLUMN . ' = "' . HA_Common::MOUSE_CLICK_EVENT_TYPE . '" THEN 1 ELSE null end) AS count_mouse_clicks '
		. ', count(case when ' . HA_Common::EVENT_TYPE_COLUMN . ' = "' . HA_Common::PAGE_VIEW_EVENT_TYPE . '" THEN 1 ELSE null end) AS count_page_views '
		. ', count(case when ' . HA_Common::EVENT_TYPE_COLUMN . ' = "' . HA_Common::AJAX_ACTION_EVENT_TYPE . '" THEN 1 ELSE null end) AS count_ajax_actions '
		. ', count(case when ' . HA_Common::EVENT_TYPE_COLUMN . ' = "' . HA_Common::TOUCHSCREEN_TAP_EVENT_TYPE . '" THEN 1 ELSE null end) AS count_touchscreen_taps '
		. ', u_env.' . HA_Common::DEVICE_COLUMN . ' AS ' . HA_Common::DEVICE_COLUMN . ', u_env.' . HA_Common::BROWSER_COLUMN
		. ' AS ' . HA_Common::BROWSER_COLUMN . ', u_env.' . HA_Common::OS_COLUMN . ' AS ' . HA_Common::OS_COLUMN . ', u_event.' . HA_Common::PAGE_WIDTH_COLUMN
		. ' FROM ' . $wpdb->prefix . HA_Common::USER_TBL_NAME . ' AS u, ' . $wpdb->prefix . HA_Common::USER_EVENT_TBL_NAME . ' AS u_event, '
		. $wpdb->prefix . HA_Common::USER_ENV_TBL_NAME . ' AS u_env WHERE u_event.' . HA_Common::USER_ENV_ID_COLUMN
		. ' = u_env.' . HA_Common::ID_COLUMN . ' AND u.' . HA_Common::ID_COLUMN . ' = u_event.' . HA_Common::USER_ID_COLUMN;
	
		$query = HA_Query_Helper::apply_query_filters($query, $filters);
	
		$query .= ' ORDER BY ' . HA_Common::RECORD_DATE_COLUMN . ' DESC';
	
		return $wpdb->get_row($query, OBJECT, 0);
	}
	private function event_statistics_table_report_data($filters, $items_per_page, $page_num) {
		global $wpdb;
		
		$count_users_query = '(SELECT COUNT(DISTINCT u_event.' . HA_Common::USER_ID_COLUMN . ') FROM '
		. $wpdb->prefix . HA_Common::USER_EVENT_TBL_NAME . ' as u_event, '
		. $wpdb->prefix . HA_Common::USER_ENV_TBL_NAME . ' AS u_env WHERE u_event.' . HA_Common::USER_ENV_ID_COLUMN
		. ' = u_env.' . HA_Common::ID_COLUMN;
		$count_users_query = HA_Query_Helper::apply_query_filters($count_users_query, $filters);
		$count_users_query .= ')';
		
		$count_pages_query = '(SELECT COUNT(DISTINCT u_event.' . HA_Common::URL_COLUMN . ') FROM '
		. $wpdb->prefix . HA_Common::USER_EVENT_TBL_NAME . ' as u_event, '
		. $wpdb->prefix . HA_Common::USER_ENV_TBL_NAME . ' AS u_env WHERE u_event.' . HA_Common::USER_ENV_ID_COLUMN
		. ' = u_env.' . HA_Common::ID_COLUMN;
		$count_pages_query = HA_Query_Helper::apply_query_filters($count_pages_query, $filters);
		$count_pages_query .= ')';
		
		$query = 'SELECT COUNT(*) as ' . HA_Common::TOTAL_COLUMN . ', u_event.' . HA_Common::EVENT_TYPE_COLUMN
		. ', u_event.' . HA_Common::RECORD_DATE_COLUMN . ' AS ' . HA_Common::RECORD_DATE_COLUMN
		. ', u_env.' . HA_Common::DEVICE_COLUMN . ' AS ' . HA_Common::DEVICE_COLUMN . ','
		. 'u_env.' . HA_Common::BROWSER_COLUMN . ' AS ' . HA_Common::BROWSER_COLUMN . ','
		. 'u_env.' . HA_Common::OS_COLUMN . ' AS ' . HA_Common::OS_COLUMN. ', ' 
		. $count_users_query . ' AS count_users' . ', ' . $count_pages_query . ' AS count_pages FROM '
		. $wpdb->prefix . HA_Common::USER_EVENT_TBL_NAME . ' as u_event, '
		. $wpdb->prefix . HA_Common::USER_ENV_TBL_NAME . ' AS u_env WHERE u_event.' . HA_Common::USER_ENV_ID_COLUMN
		. ' = u_env.' . HA_Common::ID_COLUMN;
	
		$query = HA_Query_Helper::apply_query_filters($query, $filters);
	
		$query .= ' GROUP BY ' . HA_Common::EVENT_TYPE_COLUMN;
		
		// pagination
		$item_count = $wpdb->query( $query ); //return the total number of affected rows
		$total_pages = ceil( $item_count / $items_per_page );
		// adjust the query to take pagination into account
		if ( !empty( $page_num ) && !empty( $items_per_page ) ) {
			$offset=($page_num-1)*$items_per_page;
			$query .= ' LIMIT ' .(int) $offset. ',' .(int) $items_per_page;
		}
	
		$pagination_args = array( "total_items" => $item_count, "total_pages" => $total_pages, "per_page" => $items_per_page );
		$items = $wpdb->get_results($query, ARRAY_A);
	
		return array('pagination_args' => $pagination_args, 'items' => $items);
	}
	private function event_line_graph_report_data($filters) {
		global $wpdb;

		// Time graph
		$query = 'SELECT DISTINCT DATE(  ' . HA_Common::RECORD_DATE_COLUMN . ' ) AS day, count(*) as count FROM ' . $wpdb->prefix . HA_Common::USER_TBL_NAME
		. ' AS u, ' . $wpdb->prefix . HA_Common::USER_EVENT_TBL_NAME . ' AS u_event, ' . $wpdb->prefix . HA_Common::USER_ENV_TBL_NAME
		. ' AS u_env WHERE u_event.' . HA_Common::USER_ENV_ID_COLUMN . ' = u_env.' . HA_Common::ID_COLUMN . ' AND u.'
		. HA_Common::ID_COLUMN . ' = u_event.' . HA_Common::USER_ID_COLUMN;

		$query = HA_Query_Helper::apply_query_filters($query, $filters);

		$query .= ' GROUP BY day ORDER BY ' . HA_Common::RECORD_DATE_COLUMN . ' DESC';

		$rows = $wpdb->get_results($query);
			
		$time_data = array();
		foreach ($rows as $row) {
			$day = $row->day;
			$count = $row->count;
			// TODO if a day has no data, then make it 0 visitors.
			// Otherwise, it is not plotted on the graph as 0.

			array_push($time_data, array((strtotime($day) * 1000), intval($count)));
		}

		return json_decode( json_encode( array( 'time_data' => $time_data ) ), false );
	}
	private function event_comparison_line_graph_report_data($filters) {
		global $wpdb;

		// Time graph data
		$query = 'SELECT DISTINCT DATE(  ' . HA_Common::RECORD_DATE_COLUMN . ' ) AS day, u_event.' . HA_Common::EVENT_TYPE_COLUMN . ', count(*) as count FROM '
		. $wpdb->prefix . HA_Common::USER_TBL_NAME . ' AS u, ' . $wpdb->prefix . HA_Common::USER_EVENT_TBL_NAME . ' AS u_event, '
		. $wpdb->prefix . HA_Common::USER_ENV_TBL_NAME . ' AS u_env WHERE u_event.' . HA_Common::USER_ENV_ID_COLUMN
		. ' = u_env.' . HA_Common::ID_COLUMN . ' AND u.' . HA_Common::ID_COLUMN . ' = u_event.' . HA_Common::USER_ID_COLUMN;

		$query = HA_Query_Helper::apply_query_filters($query, $filters);

		$query .= ' GROUP BY ' . HA_Common::EVENT_TYPE_COLUMN . ', day ORDER BY ' . HA_Common::RECORD_DATE_COLUMN . ' DESC';

		$rows = $wpdb->get_results($query);

		$time_data = array();
		foreach ($rows as $row) {
			$day = $row->day;
			$count = $row->count;
			$event_type = $row->event_type;
			// TODO if a day has no data, then make it 0 visitors.
			// Otherwise, it is not plotted on the graph as 0.

			$data = array();
			if (isset($time_data[$event_type]))
				$data = $time_data[$event_type];

			array_push($data, array((strtotime($day) * 1000), $count));
			$time_data[$event_type] = $data;
		}

		return json_decode( json_encode(array('time_data' => $time_data)), false);
	}
	private function event_totals_bar_graph_report_data($filters) {
		global $wpdb;
	
		// Counts data
		$query = 'SELECT count(*) as count, u_event.' . HA_Common::EVENT_TYPE_COLUMN . ' FROM ' . $wpdb->prefix . HA_Common::USER_TBL_NAME
		. ' AS u, ' . $wpdb->prefix . HA_Common::USER_EVENT_TBL_NAME . ' AS u_event, ' . $wpdb->prefix . HA_Common::USER_ENV_TBL_NAME
		. ' AS u_env WHERE u_event.' . HA_Common::USER_ENV_ID_COLUMN . ' = u_env.' . HA_Common::ID_COLUMN . ' AND u.'
		. HA_Common::ID_COLUMN . ' = u_event.' . HA_Common::USER_ID_COLUMN;
	
		$query = HA_Query_Helper::apply_query_filters($query, $filters);
	
		$query .= ' GROUP BY ' . HA_Common::EVENT_TYPE_COLUMN;
	
		$rows = $wpdb->get_results($query);
		$count_data = array();
		foreach ($rows as $row) {
			$event_type = $row->event_type;
			$count = $row->count;
			array_push($count_data, array($event_type, $count));
		}
	
		return json_decode( json_encode(array('count_data' => $count_data)), false);
	}
	private function clear_database() {
		$response = array('status' => 'OK', 'message' => 'Database cleared successfully');
		global $wpdb;
		try {
			$rows = $wpdb->get_results( 'DELETE FROM '.$wpdb->prefix.HA_Common::USER_EVENT_TBL_NAME.' WHERE 1' );
			$rows = $wpdb->get_results( 'DELETE FROM '.$wpdb->prefix.HA_Common::USER_ENV_TBL_NAME.' WHERE 1' );
			$rows = $wpdb->get_results( 'DELETE FROM '.$wpdb->prefix.HA_Common::USER_TBL_NAME.' WHERE 1' );
			$success_message .= 'Database cleared successfully.';
		} catch ( Exception $e ) {
			$response = array('error' => 'OK', 'message' => 'An error has occured. ' . $e->getMessage());
		}
		return $response;
	}
	private function add_retrieve_user_environment_details($params) {
		$user_id = $params['user_id'];
		$create_if_empty = $params['create_if_empty'];
		$browser = $params['browser'];
		$os = $params['os'];
		$device = $params['device'];
		$current_time = $params['current_time'];
		
		global $wpdb;
		$query = 'SELECT ' . HA_Common::ID_COLUMN . ' FROM ' . $wpdb->prefix . HA_Common::USER_ENV_TBL_NAME . ' WHERE '
		. HA_Common::USER_ID_COLUMN . ' = "' . $user_id . '"';
		$user_environment_id = $wpdb->get_col( $query, 0 );
			
		if ($user_environment_id == null && $create_if_empty) {
			$rowsAffected = $wpdb->insert( $wpdb->prefix . HA_Common::USER_ENV_TBL_NAME,
					array(
							HA_Common::BROWSER_COLUMN => $browser,
							HA_Common::OS_COLUMN => $os,
							HA_Common::DEVICE_COLUMN => $device,
							HA_Common::LAST_UPDT_DATE_COLUMN => $current_time,
							HA_Common::USER_ID_COLUMN => $user_id
					)
			);
			$user_environment_id = $wpdb->insert_id;
		} else {
			$user_environment_id = $user_environment_id[0];
		}

		return json_decode( json_encode(array('user_environment_id' => $user_environment_id)), false);
	}
	private function add_retrieve_user_details($params) {
		$ip_address = $params['ip_address'];
		$session_id = $params['session_id'];
		$create_if_empty = $params['create_if_empty'];
		$current_time = $params['current_time'];
		$user_role = $params['user_role'];
		$username = $params['username'];
		
		global $wpdb;
		$query = 'SELECT ' . HA_Common::ID_COLUMN . ' FROM ' . $wpdb->prefix . HA_Common::USER_TBL_NAME . ' WHERE ' . HA_Common::IP_ADDRESS_COLUMN
		. ' = "' . $ip_address . '" AND ' . HA_Common::SESSION_ID_COLUMN . ' = "' . $session_id . '"';

		$user_id = '';

		// don't insert if ip_address and session_id have not been provided
		if ($ip_address && $session_id) {
			$user_id = $wpdb->get_col( $query, 0 );
			if ($user_id == null && $create_if_empty) {
				$rowsAffected = $wpdb->insert( $wpdb->prefix . HA_Common::USER_TBL_NAME,
						array(
								HA_Common::IP_ADDRESS_COLUMN => $ip_address,
								HA_Common::LAST_UPDT_DATE_COLUMN => $current_time,
								HA_Common::SESSION_ID_COLUMN => $session_id,
								HA_Common::USER_ROLE_COLUMN => $user_role,
								HA_Common::USERNAME_COLUMN => $username,
						)
				);
				$user_id = $wpdb->insert_id;
			} else {
				$user_id = $user_id[0];
			}
		}

		return json_decode( json_encode(array('user_id' => $user_id )),false);
	}
}

?>