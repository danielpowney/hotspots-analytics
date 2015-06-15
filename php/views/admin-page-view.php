<?php 

require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'common.php';
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'tables' . DIRECTORY_SEPARATOR . 'heatmaps-table.php';
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'reports-view.php';
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'tables' . DIRECTORY_SEPARATOR . 'custom-events-table.php';
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'tables' . DIRECTORY_SEPARATOR . 'users-table.php';
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'tables' . DIRECTORY_SEPARATOR . 'user-activity-table.php';

class HA_Admin_Page_View {
	
	
	public static function settings_page($tabs) {
		$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : HA_Common::GENERAL_SETTINGS_TAB;
		?>
		<div class="wrap">
			<?php 
			
			HA_Admin_Page_View::page_header('Settings');
			HA_Admin_Page_View::show_page_tabs(HA_Common::SETTINGS_PAGE_SLUG, $tabs, $current_tab);
			
			if ( isset( $_GET['updated'] ) && isset( $_GET['page'] ) ) {
				add_settings_error('general', 'settings_updated', __('Settings saved.'), 'updated');
			}
			
			settings_errors();
			
			if ($current_tab == HA_Common::GENERAL_SETTINGS_TAB)
				HA_Admin_Page_View::show_settings_form(HA_Common::GENERAL_SETTINGS_KEY);
			else if ($current_tab == HA_Common::SCHEDULE_SETTINGS_TAB)
				HA_Admin_Page_View::show_settings_form(HA_Common::SCHEDULE_SETTINGS_KEY);
			else if ($current_tab == HA_Common::HEAT_MAP_SETTINGS_TAB)
				HA_Admin_Page_View::show_settings_form(HA_Common::HEAT_MAP_SETTINGS_KEY);
			else if ($current_tab == HA_Common::URL_FILTERS_SETTINGS_TAB) {
				HA_Admin_Page_View::show_settings_form(HA_Common::URL_FILTERS_SETTINGS_KEY);
			} else if ($current_tab == HA_Common::DATABASE_SETTINGS_TAB) {
				?>
				<form method="post" name="<?php echo HA_Common::DATABASE_SETTINGS_KEY; ?>" action="options.php" class="hut-settings-form">
					<?php
					wp_nonce_field( 'update-options' );
					settings_fields( HA_Common::DATABASE_SETTINGS_KEY );
					do_settings_sections( HA_Common::DATABASE_SETTINGS_KEY );
					?>
					<p class="submit">
						<?php 
						submit_button(null, 'primary', 'submit', false, null);
						submit_button('Clear Database', 'delete', 'clear-database', false, null);
						?>
					</p>
					<input type="hidden" name="clear-database-flag" id="clear-database-flag" value="false" />
				</form>
				<?php
			} else if ($current_tab == HA_Common::CUSTOM_EVENTS_SETTINGS_TAB) {
				echo '<h3>Custom Events</h3>';
				if ( isset( $_POST['eventType']) && isset( $_POST['customEvent'])) {
					$event_type = isset($_POST['eventType']) ? $_POST['eventType'] : '';
					$custom_event = isset($_POST['customEvent']) ? $_POST['customEvent'] : '';
					$description = isset($_POST['description']) ? $_POST['description'] : '';
				
					$url = isset( $_POST['url'] ) ? trim( $_POST['url'] ) : '';
					$url = HA_Common::normalize_url( $url );
					$url = addslashes($url);
				
					$is_form_submit = isset( $_POST['isFormSubmit'] ) ? true : false;
					$is_mouse_click = isset( $_POST['isMouseClick'] ) ? true : false;
					$is_touchscreen_tap = isset( $_POST['isTouchscreenTap'] ) ? true : false;
				
					$valid_input = true;
					if ( strlen( trim( $custom_event ) ) == 0 ) {
						echo '<div class="error"><p>An event type is required.</p></div>';
						$valid_input = false;
					}
					if ( strlen( trim( $custom_event ) ) == 0) {
						echo '<div class="error"><p>A custom event jQuery selector is required.</p></div>';
						$valid_input = false;
					}
				
					if ($valid_input == true) {
						global $wpdb;
						try {
							$results = $wpdb->insert( $wpdb->prefix.HA_Common::CUSTOM_EVENT_TBL_NAME, array( 
									HA_Common::DESCRIPTION_COLUMN => $description,
									HA_Common::CUSTOM_EVENT_COLUMN => $custom_event, 
									HA_Common::EVENT_TYPE_COLUMN => $event_type, 
									HA_Common::URL_COLUMN => $url, 
									HA_Common::IS_FORM_SUBMIT_COLUMN => $is_form_submit,
									HA_Common::IS_MOUSE_CLICK_COLUMN => $is_mouse_click,
									HA_Common::IS_TOUCHSCREEN_TAP_COLUMN => $is_touchscreen_tap ) );
							echo '<div class="success"><p>Custom event added successfully.</p></div>';
						} catch ( Exception $e ) {
							echo '<div class="error"><p>An error occurred. ' . $e->getMessage() . '</p></div>';
						}
					}
				
				}
				
				?>
				<form method="post">
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row">Custom Event</th>
								<td>
									<input type="text" name="customEvent" id="customEvent" value="" />
									<p class="description">Enter a jQuery element selector.</p>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">Event Type</th>
								<td>
									<input type="text" name="eventType" id="eventType" value="" />
									<p class="description">Categorise the event with a named type.</p>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">Trigger events</th>
								<td>
									<input type="checkbox" name="isMouseClick" id="isMouseClick" value="" checked="checked"/>
									<label for="isMouseClick">Mouse click?</label><br />
									<input type="checkbox" name="isToushcreenTap" id="isToushcreenTap" value="" />
									<label for="isTouchscreenTap">Touchscreen tap?</label><br />
									<input type="checkbox" name="isFormSubmit" id="isFormSubmit" value="" />
									<label for="isTouchscreenTap">Form submit?</label>
									<p class="description">Is the custom event associated with a form submit JavaScript event? If none are checked, mouse click is defaulted.</p>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">Description</th>
								<td>
									<input type="text" name="description" id="description" value="" />
									<p class="description">Add a description of the event.</p>
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">URL</th>
								<td>
									<input class="regular-text" type="text" name="url" id="url" value="" />&nbsp(Optional, leave empty to target all URLs)
									<p class="description">You can enter a URL to target a specific page.</p>
								</td>
							</tr>
						</tbody>
					</table>
					
					<input type="submit" class="button button-secondary" value="Add Custom Event" />
				</form>
		
				<br />
							
				<form method="post">
					<?php 
					$custom_event_table = new HA_Custom_Event_Table();
					$custom_event_table->prepare_items();
					$custom_event_table->display();
					?>
				</form>
				<?php 
			} 
			?>
			
		</div>
		<div class="clear" />
		<?php 
	}
	
