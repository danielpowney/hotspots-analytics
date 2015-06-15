<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'common.php';

class HA_General_Settings_View {
	
	public static function section_general_desc() {
	}

	public static function field_save_click_tap() {
		$general_settings = (array) get_option( HA_Common::GENERAL_SETTINGS_KEY );
		$option_value = $general_settings[HA_Common::SAVE_CLICK_TAP_OPTION];
		?>
		<input type="checkbox" name="<?php echo HA_Common::GENERAL_SETTINGS_KEY; ?>[<?php echo HA_Common::SAVE_CLICK_TAP_OPTION; ?>]" value="true" <?php checked(true, $option_value, true); ?>/>
		<p class="description">Turn on to start saving mouse clicks and touchscreen taps.</p>
		<?php
	}
	
	public static function field_draw_heat_map_enabled() {
		$general_settings = (array) get_option( HA_Common::GENERAL_SETTINGS_KEY );
		$option_value = $general_settings[HA_Common::DRAW_HEAT_MAP_ENABLED_OPTION];
		?>
		<input type="checkbox" name="<?php echo HA_Common::GENERAL_SETTINGS_KEY; ?>[<?php echo HA_Common::DRAW_HEAT_MAP_ENABLED_OPTION; ?>]" value="true" <?php checked(true, $option_value, true ); ?>/>
		<p class="description">Enable to allow drawing of the heatmap overlayed on your website. To manually draw the heatmap, add query parameter <code>drawHeatmap=true</code> to the URL (i.e. www.mywebsite.com?drawHeatmap=true or www.mywebsite.com?cat=1&drawHeatmap=true). Your WordPress theme must be HTML5 compliant and your Internet browser must support HTML5 canvas to be able to view the heat map.</p>
		<?php 
	}
	
	public static function field_debug() {
		$general_settings = (array) get_option( HA_Common::GENERAL_SETTINGS_KEY );
		$option_value = $general_settings[HA_Common::DEBUG_OPTION];
		?>
		<input type="checkbox" name="<?php echo HA_Common::GENERAL_SETTINGS_KEY; ?>[<?php echo HA_Common::DEBUG_OPTION; ?>]" value="true" <?php checked(true, $option_value, true); ?>/>
		<p class="description">Turn on to debug and draw spots on every	mouse click and touchscreen tap. This option is useful for testing that that the mouse clicks and touchscreen taps are being saved and that the plugin is working as expected.</p>
		<?php 
	}	
		
	public static function field_save_ajax_actions() {
		$general_settings = (array) get_option( HA_Common::GENERAL_SETTINGS_KEY );
		$option_value = $general_settings[HA_Common::SAVE_AJAX_ACTIONS_OPTION];
		?>
		<input type="checkbox" name="<?php echo HA_Common::GENERAL_SETTINGS_KEY; ?>[<?php echo HA_Common::SAVE_AJAX_ACTIONS_OPTION; ?>]" value="true" <?php checked(true, $option_value, true); ?>/>
		<p class="description">Turn on to start saving AJAX actions.</p>
		<?php
	}
	
	public static function field_save_custom_events() {
		$general_settings = (array) get_option( HA_Common::GENERAL_SETTINGS_KEY );
		$option_value = $general_settings[HA_Common::SAVE_CUSTOM_EVENTS_OPTION];
		?>
		<input type="checkbox" name="<?php echo HA_Common::GENERAL_SETTINGS_KEY; ?>[<?php echo HA_Common::SAVE_CUSTOM_EVENTS_OPTION; ?>]" value="true" <?php checked(true, $option_value, true); ?>/>
		<p class="description">Turn on to start saving custom events.</p>
		<?php
	}
	
	public static function field_save_page_views() {
		$general_settings = (array) get_option( HA_Common::GENERAL_SETTINGS_KEY );
		$option_value = $general_settings[HA_Common::SAVE_PAGE_VIEWS_OPTION];
		?>
		<input type="checkbox" name="<?php echo HA_Common::GENERAL_SETTINGS_KEY; ?>[<?php echo HA_Common::SAVE_PAGE_VIEWS_OPTION; ?>]" value="true" <?php checked(true, $option_value, true); ?>/>
		<p class="description">Turn on to start recording page views.</p>
		<?php
	}
	
	public static function sanitize_general_settings($input) {
		// Save click tap option
		if ( isset( $input[HA_Common::SAVE_CLICK_TAP_OPTION] ) && $input[HA_Common::SAVE_CLICK_TAP_OPTION] == "true")
			$input[HA_Common::SAVE_CLICK_TAP_OPTION] = true;
		else
			$input[HA_Common::SAVE_CLICK_TAP_OPTION] = false;
		
		// draw heat map enabled option
		if ( isset( $input[HA_Common::DRAW_HEAT_MAP_ENABLED_OPTION] ) && $input[HA_Common::DRAW_HEAT_MAP_ENABLED_OPTION] == true)
			$input[HA_Common::DRAW_HEAT_MAP_ENABLED_OPTION] = true;
		else
			$input[HA_Common::DRAW_HEAT_MAP_ENABLED_OPTION] = false;
		
		// debug option
		if ( isset( $input[HA_Common::DEBUG_OPTION] ) && $input[HA_Common::DEBUG_OPTION] == "true")
			$input[HA_Common::DEBUG_OPTION] = true;
		else
			$input[HA_Common::DEBUG_OPTION] = false;
		
		// Save AJAX actions option
		if ( isset( $input[HA_Common::SAVE_AJAX_ACTIONS_OPTION] ) && $input[HA_Common::SAVE_AJAX_ACTIONS_OPTION] == "true")
			$input[HA_Common::SAVE_AJAX_ACTIONS_OPTION] = true;
		else
			$input[HA_Common::SAVE_AJAX_ACTIONS_OPTION] = false;
		
		// Save element selectors option
		if ( isset( $input[HA_Common::SAVE_CUSTOM_EVENTS_OPTION] ) && $input[HA_Common::SAVE_CUSTOM_EVENTS_OPTION] == "true")
			$input[HA_Common::SAVE_CUSTOM_EVENTS_OPTION] = true;
		else
			$input[HA_Common::SAVE_CUSTOM_EVENTS_OPTION] = false;
		
		// Save page views option
		if ( isset( $input[HA_Common::SAVE_PAGE_VIEWS_OPTION] ) && $input[HA_Common::SAVE_PAGE_VIEWS_OPTION] == "true")
			$input[HA_Common::SAVE_PAGE_VIEWS_OPTION] = true;
		else
			$input[HA_Common::SAVE_PAGE_VIEWS_OPTION] = false;
		
		return $input;
	}
}
?>