<?php
global $sitepress;
$current_language = $sitepress->get_current_language();	
class WooTour_Booking {
	public function __construct(){
		add_filter( 'woocommerce_is_sold_individually',  array( &$this,'wc_remove_all_quantity_fields'), 10, 2 );
		//add_action( 'woocommerce_before_calculate_totals', array( &$this,'add_custom_total_price'), 199 );
		//add_action('wp_ajax_wdm_add_user_custom_data_options', array( &$this,'add_user_data_booking'));
		//add_action('wp_ajax_nopriv_wdm_add_user_custom_data_options', array( &$this,'add_user_data_booking'));
		add_filter('woocommerce_add_cart_item_data',array( &$this,'add_cart_user_data'),99,2);
		add_filter('woocommerce_get_cart_item_from_session', array( &$this,'get_cart_items_from_session'), 999, 3 );
		add_filter('woocommerce_get_item_data',array( &$this,'add_user_info_booking_from_session_into_cart'),1,3);
		add_action('woocommerce_new_order_item',array( &$this,'add_info_to_order_item_meta'),10,2);
		//add form simple product
		add_action('woocommerce_before_add_to_cart_button',array( &$this,'html_custom_field'),1,1);
		//add form variable product
		$pos_of_date = apply_filters('exwt_pos_of_picker_variation','woocommerce_before_variations_form');
		add_action($pos_of_date,array( &$this,'html_custom_field_for_variable'),1,1);
		add_filter( 'woocommerce_order_item_meta_end', array( &$this,'display_item_order_meta'), 9, 3 );
		add_action('woocommerce_before_cart_item_quantity_zero',array( &$this,'remove_user_data_booking_from_cart'),1,1);
		add_filter( 'woocommerce_add_to_cart_validation', array( &$this,'validate_add_cart_item'), 10, 5 );
		//add_filter ( 'woocommerce_cart_item_subtotal' , array( &$this,'remove_subtotal'),11 ,3);
		// remove support Deposit
		//add_filter ( 'woocommerce_calculate_totals' , array( &$this,'check_calculate_totals'),1 ,99);
		add_action( 'woocommerce_after_add_to_cart_quantity', array( &$this,'stock_info_html'), 31 );
		// hook this on priority 31, display below add to cart button.
		add_action( 'woocommerce_before_add_to_cart_quantity', array( &$this,'update_live_total_price_html'), 32 );
		add_action( 'woocommerce_before_variations_form', array( &$this,'variation_disable_days'));
    }

