<?php 

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'common.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'services' . DIRECTORY_SEPARATOR . 'local-data-services.php';

/**
 * Holds filters from session, get and post
 * @author dpowney
 *
 */
class HA_Query_Helper {

	public $browser = null;
	public $os = null;
	public $device = null;
	public $url = null;
	public $page_width = null;
	public $ip_address = null;
	public $session_id = null;
	public $last_days = null;
	public $username = null;
	public $role = null;
	public $event_type = null;
	public $user_id = null;
	public $event_types = null;

	/**
	 * Constructor.
	 * @param unknown_type $filters
	 */
	function __construct() {}
	
	/**
	 * Sets filters
	 */
	function set_filters($filters) {
		$this->browser = isset($filters['browser']) ? $filters['browser'] : null;
		$this->os = isset($filters['os']) ? $filters['os'] : null;
		$this->device = isset($filters['device']) ? $filters['device'] : null;
		$this->url = isset($filters['url']) ? $filters['url'] : null;
		$this->page_width = isset($filters['page_width']) ? $filters['page_width'] : null;
		$this->ip_address = isset($filters['ip_address']) ? $filters['ip_address'] : null;
		$this->session_id = isset($filters['session_id']) ? $filters['session_id'] : null;
		$this->last_days = isset($filters['last_days']) ? $filters['last_days'] : null;
		$this->username = isset($filters['username']) ? $filters['username'] : null;
		$this->role = isset($filters['role']) ? $filters['role'] : null;
		$this->event_type = isset($filters['event_type']) ? $filters['event_type'] : null;
		$this->event_types = isset($filters['event_types']) ? $filters['event_types'] : null;
	}
	
	/**
	 * Gets filters from session
	 */
	function get_session_filters($filters) {
		$this->ip_address= isset($_SESSION['ip_address']) && isset($filters['ip_address']) ? $_SESSION['ip_address'] : null;
		$this->session_id = isset($_SESSION['session_id']) && isset($filters['session_id']) ? $_SESSION['session_id'] : null;
		$this->browser = isset($_SESSION['browser']) && isset($filters['browser'])? $_SESSION['browser'] : null;
		$this->os = isset($_SESSION['os']) && isset($filters['os'])? $_SESSION['os'] : null;
		$this->device = isset($_SESSION['device']) && isset($filters['device'])? $_SESSION['device'] : null;
		$this->url= isset($_SESSION['url']) && isset($filters['url'])? $_SESSION['url'] : null;
		$this->page_width = isset($_SESSION['page_width']) && isset($filters['page_width'])? $_SESSION['page_width'] : null;
		$this->last_days = isset($_SESSION['last_days']) && isset($filters['last_days'])? $_SESSION['last_days'] : null;
		$this->username = isset($_SESSION['username']) && isset($filters['username'])? $_SESSION['username'] : null;
		$this->role = isset($_SESSION['role']) && isset($filters['role'])? $_SESSION['role'] : null;
		$this->event_type = isset($_SESSION['event_type']) && isset($filters['event_type'])? $_SESSION['event_type'] : null;
		$this->event_types = isset($_SESSION['event_types']) && isset($filters['event_types'])? $_SESSION['event_types'] : null;
	}
	
