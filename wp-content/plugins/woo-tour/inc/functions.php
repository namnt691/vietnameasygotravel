<?php
include_once 'class-price-by-season.php';
if(get_option('wt_enable_exoptions') =='yes'){
include plugin_dir_path(__FILE__).'product-options-addon/product-options-addon.php';
}
if(!function_exists('wt_startsWith')){
	function wt_startsWith($haystack, $needle)
	{
		return !strncmp($haystack, $needle, strlen($needle));
	}
} 
if(!function_exists('wt_get_google_fonts_url')){
	function wt_get_google_fonts_url ($font_names) {
	
		$font_url = '';
	
		$font_url = add_query_arg( 'family', urlencode(implode('|', $font_names)) , "//fonts.googleapis.com/css" );
		return $font_url;
	} 
}
if(!function_exists('wt_get_google_font_name')){
	function wt_get_google_font_name($family_name){
		$name = $family_name;
		if(wt_startsWith($family_name, 'http')){
			// $family_name is a full link, so first, we need to cut off the link
			$idx = strpos($name,'=');
			if($idx > -1){
				$name = substr($name, $idx);
			}
		}
		$idx = strpos($name,':');
		if($idx > -1){
			$name = substr($name, 0, $idx);
			$name = str_replace('+',' ', $name);
		}
		return $name;
	}
}


function wt_filter_wc_get_template_single($template, $slug, $name){
	if($slug=='content' && $name =='single-product'){
		return wootour_template_plugin('single-product');
	}else{ 
		return $template;
	}
}
function filter_wc_get_template_shop($template, $slug, $name){
	if($slug=='content' && $name =='product'){
		return wootour_template_plugin('product');
	}else{ 
		return $template;
	}
}
function wt_filter_wc_get_template_related($located, $template_name, $args){
	if($template_name =='single-product/related.php'){
		if (locate_template('woo-tour/related.php') != '') {
			return get_template_part('woo-tour/related');
		} else {
			return wt_get_plugin_url().'templates/related.php';
		}
	}else{ 
		return $located;
	}
}
// fix duplicate content
function wt_filter_wc_get_template_description($located, $template_name, $args){
	if($template_name =='single-product/tabs/description.php'){
		if (locate_template('woo-tour/description.php') != '') {
			return get_template_part('woo-tour/description');
		} else {
			return wt_get_plugin_url().'templates/description.php';
		}
	}else{ 
		return $located;
	}
}

function wt_filter_wc_get_template_variation($located, $template_name, $args){
	if($template_name =='single-product/add-to-cart/variation.php'){
		if (locate_template('woo-tour/variation.php') != '') {
			return get_template_part('woo-tour/variation');
		} else {
			return wt_get_plugin_url().'templates/variation.php';
		}
	}else{ 
		return $located;
	}
}

$wt_main_purpose = get_option('wt_main_purpose');
add_filter( 'wc_get_template', 'wt_filter_wc_get_template_variation', 99, 3 );
if($wt_main_purpose!='meta'){
	add_filter( 'wc_get_template', 'wt_filter_wc_get_template_description', 10, 3 );
	add_filter( 'wc_get_template_part', 'wt_filter_wc_get_template_single', 10, 3 );
	add_filter( 'wc_get_template_part', 'filter_wc_get_template_shop', 99, 3 );
	if($wt_main_purpose!='meta'){
		add_filter( 'wc_get_template', 'wt_filter_wc_get_template_related', 99, 3 );
	}
}
add_action( 'after_setup_theme', 'exwt_enable_gallery' );
 
