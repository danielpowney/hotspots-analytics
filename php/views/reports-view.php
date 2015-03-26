<?php 
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'common.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'query-helper.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'tables' . DIRECTORY_SEPARATOR . 'summary-table.php';

class HA_Report_View {
	
	public static function show_event_comparison_line_graph_report_tab() {
		
		// Count all custom event types
		$query_helper = new HA_Query_Helper();
		$filters = array('event_types' => true, 'url' => true, 'page_width' => true, 'last_days' => true, 'browser' => true, 'os' => true, 'device' => true);
		$query_helper->get_session_filters($filters);
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$query_helper->get_http_filters('POST');
		} else {
			$query_helper->get_http_filters('GET');
		}
		$query_helper->set_session_filters();
		
		?>
		
		<h3>Event Comparison Line Graph Report</h3>
		<form method="post">
			<div class="tablenav top">
				<?php
				$query_helper->show_filters($filters);
				?>
				<br class="clear">
			</div>
		</form>
		<?php 
		
		global $ha_admin_controller;
		$data = $ha_admin_controller->get_data_services()->simple_query('event_comparison_line_graph_report_data', $query_helper->get_filters());

		$time_data = (array) $data->time_data;
		?>
										
		<script type="text/javascript">
			jQuery(function() {	
				// Time graph
				<?php 
				$datasets = '';
				$index = 0;
				$count = count($time_data);
				foreach($time_data as $key => $value) {
					
					$options = 'label : "'. $key .'", lines: { show: true }, points: { show: true }';
					
					$datasets .= ' { data : ' . json_encode($value) . ', ' . $options . ' } ';
					if ($index < $count-1)
						$datasets .= ', ';
					$index++;
				}
				?>
			
				// add markers for weekends on grid
				function weekendAreas(axes) {
					var markings = [];
					var d = new Date(axes.xaxis.min);
					// go to the first Saturday
					d.setUTCDate(d.getUTCDate() - ((d.getUTCDay() + 1) % 7))
					d.setUTCSeconds(0);
					d.setUTCMinutes(0);
					d.setUTCHours(0);
					var i = d.getTime();
					// when we don't set yaxis, the rectangle automatically
					// extends to infinity upwards and downwards
					do {
						markings.push({ xaxis: { from: i, to: i + 2 * 24 * 60 * 60 * 1000 } });
						i += 7 * 24 * 60 * 60 * 1000;
					} while (i < axes.xaxis.max);
						return markings;
				}
				var datasets = [ <?php echo $datasets; ?> ] ;
				var options = <?php echo '{
					xaxis: { 
						mode: "time", tickLength: 5, timeformat: "%y/%m/%d", minTickSize: [1, "day"] 
					}, grid: { markings: weekendAreas }
				 } '; ?>;
				
				jQuery.plot("#custom-event-time-placeholder", datasets, options );
			});
		</script>
				
		<div class="flot-container">
			<div class="report-wrapper">
				<div class="report-container">
					<div id="custom-event-time-placeholder" class="report-placeholder"></div>
				</div>
			</div>
		</div>
						
		<?php
	}
	public static function show_event_statistics_table_report_tab() {
		?>
		<form method="post">
			<?php
			$summary_table = new HA_Summary_Table();
			$summary_table->prepare_items();
			$summary_table->display();
			?>
		</form>
		<?php
	}
	public static function show_event_totals_bar_graph_report_tab() {
		// Count all custom event types
		$query_helper = new HA_Query_Helper();
		$filters = array('event_types' => true, 'url' => true,  'page_width' => true, 'last_days' => true, 'browser' => true, 'os' => true, 'device' => true);
		$query_helper->get_session_filters($filters);
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$query_helper->get_http_filters('POST');
		} else {
			$query_helper->get_http_filters('GET');
		}
		$query_helper->set_session_filters();
		
		?>
		<h3>Event Totals Bar Graph Report</h3>
		<form method="post">
			<div class="tablenav top">
				<?php
				$query_helper->show_filters($filters);
				?>
				<br class="clear">
			</div>
		</form>
		<?php 
				
		global $ha_admin_controller;
		$data = $ha_admin_controller->get_data_services()->simple_query('event_totals_bar_graph_report_data', $query_helper->get_filters());
		$count_data = (array) $data->count_data;
		?>
												
		<script type="text/javascript">
			jQuery(function() {	
				// Bar chart
				jQuery.plot("#custom-event-count-placeholder", [ <?php echo json_encode($count_data); ?> ], {
					series: {
						bars: {
							show: true,
							barWidth: 1,
							align: "center"
						}
					},
					xaxis: {
						mode: "categories",
						tickLength: 0
					}
				});
			});
		</script>
						
		<div class="flot-container">
			<div class="report-wrapper">
				<div class="report-container">
					<div id="custom-event-count-placeholder" class="report-placeholder"></div>
				</div>
			</div>
		</div>
							
		<?php
	}
	public static function show_event_line_graph_report_tab() {
		$query_helper = new HA_Query_Helper();
		$filters = array('event_types' => true, 'url' => true,  'page_width' => true, 'last_days' => true, 'browser' => true, 'os' => true, 'device' => true);
		$query_helper->get_session_filters($filters);
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$query_helper->get_http_filters('POST');
		} else {
			$query_helper->get_http_filters('GET');
		}
		$query_helper->set_session_filters();
		
		?>
		<h3>Event Line Graph Report</h3>
		<form method="post">
			<div class="tablenav top">
				<?php
				$query_helper->show_filters($filters);
				?>
				<br class="clear">
			</div>
		</form>
					
		<?php
		
		global $ha_admin_controller;
		$data = $ha_admin_controller->get_data_services()->simple_query('event_line_graph_report_data' , $query_helper->get_filters());
				
		$time_data = $data->time_data;
		?>
		<div class="flot-container">
			<div class="report-wrapper" style="height: 450px;">
				<div id="page-views-placeholder" class="report-placeholder"></div>
			</div>
		</div>
		<div class="flot-container">
			<div class="report-wrapper" style="height: 200px;">
				<div id="overview-placeholder" class="report-placeholder"></div>
			</div>
		</div>
							
		<script type="text/javascript">
			// Time graph
			jQuery(document).ready(function() {
				// add markers for weekends on grid
				function weekendAreas(axes) {
					var markings = [];
					var d = new Date(axes.xaxis.min);
					// go to the first Saturday
					d.setUTCDate(d.getUTCDate() - ((d.getUTCDay() + 1) % 7))
					d.setUTCSeconds(0);
					d.setUTCMinutes(0);
					d.setUTCHours(0);
					var i = d.getTime();
					// when we don't set yaxis, the rectangle automatically
					// extends to infinity upwards and downwards
					do {
						markings.push({ xaxis: { from: i, to: i + 2 * 24 * 60 * 60 * 1000 } });
						i += 7 * 24 * 60 * 60 * 1000;
					} while (i < axes.xaxis.max);
						return markings;
				}
				var options = {
					xaxis: {
						mode: "time",
						tickLength: 5
					},
					selection: {
						mode: "x"
					},
					grid: {
						markings: weekendAreas
					}
				};
						
				var plot = jQuery.plot("#page-views-placeholder", [<?php echo json_encode($time_data); ?>], options);
				
				var overview = jQuery.plot("#overview-placeholder", [<?php echo json_encode($time_data); ?>], {
					series: {
						lines: {
							show: true,
							lineWidth: 1
						},
						shadowSize: 0
					},
					xaxis: {
						ticks: [],
						mode: "time"
					},
					yaxis: {
						ticks: [],
						min: 0,
						autoscaleMargin: 0.1
					},
					selection: {
						mode: "x"
					}
				});
					
				jQuery("#page-views-placeholder").bind("plotselected", function (event, ranges) {
					
					// do the zooming
						
					plot = jQuery.plot("#page-views-placeholder", [<?php echo json_encode($time_data); ?>], jQuery.extend(true, {}, options, {
						xaxis: {
							min: ranges.xaxis.from,
							max: ranges.xaxis.to
						}
					}));
						
					// don't fire event on the overview to prevent eternal loop
					overview.setSelection(ranges, true);
				});
						
				jQuery("#overview-placeholder").bind("plotselected", function (event, ranges) {
					plot.setSelection(ranges);
				});
			});
		</script>
		<?php
	}
	
	
	
	/**
	 * Shows a summary of event statistics
	 */
	public static function show_summary_report_tab() {
		
	}
	

	public static function user_activity_summary_metabox($params) {
		
		$query_helper = new HA_Query_Helper();
		$query_helper->get_session_filters(array('ip_address' => true, 'session_id' => true, 'event_type' => true, 'url' => true));
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$query_helper->get_http_filters('POST');
		} else {
			$query_helper->get_http_filters('GET');
		}
		$query_helper->set_session_filters();
		
		global $ha_admin_controller;
		$data = $ha_admin_controller->get_data_services()->simple_query('user_activity_summary_data', $query_helper->get_filters());
		
		if (isset($data->count_total) && $data->count_total > 0) {
		?>
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">IP Address</th>
						<td><?php echo $data->ip_address; ?></td>
						<th scope="row">Session ID</th>
						<td><?php echo $data->session_id; ?></td>
						<th scope="row">Duration</th>
						<td><?php 
						$latest_record_date = strtotime($data->latest_record_date);
						$oldest_record_date = strtotime($data->oldest_record_date);
						$human_time_diff = HA_Common::human_time_diff($oldest_record_date, $latest_record_date);
						echo $human_time_diff; 
						?></td>
					</tr>
					<tr valign="top">
						<th scope="row">Username</th>
						<td><?php echo $data->username; ?></td>
						<th scope="row">Role</th>
						<td><?php echo $data->role; ?></td>
						<th scope="row">Browser</th>
						<td><?php echo $data->browser; ?></td>
					</tr>
					<tr valign="top">
						<th scope="row">Latest Record Date</th>
						<td><?php echo date("F j, Y, g:i a", strtotime($data->latest_record_date)); ?></td>
						<th scope="row">Page Views</th>
						<td><?php echo $data->count_page_views; ?></td>
						<th scope="row">Device</th>
						<td><?php echo $data->device; ?></td>
						
					</tr>
					<tr valign="top">
						<th scope="row">Mouse Clicks</th>
						<td><?php echo $data->count_mouse_clicks; ?></td>
						<th scope="row">Touchscreen Taps</th>
						<td><?php echo $data->count_touchscreen_taps; ?></td>
						<th scope="row">Operating System</th>
						<td><?php echo $data->os; ?></td>
					</tr>
					<tr valign="top">
						<th scope="row">AJAX Actions</th>
						<td><?php echo $data->count_ajax_actions; ?></td>
						<th scope="row">Custom Events</th>
						<td><?php echo $data->count_total - $data->count_mouse_clicks - $data->count_touchscreen_taps - $data->count_page_views - $data->count_ajax_actions  ?></td>
						<th scope="row">Page Width</th>
						<td><?php echo $data->page_width; ?>px</td>
						</tr>
				</tbody>
			</table>
			<?php
		} else {
			echo '<p>No summary found.</p>';
		}
		 
	}
}

?>