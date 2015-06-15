<?php 

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'common.php';

/**
 * Interface for data services for wpdb
 * 
 * @author dpowney
 *
 */
interface HA_Data_Services {
	public function table_query($action, $filters, $items_per_page, $page_num);
	public function custom_query($action, $params);
	public function simple_query($action, $filters);
}

?>