function exwt_enable_gallery() {
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
// Change number or products per row to 3
if(!function_exists('wootour_template_plugin')){
	function wootour_template_plugin($pageName,$shortcode=false){
		if(isset($shortcode) && $shortcode== true){
			if (locate_template('woo-tour/content-shortcode/content-' . $pageName . '.php') != '') {
				get_template_part('woo-tour/content-shortcode/content', $pageName);
			} else {
				include wt_get_plugin_url().'shortcode/content/content-' . $pageName . '.php';
			}

		}else{
			if (locate_template('woo-tour/content-' . $pageName . '.php') != '') {
				get_template_part('woo-tour/content', $pageName);
			} else {
				include wt_get_plugin_url().'templates/content-' . $pageName . '.php';
			}
		}
	}
}
//
if(!function_exists('wt_taxonomy_info')){
	function wt_taxonomy_info( $tax, $link=false, $id= false){
		if(isset($id) && $id!=''){
			$product_id = $id;
		}else{
			$product_id = get_the_ID();
		}
		$post_type = 'product';
		ob_start();
		if(isset($tax) && $tax!=''){
			$args = array(
				'hide_empty'        => false, 
			);
			$terms = wp_get_post_terms($product_id, $tax, $args);
			if(!empty($terms) && !is_wp_error( $terms )){
				$c_tax = count($terms);
				$i=0;
				foreach ( $terms as $term ) {
					$i++;
					if(isset($link) && $link=='off'){
						echo $term->name;
					}else{
						echo '<a href="'.get_term_link( $term ).'" title="' . $term->name . '">'. $term->name .'</a>';
					}
					if($i != $c_tax){ echo '<span>, </span>';}
				}
			}
		}
		$output_string = ob_get_contents();
		ob_end_clean();
		return $output_string;
	}
}
// Get has purchased
function wt_get_all_products_ordered_by_user() {
    $orders = wt_get_all_user_orders(get_current_user_id(), 'completed');
    if(empty($orders)) {
        return false;
    }
    $order_list = '(' . join(',', $orders) . ')';//let us make a list for query
    //so, we have all the orders made by this user that were completed.
    //we need to find the products in these orders and make sure they are downloadable.
    global $wpdb;
    $query_select_order_items = "SELECT order_item_id as id FROM {$wpdb->prefix}woocommerce_order_items WHERE order_id IN {$order_list}";
    $query_select_product_ids = "SELECT meta_value as product_id FROM {$wpdb->prefix}woocommerce_order_itemmeta WHERE meta_key=%s AND order_item_id IN ($query_select_order_items)";
    $products = $wpdb->get_col($wpdb->prepare($query_select_product_ids, '_product_id'));
    return $products;
}
function wt_get_all_user_orders($user_id, $status = 'completed') {
    if(!$user_id) {
        return false;
    }
    $args = array(
        'numberposts' => -1,
        'meta_key' => '_customer_user',
        'meta_value' => $user_id,
        'post_type' => 'shop_order',
        'post_status' => array( 'wc-completed' )
        
    );
    $posts = get_posts($args);
    //get the post ids as order ids
    return wp_list_pluck($posts, 'ID');
}
// Query function
if(!function_exists('woo_tour_query')){
	function woo_tour_query($posttype, $count, $order, $orderby, $cat, $tag, $ids,$page=false,$data_qr=false, $location=false,$meta_key = false, $meta_value=false){
		if($orderby=='has_signed_up'){
			if(get_current_user_id()){
				$ids = wt_get_all_products_ordered_by_user(); 
				if($ids ==''){$ids = '-1';}
			}else{
				$ids = '-1';
			}
		}
		if($orderby=='sale'){
			$ids = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
		}
		if($ids!='' || (is_array($ids) && !empty($ids))){ //specify IDs
			if(!is_array($ids)){
				$ids = explode(",", $ids);
			}
			$args = array(
				'post_type' => $posttype,
				'posts_per_page' => $count,
				'post_status' => 'publish',
				'post__in' =>  $ids,
				'order' => $order,
				'orderby' => $orderby,
				'ignore_sticky_posts' => 1,
			);
		}elseif($ids==''){
			$args = array(
				'post_type' => $posttype,
				'posts_per_page' => $count,
				'post_status' => 'publish',
				'order' => $order,
				'orderby' => $orderby,
				'ignore_sticky_posts' => 1,
			);
			if($tag!=''){
				$tags = explode(",",$tag);
				if(is_numeric($tags[0])){$field_tag = 'term_id'; }
				else{ $field_tag = 'slug'; }
				if(count($tags)>1){
					  $texo = array(
						  'relation' => 'OR',
					  );
					  foreach($tags as $iterm) {
						  $texo[] = 
							  array(
								  'taxonomy' => 'product_tag',
								  'field' => $field_tag,
								  'terms' => $iterm,
							  );
					  }
				  }else{
					  $texo = array(
						  array(
								  'taxonomy' => 'product_tag',
								  'field' => $field_tag,
								  'terms' => $tags,
							  )
					  );
				}
			}
			//cats
			if($cat!=''){
				$cats = explode(",",$cat);
				if(is_numeric($cats[0])){$field = 'term_id'; }
				else{ $field = 'slug'; }
				if(count($cats)>1){
					  $texo = array(
						  'relation' => 'OR',
					  );
					  foreach($cats as $iterm) {
						  $texo[] = 
							  array(
								  'taxonomy' => 'product_cat',
								  'field' => $field,
								  'terms' => $iterm,
							  );
					  }
				  }else{
					  $texo = array(
						  array(
								  'taxonomy' => 'product_cat',
								  'field' => $field,
								  'terms' => $cats,
							  )
					  );
				}
			}
			//location
			if($location!=''){
				$locations = explode(",",$location);
				if(is_numeric($locations[0])){$field = 'term_id'; }
				else{ $field = 'slug'; }
				if(count($locations)>1){
					  $texo = array(
						  'relation' => 'OR',
					  );
					  foreach($locations as $iterm) {
						  $texo[] = 
							  array(
								  'taxonomy' => 'wt_location',
								  'field' => $field,
								  'terms' => $iterm,
							  );
					  }
				  }else{
					  $texo = array(
						  array(
								  'taxonomy' => 'wt_location',
								  'field' => $field,
								  'terms' => $locations,
							  )
					  );
				}
			}
			if(isset($texo)){
				$args += array('tax_query' => $texo);
			}
			if(isset($data_qr) && $data_qr!='' && is_numeric($data_qr)){
				$args['meta_query'] = array (
					 array(
						'key' => 'wt_speakers',
						'value' => $data_qr,
						'compare' => 'LIKE'
					)
				);
			}
			$cure_time =  strtotime("now");
			if($orderby=='unexpired'){
				if($order==''){$order='ASC';}
				$args += array('meta_key' => 'wt_expired', 'meta_value' => $cure_time, 'meta_compare' => '>');
				$args['orderby']= 'meta_value_num';
				$args['order']= $order;
			}elseif($orderby=='has_expired'){
				if($order==''){$order='DESC';}
				$args += array('meta_key' => 'wt_expired', 'meta_value' => $cure_time, 'meta_compare' => '<');
				$args['orderby']= 'meta_value_num';
				$args['order']= $order;
			}elseif($orderby=='featured'){
				if(!empty($args['meta_query'])){
					$args['meta_query']['relation'] = 'AND';
				}
				$args['orderby']= '';
				$tax_query[] = array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => 'featured',
					'operator' => 'IN',
				);
				$args['tax_query'] = $tax_query;
			}
		}
		if(isset($meta_key) && $meta_key!=''){
			$args['meta_key'] = $meta_key;
		}
		if(isset($meta_value) && $meta_value!='' && $meta_key!=''){
			if(!empty($args['meta_query'])){
				$args['meta_query']['relation'] = 'AND';
			}
			$args['meta_query'][] = array(
				'key'  => $meta_key,
				'value' => $meta_value,
				'compare' => '='
			);
		}
		if(isset($page) && $page!=''){
			$args['paged'] = $page;
		}
//		echo '<pre>';
//		print_r($args);
//		echo '</pre>';
		if(!isset($args['tax_query'])){
			$args['tax_query'] = array();
		}
		$args['tax_query'][] = array(
	        'taxonomy' => 'product_visibility',
	        'field'    => 'name',
	        'terms'    => 'exclude-from-catalog',
	        'operator' => 'NOT IN',
	    );
		$args = apply_filters('exwt_query',$args);
		return $args;
	}
}
//Status
if(!function_exists('woo_tour_status')){
	function woo_tour_status( $post_id, $wt_enddate=false){
		return '';
	}
}
//
if(!function_exists('wt_social_share')){
	function wt_social_share( $id = false){
		$id = get_the_ID();
		$tl_share_button = array('fb','tw','li','tb','gg','pin','vk','em','ws');
		$disable_ss = get_option('wt_ssocial_dis');
		if(is_array($disable_ss) && !empty($disable_ss)){
			$tl_share_button = array_diff($tl_share_button,$disable_ss);
		}
		$tl_share_button = apply_filters('exwt_social_share_icons',$tl_share_button);
		ob_start();
		if(is_array($tl_share_button) && !empty($tl_share_button)){
			?>
			<ul class="wootour-social-share">
				<?php if(in_array('fb', $tl_share_button)){ ?>
					<li class="facebook">
						<a class="trasition-all" title="<?php esc_html_e('Share on Facebook','woo-tour');?>" href="#" target="_blank" rel="nofollow" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u='+'<?php echo urlencode(get_permalink($id)); ?>','facebook-share-dialog','width=626,height=436');return false;"><i class="fa fa-facebook"></i>
						</a>
					</li>
				<?php }
	
				if(in_array('tw', $tl_share_button)){ ?>
					<li class="twitter">
						<a class="trasition-all" href="#" title="<?php esc_html_e('Share on Twitter','woo-tour');?>" rel="nofollow" target="_blank" onclick="window.open('http://twitter.com/share?text=<?php echo urlencode(html_entity_decode(get_the_title($id), ENT_COMPAT, 'UTF-8')); ?>&amp;url=<?php echo urlencode(get_permalink($id)); ?>','twitter-share-dialog','width=626,height=436');return false;"><i class="fa fa-twitter"></i>
						</a>
					</li>
				<?php }
	
				if(in_array('li', $tl_share_button)){ ?>
						<li class="linkedin">
							<a class="trasition-all" href="#" title="<?php esc_html_e('Share on LinkedIn','woo-tour');?>" rel="nofollow" target="_blank" onclick="window.open('http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo urlencode(get_permalink($id)); ?>&amp;title=<?php echo urlencode(html_entity_decode(get_the_title($id), ENT_COMPAT, 'UTF-8')); ?>&amp;source=<?php echo urlencode(get_bloginfo('name')); ?>','linkedin-share-dialog','width=626,height=436');return false;"><i class="fa fa-linkedin"></i>
							</a>
						</li>
				<?php }
	
				if(in_array('tb', $tl_share_button)){ ?>
					<li class="tumblr">
					   <a class="trasition-all" href="#" title="<?php esc_html_e('Share on Tumblr','woo-tour');?>" rel="nofollow" target="_blank" onclick="window.open('http://www.tumblr.com/share/link?url=<?php echo urlencode(get_permalink($id)); ?>&amp;name=<?php echo urlencode(html_entity_decode(get_the_title($id), ENT_COMPAT, 'UTF-8')); ?>','tumblr-share-dialog','width=626,height=436');return false;"><i class="fa fa-tumblr"></i>
					   </a>
					</li>
				<?php }
	
				 if(in_array('pin', $tl_share_button)){ ?>
					 <li class="pinterest">
						<a class="trasition-all" href="#" title="<?php esc_html_e('Pin this','woo-tour');?>" rel="nofollow" target="_blank" onclick="window.open('//pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink($id)) ?>&amp;media=<?php echo urlencode(wp_get_attachment_url( get_post_thumbnail_id($id))); ?>&amp;description=<?php echo urlencode(html_entity_decode(get_the_title($id), ENT_COMPAT, 'UTF-8')); ?>','pin-share-dialog','width=626,height=436');return false;"><i class="fa fa-pinterest"></i>
						</a>
					 </li>
				 <?php }
				 
				 if(in_array('vk', $tl_share_button)){ ?>
					 <li class="vk">
						<a class="trasition-all" href="#" title="<?php esc_html_e('Share on VK','woo-tour');?>" rel="nofollow" target="_blank" onclick="window.open('//vkontakte.ru/share.php?url=<?php echo urlencode(get_permalink(get_the_ID())); ?>','vk-share-dialog','width=626,height=436');return false;"><i class="fa fa-vk"></i>
						</a>
					 </li>
				 <?php }
	
				 if(in_array('em', $tl_share_button)){ ?>
					<li class="email">
						<a class="trasition-all" href="mailto:?subject=<?php echo urlencode(html_entity_decode(get_the_title($id), ENT_COMPAT, 'UTF-8')); ?>&amp;body=<?php echo urlencode(get_permalink($id)) ?>" title="<?php esc_html_e('Email this','woo-tour');?>"><i class="fa fa-envelope"></i>
						</a>
					</li>
				<?php }
				if(in_array('ws', $tl_share_button)){ 
					$mes = get_the_title($id). "\n";
					$mes .= get_permalink($id). "\n";
					?>
					<li class="whatsapp">
						<a class="trasition-all" href="https://wa.me/?text=<?php echo urlencode(strip_tags($mes)) ?>" title="<?php esc_html_e('Share on Whatsapp','woo-tour');?>"><i class="fa fa-whatsapp"></i>
						</a>
					</li>
				<?php }
				do_action('exwt_after_social_share');
				?>
			</ul>
			<?php
		}
		$output_string = ob_get_contents();
		ob_end_clean();
		return $output_string;
	}
}
//Global function
function wootour_global_layout(){
	if(is_singular('product')){
		global $layout,$post;
		if(isset($layout) && $layout!=''){
			return $layout;
		}
		$layout = get_post_meta( $post->ID, 'wt_layout', true );
		if($layout ==''){
			$layout = get_option('wt_slayout');
		}
		return $layout;
		}
}
function wt_global_expireddate(){
	global $wt_enddate, $post;
	if(isset($wt_enddate) && $wt_enddate!='' && is_main_query() && is_singular('product')){
		return $wt_enddate;
	}
	$wt_enddate = get_post_meta( $post->ID, 'wt_expired', true ) ;
	if($wt_enddate!=''){
		$wt_enddate = $wt_enddate + 86399;
	}
	return $wt_enddate;
}
function wt_global_main_purpose(){
	$wt_main_purpose = get_option('wt_main_purpose');
	return $wt_main_purpose;
}
//Ajax grid
add_action( 'wp_ajax_ex_loadmore_grid', 'ajax_ex_loadmore_grid' );
add_action( 'wp_ajax_nopriv_ex_loadmore_grid', 'ajax_ex_loadmore_grid' );

function ajax_ex_loadmore_grid(){
	global $columns,$number_excerpt,$img_size;
	$atts = json_decode( stripslashes( $_POST['param_shortcode'] ), true );
	$columns = $atts['columns']	=  isset($atts['columns']) ? $atts['columns'] : 1;
	$count =  isset($atts['count']) ? $atts['count'] :'6';
	$posts_per_page =  isset($atts['posts_per_page']) ? $atts['posts_per_page'] :'';
	$number_excerpt =  isset($atts['number_excerpt'])&& $atts['number_excerpt']!='' ? $atts['number_excerpt'] : '10';
	$img_size =  isset($atts['img_size']) ? $atts['img_size'] :'wethumb_460x307';
	$page = $_POST['page'];
	$param_query = json_decode( stripslashes( $_POST['param_query'] ), true );
	$end_it_nb ='';
	if($page!=''){ 
		$param_query['paged'] = $page;
		$count_check = $page*$posts_per_page;
		if(($count_check > $count) && (($count_check - $count)< $posts_per_page)){$end_it_nb = $count - (($page - 1)*$posts_per_page);}
		else if(($count_check > $count)) {die;}
	}
	//echo '<pre>';
	//print_r($param_query);//exit;
	$the_query = new WP_Query( $param_query );
	$it = $the_query->post_count;
	ob_start();
	if($the_query->have_posts()){
		$i =0;
		while($the_query->have_posts()){ $the_query->the_post();
			$i++;
			wootour_template_plugin('grid', true);
			if($end_it_nb!='' && $end_it_nb == $i){break;}
		}
	}
	$html = ob_get_clean();
	echo  $html;
	die;
}
//Ajax list
add_action( 'wp_ajax_ex_loadmore_list', 'ajax_ex_loadmore_list' );
add_action( 'wp_ajax_nopriv_ex_loadmore_list', 'ajax_ex_loadmore_list' );

