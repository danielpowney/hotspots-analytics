<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'common.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'services' . DIRECTORY_SEPARATOR . 'local-data-services.php';

class HA_Database_Settings_View {
	
	/**
	* Database settings description
	*/
	public static function section_database_desc() {
	
	}
	
	public static function field_url_db_limit() {
		$general_settings = (array) get_option( HA_Common::DATABASE_SETTINGS_KEY );
		$option_value = $general_settings[HA_Common::URL_DB_LIMIT_OPTION];
		?>
		<input type="text" name="<?php echo HA_Common::DATABASE_SETTINGS_KEY; ?>[<?php echo HA_Common::URL_DB_LIMIT_OPTION; ?>]" value="<?php echo esc_attr( $option_value ); ?>" />&nbsp;(leave empty for no limit)
		<p class="description">Generally, large amounts of data collected over a longer period of time does not statistically provider better results. Therefore, you can limit the number of clicks and taps saved per URL. This also improves the performance when drawing the heat map. This condition is checked on every page load to determine whether to allow saving clicks and taps (so this means that once the limit is reached, the page has to be reloaded to stop saving the clicks and taps).</p>
		<?php
	}
			
	function sanitize_database_settings($input) {
		
		// URL database limit option
		if ( isset( $input[HA_Common::URL_DB_LIMIT_OPTION] ) ) {
			if ( is_numeric( $input[HA_Common::URL_DB_LIMIT_OPTION] ) ) {
				$url_db_limit = intval( $input[HA_Common::URL_DB_LIMIT_OPTION] );
				if ( $url_db_limit <= 0 ) {
					add_settings_error( HA_Common::DATABASE_SETTINGS_KEY, 'url_db_limit_range_error', 'URL database limit must be numeric greater than 0 or empty.', 'error');
				}
			} else {
				if (strlen( trim($input[HA_Common::URL_DB_LIMIT_OPTION])) != 0)
					add_settings_error( HA_Common::DATABASE_SETTINGS_KEY, 'url_db_limit_trim_error', 'URL database limit must be numeric greater than 0 or empty.', 'error');
			}
		}
		
		// check if clear database flag is set
		if ( isset( $_POST['clear-database-flag'] ) && $_POST['clear-database-flag'] === "true" ) {
			global $ha_admin_controller;
			$response = $ha_admin_controller->get_data_services()->custom_query('clear_database', array());
			
			if ( $response['status'] == 'error') {
				add_settings_error( HA_Common::DATABASE_SETTINGS_KEY, 'clear-database-success', $response['message'], 'error');
			} else {
				add_settings_error( HA_Common::DATABASE_SETTINGS_KEY, 'clear-database-success', $response['message'], 'updated');
			}
		}
			
		return $input;
	}
		
}
?>