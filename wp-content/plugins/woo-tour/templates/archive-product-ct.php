<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
get_header();
$wt_sidebar = get_option('wt_sidebar', 'right');
$wt_shop_view = get_option('wt_shop_view');
?>
<style type="text/css">
	.wt-grid-shortcode.wt-grid-column-3 .item-post-n:nth-child(3n+1) {
		clear: left;
	}

	.wt-grid-shortcode.wt-grid-column-4 .item-post-n:nth-child(4n+1) {
		clear: left;
	}

	.wt-listing #wtmain-content h3 a {
		font-size: inherit;
	}

	.wt-listing #wtmain-content a.exwt-btn {
		color: #fff;
	}

	.wt-listing #wtmain-content .shop-we-more-meta span {
		font-weight: normal;
	}

	.wt-listing #wtmain-content .wt-grid-shortcode figure.ex-modern-blog .wt-more-meta {
		line-height: unset;
	}

	.wt-listing #wtmain-content .wt-searchbar {
		margin: 0 0 30px 0;
		border: 1px solid #ddd;
		padding: 15px 20px 25px 20px;
		background: #fafafa;
	}

	.wt-listing #wtmain-content .wt-search-form .exwt-input-group {
		box-shadow: none;
	}
</style>

<?php
/**
 * The blog template file.
 *
 * @package flatsome
 */

get_header();
$taxonomy = get_queried_object();
$term = get_queried_object();

?>
<?php
$taxonomy = get_queried_object();
$term_id = $taxonomy->term_id;
$field_key = "cate_pr";
//echo var_dump($taxonomy);

$field = get_field($field_key, "product_cat" . '_' . $term_id);
//echo $field;
if ($field == "Thuê xe") {
	get_template_part('woocommerce/layouts/thue-xe');
	comments_template();
} else if ($field == "Hotel") {
	get_template_part('woocommerce/layouts/hotel');
	comments_template();
} else if ($field == "Tour") {
	get_template_part('woocommerce/layouts/tour-page');
	//comments_template();
} else if ($field == "Cruise") {
	get_template_part('woocommerce/layouts/cruise-page');
	//comments_template();
} else {
	get_template_part('woocommerce/layouts/tour-page');
}

?>


<?php
get_footer();
