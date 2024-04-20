<?php
add_filter('woocommerce_single_product_zoom_enabled', '__return_false', 999);
get_header();
global $wt_sidebar;
$wt_sidebar = get_post_meta(get_the_ID(), 'wt_sidebar', true);
if ($wt_sidebar == '') {
    $wt_sidebar = get_option('wt_sidebar', 'right');
}
global $wt_main_purpose;
$exwf_sgpp = exwt_get_layout_purpose(get_the_ID());
$wt_main_purpose = $exwf_sgpp;
?>
<?php
$post_type = get_post_type(get_the_ID());
//$term_id = $taxonomy->term_id;

$field_key = "detail_pr";
//echo var_dump($field_key);
$field = get_field("detail_pr");

if ($field == "Cruise") {
    get_template_part('woocommerce/single-product/layouts/cruise-detail');
    //  comments_template();
} else if ($field == "Tour") {
    get_template_part('woocommerce/single-product/layouts/tour-detail');
    //   comments_template();
} else if ($field == "Thuê xe") {
    get_template_part('woocommerce/single-product/layouts/thue-xe-detail');
    comments_template();
} else if ($field == "Hotel") {
    get_template_part('woocommerce/single-product/layouts/hotel-detail');
    comments_template();
} else
?>

<?php get_footer();
