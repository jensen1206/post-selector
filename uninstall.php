<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://wwdh.de
 * @since      1.0.0
 *
 * @package    Post_Selector
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( "{$this->basename}_show_activated_page");
delete_option("{$this->basename}_message");
delete_option("{$this->basename}_product_install_authorize");
delete_option("{$this->basename}_client_id");
delete_option("{$this->basename}_client_secret");
delete_option("{$this->basename}_license_url");
delete_option('ps_two_user_role');
delete_option("{$this->basename}_install_time");
delete_option("{$this->basename}_server_api");
delete_transient( "$this->basename-admin-notice-error-panel-" . get_current_user_id() . "" );
delete_transient( "$this->basename-admin-notice-success-panel-" . get_current_user_id() . "" );


global $wpdb;
$table_name = $wpdb->prefix . 'ps_two_slide';
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);

$table_name = $wpdb->prefix . 'ps_two_galerie';
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);

$table_name = $wpdb->prefix . 'ps_two_galerie_images';
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);
