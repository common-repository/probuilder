<?php
/*
Plugin Name: Probuilder
Plugin URI: http://www.lingulo.com/probuilder-live-css-editor-for-wordpress
Description: Probuilder is the first live visual CSS editor for everyone.
Version: 0.0.1
Author: Christoph Anastasiades
Author URI: https://www.canmedia.rocks
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('ABSPATH') or die('Direct access not allowed.');

if(!function_exists('add_filter'))
{
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

define('PROBUILDER_VERSION', '0.0.1');
define('PROBUILDER_FILE',__FILE__);
define('PROBUILDER_PATH',plugin_dir_path(PROBUILDER_FILE));
define('PROBUILDER_KB', 1024);
define('PROBUILDER_MB', 1048576);
define('PROBUILDER_MAX_FILE_SIZE', 1*PROBUILDER_MB);
define('PROBUILDER_COMPILE_URL', 'https://probuilder.lingulo.com/compiler/');
define('PROBUILDER_HIGHLIGHT_COLOR', 'rgba(231, 76, 60, 0.5)');
define('PROBUILDER_DB_VERSION', '0.1');
define('PROBUILDER_CSS_CHANGES_LIMIT', '500');
define('PROBUILDER_MODE', 'DEFAULT');
define('PROBUILDER_DEFAULT_MEDIA_QUERIES', "480px\n768px\n992px\n1200px");

require_once(PROBUILDER_PATH.'classes/probuilder.class.php');

if(PROBUILDER_MODE === 'PRO')
{
	require_once(PROBUILDER_PATH.'wp-sellwire-plugin.php');
	new \SellwirePluginUpdater_eQp3( 'https://app.sellwire.net/api/1/plugin', plugin_basename(__FILE__), esc_attr(get_option('license_key')));

	require_once(PROBUILDER_PATH.'classes/proversion.class.php');
	add_action('plugins_loaded', array(new Probuilder\Proversion(), 'register'));
	add_action('admin_menu', array(new Probuilder\Proversion(), 'init_admin_menu'));
	add_action('admin_init', array(new Probuilder\Proversion(), 'init_admin_settings'));
}
else
{
	add_action('plugins_loaded', array(new Probuilder\Probuilder(), 'register'));
	add_action('admin_menu', array(new Probuilder\Probuilder(), 'init_admin_menu'));
	add_action('admin_init', array(new Probuilder\Probuilder(), 'init_admin_settings'));
}

register_activation_hook(PROBUILDER_FILE, 'install_probuilder');

function install_probuilder()
{
	global $wpdb;
	$table_name = $wpdb->prefix.'probuilder';

	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
	{
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			path MEDIUMTEXT DEFAULT '' NOT NULL,
			mq VARCHAR(50) DEFAULT '' NOT NULL,
			css_code text NOT NULL,
			PRIMARY KEY (id)
		) $charset_collate;";

		require_once(ABSPATH.'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
	add_option('probuilder_db_version', PROBUILDER_DB_VERSION);
}
?>