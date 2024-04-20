<?php
class WooTour_Hook {
	public function __construct(){
		if(get_option('wt_metaposition') == 'above'){
			add_action( 'woocommerce_after_single_product_summary', array( &$this,'woocommerce_single_ev_meta') );
		}else{
			add_action( 'woocommerce_single_product_summary', array( &$this,'woocommerce_single_ev_meta') );
		}
		add_action( 'woocommerce_after_single_product_summary', array( &$this,'woocommerce_single_ev_schedu') );
		add_filter('loop_shop_columns', array( &$this,'ex_loop_columns'));
		add_action( 'woocommerce_before_shop_loop_item', array( &$this,'woocommerce_shopitem_ev_meta'),99 );
		add_action( 'woocommerce_after_shop_loop_item_title', array( &$this,'woocommerce_shopitem_ev_more_meta') );
		add_filter( 'woocommerce_loop_add_to_cart_link', array( &$this,'change_product_link') );
		add_action( 'init', array( &$this,'remove_upsell') );
		add_action( 'widgets_init', array( &$this,'wt_widgets_init') );
 		add_filter( 'woocommerce_output_related_products_args', array( &$this,'related_products_item'), 99 );
		add_filter( 'woocommerce_product_single_add_to_cart_text', array( &$this,'woo_custom_cart_button_text'));  
		add_action('woocommerce_before_single_product',array( &$this,'add_info_before_single'),11);
		add_action('exwt_before_single_event',array( $this,'add_info_before_single'),11);
		add_filter( 'woocommerce_product_tabs', array( &$this,'woo_remove_product_tabs'), 98 );
		add_filter( 'woocommerce_single_product_image_html', array( &$this,'woo_remove_product_image'), 98 );
		//add_action( 'woocommerce_email_before_order_table', array( &$this,'woocommerce_email_hook'));
		add_filter ('woocommerce_add_to_cart_redirect', array( &$this,'woocommerce_redirect_to_checkout'));
		add_filter('wt_related_products_args',array( &$this,'wc_remove_related_products'), 10);
		add_action('woocommerce_single_product_summary',array( &$this,'show_disacount_info'),21);
		// Ver 3.5
		add_action('exwt_before_atc_form',array( &$this,'show_disacount_info'));
		add_action('exwt_before_content_1',array( &$this,'show_disacount_info_details'));
		add_action('exwt_after_content_2',array( &$this,'show_disacount_info_details'));
		add_action('exwt_after_content_3',array( &$this,'show_disacount_info_details'));
		add_action( 'woocommerce_after_single_product_summary', array( &$this,'show_disacount_info_details') );
    }
	//remove product tabs if layout 2
	function woo_remove_product_tabs( $tabs ) {
		global $exwt_sgct;
		if($exwt_sgct== true){ return $tabs;}
		global $woocommerce, $post;
		if(wootour_global_layout() =='layout-2' || wootour_global_layout() =='layout-3'){
				unset( $tabs['description'] ); 
		}
		return $tabs;
	}
	//remove image
	function woo_remove_product_image( $image ) {
		if(wootour_global_layout() =='layout-2' || wootour_global_layout() =='layout-3'){
			$image ='';
		}
		return $image;
	}
	//remove button if event pass
	function add_info_before_single(){
		global $woocommerce, $post;
		$exwf_sgpp = exwt_get_layout_purpose($post->ID);
		if($exwf_sgpp =='woo'){
			return;
		}
		$time_now =  strtotime("now");
		$gmt_offset = get_option('gmt_offset');
		if($gmt_offset!=''){
			$time_now = $time_now + ($gmt_offset*3600);
		}
		$expireddate = wt_global_expireddate() ;
		if($expireddate !='' && $time_now > $expireddate){
			$mess = esc_html__('This tour has expired','woo-tour');
			echo '
			<div class="alert alert-warning tour-mes-info"><i class="fa fa-exclamation-triangle"></i>'.$mess.'</div>
			<style type="text/css">.woocommerce div.product form.cart, .woocommerce div.product p.cart{ display:none !important}</style>';
		}
		if(wootour_global_layout() =='layout-2' || wootour_global_layout() =='layout-3'){
			global $exwt_sgct;
			if($exwt_sgct== true){ return;}
			wootour_template_plugin('layout-2');
		}
	}
	function woo_custom_cart_button_text($text) {
		global $woocommerce, $post,$product;
		$type = $product->get_type();
		if($type=='external' && get_post_meta($post->ID,'_button_text',true)!=''){
			return get_post_meta($post->ID,'_button_text',true);
		}
		if(exwt_get_layout_purpose(get_the_ID())=='woo'){ return $text;}
		$wt_main_purpose = wt_global_main_purpose();
		$wt_layout_purpose = get_post_meta($post->ID,'wt_layout_purpose',true);
		if($wt_main_purpose=='custom' && $wt_layout_purpose=='woo'){
			return esc_html__( 'Add To Cart', 'woo-tour' );
		}
		return esc_html__( 'Book Now', 'woo-tour' );
	}
	//change text
	function related_products_item( $args ) {
		$wt_related_count = get_option('wt_related_count');
		if(!is_numeric($wt_related_count) || $wt_related_count==''){
			$wt_related_count = 3;
		}
		$args['posts_per_page'] = $wt_related_count; // number related products
		$args['columns'] = 3; 
		return $args;
	}
	//Register sidebars
	function wt_widgets_init() {
		if(get_option('wt_sidebar') !='hide'){
			register_sidebar( array(
				'name' => esc_html__('WooTour','woo-tour'),
				'id' => 'wootour-sidebar',
				'description' => esc_html__('Sidebar for all pages of WooTours.','woo-tour'),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '<div class="clear"></div></div></div></div>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3><div class="wooe-sidebar"><div class="wooe-wrapper">',
			) );
		}
	}
	// change orderby
	function remove_upsell() {
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
	}
	// change add to cart link
	function change_product_link( $link ) {
		global $product;
		$product_id = $product->get_id();
		$product_sku = $product->get_sku();
		$vialltrsl =  esc_html__( 'View Details', 'woo-tour' );
		$link = '<a href="'.get_permalink().'" rel="nofollow" data-product_id="'.$product_id.'" data-product_sku="'.$product_sku.'" data-quantity="1" class="button add_to_cart_button product_type_variable">'.$vialltrsl.'</a>';
		return $link;
	}
	//List item per row
	function ex_loop_columns() {
		return 3; // 3 products per row
	}
	// Single Custom meta 
	function woocommerce_single_ev_meta() {
		global $woocommerce, $post;
		if(wootour_global_layout() !='layout-2' && wootour_global_layout() !='layout-3'){
			wootour_template_plugin('tour-meta');
		}
	}
	function woocommerce_single_ev_schedu() {
		global $woocommerce, $post;
		wootour_template_plugin('tour-acco');
	}
	// Add meta to item of shop
	function woocommerce_shopitem_ev_meta(){
		$location = wt_taxonomy_info('wt_location','off');
		if($location != ''){
			echo '<div class="wt-location-arr"><i class="fa fa-map-marker"></i>'.$location.'</div>';
		}
	}
	// Add more meta to item of shop
	function woocommerce_shopitem_ev_more_meta(){
		global $woocommerce, $post;
		$price = '';
		$wt_enddate = wt_global_expireddate();
		$wt_adress = get_post_meta( $post->ID, 'wt_adress', true ) ;
		global $product;	
		$type = $product->get_type();
		$price_html = $product->get_price();
		if($type=='variable'){
			$price = wt_variable_price_html();
		}else{
			if ( $price_html = $product->get_price_html() ) :
				$price = $price_html;
			endif; 	
		}
		echo '
		<div class="shop-wt-more-meta">';
			if($price!=''){
				echo '<span><i class="fa fa-shopping-basket"></i>'.$price.'</span>';
			}
			echo wt_meta_html();	
			echo '
		</div>';
	}
	// Email hook
	function woocommerce_email_hook($order){
		$event_details = new WC_Order( $order->get_id());
		global $event_items;
		$event_items = $event_details->get_items();
		wootour_template_plugin('email-tour-details');
	}
	// redirect to checkout
	function woocommerce_redirect_to_checkout($wc) {
		if(get_option('wt_enable_cart')=='off'){
			global $woocommerce;
			$checkout_url = wc_get_checkout_url();
			return $checkout_url;
		}
		return $wc;
	}
	// remove related
	function wc_remove_related_products( $args ) {
		$wt_srelated = get_option('wt_srelated');
		if($wt_srelated =='off'){
			return array();
		}else{
			return $args;
		}
	}
	// discount info
	function show_disacount_info(){
		global $wt_disc_bo, $wt_discount,$dtp;
		$dtp ='';
		$wt_disc_bo = get_post_meta(get_the_ID(),'wt_disc_bo',true);
		$wt_discount = get_post_meta(get_the_ID(),'wt_discount',false);
		wootour_template_plugin('discount');
	}
	function show_disacount_info_details(){
		global $wt_disc_bo, $wt_discount,$dtp;
		$dtp ='details';
		$wt_disc_bo = get_post_meta(get_the_ID(),'wt_disc_bo',true);
		$wt_discount = get_post_meta(get_the_ID(),'wt_discount',false);
		wootour_template_plugin('discount');
	}
}
$WooTour_Hook = new WooTour_Hook();