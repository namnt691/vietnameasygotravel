<?php
/*
Plugin Name: WooTours
Plugin URI: https://exthemes.net/wootour/
Description: Travel/Tour Booking with WooCommerce
Version: 3.5.1
Package: Ex 3.5
Author: ExThemes
Author URI: http://exthemes.net/
License: Commercial
*/
define( 'WOO_TOUR_PATH', plugin_dir_url( __FILE__ ) );
// Make sure we don't expose any info if called directly
if ( ! defined( 'WOO_TOUR_PATH' ) ) {
	die( '-1' );
}
if ( ! function_exists( 'wt_get_plugin_url' ) ) {
	function wt_get_plugin_url() {
		return plugin_dir_path( __FILE__ );
	}
}
if ( ! function_exists( 'wt_check_woo_exists' ) ) {
	function wt_check_woo_exists() {
		$class   = 'notice notice-error';
		$message = esc_html__( 'WooCommerce is Required to make wootours plugin work, please install or activate WooCommerce plugin', 'woo-tour' );

		printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
	}
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
	if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
		add_action( 'admin_notices', 'wt_check_woo_exists' );
		return;
	}
}
class EX_WooTour {
	public $template_url;
	public $plugin_path;
	public function __construct() {
		$this->includes();
		if ( is_admin() ) {
			$this->register_plugin_settings();
		}
		add_action( 'after_setup_theme', array( &$this, 'ex_calthumb_register' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'wootour_admin_css' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
		add_filter( 'template_include', array( $this, 'wt_template_loader' ), 999 );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_style' ), 99 );
		add_action( 'wp_head', array( $this, 'custom_css' ), 100 );
		add_action( 'plugins_loaded', array( $this, 'wt_plugin_load_textdomain' ) );
		add_action( 'wp_footer', array( $this, 'enqueue_customjs' ), 99 );
	}
	function wt_plugin_load_textdomain() {
		$textdomain = 'woo-tour';
		$locale     = '';
		if ( empty( $locale ) ) {
			if ( is_textdomain_loaded( $textdomain ) ) {
				return true;
			} else {
				return load_plugin_textdomain( $textdomain, false, plugin_basename( dirname( __FILE__ ) ) . '/language' );
			}
		} else {
			return load_textdomain( $textdomain, plugin_basename( dirname( __FILE__ ) ) . '/' . $textdomain . '-' . $locale . '.mo' );
		}
	}
	function wt_template_loader( $template ) {
		$find            = array( 'archive-product.php' );
		$file            = '';
		$wt_main_purpose = get_option( 'wt_main_purpose' );
		if ( $wt_main_purpose != 'meta' ) {
			$tp = '';
			if ( get_option( 'wt_old_layout' ) != 'yes' ) {
				$tp = '-ct';
			}
			if ( is_post_type_archive( 'product' ) || is_tax( 'product_cat' ) || is_tax( 'product_tag' ) || is_tax( 'wt_location' ) ) {
				$file   = 'archive-product' . $tp . '.php';
				$find[] = $file;
				$find[] = $this->template_url . $file;
				if ( $file ) {
					$template = locate_template( $find );

					if ( ! $template ) {
						$file     = 'woo-tour/archive-product' . $tp . '.php';
						$find[]   = $file;
						$find[]   = $this->template_url . $file;
						$template = locate_template( $find );
						if ( ! $template ) {
							$template = $this->plugin_path() . '/templates/archive-product' . $tp . '.php';
						}
					}
				}
			}
			if ( is_singular( 'product' ) ) {
				$file   = 'single-product' . $tp . '.php';
				$find[] = $file;
				$find[] = $this->template_url . $file;
				if ( $file ) {
					$template = locate_template( $find );

					if ( ! $template ) {
						$file     = 'woo-tour/single-product' . $tp . '.php';
						$find[]   = $file;
						$find[]   = $this->template_url . $file;
						$template = locate_template( $find );
						if ( ! $template ) {
							$template = $this->plugin_path() . '/templates/single-product' . $tp . '.php';
						}
					}
				}
			}
		}
		return $template;
	}
	public function plugin_path() {
		if ( $this->plugin_path ) {
			return $this->plugin_path;
		}
		return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
	}
	function register_plugin_settings() {
		global $settings;
		$settings = new wootour_Settings( __FILE__ );
		return $settings;
	}
	// thumbnails register
	function ex_calthumb_register() {
		add_image_size( 'thumb_150x160', 150, 160, true );
		add_image_size( 'thumb_100x150', 100, 150, true );
		add_image_size( 'wethumb_204x153', 204, 153, true );
		add_image_size( 'wethumb_460x307', 460, 307, true );
		add_image_size( 'wethumb_85x85', 85, 85, true );
	}
	// inculde
	function includes() {
		if ( is_admin() ) {
			require_once wt_get_plugin_url() . 'inc/admin/class-plugin-settings.php';
			include_once wt_get_plugin_url() . 'inc/admin/functions.php';
			if ( ! function_exists( 'exc_mb_init' ) ) {
				if ( ! class_exists( 'EXC_MB_Meta_Box' ) ) {
					include_once wt_get_plugin_url() . 'inc/admin/Meta-Boxes/custom-meta-boxes.php';
				}
			}
		}
		include wt_get_plugin_url() . 'inc/admin/class-tour-meta.php';
		$wt_main_purpose = get_option( 'wt_main_purpose' );
		if ( $wt_main_purpose != 'meta' ) {
			require_once wt_get_plugin_url() . 'inc/class-woo-hook.php';
		} else {
			require_once wt_get_plugin_url() . 'inc/class-woo-hook-metaonly.php';
		}
		require_once wt_get_plugin_url() . 'inc/class-woo-booking.php';
		require_once wt_get_plugin_url() . 'inc/class-checkout-hook.php';
		include_once wt_get_plugin_url() . 'inc/functions.php';
		include wt_get_plugin_url() . 'shortcode/tour-table.php';
		include wt_get_plugin_url() . 'shortcode/tour-grid.php';
		include wt_get_plugin_url() . 'shortcode/tour-list.php';
		include wt_get_plugin_url() . 'shortcode/tour-carousel.php';
		include wt_get_plugin_url() . 'shortcode/tour-search.php';
		include wt_get_plugin_url() . 'shortcode/tour-taxonomy.php';
		include wt_get_plugin_url() . 'shortcode/tour-booking-form.php';
		// widget
		include wt_get_plugin_url() . 'widgets/tour-search.php';
		include wt_get_plugin_url() . 'widgets/latest-tour.php';
	}
	/*
	 * Load js and css
	 */
	function wootour_admin_css() {
		wp_enqueue_style( 'wootour-admin', WOO_TOUR_PATH . 'assets/css/style.css', '', '3.5' );
		wp_enqueue_script( 'wootour-admin', WOO_TOUR_PATH . 'assets/js/admin.js', array( 'jquery' ), '3.5' );
	}
	function frontend_scripts() {
		$wt_fontawesome = get_option( 'wt_fontawesome' );
		if ( $wt_fontawesome != 'on' ) {
			wp_enqueue_style( 'wt-font-awesome', WOO_TOUR_PATH . 'css/font-awesome/css/font-awesome.min.css' );
		}
		wp_enqueue_style( 'wt-layout', WOO_TOUR_PATH . 'css/layout.css' );
		$main_font_default = 'Source Sans Pro';
		$g_fonts           = array( $main_font_default );
		$wt_fontfamily     = get_option( 'wt_fontfamily' );
		if ( $wt_fontfamily != '' ) {
			$wt_fontfamily = wt_get_google_font_name( $wt_fontfamily );
			array_push( $g_fonts, $wt_fontfamily );
		}
		$wt_hfont = get_option( 'wt_hfont' );
		if ( $wt_hfont != '' ) {
			$wt_hfont = wt_get_google_font_name( $wt_hfont );
			array_push( $g_fonts, $wt_hfont );
		}
		$wt_googlefont_js = get_option( 'wt_googlefont_js' );
		if ( $wt_googlefont_js != 'on' ) {
			wp_enqueue_style( 'wootour-google-fonts', wt_get_google_fonts_url( $g_fonts ), array(), '1.0.0' );
		}
		// pickadate regiter js
		wp_register_style( 'wt-pickadate', WOO_TOUR_PATH . 'js/pickadate/themes/classic.css' );
		wp_register_style( 'wt-pickadate-date', WOO_TOUR_PATH . 'js/pickadate/themes/classic.date.css' );
		wp_enqueue_style( 'wt-pickadate-time', WOO_TOUR_PATH . 'js/pickadate/themes/classic.time.css' );
		wp_register_script( 'wt-pickadate', plugins_url( '/js/pickadate/picker.js', __FILE__ ), array( 'jquery' ), '1.0' );
		wp_register_script( 'wt-pickadate-date', plugins_url( '/js/pickadate/picker.date.js', __FILE__ ), array( 'jquery' ) );
		wp_register_script( 'wt-pickadate-time', plugins_url( '/js/pickadate/picker.time.js', __FILE__ ), array( 'jquery' ) );
		wp_register_script( 'wt-pickadate-legacy', plugins_url( '/js/pickadate/legacy.js', __FILE__ ), array( 'jquery' ) );
		$wt_calendar_lg = get_option( 'wt_calendar_lg' );
		if ( $wt_calendar_lg != '' ) {
			wp_register_script( 'wt-pickadate-' . $wt_calendar_lg, plugins_url( '/js/pickadate/translations/' . $wt_calendar_lg . '.js', __FILE__ ), array( 'jquery' ) );
		}
		wp_enqueue_style( 'exl-carousel', WOO_TOUR_PATH . 'js/owl-carousel/owl.carousel.css' );
		wp_enqueue_style( 'exl-carousel-theme', WOO_TOUR_PATH . 'js/owl-carousel/owl.theme.css' );
		wp_enqueue_style( 'exl-transitions-theme', WOO_TOUR_PATH . 'js/owl-carousel/owl.transitions.css' );
		wp_enqueue_script( 'wt-exl-carousel', WOO_TOUR_PATH . 'js/owl-carousel/owl.carousel.min.js', array( 'jquery' ), '2.0', true );
		wp_enqueue_script( 'wt-masonry', plugins_url( '/js/masonry.pkgd.min.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'wt-imageloaded', plugins_url( '/js/imagesloaded.pkgd.min.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'woo-tour', plugins_url( '/js/plugin-script.js', __FILE__ ), array( 'jquery' ), '3.3.3' );
	}
	function frontend_style() {
		$wt_main_purpose = get_option( 'wt_main_purpose' );
		$wt_plugin_style = get_option( 'wt_plugin_style' );
		if ( $wt_plugin_style != 'off' ) {
			$wt_main_purpose = get_option( 'wt_main_purpose' );
			if ( $wt_main_purpose != 'meta' ) {
				wp_enqueue_style( 'woo-event-css', WOO_TOUR_PATH . 'css/style.css', '3.2' );
			} else {
				wp_enqueue_style( 'woo-event-css', WOO_TOUR_PATH . 'css/meta-style.css', '3.2' );
			}
		}
		if ( $wt_main_purpose == 'custom' ) {
			wp_enqueue_style( 'wt-woo-style', WOO_TOUR_PATH . 'css/multi-style.css' );
		}
		if ( is_singular( 'product' ) ) {
			wp_enqueue_style( 'exwt-single-tour', WOO_TOUR_PATH . 'css/single-ct.css', '1.0' );
		}
		wp_enqueue_style( 'we-general', WOO_TOUR_PATH . 'css/general.css', array(), '3.2' );
		if ( is_rtl() ) {
			wp_enqueue_style( 'exwt-rtl', WOO_TOUR_PATH . 'css/rtl.css' );
		}
	}
	function custom_css() {
		echo '<style type="text/css">';
			require wt_get_plugin_url() . '/css/custom.css.php';
		echo '</style>';
	}
	function enqueue_customjs() {
		$wt_custom_code = get_option( 'wt_custom_code' );
		if ( $wt_custom_code != '' ) {
			echo '<script>' . $wt_custom_code . '</script>';
		}
	}
}
$EX_WooTour = new EX_WooTour();

update_option( 'exwt_license', 'valid' );
update_option( 'exwt_evt_purcode', 'sxc' );
update_option( 'exwt_evt_name', 'sxc' );
update_option( 'exwt_cupdate', strtotime( '+25 year' ) );