	public static function heatmaps_page() {
		?>
		<div class="wrap">
			<?php 
			HA_Admin_Page_View::page_header('Heatmaps');
			?>
			<form method="post">
				<?php
				$ha_heatmaps_table = new HA_Heatmaps_Table();
				$ha_heatmaps_table->prepare_items();
				$ha_heatmaps_table->display();
				?>
			</form>
		</div>
		
		<?php
		HA_Admin_Page_View::show_donate_link();
	}
	
	public static function users_page($tabs) {
		$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : HA_Common::USERS_TAB;
		?>
		<div class="wrap">
			<?php 
			HA_Admin_Page_View::page_header('Users');
			HA_Admin_Page_View::show_page_tabs(HA_Common::USERS_PAGE_SLUG, $tabs, $current_tab);
			
			if ($current_tab == HA_Common::USERS_TAB) {
				HA_Admin_Page_View::show_users_tab();
			} else if ($current_tab == HA_Common::USER_ACTIVITY_TAB) {
				HA_Admin_Page_View::show_user_activity_tab();
			}
			?>
		</div>
		
		<?php
		HA_Admin_Page_View::show_donate_link();
	}
	
	public static function reports_page($tabs) {
		$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : HA_Common::EVENT_COMPARISON_LINE_GRAPH_REPORT_TAB;
		?>
		<div class="wrap">
			<?php 
			HA_Admin_Page_View::page_header('Reports');
			HA_Admin_Page_View::show_page_tabs(HA_Common::REPORTS_PAGE_SLUG, $tabs, $current_tab);
			
			if ($current_tab == HA_Common::EVENT_COMPARISON_LINE_GRAPH_REPORT_TAB) {
				HA_Report_View::show_event_comparison_line_graph_report_tab();
			} else if ($current_tab == HA_Common::EVENT_STATISTICS_TABLE_REPORT_TAB) {
				HA_Report_View::show_event_statistics_table_report_tab();
			} else if ($current_tab == HA_Common::EVENT_TOTALS_BAR_GRAPH_REPORT_TAB) {
				HA_Report_View::show_event_totals_bar_graph_report_tab();
			} else if ($current_tab == HA_Common::EVENT_LINE_GRAPH_REPORT_TAB) {
				HA_Report_View::show_event_line_graph_report_tab();
			}
			?>
		</div>
		
		<?php
		HA_Admin_Page_View::show_donate_link();
	}
	
