<?php 
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'common.php';

class HA_Heatmap_Settings_View {

	/**
	 * Heat map settings description
	 */
	public static function section_heat_map_desc() {
	}
	/** 
	 * Heat map settings fields
	 */
	public static function field_heatmapjs() {
		$heat_map_settings = (array) get_option( HA_Common::HEAT_MAP_SETTINGS_KEY );
		$option_value = $heat_map_settings[HA_Common::USE_HEATMAPJS_OPTION];
		?>
		<input type="checkbox" name="<?php echo HA_Common::HEAT_MAP_SETTINGS_KEY; ?>[<?php echo HA_Common::USE_HEATMAPJS_OPTION; ?>]" value="true" <?php checked(true, $option_value, true); ?> />
		<p class="description">Use <a href="http://www.patrick-wied.at/static/heatmapjs/">heatmap.js</a> library to draw the heatmap. Otherwise, a confetti of spots is used to show a heatmap of mouse clicks and touchscreen taps.</p>
		<?php 
	}
	
	public static function field_hot_value() {
		$heat_map_settings = (array) get_option( HA_Common::HEAT_MAP_SETTINGS_KEY );
		$option_value = $heat_map_settings[HA_Common::HOT_VALUE_OPTION];
		?>
		<input type="text" name="<?php echo HA_Common::HEAT_MAP_SETTINGS_KEY; ?>[<?php echo HA_Common::HOT_VALUE_OPTION; ?>]" value="<?php echo esc_attr( $option_value ); ?>" />&nbsp;(must be greater than 0)
		<p class="description">Set the heat value for the hottest spots which will show as red colour.</p>
		<?php
	}
	
	public static function field_spot_radius() {
		$heat_map_settings = (array) get_option( HA_Common::HEAT_MAP_SETTINGS_KEY );
		$option_value = $heat_map_settings[HA_Common::SPOT_RADIUS_OPTION];
		?>
		<input type="text" name="<?php echo HA_Common::HEAT_MAP_SETTINGS_KEY; ?>[<?php echo HA_Common::SPOT_RADIUS_OPTION; ?>]" value="<?php echo esc_attr( $option_value ); ?>" />&nbsp;(between 1 and 25)
		<p class="description">Set the radius of each spot. Note: This will effect the heat value calculation as spots with a greater radius are more likely to touch other spots.</p>
		<?php
	}
	
	public static function field_spot_opacity() {
		$heat_map_settings = (array) get_option( HA_Common::HEAT_MAP_SETTINGS_KEY );
		$option_value = $heat_map_settings[HA_Common::SPOT_OPACITY_OPTION];
		?>
		<input type="text" name="<?php echo HA_Common::HEAT_MAP_SETTINGS_KEY; ?>[<?php echo HA_Common::SPOT_OPACITY_OPTION; ?>]" value="<?php echo esc_attr( $option_value ); ?>" />&nbsp;(between 0.0 and 1.0)
		<p class="description">Set the opacity value of the spots. This is the degree of how much of the background you can see where there are spots.</p>
		<?php
	}
	
	
	public static function field_ignore_width() {
		$heat_map_settings = (array) get_option( HA_Common::HEAT_MAP_SETTINGS_KEY );
		$option_value = $heat_map_settings[HA_Common::IGNORE_WIDTH_OPTION];
		?>
		<input type="checkbox" name="<?php echo HA_Common::HEAT_MAP_SETTINGS_KEY; ?>[<?php echo HA_Common::IGNORE_WIDTH_OPTION; ?>]" value="true" <?php checked(true, $option_value, true); ?> />
		<p class="description">You can ignore the width data when drawing the heatmap. However, note your website likely appears differently for widths and responsive design.</p>
		<?php 
	}
	
	public static function field_ignore_device() {
		$heat_map_settings = (array) get_option( HA_Common::HEAT_MAP_SETTINGS_KEY );
		$option_value = $heat_map_settings[HA_Common::IGNORE_DEVICE_OPTION];
		?>
		<input type="checkbox" name="<?php echo HA_Common::HEAT_MAP_SETTINGS_KEY; ?>[<?php echo HA_Common::IGNORE_DEVICE_OPTION; ?>]" value="true" <?php checked(true, $option_value, true); ?> />
		<p class="description">You can ignore the device when drawing the heatmap.</p>
		<?php 
	}
	
