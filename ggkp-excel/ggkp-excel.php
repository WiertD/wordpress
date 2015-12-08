<?php
/**
 * GG KP Excel
 *
 * Importeer en filter Klompenpad Excel Werkblad
 *
 * @package   Excel_Import
 * @author    GemeneGronden <info@gemenegronden.nl>
 * @license   GPL-2.0+
 * @link      http://gemenegronden.nl
 * *
 * @wordpress-plugin
 * Plugin Name:       GG Klompenpad Excel
 * Plugin URI:        http://gemenegronden.nl
 * Description:       Non generic plugin based on WP Excel CMS, Imports and filters a specific Klompenpad Excel file  
 *                    on the admin side and saves it as structurec json file. On the public side it appends the json 
 *                    data to specific pages according to the json structure.
 * Version:           1.0.7
 * Author:            Wiert Dijkkamp
 * Author URI:        http://gemenegronden.nl
 * Text Domain:       ggkp-excel
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/
DEFINE('GGKP_MAP', 'ggkp-excel');
DEFINE('GGKP_WERKBLAD','KlompenPaden');
require_once( plugin_dir_path( __FILE__ ) . 'public/ggkp-excel-public.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 * @TODO:
 *
 * - replace Plugin_Name with the name of the class defined in
 *   `class-plugin-name.php`
 */
register_activation_hook( __FILE__, array( 'GGKP_Excel', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'GGKP_Excel', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'GGKP_Excel', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
    require_once( plugin_dir_path( __FILE__ ) . 'admin/ggkp-excel-admin.php' );
    add_action( 'plugins_loaded', array( 'GGKP_Excel_Admin', 'get_instance' ) );
}

