
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
if($field=="Thuê xe")
{
get_template_part( 'woocommerce/layouts/thue-xe');
			comments_template();
}
else if($field="Hotel"){
get_template_part( 'woocommerce/layouts/hotel');
			comments_template();
}

?>