	public static function show_page_tabs($page, $tabs, $current_tab) {
		?>
		<h2 class="nav-tab-wrapper">
			<?php
			foreach ( $tabs as $tab_key => $tab_caption ) {
				$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
				echo '<a class="nav-tab ' . $active . '" href="?page=' . $page . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';
			} 
			?>
		</h2>
		<?php
	}
	
	public static function show_users_tab() {
		?>
		<form method="post">
			<?php 
			$ha_users_table = new HA_Users_Table();
			$ha_users_table->prepare_items();
			$ha_users_table->display();
			?>
		</form>
		<?php
	}
	public static function show_user_activity_tab() {
		?>
		<form method="post">
			<div class="tablenav top">
				<?php 
				$query_helper = new HA_Query_Helper();
				$filters = array(
						'ip_address' => true,
						'session_id' => true,
						'event_type' => true,
						'url' => true
				);
				$query_helper->get_session_filters($filters);
				if ($_SERVER['REQUEST_METHOD'] == 'POST') {
					$query_helper->get_http_filters('POST');
				} else {
					$query_helper->get_http_filters('GET');
				}
				$query_helper->set_session_filters();
				$query_helper->show_filters($filters);
				?>
			</div>
			<div id="poststuff" class="">
		        <div id="post-body" class="metabox-holder">
	                <?php wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false ); ?>
	                <?php wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false ); ?>
	                <?php add_meta_box("user-activity-summary-metabox", "Summary", array('HA_Report_View', "user_activity_summary_metabox"), HA_Common::REPORTS_PAGE_SLUG, "normal");?>
	                <?php do_meta_boxes(HA_Common::REPORTS_PAGE_SLUG,'normal', array());?>
	
				</div>
			</div>
			<?php 
			$user_activity_table = new HA_User_Activity_Table();
			$user_activity_table->prepare_items();
			$user_activity_table->display();
			?>
		</form>
		<?php
	}
	
	/**
	 * Shows plugin page header
	 */
	public static function page_header($title) {
		
		?>
		<div id="icon-options-general" class="icon32"><br></div>
		<h2><?php echo $title; ?></h2>
		<?php
	}
	
	
	public static function show_settings_form($settings_key) {
		?>
		<form method="post" name="<?php echo $settings_key; ?>" action="options.php" class="hut-settings-form">
			<?php
			wp_nonce_field( 'update-options' );
			settings_fields( $settings_key );
			do_settings_sections( $settings_key );
			submit_button(null, 'primary', 'submit', true, null);
			?>
		</form>
		<?php
	}
	
	public static function show_donate_link() {
	}
}

?>