<?php
 $post_type = get_post_type(get_the_ID());
$term_id = $taxonomy->term_id;
$field_key = "detail_pr";
//echo var_dump($field_key);
$field = get_field("detail_pr");
if($field=="Thuê xe")
{

get_template_part( 'woocommerce/single-product/layouts/thue-xe-detail');
			comments_template();

	
}
else if($field="Hotel"){
	get_template_part( 'woocommerce/single-product/layouts/hotel-detail');
			comments_template();
}

?>
