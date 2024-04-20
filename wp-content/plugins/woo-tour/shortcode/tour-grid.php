<?php
function parse_wt_grid_func($atts, $content){
	if(is_admin()){ return;}
	global $columns,$number_excerpt,$img_size;
	$ID = isset($atts['ID']) ? $atts['ID'] : rand(10,9999);
	$posttype =  isset($atts['posttype']) ? $atts['posttype'] :'product';
	$ids =  isset($atts['ids']) ? $atts['ids'] :'';
	$count =  isset($atts['count']) ? $atts['count'] :'6';
	$posts_per_page =  isset($atts['posts_per_page']) ? $atts['posts_per_page'] :'';
	$order =  isset($atts['order']) ? $atts['order'] :'';
	$orderby =  isset($atts['orderby']) ? $atts['orderby'] :'';
	$cat =  isset($atts['cat']) ? $atts['cat'] :'';
	$tag =  isset($atts['tag']) ? $atts['tag'] :'';
	$location =  isset($atts['location']) ? $atts['location'] :'';
	$style =  isset($atts['style']) ? $atts['style'] :'';
	$number_excerpt =  isset($atts['number_excerpt'])&& $atts['number_excerpt']!='' ? $atts['number_excerpt'] : '10';
	$columns =  isset($atts['columns']) && $atts['columns']!='' ? $atts['columns'] :'3';
	$img_size =  isset($atts['img_size']) ? $atts['img_size'] :'wethumb_460x307';
	$paged = get_query_var('paged')?get_query_var('paged'):(get_query_var('page')?get_query_var('page'):1);
	$meta_key 	= isset($atts['meta_key']) ? $atts['meta_key'] : '';
	$meta_value 	= isset($atts['meta_value']) ? $atts['meta_value'] : '';

	if($posts_per_page =="" || $posts_per_page > $count){$posts_per_page = $count; $paged ='';}
	
	if($orderby == 'recent_view'){
		$viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] ) : array();
		$viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );
		$ids = implode(",",$viewed_products);
		if ( empty( $viewed_products ) ) {
			return;
		}
	}
	
	$args = woo_tour_query($posttype, $posts_per_page, $order, $orderby, $cat, $tag, $ids,$paged,'',$location, $meta_key, $meta_value);
	ob_start();
	$the_query = new WP_Query( $args ); 
	if($the_query->have_posts()){?>
		<div class="wt-grid-shortcode <?php echo 'wt-grid-column-'.esc_attr($columns).' gr-'.$style;?>" id="grid-<?php echo $ID;?>">
        	<div class="ct-grid">
        	<div class="grid-container">
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
				if(function_exists('wp_pagenavi')) {
					$the_query->max_num_pages = $num_pg;
				}
				$i++;
				if(($num_pg == $paged) && $num_pg!='1'){
					if($i > $it_ep){ break;}
				}
				if($style=='classic'){
                	wootour_template_plugin('grid-classic', true);
                }else{
					wootour_template_plugin('grid', true);
				}
            }?>
            </div>
            <div class="clear"></div>
            </div>
            <?php
			if(function_exists('wp_pagenavi')) {
				?>
                <div class="wt-pagenavi">
					<?php
                    wp_pagenavi(array( 'query' => $the_query));
                    ?>
                </div>
                <?php
			}else{
				if($posts_per_page<$count){
					$loadtrsl = esc_html__('Load more','woo-tour');
					echo '
						<div class="ex-loadmore">
							<input type="hidden"  name="id_grid" value="grid-'.$ID.'">
							<input type="hidden"  name="num_page" value="'.$num_pg.'">
							<input type="hidden"  name="num_page_uu" value="1">
							<input type="hidden"  name="current_page" value="1">
							<input type="hidden"  name="ajax_url" value="'.esc_url(admin_url( 'admin-ajax.php' )).'">
							<input type="hidden"  name="param_query" value="'.esc_html(str_replace('\/', '/', json_encode($args))).'">
							<input type="hidden" id="param_shortcode" name="param_shortcode" value="'.esc_html(str_replace('\/', '/', json_encode($atts))).'">
							<a  href="javascript:void(0)" class="loadmore-grid" data-id="grid-'.$ID.'">
								<span class="load-text">'.$loadtrsl.'</span><span></span>&nbsp;<span></span>&nbsp;<span></span>
							</a>';
					echo'</div>';
				}
			}?>
        </div>
		<?php
	}else{
		$noftrsl = esc_html__('No Tour Found','woo-tour');
		if($orderby=='has_signed_up' && !is_user_logged_in()){
			$noftrsl = esc_html__('Please Login to See','woo-tour');
			echo '<h2>'.$noftrsl.'</h2>';
		}else{
			echo '<p>'.$noftrsl.'</p>';
		}
	}
	wp_reset_postdata();
	$output_string = ob_get_contents();
	ob_end_clean();
	return $output_string;

}
add_shortcode( 'wt_grid', 'parse_wt_grid_func' );
add_action( 'after_setup_theme', 'wt_reg_grid_vc' );
function wt_reg_grid_vc(){
	if(function_exists('vc_map')){
	vc_map( array(
	   "name" => esc_html__("WooTours - Grid", "woo-tour"),
	   "base" => "wt_grid",
	   "class" => "",
	   "icon" => "icon-grid",
	   "controls" => "full",
	   "category" => esc_html__('Wootours','woo-tour'),
	   "params" => array(
		   array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Style", 'woo-tour'),
			 "param_name" => "style",
			 "value" => array(
			 	esc_html__('Modern', 'woo-tour') => '',
				esc_html__('Classic', 'woo-tour') => 'classic',
				
			 ),
			 "description" => ''
		  ),
		   array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Columns", 'woo-tour'),
			 "param_name" => "columns",
			 "value" => array(
			 	esc_html__('', 'woo-tour') => '',
				esc_html__('1 columns', 'woo-tour') => '1',
				esc_html__('2 columns', 'woo-tour') => '2',
				esc_html__('3 columns', 'woo-tour') => '3',
				esc_html__('4 columns', 'woo-tour') => '4',
				esc_html__('5 columns', 'woo-tour') => '5',
			 ),
			 "description" => ''
		  ),	
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
				esc_html__('Has signed up', 'woo-tour') => 'has_signed_up',
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