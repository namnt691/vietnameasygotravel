<?php
/**
 * Single variation display
 *
 * This is a javascript-based template for single variations (see https://codex.wordpress.org/Javascript_Reference/wp.template).
 * The values will be dynamically replaced after selecting attributes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.5.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $product;
$wt_main_purpose = wt_global_main_purpose();
$wt_slayout_purpose = get_option('wt_slayout_purpose');
if ($product && method_exists($product,'get_id')){
	$wt_layout_purpose = get_post_meta($product->get_id(),'wt_layout_purpose',true);
} else {
	$wt_layout_purpose = get_post_meta(get_the_ID(),'wt_layout_purpose',true);
}
if(($wt_main_purpose=='custom' && $wt_layout_purpose!='tour') || ($wt_main_purpose=='meta' && $wt_layout_purpose=='woo') || ($wt_main_purpose=='meta' && $wt_layout_purpose!='tour' && $wt_slayout_purpose=='woo') ){
	?>
    <script type="text/template" id="tmpl-variation-template">
		<div class="woocommerce-variation-description">
			{{{ data.variation.variation_description }}}
		</div>
	
		<div class="woocommerce-variation-price">
			{{{ data.variation.price_html }}}
		</div>
	
		<div class="woocommerce-variation-availability">
			{{{ data.variation.availability_html }}}
		</div>
	</script>
	<script type="text/template" id="tmpl-unavailable-variation-template">
		<p><?php _e( 'Sorry, this product is unavailable. Please choose a different combination.', 'woocommerce' ); ?></p>
	</script>

    <?php
}else{
	?>
	<script type="text/template" id="tmpl-variation-template">
		<div class="woocommerce-variation-description">
			{{{ data.variation.variation_description }}}
		</div>
		<table class="tour-tble">
			<tbody>
				<tr>
					<td>
						<div class="woocommerce---price adult-price">
							<span class="lb-pric">{{{ data.variation._adult_label }}}</span>
							<span class="p-price">{{{ data.variation._adult_price }}}</span>
						</div>
					</td>
					<td>{{{ data.variation._adult_select }}}</td>
				</tr>
			</tbody>	
		</table>
		<input type="hidden" name="wt_variable_id" value="{{{ data.variation.variation_id }}}">	
		<div class="woocommerce-variation-availability">
			{{{ data.variation.availability_html }}}
		</div>
		
		{{{ data.variation._child_price }}}
						
		{{{ data.variation._infant_price }}}
		
		{{{ data.variation._ct1_price }}}
		
		{{{ data.variation._ct2_price }}}
	</script>
	<script type="text/template" id="tmpl-unavailable-variation-template">
		<p><?php _e( 'Sorry, this product is unavailable. Please choose a different combination.', 'woocommerce' ); ?></p>
	</script>
<?php
}