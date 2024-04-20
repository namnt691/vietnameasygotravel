<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       tamnghia.com
 * @since      1.0.0
 *
 * @package    TNIndependent
 * @subpackage TNIndependent/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    TNADVS
 * @subpackage TNADVS/admin
 * @author     Tam Nghia <dev@tamnghia.com>
 */
class TNADVS_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        new TNADVS_Admin_Init();
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in TNSetting_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The TNSetting_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/admin.css', array(), $this->version, 'all' );

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in TNSetting_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The TNSetting_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery','wp-color-picker' ), $this->version, false );

    }

}


class TNADVS_Admin_Init {

    function __construct() {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
    }

    function admin_menu() {
        add_menu_page(
            __( 'Quản lý quảng cáo', TNADVS_TEXT_DOMAIN ),
            __( 'Quản lý quảng cáo', TNADVS_TEXT_DOMAIN),
            'manage_options',
            TNADVS_PLUGIN_SLUG,
            array( $this, 'display_dashboard_content' ),
            '',
            16
        );

      

    }
    public function display_dashboard_content() {
        $action='';
        if(isset($_GET["action"]))
            $action = $_GET["action"];
        if($action=="add" || $action=="edit")
        {
            require_once plugin_dir_path(__FILE__) . 'partials/tnadvs-edit-display.php';
        }
        else
            require_once plugin_dir_path(__FILE__) . 'partials/tnadvs-admin-display.php';
    }






}