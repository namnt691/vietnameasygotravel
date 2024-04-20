<?php
function parse_wt_search_func($atts, $content){
	if(is_admin()){ return;}
	global $ID,$show_filters,$search_people;
	$ID = isset($atts['ID']) ? $atts['ID'] : rand(10,9999);
	$search_style =  isset($atts['search_style']) ? $atts['search_style'] :'';
	$location =  isset($atts['location']) ? $atts['location'] :'';
	$show_filters =  isset($atts['show_filters']) ? $atts['show_filters'] :'';
	$search_people =  isset($atts['search_people']) ? $atts['search_people'] :'';
	$show_location =  isset($atts['show_location']) ? $atts['show_location'] :'';
	$cats =  isset($atts['cats']) ? $atts['cats'] :'';
	$tags =  isset($atts['tags']) ? $atts['tags'] :'';
	$search_ajax =  isset($atts['search_ajax']) ? $atts['search_ajax'] :'';
	$search_layout =  isset($atts['search_layout']) ? $atts['search_layout'] :'';
	$result_showin =  isset($atts['result_showin']) && $atts['result_showin']!='' ? $atts['result_showin'] :'.wt-ajax-result';
	$args = array(
		'hide_empty'        => true, 
		'include'           => explode(",",$location)
	); 
	$terms = get_terms('wt_location', $args);
	ob_start();
	?>
	<div class="we-search-container we-s<?php echo esc_attr($ID);?>" data-id ="we-s<?php echo esc_attr($ID);?>">
    	<div class="we-loading">
            <div class="wpex-spinner">
                <div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div>
            </div>
        </div>
		<div class="wt-search-form wt-search-shortcode <?php if($search_ajax=='1'){?> we-ajax-search<?php }?>" id="we-s<?php echo esc_attr($ID);?>">
			<input type="hidden"  name="ajax_url" value="<?php echo esc_url(admin_url( 'admin-ajax.php' ));?>">
            <input type="hidden"  name="result_showin" value="<?php echo esc_attr($result_showin);?>">
            <input type="hidden"  name="search_layout" value="<?php echo esc_attr($search_layout);?>">
            <input type="hidden"  name="search_id" value="we-s<?php echo esc_attr($ID);?>">
	        <form role="search" method="get" id="searchform" class="wt-product-search-form" action="<?php echo home_url(); ?>/">
	        	<?php if($search_style!='modern'){?>
		        	<div class="exwt-input-group">
		            <?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){ ?>
		              <div class="exwt-input-group-btn wt-search-dropdown">
		                <button name="wt_location" type="button" class="exwt-btn exwt-btn-default wt-product-search-dropdown-button wt-showdrd"><span class="button-label"><?php echo $show_filters=='yes' ? esc_html__('Filter','woo-tour') : esc_html__('Locations','woo-tour'); ?></span> <span class="fa fa-angle-down"></span></button>
		                <div class="wt-dropdown-select">
		                	<?php if($show_filters=='yes'){ wt_search_filters($cats,$tags,$location=''); }
							if($show_location!='no'){?>
								<div class="exwt-row">
								<?php 
								$i = 0;
								$nb_tern = count($terms);
								foreach ( $terms as $term ) {
									$i++;
									$tax_img = '';
									if(function_exists('z_taxonomy_image_url')){ $tax_img = z_taxonomy_image_url($term->term_id);}
									if($tax_img==''){ 
										$img_id = get_option('id_image_' . $term->term_id);
										if($img_id!=''){
											$get_img = wp_get_attachment_image_src($img_id,'wethumb_85x85');
											if(isset($get_img[0])){
												$tax_img = $get_img[0];
											}
										}
									}
									$img ='';
									if($tax_img!=''){
										$img = '<img src="'.esc_url($tax_img).'" alt="'.esc_attr($term->name).'">';
									}
									if ((!function_exists('version_compare')) || version_compare(phpversion(), '5.4', '<')) {
										$tour = $term->count > 1 ? sprintf(esc_html__('%d Tours', 'woo-tour'), $term->count) : sprintf(esc_html__('%d Tour', 'woo-tour'), $term->count);
									}else{
										$tour = get_term_post_count('wt_location',$term->term_id);
										$tour = sprintf(esc_html__('%d Tours', 'woo-tour'), $tour);
									}
									echo '<div class="exwt-col4">
										<a href="'. esc_url( get_term_link( $term ) ) .'" data-value="'. $term->slug .'">';
											echo $img!='' ? '<span class="loc-image">'.$img.'</span>' : '';
											echo '
											<span class="loc-details">
												<h3>'. $term->name .'</h3>
												<span>'. $tour .'</span>
											</span>
										</a>
									</div>';
									if($i%3== 0 || $i == $nb_tern){
										echo '</div><div class="exwt-row">';
									}
								}?>
								</div>
								<?php 
							}
							?>
		                </div>
		              </div><!-- /btn-group -->
		            <?php } //if have terms ?>
		            
		              <input type="text" value="<?php the_search_query(); ?>" name="s" id="s" placeholder="<?php echo esc_html__('I want to travel to...','woo-tour'); ?>" class="form-control" />
		              <input type="hidden" name="post_type" value="product" />
		              <span class="exwt-input-group-btn">
		              	<button type="submit" id="searchsubmit" class="exwt-btn exwt-btn-default wt-product-search-submit" <?php if(isset($ID) && $ID!=''){?> data-id ="we-s<?php echo esc_attr($ID);?>" <?php }?> ><i class="fa fa-search"></i></button>
		              </span>
		            </div>
		        <?php }else{
		        	wootour_template_plugin('search-modern', true);
	        	}?>
	        </form>
	    </div>
	    <?php 
	    if($search_ajax=='1'){
			echo '<div class="wt-ajax-result"></div>';
		}
	    ?>
	</div>
	<?php
	wp_reset_postdata();
	$output_string = ob_get_contents();
	ob_end_clean();
	return $output_string;

}

