<?php 
/*
Plugin Name: Hotspots Analytics
Plugin URI: http://wordpress.org/extend/plugins/hotspots/
Description: The most advanced analytics plugin for WordPress websites including heatmaps, user activity and custom event tracking.
Version: 4.0.12
Author: Daniel Powney
Auhtor URI: danielpowney.com
License: GPL2
*/

require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'admin-controller.php';
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'frontend-controller.php';

$ha_admin_controller = null;
$ha_frontend_controller = null;

if (is_admin() && class_exists('HA_Admin_Controller')) {
	$ha_admin_controller = new HA_Admin_Controller();
} else if (class_exists('HA_Frontend_Controller')) {
	$ha_frontend_controller = new HA_Frontend_Controller();
}

// Activation and deactivation
function ha_activate_plugin() {
	if (is_admin() && class_exists('HA_Admin_Controller')) {
		HA_Admin_Controller::activate_plugin();
	}

}
function ha_uninstall_plugin() {
	if (is_admin() && class_exists('HA_Admin_Controller')) {
		HA_Admin_Controller::uninstall_plugin();
	}
}

register_activation_hook( __FILE__, 'ha_activate_plugin');
register_uninstall_hook( __FILE__, 'ha_uninstall_plugin' );
//register_deactivation_hook( __FILE__, 'ha_uninstall_plugin' );

if ( session_id() == '' ) {
	session_start();
}
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'update-check.php';
?>