	function stock_info_html() {
		echo '<span class="wt-tickets-status"></span>';
	}
	function check_calculate_totals( $data) {
		$cart_object = $data;
		foreach ( $cart_object->cart_contents as $key => $value ) {
			if( isset( $value[ 'deposit' ] ) && $value[ 'deposit' ][ 'enable' ] === 'yes' ){
				$product = $value[ 'data' ];
				if( $product->get_type() == 'variation' ){
					//check override
					$override = $product->get_meta( '_wc_deposits_override_product_settings' , true ) === 'yes';
					if( $override ){
						$amount_type = $product->get_meta( '_wc_deposits_amount_type' , true );
						$deposit_amount = floatval( $product->get_meta( '_wc_deposits_deposit_amount' , true ) );
					} else{
						$parent = wc_get_product( $product->get_parent_id() );
	                    $amount_type = $parent->get_meta( '_wc_deposits_amount_type' , true );
						$deposit_amount = floatval( $parent->get_meta( '_wc_deposits_deposit_amount' , true ) );
					}
				} else{
					$amount_type = $product->get_meta( '_wc_deposits_amount_type' , true );
					$deposit_amount = $product->get_meta( '_wc_deposits_deposit_amount' , true );
				}
				if( $amount_type != 'fixed' ){
					$cart_object->cart_contents[ $key ]['deposit']['total'] = $value['data']->price;
					$cart_object->cart_contents[ $key ]['deposit']['deposit'] = $value['data']->price * $deposit_amount/100;
					$cart_object->cart_contents[ $key ]['deposit']['remaining'] = $value['data']->price * (100-$deposit_amount)/100;
				}
			}
			/*
			$rm = '';
			if(isset($value['_adult']) && $value['_adult']!=''){
				$rm = $value['line_total'] - $value['data']->price;
				$cart_object->cart_contents[ $key ]['line_subtotal'] = $cart_object->cart_contents[ $key ]['line_total'] = $value['data']->price;
			}
			$cart_object-> cart_contents_total = $cart_object-> cart_contents_total - $rm;
			$cart_object-> subtotal = $cart_object-> subtotal - $rm;
			$cart_object-> subtotal_ex_tax = $cart_object-> subtotal_ex_tax - $rm;
			$cart_object->removed_cart_contents = array();
			*/
		}
		return $cart_object;
	}
	function remove_subtotal( $wc, $cart_item, $cart_item_key  ) {
		//print_r($cart_item);
		if(isset($cart_item['_adult']) && $cart_item['_adult']!=''){
			$product = $cart_item['data'];
       		if ($product->wc_deposits_enable_deposit === 'yes' && !empty($cart_item['deposit']) && $cart_item['deposit']['enable'] === 'yes'){
				$tax = get_option('wc_deposits_tax_display', 'no') === 'yes' ?  $product->get_price_including_tax($cart_item['quantity']) -
        $product->get_price_excluding_tax($cart_item['quantity']) : 0;
				$deposit = $cart_item['deposit']['deposit'];
				$remaining = $cart_item['line_subtotal']*1 - $deposit*1;
				
				return woocommerce_price($deposit + $tax) . ' ' . __('Deposit', 'woocommerce-deposits') . '<br/>(' .
					   woocommerce_price($remaining) . ' ' . __('Remaining', 'woocommerce-deposits') . ')';
			}else{
				return wc_price( $cart_item['line_subtotal'] );
			}
		}else{
			return $wc;
		}
	}
	// Stop event booking before X day event start
	function validate_add_cart_item( $passed, $product_id, $quantity, $variation_id = '', $variations= '' ) {
		
		if(isset($_GET['departure']) && $_GET['departure']!=''){ 
			$_POST['wt_date']= esc_attr($_GET['departure']);
			$date = str_replace('/', '-', $_GET['departure']);
			$_POST['wt_sldate'] = date('Y_m_d', strtotime($date));
		}
		if(isset($_GET['adult'])){ $_POST['wt_number_adult']= esc_attr($_GET['adult']); }
		if(is_numeric($variation_id) && $variation_id > 0 || ($variation_id =='' && get_post_type($product_id) == 'product_variation')){
			$wt_child = wt_get_price(($variation_id=='' ? $product_id : $variation_id ), '_child_price','',true);
			$wt_infant = wt_get_price(($variation_id=='' ? $product_id : $variation_id ), '_infant_price','',true);
			$wt_ctps1 = wt_get_price(($variation_id=='' ? $product_id : $variation_id ), '_ctfield1_price','',true);
			$wt_ctps2 = wt_get_price(($variation_id=='' ? $product_id : $variation_id ), '_ctfield2_price','',true);
		}else{
			$wt_child = wt_get_price($product_id, 'wt_child','',true);
			$wt_infant = wt_get_price($product_id, 'wt_infant','',true);
			$wt_ctps1 = wt_get_price($product_id, 'wt_ctps1','',true);
			$wt_ctps2 = wt_get_price($product_id, 'wt_ctps2','',true);
		}
		if(isset($_GET['child']) && (is_numeric($wt_child) && $wt_child >=0 || $wt_child!='OFF') ){ $_POST['wt_number_child']= esc_attr($_GET['child']); }
		if(isset($_GET['infant']) && (is_numeric($wt_infant) && $wt_infant >=0 || $wt_infant!='OFF') ){ $_POST['wt_number_infant']= esc_attr($_GET['infant']); }
		if(isset($_GET['ct1']) && (is_numeric($wt_ctps1) && $wt_ctps1 >=0 || $wt_ctps1!='OFF') ){ $_POST['wt_number_ct1']= esc_attr($_GET['ct1']); }
		if(isset($_GET['ct2']) && (is_numeric($wt_ctps2) && $wt_ctps2 >=0 || $wt_ctps2!='OFF') ){ $_POST['wt_number_ct2']= esc_attr($_GET['ct2']); }
		
		$wt_main_purpose = get_option('wt_main_purpose');
		if($wt_main_purpose=='custom' || $wt_main_purpose=='meta'){
			$wt_layout_purpose = get_post_meta($product_id,'wt_layout_purpose',true);
			$wt_slayout_purpose = get_option('wt_slayout_purpose');
			if(($wt_layout_purpose=='woo') || ($wt_main_purpose=='meta' && $wt_layout_purpose!='tour' && $wt_slayout_purpose=='woo') || ($wt_main_purpose=='custom' && $wt_layout_purpose=='')){
				return $passed;
			}
		}else if(exwt_get_layout_purpose($product_id)=='woo'){return $passed;}
		// do your validation, if not met switch $passed to false
		if ( !isset($_POST['wt_date']) || $_POST['wt_date']=='' ){
			$passed = apply_filters( 'wt_validate_select_date', false );
			if($passed!=true){
				$t_stopb = esc_html__('Please select Departure','woo-tour');
				wc_add_notice( $t_stopb, 'error' );
			}
		}else{
			$wt_sldate = isset($_POST['wt_sldate']) ? $_POST['wt_sldate'] : '';
			if ($wt_sldate!='' ||  isset($_POST['wt_number_adult']) ){
				$avari = get_post_meta($product_id, $wt_sldate, true);
				$mt_varst= get_option('wt_dismulti_varstock');
				$max_ad = get_post_meta($product_id, 'wt_adult_max', true);
				$min_ad = get_post_meta($product_id, 'wt_adult_min', true);
				if(is_numeric($variation_id) && $variation_id > 0){
					if($mt_varst!='yes'){
						$avari = get_post_meta($product_id, $wt_sldate.'_vaID_'.$variation_id, true);
					}
					$max_ad = get_post_meta($variation_id, '_max_adult', true);
					$min_ad = get_post_meta($variation_id, '_min_adult', true);
					
				}
				if((is_numeric($min_ad) && $_POST['wt_number_adult'] < $min_ad) ||($min_ad > 0 && !is_numeric($_POST['wt_number_adult']))){
					$passed = false;
					wc_add_notice( sprintf( esc_html__('Please select a number of adult no less than %s.','woo-tour' ) , $min_ad), 'error' );
				}
				if(is_numeric($max_ad) && $_POST['wt_number_adult'] > $max_ad){
					$passed = false;
					wc_add_notice( sprintf( esc_html__('Please select a number of adult no more than %s.','woo-tour' ) , $max_ad), 'error' );
				}
				$ud_qty = 0;
				if(isset($_POST['wt_number_adult']) && $_POST['wt_number_adult'] > 0){
					$ud_qty = $_POST['wt_number_adult']*1;
				}
				if(isset($_POST['wt_number_infant']) && $_POST['wt_number_infant'] > 0){
					$ud_qty = $ud_qty + $_POST['wt_number_infant']*1;
				}
				if(isset($_POST['wt_number_child']) && $_POST['wt_number_child'] > 0){
					$ud_qty = $ud_qty + $_POST['wt_number_child']*1;
				}
				if(isset($_POST['wt_number_ct1']) && $_POST['wt_number_ct1'] > 0){
					$ud_qty = $ud_qty + $_POST['wt_number_ct1']*1;
				}
				if(isset($_POST['wt_number_ct2']) && $_POST['wt_number_ct2'] > 0){
					$ud_qty = $ud_qty + $_POST['wt_number_ct2']*1;
				}
				
				if($avari==''){
					$def_stock_va = '';
					if($mt_varst!='yes' && is_numeric($variation_id) && $variation_id > 0){
						$def_stock_va = get_post_meta($product_id, $variation_id.'_def_stock', true);
					}
					$def_stock = $def_stock_va!='' ? $def_stock_va : get_post_meta($product_id, 'def_stock', true);
					//$def_stock = get_post_meta($product_id, 'def_stock', true);
					if($def_stock > 0){
						$avari = $def_stock;
					}
				}
				if($avari!='' && ($avari < $ud_qty)){
					$passed = false;
					$t_stopb = esc_html__('Sorry there is not enough stock','woo-tour');
					wc_add_notice( $t_stopb, 'error' );
				}
				global $woocommerce;
				$items = $woocommerce->cart->get_cart();
				$check_stock = 0;
				$cart_it = 0;
				foreach($items as $item => $values) { 
					if($values['product_id'] == $product_id && $values['_date'] == $_POST['wt_date'] ){
						$crr_qty = 0;
						if(isset($values['_adult']) && $values['_adult'] > 0){
							$crr_qty = $values['_adult']*1;
						}
						if(isset($values['_infant']) && $values['_infant'] > 0){
							$crr_qty = $crr_qty + $values['_infant']*1;
						}
						if(isset($values['_child']) && $values['_child'] > 0){
							$crr_qty = $crr_qty + $values['_child']*1;
						}
						if(isset($values['_wtct1']) && $values['_wtct1'] > 0){
							$crr_qty = $crr_qty + $values['_wtct1']*1;
						}
						if(isset($values['_wtct2']) && $values['_wtct2'] > 0){
							$crr_qty = $crr_qty + $values['_wtct2']*1;
						}
						$cart_it = $check_stock = $crr_qty;
					}
				}
				$check_stock = $check_stock + $ud_qty;
				if($avari!='' && ($avari < $check_stock) && $cart_it !=''){
					$passed = false;
					$t_stopb = sprintf(esc_html__('You cannot add that amount to the cart - we have %d in stock and you already have %d in your cart.', 'woo-tour'),$avari,$cart_it);
					wc_add_notice( $t_stopb, 'error' );
				}
			}
		}
		return $passed;
	
	}
	// remove select qty
	function wc_remove_all_quantity_fields( $return, $product ) {
		if(exwt_get_layout_purpose(get_the_ID())=='woo'){ return false;}
		return $return;
	}
	// Total booking price
	function add_custom_total_price( $cart_object ) {
		global $woocommerce;
		foreach ( $cart_object->cart_contents as $key => $value ) {
			$price_adu = $price_child = $price_inf = $price_ct1 = $price_ct2 = 0;
			$ud_qty ='';
			$ck_t = 0;
			if(isset($value['_child']) && $value['_child']!=''){
				$ck_t = 1;
				//$wt_child = get_post_meta( $value['product_id'], 'wt_child_sale', true );
				$wt_child = wt_get_price($value['product_id'], 'wt_child');
				if(isset($value['variation_id']) && is_numeric($value['variation_id']) && $value['variation_id'] > 0){	
					//$wt_child = get_post_meta( $value['variation_id'], '_child_price', true );
					$wt_child = wt_get_price($value['variation_id'], '_child_price');
				}
				if($wt_child !='OFF'){
					$price_child = is_numeric($wt_child) ? $wt_child * $value['_child'] : 0;
				}
			}
			if(isset($value['_infant']) && $value['_infant']!=''){
				$ck_t = 1;
				//$wt_infant = get_post_meta( $value['product_id'], 'wt_infant', true );
				$wt_infant = wt_get_price($value['product_id'], 'wt_infant');
				if(isset($value['variation_id']) && is_numeric($value['variation_id']) && $value['variation_id'] > 0){
					//$wt_infant = get_post_meta( $value['variation_id'], '_infant_price', true );
					$wt_infant = wt_get_price($value['variation_id'], '_infant_price');
				}
				if($wt_infant !='OFF'){
					$price_inf = $wt_infant!='' ? $wt_infant * $value['_infant'] : '';
				}
			}
			if(isset($value['_wtct1']) && $value['_wtct1']!=''){
				$ck_t = 1;
				$wt_ctps1 = wt_get_price($value['product_id'], 'wt_ctps1');
				if(isset($value['variation_id']) && is_numeric($value['variation_id']) && $value['variation_id'] > 0){
					$wt_ctps1 = wt_get_price($value['variation_id'], '_ctfield1_price');
				}
				if($wt_ctps1 !='OFF'){
					$price_ct1 = $wt_ctps1 * $value['_wtct1'] ;
				}
			}
			if(isset($value['_wtct2']) && $value['_wtct2']!=''){
				$ck_t = 1;
				$wt_ctps2 = wt_get_price($value['product_id'], 'wt_ctps2');
				if(isset($value['variation_id']) && is_numeric($value['variation_id']) && $value['variation_id'] > 0){
					$wt_ctps2 = wt_get_price($value['variation_id'], '_ctfield2_price');
				}
				if($wt_ctps2 !='OFF'){
					$price_ct2 = $wt_ctps2 * $value['_wtct2'] ;
				}
			}
			if( $ck_t == 1 && (!isset($value['_adult']) || $value['_adult']=='') ){
				$value['_adult'] = 0;
			}
			// check fixed price
			$wt_fixed_price = get_post_meta( $value['product_id'], 'wt_fixed_price', true );
			if($wt_fixed_price=='yes'){
				// do nothing with fixed price
			} else if(isset($value['_adult']) && ($value['_adult']!='' || ($value['_adult']=='0'))){
				if(isset($value['variation_id']) && is_numeric($value['variation_id']) && $value['variation_id'] > 0){
					$_product = new WC_Product_Variation( $value['variation_id'] );
					$pricefix =  $value['data']->get_price();
					$price_adu = $pricefix *($value['_adult'] - 1);
				}else{
					$product = new WC_Product($value['product_id']);
					$pricefix = $value['data']->get_price();
					$price_adu = $pricefix *($value['_adult'] - 1);
				}
				$wt_discount = get_post_meta($value['product_id'],'wt_discount',false);
				$wt_disc_bo = get_post_meta($value['product_id'],'wt_disc_bo',true);
				do_action('wt_all_cart_data',$value);
				if(!empty($wt_discount) && ( !isset($value['deposit_value']) || $value['deposit_value']=='' )){
					$cure_time =  strtotime("now");
					$gmt_offset = get_option('gmt_offset');
					if($gmt_offset!=''){
						$cure_time = $cure_time + ($gmt_offset*3600);
					}
					//echo '<pre>';print_r($value);//exit;
					usort($wt_discount, function($a, $b) { // anonymous function
						return $a['wt_disc_number'] - $b['wt_disc_number'];
					});
					$wt_discount = array_reverse($wt_discount);
					//print_r($wt_discount);//exit;
					foreach ($wt_discount as $item){
						$enddc = $item['wt_disc_end']!='' ? $item['wt_disc_end'] + 86399 : '';
						if($wt_disc_bo != 'season'){
							if(($item['wt_disc_start']=='' && $enddc=='') || ($item['wt_disc_start']!='' && $enddc=='' && $cure_time > $item['wt_disc_start']) || ($item['wt_disc_start']=='' && $enddc!='' && $cure_time < $enddc) || ($item['wt_disc_start']!='' && $enddc!='' && $cure_time < $enddc && $item['wt_disc_start'] < $cure_time) ){
								if($value['_adult'] >= $item['wt_disc_number']){
									if($item['wt_disc_type']=='percent' && $item['wt_disc_am'] > 0){
										$disc_price = $pricefix - ($pricefix * $item['wt_disc_am']/100);
									}elseif($item['wt_disc_am'] > 0){
										$disc_price = $pricefix - $item['wt_disc_am'];
									}else{break;}
									$pricefix = $disc_price;
									$price_adu = $pricefix *($value['_adult'] - 1);
									break;
								}
							}
						}elseif(isset($value['_metadate']) && $value['_metadate']!=''){
							$dtb = explode("_",$value['_metadate']);
							$dtb = $dtb[0].'-'.$dtb[1].'-'.$dtb[2];
							$dtb_unix = strtotime($dtb);
							if(($item['wt_disc_start']=='' && $enddc=='') || ($item['wt_disc_start']!='' && $enddc=='' && $dtb_unix >= $item['wt_disc_start']) || ($item['wt_disc_start']=='' && $enddc!='' && $dtb_unix < $enddc) || ($item['wt_disc_start']!='' && $enddc!='' && $dtb_unix < $enddc && $item['wt_disc_start'] <= $dtb_unix) ){
								 	if($item['wt_disc_type']=='percent' && $item['wt_disc_am'] > 0){
										$disc_price = $pricefix - ($pricefix * $item['wt_disc_am']/100);
										$price_child = $price_child > 0 ? $price_child - ($price_child * $item['wt_disc_am']/100) : 0;
										$price_child = $price_child < 0 ? 0 : $price_child; 
										$price_inf = $price_inf > 0 ? $price_inf - ($price_inf * $item['wt_disc_am']/100) : 0;
										$price_inf = $price_inf < 0 ? 0 : $price_inf; 
										$price_ct1 = $price_ct1 > 0 ? $price_ct1 - ($price_ct1 * $item['wt_disc_am']/100) : 0;
										$price_ct1 = $price_ct1 < 0 ? 0 : $price_ct1; 
										$price_ct2 = $price_ct2 > 0 ? $price_ct2 - ($price_ct2 * $item['wt_disc_am']/100) : 0;
										$price_ct2 = $price_ct2 < 0 ? 0 : $price_ct2; 
									}elseif($item['wt_disc_am'] > 0){
										$disc_price = $pricefix - $item['wt_disc_am'];
										
										$price_child = $price_child > 0 ? $price_child - ($item['wt_disc_am']*$value['_child']) : 0;
										$price_child = $price_child < 0 ? 0 : $price_child; 
										$price_inf = $price_inf > 0 ? $price_inf - ($item['wt_disc_am']*$value['_infant']) : 0;
										$price_inf = $price_inf < 0 ? 0 : $price_inf; 
										$price_ct1 = $price_ct1 > 0 ? $price_ct1 - ($item['wt_disc_am']*$value['_wtct1']) : 0;
										$price_ct1 = $price_ct1 < 0 ? 0 : $price_ct1; 
										$price_ct2 = $price_ct2 > 0 ? $price_ct2 - ($item['wt_disc_am']*$value['_wtct2']) : 0;
										$price_ct2 = $price_ct2 < 0 ? 0 : $price_ct2; 
									}else{break;}
									$pricefix = $disc_price;
									$price_adu = $pricefix *($value['_adult'] - 1);
									break;
							}
							
						}
					}
				}
				if(WC()->session->get('reload_checkout')==true){
					$value['data']->set_price(($value['data']->get_price()));
				}else{
					//$value['data']->price = $value['data']->price + $price_adu*1 + $price_child*1 + $price_inf*1;
					if(isset($cart_object->cart_contents[$key]['_prhaschanged']) && $cart_object->cart_contents[$key]['_prhaschanged']!=''){
						$pr_b = $cart_object->cart_contents[$key]['_prhaschanged'];
						
						if (class_exists('SitePress')  && class_exists('WCML_Multi_Currency_Prices')) {
							$currency = get_woocommerce_currency();
							if($currency!= $cart_object->cart_contents[$key]['_crc_when_book']){
								$exc_rates = exwt_wpmlget_exchange_rates();
								if (isset($exc_rates[$cart_object->cart_contents[$key]['_crc_when_book']])){
									$df_price = $pr_b/$exc_rates[$cart_object->cart_contents[$key]['_crc_when_book']];
									$pr_b = exwt_convert_currency($df_price,true);
								}
							}
							$cart_object->cart_contents[$key]['_crc_when_book'] = $currency;
						}else if(function_exists('wmc_get_price')){
							if(!isset ($cart_object->cart_contents[$key]['_unwoo_mlt']) || $cart_object->cart_contents[$key]['_unwoo_mlt']!=1){
								/*
								$setting         = new WOOMULTI_CURRENCY_Data();
								$selected_currencies = $setting->get_list_currencies();
								$current_currency    = $setting->get_current_currency();
								$uncv = $pr_b/$selected_currencies[$current_currency]['rate'];
								$cart_object->cart_contents[$key]['_unwoo_mlt'] = 1;
								$cart_object->cart_contents[$key]['_unwoo_mlt_price'] = $uncv;
								*/
							}
						};
						$cart_object->cart_contents[$key]['_prhaschanged'] = $pr_b;
						// Product addon flat fee		
						if(isset($value['addons'])){
							$apply_price_all = apply_filters( 'exwt_apply_addon_price_all', 0 );
							foreach($value['addons'] as $it_ad){
								if($it_ad['price_type'] =='flat_fee'){
									$dup_rm = $it_ad['price']* ($value['_adult'] - 1 );
									$pr_b = $pr_b - $dup_rm;
								}else if($apply_price_all == '1'){
									$nbif = isset($value['_infant']) && $value['_infant']!='' ? $value['_infant'] : 0;
									$nbchild = isset($value['_child']) && $value['_child']!='' ? $value['_child'] : 0;
									$pr_b = $pr_b +  ( $it_ad['price']* ($nbif + $nbchild));
								}
							}
						}
						$value['data']->set_price($pr_b);
						if(isset ($cart_object->cart_contents[$key]['_unwoo_mlt_price']) &&  $cart_object->cart_contents[$key]['_unwoo_mlt_price']!=''){
							$value['data']->set_price($cart_object->cart_contents[$key]['_unwoo_mlt_price']);
						}
					}else{
						$value['data']->set_price( $pricefix + $price_adu*1 + $price_child*1 + $price_inf*1 + $price_ct1*1 + $price_ct2*1 );
						$cart_object->cart_contents[$key]['_prhaschanged'] = $pricefix + $price_adu*1 + $price_child*1 + $price_inf*1 + $price_ct1*1 + $price_ct2*1;
						if (class_exists('SitePress')  && class_exists('WCML_Multi_Currency_Prices')) {
							$currency = get_woocommerce_currency();
							$cart_object->cart_contents[$key]['_crc_when_book'] = $currency;
						}
					}
				}
			}
		}
	}	
	// step 1 add user data booking to session
	function add_user_data_booking()
	{
		WC()->session->set( '_tour_info', '' );
		//Custom data - Sent Via AJAX post method
		$_tour_info= array();
		$product_id = isset($_POST['wt_tourid']) ? $_POST['wt_tourid'] : ''; //This is product ID
		$wt_date = isset($_POST['wt_date']) ? $_POST['wt_date'] : '';
		$wt_number_adult = isset($_POST['wt_number_adult']) ? $_POST['wt_number_adult'] : '';
		$wt_number_child = isset($_POST['wt_number_child']) ? $_POST['wt_number_child'] : '';
		$wt_number_infant = isset($_POST['wt_number_infant']) ? $_POST['wt_number_infant'] : '';
		// ct1,2
		$wt_number_ct1 = isset($_POST['wt_number_ct1']) ? $_POST['wt_number_ct1'] : '';
		$wt_number_ct2 = isset($_POST['wt_number_ct2']) ? $_POST['wt_number_ct2'] : '';
		$wt_sldate = isset($_POST['wt_sldate']) ? $_POST['wt_sldate'] : '';
		//session_start();
		$_tour_info['_date'] = $wt_date;
		$_tour_info['_adult'] =  $wt_number_adult;
		$_tour_info['_child'] =  $wt_number_child;
		$_tour_info['_infant'] =  $wt_number_infant;
		$_tour_info['_wtct1'] =  $wt_number_ct1;
		$_tour_info['_wtct2'] =  $wt_number_ct2;
		$_tour_info['_metadate'] =  $wt_sldate;
		$_tour_info['_duration'] = get_post_meta( $product_id, 'wt_duration', true );
	
		if ( ! WC()->session->has_session() ) {
			WC()->session->set_customer_session_cookie( true );
		}
	
		WC()->session->set( '_tour_info', $_tour_info );
		die();
	}
	// step 2 add user data booking to cart data
	function add_cart_user_data($cart_item_data,$product_id)
	{
		$wt_main_purpose = get_option('wt_main_purpose');
		if($wt_main_purpose=='custom' || $wt_main_purpose=='meta'){
			$wt_layout_purpose = get_post_meta($product_id,'wt_layout_purpose',true);
			$wt_slayout_purpose = get_option('wt_slayout_purpose');
			if(($wt_layout_purpose=='woo') || ($wt_main_purpose=='meta' && $wt_layout_purpose!='tour' && $wt_slayout_purpose=='woo') || ($wt_main_purpose=='custom' && $wt_layout_purpose=='')){
				//WC()->session->set( '_tour_info', '' );
				return $cart_item_data;
			}
		}
		//$_tour_info = WC()->session->get( '_tour_info' );
		/*--New way to add data--*/
		$_tour_info= array();
		$product_id = isset($_POST['wt_tourid']) ? $_POST['wt_tourid'] : ''; //This is product ID
		$wt_date = isset($_POST['wt_date']) ? $_POST['wt_date'] : '';
		$wt_number_adult = isset($_POST['wt_number_adult']) ? $_POST['wt_number_adult'] : '';
		$wt_number_child = isset($_POST['wt_number_child']) ? $_POST['wt_number_child'] : '';
		$wt_number_infant = isset($_POST['wt_number_infant']) ? $_POST['wt_number_infant'] : '';
		$wt_number_ct1 = isset($_POST['wt_number_ct1']) ? $_POST['wt_number_ct1'] : '';
		$wt_number_ct2 = isset($_POST['wt_number_ct2']) ? $_POST['wt_number_ct2'] : '';
		$wt_sldate = isset($_POST['wt_sldate']) ? $_POST['wt_sldate'] : '';
		$_tour_info['_date'] = $wt_date;
		$_tour_info['_adult'] =  $wt_number_adult;
		$_tour_info['_child'] =  $wt_number_child;
		$_tour_info['_infant'] =  $wt_number_infant;
		$_tour_info['_wtct1'] =  $wt_number_ct1;
		$_tour_info['_wtct2'] =  $wt_number_ct2;
		$_tour_info['_metadate'] =  $wt_sldate;
		$_tour_info['_duration'] = get_post_meta( $product_id, 'wt_duration', true );

		$new_value = array();
		if (isset($_tour_info['_date'])) {
			$_date = $_tour_info['_date'];
			$new_value['_date'] =  $_date;
		}
		if (isset($_tour_info['_adult'])) {
			$_adult = $_tour_info['_adult'];
			$new_value['_adult'] =  $_adult;
		}
		if (isset($_tour_info['_child'])) {
			$_child = $_tour_info['_child'];
			$new_value['_child'] =  $_child;
		}
		if (isset($_tour_info['_infant'])) {
			$_infant = $_tour_info['_infant'];
			$new_value['_infant'] =  $_infant;
		}
		if (isset($_tour_info['_wtct1'])) {
			$_wtct1 = $_tour_info['_wtct1'];
			$new_value['_wtct1'] =  $_wtct1;
		}
		if (isset($_tour_info['_wtct2'])) {
			$_wtct2 = $_tour_info['_wtct2'];
			$new_value['_wtct2'] =  $_wtct2;
		}
		if (isset($_tour_info['_metadate'])) {
			$_metadate = $_tour_info['_metadate'];
			$new_value['_metadate'] =  $_metadate;
		}
		if (isset($_tour_info['_duration'])) {
			$_duration = $_tour_info['_duration'];
			$new_value['_duration'] =  $_duration;
		}
		if(isset($_tour_info['_date'])) { unset( $_tour_info['_date'] );}
		if(isset($_tour_info['_adult'])) { unset( $_tour_info['_adult'] );}
		if(isset($_tour_info['_child'])) { unset( $_tour_info['_child'] );}
		if(isset($_tour_info['_infant'])) { unset( $_tour_info['_infant'] );}
		if(isset($_tour_info['_wtct1'])) { unset( $_tour_info['_wtct1'] );}
		if(isset($_tour_info['_wtct2'])) { unset( $_tour_info['_wtct2'] );}
		if(isset($_tour_info['_metadate'])) { unset( $_tour_info['_metadate'] );}
		if(isset($_tour_info['_duration'])) { unset( $_tour_info['_duration'] );}
		WC()->session->set( '_tour_info', '' );
		//Unset our custom session variable, as it is no longer needed.
		if( empty($_adult) && empty($_child) && empty($_date) && empty($_infant) && empty($_wtct1) && empty($_wtct2) )
			return $cart_item_data;
		else{
			if(empty($cart_item_data))
				return $new_value;
			else
				return array_merge($cart_item_data,$new_value);
		}
	}
	// step 3 get cart data from session from step 2
	function get_cart_items_from_session($item,$values,$key){
		//$_tour_info = WC()->session->get( '_tour_info' );
		//echo '<pre>';print_r($values);echo '</pre>';exit;
		if (array_key_exists( '_date', $values ) ){
			$item['_date'] = $values['_date'];
		}
		if (array_key_exists( '_adult', $values ) ){
			$item['_adult'] = $values['_adult'];
		}
		if (array_key_exists( '_child', $values ) ){
			$item['_child'] = $values['_child'];
		}
		if (array_key_exists( '_infant', $values ) ){
			$item['_infant'] = $values['_infant'];
		}
		if (array_key_exists( '_wtct1', $values ) ){
			$item['_wtct1'] = $values['_wtct1'];
		}
		if (array_key_exists( '_wtct2', $values ) ){
			$item['_wtct2'] = $values['_wtct2'];
		}
		if (array_key_exists( '_metadate', $values ) ){
			$item['_metadate'] = $values['_metadate'];
		}
		if (array_key_exists( '_duration', $values ) ){
			$item['_duration'] = $values['_duration'];
		}
		if ( isset( $values['_adult'] ) && ! empty ( $values['_adult'] ) ) {
			$wt_discount = get_post_meta($values['product_id'],'wt_discount',false);
			$wt_disc_bo = get_post_meta($values['product_id'],'wt_disc_bo',true);
			if(!empty($wt_discount) && ( !isset($values['deposit_value']) || $values['deposit_value']=='' )){
				$cure_time =  strtotime("now");
				$gmt_offset = get_option('gmt_offset');
				if($gmt_offset!=''){
					$cure_time = $cure_time + ($gmt_offset*3600);
				}
				usort($wt_discount, function($a, $b) { // anonymous function
					return (int)$a['wt_disc_number'] - (int)$b['wt_disc_number'];
				});
				$wt_discount = array_reverse($wt_discount);
				foreach ($wt_discount as $item_dc){
					$enddc = $item_dc['wt_disc_end']!='' ? $item_dc['wt_disc_end'] + 86399 : '';
					$item['_wtdiscount_type'] = $wt_disc_bo;
					if($wt_disc_bo != 'season'){
						if(($item_dc['wt_disc_start']=='' && $enddc=='') || ($item_dc['wt_disc_start']!='' && $enddc=='' && $cure_time > $item_dc['wt_disc_start']) || ($item_dc['wt_disc_start']=='' && $enddc!='' && $cure_time < $enddc) || ($item_dc['wt_disc_start']!='' && $enddc!='' && $cure_time < $enddc && $item_dc['wt_disc_start'] < $cure_time) ){
							if($values['_adult'] >= $item_dc['wt_disc_number']){
								if($item_dc['wt_disc_type']=='percent' && $item_dc['wt_disc_am'] > 0){
									$disc_value = $item_dc['wt_disc_am'].'%';
								}elseif($item_dc['wt_disc_am'] > 0){
									$disc_value = wc_price($item_dc['wt_disc_am']);
								}else{break;}
									$item['_wtdiscount'] = $disc_value;
								break;
							}
						}
					}elseif(isset($values['_metadate']) && $values['_metadate']!=''){
						$dtb = explode("_",$values['_metadate']);
						$dtb = $dtb[0].'-'.$dtb[1].'-'.$dtb[2];
						$dtb_unix = strtotime($dtb);
						if(($item_dc['wt_disc_start']=='' && $enddc=='') || ($item_dc['wt_disc_start']!='' && $enddc=='' && $dtb_unix >= $item_dc['wt_disc_start']) || ($item_dc['wt_disc_start']=='' && $enddc!='' && $dtb_unix < $enddc) || ($item_dc['wt_disc_start']!='' && $enddc!='' && $dtb_unix < $enddc && $item_dc['wt_disc_start'] <= $dtb_unix) ){
								if($item_dc['wt_disc_type']=='percent' && $item_dc['wt_disc_am'] > 0){
									$disc_value = $item_dc['wt_disc_am'].'%';
								}elseif($item_dc['wt_disc_am'] > 0){
									$disc_value = wc_price($item_dc['wt_disc_am']);
								}else{break;}
									$item['_wtdiscount'] = $disc_value;
								break;
						}
						
					}
					
				}
			}
		}
		return $item;
	}
	// step 4 add user info booking to cart
	function add_user_info_booking_from_session_into_cart($other_data, $cart_item ){		
		global $sitepress;
		$current_language = $sitepress->get_current_language();	
		$departurn ="Departure";	
		if ($current_language == 'vi') {
			$departurn ="Khởi hành";	
		}
		if ($current_language == 'en') {
			$departurn ="Departure";	
		}

		if ( isset( $cart_item['_date'] ) && ! empty ( $cart_item['_date'] ) ) {
			$wt_date_label = get_post_meta( $cart_item['product_id'], 'wt_date_label', true ) ;
			$wt_date_label = $wt_date_label!='' ? $wt_date_label : esc_html__($departurn,'woo-tour');
			$other_data[] = array(
				'name'  => $wt_date_label,
				'value' => $cart_item['_date']
			);
		}
		if(isset($cart_item['_metadate']) && $cart_item['_metadate']!=''){
			$dtb = explode("_",$cart_item['_metadate']);
			$dtb = $dtb[0].'-'.$dtb[1].'-'.$dtb[2];
			$dtb_unix = strtotime($dtb);
		}
		$season_price = $dtb_unix!= '' ? exwt_get_price_season($cart_item['product_id'],$dtb_unix,'',$cart_item['variation_id']) : '';
		if ( isset( $cart_item['_adult'] ) && ! empty ( $cart_item['_adult'] ) ) {
			$wt_adult_label = get_post_meta( $cart_item['product_id'], 'wt_adult_label', true ) ;
			$wt_adult_label = $wt_adult_label!='' ? $wt_adult_label : esc_html__('Adult','woo-tour');
			$_price_old = wc_get_product( $cart_item['data']->get_id() );
			/*$_price_old = wc_get_product( $cart_item['product_id'] );
			if(isset($cart_item['variation_id']) && $cart_item['variation_id']!=''){
				$_price_old = wc_get_product( $cart_item['variation_id'] );
			}*/
			$wt_fixed_price = get_post_meta( $cart_item['product_id'], 'wt_fixed_price', true );
			if($wt_fixed_price=='yes'){
				$ad_if = $cart_item['_adult'];
			}else{
				$pricefix = $_price_old->get_price();
				if(is_array($season_price) && !empty($season_price)){
					$pricefix =  wt_get_price('', '_adult',$season_price); 
				}
				$ad_if = $cart_item['_adult'].' x '.wt_addition_price_html($pricefix,true,'');
			}
			$other_data[] = array(
				'name'  => $wt_adult_label,
				'value' => $ad_if,
			);
		}

		$wt_child = wt_get_price($cart_item['product_id'], 'wt_child',$season_price);
		$wt_infant = wt_get_price($cart_item['product_id'], 'wt_infant',$season_price);
		$wt_ctps1 = wt_get_price($cart_item['product_id'], 'wt_ctps1',$season_price);
		$wt_ctps2 = wt_get_price($cart_item['product_id'], 'wt_ctps2',$season_price);
		if(isset($cart_item['variation_id']) && is_numeric($cart_item['variation_id']) && $cart_item['variation_id'] > 0){
			$wt_child = wt_get_price($cart_item['variation_id'], '_child_price',$season_price);
			$wt_infant = wt_get_price($cart_item['variation_id'], '_infant_price',$season_price);
			$wt_ctps1 = wt_get_price($cart_item['variation_id'], '_ctfield1_price',$season_price);
			$wt_ctps2 = wt_get_price($cart_item['variation_id'], '_ctfield2_price',$season_price);
		}
		if(is_numeric($wt_child)){
			$wt_child = wt_addition_price_html($wt_child);
			$wt_child = ' x '.$wt_child;
		}
		if(is_numeric($wt_infant)){
			$wt_infant = wt_addition_price_html($wt_infant);
			$wt_infant = ' x '.$wt_infant;
		}
		if(is_numeric($wt_ctps1)){
			$wt_ctps1 = wt_addition_price_html($wt_ctps1);
			$wt_ctps1 = ' x '.$wt_ctps1;
		}
		if(is_numeric($wt_ctps2)){
			$wt_ctps2 = wt_addition_price_html($wt_ctps2);
			$wt_ctps2 = ' x '.$wt_ctps2;
		}
		global $sitepress;
		$current_language = $sitepress->get_current_language();	
		$adu ="Adult: ";	
		$chil ="Children: ";	
		$Infant ="Infant: ";	
		if ($current_language == 'vi') {
			$adu ="Người lớn :";	
			$chil ="Trẻ em: ";	
			$Infant ="Sơ sinh: ";	
		}
		if ($current_language == 'en') {
			$adu ="Adult: ";	
			$chil ="Children: ";	
			$Infant ="Infant: ";	
		}
		if ( isset( $cart_item['_child'] ) && ! empty ( $cart_item['_child'] ) ) {
			$wt_child_label = get_post_meta( $cart_item['product_id'], 'wt_child_label', true ) ;
			$wt_child_label = $wt_child_label!='' ? $wt_child_label : esc_html__($chil,'woo-tour');
			$other_data[] = array(
				'name'  => $wt_child_label,
				'value' => $cart_item['_child'].$wt_child
			);
		}
		if ( isset( $cart_item['_infant'] ) && ! empty ( $cart_item['_infant'] ) ) {
			$wt_infant_label = get_post_meta( $cart_item['product_id'], 'wt_infant_label', true ) ;
			$wt_infant_label = $wt_infant_label!='' ? $wt_infant_label : esc_html__($Infant,'woo-tour');
			$other_data[] = array(
				'name'  => $wt_infant_label,
				'value' => $cart_item['_infant'].$wt_infant
			);
		}
		if ( isset( $cart_item['_wtct1'] ) && ! empty ( $cart_item['_wtct1'] ) ) {
			$wt_ctps1_label = get_post_meta( $cart_item['product_id'], 'wt_ctps1_label', true ) ;
			$label1 = explode("|",get_option('wt_ctfield1_info'));
			if(isset($label1[0]) && $label1[0]!=''){
				$wt_ctps1_label = $wt_ctps1_label!='' ? $wt_ctps1_label : $label1[0];
				$other_data[] = array(
					'name'  => $wt_ctps1_label,
					'value' => $cart_item['_wtct1'].$wt_ctps1
				);
			}
		}
		if ( isset( $cart_item['_wtct2'] ) && ! empty ( $cart_item['_wtct2'] ) ) {
			$wt_ctps2_label = get_post_meta( $cart_item['product_id'], 'wt_ctps2_label', true ) ;
			$label2 = explode("|",get_option('wt_ctfield2_info'));
			if(isset($label2[0]) && $label2[0]!=''){
				$wt_ctps2_label = $wt_ctps2_label!='' ? $wt_ctps2_label : $label2[0];
				$other_data[] = array(
					'name'  => $wt_ctps2_label,
					'value' => $cart_item['_wtct2'].$wt_ctps2
				);
			}
		}
		if ( isset( $cart_item['_wtdiscount'] ) && ! empty ( $cart_item['_wtdiscount'] ) ) {
			if(isset($cart_item['_wtdiscount_type']) && $cart_item['_wtdiscount_type']=='season'){
				$tdc = esc_html__('Per each user','woo-tour');
			}else{
				$tdc = esc_html__('Per each adult','woo-tour');
			}
			$other_data[] = array(
				'name'  => esc_html__('Discount','woo-tour'),
				'value' => $cart_item['_wtdiscount'].' '.$tdc
			);
		}
		return $other_data;
	}
	// step 5 add user booking info to order admin
	function add_info_to_order_item_meta($item_id, $item){
		if(isset($item->legacy_values) && isset($item->legacy_values['_date']) && !empty($item->legacy_values['_date'])){
			wc_add_order_item_meta($item_id, '_date', sanitize_text_field($item->legacy_values['_date']));
		}
		if(isset($item->legacy_values) && isset($item->legacy_values['_adult']) && !empty($item->legacy_values['_adult'])){
			wc_add_order_item_meta($item_id, '_adult', sanitize_text_field($item->legacy_values['_adult']));
		}
		if(isset($item->legacy_values) && isset($item->legacy_values['_child']) && !empty($item->legacy_values['_child'])){
			wc_add_order_item_meta($item_id, '_child', sanitize_text_field($item->legacy_values['_child']));
		}
		if(isset($item->legacy_values) && isset($item->legacy_values['_infant']) && !empty($item->legacy_values['_infant'])){
			wc_add_order_item_meta($item_id, '_infant', sanitize_text_field($item->legacy_values['_infant']));
		}
		if(isset($item->legacy_values) && isset($item->legacy_values['_wtct1']) && !empty($item->legacy_values['_wtct1'])){
			wc_add_order_item_meta($item_id, '_wtct1', sanitize_text_field($item->legacy_values['_wtct1']));
		}
		if(isset($item->legacy_values) && isset($item->legacy_values['_wtct2']) && !empty($item->legacy_values['_wtct2'])){
			wc_add_order_item_meta($item_id, '_wtct2', sanitize_text_field($item->legacy_values['_wtct2']));
		}
		if(isset($item->legacy_values) && isset($item->legacy_values['_metadate']) && !empty($item->legacy_values['_metadate'])){
			wc_add_order_item_meta($item_id, '_metadate', sanitize_text_field($item->legacy_values['_metadate']));
		}
		if(isset($item->legacy_values) && isset($item->legacy_values['_wtdiscount']) && !empty($item->legacy_values['_wtdiscount'])){
			wc_add_order_item_meta($item_id, '_wtdiscount', sanitize_text_field($item->legacy_values['_wtdiscount'].' '.esc_html__('Per each adult','woo-tour')));
		}
		if(isset($item->legacy_values) && isset($item->legacy_values['_duration']) && !empty($item->legacy_values['_duration'])){
			wc_add_order_item_meta($item_id, '_duration', sanitize_text_field($item->legacy_values['_duration']));
		}
	}
	// step 7 add imfo booking form for simple product
	function html_custom_field(){
		global $sitepress;
		$current_language = $sitepress->get_current_language();	
		$adu ="Adult: ";	
		$chil ="Children: ";	
		$Infant ="Infant: ";	
		if ($current_language == 'vi') {
			$adu ="Người lớn :";	
			$chil ="Trẻ em: ";	
			$Infant ="Sơ sinh: ";	
		}
		if ($current_language == 'en') {
			$adu ="Adult: ";	
			$chil ="Children: ";	
			$Infant ="Infant: ";	
		}
		if(exwt_get_layout_purpose(get_the_ID())=='woo'){ return;}
		global $product;	
		$type = $product->get_type();
		if($type=='variable' || $type=='external'){return;}
		$wt_main_purpose = wt_global_main_purpose();
		$wt_slayout_purpose = get_option('wt_slayout_purpose');
		$wt_show_sdate = get_option('wt_show_sdate');
		$wt_layout_purpose = get_post_meta(get_the_ID(),'wt_layout_purpose',true);
		if(($wt_main_purpose=='custom' && $wt_layout_purpose!='tour') || ($wt_main_purpose=='meta' && $wt_layout_purpose=='woo') || ($wt_main_purpose=='meta' && $wt_layout_purpose!='tour' && $wt_slayout_purpose=='woo') ){return;}
		$wt_customdate = get_post_meta( get_the_ID(), 'wt_customdate', false ) ;
		$wt_disabledate = get_post_meta( get_the_ID(), 'wt_disabledate', false ) ;
		$arr_disdate = array();
		if(is_array($wt_disabledate) && !empty($wt_disabledate)){
			$i = 0;
			foreach($wt_disabledate as $idt){
				$i ++;
				$arr_disdate[$i] = $idt;
			}
		}
		$arr_disdate = str_replace('\/', '/', json_encode($arr_disdate));
		$wt_firstday = get_option('wt_firstday','7');
		$wt_weekday = get_post_meta( get_the_ID(), 'wt_weekday', true ) ;
		$weekday = array(1,2,3,4,5,6,7);
		$arr_diff = array();
		if(is_array($wt_weekday) && !empty($wt_weekday)){
			$arr_diff = array_diff($weekday,$wt_weekday);
			if(!empty($arr_diff) && $wt_firstday == 1){
				$j = 0;
				$new_diff = array();
				foreach($arr_diff as $itd){
					if($itd == 1){
						$new_diff[$j] = 7;
					}else{
						$new_diff[$j] = $itd*1 - 1;
					}
					$j++;
				}
				$arr_diff = $new_diff;
			}
		}
		$arr_diff = str_replace('\/', '/', json_encode($arr_diff));
		$wt_expired = get_post_meta( get_the_ID(), 'wt_expired', true ) ;
		/*if($wt_expired !=''){
			$time_now =  strtotime("now");
			$wt_expired = $wt_expired - $time_now;
			$wt_expired = $wt_expired/86400;
			if($wt_expired > 0){ $wt_expired = floor($wt_expired);}else{ $wt_expired = '';}
		}*/
		if($wt_expired !=''){ $wt_expired = date_i18n('Y-m-d', $wt_expired); }
		/*--Book before--*/
		$wt_disable_book = get_post_meta( get_the_ID(), 'wt_disable_book', true ) ;
		if($wt_disable_book==''){
			$wt_disable_book = get_option('wt_disable_book');
		}
		$dis_uni = 0;
		if($wt_disable_book!='' && is_numeric($wt_disable_book) || ( $wt_disable_book!='' && is_numeric(str_replace("h","",$wt_disable_book))) ){
			$dis_uni = apply_filters( 'wt_disable_book_day', strtotime("+$wt_disable_book day") );
			$gmt_offset = get_option('gmt_offset');
			if($gmt_offset!=''){
				$dis_uni = $dis_uni + ($gmt_offset*3600);
			}
			$wt_disable_book = date_i18n('Y-m-d',$dis_uni);
		}else{
			$wt_disable_book='';
		}
		$wt_start = get_post_meta( get_the_ID(), 'wt_start', true ) ;
		if($wt_start!='' && is_numeric($wt_start)){
			if($wt_start > $dis_uni && $wt_start > time()){
				$wt_disable_book = date_i18n('Y-m-d',$wt_start);
				$dis_uni = $wt_start;
			}else if($wt_start < time()){
				//$wt_disable_book='';
			}
		}
		$arr_ctdate = '';
		$df_day = '';
		$date_dmy = array();
		$wt_customdate = array_filter($wt_customdate);
		if(is_array($wt_customdate) && !empty($wt_customdate)){
			$i=0;
			$cure_time =  strtotime("now");
			$gmt_offset = get_option('gmt_offset');
			if($gmt_offset!=''){
				$cure_time = $cure_time + ($gmt_offset*3600);
			}
			if($wt_show_sdate !='calendar'){
				if( $wt_disable_book ==''){ $wt_disable_book = 0;}
				$df_day ='';
				foreach($wt_customdate as $item){
					$i++;
					$clss = '';
					$avari = get_post_meta(get_the_ID(), date_i18n('Y_m_d', $item), true);
					if(($wt_disable_book!='' && $dis_uni > $item) || ($avari !='' && $avari < 1) || ($item < $cure_time)){
						$clss = 'wt-disble';
					}
					$date_s = date_i18n( get_option('date_format'), $item);
					$date_ft = apply_filters( 'wt_date_in_list_html', $date_s, $item );
					$arr_ctdate .= '<li class="'.$clss.'" data-value="'.date_i18n( get_option('date_format'), $item).'" data-date="'.date_i18n('Y_m_d', $item).'">'.$date_ft.'</li>';
				}
			}else{
				foreach($wt_customdate as $ict){
					if($ict!=''){
						$i ++;
						$avari = get_post_meta(get_the_ID(), date_i18n('Y_m_d', $ict), true);
						if($avari !='' && $avari < 1){$ict = 0;}
						if($ict > $cure_time && $ict >= $dis_uni){
							$arr_ctdate[$i] = $ict;
							$date_dmy[$i] = date_i18n('m-d-Y', $ict);
						}elseif(count($wt_customdate) == $i && empty($arr_ctdate)){
							$arr_ctdate[$i] = $ict - (2*2592000);
						}
					}
				}
				$arr_ctdate = str_replace('\/', '/', json_encode($arr_ctdate));
			}
		}elseif($wt_show_sdate =='calendar'){
			$arr_ctdate = array();
			$arr_ctdate = str_replace('\/', '/', json_encode($arr_ctdate));
		}
		$trsl_mtext [1]= esc_html__('January','woo-tour'); $trsl_mtext [2]= esc_html__('February','woo-tour'); $trsl_mtext [3]= esc_html__('March','woo-tour'); $trsl_mtext [4]= esc_html__('April','woo-tour'); $trsl_mtext [5]= esc_html__('May','woo-tour'); $trsl_mtext [6]= esc_html__('June','woo-tour'); $trsl_mtext [7]= esc_html__('July','woo-tour'); $trsl_mtext [8]= esc_html__('August','woo-tour'); $trsl_mtext [9]= esc_html__('September','woo-tour'); $trsl_mtext [10]= esc_html__('October','woo-tour'); $trsl_mtext [11]= esc_html__('November','woo-tour'); $trsl_mtext [12]= esc_html__('December','woo-tour');
		$trsl_mtext = str_replace('\/', '/', json_encode($trsl_mtext));
		$trsl_dtext [1]= esc_html__('Sun','woo-tour');$trsl_dtext [2]= esc_html__('Mon','woo-tour');$trsl_dtext [3]= esc_html__('Tue','woo-tour');
		$trsl_dtext [4] = esc_html__('Wed','woo-tour'); $trsl_dtext [5]= esc_html__('Thu','woo-tour');$trsl_dtext [6]= esc_html__('Fri','woo-tour');$trsl_dtext [7]= esc_html__('Sat','woo-tour');
		$trsl_dtext = str_replace('\/', '/', json_encode($trsl_dtext));
		
		$wt_date_label = get_post_meta( get_the_ID(), 'wt_date_label', true ) ;
		$wt_date_label = $wt_date_label!='' ? $wt_date_label.': ' : esc_html__('Departure: ','woo-tour');
		// register js	
		wp_enqueue_style('wt-pickadate');
		wp_enqueue_style('wt-pickadate-date');
		wp_enqueue_style('wt-pickadate-time');
		wp_enqueue_script( 'wt-pickadate' );
		wp_enqueue_script( 'wt-pickadate-date');
		wp_enqueue_script( 'wt-pickadate-time');
		wp_enqueue_script( 'wt-pickadate-legacy');
		$wt_calendar_lg = get_option('wt_calendar_lg');
		if($wt_calendar_lg!=''){
			wp_enqueue_script( 'wt-pickadate-'.$wt_calendar_lg );
		}
		echo '
		<div class="tour-info-select">
			<span class="wt-departure">' . $wt_date_label .'
				<span>';
					if($arr_ctdate!='' && $wt_show_sdate!='calendar'){
						echo '
						<input type="text" class="wt-custom-date" readonly name="wt_date" value="'.$df_day.'">
						<ul class="wt-list-date">'.$arr_ctdate.'</ul>';
					}else{
						$wt_calendar_lg = get_option('wt_calendar_lg');
						$wt_calendar_datefm = get_option('wt_calendar_datefm');
						echo '
						<input type="hidden" name="wt_datefm" value="'.esc_attr($wt_calendar_datefm).'">
						<input type="hidden" name="wt_weekday_disable" value='.$arr_diff.'>
						<input type="hidden" name="wt_langu" value='.$wt_calendar_lg.'>
						<input type="hidden" name="wt_date_disable" value="'.esc_attr($arr_disdate).'">
						<input type="hidden" name="wt_cust_date" value='.$arr_ctdate.'>
						<input type="hidden" name="wt_cust_datefm" value='.str_replace('\/', '/', json_encode($date_dmy)).'>
						<input type="hidden" name="wt_expired" value="'.$wt_expired.'">
						<input type="hidden" name="wt_firstday" value="'.$wt_firstday.'">
						<input type="hidden" name="wt_daytrsl" value="'.esc_attr(str_replace(' ', '\u0020', $trsl_dtext)).'">
						<input type="hidden" name="wt_montrsl" value="'.esc_attr(str_replace(' ', '\u0020', $trsl_mtext)).'">
						<input type="text" readonly name="wt_date">';
					}
					do_action('wt_data_booking_form',$ptype='simple');
					echo '
					<i class="fa fa-calendar wt-bticon" aria-hidden="true"></i>
					<input type="hidden" name="wt_ajax_url" value='.esc_url(admin_url( 'admin-ajax.php' )).'>
					<input type="hidden" name="wt_tourid" value='.esc_attr( get_the_ID()).'>
					<input type="hidden" name="wt_sldate" value="">
					<input type="hidden" name="wt_book_before" value="'.$wt_disable_book.'">
				</span>
			</span>';
			echo '
			<span class="wt-user-info wtsl-'.get_option( 'wt_type_qunatity' ).'">';
				$wt_adult_max = get_post_meta( get_the_ID(), 'wt_adult_max', true );
				$sl_value = '';
				$l = get_option('wt_default_adl')!='' ? get_option('wt_default_adl') : 5;
				if(is_numeric ($wt_adult_max)){$l = $wt_adult_max;}
				$wt_adult_min = get_post_meta( get_the_ID(), 'wt_adult_min', true );
				$wt_adult_min = $wt_adult_min >= 0 && $wt_adult_min !='' ? $wt_adult_min : 1;
				//$wt_adult_max = $wt_adult_max * 1;
				for($i=$wt_adult_min; $i <= $l ; $i++){
					$sl_value .= '<option value="'.$i.'">'.$i.'</option>';
				}
				$wt_adult_label = get_post_meta( get_the_ID(), 'wt_adult_label', true ) ;

				$wt_adult_label = $wt_adult_label!='' ? $wt_adult_label.': ' : esc_html__($adu,'woo-tour');
				$wt_adult_label = '<span class="lb-pric">'.$wt_adult_label.'</span>';
				echo '<span class="_adult_select">Passenger: <span class="p-price">'.$product->get_price_html().'</span>';
					echo exwt_quantity_html('wt_number_adult', $sl_value,$wt_adult_min,$wt_adult_min,$l);
				echo '</span>';
				// child field
				wt_passenger_field_html('wt_child','wt_child_max','wt_child_label','wt_def_childf','wt_default_child','wt_child_sale','wt_number_child','_child_select','','wt_child_min');
				// infant field
				wt_passenger_field_html('wt_infant','wt_infant_max','wt_infant_label','wt_def_intff','wt_default_inf','wt_infant_sale','wt_number_infant','_infant_select','','wt_infant_min');
				if(get_option('wt_ctfieldprice') == 1){
					// custom field 1
					$label1 = explode("|",get_option('wt_ctfield1_info'));
					$dfl_ct1 = isset($label1[2]) ? $label1[2] : '';
					$dfm_ct1 = isset($label1[1]) ? $label1[1] : '';
					wt_passenger_field_html('wt_ctps1','wt_ctps1_max','wt_ctps1_label',$dfl_ct1,$dfm_ct1,'wt_ctps1_sale','wt_number_ct1','_ct1_select',$label1[0],'wt_ctps1_min');
					// custom field 2
					$label2 = explode("|",get_option('wt_ctfield2_info'));
					$dfl_ct2 = isset($label2[2]) ? $label2[2] : '';
					$dfm_ct2 = isset($label2[1]) ? $label2[1] : '';
					wt_passenger_field_html('wt_ctps2','wt_ctps2_max','wt_ctps2_label',$dfl_ct2,$dfm_ct2,'wt_ctps2_sale','wt_number_ct2','_ct2_select',$label2[0],'wt_ctps2_min');
				}
				echo '
			</span>
		</div>';
	}
	function html_custom_field_for_variable(){
		$wt_main_purpose = wt_global_main_purpose();
		$wt_slayout_purpose = get_option('wt_slayout_purpose');
		$wt_layout_purpose = get_post_meta(get_the_ID(),'wt_layout_purpose',true);
		if(($wt_main_purpose=='custom' && $wt_layout_purpose!='tour') || ($wt_main_purpose=='meta' && $wt_layout_purpose=='woo') || ($wt_main_purpose=='meta' && $wt_layout_purpose!='tour' && $wt_slayout_purpose=='woo') ){
			return;
		}
		$wt_customdate = get_post_meta( get_the_ID(), 'wt_customdate', false ) ;
		$wt_disabledate = get_post_meta( get_the_ID(), 'wt_disabledate', false ) ;
		$arr_disdate = array();
		if(is_array($wt_disabledate) && !empty($wt_disabledate)){
			$i = 0;
			foreach($wt_disabledate as $idt){
				$i ++;
				$arr_disdate[$i] = $idt;
			}
		}
		$arr_disdate = str_replace('\/', '/', json_encode($arr_disdate));
		$wt_firstday = get_option('wt_firstday','7');
		$wt_weekday = get_post_meta( get_the_ID(), 'wt_weekday', true ) ;
		$weekday = array(1,2,3,4,5,6,7);
		$arr_diff = array();
		if(is_array($wt_weekday) && !empty($wt_weekday)){
			$arr_diff = array_diff($weekday,$wt_weekday);
			if(!empty($arr_diff) && $wt_firstday == 1){
				$j = 0;
				$new_diff = array();
				foreach($arr_diff as $itd){
					if($itd == 1){
						$new_diff[$j] = 7;
					}else{
						$new_diff[$j] = $itd*1 - 1;
					}
					$j++;
				}
				$arr_diff = $new_diff;
			}
		}
		$arr_diff = str_replace('\/', '/', json_encode($arr_diff));
		$wt_expired = get_post_meta( get_the_ID(), 'wt_expired', true ) ;
		if($wt_expired !=''){ $wt_expired = date_i18n('Y-m-d', $wt_expired); }
		/*--Book before--*/
		$wt_disable_book = get_post_meta( get_the_ID(), 'wt_disable_book', true ) ;
		if($wt_disable_book==''){$wt_disable_book = get_option('wt_disable_book');}
		$dis_uni = 0;
		if($wt_disable_book!='' && is_numeric($wt_disable_book) || ( $wt_disable_book!='' && is_numeric(str_replace("h","",$wt_disable_book))) ){
			$dis_uni = apply_filters( 'wt_disable_book_day', strtotime("+$wt_disable_book day") );
			$gmt_offset = get_option('gmt_offset');
			if($gmt_offset!=''){
				$dis_uni = $dis_uni + ($gmt_offset*3600);
			}
			$wt_disable_book = date_i18n('Y-m-d',$dis_uni);
		}else{
			$wt_disable_book='';
		}
		$wt_start = get_post_meta( get_the_ID(), 'wt_start', true ) ;
		if($wt_start!='' && is_numeric($wt_start)){
			if($wt_start > $dis_uni && $wt_start > time()){
				$wt_disable_book = date_i18n('Y-m-d',$wt_start);
				$dis_uni = $wt_start;
			}else if($wt_start < time()){
				//$wt_disable_book='';
			}
		}
		$arr_ctdate = '';
		$df_day = '';
		$date_dmy = array();
		$wt_show_sdate = get_option('wt_show_sdate');
		$wt_customdate = array_filter($wt_customdate);
		if(is_array($wt_customdate) && !empty($wt_customdate)){
			$i=0;
			
			$cure_time =  strtotime("now");
			$gmt_offset = get_option('gmt_offset');
			if($gmt_offset!=''){
				$cure_time = $cure_time + ($gmt_offset*3600);
			}
			if($wt_show_sdate !='calendar'){
				if( $wt_disable_book ==''){ $wt_disable_book = 0;}
				$df_day ='';
				foreach($wt_customdate as $item){
					$i++;
					$clss = '';
					$avari = get_post_meta(get_the_ID(), date_i18n('Y_m_d', $item), true);
					if(($wt_disable_book!='' && $dis_uni > $item) || ($avari !='' && $avari < 1) || ($item < $cure_time)){
						$clss = 'wt-disble';
					}
					$date_s = date_i18n( get_option('date_format'), $item);
					$date_ft = apply_filters( 'wt_date_in_list_html', $date_s, $item );
					$arr_ctdate .= '<li class="'.$clss.'" data-value="'.date_i18n( get_option('date_format'), $item).'" data-date="'.date_i18n('Y_m_d', $item).'">'.$date_ft.'</li>';
				}
			}else{
				
				foreach($wt_customdate as $ict){
					$i ++;
					$avari = get_post_meta(get_the_ID(), date_i18n('Y_m_d', $ict), true);
					if($avari !='' && $avari < 1){$ict = 0;}
					if($ict > $cure_time && $ict >= $dis_uni){
						$arr_ctdate[$i] = $ict;
						$date_dmy[$i] = date_i18n('m-d-Y', $ict);
					}elseif(count($wt_customdate) == $i && empty($arr_ctdate)){
						$arr_ctdate[$i] = $ict - (2*2592000);
					}
				}
				$arr_ctdate = str_replace('\/', '/', json_encode($arr_ctdate));
			}
		}elseif($wt_show_sdate =='calendar'){
			$arr_ctdate = array();
			$arr_ctdate = str_replace('\/', '/', json_encode($arr_ctdate));
		}
		$trsl_mtext [1]= esc_html__('January','woo-tour');
		$trsl_mtext [2]= esc_html__('February','woo-tour');
		$trsl_mtext [3]= esc_html__('March','woo-tour');
		$trsl_mtext [4]= esc_html__('April','woo-tour');
		$trsl_mtext [5]= esc_html__('May','woo-tour');
		$trsl_mtext [6]= esc_html__('June','woo-tour');
		$trsl_mtext [7]= esc_html__('July','woo-tour');
		$trsl_mtext [8]= esc_html__('August','woo-tour');
		$trsl_mtext [9]= esc_html__('September','woo-tour');
		$trsl_mtext [10]= esc_html__('October','woo-tour');
		$trsl_mtext [11]= esc_html__('November','woo-tour');
		$trsl_mtext [12]= esc_html__('December','woo-tour');
		$trsl_mtext = str_replace('\/', '/', json_encode($trsl_mtext));
		$trsl_dtext [1]= esc_html__('Sun','woo-tour');
		$trsl_dtext [2]= esc_html__('Mon','woo-tour');
		$trsl_dtext [3]= esc_html__('Tue','woo-tour');
		$trsl_dtext [4]= esc_html__('Wed','woo-tour');
		$trsl_dtext [5]= esc_html__('Thu','woo-tour');
		$trsl_dtext [6]= esc_html__('Fri','woo-tour');
		$trsl_dtext [7]= esc_html__('Sat','woo-tour');
		$trsl_dtext = str_replace('\/', '/', json_encode($trsl_dtext));
		$wt_date_label = get_post_meta( get_the_ID(), 'wt_date_label', true ) ;
		global $sitepress;
		$current_language = $sitepress->get_current_language();	
		$departurn1 ="Departure";	
		if ($current_language == 'vi') {
			$departurn1 ="Khởi hành: ";	
		}
		if ($current_language == 'en') {
			$departurn1 ="Departure: ";	
		}


		$wt_date_label = $wt_date_label!='' ? $wt_date_label.': ' : esc_html__($departurn1,'woo-tour');
		// register js
		wp_enqueue_style('wt-pickadate');
		wp_enqueue_style('wt-pickadate-date');
		wp_enqueue_style('wt-pickadate-time');
		wp_enqueue_script( 'wt-pickadate' );
		wp_enqueue_script( 'wt-pickadate-date');
		wp_enqueue_script( 'wt-pickadate-time');
		wp_enqueue_script( 'wt-pickadate-legacy');
		$wt_calendar_lg = get_option('wt_calendar_lg');
		if($wt_calendar_lg!=''){
			wp_enqueue_script( 'wt-pickadate-'.$wt_calendar_lg );
		}
		echo '
		<table class="tour-tble date-sl">
		<tbody>
		<td class="label"><label for="' . sanitize_title($wt_date_label) .'">' . $wt_date_label .'</label></td>
		<td class="value">
		<div class="tour-info-select">
			<span class="wt-departure">
				<span>';
					if($arr_ctdate!='' && $wt_show_sdate!='calendar'){
						echo '
						<input type="text" class="wt-custom-date" readonly name="wt_date" value="'.$df_day.'">
						<ul class="wt-list-date">'.$arr_ctdate.'</ul>';
					}else{
						$wt_calendar_lg = get_option('wt_calendar_lg');
						$wt_calendar_datefm = get_option('wt_calendar_datefm');
						echo '
						<input type="hidden" name="wt_datefm" value="'.esc_attr($wt_calendar_datefm).'">
						<input type="hidden" name="wt_weekday_disable" value='.$arr_diff.'>
						<input type="hidden" name="wt_langu" value='.$wt_calendar_lg.'>
						<input type="hidden" name="wt_date_disable" value='.$arr_disdate.'>
						<input type="hidden" name="wt_cust_date" value='.$arr_ctdate.'>
						<input type="hidden" name="wt_cust_datefm" value='.str_replace('\/', '/', json_encode($date_dmy)).'>
						<input type="hidden" name="wt_expired" value="'.$wt_expired.'">
						<input type="hidden" name="wt_firstday" value="'.$wt_firstday.'">
						<input type="hidden" name="wt_daytrsl" value="'.esc_attr(str_replace(' ', '\u0020', $trsl_dtext)).'">
						<input type="hidden" name="wt_montrsl" value="'.esc_attr(str_replace(' ', '\u0020', $trsl_mtext)).'">
						<input type="text" readonly name="wt_date">';
					}
					do_action('wt_data_booking_form',$ptype='variable');
					echo '
					<i class="fa fa-calendar wt-bticon" aria-hidden="true"></i>
					<input type="hidden" name="wt_ajax_url" value='.esc_url(admin_url( 'admin-ajax.php' )).'>
					<input type="hidden" name="wt_tourid" value='.esc_attr( get_the_ID()).'>
					<input type="hidden" name="wt_sldate" value="">
					<input type="hidden" name="wt_book_before" value="'.$wt_disable_book.'">
				</span>
			</span>
		</div>
		</td>
		</tbody>
		</table>
		';
	}
	// add meta display frontend order
	function display_item_order_meta( $item_id, $item, $order ) {
		$id = $item['product_id'];
		if(function_exists('wc_get_order_item_meta')){
			$_date = wc_get_order_item_meta($item_id,'_date');
			$_adult = wc_get_order_item_meta($item_id,'_adult');
			$_child = wc_get_order_item_meta($item_id,'_child');
			$_infant = wc_get_order_item_meta($item_id,'_infant');
			$_wtct1 = wc_get_order_item_meta($item_id,'_wtct1');
			$_wtct2 = wc_get_order_item_meta($item_id,'_wtct2');
			$_wtdiscount = wc_get_order_item_meta($item_id,'_wtdiscount');
			$_duration = wc_get_order_item_meta($item_id,'_duration');
		}else{
			$_date = $item->get_meta('_date');
			$_adult = $item->get_meta('_adult');
			$_child = $item->get_meta('_child');
			$_infant = $item->get_meta('_infant');
			$_wtct1 = $item->get_meta('_wtct1');
			$_wtct2 = $item->get_meta('_wtct2');
			$_wtdiscount = $item->get_meta('_wtdiscount');
			$_duration = $item->get_meta('_duration');
		}
		$output ='';
		if ( $_date !='' ) {
			$wt_date_label = get_post_meta( $id, 'wt_date_label', true ) ;
			$wt_date_label = $wt_date_label!='' ? $wt_date_label.':' : esc_html__('Departure: ','woo-tour');
			$_date = apply_filters( 'wt_email_date_html', $_date, $id,$item,$item_id, $order);
			$output .= '<p class="exwt-orif variation" style="margin: 5px 0 0 0">' . $wt_date_label .' '. $_date . '</p>';
		}
		if ( $_duration !='' ) {
			$output .=  $_duration!='' ? '<p class="exwt-orif variation">'.esc_html__('Duration:','woo-tour').'&nbsp;'.$_duration.'</p>' : '';		
		}
		global $sitepress;
		$current_language = $sitepress->get_current_language();	
		$adu ="Adult: ";	
		$chil ="Children: ";	
		$Infant ="Infant: ";	
		if ($current_language == 'vi') {
			$adu ="Người lớn :";	
			$chil ="Trẻ em: ";	
			$Infant ="Sơ sinh: ";	
		}
		if ($current_language == 'en') {
			$adu ="Adult: ";	
			$chil ="Children: ";	
			$Infant ="Infant: ";	
		}
		if ( $_adult!='' ) {
			$wt_adult_label = get_post_meta( $id, 'wt_adult_label', true ) ;
			$wt_adult_label = $wt_adult_label!='' ? $wt_adult_label.':' : esc_html__($adu,'woo-tour');
			$output .= '<p class="exwt-orif variation" style="margin: 5px 0 0 0">' . $wt_adult_label .' '. $_adult . '</p>';
	
		}
		if ( $_child!='' ) {
			$wt_child_label = get_post_meta( $id, 'wt_child_label', true ) ;
			$wt_child_label = $wt_child_label!='' ? $wt_child_label.':' : esc_html__($chil,'woo-tour');	
			$output .= '<p class="exwt-orif variation" style="margin: 5px 0 0 0">' . $wt_child_label .' '. $_child . '</p>';
	
		}
		if ( $_infant!='' ) {
			$wt_infant_label = get_post_meta( $id, 'wt_infant_label', true ) ;
			$wt_infant_label = $wt_infant_label!='' ? $wt_infant_label.':' : esc_html__($Infant,'woo-tour');
			$output .= '<p class="exwt-orif variation" style="margin: 5px 0 0 0">' . $wt_infant_label .' '. $_infant . '</p>';
		}
		if ( $_wtct1!='' ) {
			$label1 = explode("|",get_option('wt_ctfield1_info'));			
			$wt_ctps1_label = get_post_meta( $id, 'wt_ctps1_label', true ) ;
			if(isset($label1[0]) && $label1[0]!=''){
				$wt_ctps1_label = $wt_ctps1_label!='' ? $wt_ctps1_label.':' : $label1[0].':';
				$output .= '<p class="exwt-orif variation" style="margin: 5px 0 0 0">' . $wt_ctps1_label .' '. $_wtct1 . '</p>';
			}
		}
		if ( $_wtct2!='' ) {
			$label2 = explode("|",get_option('wt_ctfield2_info'));			
			$wt_ctps2_label = get_post_meta( $id, 'wt_ctps2_label', true ) ;
			if(isset($label2[0]) && $label2[0]!=''){
				$wt_ctps2_label = $wt_ctps2_label!='' ? $wt_ctps2_label.':' : $label2[0].':';
				$output .= '<p class="exwt-orif variation" style="margin: 5px 0 0 0">' . $wt_ctps2_label .' '. $_wtct2 . '</p>';
			}
		}
		if ( $_wtdiscount!='' ) {
			$output .= '<p class="exwt-orif variation" style="margin: 5px 0 0 0">' . esc_html__('Discount: ','woo-tour') .' '. $_wtdiscount . '</p>';
		}
		echo $output;
	}
	// remove user data booking
	function remove_user_data_booking_from_cart($cart_item_key){
		global $woocommerce;
		// Get cart
		$cart = $woocommerce->cart->get_cart();
		// For each item in cart, if item is upsell of deleted product, delete it
		foreach( $cart as $key => $values){
			if ( $values['wdm_user_custom_data_value'] == $cart_item_key )
				unset( $woocommerce->cart->cart_contents[ $key ] );
		}
	}
	// update total
	function update_live_total_price_html() {
		if(get_option('wt_live_total')=='yes'){
			wootour_template_plugin('live-total');
		}
	}
	// disable days
	function variation_disable_days(){
		global $product;
		$variations = $product->get_available_variations();
		$arr_key = $arr_dd = array();
		if(is_array($variations) && !empty($variations)){
		    foreach ($variations as $value) {
		    	$variation_id =  $value['variation_id'];
		    	if(is_array($value['attributes']) && !empty($value['attributes'])){
		    		$name = '';
			    	foreach ($value['attributes'] as $key => $value_att) {
			    		$arr_key[$value_att] = $key;
			    		$name = $name !='' ? $name.'|'.$value_att : $value_att;
			    	}
			    }
		    	//$name = isset($value['attributes']['attribute_select-time']) ? $value['attributes']['attribute_select-time'] : '';
		    	$disable_day = get_post_meta( $variation_id, '_dis_weekdays', true );
		    	if(is_array($disable_day) && !empty($disable_day)){
		    		$disable_day = array_filter($disable_day);
		    		//echo '<pre>'; print_r(array_filter($disable_day));echo '</pre>';
			    	$arr_dd[$name] = !empty($disable_day) ? $disable_day : '';
			    }
		    }
		}
		if(!empty($arr_dd)){
			echo '<input type="hidden" name="exwt_dvardays" value="'.esc_attr(str_replace('\/', '/', json_encode($arr_dd))).'"/>';
		}
		if(!empty($arr_key)){
			echo '<input type="hidden" name="exwt_keydays" value="'.esc_attr(str_replace('\/', '/', json_encode($arr_key))).'"/>';
		}
	}
}
$WooTour_Booking = new WooTour_Booking();
/**
 * New way to Update price
 */