	function get_http_filters($method) {
		if ($method == 'GET') {
			
			if (isset($_GET['ip_address'])) {
				$this->ip_address =  $_GET['ip_address'];
			}
			
			if (isset($_GET['session_id'])) {
				$this->session_id =  $_GET['session_id'];
			}
			
			if ( isset($_GET["url"])) {
				$this->url = stripslashes($_GET["url"]);
			}
			
			if (isset($_GET["browser"])) {
				$this->browser = $_GET["browser"];
			}
			 
			if (isset($_GET["os"])) {
				$this->os = $_GET["os"];
			}
			
			if (isset($_GET["device"])) {
				$this->device = $_GET["device"];
			}
			
			if (isset($_GET["page_width"])) {
				$this->page_width = $_GET["page_width"];
			}
			
			if (isset($_GET['last_days'])) {
				$this->last_days = $_GET['last_days'];
			}
			
			if (isset($_GET['username'])) {
				$this->username = $_GET['username'];
			}
			
			if ( isset($_GET['role'])) {
				$this->role = $_GET['role'];
			}
			
			if ( isset($_GET['event_type'])) {
				$this->event_type = $_GET['event_type'];
			}
			
			if ( isset($_GET['event_types'])) {
				$this->event_types = $_GET['event_types'];
			} else {
				$this->event_types = array();
			}
		} else {
						
			if (isset($_POST['ip_address'])) {
				$this->ip_address =  $_POST['ip_address'];
			}
				
			if (isset($_POST['session_id'])) {
				$this->session_id =  $_POST['session_id'];
			}
			
			if ( isset($_POST["url"])) {
				$this->url = stripslashes($_POST["url"]);
			}
			
			if (isset($_POST["browser"])) {
				$this->browser = $_POST["browser"];
			}
			
			if (isset($_POST["os"])) {
				$this->os = $_POST["os"];
			}
				
			if (isset($_POST["device"])) {
				$this->device = $_POST["device"];
			}
				
			if (isset($_POST["page_width"])) {
				$this->page_width = $_POST["page_width"];
			}
				
			if (isset($_POST['last_days'])) {
				$this->last_days = $_POST['last_days'];
			}
				
			if (isset($_POST['username'])) {
				$this->username = $_POST['username'];
			}
				
			if ( isset($_POST['role'])) {
				$this->role = $_POST['role'];
			}
				
			if ( isset($_POST['event_type'])) {
				$this->event_type = $_POST['event_type'];
			}
			
			if ( isset($_POST['event_types'])) {
				$this->event_types = $_POST['event_types'];
			} else {
				$this->event_types = array();
			}
			
		}
	}
	
	/**
	 * Gets filters as an array
	 */
	function get_filters() {
		return array(
				'browser' => $this->browser,
				'os' => $this->os,
				'device' => $this->device,
				'url' => $this->url,
				'page_width' => $this->page_width,
				'ip_address' => $this->ip_address,
				'session_id' => $this->session_id,
				'last_days' => $this->last_days,
				'username' => $this->username,
				'role' => $this->role,
				'event_type' => $this->event_type,
				'event_types' => $this->event_types
			);
	}
	
	/**
	 * Resets session filters to empty
	 */
	function reset_session_filters() {
		$_SESSION['browser'] = null;
		$_SESSION['os'] = null;
		$_SESSION['device'] = null;
		$_SESSION['url'] = null;
		$_SESSION['page_width'] = null;
		$_SESSION['ip_address'] = null;
		$_SESSION['session_id'] = null;
		$_SESSION['last_days'] = null;
		$_SESSION['username'] = null;
		$_SESSION['role'] = null;
		$_SESSION['event_type'] = null;
		$_SESSION['event_types'] = null;
	}
	
	/**
	 * Resets filters to empty
	 */
	function reset_filters() {
		$this->ip_address=  null;
		$this->session_id =  null;
		$this->browser = null;
		$this->os =  null;
		$this->device =  null;
		$this->url= null;
		$this->page_width = null;
		$this->last_days =  null;
		$this->username =null;
		$this->role = null;
		$this->event_type = null;
		$this->event_types = null;
	}
	
	/**
	 * Sets the filters to the session
	 */
	function set_session_filters() {
		$_SESSION['browser'] = $this->browser;
		$_SESSION['os'] = $this->os;
		$_SESSION['device'] = $this->device;
		$_SESSION['url'] = $this->url;
		$_SESSION['page_width'] = $this->page_width;
		$_SESSION['ip_address'] = $this->ip_address;
		$_SESSION['session_id'] = $this->session_id;
		$_SESSION['last_days'] = $this->last_days;
		$_SESSION['username'] = $this->username;
		$_SESSION['role'] = $this->role;
		$_SESSION['event_type'] = $this->event_type;
		$_SESSION['event_types'] = $this->event_types;
	}
	
