<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u268889064_easygotravel' );

/** Database username */
define( 'DB_USER', 'u268889064_easygotravel' );

/** Database password */
define( 'DB_PASSWORD', '3kO+TJr*' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );




/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'g)?<ey9Y+Zr#H2k^zOaybZK!@gA$fc1hOW%D]eDC24rm8fveq:K-+dD/K,rs}~;8' );
define( 'SECURE_AUTH_KEY',  'UuOVT$6-B07TRF=aZjdDYP}UCay2tm>$~DO|h1^r:INg1ik^5ke&$2my+u@rhC#2' );
define( 'LOGGED_IN_KEY',    'FeVxe%#<]Gq@gVAn!^foB?OEu`%N-/s7W8jiZ| n2tMjf|NJ@5qfJxJ0ODlaa%UA' );
define( 'NONCE_KEY',        'D)GQ=,S,sgM|+$&ovJ]wQ`AjR]9KN<AlR{xVUBRmpP_gY3%T7K$Op#|J>QtenFlq' );
define( 'AUTH_SALT',        'f3b?xn~-b0MR(aYUY&bj-:^5@4N*qsL0jlUZ}2n$B=db%M_vr-/$1| }LIOoF#Zt' );
define( 'SECURE_AUTH_SALT', '{434QE8`mkN2:;O10{Uzf<I7${*rPWDVRfKC3JN8^O=(RF]o{UtH^Vs+3nO8~8BD' );
define( 'LOGGED_IN_SALT',   'U*9fjGJV @FQps@p2)V|^3^I,o*.[u.12~}S:;xsg)45mN81w~4?ES.%)hqD|#s3' );
define( 'NONCE_SALT',       '.HjlVl*!oEG@doHn8+w38]D;y~I0zM{]>C*nw!Kwb|#=$x6f_iI.)DROYO`=L8(8' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Đường dẫn tuyệt đối đến thư mục cài đặt WordPress. */
//Multi Domain for a site
define('WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST']);
define('WP_HOME', 'http://' . $_SERVER['HTTP_HOST']);

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
