<?php

/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see       https://docs.woocommerce.com/document/template-structure/
 * @package   WooCommerce/Templates
 * @version     3.9.0
 */



?>


<h3 style='color: var(--s-3-p-4, #bf1b21); font-size: 24px; font-style: normal; font-weight: 900; line-height: normal;'>
	Sản phẩm liên quan
</h3>
<div class="row">

	<?php
	global $product;

	if (!is_a($product, 'WC_Product')) {
		$product = wc_get_product(get_the_id());
	}
	// get the custom post type's taxonomy terms
	$custom_taxterms = wp_get_object_terms($product->ID, 'product_cat', array('fields' => 'ids'));
	// arguments

	$taxonomy   = 'product_cat'; // HERE define the targeted product attribute taxonomy

	$term_slugs = wp_get_post_terms(get_the_id(), $taxonomy, ['fields' => 'slugs']); // Get terms for the product
	$args = apply_filters('woocommerce_related_products_args', array(
		'post_type'            => 'product',
		'ignore_sticky_posts'  => 1,
		'posts_per_page'       => 8,
		'post__not_in'         => array(get_the_id()),
		'tax_query'            => array(array(
			'taxonomy' => $taxonomy,
			'field'    => 'slug',
			'terms'    => $term_slugs,
		)),
		'fields'  => 'ids',
		'orderby' => 'rand',
	));
	$related_items = new WP_Query($args);
	// loop over query
	if ($related_items->have_posts()) :
		while ($related_items->have_posts()) : $related_items->the_post();
	?>
			<div class="col  medium-3 small-12 large-3 ">
				<div class="pro-item">
					<?php
					$price = get_post_meta(get_the_ID(), '_regular_price', true);
					$saleprice = get_post_meta(get_the_ID(), '_sale_price', true);
					echo GetSale($saleprice, $price);
					?>
					<div class="pro-thumb">
						<a href="<?php echo get_the_permalink(); ?>" title="<?php echo get_the_title(); ?>">
							<img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large') ?>" alt="<?php echo get_the_title(); ?>" />
						</a>
					</div>
					<div class="pro-content">
						<div class="pro-title">
							<a href="<?php echo get_the_permalink(); ?>" title="<?php echo get_the_title(); ?>">
								<?php echo get_the_title(); ?>
							</a>
						</div>
						<div class="pro-price-gr">
							<div class="pro-price">
								<?php echo GetPrice($saleprice, $price); ?>
							</div>
						</div>
						<div class="box-rating_star">
							<span class="box-rating_star_on"><i class="fa fa-star"></i></span>
							<span class="box-rating_star_on"><i class="fa fa-star"></i></span>
							<span class="box-rating_star_on"><i class="fa fa-star"></i></span>
							<span class="box-rating_star_on"><i class="fa fa-star"></i></span>
							<span class="box-rating_star_on"><i class="fa fa-star"></i></span>
						</div>
						<div class="pro-readmore">
							<a href="<?php echo get_the_permalink(); ?>" title="<?php echo get_the_title(); ?>">
								Mua ngay
							</a>
						</div>
					</div>
				</div>
			</div>


	<?php
		endwhile;
	endif;
	// Reset Post Data
	wp_reset_postdata();
	?>
</div>