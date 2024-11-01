<?php
/*
  Plugin Name: WP Htaccess Edit
  Description: Simple and Safe WordPress htaccess file editor.
  Version:     1.0.0
  Author:      Asilweb
  Author URI:  http://asilweb.com
  License:     GPL2

  WP Htaccess Edit is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 2 of the License, or
  any later version.

  WP Htaccess Edit is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with WP Htaccess Edit. If not, see https://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) die('Silence is golden.');


// Path and url configuration

if( is_admin() ){

	define( 'WPHE_VERSION' , '1.0' ) ;

	if( ! defined( 'WP_SITEURL' ) ){
			define( 'WP_SITEURL', get_site_url() );
	}

	if( ! defined( 'WP_CONTENT_URL' )){
		define( 'WP_CONTENT_URL', content_url() );
	}

	if( ! defined( 'WP_PLUGIN_URL' ) ){
		define( 'WP_PLUGIN_URL', plugins_url() );
	}

	$WPHE_ROOT = trailingslashit( plugin_dir_path( __FILE__ ) );
	$WPHE_INC = $WPHE_ROOT.'inc/';
	$WPHE_DIR = str_replace('\\', '/', dirname(plugin_basename(__FILE__)));
	$WPHE_URI = WP_PLUGIN_URL.'/'.$WPHE_DIR.'/';

// Load translation files 

	$wphe_locale = get_locale();

	if( ! empty( $wphe_locale ) ){

		$wphe_mofile = dirname(__FILE__) . '/lang/'.$wphe_locale.'.mo';

		if( @file_exists( $wphe_mofile ) && is_readable( $wphe_mofile ) ){

			load_textdomain( 'wphe', $wphe_mofile );
		}

		unset( $wphe_mofile );
	}

	unset( $wphe_locale );




// Load inc files 

	if( file_exists( $WPHE_INC.'functions.php' ) ){

		require $WPHE_INC.'functions.php';

	}else{ 

		wp_die( wphe_error_message() ); 

	 }
 



// Add pages to the menu 

	function wphe_admin_menu(){

	    global $WPHE_DIR, $WPHE_URI;

	    if( current_user_can( 'activate_plugins' ) ){

			add_menu_page( 'WP Htaccess Edit', 'Htaccess Edit', 'activate_plugins', $WPHE_DIR, 'wphe_view_page', '' );
			wphe_add_page( 'Edit Htaccess', 'Edit', 'activate_plugins', $WPHE_DIR, 'wphe_view_page' );
			wphe_add_page( 'Backup Htaccess', 'Backup', 'activate_plugins', $WPHE_DIR.'_backup', 'wphe_view_page' );

			wp_enqueue_style('style', $WPHE_URI.'style/style.css' );
		}
		unset($WPHE_DIR);
		unset($WPHE_URI);
	}



// Output page


	function wphe_view_page()
	{
		global $WPHE_DIR, $WPHE_ROOT, $WPHE_URI, $WPHE_VERSION;

	    switch ( strip_tags(addslashes( sanitize_text_field( $_GET['page'] ) ) ) ){

			case $WPHE_DIR:

				require $WPHE_ROOT.'pages/dashboard.php';

			break;

			case $WPHE_DIR.'_backup': 

				require $WPHE_ROOT.'pages/backup.php';

			break;

			default:

			    $WPHE_ROOT.'pages/dashboard.php';

			break;
		}

		unset( $WPHE_DIR );
		unset( $WPHE_ROOT );
		unset( $WPHE_URI );
		unset( $WPHE_VERSION );
	}



// Create menus in admin panel 

	add_action( 'admin_menu', 'wphe_admin_menu' );



// Help function to create menus

	function wphe_add_page( $page_title, $menu_title, $access_level, $file, $function = '' ){

		global $WPHE_DIR;

		add_submenu_page( $WPHE_DIR, $page_title, $menu_title, $access_level, $file, $function );

		unset($WPHE_DIR);
		unset($page_title);
		unset($menu_title);
		unset($access_level);
		unset($file);
		unset($function);
	}



// Returns wp file error

	function wphe_wp_error_message(){

		return __( 'Fatal Error: WordPress core file are not found!', 'wphe');
	}

	

// Returns plugin error

	function wphe_error_message(){

		return __('Fatal error: Plugin is corrupted!', 'wphe');
	}


} else {

	return;
}