add_filter( 'woocommerce_add_cart_item',  'exwt_update_total_price_item', 100, 1 );
function exwt_update_total_price_item($value){
	$price_adu = $price_child = $price_inf = $price_ct1 = $price_ct2 = 0;
	$ud_qty = $dtb_unix = '';
	$ck_t = 0;
	if(isset($value['_metadate']) && $value['_metadate']!=''){
		$dtb = explode("_",$value['_metadate']);
		$dtb = $dtb[0].'-'.$dtb[1].'-'.$dtb[2];
		$dtb_unix = strtotime($dtb);
	}
	$season_price = $dtb_unix!= '' ? exwt_get_price_season($value['product_id'],$dtb_unix,'',$value['variation_id']) : '';
	if(isset($value['_child']) && $value['_child']!=''){
		$ck_t = 1;
		$wt_child = wt_get_price($value['product_id'], 'wt_child',$season_price,true);
		if(isset($value['variation_id']) && is_numeric($value['variation_id']) && $value['variation_id'] > 0){
			$wt_child = wt_get_price($value['variation_id'], '_child_price',$season_price,true);
		}
		if($wt_child !='OFF' && $wt_child > 0 && $value['_child'] > 0){
			$price_child = $wt_child * $value['_child'] ;
		}
	}
	if(isset($value['_infant']) && $value['_infant']!=''){
		$ck_t = 1;
		$wt_infant = wt_get_price($value['product_id'], 'wt_infant',$season_price,true);
		if(isset($value['variation_id']) && is_numeric($value['variation_id']) && $value['variation_id'] > 0){
			$wt_infant = wt_get_price($value['variation_id'], '_infant_price',$season_price,true);
		}
		if($wt_infant !='OFF' && $wt_infant > 0 && $value['_infant'] > 0){
			$price_inf = $wt_infant * $value['_infant'] ;
		}
	}
	if(isset($value['_wtct1']) && $value['_wtct1']!=''){
		$ck_t = 1;
		$wt_ctps1 = wt_get_price($value['product_id'], 'wt_ctps1',$season_price,true);
		if(isset($value['variation_id']) && is_numeric($value['variation_id']) && $value['variation_id'] > 0){
			$wt_ctps1 = wt_get_price($value['variation_id'], '_ctfield1_price',$season_price,true);
		}
		if($wt_ctps1 !='OFF' && $wt_ctps1 >0){
			$price_ct1 = $wt_ctps1 * $value['_wtct1'] ;
		}
	}
	if(isset($value['_wtct2']) && $value['_wtct2']!=''){
		$ck_t = 1;
		$wt_ctps2 = wt_get_price($value['product_id'], 'wt_ctps2',$season_price,true);
		if(isset($value['variation_id']) && is_numeric($value['variation_id']) && $value['variation_id'] > 0){
			$wt_ctps2 = wt_get_price($value['variation_id'], '_ctfield2_price',$season_price,true);
		}
		if($wt_ctps2 !='OFF' && $wt_ctps2 >0 ){
			$price_ct2 = $wt_ctps2 * $value['_wtct2'] ;
		}
	}
	if( $ck_t == 1 && (!isset($value['_adult']) || $value['_adult']=='') ){
		$value['_adult'] = 0;
	}
	// check fixed price
	$wt_fixed_price = get_post_meta( $value['product_id'], 'wt_fixed_price', true );
	if($wt_fixed_price=='yes'){
		// do nothing with fixed price
		if(is_array($season_price) && !empty($season_price)){
			$pricefix =  $season_price['wt_p_adult']!='' && is_numeric($season_price['wt_p_adult']) ? $season_price['wt_p_adult'] : ''; 
			if($pricefix !='' && $pricefix >= 0){$value['data']->set_price($pricefix);}
		}
	} else if(isset($value['_adult']) && ($value['_adult']!='' || ($value['_adult']=='0'))){
		$pricefix =  $value['data']->get_price('edit');
		if(is_array($season_price) && !empty($season_price)){
			$pricefix =  $season_price['wt_p_adult']!='' && is_numeric($season_price['wt_p_adult']) ? $season_price['wt_p_adult'] : ''; 
		}
		$price_adu = $pricefix *$value['_adult'];
		$price_adu = apply_filters('exwt_ttprice_adult',$price_adu,$pricefix,$value);
		// Product addon flat fee		
		if(isset($value['addons'])){
			$apply_price_all = apply_filters( 'exwt_apply_addon_price_all', 0 );
			foreach($value['addons'] as $it_ad){
				if($it_ad['price_type'] =='flat_fee'){
					$dup_rm = $it_ad['price']* ($value['_adult'] - 1 );
					$price_adu = $price_adu - $dup_rm;
				}else if($apply_price_all == '1'){
					$nbif = isset($value['_infant']) && $value['_infant']!='' ? $value['_infant'] : 0;
					$price_inf = $price_inf + $price_inf*$nbif;
					$nbchild = isset($value['_child']) && $value['_child']!='' ? $value['_child'] : 0;
					$price_child = $price_child + $price_child*$nbif;
					$nbct1 = isset($value['_wtct1']) && $value['_wtct1']!='' ? $value['_wtct1'] : 0;
					$price_ct1 = $price_ct1 + $price_ct1*$nbct1;
					$nbct2 = isset($value['_wtct2']) && $value['_wtct2']!='' ? $value['_wtct2'] : 0;
					$price_ct2 = $price_ct2 + $price_ct2*$nbct2;
					
				}
			}
		}
		$total_price = $price_adu + $price_child + $price_inf + $price_ct1 + $price_ct2;

		$wt_discount = get_post_meta($value['product_id'],'wt_discount',false);
		$wt_disc_bo = get_post_meta($value['product_id'],'wt_disc_bo',true);
		do_action('wt_all_cart_data',$value);
		if(!empty($wt_discount) && ( !isset($value['deposit_value']) || $value['deposit_value']=='' )){
			$cure_time =  strtotime("now");
			$gmt_offset = get_option('gmt_offset');
			if($gmt_offset!=''){
				$cure_time = $cure_time + ($gmt_offset*3600);
			}
			usort($wt_discount, function($a, $b) { // anonymous function
				return (int)$a['wt_disc_number'] - (int)$b['wt_disc_number'];
			});
			$wt_discount = array_reverse($wt_discount);
			foreach ($wt_discount as $item){
				$enddc = $item['wt_disc_end']!='' ? $item['wt_disc_end'] + 86399 : '';
				if($wt_disc_bo != 'season'){
					if(($item['wt_disc_start']=='' && $enddc=='') || ($item['wt_disc_start']!='' && $enddc=='' && $cure_time > $item['wt_disc_start']) || ($item['wt_disc_start']=='' && $enddc!='' && $cure_time < $enddc) || ($item['wt_disc_start']!='' && $enddc!='' && $cure_time < $enddc && $item['wt_disc_start'] < $cure_time) ){
						if($value['_adult'] >= $item['wt_disc_number']){
							if($item['wt_disc_type']=='percent' && $item['wt_disc_am'] > 0){
								$disc_price = $pricefix - ($pricefix * $item['wt_disc_am']/100);
							}elseif($item['wt_disc_am'] > 0){
								$disc_price = $pricefix - $item['wt_disc_am'];
							}else{break;}
							$pricefix = $disc_price;
							$price_adu = apply_filters( 'wt_wmc_price_adult', $pricefix * $value['_adult'], $value );
							$total_price = $price_adu + $price_child + $price_inf + $price_ct1 + $price_ct2;
							break;
						}
					}
				}elseif(isset($value['_metadate']) && $value['_metadate']!=''){
					if(($item['wt_disc_start']=='' && $enddc=='') || ($item['wt_disc_start']!='' && $enddc=='' && $dtb_unix >= $item['wt_disc_start']) || ($item['wt_disc_start']=='' && $enddc!='' && $dtb_unix < $enddc) || ($item['wt_disc_start']!='' && $enddc!='' && $dtb_unix < $enddc && $item['wt_disc_start'] <= $dtb_unix) ){
						 	if($item['wt_disc_type']=='percent' && $item['wt_disc_am'] > 0){
								$disc_price = $pricefix - ($pricefix * $item['wt_disc_am']/100);
								$price_child = $price_child > 0 ? $price_child - ($price_child * $item['wt_disc_am']/100) : 0;
								$price_child = $price_child < 0 ? 0 : $price_child; 
								$price_inf = $price_inf > 0 ? $price_inf - ($price_inf * $item['wt_disc_am']/100) : 0;
								$price_inf = $price_inf < 0 ? 0 : $price_inf; 
								$price_ct1 = $price_ct1 > 0 ? $price_ct1 - ($price_ct1 * $item['wt_disc_am']/100) : 0;
								$price_ct1 = $price_ct1 < 0 ? 0 : $price_ct1; 
								$price_ct2 = $price_ct2 > 0 ? $price_ct2 - ($price_ct2 * $item['wt_disc_am']/100) : 0;
								$price_ct2 = $price_ct2 < 0 ? 0 : $price_ct2; 
							}elseif($item['wt_disc_am'] > 0){
								$disc_price = $pricefix - $item['wt_disc_am'];
								
								$price_child = $price_child > 0 ? $price_child - ($item['wt_disc_am']*$value['_child']) : 0;
								$price_child = $price_child < 0 ? 0 : $price_child; 
								$price_inf = $price_inf > 0 ? $price_inf - ($item['wt_disc_am']*$value['_infant']) : 0;
								$price_inf = $price_inf < 0 ? 0 : $price_inf; 
								$price_ct1 = $price_ct1 > 0 ? $price_ct1 - ($item['wt_disc_am']*$value['_wtct1']) : 0;
								$price_ct1 = $price_ct1 < 0 ? 0 : $price_ct1; 
								$price_ct2 = $price_ct2 > 0 ? $price_ct2 - ($item['wt_disc_am']*$value['_wtct2']) : 0;
								$price_ct2 = $price_ct2 < 0 ? 0 : $price_ct2; 
							}else{break;}
							$pricefix = $disc_price;
							$price_adu = apply_filters( 'wt_wmc_price_adult', $pricefix * $value['_adult'], $value );;
							$total_price = $price_adu + $price_child + $price_inf + $price_ct1 + $price_ct2;
							break;
					}
					
				}
			}
		}
		$value['data']->set_price( $total_price );
		
	}
	return $value;
}
add_filter( 'woocommerce_get_cart_item_from_session', 'exwt_update_total_from_session', 21, 2 );
function exwt_update_total_from_session($cart_item, $values){
	$cart_item = exwt_update_total_price_item($cart_item);
	return $cart_item;
}

