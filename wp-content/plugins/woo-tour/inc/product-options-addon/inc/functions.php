<?php
// woo hook
include plugin_dir_path(__FILE__).'woo-hook.php';
function exwo_text_domain(){
	$textdomain = 'product-options-addon';
	if(class_exists('EX_WOOFood')){
		$textdomain = 'woocommerce-food';
	}
	return $textdomain;
}
function exwo_convert_number_decimal_comma($number,$cv = false){
	if($number==''){ return;}
	if(get_option( 'woocommerce_price_decimal_sep' )==','){
		$number = floatval(str_replace(',', '.', $number));
	}else if(get_option( 'woocommerce_price_decimal_sep' )=='.' && strpos($number, ',') !== false){
		$number = floatval(str_replace(',', '.', str_replace('.', '', $number)));
	}
	if(isset($cv) && $cv==true){
		$number = exwo_price_display($number);
	}
	return apply_filters('exwo_option_price', $number);

}
function exwo_price_display($number){
	if($number==''){ return;}
	if(function_exists('wmc_get_price')){
		$number =  wmc_get_price($number);
	}
	return apply_filters('exwo_option_dsprice',$number);
}