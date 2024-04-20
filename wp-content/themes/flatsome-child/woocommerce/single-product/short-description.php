<?php
/**
 * Single product short description
 *
 * @author  Automattic
 * @package WooCommerce/Templates
 * @version 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post;

$short_description = apply_filters( 'woocommerce_short_description', $post->post_excerpt );

if ( ! $short_description ) {
	return;
}
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

$classes = array();
if($product->is_on_sale()) $classes[] = 'price-on-sale';
if(!$product->is_in_stock()) $classes[] = 'price-not-in-stock';
?>
<div class="product-short-description">
	<?php echo $short_description; // WPCS: XSS ok. ?>
</div>



<div class="price-wrapper">
	<p class="price product-page-price <?php echo implode(' ', $classes); ?>">
  <?php echo $product->get_price_html(); ?></p>
</div>
