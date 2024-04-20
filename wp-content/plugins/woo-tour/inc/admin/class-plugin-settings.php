<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class wootour_Settings {
    private $dir;
	private $file;
	private $assets_dir;
	private $assets_url;
	private $settings_base;
	private $settings;
	public function __construct( $file ) {
		$this->file = $file;
		$this->dir = dirname( $this->file );
		$this->assets_dir = trailingslashit( $this->dir ) . 'assets';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', $this->file ) ) );
		$this->settings_base = '';
		// Initialise settings
		add_action( 'admin_init', array( $this, 'init' ) );
		// Register plugin settings
		add_action( 'admin_init' , array( $this, 'register_settings' ) );
		// Add settings page to menu
		add_action( 'admin_menu' , array( $this, 'add_menu_item' ) );
		// Add settings link to plugins page
		add_filter( 'plugin_action_links_' . plugin_basename( $this->file ) , array( $this, 'add_settings_link' ) );
	}
	/**
	 * Initialise settings
	 * @return void
	 */
	public function init() {
		$this->settings = $this->settings_fields();
	}
	/**
	 * Add settings page to admin menu
	 * @return void
	 */
	public function add_menu_item() {
		$page = add_menu_page( esc_html__( 'WooTours Settings', 'woo-tour' ) , esc_html__( 'WooTours', 'woo-tour' ) , 'manage_options' , 'wootours' ,  array( $this, 'settings_page' ) );
		add_action( 'admin_print_styles-' . $page, array( $this, 'settings_assets' ) );
	}
	/**
	 * Load settings JS & CSS
	 * @return void
	 */
	public function settings_assets() {
		// We're including the farbtastic script & styles here because they're needed for the colour picker
		// If you're not including a colour picker field then you can leave these calls out as well as the farbtastic dependency for the wpt-admin-js script below
		wp_enqueue_style( 'farbtastic' );
		wp_enqueue_script( 'farbtastic' );
		// We're including the WP media scripts here because they're needed for the image upload field
		// If you're not including an image upload then you can leave this function call out
		wp_enqueue_media();
		wp_register_script( 'wpt-admin-js', $this->assets_url . 'js/settings.js', array( 'farbtastic', 'jquery' ), '3.5' );
		wp_enqueue_script( 'wpt-admin-js' );
	}
	/**
	 * Add settings link to plugin list table
	 * @param  array $links Existing links
	 * @return array 		Modified links
	 */
	public function add_settings_link( $links ) {
		$settings_link = '<a href="options-general.php?page=wootours">' . esc_html__( 'WooTours Settings', 'woo-tour' ) . '</a>';
  		array_push( $links, $settings_link );
  		return $links;
	}
	/**
	 * Build settings fields
	 * @return array Fields to be displayed on settings page
	 */
	private function settings_fields() {
		$settings['general'] = array(
			'title'					=> esc_html__( 'General', 'woo-tour' ),
			'description'			=> esc_html__( '', 'woo-tour' ),
			'fields'				=> array(
				array(
					'id' 			=> 'wt_main_purpose',
					'label'			=> esc_html__( 'Main Purpose', 'woo-tour' ),
					'description'	=> esc_html__( 'If you want to use the default layout and style from your theme, you can choose option "Only metadata with the default layout', 'woo-tour' ),
					'type'			=> 'select',
					'options'		=> array( 
						'tour' => esc_html__( 'Tours with built in layout and style', 'woo-tour' ),
						'custom' => esc_html__( 'Custom', 'woo-tour' ),
						'meta' => esc_html__( 'Only metadata with the default layout and style', 'woo-tour' )
					),
					'default'		=> ''
				),
				array(
					'id' 			=> 'wt_main_color',
					'label'			=> esc_html__( 'Main color', 'woo-tour' ),
					'description'	=> esc_html__( 'Choose main color to replace with default color', 'woo-tour' ),
					'type'			=> 'color',
					'placeholder'			=> '',
					'default'		=> '#00467e'
				),
				array(
					'id' 			=> 'wt_fontfamily',
					'label'			=> esc_html__( 'Main Font Family', 'woo-tour' ),
					'description'	=> esc_html__( 'Enter Google font-family name here. For example, if you choose "Source Sans Pro" Google Font, enter Source Sans Pro', 'woo-tour' ),
					'type'			=> 'text',
					'placeholder'			=> '',
					'default'		=> ''
				),
				array(
					'id' 			=> 'wt_fontsize',
					'label'			=> esc_html__( 'Main Font Size', 'woo-tour' ),
					'description'	=> esc_html__( 'Enter size of font, Ex: 13px', 'woo-tour' ),
					'type'			=> 'text',
					'placeholder'			=> '',
					'default'		=> ''
				),
				array(
					'id' 			=> 'wt_hfont',
					'label'			=> esc_html__( 'Heading Font Family', 'woo-tour' ),
					'description'	=> esc_html__( 'Enter Google font-family name here. For example, if you choose "Source Sans Pro" Google Font, enter Source Sans Pro', 'woo-tour' ),
					'type'			=> 'text',
					'placeholder'			=> '',
					'default'		=> '',
				),
				array(
					'id' 			=> 'wt_hfontsize',
					'label'			=> esc_html__( 'Heading Font Size', 'woo-tour' ),
					'description'	=> esc_html__( 'Enter size of font, Ex: 20px', 'woo-tour' ),
					'type'			=> 'text',
					'placeholder'			=> '',
					'default'		=> ''
				),
				array(
					'id' 			=> 'wt_sidebar',
					'label'			=> esc_html__( 'Sidebar', 'woo-tour' ),
					'description'	=> esc_html__( 'Select position of sidebar for single and listing page, select hide if you dont use sidebar', 'woo-tour' ),
					'type'			=> 'select',
					'options'		=> array( 
						'right' => esc_html__( 'Right', 'woo-tour' ),
						'left' => esc_html__( 'Left', 'woo-tour' ),
						'hide' => esc_html__( 'Hide', 'woo-tour' )
					),
					'default'		=> ''
				),
				// calendar language
				array(
					'id' 			=> 'wt_calendar_lg',
					'label'			=> esc_html__( 'Date picker Language', 'woo-tour' ),
					'description'	=> esc_html__( 'Select language of Date picker', 'woo-tour' ),
					'type'			=> 'select',
					'options'		=> array( 
						'' => esc_html__( 'en', 'woo-tour' ),
						'ar' => esc_html__( 'ar', 'woo-tour' ),
						'bg_BG' => esc_html__( 'bg_BG', 'woo-tour' ),
						'bs_BA' => esc_html__( 'bs_BA', 'woo-tour' ),
						'ca_ES' => esc_html__( 'ca_ES', 'woo-tour' ),
						'cs_CZ' => esc_html__( 'cs_CZ', 'woo-tour' ),
						'da_DK' => esc_html__( 'da_DK', 'woo-tour' ),
						'de_DE' => esc_html__( 'de_DE', 'woo-tour' ),
						'el_GR' => esc_html__( 'el_GR', 'woo-tour' ),
						'es_ES' => esc_html__( 'es_ES', 'woo-tour' ),
						'et_EE' => esc_html__( 'et_EE', 'woo-tour' ),
						'eu_ES' => esc_html__( 'eu_ES', 'woo-tour' ),
						'fa_IR' => esc_html__( 'fa_IR', 'woo-tour' ),
						'fi_FI' => esc_html__( 'fi_FI', 'woo-tour' ),
						'fr_FR' => esc_html__( 'fr_FR', 'woo-tour' ),
						'ge_GEO' => esc_html__( 'ge_GEO', 'woo-tour' ),
						'gl_ES' => esc_html__( 'gl_ES', 'woo-tour' ),
						'he_IL' => esc_html__( 'he_IL', 'woo-tour' ),
						'hi_IN' => esc_html__( 'hi_IN', 'woo-tour' ),
						'hr_HR' => esc_html__( 'hr_HR', 'woo-tour' ),
						'hu_HU' => esc_html__( 'hu_HU', 'woo-tour' ),
						'id_ID' => esc_html__( 'id_ID', 'woo-tour' ),
						'is_IS' => esc_html__( 'is_IS', 'woo-tour' ),
						'it_IT' => esc_html__( 'it_IT', 'woo-tour' ),
						'ja_JP' => esc_html__( 'ja_JP', 'woo-tour' ),
						'ko_KR' => esc_html__( 'ko_KR', 'woo-tour' ),
						'lt_LT' => esc_html__( 'lt_LT', 'woo-tour' ),
						'lv_LV' => esc_html__( 'lv_LV', 'woo-tour' ),
						'nb_NO' => esc_html__( 'nb_NO', 'woo-tour' ),
						'ne_NP' => esc_html__( 'ne_NP', 'woo-tour' ),
						'nl_NL' => esc_html__( 'nl_NL', 'woo-tour' ),
						'no_NO' => esc_html__( 'no_NO', 'woo-tour' ),
						'pl_PL' => esc_html__( 'pl_PL', 'woo-tour' ),
						'pt_BR' => esc_html__( 'pt_BR', 'woo-tour' ),
						'pt_PT' => esc_html__( 'pt_PT', 'woo-tour' ),
						'ro_RO' => esc_html__( 'ro_RO', 'woo-tour' ),
						'ru_RU' => esc_html__( 'ru_RU', 'woo-tour' ),
						'sk_SK' => esc_html__( 'sk_SK', 'woo-tour' ),
						'sl_SI' => esc_html__( 'sl_SI', 'woo-tour' ),
						'sv_SE' => esc_html__( 'sv_SE', 'woo-tour' ),
						'th_TH' => esc_html__( 'th_TH', 'woo-tour' ),
						'tr_TR' => esc_html__( 'tr_TR', 'woo-tour' ),
						'uk_UA' => esc_html__( 'uk_UA', 'woo-tour' ),
						'vi_VN' => esc_html__( 'vi_VN', 'woo-tour' ),
						'zh_CN' => esc_html__( 'zh_CN', 'woo-tour' ),
						'zh_TW' => esc_html__( 'zh_TW', 'woo-tour' ),
					),
					'default'		=> ''
				),
				array(
					'id' 			=> 'wt_firstday',
					'label'			=> esc_html__( 'First day of date picker', 'woo-tour' ),
					'description'	=> esc_html__( 'The day that each week begins', 'woo-tour' ),
					'type'			=> 'select',
					'options'		=> array( 
						'' => esc_html__('Sunday', 'woo-tour'),
						'1' => esc_html__('Monday', 'woo-tour'),
						),
					'default'		=> ''
				),
				array(
					'id' 			=> 'wt_calendar_datefm',
					'label'			=> esc_html__( 'Date picker format', 'woo-tour' ),
					'description'	=> '',
					'type'			=> 'text',
					'description'		=> esc_html__( 'Defaut: d mmmm, yyyy', 'woo-tour' ),
					'placeholder'			=> '',
					'default'		=> ''
				),
				array(
					'id' 			=> 'wt_shop_view',
					'label'			=> esc_html__( 'Listing Layout', 'woo-tour' ),
					'description'	=> '',
					'type'			=> 'select',
					'options'		=> array( 
						'list' => esc_html__( 'Grid', 'woo-tour' ),
						'table' => esc_html__( 'Table', 'woo-tour' ),
						),
					'default'		=> ''
				),
				array(
					'id' 			=> 'wt_schedu_map',
					'label'			=> esc_html__( 'Enable Schedule and Map field', 'woo-tour' ),
					'description'	=> '',
					'type'			=> 'select',
					'options'		=> array( 
						'' => esc_html__( 'No', 'woo-tour' ),
						'1' => esc_html__( 'Yes', 'woo-tour' ),
						),
					'default'		=> ''
				),
				array(
					'id' 			=> 'wt_enable_customdate',
					'label'			=> esc_html__( 'Show custom date in Shortcode', 'woo-tour' ),
					'description'	=> '',
					'type'			=> 'select',
					'options'		=> array( 
						'' => esc_html__( 'No', 'woo-tour' ),
						'1' => esc_html__( 'Yes', 'woo-tour' ),
						),
					'default'		=> ''
				),
				array(
					'id' 			=> 'wt_loc_slug',
					'label'			=> esc_html__( 'Location slug', 'woo-tour' ),
					'description'	=> esc_html__( 'Enter location slug, default: location', 'woo-tour' ),
					'type'			=> 'text',
					'placeholder'			=> '',
					'default'		=> ''
				),
			)
		);
		
		$settings['single_tour'] = array(
			'title'					=> esc_html__( 'Single Tour', 'woo-tour' ),
			'description'			=> esc_html__( '', 'woo-tour' ),
			'fields'				=> array()
		);
		$settings['single_tour']['fields'][] = array(
			'id' 			=> 'wt_slayout_purpose',
			'label'			=> esc_html__( 'Default Layout Purpose', 'woo-tour' ),
			'description'	=> esc_html__( 'If you sell normal product (like t-shirt) with tour, you can choose default layout for single product, you can ignore this setting if you sell only tour', 'woo-tour' ),
			'type'			=> 'select',
			'options'		=> array( 
				'tour' => esc_html__( 'Tour', 'woo-tour' ),
				'woo' => esc_html__( 'WooCommere', 'woo-tour' ),
			),
			'default'		=> ''
		);
		$wt_main_purpose = wt_global_main_purpose();
		if($wt_main_purpose!='meta'){
			$settings['single_tour']['fields'][]=array(
				'id' 			=> 'wt_slayout',
				'label'			=> esc_html__( 'Layout', 'woo-tour' ),
				'description'	=> esc_html__( 'Select default layout of single tour', 'woo-tour' ),
				'type'			=> 'select',
				'options'		=> array( 
					'layout-1' => esc_html__( 'Default', 'woo-tour' ),
					'layout-2' => esc_html__( 'Full Width', 'woo-tour' ),
					'layout-3' => esc_html__( 'Full Width Flat', 'woo-tour' )
				),
				'default'		=> ''
			);
		}
		$settings['single_tour']['fields'][] = array(
			'id' 			=> 'wt_enable_exoptions',
			'label'			=> esc_html__( 'Enable Extra Options', 'woo-tour' ),
			'description'	=> esc_html__( 'Select yes to enable this feature', 'woo-tour' ),
			'type'			=> 'select',
			'options'		=> array( 
				'no' => esc_html__( 'No', 'woo-tour' ),
				'yes' => esc_html__( 'Yes', 'woo-tour' ),
			),
			'default'		=> ''
		);
		$settings['single_tour']['fields'][] = array(
			'id' 			=> 'wt_show_sdate',
			'label'			=> esc_html__( 'Show Special date in', 'woo-tour' ),
			'description'	=> '',
			'type'			=> 'select',
			'options'		=> array( 
				'' => esc_html__( 'List', 'woo-tour' ),
				'calendar' => esc_html__( 'Calendar', 'woo-tour' ),
			),
			'default'		=> ''
		);
		$settings['single_tour']['fields'][] = array(
			'id' 			=> 'wt_date_picker',
			'label'			=> esc_html__( 'Admin date picker format', 'woo-tour' ),
			'description'	=> '',
			'type'			=> 'select',
			'options'		=> array( 
				'' => esc_html__( 'mm/dd/yyyy', 'woo-tour' ),
				'dmy' => esc_html__( 'dd/mm/yyyy', 'woo-tour' ),
				),
			'default'		=> ''
		);
		$settings['single_tour']['fields'][] = array(
			'id' 			=> 'wt_ssocial',
			'label'			=> esc_html__( 'Show Social Share', 'woo-tour' ),
			'description'	=> esc_html__( 'Show/hide Social Share section', 'woo-tour' ),
			'type'			=> 'select',
			'options'		=> array( 
				'' => esc_html__( 'Show', 'woo-tour' ),
				'off' => esc_html__( 'Hide', 'woo-tour' ),
			),
			'default'		=> ''
		);
		if(get_option('wt_ssocial') !='off'){
			$settings['single_tour']['fields'][]= array(
				'id' 			=> 'wt_ssocial_dis',
				'label'			=> esc_html__( 'Disable special social', 'woo-tour' ),
				'description'	=> esc_html__( 'Select special social you want to disable', 'woo-tour' ),
				'type'			=> 'checkbox_multi',
				'options'		=> array(
					'fb' => esc_html__( 'Facebook', 'exthemes' ),
					'tw' => esc_html__( 'Twitter', 'exthemes' ),
					'li' => esc_html__( 'LinkedIn', 'exthemes' ),
					'tb' => esc_html__( 'Tumblr', 'exthemes' ),
					'pin' => esc_html__( 'Pin this', 'exthemes' ),
					'vk' => esc_html__( 'VK', 'exthemes' ),
					'em' => esc_html__( 'Email', 'exthemes' ),
					'ws' => esc_html__( 'Whatsapp', 'exthemes' ),
				),
				'default'		=> ''
			);
		}
		$settings['single_tour']['fields'][] = array(
			'id' 			=> 'wt_type_qunatity',
			'label'			=> esc_html__( 'Type of quantity field', 'woo-tour' ),
			'description'	=> esc_html__( 'Choose type of quatity field, if you want to limit max qty, please choose "Select box"', 'woo-tour' ),
			'type'			=> 'select',
			'options'		=> array( 
				'select' => esc_html__( 'Select box', 'woo-tour' ),
				'text' => esc_html__( 'Text box', 'woo-tour' ),
				),
			'default'		=> ''
		);
		$settings['single_tour']['fields'][] =	array(
			'id' 			=> 'wt_default_adl',
			'label'			=> esc_html__( 'Default max number of adult' , 'woo-tour' ),
			'description'	=> esc_html__( 'Enter number, default 5', 'woo-tour' ),
			'type'			=> 'text',
			'default'		=> '',
			'placeholder'	=> esc_html__( '', 'woo-tour' )
		);
		$settings['single_tour']['fields'][] =	array(
			'id' 			=> 'wt_default_child',
			'label'			=> esc_html__( 'Default max number of Children' , 'woo-tour' ),
			'description'	=> esc_html__( 'Enter number, default 5', 'woo-tour' ),
			'type'			=> 'text',
			'default'		=> '',
			'placeholder'	=> esc_html__( '', 'woo-tour' )
		);
		$settings['single_tour']['fields'][] = array(
			'id' 			=> 'wt_default_inf',
			'label'			=> esc_html__( 'Default max number of Infant' , 'woo-tour' ),
			'description'	=> esc_html__( 'Enter number, default 5', 'woo-tour' ),
			'type'			=> 'text',
			'default'		=> '',
			'placeholder'	=> esc_html__( '', 'woo-tour' )
		);
		$settings['single_tour']['fields'][] = array(
			'id' 			=> 'wt_def_childf',
			'label'			=> esc_html__( 'Default show Children field', 'woo-tour' ),
			'description'	=> '',
			'type'			=> 'select',
			'options'		=> array( 
				'' => esc_html__( 'Show', 'woo-tour' ),
				'off' => esc_html__( 'Hide', 'woo-tour' ),
			),
			'default'		=> ''
		);
		$settings['single_tour']['fields'][] = array(
			'id' 			=> 'wt_def_intff',
			'label'			=> esc_html__( 'Default show Infant field', 'woo-tour' ),
			'description'	=> '',
			'type'			=> 'select',
			'options'		=> array( 
				'' => esc_html__( 'Show', 'woo-tour' ),
				'off' => esc_html__( 'Hide', 'woo-tour' ),
			),
			'default'		=> ''
		);
		$settings['single_tour']['fields'][] = array(
			'id' 			=> 'wt_ctfieldprice',
			'label'			=> esc_html__( 'Add more 2 custom price fields', 'woo-tour' ),
			'description'	=> '',
			'type'			=> 'select',
			'options'		=> array( 
				'' => esc_html__( 'No', 'woo-tour' ),
				'1' => esc_html__( 'Yes', 'woo-tour' ),
			),
			'default'		=> ''
		);
		if(get_option('wt_ctfieldprice')=='1'){
			$settings['single_tour']['fields'][] = array(
				'id' 			=> 'wt_ctfield1_info',
				'label'			=> esc_html__( 'Default label name 1, max quantity, default hide this field' , 'woo-tour' ),
				'description'	=> esc_html__( 'Example: Name 1 | 5 | hide', 'woo-tour' ),
				'type'			=> 'text',
				'default'		=> '',
				'placeholder'	=> ''
			);
			$settings['single_tour']['fields'][] = array(
				'id' 			=> 'wt_ctfield2_info',
				'label'			=> esc_html__( 'Default label name 2 , max quantity, default hide this field' , 'woo-tour' ),
				'description'	=> esc_html__( 'Example: Name 2 | 5 | hide', 'woo-tour' ),
				'type'			=> 'text',
				'default'		=> '',
				'placeholder'	=> esc_html__( '', 'woo-tour' )
			);
		}
		$settings['single_tour']['fields'][] = array(
			'id' 			=> 'wt_dismulti_varstock',
			'label'			=> esc_html__( 'Multi stock for each variation', 'woo-tour' ),
			'description'	=> esc_html__( 'Select disable to use one stock for all variations', 'woo-tour' ),
			'type'			=> 'select',
			'options'		=> array( 
				'' => esc_html__( 'Enable', 'woo-tour' ),
				'yes' => esc_html__( 'Disable', 'woo-tour' ),
				'sp_only' => esc_html__( 'Enable only for special variation', 'woo-tour' ),
			),
			'default'		=> ''
		);
		$settings['single_tour']['fields'][] = array(
			'id' 			=> 'wt_disable_book',
			'label'			=> esc_html__( 'Minimum time to order tour' , 'woo-tour' ),
			'description'	=> esc_html__( 'Set minimum time required to order tour before X days, example enter 2 to required the minimum time is 2 days', 'woo-tour' ),
			'type'			=> 'text',
			'default'		=> '',
			'placeholder'	=> esc_html__( 'Enter number', 'woo-tour' )
		);
		
		$settings['single_tour']['fields'][]= array(
			'id' 			=> 'wt_live_total',
			'label'			=> esc_html__( 'Enable Total price', 'exthemes' ),
			'description'	=> esc_html__( 'Enable live update Total price in single tour page ( only work with default price field of WooTour)', 'exthemes' ),
			'type'			=> 'select',
			'options'		=> array( 
				'' => esc_html__( 'No', 'exthemes' ),
				'yes' => esc_html__( 'Yes', 'exthemes' ),
				),
			'default'		=> ''
		);
		
		$settings['checkout_tour'] = array(
			'title'					=> esc_html__( 'Checkout', 'woo-tour' ),
			'description'			=> esc_html__( '', 'woo-tour' ),
			'fields'				=> array(
				array(
					'id' 			=> 'wt_enable_cart',
					'label'			=> esc_html__( 'Enable redirect to Checkout page', 'woo-tour' ),
					'description'	=> esc_html__( 'Redirect to the Checkout page after successful addition', 'woo-tour' ),
					'type'			=> 'select',
					'options'		=> array( 
						'on' => esc_html__( 'Off', 'woo-tour' ),
						'off' => esc_html__( 'On', 'woo-tour' ),
						),
					'default'		=> ''
				),
				array(
					'id' 			=> 'wt_disable_attendees',
					'label'			=> esc_html__( 'Disable multiple attendees info', 'woo-tour' ),
					'description'	=> '',
					'type'			=> 'select',
					'options'		=> array( 
						'' => esc_html__( 'No', 'woo-tour' ),
						'yes' => esc_html__( 'Yes', 'woo-tour' ),
					),
					'default'		=> ''
				),
				array(
					'id' 			=> 'wt_attendee_name',
					'label'			=> esc_html__( 'Attendee name ', 'woo-tour' ),
					'description'	=> '',
					'type'			=> 'select',
					'options'		=> array( 
						'' => esc_html__( 'Required', 'woo-tour' ),
						'no' => esc_html__( 'Optional', 'woo-tour' ),
						'dis' => esc_html__( 'Disabled', 'woo-tour' ),
					),
					'default'		=> ''
				),
				array(
					'id' 			=> 'wt_attendee_email',
					'label'			=> esc_html__( 'Attendee email', 'woo-tour' ),
					'description'	=> '',
					'type'			=> 'select',
					'options'		=> array( 
						'' => esc_html__( 'Required', 'woo-tour' ),
						'no' => esc_html__( 'Optional', 'woo-tour' ),
						'dis' => esc_html__( 'Disabled', 'woo-tour' ),
					),
					'default'		=> ''
				),
				array(
					'id' 			=> 'wt_attendee_birth',
					'label'			=> esc_html__( 'Attendee Date of birth', 'woo-tour' ),
					'description'	=> '',
					'type'			=> 'select',
					'options'		=> array( 
						'' => esc_html__( 'Required', 'woo-tour' ),
						'no' => esc_html__( 'Optional', 'woo-tour' ),
						'dis' => esc_html__( 'Disabled', 'woo-tour' ),
					),
					'default'		=> ''
				),
				array(
					'id' 			=> 'wt_attendee_gender',
					'label'			=> esc_html__( 'Attendee gender', 'woo-tour' ),
					'description'	=> '',
					'type'			=> 'select',
					'options'		=> array( 
						'' => esc_html__( 'Required', 'woo-tour' ),
						'no' => esc_html__( 'Optional', 'woo-tour' ),
						'dis' => esc_html__( 'Disabled', 'woo-tour' ),
					),
					'default'		=> ''
				),
			)
		);
		
		$settings['custom-css'] = array(
			'title'					=> esc_html__( 'Custom Code', 'woo-tour' ),
			'description'			=> esc_html__( '', 'woo-tour' ),
			'fields'				=> array(
				array(
					'id' 			=> 'wt_custom_css',
					'label'			=> esc_html__( 'Paste your CSS code' , 'woo-tour' ),
					'description'	=> esc_html__( 'Add custom CSS code to the plugin without modifying files', 'woo-tour' ),
					'type'			=> 'textarea',
					'default'		=> '',
					'placeholder'	=> esc_html__( '', 'woo-tour' )
				),
				array(
					'id' 			=> 'wt_custom_code',
					'label'			=> esc_html__( 'Paste your js code' , 'woo-tour' ),
					'description'	=> esc_html__( 'Add custom js code to the plugin without modifying files', 'woo-tour' ),
					'type'			=> 'textarea',
					'default'		=> '',
					'placeholder'	=> ''
				),
			)
		);
		$settings['js_css_settings'] = array(
			'title'					=> esc_html__( 'Js & Css file', 'woo-tour' ),
			'description'			=> '',
			'fields'				=> array(
				array(
					'id' 			=> 'wt_fontawesome',
					'label'			=> esc_html__( 'Turn off Font Awesome', 'woo-tour' ),
					'description'	=> esc_html__( "Turn off loading plugin's Font Awesome if your theme has already loaded this library", 'woo-tour' ),
					'type'			=> 'checkbox',
					'default'		=> ''
				),
				array(
					'id' 			=> 'wt_googlefont_js',
					'label'			=> esc_html__( 'Turn off Google Font', 'woo-tour' ),
					'description'	=> esc_html__( "Turn off loading Google Font if you dont use Google Font", 'woo-tour' ),
					'type'			=> 'checkbox',
					'default'		=> ''
				),
			)
		);
		$_name = get_option('exwt_evt_name');
		$_pcode = get_option('exwt_evt_purcode');
		if($_name=='' || $_pcode=='' || get_option('exwt_license') =='invalid'){
			$sdes = '<p class="exwt-atrq">Please add a valid purchase code to continue, <a href="'.esc_url(admin_url('admin.php?page=wootours#plugin-license')).'">activate your license here</a></p>';
			$settings['general'] = array(
				'title'					=> esc_html__( 'General', 'exthemes' ),
				'description'			=> $sdes,
				'fields'				=> array()
			);
			$settings['single_tour'] = array(
				'title'					=> esc_html__( 'Single Tour', 'exthemes' ),
				'description'			=> $sdes,
				'fields'				=> array()
			);
			$settings['checkout_tour'] = array(
				'title'					=> esc_html__( 'Checkout', 'exthemes' ),
				'description'			=> $sdes,
				'fields'				=> array()
			);
			$settings['custom-css'] = array(
				'title'					=> esc_html__( 'Custom Code', 'exthemes' ),
				'description'			=> $sdes,
				'fields'				=> array()
			);
			$settings['js_css_settings'] = array(
				'title'					=> esc_html__( 'Js & Css file', 'exthemes' ),
				'description'			=> $sdes,
				'fields'				=> array()
			);
		}
		$settings['plugin-license'] = array(
			'title'					=> esc_html__( 'Plugin License', 'woo-tour' ),
			'description'			=> '<p><a href="'.esc_url(admin_url('admin.php?page=wootours&delete_license=yes#plugin-license')).'">Deactivate license from this site ?</a><p>',
			'fields'				=> array(
				array(
					'id' 			=> 'exwt_evt_name',
					'label'			=> esc_html__( 'Envato Username' , 'woo-tour' ),
					'description'	=> 'Enter Envato username which you have purchased this plugin (not email)',
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> ''
				),
				array(
					'id' 			=> 'exwt_evt_purcode',
					'label'			=> esc_html__( 'Purchase Code' , 'woo-tour' ),
					'description'	=> 'Enter your <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-"> purchase code </a> of this plugin',
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> ''
				),
			)
		);
		$settings = apply_filters( 'wootours_fields', $settings );
		return $settings;
	}
	/**
	 * Register plugin settings
	 * @return void
	 */
	public function register_settings() {
		if( is_array( $this->settings ) ) {
			foreach( $this->settings as $section => $data ) {
				// Add section to page
				add_settings_section( $section, $data['title'], array( $this, 'settings_section' ), 'wootours' );
				foreach( $data['fields'] as $field ) {
					// Validation callback for field
					$validation = '';
					if( isset( $field['callback'] ) ) {
						$validation = $field['callback'];
					}
					// Register field
					$option_name = $this->settings_base . $field['id'];
					register_setting( 'wootours', $option_name, $validation );
					// Add field to page
					add_settings_field( $field['id'], $field['label'], array( $this, 'display_field' ), 'wootours', $section, array( 'field' => $field, 'class' =>$field['id'] ) );
				}
			}
		}
	}
	public function settings_section( $section ) {
		$html = '<p class="'.$section['id'].'"> ' . $this->settings[ $section['id'] ]['description'] . '</p>' . "\n";
		echo $html;
	}
	/**
	 * Generate HTML for displaying fields
	 * @param  array $args Field data
	 * @return void
	 */
	public function display_field( $args ) {
		$field = $args['field'];
		$html = '';
		$option_name = $this->settings_base . $field['id'];
		$option = get_option( $option_name );
		$data = '';
		if( isset( $field['default'] ) ) {
			$data = $field['default'];
			if( $option ) {
				$data = $option;
			}
		}
		switch( $field['type'] ) {
			case 'text':
			case 'password':
			case 'number':
				$_name = get_option('exwt_evt_name');
				$_pcode = get_option('exwt_evt_purcode');
				$val = esc_attr($data);
				if($option_name == 'exwt_evt_purcode' && $_name!='' && $_pcode!='' && get_option('exwt_license') !='invalid'){
					$val = '***';
				}
				$html .= '<input id="' . esc_attr( $field['id'] ) . '" type="' . $field['type'] . '" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" value="' . $val . '"/>' . "\n";
			break;
			case 'text_secret':
				$html .= '<input id="' . esc_attr( $field['id'] ) . '" type="text" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" value=""/>' . "\n";
			break;
			case 'textarea':
				$html .= '<textarea id="' . esc_attr( $field['id'] ) . '" rows="5" cols="50" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '">' . $data . '</textarea><br/>'. "\n";
			break;
			case 'checkbox':
				$checked = '';
				if( $option && 'on' == $option ){
					$checked = 'checked="checked"';
				}
				$html .= '<input id="' . esc_attr( $field['id'] ) . '" type="' . $field['type'] . '" name="' . esc_attr( $option_name ) . '" ' . $checked . '/>' . "\n";
			break;
			case 'checkbox_multi':
				foreach( $field['options'] as $k => $v ) {
					$checked = false;
					if( is_array($data) && in_array( $k, $data)){
						$checked = true;
					}
					$html .= '<label for="' . esc_attr( $field['id'] . '_' . $k ) . '" class="cb-inline"><input type="checkbox" ' . checked( $checked, true, false ) . ' name="' . esc_attr( $option_name ) . '[]" value="' . esc_attr( $k ) . '" id="' . esc_attr( $field['id'] . '_' . $k ) . '" /> ' . wp_kses_post($v) . '</label> ';
				}
			break;
			case 'radio':
				foreach( $field['options'] as $k => $v ) {
					$checked = false;
					if( $k == $data ) {
						$checked = true;
					}
					$html .= '<label for="' . esc_attr( $field['id'] . '_' . $k ) . '"><input type="radio" ' . checked( $checked, true, false ) . ' name="' . esc_attr( $option_name ) . '" value="' . esc_attr( $k ) . '" id="' . esc_attr( $field['id'] . '_' . $k ) . '" /> ' . $v . '</label> ';
				}
			break;
			case 'select':
				$html .= '<select name="' . esc_attr( $option_name ) . '" id="' . esc_attr( $field['id'] ) . '">';
				foreach( $field['options'] as $k => $v ) {
					$selected = false;
					if( $k == $data ) {
						$selected = true;
					}
					$html .= '<option ' . selected( $selected, true, false ) . ' value="' . esc_attr( $k ) . '">' . $v . '</option>';
				}
				$html .= '</select> ';
			break;
			case 'select_multi':
				$html .= '<select name="' . esc_attr( $option_name ) . '[]" id="' . esc_attr( $field['id'] ) . '" multiple="multiple">';
				foreach( $field['options'] as $k => $v ) {
					$selected = false;
					if( in_array( $k, $data ) ) {
						$selected = true;
					}
					$html .= '<option ' . selected( $selected, true, false ) . ' value="' . esc_attr( $k ) . '" />' . $v . '</label> ';
				}
				$html .= '</select> ';
			break;
			case 'image':
				$image_thumb = '';
				if( $data ) {
					$image_thumb = wp_get_attachment_thumb_url( $data );
				}
				$html .= '<img id="' . $option_name . '_preview" class="image_preview" src="' . $image_thumb . '" /><br/>' . "\n";
				$html .= '<input id="' . $option_name . '_button" type="button" data-uploader_title="' . esc_html__( 'Upload an image' , 'woo-tour' ) . '" data-uploader_button_text="' . esc_html__( 'Use image' , 'woo-tour' ) . '" class="image_upload_button button" value="'. esc_html__( 'Upload new image' , 'woo-tour' ) . '" />' . "\n";
				$html .= '<input id="' . $option_name . '_delete" type="button" class="image_delete_button button" value="'. esc_html__( 'Remove image' , 'woo-tour' ) . '" />' . "\n";
				$html .= '<input id="' . $option_name . '" class="image_data_field" type="hidden" name="' . $option_name . '" value="' . $data . '"/><br/>' . "\n";
			break;
			case 'color':
				?><div class="color-picker" style="position:relative;">
			        <input type="text" name="<?php esc_attr_e( $option_name ); ?>" class="color" value="<?php esc_attr_e( $data ); ?>" />
			        <div style="position:absolute;background:#FFF;z-index:99;border-radius:100%;" class="colorpicker"></div>
			    </div>
			    <?php
			break;
		}
		switch( $field['type'] ) {
			case 'checkbox_multi':
			case 'radio':
			case 'select_multi':
				$html .= '<br/><span class="description">' . $field['description'] . '</span>';
			break;
			default:
				$html .= '<label for="' . esc_attr( $field['id'] ) . '"><span class="description">' . $field['description'] . '</span></label>' . "\n";
			break;
		}
		echo $html;
	}
	/**
	 * Validate individual settings field
	 * @param  string $data Inputted value
	 * @return string       Validated value
	 */
	public function validate_field( $data ) {
		if( $data && strlen( $data ) > 0 && $data != '' ) {
			$data = urlencode( strtolower( str_replace( ' ' , '-' , $data ) ) );
		}
		return $data;
	}
	/**
	 * Load settings page content
	 * @return void
	 */
	public function settings_page() {
		// Build page HTML
		$html = '<div class="wrap" id="wootours">' . "\n";
			$html .= '<h2>' . esc_html__( 'WooTours Settings' , 'woo-tour' ) . '</h2>' . "\n";
			$html .= '<form method="post" action="options.php" enctype="multipart/form-data">' . "\n";
				// Setup navigation
				$html .= '<ul id="settings-sections" class="subsubsub hide-if-no-js">' . "\n";
					//$html .= '<li><a class="tab all current" href="#standard">' . esc_html__( 'All' , 'woo-tour' ) . '</a></li>' . "\n";
					foreach( $this->settings as $section => $data ) {
						$html .= '<li><a class="tab" href="#' . $section . '">' . $data['title'] . '</a></li>' . "\n";
					}
				$html .= '</ul>' . "\n";
				$html .= '<div class="clear"></div>' . "\n";
				// Get settings fields
				ob_start();
				settings_fields( 'wootours' );
				do_settings_sections( 'wootours' );
				$html .= ob_get_clean();
				$html .= '<p class="submit">' . "\n";
					$html .= '<input name="Submit" type="submit" class="button-primary" value="' . esc_attr( esc_html__( 'Save Settings' , 'woo-tour' ) ) . '" />' . "\n";
				$html .= '</p>' . "\n";
			$html .= '</form>' . "\n";
		$html .= '</div>' . "\n";
		echo $html;
	}
}

