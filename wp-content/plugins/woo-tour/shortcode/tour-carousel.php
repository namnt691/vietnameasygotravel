<?php
function parse_wt_carousel_func($atts, $content){
	if(is_admin()){ return;}
	$ID = isset($atts['ID']) ? $atts['ID'] : rand(10,9999);
	$posttype =  isset($atts['posttype']) ? $atts['posttype'] :'product';
	$ids =  isset($atts['ids']) ? $atts['ids'] :'';
	$count =  isset($atts['count']) ? $atts['count'] :'6';
	$posts_per_page =  isset($atts['posts_per_page']) ? $atts['posts_per_page'] :'3';
	$order =  isset($atts['order']) ? $atts['order'] :'';
	$orderby =  isset($atts['orderby']) ? $atts['orderby'] :'';
	$cat =  isset($atts['cat']) ? $atts['cat'] :'';
	$tag =  isset($atts['tag']) ? $atts['tag'] :'';
	$location =  isset($atts['location']) ? $atts['location'] :'';
	$number_excerpt =  isset($atts['number_excerpt']) ? $atts['number_excerpt'] :'';

	$meta_key 	= isset($atts['meta_key']) ? $atts['meta_key'] : '';
	$meta_value 	= isset($atts['meta_value']) ? $atts['meta_value'] : '';

	
	$autoplay =  isset($atts['autoplay']) ? $atts['autoplay'] :'';
	global $img_size,$number_excerpt;
	$img_size =  isset($atts['img_size']) ? $atts['img_size'] :'wethumb_460x307';
	
	$grid_autoplay ='off' ;
	
	if($orderby == 'recent_view'){
		$viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] ) : array();
		$viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );
		$ids = implode(",",$viewed_products);
		if ( empty( $viewed_products ) ) {
			return;
		}
	}
	
	$args = woo_tour_query($posttype, $count, $order, $orderby, $cat, $tag, $ids,'','',$location,$meta_key, $meta_value);
	ob_start();
	$the_query = new WP_Query( $args );
	if($the_query->have_posts()){?>
		<div class="wt-carousel wt-grid-shortcode wt-grid-column-1" id="grid-<?php echo $ID;?>">
        	<div class="grid-container">
                <div class="is-carousel" id="post-corousel-<?php echo $ID; ?>" data-items="<?php echo esc_attr($posts_per_page); ?>" <?php if($autoplay=='on'){?> data-autoplay=1 <?php }?> data-navigation=1 data-pagination=1 data-rtl="<?php echo is_rtl() ? 'yes' :'';?>">
                    <?php 
                    $i=0;
                    $it = $the_query->found_posts;
                    if($it < $count || $count=='-1'){ $count = $it;}
                    if($count  > $posts_per_page){
                        $num_pg = ceil($count/$posts_per_page);
                        $it_ep  = $count%$posts_per_page;
                    }else{
                        $num_pg = 1;
                    }
                    while($the_query->have_posts()){ $the_query->the_post();
                        ?>
                        <div class="grid-row">
                        	<?php wootour_template_plugin('carousel', true);?>
                        </div>
                        <?php
                    }?>            
                </div>
            </div>
            <div class="clear"></div>
        </div>
		<?php
	}
	wp_reset_postdata();
	$output_string = ob_get_contents();
	ob_end_clean();
	return $output_string;

}
add_shortcode( 'wt_carousel', 'parse_wt_carousel_func' );
add_action( 'after_setup_theme', 'wt_reg_carousel_vc' );
function wt_reg_carousel_vc(){
	if(function_exists('vc_map')){
	vc_map( array(
	   "name" => esc_html__("Wootours - Carousel", "woo-tour"),
	   "base" => "wt_carousel",
	   "class" => "",
	   "icon" => "icon-carousel",
	   "controls" => "full",
	   "category" => esc_html__('Wootours','woo-tour'),
	   "params" => array(
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("IDs", "woo-tour"),
			"param_name" => "ids",
			"value" => "",
			"description" => esc_html__("Specify post IDs to retrieve", "woo-tour"),
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Count", "woo-tour"),
			"param_name" => "count",
			"value" => "",
			"description" => esc_html__("Number of posts", 'woo-tour'),
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Posts per page", "woo-tour"),
			"param_name" => "posts_per_page",
			"value" => "",
			"description" => esc_html__("Number items per page", 'woo-tour'),
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Category", "woo-tour"),
			"param_name" => "cat",
			"value" => "",
			"description" => esc_html__("List of cat ID (or slug), separated by a comma", "woo-tour"),
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Tags", "woo-tour"),
			"param_name" => "tag",
			"value" => "",
			"description" => esc_html__("List of tags, separated by a comma", "woo-tour"),
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Location", "woo-tour"),
			"param_name" => "location",
			"value" => "",
			"description" => esc_html__("List of locations, separated by a comma", "woo-tour"),
		  ),
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Order", 'woo-tour'),
			 "param_name" => "order",
			 "value" => array(
			 	esc_html__('DESC', 'woo-tour') => 'DESC',
				esc_html__('ASC', 'woo-tour') => 'ASC',
			 ),
			 "description" => ''
		  ),
		  array(
		  	 "admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Order by", 'woo-tour'),
			 "param_name" => "orderby",
			 "value" => array(
			 	esc_html__('Date', 'woo-tour') => 'date',
				esc_html__('ID', 'woo-tour') => 'ID',
				esc_html__('Author', 'woo-tour') => 'author',
			 	esc_html__('Title', 'woo-tour') => 'title',
				esc_html__('Name', 'woo-tour') => 'name',
				esc_html__('Modified', 'woo-tour') => 'modified',
			 	esc_html__('Parent', 'woo-tour') => 'parent',
				esc_html__('Random', 'woo-tour') => 'rand',
				esc_html__('Menu order', 'woo-tour') => 'menu_order',
				esc_html__('Meta value', 'woo-tour') => 'meta_value',
				esc_html__('Meta value num', 'woo-tour') => 'meta_value_num',
				esc_html__('Post__in', 'woo-tour') => 'post__in',
				esc_html__('Unexpired', 'woo-tour') => 'unexpired',
				esc_html__('Has expired', 'woo-tour') => 'has_expired',
				esc_html__('Recently Viewed', 'woo-tour') => 'recent_view',
				esc_html__('Sale', 'woo-tour') => 'sale',
				esc_html__('Featured', 'woo-tour') => 'featured',
				esc_html__('None', 'woo-tour') => 'none',
			 ),
			 "description" => ''
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Meta key", "woo-tour"),
			"param_name" => "meta_key",
			"value" => "",
			"description" => esc_html__("Enter meta key to query", "woo-tour"),
		  ),
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Autoplay", 'woo-tour'),
			 "param_name" => "autoplay",
			 "value" => array(
				esc_html__('Off', 'woo-tour') => 'off',
				esc_html__('On', 'woo-tour') => 'on',
			 ),
			 "description" => ''
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Number of Excerpt", "woo-tour"),
			"param_name" => "number_excerpt",
			"value" => "",
			"description" => esc_html__("Enter number", "woo-tour"),
		  ),
	   )
	));
	}
}