	/**
	 * Shows the filters
	 * 
	 * @param unknown_type $filters
	 */
	function show_filters($filters) {
		
		$count = 0;
		$index = 0;
		$filters_per_row = 5;
		foreach ($filters as $filter_key => $filter_value) {
			if ($filter_value == true) {
				
				$count++;
				$index++;
				
				switch ($filter_key) {
					case 'ip_address' :
						?>
						<label for="ip_address">IP Address</label>
						<input type="text" name="ip_address" id="ip_address" value="<?php echo $this->ip_address; ?>" />
						<?php
						break;
					case 'session_id' :
						?>
						<label for="session_id">Session ID</label>
						<input type="text" name="session_id" id="session_id" value="<?php echo $this->session_id; ?>" />
						<?php
						break;
					case 'event_type' :
						global $ha_admin_controller;
						$rows = $ha_admin_controller->get_data_services()->custom_query('distinct_event_type_from_user_events', array());
						?>
									
						<label for="event_type">Event Type</label>
						<select name="event_type" id="event_type">
							<option value="" <?php if (!isset($this->event_type)) echo 'selected="selected"'; ?>>All</option>
							<?php
							foreach ($rows as $row) {
								?>
								<option value="<?php echo $row->event_type; ?>" <?php if ($this->event_type == $row->event_type) echo 'selected="selected"'; ?>><?php echo $row->event_type; ?></option>
								<?php
							}
							?>
						</select>
						<?php					
						break;
					case 'event_types' :
						global $ha_admin_controller;
						$rows = $ha_admin_controller->get_data_services()->custom_query('distinct_event_type_from_user_events', array());
							
						$event_types = array();
						foreach ($rows as $row) {
							array_push($event_types,  $row->event_type);
						}
						?>
									
						<label class="ha_checkbox_label" for="event_types[]">Event Types</label>
						<?php
						foreach ($event_types as $event_type) {
							?>
							<label class="ha_checkbox_label">
								<input name="event_types[]" type="checkbox" value="<?php echo $event_type; ?>" <?php 
								if (is_array($this->event_types) && in_array($event_type, $this->event_types)) {
									echo 'checked="checked"';
								}
								
								?>><?php echo $event_type; ?></input>
							</label>
							<?php
						}
						
						$count = $filters_per_row;
							
						break;
					case 'url' :
						global $ha_admin_controller;
						$rows = $ha_admin_controller->get_data_services()->custom_query('distinct_url_from_user_events', array());
						?>
						<label for="url">Page URL</label>
						<select name="url" id="url" class="regular-text">
							<option value="">All</option>
							<?php
							foreach ($rows as $row) {
								$current_url = stripslashes($row->url);
								$selected = '';
								if ($current_url == $this->url)
									$selected = ' selected="selected"';
								echo '<option value="' . addslashes($current_url) . '"' . $selected . '>' . $current_url . '</option>';
							}
							?>
						</select>
						<?php
						break;
					case 'page_width' :
						global $ha_admin_controller;
						$rows = $ha_admin_controller->get_data_services()->custom_query('distinct_page_width_from_user_events', array());
						?>
						<label for="page_width">Page Width</label>
						<select name="page_width" id="width">
							<option value="">All</option>
							<?php
							foreach ($rows as $row) {
								$current_width= $row->page_width;
								$selected = '';
								if ($current_width == $this->page_width)
									$selected = ' selected="selected"';
								echo '<option value="' . $current_width . '"' . $selected . '>' . $current_width . 'px</option>';
							}
							?>
						</select>
						<?php
						break;
					case 'browser' :
						global $ha_admin_controller;
						$rows = $ha_admin_controller->get_data_services()->custom_query('distinct_browser_from_user_env', array());
						?>
						<label for="browser">Browser</label>
						<select name="browser" id="browser">
							<option value="">All</option>
							<?php 
							foreach ($rows as $row) {
								$current_browser = $row->browser;
								$selected = '';
								if ($current_browser == $this->browser)
									$selected = ' selected="selected"';
								echo '<option value="' . $current_browser . '"' . $selected . '>' . $current_browser . '</option>';
							}
							?>
						</select>
						<?php
						break;
					case 'os' :
						global $ha_admin_controller;
						$rows = $ha_admin_controller->get_data_services()->custom_query('distinct_os_from_user_env', array());
						?>
						<label for="os">Operating System</label>
						<select name="os" id="os">
							<option value="">All</option>
							<?php
							foreach ($rows as $row) {
								$current_os = $row->os;
								$selected = '';
								if ($current_os == $this->os)
									$selected = ' selected="selected"';
								echo '<option value="' . $current_os . '"' . $selected . '>' . $current_os . '</option>';
							}
							?>
						</select>
						<?php
						break;
					case 'device' :
						global $ha_admin_controller;
						$rows = $ha_admin_controller->get_data_services()->custom_query('distinct_device_from_user_env', array());
						?>
						<label for="device">Device</label>
						<select name="device" id="device">
							<option value="">All</option>
							<?php
							foreach ($rows as $row) {
								$current_device = $row->device;
								$selected = '';
								if ($current_device == $this->device)
									$selected = ' selected="selected"';
								echo '<option value="' . $current_device . '"' . $selected . '>' . $current_device . '</option>';
							}
							?>
						</select>
						<?php
						break;
					case 'last_days' :
						?>
						<labe for="last_days">Days</labe>
						<select name="last_days" id="last_days">
							<option value="" <?php if (!isset($this->last_days)) echo 'selected="selected"'; ?>></option>
							<option value="0" <?php if ($this->last_days == '0') echo 'selected="selected"'; ?>>Today</option>
							<option value="1" <?php if ($this->last_days == '1') echo 'selected="selected"'; ?>>Yesterday</option>
							<option value="7" <?php if ($this->last_days == '7') echo 'selected="selected"'; ?>>Last 7 days</option>
							<option value="30" <?php if ($this->last_days == '30') echo 'selected="selected"'; ?>>Last 30 days</option>
							<option value="60" <?php if ($this->last_days == '60') echo 'selected="selected"'; ?>>Last 60 days</option>
						</select>
						<?php	
						break;
					case 'username' :
						?>
						<label for="username">Username</label>
						<input type="text" name="username" id="username" value="<?php echo $this->username; ?>" />
						<?php
						break;
					case 'role' :
						global $ha_admin_controller;
						$rows = $ha_admin_controller->get_data_services()->custom_query('distinct_role_from_user', array());
							
						?>
						<label for="role">Role</label>
						<select name="role" id="role">
							<option value=""></option>
							<?php
						
							foreach ($rows as $row) {
								echo '<option value="' . $row->role . '"';
								if ($row->role == $this->role) {
										echo 'selected="selected"';
								}
								echo '>' . $row->role . '</option>'; 
							}
							?>
						</select>
						<?php
						break;
					default : 
						break;
				}
				
				if (count($filters) == $index) {
					echo '<input type="submit" class="button" value="Filter" />';
				}
				
				if ($count % $filters_per_row == 0 && count($filters) != $index) {
					echo '<br />';
				}
			}
			
			
		}
		
			
	}
	