add_action('pre_update_option_exwt_evt_purcode', function( $value, $old_value ) {
	if($value =='***' && $old_value!=$value){
		$value = $old_value;
	}
	return $value;
}, 10,2);

add_action( 'admin_init', 'exwt_del_license' );
if(!function_exists('exwt_del_license')){
	function exwt_del_license() {
		if(is_user_logged_in() && current_user_can( 'manage_options' ) && isset($_GET['page']) && $_GET['page']=='wootours' && isset($_GET['delete_license']) && $_GET['delete_license']=='yes' ){
			$_name = get_option('exwt_evt_name');
			$_pcode = get_option('exwt_evt_purcode');
			$site = get_site_url();
			$url = 'https://exthemes.net/verify-purchase-code/';
			$data = array('buyer' => $_name, 'code' => $_pcode, 'item_id' =>'19404740', 'site' => $site, 'delete'=>'yes');
			$options = array(
			        'http' => array(
			        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			        'method'  => 'POST',
			        'content' => http_build_query($data),
			    )
			);

			$context  = stream_context_create($options);
			$res = @file_get_contents($url, false, $context);
			delete_option( 'exwt_ckforupdate');
			delete_option( 'exwt_li_mes');
			delete_option( 'exwt_license');
			delete_option( 'exwt_cupdate');
			delete_option( 'exwt_evt_name');
			delete_option( 'exwt_evt_purcode');
			wp_redirect( ( admin_url( '?page=wootours#plugin-license' ) ) );
			die;
		}
	}
}
// active into
function exwt_check_purchase_code() {
	$class = 'notice notice-error exwt-atrq';
	$message =  'You are using an unregistered version of WooTours, please <a href="'.esc_url(admin_url('admin.php?page=wootours#plugin-license')).'">active your license</a> of WooTours';

	printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
}
function exwt_invalid_pr_code() {
	$class = 'notice notice-error';
	$get_mes = get_option( 'exwt_li_mes');
	$get_mes = $get_mes!='' ? explode('|', $get_mes) : '';
	if(is_array($get_mes) && !empty($get_mes)){
		$message =  'Invalid purchase code for WooTours plugin, This license has registered for: '. $get_mes[0] .' - '. $get_mes[1] ;
	}else{
		$message =  'Invalid purchase code for WooTours plugin, please find check how to find your purchase code <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-">here </a>';
	}
	printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
}
$scd_ck = get_option( 'exwt_ckforupdate');
$crt = strtotime('now');
$_name = get_option('exwt_evt_name');
$_pcode = get_option('exwt_evt_purcode');
if ($_name =='' || $_pcode=='' ) {
	add_action( 'admin_notices', 'exwt_check_purchase_code' );
	delete_option( 'exwt_ckforupdate');
}
if($scd_ck=='' || $crt > $scd_ck ){
	if($_name=='' || $_pcode==''){
		delete_option( 'exwt_li_mes');
	}else{
		$check_version = '';
		global $pagenow;
		if((isset($_GET['page']) && ($_GET['page'] =='wootours' )) || (isset($_GET['post_type']) && $_GET['post_type']=='product') || $pagenow == 'plugins.php' ){
			
			$site = get_site_url();
			$url = 'https://exthemes.net/verify-purchase-code/';
			$myvars = 'buyer=' . $_name . '&code=' . $_pcode. '&site='.$site.'&item_id=19404740';
			$res = '';
			if(function_exists('stream_context_create')){
				$data = array('buyer' => $_name, 'code' => $_pcode, 'item_id' =>'19404740', 'site' => $site);
				$options = array(
				        'http' => array(
				        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				        'method'  => 'POST',
				        'content' => http_build_query($data),
				    )
				);

				$context  = stream_context_create($options);
				$res = @file_get_contents($url, false, $context);
				if($res=== false){
					$res!='';
				}
			}
			if($res!=''){
				$res = json_decode($res);
			}else{
				$ch = curl_init( $url );
				curl_setopt( $ch, CURLOPT_POST, 1);
				curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
				curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt( $ch, CURLOPT_HEADER, 0);
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
				curl_setopt($ch, CURLOPT_TIMEOUT, 2);
				$res=json_decode(curl_exec($ch),true);
				curl_close($ch);
			}
			$check_version = isset($res[5]) ? $res[5] : '';
			update_option( 'exwt_version', $check_version );
			//print_r( $res) ;exit;
			update_option( 'exwt_license', '');
			if(isset($res[0]) && $res[0] == 'error' && $_name!='' && $_pcode!=''){
				update_option( 'exwt_ckforupdate', '' );
				if(isset($res[2]) && isset($res[2][0]) && $res[2][0] == 'invalid'){
					update_option( 'exwt_li_mes', $res[2][1][0] );
				}
				update_option( 'exwt_ckforupdate', strtotime('+7 day') );
				update_option( 'exwt_license', 'invalid');
			}else if(isset($res[0]) && $res[0] == 'success'){
				update_option( 'exwt_ckforupdate', strtotime('+15 day') );
				delete_option( 'exwt_li_mes');
			}else{
				update_option( 'exwt_ckforupdate', strtotime('+10 day') );
			}
		}
	}
}
if(get_option('exwt_license') =='invalid'){
	add_action( 'admin_notices', 'exwt_invalid_pr_code' );
}
if( ! function_exists('get_plugin_data') ){
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}
$plugin_data = get_plugin_data( WP_PLUGIN_DIR  . '/woo-tour/woo-tour.php' );
$plugin_version = str_replace('.', '',$plugin_data['Version']);
$check_version = get_option( 'exwt_version');
$check_version = $check_version !='' ? str_replace('.', '',$check_version) : '';
if(strlen($check_version) > strlen($plugin_version)){
	$plugin_version = is_numeric($plugin_version) ?  $plugin_version *10 : '';
}else if(strlen($check_version) < strlen($plugin_version)){
	$check_version = is_numeric($check_version) ?  $check_version *10 : '';
}
	if($check_version!='' && $check_version > $plugin_version){
		add_filter('wp_get_update_data','exwt_up_count_pl',10);
		function exwt_up_count_pl($update_data){
			$update_data['counts']['plugins'] =  $update_data['counts']['plugins'] + 1;
			return $update_data;
		}
		if (file_exists( WP_PLUGIN_DIR.'/woo-tour/woo-tour.php' ) ) {
			add_action( 'after_plugin_row_/woo-tour/woo-tour.php', 'show_purchase_notice_under_plugin', 10 );
		}else{
		add_action( 'after_plugin_row_woo-exfood/woo-food.php', 'show_purchase_notice_under_plugin', 10 );
	}
	function show_purchase_notice_under_plugin(){
		$text = sprintf(
			esc_html__( 'There is a new version of WooTours available. %1$s View details %2$s and please check how to update plugin %3$s here%4$s.', 'exthemes' ),
				'<a href="https://codecanyon.net/item/wootour-woocommerce-travel-tour-and-appointment-booking/19404740#item-description__changelog" target="_blank">',
				'</a>', 
				'<a href="https://exthemes.net/wootour/doc/" target="_blank">',
				'</a>'
			);
		echo '
		<style>[data-slug="woo-tour"].active td,[data-slug="woo-tour"].active th { box-shadow: none;}</style>
		<tr class="plugin-update-tr active">
			<td colspan="4" class="plugin-update">
				<div class="update-message notice inline notice-alt"><p>'.$text.'</p></div>
			</td>
		</tr>';
	}
}