function ajax_ex_loadmore_list(){
	global $img_size,$phone,$quotes_link;
	$atts = json_decode( stripslashes( $_POST['param_shortcode'] ), true );
	
	$phone =  isset($atts['phone']) ? $atts['phone'] :'';
	$quotes_link =  isset($atts['quotes_link']) ? $atts['quotes_link'] :'';
	$img_size =  isset($atts['img_size']) ? $atts['img_size'] :'wethumb_460x307';
	
	$count =  isset($atts['count']) ? $atts['count'] :'6';
	$posts_per_page =  isset($atts['posts_per_page']) ? $atts['posts_per_page'] :'';
	$page = $_POST['page'];
	$param_query = json_decode( stripslashes( $_POST['param_query'] ), true );
	$end_it_nb ='';
	if($page!=''){ 
		$param_query['paged'] = $page;
		$count_check = $page*$posts_per_page;
		if(($count_check > $count) && (($count_check - $count)< $posts_per_page)){$end_it_nb = $count - (($page - 1)*$posts_per_page);}
		else if(($count_check > $count)) {die;}
	}
	$the_query = new WP_Query( $param_query );
	$it = $the_query->post_count;
	ob_start();
	if($the_query->have_posts()){
		$i =0;
		while($the_query->have_posts()){ $the_query->the_post();
			$i++;
			wootour_template_plugin('list', true);
			if($end_it_nb!='' && $end_it_nb == $i){break;}
		}
	}
	$html = ob_get_clean();
	echo  $html;
	die;
}

//table load
add_action( 'wp_ajax_ex_loadmore_table', 'ajax_ex_loadmore_table' );
add_action( 'wp_ajax_nopriv_ex_loadmore_table', 'ajax_ex_loadmore_table' );

function ajax_ex_loadmore_table(){
	global $style;
	$atts = json_decode( stripslashes( $_POST['param_shortcode'] ), true );
	$style =  isset($atts['style']) ? $atts['style'] :'';
	$count =  isset($atts['count']) ? $atts['count'] :'6';
	$posts_per_page =  isset($atts['posts_per_page']) ? $atts['posts_per_page'] :'';
	$page = $_POST['page'];
	$style =  isset($atts['style']) ? $atts['style'] :'';
	$param_query = json_decode( stripslashes( $_POST['param_query'] ), true );
	$end_it_nb ='';
	if($page!=''){ 
		$param_query['paged'] = $page;
		$count_check = $page*$posts_per_page;
		if(($count_check > $count) && (($count_check - $count)< $posts_per_page)){$end_it_nb = $count - (($page - 1)*$posts_per_page);}
		else if(($count_check > $count)) {die;}
	}
	$the_query = new WP_Query( $param_query );
	$it = $the_query->post_count;
	ob_start();
	global $ajax_load;
	if($the_query->have_posts()){
		while($the_query->have_posts()){ $the_query->the_post();
			$ajax_load =1;
			wootour_template_plugin('table', true);
			if($end_it_nb!='' && $end_it_nb == $i){break;}
		}
	}
	$html = ob_get_clean();
	echo  $html;
	die;
}
//variable_price
if(!function_exists('wt_variable_price_html')){
	function wt_variable_price_html(){
		$fromtrsl = esc_html__('From  ','woo-tour');
		global $product;

		$available_variations = $product->get_available_variations();
		$selectedPrice = '';
		$default_attributes = $product->get_default_attributes();
		if(!empty($default_attributes)){
			foreach($product->get_available_variations() as $pav){
	            $def=true;
	            foreach($product->get_default_attributes() as $defkey=>$defval){
	                if($pav['attributes']['attribute_'.$defkey]!=$defval){
	                    $def=false;             
	                }   
	            }
	            if($def){
	                $selectedPrice = wc_price($pav['display_price'].$product->get_price_suffix());         
	            }
	        }
	    }
		if($selectedPrice!=''){
			$price_html = $selectedPrice;
		}else{
			$selectedPrice = $price_html = wc_price($product->get_variation_price('min')).$product->get_price_suffix();
		}
		if($selectedPrice == wc_price($product->get_variation_price('min')).$product->get_price_suffix()){
			$price_html = $fromtrsl.' '.$price_html;
		}
		return apply_filters( 'wt_variable_price_html', $price_html, $product->get_variation_price('min'));
	}
}
if(!function_exists('wt_price_currency')){
	function wt_price_currency($price, $price_nb=false){
		if(isset($price_nb) && is_numeric($price_nb)){
			return apply_filters( 'wt_child_infant_cur_price', wc_price($price_nb), $price_nb );
		}else{
			return apply_filters( 'wt_child_infant_cur_price', wc_price($price), $price );
		}
	}
}
if(!function_exists('wt_addition_price_html')){
	function wt_addition_price_html($price,$span=true,$sale_price=false,$uncv=false){
		if(!is_numeric($price)){ return;}
		if(function_exists('wmc_get_price') && isset($uncv) && $uncv == true){
			$price =  wmc_get_price($price);
			if(isset($sale_price) && $sale_price > 0){
				$sale_price =  wmc_get_price($sale_price);
			}
		}
		if(isset($uncv) && $uncv == true){
			$price = exwt_convert_currency($price);
			$price = exwt_apply_rounding_rules($price);
			if(isset($sale_price) && $sale_price > 0){
				$sale_price = exwt_convert_currency($sale_price);
				$sale_price = exwt_apply_rounding_rules($sale_price);
			}
		}
		if(apply_filters ('wt_price_child_infant_ac',$price) != $price ){
			return apply_filters ('wt_price_child_infant_ac',$price,$sale_price);
		}
		if(isset($sale_price) && $sale_price > 0){
			$saleprice_nb = $sale_price;
			$sale_price ='<span class="wt-tprice">'.$sale_price.'</span>';
			$price = '<del>'.wt_price_currency($price).'</del>'.wt_price_currency($sale_price,$saleprice_nb);
		}else{
			$price_nb = $price;
			if(isset($span) && $span=='1'){ $price ='<span class="wt-tprice">'.$price.'</span>';}
			$price = '<span>'.wt_price_currency($price,$price_nb).'</span>';
		}
		$price = '<span class="p-price">'.$price.'</span>';
		$price = apply_filters( 'wt_child_infant_price', $price );
		return $price;
	}
}
if(!function_exists('wt_meta_html')){
	function wt_meta_html(){
		$html ='';
		global $post;
		$wt_duration = get_post_meta( $post->ID, 'wt_duration', true ) ;
		if($wt_duration!=''){
		$html .='
			<span>
				<i class="fa fa-clock-o" aria-hidden="true"></i>
				'.$wt_duration.'
			</span>';
		}
		$wt_transport = get_post_meta( $post->ID, 'wt_transport', true ) ;
		if($wt_transport!=''){
		$html .='
			<span>
				<i class="fa fa-paper-plane" aria-hidden="true"></i>
				'.$wt_transport.'
			</span>';
		}
		$html = apply_filters('exwt_grid_meta_html',$html,$post);
		return $html;
	}
}
add_filter( 'body_class', 'wt_custom_class' );
if(!function_exists('wt_custom_class')){
	function wt_custom_class( $classes ) {
		if(is_singular('product')){
			global $wp_query ;
			$post_id = '';
			if(isset($wp_query->queried_object_id)){
				$post_id = $wp_query->queried_object_id;
			}
			$wt_main_purpose = get_option('wt_main_purpose');
			if($wt_main_purpose=='meta'){
				$classes[] = 'wt-mode-meta';
				if($post_id !=''){
					$def_sg = get_option('wt_slayout_purpose');
					$s_layout = get_post_meta( $post_id, 'wt_layout_purpose', true ) ;
					if($def_sg=='tour' && $s_layout!='woo' || $def_sg=='woo' && $s_layout=='tour'){
						$classes[] = 'wt-hide-quantiny';
					}
				}
			}elseif($wt_main_purpose=='custom'){
				$classes[] = 'wt-mode-custom';
				if($post_id !=''){
					$s_layout = get_post_meta( $post_id, 'wt_layout_purpose', true ) ;
					if($s_layout=='tour'){
						$classes[] = 'wt-hide-quantiny';
					}
				}
			}else{
				$classes[] = 'wt-mode-tour wt-hide-quantiny';
			}
			$p_season = exwt_get_price_season($post_id,'');
			if( is_array($p_season) && !empty($p_season)){
				$classes[] = 'exwt-tour-season';
			}
			$time_now =  strtotime("now");
			$gmt_offset = get_option('gmt_offset');
			if($gmt_offset!=''){
				$time_now = $time_now + ($gmt_offset*3600);
			}
			$expireddate = wt_global_expireddate() ;
			if($expireddate !='' && $time_now > $expireddate){
				$classes[] = 'exwt-tour-expired';
			}
		}
		return $classes;
	}
}
function wt_is_tour() {
	$tour = false;
	if(is_singular('product')){
		global $wp_query ;
		$post_id = '';
		if(isset($wp_query->queried_object_id)){
			$post_id = $wp_query->queried_object_id;
		}
		$wt_main_purpose = get_option('wt_main_purpose');
		if($wt_main_purpose=='meta'){
			if($post_id !=''){
				$def_sg = get_option('wt_slayout_purpose');
				$s_layout = get_post_meta( $post_id, 'wt_layout_purpose', true ) ;
				if($def_sg=='tour' && $s_layout!='woo' || $def_sg=='woo' && $s_layout=='tour'){
					$tour = true;
				}
			}
		}elseif($wt_main_purpose=='custom'){
			$classes[] = 'wt-mode-custom';
			if($post_id !=''){
				$s_layout = get_post_meta( $post_id, 'wt_layout_purpose', true ) ;
				if($s_layout=='tour'){
					$tour = true;
				}
			}
		}else{
			$tour = true;
		}
		
	}
	return $tour;
}
if(!function_exists('wt_onsale_check')){
	function wt_onsale_check (){
		global $product;
		if ( $product->is_on_sale() ) {
			echo '<span class="woocommerce-wt-onsale">' . __( 'Sale!', 'woocommerce' ) . '</span>';
		}else {
			if(function_exists('wc_get_rating_html')){
				$rating_html = wc_get_rating_html($product->get_average_rating());
			}else{
				$rating_html = $product->get_rating_html();
			}
			if ( get_option( 'woocommerce_enable_review_rating' ) != 'no' && $rating_html){
					echo '<div class="woocommerce-wt-onsale woocommerce">'.$rating_html.'</div>';
			}
		}
	}
}