	/**
	 * Apply filters to query
	 * 
	 * @param unknown_type $query
	 * @param unknown_type $filters
	 * @return string query
	 */
	public static function apply_query_filters(&$query, $filters) {
	
		// Check whether to start query with WHERE or AND if WHERE already exists
		$query_filter_start = ' AND ';
		if (strpos($query,'WHERE') < 0 || strpos($query,'WHERE') == false) {
			$query_filter_start = ' WHERE';
		}
	
		$query_filters = '';
		
		// ignore width, if false use the width allowance to filter a page width range
		if (isset($filters['ignore_width']) && isset($filters['width_allowance'])
				&& $filters['ignore_width'] == false && is_int($filters['width_allowance'])
				&& isset($filters['page_width']) && is_int($filters['page_width'])
				&& ((isset($filters['exact_width']) && $filters['exact_width'] == false)
						|| !isset($filters['exact_width']))) {
		
			$width_allowance = $filters['width_allowance'];
			$page_width = $filters['page_width'];
			$diff_left = $page_width - $width_allowance;
			$diff_right = $page_width + $width_allowance;
			$query_filters .= ' AND u_event.'.HA_Common::PAGE_WIDTH_COLUMN.' >= ' . $diff_left .
			' AND u_event.'.HA_Common::PAGE_WIDTH_COLUMN.' <= '. $diff_right;
		} else if (isset($filters['page_width']) && is_numeric($filters['page_width'])) {
			$query_filters .= $query_filter_start . ' u_event.' . HA_Common::PAGE_WIDTH_COLUMN . ' = ' . $filters['page_width'];
			$query_filter_start = ' AND';
		}
		
		// user event id
		if ( isset($filters['user_event_id']) && is_int($filters['user_event_id']) ) {
			$query_filters .= ' AND u_event.' . HA_Common::ID_COLUMN . ' = ' . $filters['user_event_id'];
		}
		
		// ignore device
		if ( isset($filters['ignore_device']) && $filters['ignore_device'] == false && isset($filters['device'])) {
			$query_filters .= ' AND u_env.' . HA_Common::DEVICE_COLUMN . ' = "' . $filters['device'] . '"';
		}
		
		// ignore os
		if (isset($filters['ignore_os']) && $filters['ignore_os'] == false && isset($filters['os'])) {
			$query_filters .= ' AND u_env.' . HA_Common::OS_COLUMN . ' = "' . $filters['os'] . '"';
		}
		
		// ignore browser
		if (isset($filters['ignore_browser']) && $filters['ignore_browser'] == false && isset($filters['browser'])) {
			$query_filters .= ' AND u_env.' . HA_Common::BROWSER_COLUMN . ' = "' . $filters['browser'] . '"';
		}
		
		if (isset($filters['hide_roles']) && is_array($filters['hide_roles']) && count($filters['hide_roles']) > 0) {
			foreach ($filters['hide_roles'] as $role) {
				if ($role == HA_Common::NO_ROLE_VALUE)
					$query_filters .= ' AND u.' . HA_Common::USER_ROLE_COLUMN . ' != ""';
				else
					$query_filters .= ' AND u.' . HA_Common::USER_ROLE_COLUMN . ' != "' . $role . '"';
			}
		}
		
		// event types
		if (isset($filters['event_types']) && is_array($filters['event_types'])) {
			$event_types = $filters['event_types'];
			$count = count($event_types);
		
			if ($count > 0) {
				$query_filters .= ' AND ';
				$query_filters .= '(';
			}
		
			$index = 0;
			foreach ($event_types as $event_type) {
				if ($index > 0) {
					$query_filters .= ' OR ';
				}
				$query_filters .= 'u_event.' . HA_Common::EVENT_TYPE_COLUMN . ' = "' . $event_type . '"';
				$index++;
			}
		
			if ($count > 0) {
				$query_filters .= ')';
			}
		} else if (isset($filters['event_type']) && strlen($filters['event_type']) > 0) {
			$query_filters .= $query_filter_start . ' u_event.' . HA_Common::EVENT_TYPE_COLUMN . ' = "' . $filters['event_type'] . '"';
			$query_filter_start = ' AND';
		}
	
		if (isset($filters['url']) && strlen($filters['url']) > 0) {
			$query_filters .= $query_filter_start . 'u_event.' . HA_Common::URL_COLUMN . ' = "' . $filters['url'] . '"';
			$query_filter_start = ' AND';
		}
	
		if (isset($filters['browser']) && strlen($filters['browser']) > 0) {
			$query_filters .= $query_filter_start . ' u_env.' . HA_Common::BROWSER_COLUMN . ' = "' . $filters['browser'] . '"';
			$query_filter_start = ' AND';
		}
	
		if (isset($filters['os']) && strlen($filters['os']) > 0) {
			$query_filters .= $query_filter_start . ' u_env.' . HA_Common::OS_COLUMN . ' = "' . $filters['os'] . '"';
			$query_filter_start = ' AND';
		}
			
		if (isset($filters['device']) && strlen($filters['device']) > 0) {
			$query_filters .= $query_filter_start . ' u_env.' . HA_Common::DEVICE_COLUMN . ' = "' . $filters['device'] . '"';
			$query_filter_start = ' AND';
		}
	
		if (isset($filters['ip_address']) && strlen($filters['ip_address']) > 0) {
			$query_filters .= $query_filter_start . ' u.' . HA_Common::IP_ADDRESS_COLUMN . ' = "' . $filters['ip_address'] . '"';
			$query_filter_start = ' AND';
		}
		if (isset($filters['session_id']) && strlen($filters['session_id']) > 0) {
			$query_filters .= $query_filter_start . ' u.' . HA_Common::SESSION_ID_COLUMN . ' = "' . $filters['session_id'] . '"';
			$query_filter_start = ' AND';
		}
	
	
		if (isset($filters['username']) && strlen($filters['username']) > 0) {
			$query_filters .= $query_filter_start . ' u.' . HA_Common::USERNAME_COLUMN . ' LIKE "%' . $filters['username'] . '%"';
			$query_filter_start = ' AND';
		}
	
		if (isset($filters['role']) && strlen($filters['role']) > 0) {
			$query_filters .= $query_filter_start.  ' u.' . HA_Common::USER_ROLE_COLUMN . ' = "' . $filters['role'] . '"';
			$query_filter_start = ' AND';
		}
	
		if (isset($filters['last_days']) && strlen($filters['last_days']) > 0) {
			$query_filters .= $query_filter_start . ' u_event.' . HA_Common::RECORD_DATE_COLUMN . ' >= DATE_SUB(NOW(), INTERVAL ' . $filters['last_days'] . ' DAY)';
			$query_filter_start = ' AND';
		}
		
		return $query . $query_filters;
	}
}

?>