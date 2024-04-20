<?php
function exwt_search_available($f_value, $array,$find,$variation=false) {
	if(isset($find) && is_numeric($find)){
		foreach ($array as $key => $val) {
			if($val['wt_p_start'] <= $find && $val['wt_p_end'] >= $find){
				if(!isset($val['wt_p_variation'])){ $val['wt_p_variation'] = '';}
				if((isset($variation) && $variation== $val['wt_p_variation']) || $val['wt_p_variation']==''){
					return $array[$key];
				}
			}	
		}
		return null;
	}
	foreach ($array as $key => $val) {
		if ($val['wt_p_end'] != '' && $val['wt_p_end'] <= $f_value) {
           unset($array[$key]);
       	}
	}
   	return $array;
}
function exwt_get_price_season($id,$find, $product=false,$variation=false){
	$season_price = get_post_meta( $id, 'wt_p_season', false );
	if(!is_array($season_price) || empty($season_price)){
		return;
	}
	usort($season_price, function($a, $b) { // anonymous function
		return $a['wt_p_start'] - $b['wt_p_start'];
	});
	$cure_time =  strtotime("now");
	$gmt_offset = get_option('gmt_offset');
	if($gmt_offset!=''){
		$cure_time = $cure_time + ($gmt_offset*3600);
	}
	if(isset($find) && is_numeric($find)){
		$cure_time = $find;
	}
	$season_price = exwt_search_available($cure_time, $season_price,$find,$variation);
	if (class_exists('WOOCS')) {
	    global $WOOCS;
	    if ($WOOCS->is_multiple_allowed) {
	        $season_price = $WOOCS->woocs_exchange_value(floatval($season_price));
	    }
	}
	return $season_price;
}
// change price html
add_filter( 'woocommerce_get_price_html', 'exwt_ss_change_price_html', 100, 2 );
function exwt_ss_change_price_html( $price, $product ){
	$p_season = exwt_get_price_season($product->get_id(),'');
	if(is_array($p_season) && !empty($p_season)){
		global $_hide_text;
		if(is_singular('product') && $_hide_text!='1'){
			return '<span class="tbss-viewprice">'.esc_html__('View price','woo-tour').'</span>';
		}else{
			return;
		}
	}
    return $price;
}
// price table
add_filter( 'woocommerce_product_tabs', 'exwt_ss_table_price_tab' );
function exwt_ss_table_price_tab( $tabs ) {
	$p_season = exwt_get_price_season(get_the_ID(),'');
	if($p_season=='' || empty($p_season)){
		return $tabs;
	}
	// Adds the new tab
	$tabs['pricetb_tab'] = array(
		'title' 	=> esc_html__( 'Price table', 'woo-tour' ),
		'priority' 	=> 11,
		'callback' 	=> 'exwt_ss_table_price_tab_content'
	);
	return $tabs;
}
function exwt_ss_table_price_tab_content() {
	wootour_template_plugin('price-table');
}