	public static function field_ignore_os() {
		$heat_map_settings = (array) get_option( HA_Common::HEAT_MAP_SETTINGS_KEY );
		$option_value = $heat_map_settings[HA_Common::IGNORE_OS_OPTION];
		?>
		<input type="checkbox" name="<?php echo HA_Common::HEAT_MAP_SETTINGS_KEY; ?>[<?php echo HA_Common::IGNORE_OS_OPTION; ?>]" value="true" <?php checked(true, $option_value, true); ?> />
		<p class="description">You can ignore the operating system when drawing the heatmap.</p>
		<?php 
	}
	
	public static function field_ignore_browser() {
		$heat_map_settings = (array) get_option( HA_Common::HEAT_MAP_SETTINGS_KEY );
		$option_value = $heat_map_settings[HA_Common::IGNORE_BROWSER_OPTION];
		?>
		<input type="checkbox" name="<?php echo HA_Common::HEAT_MAP_SETTINGS_KEY; ?>[<?php echo HA_Common::IGNORE_BROWSER_OPTION; ?>]" value="true" <?php checked(true, $option_value, true); ?> />
		<p class="description">You can ignore the browser when drawing the heatmap.</p>
		<?php 
	}
	
	public static function field_width_allowance() {
		$heat_map_settings = (array) get_option( HA_Common::HEAT_MAP_SETTINGS_KEY );
		$option_value = $heat_map_settings[HA_Common::WIDTH_ALLOWANCE_OPTION];
		?>
		<input type="text" name="<?php echo HA_Common::HEAT_MAP_SETTINGS_KEY; ?>[<?php echo HA_Common::WIDTH_ALLOWANCE_OPTION; ?>]" value="<?php echo esc_attr( $option_value ); ?>" />&nbsp;pixels (between 0 and 20)
		<p class="description">An allowance to the width when drawing the heatmap. This saves time when adjusting the width to draw a heatmap as the width does not need to be exact (i.e if the width allowance is 6 pixels and the heat map width is 1600 pixels, then all clicks and taps within width of 1594 pixels to 1606 pixels will also be drawn on the heatmap). Note: the larger the width allowance, the less accurate the placement of the clicks and taps on the heatmap will likely be.</p>
		<?php
	}
	
	public static function field_hide_roles() {
		$heat_map_settings = (array) get_option( HA_Common::HEAT_MAP_SETTINGS_KEY );
		$hide_roles = $heat_map_settings[HA_Common::HIDE_ROLES_OPTION];
		
		global $wp_roles;
		if ( ! isset( $wp_roles) )
			$wp_roles = new WP_Roles();
	
		$roles = $wp_roles->get_names();
		// add None to the array of role non logged in users or visitors who do not have a role
		$roles[HA_Common::NO_ROLE_VALUE] = "None";
		
		
		echo '<p>';
		foreach ($roles as $role_value => $role_name) {
			echo '<input type="checkbox" name="' . HA_Common::HEAT_MAP_SETTINGS_KEY . '[' . HA_Common::HIDE_ROLES_OPTION . '][]" value="' . $role_value . '"';
			
			if (is_array($hide_roles)) {
				if (in_array($role_value, $hide_roles)) {
					echo 'checked="checked"';
				}
			} else {
				checked(true, $hide_roles, true );
			}
			echo ' />&nbsp;<label class="ha_checkbox_label">' . $role_name . '</label>'; 
		}
		
		echo '</p>';
		echo '<p class="description">You can hide mouse clicks and touchscreen taps of users from specific roles from being displayed on the heatmaps. None is for all non logged in users or visitors who do not have a role.</p>';
	}
	
