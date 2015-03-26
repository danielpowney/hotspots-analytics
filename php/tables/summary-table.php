<?php 

if (!class_exists('WP_List_Table')) {
	require_once( ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'class-wp-list-table.php' );
}
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'common.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'query-helper.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'services' . DIRECTORY_SEPARATOR . 'local-data-services.php';

/**
 * A table for summary
 *
 * @author dpowney
 *
 */
class HA_Summary_Table extends WP_List_Table {
	
	
	
	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct( array(
				'singular'=> 'Summary Table',
				'plural' => 'Summary Table',
				'ajax'	=> false
		) );
	}
	
	/**
	 * (non-PHPdoc)
	 * @see WP_List_Table::extra_tablenav()
	 */
	function extra_tablenav( $which ) {
	if ( $which == "top" ) {
			$query_helper = new HA_Query_Helper();
			$filters = array(
					'last_days' => true,
					'device' => true,
					'os' => true,
					'browser' => true
			);
			$query_helper->get_session_filters($filters);
			$query_helper->show_filters($filters);
			
		}
		if ( $which == "bottom" ){
		}
		if ( $which == "bottom" ){
			echo '';
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see WP_List_Table::get_columns()
	 */
	function get_columns() {
		return $columns= array(
				HA_Common::ID_COLUMN => __(''),
				HA_Common::EVENT_TYPE_COLUMN => __('Event Type'),
				HA_Common::TOTAL_COLUMN => __('Total Count'),
				HA_Common::AVG_PER_USER_COLUMN => __('Average per User'),
				HA_Common::AVG_PER_URL_COLUMN => __('Average per Page URL'),
		);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see WP_List_Table::prepare_items()
	 */
	function prepare_items() {
		global $wpdb;
		
		// Register the columns
		$columns = $this->get_columns();
		$hidden = array(HA_Common::ID_COLUMN );
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);
	
		// get table data
		$query_helper = new HA_Query_Helper();
		$query_helper->get_session_filters(array('last_days' => true, 'device' => true, 'os' => true, 'browser' => true));
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$query_helper->get_http_filters('POST');
		} else {
			$query_helper->get_http_filters('GET');
		}
		$query_helper->set_session_filters();
		
		$items_per_page = 25;
		// Ensure paging is reset on filter submit by checking HTTP method as well
		$page_num = !empty($_GET["paged"]) && ($_SERVER['REQUEST_METHOD'] != 'POST') ? mysql_real_escape_string($_GET["paged"]) : '';
		if (empty($page_num) || !is_numeric($page_num) || $page_num<=0 ) {
			$page_num = 1;
		}
		
		global $ha_admin_controller;
		$data = $ha_admin_controller->get_data_services()->table_query('event_statistics_table_report_data', $query_helper->get_filters(), $items_per_page, $page_num);
		
		if ( isset( $data['pagination_args'])) {
			$this->set_pagination_args( $data['pagination_args'] );
		}
		if ( isset( $data['items'] )) {
			$this->items = $data['items'];
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
			case HA_Common::EVENT_TYPE_COLUMN :
			case HA_Common::TOTAL_COLUMN : {
				echo $item[ $column_name ];
				break;
			}
			case HA_Common::AVG_PER_URL_COLUMN : {
				$avg_per_url = ($item['count_pages'] > 0) ? round(($item['total'] / $item['count_pages']), 2) : '0';
				echo $avg_per_url;
				break;
			}
			case HA_Common::AVG_PER_USER_COLUMN :
				$avg_per_user = ($item['count_users'] > 0) ? round(($item['total'] / $item['count_users']), 2) : '0';
				echo $avg_per_user;
				break;
			default:
				return print_r( $item, true ) ;
		}
	}
}

?>