//Add info to pdf invoice
add_action( 'wpo_wcpdf_after_item_meta', 'wooevents_add_event_meta', 10, 3 );
function wooevents_add_event_meta ($template_type, $item, $order) {
	$location = wt_taxonomy_info('wt_location','',$item['product_id']);
	$mt_date = wc_get_order_item_meta($item['item_id'],'_date', true);
	$html = $mt_date !='' ? '<dl class="meta"><strong>'.esc_html__('Date: ','woo-tour').'</strong>'.$mt_date.'</dl>' : '';
	$html .= $location!='' ? '<dl class="meta"><strong>'.esc_html__('Location: ','woo-tour').'</strong>'.$location.'</dl>' : '';
	
	$_adult = wc_get_order_item_meta($item['item_id'],'_adult', true);
	if ( $_adult!='' ) {
		$wt_adult_label = get_post_meta( $item['product_id'], 'wt_adult_label', true ) ;
		$wt_adult_label = $wt_adult_label!='' ? $wt_adult_label.':' : esc_html__('Adult: ','woo-tour');
		$html .= '<dl class="variation"><strong>' . $wt_adult_label .' </strong>'. $_adult . '</dl>';

	}
	$_child = wc_get_order_item_meta($item['item_id'],'_child', true);
	if ( $_child!='' ) {
		$wt_child_label = get_post_meta( $item['product_id'], 'wt_child_label', true ) ;
		$wt_child_label = $wt_child_label!='' ? $wt_child_label.':' : esc_html__('Children: ','woo-tour');	
		$html .= '<dl class="variation"><strong>' . $wt_child_label .' </strong>'. $_child . '</dl>';

	}
	$_infant = wc_get_order_item_meta($item['item_id'],'_infant', true);
	if ( $_infant!='' ) {
		$wt_infant_label = get_post_meta( $item['product_id'], 'wt_infant_label', true ) ;
		$wt_infant_label = $wt_infant_label!='' ? $wt_infant_label.':' : esc_html__('Infant: ','woo-tour');
		$html .= '<dl class="variation"><strong>' . $wt_infant_label .' </strong>'. $_infant . '</dl>';
	}
	
	$_wtct1 = wc_get_order_item_meta($item['item_id'],'_wtct1', true);
	if ( $_wtct1!='' ) {
		$label1 = explode("|",get_option('wt_ctfield1_info'));			
		$wt_ctps1_label = get_post_meta( $item['product_id'], 'wt_ctps1_label', true ) ;
		if(isset($label1[0]) && $label1[0]!=''){
			$wt_ctps1_label = $wt_ctps1_label!='' ? $wt_ctps1_label.':' : $label1[0].':';
			$html .= '<dl class="variation"><strong>' . $wt_ctps1_label .' </strong>'. $_wtct1 . '</dl>';
		}
	}
	$_wtct2 = wc_get_order_item_meta($item['item_id'],'_wtct2', true);
	if ( $_wtct2!='' ) {
		$label2 = explode("|",get_option('wt_ctfield2_info'));			
		$wt_ctps2_label = get_post_meta( $item['product_id'], 'wt_ctps2_label', true ) ;
		if(isset($label2[0]) && $label2[0]!=''){
			$wt_ctps2_label = $wt_ctps2_label!='' ? $wt_ctps2_label.':' : $label2[0].':';
			$html .= '<dl class="variation"><strong>' . $wt_ctps2_label .' </strong>'. $_wtct2 . '</dl>';
		}
	}
	
	// user info
	$order_items = $order->get_items();
	$n = 0; $find = 0;
	foreach ($order_items as $items_key => $items_value) {
		$n ++;
		if($items_value->get_id() == $item['item_id']){
			$find = 1;
			break;
		}
	}
	if($find == 0){ return;}
	$value_id = $item['product_id'].'_'.$n;
	$value_id = apply_filters( 'wt_attendee_key', $value_id, $item );
	
	$metadata = get_post_meta($order-> get_id(),'att_info-'.$value_id, true);
	if($metadata == ''){
		$metadata = get_post_meta($order->get_id(),'att_info-'.$item['product_id'], true);
	}
	if($metadata !=''){
		  $metadata = explode("][",$metadata);
		  if(!empty($metadata)){
			  $i=0;
			  foreach($metadata as $item){
				  $i++;
				  $item = explode("||",$item);
				  $f_name = isset($item[1]) && $item[1]!='' ? $item[1] : '';
				  $l_name = isset($item[2]) && $item[2]!='' ? $item[2] : '';
				  $bir_day = isset($item[3]) && $item[3]!='' ? $item[3] : '';
				  $male = isset($item[4]) && $item[4]!='' ? $item[4] : '';				  
				  
				  $html .= '<dl class="we-user-info" style="margin-top:15px;"><b>'.esc_html__('User ','woo-tour').'('.$i.') </b>';
				  $html .=  $f_name!='' && $l_name!='' ? '<p style="margin-top:0;"><span>'.esc_html__('Name: ','woo-tour').'</span>'.$f_name.' '.$l_name.'</p>' : '';
				  $html .=  isset($item[0]) && $item[0]!='' ?  '<p style="margin-top:0;"><span>'.esc_html__('Email: ','woo-tour').'</span>'.$item[0].'</p>' : '';
				  $html .=  $bir_day!='' ? '<p style="margin-top:0;"><span>'.esc_html__('Date of birth: ','woo-tour').'</span>'.$bir_day.'</p>' : '';
				  $html .=  $male!='' ? '<p style="margin-top:0;"><span>'.esc_html__('Gender: ','woo-tour').'</span>'.$male.'</p>' : '';

				  ob_start();
				  do_action( 'wt_after_order_info', $item);
				  $output_string = ob_get_contents();
				  ob_end_clean();
				  $html .= $output_string;

				  $html .= '</dl>';
			  }
		  }
	  }
	
	
	echo $html;
}
add_filter( 'body_class', 'wt_add_ct_class');
function wt_add_ct_class( $classes ) {
	$wt_dbclss = 'wt-mode';
	$purpose = get_option('wt_main_purpose');
	if(get_option(('wt_disable_quantity') != 'yes' && $purpose == 'meta') || $purpose == 'custom'){
		$wt_dbclss = 'wt-unremove-qtn';
	}
	if(is_cart() || is_checkout() || is_account_page()){
		$classes[] = 'exwt-cart-ck-page';
	}
	return array_merge( $classes, array( $wt_dbclss ) );
}
// Check ticket status
add_action( 'wp_ajax_wt_check_ticket_status', 'wt_check_ticket_status' );
add_action( 'wp_ajax_nopriv_wt_check_ticket_status', 'wt_check_ticket_status' );

