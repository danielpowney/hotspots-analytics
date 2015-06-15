<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'common.php';

class HA_URL_Filters_Settings_View {
	
	/**
	 * URL Filter settings description
	 */
	public static function section_url_filters_desc() {
		echo "<p>URL filters can be useful for performance reasons (i.e. reduce server load) and to target specific pages (i.e. Home page only).</p>";
	}
	/**
	 * URl Filter settings fields
	 */
	public static function field_apply_url_filters() {
		$url_filters_settings = (array) get_option( HA_Common::URL_FILTERS_SETTINGS_KEY );
		$option_value = $url_filters_settings[HA_Common::APPLY_URL_FILTERS_OPTION];
		?>
		<input type="checkbox" name="<?php echo HA_Common::URL_FILTERS_SETTINGS_KEY; ?>[<?php echo HA_Common::APPLY_URL_FILTERS_OPTION; ?>]" value="true" <?php checked(true, $option_value, true); ?> />
		<p class="description">Turn on to apply the URL filters.</p>
		<?php 
	}
	public static function field_filter_type() {
		$url_filters_settings = (array) get_option( HA_Common::URL_FILTERS_SETTINGS_KEY );
		?>
		<input type="radio" name="<?php echo HA_Common::URL_FILTERS_SETTINGS_KEY; ?>[<?php echo HA_Common::FILTER_TYPE_OPTION; ?>]" value="whitelist" <?php checked(HA_Common::WHITELIST_VALUE, $url_filters_settings[HA_Common::FILTER_TYPE_OPTION], true); ?> />
		<label for="filterType">Whitelist</label><br />
		<input type="radio" name="<?php echo HA_Common::URL_FILTERS_SETTINGS_KEY; ?>[<?php echo HA_Common::FILTER_TYPE_OPTION; ?>]" value="blacklist"  <?php checked(HA_Common::BLACKLIST_VALUE, $url_filters_settings[HA_Common::FILTER_TYPE_OPTION], true); ?>/>
		<label for="filterType">Blacklist</label>
		<p class="description">Set a filter type to either include (whitelist) or exclude (blacklist).</p>
		<?php
	}
	public static function field_url_filters_list() {
		$url_filters_settings = (array) get_option( HA_Common::URL_FILTERS_SETTINGS_KEY );
		$option_value = $url_filters_settings[HA_Common::URL_FILTERS_LIST_OPTION];
		?>
		<textarea  name="<?php echo HA_Common::URL_FILTERS_SETTINGS_KEY; ?>[<?php echo HA_Common::URL_FILTERS_LIST_OPTION; ?>]" rows="5" cols="100"><?php echo $option_value; ?></textarea>
		<p class="description">Each URL must be on a newline</p>
		<?php 
	}	
	public static function sanitize_url_filters_settings($input) {
	
		// Apply URL filters option
		if ( isset( $input[HA_Common::APPLY_URL_FILTERS_OPTION] ) && $input[HA_Common::APPLY_URL_FILTERS_OPTION] == "true")
			$input[HA_Common::APPLY_URL_FILTERS_OPTION] = true;
		else
			$input[HA_Common::APPLY_URL_FILTERS_OPTION] = false;

		$url_filters_list = preg_split("/[\r\n,]+/", $input[HA_Common::URL_FILTERS_LIST_OPTION], -1, PREG_SPLIT_NO_EMPTY);

		$new_url_filters_list = '';
		foreach ($url_filters_list as $url) {
			$url = HA_Common::normalize_url($url);
			$new_url_filters_list .= $url . '&#13;&#10;';
		}
		$input[HA_Common::URL_FILTERS_LIST_OPTION] = $new_url_filters_list;
		return $input;
	}
}
?>