<?php
// Add to cart form
function exwt_add_to_cart_form_shortcode( $atts ) {
		if ( empty( $atts ) ) {return '';}
		if ( ! isset( $atts['id'] ) && ! isset( $atts['sku'] ) ) {return '';}
		$args = array(
			'posts_per_page'      => 1,
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'no_found_rows'       => 1,
		);
		if ( isset( $atts['sku'] ) ) {
			$args['meta_query'][] = array(
				'key'     => '_sku',
				'value'   => sanitize_text_field( $atts['sku'] ),
				'compare' => '=',
			);
			$args['post_type'] = array( 'product', 'product_variation' );
		}
		if ( isset( $atts['id'] ) ) {
			$args['p'] = absint( $atts['id'] );
		}
		$single_product = new WP_Query( $args );
		$preselected_id = '0';
		global $exwt_form;
		$exwt_form = true;
		// Check if sku is a variation.
		if ( isset( $atts['sku'] ) && $single_product->have_posts() && 'product_variation' === $single_product->post->post_type ) {
			$variation = new WC_Product_Variation( $single_product->post->ID );
			$attributes = $variation->get_attributes();
			// Set preselected id to be used by JS to provide context.
			$preselected_id = $single_product->post->ID;
			// Get the parent product object.
			$args = array(
				'posts_per_page'      => 1,
				'post_type'           => 'product',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
				'no_found_rows'       => 1,
				'p'                   => $single_product->post->post_parent,
			);
			$single_product = new WP_Query( $args );
			?>
			<script type="text/javascript">
				jQuery( document ).ready( function( $ ) {
					var $variations_form = $( '[data-product-page-preselected-id="<?php echo esc_attr( $preselected_id ); ?>"]' ).find( 'form.variations_form' );
					<?php foreach ( $attributes as $attr => $value ) { ?>
						$variations_form.find( 'select[name="<?php echo esc_attr( $attr ); ?>"]' ).val( '<?php echo esc_js( $value ); ?>' );
					<?php } ?>
				});
			</script>
		<?php
		}
		// For "is_single" to always make load comments_template() for reviews.
		$single_product->is_single = true;
		ob_start();
		global $wp_query;

		$previous_wp_query = $wp_query;
		$wp_query          = $single_product;
		wp_enqueue_script( 'wc-single-product' );

		while ( $single_product->have_posts() ) {
			$single_product->the_post()
			?>
			<div class="single-product" data-product-page-preselected-id="<?php echo esc_attr( $preselected_id ); ?>">
				<?php woocommerce_template_single_add_to_cart(); ?>
			</div>
			<?php
		}
		$wp_query = $previous_wp_query;
		wp_reset_postdata();
		$notice = '';
		if(isset($_GET['atc']) && $_GET['atc'] =='yes'){
			$notice = wc_print_notices( true);
			wc_clear_notices();
		}
		return '<div class="exwt-booking-form woocommerce">' .$notice. ob_get_clean() . '</div>';
}
add_shortcode( 'exwt_cart_form', 'exwt_add_to_cart_form_shortcode' );
add_filter( 'woocommerce_add_to_cart_form_action', 'exwf_red_after_atc_ctform',99 );
function exwf_red_after_atc_ctform($url){
	global $exwt_form;
	if($exwt_form == true){
		return '?atc=yes';
	}
	$exwt_form = false;
	return $url;
}
add_action( 'after_setup_theme', 'exwt_booking_form_reg_vc' );
function exwt_booking_form_reg_vc(){
	if(function_exists('vc_map')){
	vc_map( array(
	   "name" => esc_html__("WooTours - Booking form", "woo-tour"),
	   "base" => "exwt_cart_form",
	   "class" => "",
	   "icon" => "",
	   "controls" => "full",
	   "category" => esc_html__('Wootours','woo-tour'),
	   "params" => array(
			array(
				"admin_label" => true,
				"type" => "textfield",
				"heading" => esc_html__("ID", "woo-tour"),
				"param_name" => "id",
				"value" => "",
				"description" => esc_html__("Enter ID of product", "woo-tour"),
			),
	   )
	));
	}
}