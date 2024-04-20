<?php
// admin functions
include 'inc/metadata.php';
include 'inc/global-options.php';

add_action( 'admin_enqueue_scripts', 'exwooop_admin_scripts' );
function exwooop_admin_scripts(){
	$js_params = array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) );
	wp_localize_script( 'jquery', 'exwoofood_ajax', $js_params  );
	wp_enqueue_style('exwoo-options', EX_WOO_OPTION_PATH . 'admin/css/style.css','','1.6');
	wp_enqueue_script('exwoo-options', EX_WOO_OPTION_PATH . 'admin/js/admin.min.js', array( 'jquery' ),'1.6' );
}

add_action( 'init', 'exwo_update_data_options_by_ids' );
if(!function_exists('exwo_update_data_options_by_ids')){
	function exwo_update_data_options_by_ids() {
		if ( is_user_logged_in() && current_user_can( 'manage_options' )){
			$update_exwo = get_option('exwo_update_ids');
			if( $update_exwo != 'yes') {
				$my_posts = get_posts( array('post_type' => 'exwo_glboptions', 'numberposts' => -1 ) );
				foreach ( $my_posts as $post ):
					$ids_arr = get_post_meta($post->ID,'exwo_product_ids_arr', false );
					$product_ids = get_post_meta($post->ID,'exwo_product_ids', true );
					if(empty($ids_arr) && $product_ids!=''){
						$arr_ids = explode(",",$product_ids);
						foreach ($arr_ids as $key => $item) {
							add_post_meta($post->ID,'exwo_product_ids_arr',str_replace(' ', '',$item));
						}
					}
				endforeach;
				update_option('exwo_update_ids','yes');
			}
		}
	}
}

add_filter( 'woocommerce_product_import_process_item_data', 'exwo_unserialize_meta_in_import' );
function exwo_unserialize_meta_in_import( $data ) {
	$unserialize_with_key = array( 'exwo_options');
	if ( isset( $data['meta_data'] ) ) {
		foreach ( $data['meta_data'] as $index => $meta ) {
			if (in_array( $meta['key'], $unserialize_with_key)) {
				if ( $meta['value']!='' ) {
					$data['meta_data'][ $index ]['value'] = unserialize($data['meta_data'][ $index ]['value']);
				}
			}
		}
	}
	return $data;
}
add_filter('woocommerce_product_export_meta_value',  'exwo_woo_handle_export', 10, 4); 
function exwo_woo_handle_export($value, $meta, $product, $row){
    if ($meta->key == 'exwo_options') {
        return serialize($value);
    }
	return $value; 
}