function wt_check_ticket_status(){
	$wt_sldate = isset($_POST['wt_sldate']) ? $_POST['wt_sldate'] : '';
	$wt_tourid = isset($_POST['wt_tourid']) ? $_POST['wt_tourid'] : '';
	$wt_variable_id = isset($_POST['wt_variable_id']) ? $_POST['wt_variable_id'] : '';
	$avari = get_post_meta($wt_tourid, $wt_sldate, true);
	$mt_varst= get_option('wt_dismulti_varstock');
	if($mt_varst!='yes' && is_numeric($wt_variable_id) && $wt_variable_id > 0){
		if($mt_varst=='sp_only'){
			$_idva_reduce = get_post_meta($wt_variable_id, '_idva_reduce', true);
			if($_idva_reduce!='' && is_numeric($_idva_reduce)){
				$metadate = $wt_sldate.'_vaID_'.$_idva_reduce;
			}else{
				$metadate = $wt_sldate.'_vaID_'.$wt_variable_id;
			}
		}else{
			$metadate = $wt_sldate.'_vaID_'.$wt_variable_id;
		}

		$avari = get_post_meta($wt_tourid, $metadate, true);
	}
	$msg ='';
	if($avari > 0){
		$msg = $avari.' '.esc_html__('Available','woo-tour');
	}elseif($avari!='' && $avari == 0){
		$msg = esc_html__('Sorry, No ticket available at this date','woo-tour');
	}else{
		$def_stock_va = '';
		if($mt_varst!='yes' && is_numeric($wt_variable_id) && $wt_variable_id > 0){
			$def_stock_va = get_post_meta($wt_tourid, $wt_variable_id.'_def_stock', true);
		}
		$def_stock = $def_stock_va!='' ? $def_stock_va : get_post_meta($wt_tourid, 'def_stock', true);
		if($def_stock > 0){
			$avari = $def_stock;
			$msg = $avari.' '.esc_html__('Available','woo-tour');
		}
	}
	$dc = wt_discount_available($wt_tourid,$wt_sldate);
	if($dc!='<p class="crda-dcinfo"></p>'){
		$msg .= ' '.$dc;
	}
	$dtb = explode("_",$wt_sldate);
	$dtb_unix = strtotime($dtb[0].'-'.$dtb[1].'-'.$dtb[2]);
	$season_price = exwt_get_price_season($wt_tourid,$dtb_unix,'',$wt_variable_id);
	$p_season = exwt_get_price_season($wt_tourid,'');
	if (is_plugin_active( 'woocommerce-payments/woocommerce-payments.php' ) ) {
		remove_all_filters('wc_price_args');
	}
	if($season_price == null && is_array($p_season) && !empty($p_season)){
		$all_prices = exwt_get_all_prices($wt_tourid,$wt_variable_id);
		$season_price['wt_p_adult'] =  $all_prices['adult'];
		$season_price['wt_p_child'] = $all_prices['child'];
		$season_price['wt_p_infant'] =  $all_prices['infant'];
		$season_price['wt_p_ctps1'] = $all_prices['ctps1'];
		$season_price['wt_p_ctps2'] = $all_prices['ctps2'];
	}
	if(is_array($season_price) && !empty($season_price)){
		$season_price['wt_p_adult'] =  wc_price(wt_get_price('', '_adult',$season_price));
		$season_price['wt_p_child'] =  wc_price(wt_get_price('', 'wt_child',$season_price));
		$season_price['wt_p_infant'] =  wc_price(wt_get_price('', 'wt_infant',$season_price));
		$season_price['wt_p_ctps1'] =  wc_price(wt_get_price('', 'wt_ctps1',$season_price));
		$season_price['wt_p_ctps2'] = wc_price(wt_get_price('', 'wt_ctps2',$season_price));
	}
	$msg = str_replace(' ', '', $msg)!='' ? $msg : '';
	$output =  array('status'=>$avari,'massage'=> $msg, 'p_season'=>$season_price);
	echo str_replace('\/', '/', json_encode($output));
	die;
}
// get all price
function exwt_get_all_prices($id,$variation_id){
	$prices = array();
	if(is_numeric($variation_id) && $variation_id > 0){
		$product = new WC_Product_Variation( $variation_id );
		$prices['adult'] = $product->get_price();
		$prices['child'] = wt_get_price($variation_id, '_child_price');
		$prices['infant'] = wt_get_price($variation_id, '_infant_price');
		$prices['ctps1'] = wt_get_price($variation_id, '_ctfield1_price');
		$prices['ctps2'] = wt_get_price($variation_id, '_ctfield2_price');
	}else{
		$product = wc_get_product( $id );
		$prices['adult'] = $product->get_price();
		$prices['child'] = wt_get_price($id, 'wt_child');
		$prices['infant'] = wt_get_price($id, 'wt_infant');
		$prices['ctps1'] = wt_get_price($id, 'wt_ctps1');
		$prices['ctps2'] = wt_get_price($id, 'wt_ctps2');
	}
	return $prices;
}
if(!function_exists('wt_discount_available')){
	function wt_discount_available($id,$_metadate){
		$wt_discount = get_post_meta($id,'wt_discount',false);
		$wt_disc_bo = get_post_meta($id,'wt_disc_bo',true);
		$_wtdiscount = '';
		if(!empty($wt_discount)){
			$cure_time =  strtotime("now");
			$gmt_offset = get_option('gmt_offset');
			if($gmt_offset!=''){
				$cure_time = $cure_time + ($gmt_offset*3600);
			}
			usort($wt_discount, function($a, $b) { // anonymous function
				return $a['wt_disc_number'] - $b['wt_disc_number'];
			});
			$wt_discount = array_reverse($wt_discount);
			foreach ($wt_discount as $item_dc){
				$enddc = $item_dc['wt_disc_end']!='' ? $item_dc['wt_disc_end'] + 86399 : '';
				$_wtdiscount_type = $wt_disc_bo;
				if($wt_disc_bo != 'season'){
					if(($item_dc['wt_disc_start']=='' && $enddc=='') || ($item_dc['wt_disc_start']!='' && $enddc=='' && $cure_time > $item_dc['wt_disc_start']) || ($item_dc['wt_disc_start']=='' && $enddc!='' && $cure_time < $enddc) || ($item_dc['wt_disc_start']!='' && $enddc!='' && $cure_time < $enddc && $item_dc['wt_disc_start'] < $cure_time) ){
						//if(2 == 1){
							if($item_dc['wt_disc_type']=='percent' && $item_dc['wt_disc_am'] > 0){
								$disc_value = $item_dc['wt_disc_am'].'%';
							}elseif($item_dc['wt_disc_am'] > 0){
								$disc_value = wc_price($item_dc['wt_disc_am']);
							}//else{break;}
								//$_wtdiscount = $disc_value;
							//break;
							$_wtdiscount .= '<span style="display:none" data-adult="'.esc_attr($item_dc['wt_disc_number']).'" data-type="'.esc_attr($item_dc['wt_disc_type']).'" data-number="'.esc_attr($item_dc['wt_disc_am']).'">'.esc_html__('Discount','woo-tour') .' '.$disc_value.' '.esc_html__('per each adult','woo-tour').'</span>';
						//}
					}
				}elseif(isset($_metadate) && $_metadate!=''){
					$dtb = explode("_",$_metadate);
					$dtb = $dtb[0].'-'.$dtb[1].'-'.$dtb[2];
					$dtb_unix = strtotime($dtb);
					if(($item_dc['wt_disc_start']=='' && $enddc=='') || ($item_dc['wt_disc_start']!='' && $enddc=='' && $dtb_unix >= $item_dc['wt_disc_start']) || ($item_dc['wt_disc_start']=='' && $enddc!='' && $dtb_unix < $enddc) || ($item_dc['wt_disc_start']!='' && $enddc!='' && $dtb_unix < $enddc && $item_dc['wt_disc_start'] <= $dtb_unix) ){
							if($item_dc['wt_disc_type']=='percent' && $item_dc['wt_disc_am'] > 0){
								$disc_value = $item_dc['wt_disc_am'].'%';
							}elseif($item_dc['wt_disc_am'] > 0){
								$disc_value = wc_price($item_dc['wt_disc_am']);
							}else{break;}
							$_wtdiscount = '<span class="wt-dc-season" data-type="'.esc_attr($item_dc['wt_disc_type']).'" data-number="'.esc_attr($item_dc['wt_disc_am']).'">'.$disc_value.'</span>';
							break;
					}
					
				}
				
			}
		}
		$class = '';
		if($_wtdiscount!=''){
			if($_wtdiscount_type=='season'){
				$_wtdiscount = esc_html__('Discount','woo-tour') .' '.$_wtdiscount.' '.esc_html__('per each user','woo-tour');
			}else{
				$class = 'wt-dctype-adult';
				//$_wtdiscoun = '';//esc_html__('Per each adult','woo-tour');
			}
		}
		return $_wtdiscount!='' ? '<p class="crda-dcinfo '.$class.'">'.$_wtdiscount.'</p>' : '';
	}
}
if(!function_exists('exwt_quantity_html')){
	function exwt_quantity_html($name, $option, $value, $min, $max){
		$wt_type_qunatity = get_option( 'wt_type_qunatity' ) ;
		if($wt_type_qunatity=='text'){
			if($min!='' && is_numeric($min)){ $value = $min; }
			$html = '
			<div class="wt-quantity">
				<input type="button" value="-" id="wtminus_ticket" class="minus" />
				<input type="text" class="wt-qf" name="'.$name.'" value="'.$value.'" data-min="'.$min.'" data-max="'.$max.'">
				<input type="button" value="+" id="wtadd_ticket" class="plus" />
			</div>';
		}else{
			$html = '<select name="'.$name.'">'.$option.'</select>';
		}
		return $html;
	}
}
if(!function_exists('exwt_table_variation_html')){
	function exwt_table_variation_html($price, $label, $class){
		$html = '
		<table class="tour-tble">
			<tbody>
				<tr>
					<td>
						<div class="woocommerce-variation-'.esc_attr($class).'">
							'.$price.'
						</div>
					</td>
					<td>'.$label.'</td>
				</tr>
			</tbody>	
		</table>
		';
		
		return $html;
	}
}

