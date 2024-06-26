<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see     http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

// Ensure visibility
if ( ! $product || ! $product->is_visible() ) {
	return;
}

// Event color support
$wt_eventcolor = get_post_meta( get_the_ID(), 'wt_eventcolor', true );
?>
<li <?php post_class('product'); ?>>

	<?php
	if($wt_eventcolor!=""){?>
		<style type="text/css" scoped>.woocommerce ul.products li.product.post-<?php echo get_the_ID();?> a.button { background-color:<?php echo $wt_eventcolor; ?>}</style>
        <?php
    }
    $WooTour_Hook = new WooTour_Hook();
    $WooTour_Hook->woocommerce_shopitem_ev_meta();
	/**
	 * woocommerce_before_shop_loop_item hook.
	 *
	 * @hooked woocommerce_template_loop_product_link_open - 10
	 */
	//do_action( 'woocommerce_before_shop_loop_item' );
	woocommerce_template_loop_product_link_open();
	/**
	 * woocommerce_before_shop_loop_item_title hook.
	 *
	 * @hooked woocommerce_show_product_loop_sale_flash - 10
	 * @hooked woocommerce_template_loop_product_thumbnail - 10
	 */
	woocommerce_show_product_loop_sale_flash();
	woocommerce_template_loop_product_thumbnail();
	//do_action( 'woocommerce_before_shop_loop_item_title' );
	woocommerce_template_loop_product_title();
	/**
	 * woocommerce_shop_loop_item_title hook.
	 *
	 * @hooked woocommerce_template_loop_product_title - 10
	 */
	//do_action( 'woocommerce_shop_loop_item_title' );

	/**
	 * woocommerce_after_shop_loop_item_title hook.
	 *
	 * @hooked woocommerce_template_loop_rating - 5
	 * @hooked woocommerce_template_loop_price - 10
	 */
	woocommerce_template_loop_rating();
	$WooTour_Hook->woocommerce_shopitem_ev_more_meta();
	woocommerce_template_loop_price();
	//do_action( 'woocommerce_after_shop_loop_item_title' );

	/**
	 * woocommerce_after_shop_loop_item hook.
	 *
	 * @hooked woocommerce_template_loop_product_link_close - 5
	 * @hooked woocommerce_template_loop_add_to_cart - 10
	 */
	woocommerce_template_loop_product_link_close();
	woocommerce_template_loop_add_to_cart();
	//do_action( 'woocommerce_after_shop_loop_item' );
	?>

</li>