	/**
	 * Sanitize and validate heat map settings
	 * 
	 * @param unknown_type $input
	 */
	public static function sanitize_heat_map_settings($input) {
		
		// use heatmap.js option
		if ( isset( $input[HA_Common::USE_HEATMAPJS_OPTION] ) && $input[HA_Common::USE_HEATMAPJS_OPTION] == "true")
			$input[HA_Common::USE_HEATMAPJS_OPTION] = true;
		else
			$input[HA_Common::USE_HEATMAPJS_OPTION] = false;
		
		// Width allowance option
		if ( isset( $input[HA_Common::WIDTH_ALLOWANCE_OPTION] ) ) {
			if ( is_numeric( $input[HA_Common::WIDTH_ALLOWANCE_OPTION] ) ) {
				$width_allowance = intval( $input[HA_Common::WIDTH_ALLOWANCE_OPTION] );
				if ( $width_allowance < 0 || $width_allowance > 20) {
					add_settings_error( HA_Common::HEAT_MAP_SETTINGS_KEY, 'width_allowance_range_error', 'Width allowance must be numeric between 0 and 20.', 'error');
				}
			} else {
				add_settings_error( HA_Common::HEAT_MAP_SETTINGS_KEY, 'width_allowance_format_error', 'Width allowance must be numeric between 0 and 20.', 'error');
			}
		
		}
		
		// hot value option
		if ( isset( $input[HA_Common::HOT_VALUE_OPTION] ) ) {
			if ( is_numeric( $input[HA_Common::HOT_VALUE_OPTION] ) ) {
				$hot_value = intval( $input[HA_Common::HOT_VALUE_OPTION] );
				if ( $hot_value <= 0 ) {
					add_settings_error( HA_Common::HEAT_MAP_SETTINGS_KEY, 'hot_value_range_error', 'Hot value must be numeric greater than 0.', 'error');
				}
			} else {
				add_settings_error( HA_Common::HEAT_MAP_SETTINGS_KEY, 'hot_value_non_numeric_error', 'Hot value must be numeric greater than 0.', 'error');
			}
		}
		
		// spot opacity option
		if ( isset( $input[HA_Common::SPOT_OPACITY_OPTION] ) ) {
			if ( is_numeric( $input[HA_Common::SPOT_OPACITY_OPTION] ) ) {
				$spot_opacity = floatval( $input[HA_Common::SPOT_OPACITY_OPTION] );
				if ( $spot_opacity < 0 || $spot_opacity > 1 ) {
					add_settings_error( HA_Common::HEAT_MAP_SETTINGS_KEY, 'spot_opacity_range_error', 'Spot opacity must be numeric between 0 and 1.', 'error');
				}
			} else {
				add_settings_error( HA_Common::HEAT_MAP_SETTINGS_KEY, 'spot_opacity_non_numeric_error', 'Spot opacity must be numeric between 0 and 1.', 'error');
			}
		}
		
		// spot radius option
		if ( isset( $input[HA_Common::SPOT_RADIUS_OPTION] ) ) {
			if ( is_numeric( $input[HA_Common::SPOT_RADIUS_OPTION] ) ) {
				$spot_radius = intval( $input[HA_Common::SPOT_RADIUS_OPTION] );
				if ( $spot_radius < 1 && $spot_radius > 25 ) {
					add_settings_error( HA_Common::HEAT_MAP_SETTINGS_KEY, 'spot_radius_range_error', 'Spot radius must be numeric between 1 and 25.', 'error');
				}
			} else {
				add_settings_error( HA_Common::HEAT_MAP_SETTINGS_KEY, 'spot_radius_non_numeric_error', 'Spot radius must be numeric between 1 and 25.', 'error');
			}
		}
		
		// Ignore width option
		if ( isset( $input[HA_Common::IGNORE_WIDTH_OPTION] ) && $input[HA_Common::IGNORE_WIDTH_OPTION] == "true")
			$input[HA_Common::IGNORE_WIDTH_OPTION] = true;
		else
			$input[HA_Common::IGNORE_WIDTH_OPTION] = false;
		
		// Ignore device option
		if ( isset( $input[HA_Common::IGNORE_DEVICE_OPTION] ) && $input[HA_Common::IGNORE_DEVICE_OPTION] == "true")
			$input[HA_Common::IGNORE_DEVICE_OPTION] = true;
		else
			$input[HA_Common::IGNORE_DEVICE_OPTION] = false;
		
		// Ignore os family option
		if ( isset( $input[HA_Common::IGNORE_OS_OPTION] ) && $input[HA_Common::IGNORE_OS_OPTION] == "true")
			$input[HA_Common::IGNORE_OS_OPTION] = true;
		else
			$input[HA_Common::IGNORE_OS_OPTION] = false;
		
		// Ignore browser family option
		if ( isset( $input[HA_Common::IGNORE_BROWSER_OPTION] ) && $input[HA_Common::IGNORE_BROWSER_OPTION] == "true")
			$input[HA_Common::IGNORE_BROWSER_OPTION] = true;
		else
			$input[HA_Common::IGNORE_BROWSER_OPTION] = false;
		
		return $input;
	}
}
?>