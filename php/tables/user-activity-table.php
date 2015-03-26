<?php 

if (!class_exists('WP_List_Table')) {
	require_once( ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'class-wp-list-table.php' );
}
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'common.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'query-helper.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'services' . DIRECTORY_SEPARATOR . 'local-data-services.php';


/**
 * A table for user activity
 *
 * @author dpowney
 *
 */
class HA_User_Activity_Table extends WP_List_Table {
	
	private $row_sequence_map = array();

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct( array(
				'singular'=> 'User Activity',
				'plural' => 'User Activities',
				'ajax'	=> false
		) );
	}

	/** (non-PHPdoc)
	 * @see WP_List_Table::extra_tablenav()
	 */
	function extra_tablenav( $which ) {
		if ( $which == "top" ) {
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
				'sequence' => __('Sequence'),
				'event_type' => __('Event Type'),
				'description' => __('Description'),
				'record_date' => __('Record Date'),
				'time_elapsed' => __('Time Elapsed'),
				'url' => __('Page URL'),
				'action' => __('Action')
		);
	}

	/**
	 * (non-PHPdoc)
	 * @see WP_List_Table::prepare_items()
	 */
	function prepare_items() {
		
		$query_helper = new HA_Query_Helper();
		$query_helper->get_session_filters(array('ip_address' => true, 'session_id' => true, 'event_type' => true, 'url' => true));
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
		$data = $ha_admin_controller->get_data_services()->table_query('user_activity_table_data' ,$query_helper->get_filters(), $items_per_page, $page_num);
		
		$this->set_pagination_args( $data['pagination_args'] );
		$this->items =  isset($data['items']) ? $data['items'] : array();
		
		$index = count($this->items) + (($page_num-1) * $items_per_page);
		foreach ($this->items as &$item) {
			$item['sequence'] = $index--;
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
				return $item[$column_name];
				break;
			case 'time_elapsed':
				$sequence = intval($item['sequence']);
				if ($sequence > 1) {
					$current_activity_time = strtotime($item['record_date']);
					$page_num = $this->get_pagenum();
					$items_per_page = $this->get_pagination_arg('per_page');
					$previous_row = $this->items[(count($this->items) - $sequence + 1) + (($page_num-1) * $items_per_page)];
					$previous_activity_time = strtotime($previous_row['record_date']);
					$human_time_diff = HA_Common::human_time_diff($previous_activity_time, $current_activity_time);
					echo $human_time_diff;
				}
				
				break;
			case 'url':
				echo '<a href="' . $item[$column_name] . '">' . $item[$column_name] . '</a>';
				break;
			default:
				echo $item[$column_name];
				break;
		}
	}
	
	function column_action( $item ){
	
		if ($item[ 'event_type'] == HA_Common::MOUSE_CLICK_EVENT_TYPE || $item['event_type'] == HA_Common::TOUCHSCREEN_TAP_EVENT_TYPE) {
			$id = $item[ 'id' ];
			$url = $item[ 'url' ];
			$page_width = $item[ 'page_width' ];
		
			echo '<input type="hidden" id="' . $id . '-url" name="' . $id . '-url" value="' . addslashes($url) . '"></input>';
			echo '<input type="hidden" id="' . $id . '-page_width" name="' . $id . '-page_width" value="' . $page_width . '"></input>';
			echo '<input type="hidden" id="' . $id . '-user_event_id" name="' . $id . '-user_event_id" value="' . $id . '"></input>';
			echo '<input id="' . $id .'" type="button" class="button view-heat-map-button" value="View Heatmap" />';
		}
	}
}

?>