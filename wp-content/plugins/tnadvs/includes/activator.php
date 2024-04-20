<?php

/**
 * Fired during plugin activation
 *
 * @link       tamnghia.com
 * @since      1.0.0
 *
 * @package    TNIndependent
 * @subpackage TNIndependent/include
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @package    TNADVS
 * @subpackage TNADVS/include
 * @author     Tam Nghia <dev@tamnghia.com>
 */
class TNADVS_Activator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        global $wpdb;
        global $charset_collate;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix .TNADVS_TABLE_NAME;
        if ($wpdb->get_var("SHOW TABLES LIKE '" . $table_name . "'") != $table_name) {
            $sql =
                "CREATE TABLE " . $table_name . " (
         iid mediumint(8)  NOT NULL auto_increment ,
            vlan varchar(5) NULL,
           vcode varchar(25) NULL,
         vname varchar(255) NULL,
           vimg varchar(255) NULL,
            vdescription varchar(1000) NULL,
             vlink varchar(500) NULL,
         vcontent longtext NULL,
         dcreate datetime,
          dmodified datetime,
              vauthor varchar(255) NULL,
                 iorder tinyint(4)	 NULL,
          vstatus varchar(20),
            vpr1 varchar(255),
              vpr2 varchar(255),
                vpr3 varchar(255),
         PRIMARY KEY  (iid))$charset_collate;";


            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }

    }

}