if(!function_exists('wt_get_price')){
	function wt_get_price($id, $meta,$season_price=false,$donotcv=false){
		$price = '';
		if($meta!='' && $id!=''){
			if(get_post_meta( $id, $meta.'_sale', true )!=''){
				$price = get_post_meta( $id, $meta.'_sale', true );
			}else{
				$price = get_post_meta( $id, $meta, true );
			}
		}
		if(is_array($season_price) && !empty($season_price)){
			if($meta=='_adult'){
				$price = isset($season_price['wt_p_adult']) && $season_price['wt_p_adult']!='' && is_numeric($season_price['wt_p_adult']) ? $season_price['wt_p_adult'] : ''; 
			}else if($meta=='wt_child' || $meta=='_child_price'){
				$price = isset($season_price['wt_p_child']) && $season_price['wt_p_child']!='' && is_numeric($season_price['wt_p_child']) ? $season_price['wt_p_child'] : ''; 
			}else if($meta=='wt_infant' || $meta=='_infant_price'){
				$price = isset($season_price['wt_p_infant']) && $season_price['wt_p_infant']!='' && is_numeric($season_price['wt_p_infant']) ? $season_price['wt_p_infant'] : ''; 
			}else if($meta=='wt_ctps1' || $meta=='_ctfield1_price'){
				$price = isset($season_price['wt_p_ctps1']) && $season_price['wt_p_ctps1']!='' && is_numeric($season_price['wt_p_ctps1']) ? $season_price['wt_p_ctps1'] : ''; 
			}else if($meta=='wt_ctps2' || $meta=='_ctfield2_price'){
				$price = isset($season_price['wt_p_ctps2']) && $season_price['wt_p_ctps2']!='' && is_numeric($season_price['wt_p_ctps2']) ? $season_price['wt_p_ctps2'] : ''; 
			}
		}
		if(isset($donotcv) && $donotcv==true){
			return $price;
		}

		if(function_exists('wmc_get_price')){
			$price =  wmc_get_price($price);
		}
		$price = exwt_convert_currency($price);
		$price = exwt_apply_rounding_rules($price);
		return $price;
	}
}
function exwt_convert_currency($price, $pass=false){
	if(class_exists('WOOCS')){
		global $WOOCS;
		if($WOOCS->is_multiple_allowed){
			$price = $WOOCS->woocs_exchange_value(floatval($price));
		}
	}
	if (!class_exists('SitePress')  && !class_exists('WCML_Multi_Currency_Prices')) {
		return $price;
	}
	$currency = get_woocommerce_currency();
	if ( $currency != get_option( 'woocommerce_currency' ) || $pass==true) {
		$exchange_rates = exwt_wpmlget_exchange_rates();
		if ( isset( $exchange_rates[ $currency ] ) && is_numeric( $price ) ) {
			$price = $price * $exchange_rates[ $currency ];
	
			// exception - currencies_without_cents
			$currencies_without_cents = array('JPY', 'TWD', 'KRW', 'BIF', 'BYR', 'CLP', 'GNF', 'ISK', 'KMF', 'PYG', 'RWF', 'VUV', 'XAF', 'XOF', 'XPF');
			$currencies_without_cents = apply_filters( 'wcml_currencies_without_cents', $currencies_without_cents );
			if ( in_array( $currency, $currencies_without_cents ) ) {
				$price = exwt_wpmlround_up( $price );
			}
		} else {
			$price = 0;
		}
	
	}
	return $price;
}
function exwt_wpmlround_up( $amount ) {
	if ( $amount - floor( $amount ) < 0.5 ) {
		$amount = floor( $amount );
	} else {
		$amount = ceil( $amount );
	}

	return $amount;
}
function exwt_wpmlget_exchange_rates(){
	$exchange_rates = array(get_option('woocommerce_currency') => 1);
	if(!function_exists('get_woocommerce_currencies')){
		return;
	}
	$woo_currencies = get_woocommerce_currencies();
	$currencies = exwt_wpmlget_currencies();
	foreach($currencies as $code => $currency){
		if(!empty($woo_currencies[$code])){
			$exchange_rates[$code] = $currency['rate'];
		}
	}
	return apply_filters('wcml_exchange_rates', $exchange_rates);
}
function exwt_wpmlget_currencies( $include_default = false ){
	global $sitepress, $woocommerce_wpml,$wpdb;
	$currencies = array();
	$default_currency = get_option('woocommerce_currency');
	$currenc = $woocommerce_wpml->settings['currency_options'];
	foreach($currenc as $key => $value){
		if( $default_currency != $key || $include_default ){
			$currencies[$key] = $value;
		}
	}

	return $currencies;
}


function exwt_apply_rounding_rules( $price, $currency = false ) {
	if (!class_exists('SitePress')  && !class_exists('WCML_Multi_Currency_Prices')) {
		return $price;
	}
	global $sitepress, $woocommerce_wpml,$wpdb;

	$currency = get_woocommerce_currency();
	if(isset($woocommerce_wpml->settings['currency_options'][ $currency ])){
		$currency_options = $woocommerce_wpml->settings['currency_options'][ $currency ];
	}else{
		return $price;
	}

	if ( $currency_options['rounding'] != 'disabled' ) {

		if ( $currency_options['rounding_increment'] > 1 ) {
			$price = $price / $currency_options['rounding_increment'];
		}

		switch ( $currency_options['rounding'] ) {
			case 'up':
				$rounded_price = ceil( $price );
				break;
			case 'down':
				$rounded_price = floor( $price );
				break;
			case 'nearest':
				$rounded_price = exwt_wpmlround_up( $price );
				break;
		}

		if ( $rounded_price > 0 ) {
			$price = $rounded_price;
		}

		if ( $currency_options['rounding_increment'] > 1 ) {
			$price = $price * $currency_options['rounding_increment'];
		}

		if ( $currency_options['auto_subtract'] && $currency_options['auto_subtract'] < $price ) {
			$price = $price - $currency_options['auto_subtract'];
		}

	} else {

		// Use configured number of decimals
		if(!is_numeric($currency_options['num_decimals'])){
			$currency_options['num_decimals'] = 0;
		}
		if(!is_numeric($price)){
			$price = 0;
		}
		$price = floor( $price * pow( 10, $currency_options['num_decimals'] ) + 0.0001 ) / pow( 10, $currency_options['num_decimals'] );

	}


	return apply_filters( 'wcml_rounded_price', $price, $currency );

}
if(!function_exists('wt_passenger_field_html')){
	function wt_passenger_field_html($price_key,$max_key,$label_key,$df_skey,$dmax_key,$price_skey,$input_key,$class,$dfct=false,$min=false){
		$wt_price = get_post_meta( get_the_ID(), $price_key, true ) ;
		$wt_max = get_post_meta( get_the_ID(), $max_key, true ) ;
		$wt_label = get_post_meta( get_the_ID(), $label_key, true ) ;
		$default_label = '';
		if($price_key =='wt_child'){
			$default_label = esc_html__('Children: ','woo-tour');
		}else if($price_key =='wt_infant'){
			$default_label = esc_html__('Infant: ','woo-tour');
		}else if(isset($dfct) && $dfct!=''){
			$default_label = $dfct.': ';
		}
		if($default_label ==''){ return;}
		$wt_label = $wt_label!='' ? $wt_label.': ' : $default_label;
		$wt_label = '<span class="lb-pric">'.$wt_label.'</span>';
		if(isset($dfct) && $dfct!=''){
			$df_skey = preg_replace('/\s+/', '', $df_skey);
			if($df_skey == 'hide'){ $df_skey = 'off';}
			$wt_def_of = $df_skey ;
		}else{ $wt_def_of = get_option( $df_skey ) ; }
		if( ($wt_price!='OFF' && $wt_price!='') || ($wt_price=='' && $wt_def_of!='off') ){
			$sl_ivalue = '';//'<option value="">0</option>';
			if(isset($dfct) && $dfct!=''){
				$l = $dmax_key !='' ? $dmax_key : 5 ;
			}else{
				$l = get_option($dmax_key) !='' ? get_option($dmax_key) : 5 ;
			}
			if(is_numeric ($wt_max)){
				$l = $wt_max;
			}
			$w_min = isset($min) && $min!='' ? get_post_meta( get_the_ID(), $min, true ) : 0;
			$w_min = $w_min > 0 ? $w_min : 0;
			if($w_min > 0 ){$sl_ivalue ='';}
			//if($w_min == 0){ $w_min = 1;}
			$l = $l*1;
			for($i=$w_min; $i <= $l ; $i++){
				$sl_ivalue .= '<option value="'.$i.'">'.$i.'</option>';
			}
			$if_sale = get_post_meta( get_the_ID(), $price_skey, true );
			echo '<span class="'.$class.'">' . $wt_label . wt_addition_price_html($wt_price,1,$if_sale,true);
				echo exwt_quantity_html($input_key, $sl_ivalue,'0',$w_min,$l);
			echo '</span>';
		}
	}
}


if(!function_exists('wt_passenger_field_variable_html')){
	function wt_passenger_field_variable_html(){
	}
}

if(!function_exists('wt_custom_date_html')){
	function wt_custom_date_html(){
		if(get_option('wt_enable_customdate') !=1 ){
			return;
		}
		$wt_customdate = get_post_meta( get_the_ID(), 'wt_customdate', false ) ;
		if(is_array($wt_customdate) && !empty($wt_customdate)){
			echo '<span class="tb-meta"><i class="fa fa-calendar" aria-hidden="true"></i>';
			$i = 0;
			foreach($wt_customdate as $item){
				$i ++;
				echo '<span>';
				if($i >1){ echo ', ';}
				echo date_i18n( get_option('date_format'), $item).'</span>';
			}
			echo '</span>';
		}
	}
}

if(!function_exists('wt_safe_strtotime')){
	function wt_safe_strtotime($string,$fm)
	{
		if(!preg_match("/\d{4}/", $string, $match)) return str_replace('/', ' ', $string); //year must be in YYYY form
		if($fm != ''){
			$date_fm = $fm;
		}else{
			$date_fm =  get_option('date_format');
		}
		$year = intval($match[0]);//converting the year to integer
		$string = str_replace('/', '-', $string);
		if($year >= 1970 && $year < 2036) return date_i18n( $date_fm, strtotime($string));//the year is after 1970
		if(stristr(PHP_OS, "WIN") && !stristr(PHP_OS, "DARWIN")) //OS seems to be Windows, not Unix nor Mac
		{
			$diff = 1975 - $year;//calculating the difference between 1975 and the year
			$new_year = $year + $diff;//year + diff = new_year will be for sure > 1970
			$new_date = date_i18n( $date_fm, strtotime(str_replace($year, $new_year, $string)));//replacing the year with the new_year, try strtotime, rendering the date
			return str_replace($new_year, $year, $new_date);//returning the date with the correct year
		}
		return date_i18n( $date_fm,strtotime($string));//do normal strtotime
	}
}

