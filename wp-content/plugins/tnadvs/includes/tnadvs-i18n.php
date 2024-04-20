<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       tamnghia.com
 * @since      1.0.0
 *
 * @package    TNIndependent
 * @subpackage TNIndependent/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    TNCustomerRiviews
 * @subpackage  TNCustomerRiviews/includes
 * @author     Tam Nghia <dev@tamnghia.com>
 */
class TNADVS_i18n {


    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain() {

        load_plugin_textdomain(
            TNADVS_TEXT_DOMAIN,
            false,
            dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
        );

    }



}
