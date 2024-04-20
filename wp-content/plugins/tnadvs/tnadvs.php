<?php
ob_start();
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              tamnghia.com
 * @since             1.0.0
 * @package           Advs
 *
 * @wordpress-plugin
 * Plugin Name:       Hình ảnh quảng cáo
 * Plugin URI:        #
 * Description:       Hiển thị các thông tin hình ảnh quảng cáo, slide theo các vị trí
 * Version:           1.0.0
 * Author:            Tam Nghia
 * Author URI:        tamnghia.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       TNADVS
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}
// Name of the plugin
if ( !defined( 'TNADVS_PLUGIN_NAME' ) ) {
    define( 'TNADVS_PLUGIN_NAME', 'Hình ảnh quảng cáo' );
}
// table name of the plugin
if ( !defined( 'TNADVS_TABLE_NAME' ) ) {
    define( 'TNADVS_TABLE_NAME', 'tnadvs' );
}
// The current version of the plugin
if ( !defined( 'TNADVS_PLUGIN_VERSION' ) ) {
    define( 'TNADVS_PLUGIN_VERSION', '1.0.0' );
}
// The unique identifier of the plugin
if ( !defined( 'TNADVS_PLUGIN_SLUG' ) ) {
    define( 'TNADVS_PLUGIN_SLUG', 'tnadvs' );
}
// text doamin for translate
if ( !defined( 'TNADVS_TEXT_DOMAIN' ) ) {
    define( 'TNADVS_TEXT_DOMAIN', 'tnadvs' );
}

// Path to the plugin directory
if ( !defined( 'TNADVS_PLUGIN_DIR' ) ) {
    define( 'TNADVS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}



/**
 * The code that runs during plugin activation.
 * This action is documented in includes/activator.php
 */
if ( !function_exists( 'TNADVS_activate' ) ) {
    function TNADVS_activate()
    {
        require_once plugin_dir_path(__FILE__) . 'includes/activator.php';
        TNADVS_Activator::activate();
    }
    register_activation_hook( __FILE__, 'TNADVS_activate' );
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/deactivator.php
 */
if ( !function_exists( 'TNADVS_deactivate' ) ) {
    function TNADVS_deactivate()
    {
        require_once plugin_dir_path(__FILE__) . 'includes/deactivator.php';
        TNADVS_Deactivator::deactivate();
    }
    register_deactivation_hook( __FILE__, 'TNADVS_deactivate' );
}



/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/tnadvs.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_tnadvs() {

    $plugin = new TNADVS();
    $plugin->run();




}
run_tnadvs();