//add_action( 'woocommerce_before_checkout_process','exwt_verify_tour_date_onck');
function exwt__verify_tour_date_on_ck(){
	$disable_book = get_option('wt_disable_book');
	$cure_time =  strtotime("now");
	$gmt_offset = get_option('gmt_offset');
	if($gmt_offset!=''){
		$cure_time = $cure_time + ($gmt_offset*3600);
	}
	$msg_it ='';
	foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
		if(isset($values['_date']) && isset($values['_adult']) && isset($values['_metadate'])){
			$id_cr = $values['product_id'];
			$wt_disable_book = get_post_meta( $id_cr, 'wt_disable_book', true ) ;
			$wt_start = get_post_meta( $id_cr, 'wt_start', true ) ;
			if($wt_disable_book==''){
				$wt_disable_book = $disable_book!='' ? $disable_book : 0;
			}
			$dis_uni = apply_filters( 'wt_disable_book_day', strtotime("+$wt_disable_book day") );
			$gmt_offset = get_option('gmt_offset');
			if($gmt_offset!=''){
				$dis_uni = $dis_uni + ($gmt_offset*3600);
			}
			$wt_disable_book = date_i18n('Y-m-d',$dis_uni);
			if($wt_start!='' && is_numeric($wt_start)){
				if($wt_start > $dis_uni && $wt_start > time()){
					$dis_uni = $wt_start;
				}
			}
			$dtb = explode("_",$value['_metadate']);
			$dtb = $dtb[0].'-'.$dtb[1].'-'.$dtb[2];
			$dtb_unix = strtotime($dtb);
			if($dtb_unix < $cure_time || $dtb_unix < $dis_uni){
				$msg_it .= sprintf( esc_html__('Sorry the date "%s" of tour:"%s" has passed or not available','woocommerce-food' ),$values['_date'],get_the_title($id_cr)).'</br>';
			}
		}
	}
	if($msg_it!=''){
		wc_add_notice(  $msg_it,'error');
		return;
	}
}