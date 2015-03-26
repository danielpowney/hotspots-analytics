<?php 

if (!class_exists('WP_List_Table')) {
	require_once( ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'class-wp-list-table.php' );
}
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'common.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'query-helper.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'services' . DIRECTORY_SEPARATOR . 'local-data-services.php';


/**
 * A table for filtering heat map details
 *
 * @author dpowney
 *
 */
class HA_Heatmaps_Table extends WP_List_Table {

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct( array(
				'singular'=> 'Heat Map Detail',
				'plural' => 'Heat Maps Details',
				'ajax'	=> false
		) );
	}

	/** (non-PHPdoc)
	 * @see WP_List_Table::extra_tablenav()
	 */
	function extra_tablenav( $which ) {
		if ( $which == "top" ) {			
			$query_helper = new HA_Query_Helper();
			
			$query_helper = new HA_Query_Helper();
			$query_helper->get_session_filters(array('last_days' => true, 'url' => true, 'page_width' => true, 'browser' => true, 'device' => true, 'os' => true));
			
			$filters = array(
					'page_width' => true,
					'last_days' => true,
					'url' => true,
					'device' => true,
					'os' => true,
					'browser' => true
			);
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
				'id' => __('Id'),
				'user_id' => __('User Id'),
				'user_env_id' => __('User Env Id'),
				'count' => __('Count'),
				'event_type' => __('Event Type'),
				'url' => __('URL'),
				'device' => __('Device'),
				'browser' => __('Browser'),
				'os' => __('Operating System'),
				'record_date' => __('Record Date'),
				'last_updt_date' => __('Last Update Date'),
				'page_width' => __('Page Width'),
				'description' => __('Description'),
				'action' => __('Action')
		);
	}

	/**
	 * (non-PHPdoc)
	 * @see WP_List_Table::prepare_items()
	 */
	function prepare_items() {
		$query_helper = new HA_Query_Helper();
		$query_helper->get_session_filters(array('last_days' => true, 'url' => true, 'page_width' => true, 'browser' => true, 'device' => true, 'os' => true));
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$query_helper->get_http_filters('POST');
		} else {
			$query_helper->get_http_filters('GET');
		}
		$query_helper->set_session_filters();
		
		// Register the columns
		$columns = $this->get_columns();

		$hidden = array('last_updt_date', 'user_env_id', 'user_id', 'device', 'browser', 'os', 'id', 'record_date', 'description');
			
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);

		$items_per_page = 25;
		// Ensure paging is reset on filter submit by checking HTTP method as well
		$page_num = !empty($_GET["paged"]) && ($_SERVER['REQUEST_METHOD'] != 'POST') ? mysql_real_escape_string($_GET["paged"]) : '';
		if (empty($page_num) || !is_numeric($page_num) || $page_num<=0 ) {
			$page_num = 1;
		}
		
		global $ha_admin_controller;
		$data = $ha_admin_controller->get_data_services()->table_query('heatmaps_table_data', $query_helper->get_filters(), $items_per_page, $page_num);
		
		$this->set_pagination_args( $data['pagination_args'] );
		$this->items =   $data['items'];
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
			case 'page_width':
				return $item[$column_name];
				break;
			default:
				echo $item[$column_name];
				break;
		}
	}
	
	function column_action( $item ){
		
		$id = $item[ 'id' ];
		$url = $item[ 'url' ];
		$width = $item[ 'page_width' ];
		
		echo '<input type="hidden" id="' . $id . '-url" name="' . $id . '-url" value="' . addslashes($url) . '"></input>';
		echo '<input type="hidden" id="' . $id . '-page_width" name="' . $id . '-page_width" value="' . $width . '"></input>';
		echo '<input id="' . $id .'" type="button" class="button view-heat-map-button" value="View Heatmap" />';
	}
	
	function column_page_width( $item ){
		echo $item['page_width'] . 'px';
	}
}

?>