//ajax search shortcode new
add_action( 'wp_ajax_wt_ajax_search', 'wt_ajax_search_result' );
add_action( 'wp_ajax_nopriv_wt_ajax_search', 'wt_ajax_search_result' );
if(!function_exists('wt_ajax_search_result')){
	function wt_ajax_search_result(){
		$page = isset($_POST['page']) ? $_POST['page'] : '';
		global $posts_per_page,$count,$layout,$idsc;
		$idsc = isset($_POST['idsc']) ? $_POST['idsc'] : '';
		$layout = isset($_POST['layout']) ? $_POST['layout'] : '';
		$posts_per_page = 3;
		$count = 999;
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => $posts_per_page,
			'post_status' => 'publish',
			's' => $_POST['key_word'],
			'ignore_sticky_posts' => 1,
		);
		$args['paged'] = $page;
		$cat = isset($_POST['cat']) && $_POST['cat']!='' ? $_POST['cat'] : '';
		$tag = isset($_POST['tag']) && $_POST['tag']!='' ? $_POST['tag'] : '';
		$location = isset($_POST['location']) && $_POST['location']!='' ? $_POST['location'] : '';
		
		if($tag!=''){
			$texo['relation'] = 'AND';
			$tags = explode(",",$tag);
			if(is_numeric($tags[0])){$field_tag = 'term_id'; }
			else{ $field_tag = 'slug'; }
			if(count($tags)>1){
				  foreach($tags as $iterm) {
					  if($iterm!=''){
					  $texo[] = array(
							  'taxonomy' => 'product_tag',
							  'field' => $field_tag,
							  'terms' => $iterm,
						  );
					  }
				  }
			  }else{
				  if(!empty($tags)){
				  $texo[] = array(
						  'taxonomy' => 'product_tag',
						  'field' => $field_tag,
						  'terms' => $tags,
				  );
				  }
			}
		}
		if($cat!=''){
			$texo['relation'] = 'AND';
			$cats = explode(",",$cat);
			if(is_numeric($cats[0])){$field = 'term_id'; }
			else{ $field = 'slug'; }
			if(count($cats)>1){
				  foreach($cats as $iterm) {
					  if($iterm!=''){
					  $texo[] = array(
							  'taxonomy' => 'product_cat',
							  'field' => $field,
							  'terms' => $iterm,
						  );
					  }
				  }
			  }else{
				  if(!empty($cats)){
					  $texo[] = array(
								  'taxonomy' => 'product_cat',
								  'field' => $field,
								  'terms' => $cats,
					  );
				  }
			}
		}
		if($location!=''){
			$texo['relation'] = 'AND';
			$locations = explode(",",$location);
			if(is_numeric($locations[0])){$field = 'term_id'; }
			else{ $field = 'slug'; }
			if(count($locations)>1){
				  foreach($locations as $iterm) {
					  if($iterm!=''){
					  $texo[] = array(
							  'taxonomy' => 'wt_location',
							  'field' => $field,
							  'terms' => $iterm,
						  );
					  }
				  }
			  }else{
				  if(!empty($locations)){
					  $texo[] = array(
								  'taxonomy' => 'wt_location',
								  'field' => $field,
								  'terms' => $locations,
					  );
				  }
			}
		}
		if(isset($texo)){
			$args += array('tax_query' => $texo);
		}
		$args = apply_filters( 'wt_ajax_search_arg', $args );
		global $the_query;
		$the_query = new WP_Query( $args );
		$it = $the_query->post_count;
		ob_start();
		wootour_template_plugin('search-ajax', true);
		$html = ob_get_clean();
		ob_end_clean();
		echo  $html;
		die;
	}
}

if(!function_exists('wpext_pagenavi')){
	function wpext_pagenavi($the_query,$idsc){
		if(function_exists('paginate_links')) {
			echo '<div class="wt-ajax-pagination" data-id="'.$idsc.'" id="pag-'.rand(10,9999).'">';
			$args = array(
				'base'         => home_url( '/%_%' ),
				'format'       => '?paged=%#%',
				'add_args'     => '',
				'show_all'     => false,
				'current' => isset($_POST['page']) && $_POST['page']!='' ? $_POST['page'] : max( 1, get_query_var('paged') ),
				'total' => $the_query->max_num_pages,
				'prev_text'    => '&larr;',
				'next_text'    => '&rarr;',
				'type'         => 'list',
				'end_size'     => 2,
				'mid_size'     => 2
			);
			$args['add_args'] = array(
				'post_type' => 'product',
				's' => isset($_POST['key_word']) ? $_POST['key_word'] : '',
				'product_cat' => $_POST['cat'],
				'product_tag' => $_POST['tag'],
				'location' => $_POST['location']
			);
			echo paginate_links($args);
		}
	}
}


//add_filter( 'woocommerce_add_to_cart_validation', 'wt_validate_add_cart_item', 9, 5 );
if(!function_exists('wt_validate_add_cart_item')){
	function wt_validate_add_cart_item( $passed, $product_id, $quantity, $variation_id = '', $variations= '' ) {
		if(isset($_POST['wt_sldate']) && $_POST['wt_sldate']!=''){
			$wt_disable_book = get_post_meta( $product_id, 'wt_disable_book', true ) ;
			if($wt_disable_book==''){
				$wt_disable_book = get_option('wt_disable_book');
			}
			$dis_uni = 0;
			if($wt_disable_book!='' && is_numeric($wt_disable_book)){
				$dis_uni = apply_filters( 'wt_disable_book_day', strtotime("+$wt_disable_book day") );
				$wt_disable_book = date_i18n('Y-m-d',$dis_uni);
			}else{
				$wt_disable_book='';
			}
			$wt_start = get_post_meta( $product_id, 'wt_start', true ) ;
			if($wt_start!='' && is_numeric($wt_start)){
				if( $wt_disable_book ==''){ $wt_disable_book = 0;}
				if($wt_start > $dis_uni && $wt_start > time()){
					$wt_disable_book = date_i18n('Y-m-d',$wt_start);
					$dis_uni = $wt_start;
				}else if($wt_start < time()){
					//$wt_disable_book='';
				}
			}
			$selectdate =  strtotime(str_replace("_", "-", $_POST['wt_sldate']));
			$selectdate = $selectdate + 86399;
			if ( $selectdate < $dis_uni ){
				$passed = false;
				$t_stopb = esc_html__('Tickets not available','exthemes');
				wc_add_notice( $t_stopb, 'error' );
			}
		}
		return $passed;

	}
}

/*------------ support Advanced Order Export --------------*/
// start date
add_filter('woe_get_order_product_fields', 'wt_departuredate', 10, 2);
function wt_departuredate($fields,$format) {
	$fields['_tourdate'] = array( 'label' => esc_html__( 'Departure date', 'woo-tour' ), 'colname' => esc_html__( 'Departure date', 'woo-tour' ), 'checked' => 1 );
	return $fields;
}
add_filter('woe_get_order_product_value__tourdate', 'wt_get_product_date_from_order', 10, 4);
function wt_get_product_date_from_order($value,$order, $item, $product) {
	$product_id = $item->get_product_id();
	$meta_std = wc_get_order_item_meta($item->get_id(),'_date');
	return $meta_std;
}
// Attendee info
add_filter('woe_get_order_product_fields', 'wt_export_attendee_info', 10, 2);
function wt_export_attendee_info($fields,$format) {
	$fields['wt_attendee'] = array( 'label' => esc_html__( 'Attendee info', 'woo-tour' ), 'colname' => esc_html__( 'Attendee info', 'woo-tour' ), 'checked' => 1 );
	return $fields;
}
add_filter('woe_get_order_product_value_wt_attendee', 'get_product_wt_attendee_from_order', 10, 4);
function get_product_wt_attendee_from_order($value,$order, $item, $product) {
	$id = $item->get_product_id();
	$order_items = $order->get_items();
	$n = 0; $find = 0;
	foreach ($order_items as $items_key => $items_value) {
		$n ++;
		if($items_value->get_id() == $item->get_id()){
			$find = 1;
			break;
		}
	}
	if($find == 0){ return;}
	$value_id = $id.'_'.$n;
	$value_id = apply_filters( 'wt_attendee_key', $value_id, $item );
	$metadata = get_post_meta($order->id,'att_info-'.$value_id, true);
	if($metadata == '' ){
		$metadata = get_post_meta($order->id,'att_info-'.$id, true);
	}
	$html='';
	if($metadata !=''){
		$metadata = explode("][",$metadata);
		if(!empty($metadata)){
			$i=0;
			ob_start();
			foreach($metadata as $item){
				$i++;
					$item = explode("||",$item);
					$f_name = isset($item[1]) && $item[1]!='' ? $item[1] : '';
					$l_name = isset($item[2]) && $item[2]!='' ? $item[2] : '';
					$bir_day = isset($item[3]) && $item[3]!='' ? $item[3] : '';
					$male = isset($item[4]) && $item[4]!='' ? $item[4] : '';
					echo $wt_duration!='' ? '<dl class="variation">'.esc_html__('Duration:','woo-tour').'&nbsp;'.$wt_duration.'</dl>' : '';
					do_action( 'wt_before_order_info', $item);
					echo '<div class="we-user-info">'.esc_html__('Attendees info','woo-tour').' ('.$i.') <br>';
					echo  $f_name!='' && $l_name!='' ? '<span><b>'.esc_html__(' Name: ','woo-tour').'&nbsp;</b>'.$f_name.' '.$l_name.'</span><br>' : '';
					echo  isset($item[0]) && $item[0]!='' ? '<span><b>'.esc_html__(' Email: ','woo-tour').'&nbsp;</b>'.$item[0].'</span><br>' : '';
					if($bir_day!=''){
						$bir_day = preg_replace('/\s+/', '/', $bir_day);
						$bir_day = wt_safe_strtotime($bir_day,'');
					}
					echo  $bir_day!='' ? '<span><b>'.esc_html__('Date of birth: ','woo-tour').'&nbsp;</b>'.$bir_day.'</span><br>' : '';
					if($male=='male'){ $male = esc_html__('Male','woo-tour');}
					else if($male=='female'){ $male = esc_html__('Female','woo-tour');}
					else if($male=='other'){ $male = esc_html__('Other','woo-tour');}
					
					echo  $male!='' ? '<span><b>'.esc_html__('Gender: ','woo-tour').'&nbsp;</b>'.$male.'</span><br>' : '';
					do_action( 'wt_after_order_info', $item);
					echo '</div>';
			}
			wp_reset_postdata();
			$html = ob_get_contents();
			ob_end_clean();
		}
	}
	return $html;
}

