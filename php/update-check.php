<?php

/**
 * This file performs a check to determine whether an update is required
 */

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'common.php';

// Check if we need to do an upgrade from a previous version
$previous_plugin_version = get_option( HA_Common::PLUGIN_VERSION_OPTION );
if ( $previous_plugin_version != HA_Common::PLUGIN_VERSION ) {

	// reactivate plugin and db updates will occur
	HA_Admin_Controller::activate_plugin();
	
	try {
		// Delete old files that are no longer used from previous versions
		
		// PHP files
		if (file_exists( dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'class-frontend.php'))
			unlink(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'class-frontend.php');
		if (file_exists( dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'class-admin.php'))
			unlink(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'class-admin.php');
		if (file_exists( dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'class-admin-tables.php'))
			unlink(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'class-admin-tables.php');
		if (file_exists( dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'updates.php'))
			unlink(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'updates.php');
		if (file_exists( dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'class-common.php'))
			unlink(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'class-common.php');
		
		// Dirs
		if (is_dir( dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'heatmap.js'))
			recursive_rmdir_and_unlink(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'heatmap.js');
		if (is_dir( dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'uaparser'))
			recursive_rmdir_and_unlink(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'uaparser');
		
		// JS
		if (file_exists( dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'detect-zoom.js'))
			unlink(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'detect-zoom.js');
		
		// Images
		if (file_exists( dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'hotspots.png'))
			unlink(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'hotspots.png');
		if (file_exists( dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'hotspots16.ico'))
			unlink(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'hotspots16.ico');
		if (file_exists( dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'hotspots32.ico'))
			unlink(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'hotspots32.ico');
		
	} catch (Exception $e) {
		die('An error occured updating the plugin file structure! Try manually deleting the plugin files to fix the problem.');
	}

	update_option( HA_Common::PLUGIN_VERSION_OPTION, HA_Common::PLUGIN_VERSION );
}



/**
 * Recursive function to remove a directory and all it's sub-directories and contents
 * @param unknown_type $dir
 */
function recursive_rmdir_and_unlink($dir) {
	if (is_dir($dir)) {
		$objects = scandir($dir);
		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
				if (filetype($dir . DIRECTORY_SEPARATOR . $object) == "dir")
					recursive_rmdir_and_unlink($dir. DIRECTORY_SEPARATOR . $object);
				else unlink($dir . DIRECTORY_SEPARATOR . $object);
			}
		}
		
		reset($objects);
		
		rmdir($dir);
	}
}
 
 ?>