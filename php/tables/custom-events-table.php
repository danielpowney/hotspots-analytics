<?php 

if (!class_exists('WP_List_Table')) {
	require_once( ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'class-wp-list-table.php' );
}
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'common.php';

/**
 * A table for custom events
 *
 * @author dpowney
 *
 */
class HA_Custom_Event_Table extends WP_List_Table {
	const
	CHECKBOX_COLUMN 				= 'cb',
	DELETE_CHECKBOX 				= 'delete[]',
	DELETE_BULK_ACTION_NAME			= 'delete',
	DELETE_BULK_ACTION_LABEL		= 'Delete';
	
	
	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct( array(
				'singular'=> 'Custom Event Table',
				'plural' => 'Custom Events Table',
				'ajax'	=> false
		) );
	}
	
	/**
	 * (non-PHPdoc)
	 * @see WP_List_Table::extra_tablenav()
	 */
	function extra_tablenav( $which ) {
		if ( $which == "top" ){
			echo '';
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
				HA_Custom_Event_Table::CHECKBOX_COLUMN => '<input type="checkbox" />',
				HA_Common::ID_COLUMN => __(''),
				HA_Common::CUSTOM_EVENT_COLUMN => __('Custom Event jQuery Selector'),
				HA_Common::EVENT_TYPE_COLUMN => __('Event Type'),
				HA_Common::DESCRIPTION_COLUMN => __('Description'),
				HA_Common::IS_FORM_SUBMIT_COLUMN => __('Form Submit'),
				HA_Common::URL_COLUMN => __('Page URL')
		);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see WP_List_Table::prepare_items()
	 */
	function prepare_items() {
		global $wpdb;
	
		// Process any bulk actions first
		$this->process_bulk_action();
	
		// Register the columns
		$columns = $this->get_columns();
		$hidden = array(HA_Common::ID_COLUMN );
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);
	
		// get table data
		$query = 'SELECT * FROM '.$wpdb->prefix.HA_Common::CUSTOM_EVENT_TBL_NAME;
	
		// pagination
		$item_count = $wpdb->query( $query ); //return the total number of affected rows
		$items_per_page = 10;
		$page_num = ! empty( $_GET['paged'] ) ? $_GET['paged'] : '';
		if ( empty( $page_num ) || !is_numeric( $page_num ) || $page_num <= 0 ) {
			$page_num = 1;
		}
		$total_pages = ceil( $item_count / $items_per_page );
		// adjust the query to take pagination into account
		if ( !empty( $page_num ) && !empty( $items_per_page ) ) {
			$offset=($page_num-1)*$items_per_page;
			$query .= ' LIMIT ' .(int) $offset. ',' .(int) $items_per_page;
		}
		$this->set_pagination_args( array( "total_items" => $item_count, "total_pages" => $total_pages, "per_page" => $items_per_page ) );
	
		$this->items = $wpdb->get_results( $query, ARRAY_A );
	}
	
	/**
	 * Default column
	 * @param unknown_type $item
	 * @param unknown_type $column_name
	 * @return unknown|mixed
	 */
	function column_default( $item, $column_name ) {
		switch( $column_name ) {
			case HA_Common::ID_COLUMN :
			case HA_Common::EVENT_TYPE_COLUMN :
			case HA_Common::DESCRIPTION_COLUMN :
			case HA_Common::CUSTOM_EVENT_COLUMN : {
				echo $item[ $column_name ];
				break;
			}
			case HA_Common::IS_FORM_SUBMIT_COLUMN : {
				echo ($item[ $column_name ] == 0) ? 'false' : 'true';
				break;
			}
			case HA_Common::URL_COLUMN :
			case HA_Custom_Event_Table::CHECKBOX_COLUMN :
				return $item[ $column_name ];
			default:
				return print_r( $item, true ) ;
		}
	}
	
	/**
	 * url column
	 * @param unknown_type $item
	 * @return string
	 */
	function column_url( $item ) {
		echo stripslashes( $item['url'] );
	}
	
	/**
	 * checkbox column
	 * @param unknown_type $item
	 * @return string
	 */
	function column_cb( $item ) {
		return sprintf(
				'<input type="checkbox" name="'.HA_Custom_Event_Table::DELETE_CHECKBOX.'" value="%s" />', $item[HA_Common::ID_COLUMN]
		);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see WP_List_Table::get_bulk_actions()
	 */
	function get_bulk_actions() {
		$actions = array(
				HA_Custom_Event_Table::DELETE_BULK_ACTION_NAME => 'Delete'
		);
		return $actions;
	}
	
	/**
	 * Handles bulk actions
	 */
	function process_bulk_action() {
		if ($this->current_action() === HA_Custom_Event_Table::DELETE_BULK_ACTION_NAME) {
			global $wpdb;
	
			$checked = ( is_array( $_REQUEST['delete'] ) ) ? $_REQUEST['delete'] : array( $_REQUEST['delete'] );
	
			foreach($checked as $id) {
				$query = "DELETE FROM ". $wpdb->prefix.HA_Common::CUSTOM_EVENT_TBL_NAME . " WHERE " .  HA_Common::ID_COLUMN . " = " . $id;
				$results = $wpdb->query( $query );
			}
		}
	}
}

?>