function wt_search_by_date( $query ) {
    if( is_search() && $query->is_main_query() && is_shop() && !is_admin() && isset($_GET['date']) && $_GET['date']!='' ){
    	$date = strtotime(str_replace('/', '-', $_GET['date']));
        global $wpdb;
        $kdate = date('Y_m_d', $date);
		//$prepare_1 = $wpdb->prepare( "SELECT post_id FROM " . $wpdb->prefix . "postmeta WHERE meta_key ='wt_customdate' and meta_value = %s", $date );

		$prepare_1 = $wpdb->prepare( "
			SELECT ID FROM " . $wpdb->prefix . "posts p, " . $wpdb->prefix . "postmeta m1, " . $wpdb->prefix . "postmeta m2
		    WHERE p.ID = m1.post_id and p.ID = m2.post_id
		    AND m1.meta_key = 'wt_customdate' AND m1.meta_value = %f
			", $date );
		$res1 = $wpdb->get_results( $prepare_1, OBJECT_K );
		if(!function_exists('array_column')){ return;}
		$res1 = array_column($res1, 'ID');
		if(empty($res1)){ $query->set('post__in', array(0)); return;}
		if(isset($_GET['people']) && is_numeric($_GET['people'])){
			$prepare_2 = $wpdb->prepare( "
				SELECT ID FROM " . $wpdb->prefix . "posts p, " . $wpdb->prefix . "postmeta m1, " . $wpdb->prefix . "postmeta m2
			    WHERE p.ID = m1.post_id and p.ID = m2.post_id
			    AND m1.meta_key = 'def_stock' AND m1.meta_value >= %1s
			    AND m2.meta_key LIKE '".$kdate."%' AND m2.meta_value >=  %2s
				", $_GET['people'],$_GET['people'] );

			$prepare_3 = $wpdb->prepare( "
				SELECT ID FROM " . $wpdb->prefix . "posts p, " . $wpdb->prefix . "postmeta m1, " . $wpdb->prefix . "postmeta m2
			    WHERE p.ID = m1.post_id and p.ID = m2.post_id
			    AND m1.meta_key = 'def_stock' AND m1.meta_value >= %d
			    AND NOT EXISTS (
	              SELECT * FROM " . $wpdb->prefix . "postmeta
	               WHERE " . $wpdb->prefix . "postmeta.meta_key LIKE '".$kdate."%'
	                AND " . $wpdb->prefix . "postmeta.post_id=p.ID
	            ) 
				", $_GET['people']);

			$prepare_4 = $wpdb->prepare( "
				SELECT ID FROM " . $wpdb->prefix . "posts p, " . $wpdb->prefix . "postmeta m1, " . $wpdb->prefix . "postmeta m2
			    WHERE p.ID = m1.post_id and p.ID = m2.post_id
			    AND NOT EXISTS (
	              SELECT * FROM " . $wpdb->prefix . "postmeta
	               WHERE " . $wpdb->prefix . "postmeta.meta_key = %s
	                AND " . $wpdb->prefix . "postmeta.post_id=p.ID
	            )
	            AND p.post_type = 'product'
				", 'def_stock');
			$res2 = $wpdb->get_results( $prepare_2, OBJECT_K );
			$res3 = $wpdb->get_results( $prepare_3, OBJECT_K );
			$res4 = $wpdb->get_results( $prepare_4, OBJECT_K );
			$res1 = json_decode(json_encode($res1), True);
			$res2 = json_decode(json_encode($res2), True);
			$res3 = json_decode(json_encode($res3), True);
			$res4 = json_decode(json_encode($res4), True);
			$m_dat = $res2 + $res3 + $res4;
			//$m_dat = array_merge($m_dat,$res4);
			//echo '<pre>';
			$m_dat = array_column($m_dat, 'ID');
			//print_r($res1);
			//print_r($m_dat);
			$result=array_intersect($m_dat,$res1);
			//$result = array_column($result, 'ID');
			//print_r($result);
			$query->set('post__in', $result);
			//exit;
		}else{
			$query->set('post__in', $res1);
		}
		
		//echo '<pre>'; print_r($query);exit;
    }
}
add_action( 'pre_get_posts', 'wt_search_by_date',999 );

add_filter( 'woocommerce_page_title', 'wt_change_title_of_search' );
function wt_change_title_of_search($page_title){
	if ( is_search() && isset($_GET['date'])  && isset($_GET['people']) && get_search_query()=='' ) {
		if($_GET['date'] !='' && $_GET['people']!='' ){
			$page_title = sprintf( __( 'Search results: &ldquo; %1$s people for the %2$s &rdquo;', 'woo-tour' ), $_GET['people'], $_GET['date']  );
		}else if(($_GET['date']) !=''){
			$page_title = sprintf( __( 'You searched a tour for the %s', 'woo-tour' ), $_GET['date']  );
		}
	}
	return $page_title;
}

// event meta shortcode for WooCommerce layout builder 
if(!function_exists('wt_tourmeta_element_shortcode')){
	function wt_tourmeta_element_shortcode(){
		global $woocommerce, $post;
		ob_start();
		wootour_template_plugin('tour-meta');
		$output_string = ob_get_contents();
		ob_end_clean();
		return $output_string;
		
	}
	add_shortcode( 'wt_tourmeta', 'wt_tourmeta_element_shortcode' );
}
if(!function_exists('wt_touraccor_element_shortcode')){
	function wt_touraccor_element_shortcode(){
		global $woocommerce, $post;
		ob_start();
		wootour_template_plugin('tour-acco');
		$output_string = ob_get_contents();
		ob_end_clean();
		return $output_string;
		
	}
	add_shortcode( 'wt_accor', 'wt_touraccor_element_shortcode' );
}

//add_filter('woocommerce_coupon_is_valid', 'exwt__coupon_is_valid', 999, 3);

function exwt__coupon_is_valid($valid, $coupon, $cart_object){
	foreach ( $cart_object->get_items() as $key => $value ) {
		print_r($value->object);
	}
	return false;
}

// reduce for pending payment
//add_action( 'woocommerce_after_order_object_save', 'exwf__reduce_ticket_pedding', 10, 2 );
function exwf__reduce_ticket_pedding( $order_id, $data_store ) {
	$order = wc_get_order( $order_id );
	if( 'pending' === $order->get_status() ) {
		do_action( 'woocommerce_reduce_order_stock', $order );
	}
}
if(!function_exists('exwt_get_current_time')){
	function exwt_get_current_time(){
		$cure_time =  strtotime("now");
		$gmt_offset = get_option('gmt_offset');
		if($gmt_offset!=''){
			$cure_time = $cure_time + ($gmt_offset*3600);
		}
		return $cure_time;
	}
}
// get single purpose
function exwt_get_layout_purpose($id){
	$exwf_mainpp = get_option('wt_slayout_purpose','tour');
	$exwf_sgpp = get_post_meta($id,'wt_layout_purpose',true);
	if($exwf_sgpp=='' || $exwf_sgpp=='def'){
		if(function_exists('wt__cat_custom_layout')){
			$we_layout_purpose = wt__cat_custom_layout($id);
			if($we_layout_purpose!=''){
				return $we_layout_purpose;
			}
		}
	    $exwf_sgpp = $exwf_mainpp;
	}
	return $exwf_sgpp;
}
if(!function_exists('exwt_show_reviews')){
    function exwt_show_reviews($id,$product=false){
        if(!isset($product) || $product==''){
            $product = wc_get_product( $id );
        }
        if(function_exists('wc_get_rating_html')){
            $rating_html = wc_get_rating_html($product->get_average_rating());
        }else{
            $rating_html = $product->get_rating_html();
        }
        $rating_count = $product->get_rating_count();
        if (  $rating_count > 0 && get_option( 'woocommerce_enable_review_rating' ) != 'no' && $rating_html){
                return  '<div class="exwf-rating woocommerce">'.$rating_html.'</div>';
        }
    }
}

function exwt_add_to_cart(){
	$time_now =  strtotime("now");
    $gmt_offset = get_option('gmt_offset');
    if($gmt_offset!=''){
        $time_now = $time_now + ($gmt_offset*3600);
    }
    $expireddate = wt_global_expireddate() ;
    if($expireddate !='' && $time_now > $expireddate){
	    return;
	}
	do_action('exwt_before_atc_form');
	woocommerce_template_single_add_to_cart();
}
add_filter( 'woocommerce_is_sold_individually', 'exwt_remove_qty_if_tour',20,2);
function exwt_remove_qty_if_tour( $individually,$product ) {
	$exwf_sgpp = exwt_get_layout_purpose($product->get_id());
	if($exwf_sgpp!='woo'){
		return true;
	}
	return $individually;
}