if(!function_exists('wt_search_filters')){
	function wt_search_filters($cat_include, $tag_include, $location_include){
		$column = 3;
		if($cat_include=='hide'){
			$column = $column -1;
		}
		if($tag_include=='hide'){
			$column = $column -1;
		}
		if($location_include=='hide'){
			$column = $column -1;
		}
		if($column=='3'){ $class = 'exwt-col4';}elseif($column=='2'){$class = 'exwt-col6';}
		elseif($column=='1'){$class = 'exwt-col12';}
		$all_text = esc_html__('All','woo-tour');?>
		<div class="wt-filter-expand <?php echo esc_attr('we-column-'.$column)?> exwt-row">
			<?php 
			if($cat_include!='hide'){
				$args = array( 'hide_empty' => false ); 
				if($cat_include!=''){
					$cat_include = explode(",", $cat_include);
					if(is_numeric($cat_include[0])){
						$args['include'] = $cat_include;
						$args['orderby'] = 'include';
					}else{
						$args['slug'] = $cat_include;
						$args['orderby'] = 'slug__in';
					}
				}
				$terms = get_terms('product_cat', $args);
				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){ ?>
					<div class="wt-filter-cat <?php echo esc_attr($class);?>">
						<span class=""><?php echo esc_html__('Category','woo-tour');?></span>
                        <select name="product_cat">
                            <option value=""><?php echo esc_html($all_text);?></option>
                            <?php 
                              foreach ( $terms as $term ) {
                              	$selected = '';
								if((isset($_GET['product_cat']) && $_GET['product_cat'] == $term->slug)){
									$selected ='selected';
								}
                                echo '<option value="'. $term->slug .'" '.$selected.'>'. $term->name .'</option>';
                              }?>
                        </select>
					</div>
			<?php } 
			}
			if($tag_include!='hide'){
				$args = array( 'hide_empty' => false ); 
				if($tag_include!=''){
					$tag_include = explode(",", $tag_include);
					if(is_numeric($tag_include[0])){
						$args['include'] = $tag_include;
						$args['orderby'] = 'include';
					}else{
						$args['slug'] = $tag_include;
						$args['orderby'] = 'slug__in';
					}
				}
				$terms = get_terms('product_tag', $args);
				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){ ?>
					<div class="we-filter-tag <?php echo esc_attr($class);?>">
						<span class=""><?php echo esc_html__('Tags','woo-tour');?></span>
                        <select name="product_tag">
                            <option value=""><?php echo esc_html($all_text);?></option>
                            <?php 
                              foreach ( $terms as $term ) {
                              	$selected = '';
								if((isset($_GET['product_tag']) && $_GET['product_tag'] == $term->slug)){
									$selected ='selected';
								}
                                echo '<option value="'. $term->slug .'" '.$selected.'>'. $term->name .'</option>';
                              }
                              ?>
                        </select>
					</div>
			<?php } 
			} 
			if($location_include!='hide'){
				$args = array( 'hide_empty' => false ); 
				if($location_include!=''){
					$location_include = explode(",", $location_include);
					if(is_numeric($location_include[0])){
						$args['include'] = $location_include;
						$args['orderby'] = 'include';
					}else{
						$args['slug'] = $location_include;
						$args['orderby'] = 'slug__in';
					}
				}
				$terms = get_terms('wt_location', $args);
				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){ ?>
					<div class="we-filter-tag <?php echo esc_attr($class);?>">
						<span class=""><?php echo esc_html__('Location','woo-tour');?></span>
                        <select name="wt_location">
                            <option value=""><?php echo esc_html($all_text);?></option>
                            <?php 
                              foreach ( $terms as $term ) {
                              	$selected = '';
								if((isset($_GET['wt_location']) && $_GET['wt_location'] == $term->slug)){
									$selected ='selected';
								}
                                echo '<option value="'. $term->slug .'" '.$selected.'>'. $term->name .'</option>';
                              }
                              ?>
                        </select>
					</div>
			<?php } 
			}?>
        </div>
	<?php
    }
}
if(!function_exists('get_term_post_count')){
	function get_term_post_count( $taxonomy = 'category', $term = '', $args = array() )
	{
		// Lets first validate and sanitize our parameters, on failure, just return false
		if ( !$term )
			return false;
	
		if ( $term !== 'all' ) {
			if ( !is_array( $term ) ) {
				$term = filter_var(       $term, FILTER_VALIDATE_INT );
			} else {
				$term = filter_var_array( $term, FILTER_VALIDATE_INT );
			}
		}
	
		if ( $taxonomy !== 'category' ) {
			$taxonomy = filter_var( $taxonomy, FILTER_SANITIZE_STRING );
			if ( !taxonomy_exists( $taxonomy ) )
				return false;
		}
	
		if ( $args ) {
			if ( !is_array ) 
				return false;
		}
	
		// Now that we have come this far, lets continue and wrap it up
		// Set our default args
		$defaults = array(
			'posts_per_page' => 1,
			'fields'         => 'ids'
		);
	
		if ( $term !== 'all' ) {
			$defaults['tax_query'] = [
				[
					'taxonomy' => $taxonomy,
					'terms'    => $term
				]
			];
		}
		$combined_args = wp_parse_args( $args, $defaults );
		$q = new WP_Query( $combined_args );
	
		// Return the post count
		return $q->found_posts;
	}
}
add_shortcode( 'wt_search', 'parse_wt_search_func' );
add_action( 'after_setup_theme', 'wt_search_reg_vc' );
function wt_search_reg_vc(){
	if(function_exists('vc_map')){
	vc_map( array(
	   "name" => esc_html__("WooTours - Search", "woo-tour"),
	   "base" => "wt_search",
	   "class" => "",
	   "icon" => "icon-search",
	   "controls" => "full",
	   "category" => esc_html__('Wootours','woo-tour'),
	   "params" => array(
			array(
				"admin_label" => true,
				"type" => "textfield",
				"heading" => esc_html__("Included Locations", "woo-tour"),
				"param_name" => "location",
				"value" => "",
				"description" => esc_html__("List of location ID (or slug), separated by a comma, Ex: 13,14", "woo-tour"),
			),
			array(
				"admin_label" => true,
				"type" => "textfield",
				"heading" => esc_html__("Included Cats in filter", "woo-tour"),
				"param_name" => "cats",
				"value" => "",
				"description" => esc_html__("List of Cats ID (or slug), separated by a comma, Ex: 13,14", "woo-tour"),
			),
			array(
				"admin_label" => true,
				"type" => "textfield",
				"heading" => esc_html__("Included Tags in filter", "woo-tour"),
				"param_name" => "tags",
				"value" => "",
				"description" => esc_html__("List of Tags ID (or slug), separated by a comma, Ex: 13,14", "woo-tour"),
			),
			array(
				"admin_label" => true,
				"type" => "dropdown",
				"class" => "",
				"heading" => esc_html__("Show filters", 'woo-tour'),
				"param_name" => "show_filters",
				"value" => array(
					esc_html__('No', 'woo-tour') => 'no',
				esc_html__('Yes', 'woo-tour') => 'yes',
				),
				"description" => ''
			),
			array(
				"admin_label" => true,
				"type" => "dropdown",
				"class" => "",
				"heading" => esc_html__("Show list of locations", 'woo-tour'),
				"param_name" => "show_location",
				"value" => array(
					esc_html__('Yes', 'woo-tour') => 'yes',
				esc_html__('No', 'woo-tour') => 'no',
				),
				"description" => ''
			),
			array(
				"admin_label" => true,
				"type" => "dropdown",
				"class" => "",
				"heading" => esc_html__("Search Ajax", 'exthemes'),
				"param_name" => "search_ajax",
				"value" => array(
					esc_html__('No', 'exthemes') => '',
				esc_html__('Yes', 'exthemes') => '1',
				),
				"description" => ''
			),
			array(
				"admin_label" => true,
				"type" => "dropdown",
				"class" => "",
				"heading" => esc_html__("Search Ajax layout", 'exthemes'),
				"param_name" => "search_layout",
				"value" => array(
					esc_html__('Table', 'exthemes') => '',
				esc_html__('Grid', 'exthemes') => 'grid',
				),
				"description" => esc_html__("Show search ajax result in table or grid layout", 'exthemes'),
			),
		  
			array(
				"admin_label" => true,
				"type" => "textfield",
				"heading" => esc_html__("Search ajax result show in", "exthemes"),
				"param_name" => "result_showin",
				"value" => "",
				"description" => esc_html__("Enter class or id of element you want to show search result, default show in search shortcode element", "exthemes"),
			),
	   )
	));
	}
}