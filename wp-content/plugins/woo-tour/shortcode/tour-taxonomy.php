<?php
function parse_wt_taxonomy_func($atts, $content){
	if(is_admin()){ return;}
	$style =  isset($atts['style']) ? $atts['style'] :'1';
	$taxonomy =  isset($atts['taxonomy']) && $atts['taxonomy']!='' ? $atts['taxonomy'] :'wt_location';
	$count =  isset($atts['count']) && $atts['count']!='' ? $atts['count'] :'all';
	$term_ids =  isset($atts['term_ids']) ? $atts['term_ids'] :'';
	$img_size =  isset($atts['img_size']) ? $atts['img_size'] :'full';
	$column =  isset($atts['column']) ? $atts['column'] :'3';
	$order =  isset($atts['order']) && $atts['order']!='' ? $atts['order'] :'';
	$orderby =  isset($atts['orderby']) && $atts['orderby']!='' ? $atts['orderby'] :'';
	$number_des =  isset($atts['number_des'])&& $atts['number_des']!='' ? $atts['number_des'] : '';
	ob_start();
	$term_ids = explode(",",$term_ids);
	$args = array(
		'orderby'			=> $orderby,
		'include'           => $term_ids,
		'number'			=> $count,
		'order'			=> $order,
	); 
	$terms = get_terms($taxonomy, $args);
	$nb_tern = count($terms);
	if($nb_tern > 0){
		?>
		<div class="wt-taxonomy-list wt-grid-shortcode wt-grid-column-<?php esc_attr_e($column);?> wt-taxstyle-<?php esc_attr_e($style);?>">
			<div class="ct-grid">
				<div class="grid-container">
				<?php 
				$i = 0;
				$nb_tern = count($terms);
				foreach ( $terms as $term ) {
					$i++;
					$tax_img = '';
					if(function_exists('z_taxonomy_image_url')){ $tax_img = z_taxonomy_image_url($term->term_id);}
					if($tax_img==''){ 
						$img_id = get_option('id_image_' . $term->term_id);
						if($taxonomy =='product_cat'){
							$img_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
						}
						if($img_id!=''){
							$get_img = wp_get_attachment_image_src($img_id,$img_size);
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
						$tour = get_term_post_count($taxonomy,$term->term_id);
						$tour = $tour > 1 ? sprintf(esc_html__('%d Tours', 'woo-tour'), $tour) : sprintf(esc_html__('%d Tour', 'woo-tour'), $tour);
					}
					$cl_h = '';
					if($img==''){
						$cl_h = 'wt-no-img';
					}
					echo '<div class="item-post-n">
						<div class="wt-tax-content '.$cl_h.'">
						<a href="'. esc_url( get_term_link( $term ) ) .'" data-value="'. $term->slug .'">';
							
							echo $img!='' ? '<span class="loc-image">'.$img.'</span>' : '';
							echo '
							<span class="loc-details">
								<h3>'. $term->name .'</h3>
								<span>'. $tour .'</span>';
								if($number_des>0){ 
									echo $term->description !='' ? '<p>'.wp_trim_words($term->description,$number_des,$more = '...').'</p>' : '';
								}else if($number_des==''){
									echo $term->description !='' ? '<p>'. $term->description .'</p>' : '';
								}
								echo '
							</span>
						</a>
						</div>
					</div>';
				}?>
				</div>
			</div>
		</div>
		<?php 
	}
	$output_string = ob_get_contents();
	ob_end_clean();
	return $output_string;

}
add_shortcode( 'wt_taxonomy', 'parse_wt_taxonomy_func' );
add_action( 'after_setup_theme', 'wt_reg_taxonomy_vc' );
function wt_reg_taxonomy_vc(){
	if(function_exists('vc_map')){
	vc_map( array(
	   "name" => esc_html__("WooTours - Taxonomy", "woo-tour"),
	   "base" => "wt_taxonomy",
	   "class" => "",
	   "icon" => "icon-list",
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
					esc_html__('Style 1', 'woo-tour') => '1',
					esc_html__('Style 2', 'woo-tour') => '2',
				),
				"description" => ''
			),
			array(
				"admin_label" => true,
				"type" => "textfield",
				"heading" => esc_html__("Taxonomy", "woo-tour"),
				"param_name" => "taxonomy",
				"value" => "",
				"description" => esc_html__("Enter name of taxonomy, default: wt_location", "woo-tour"),
			),
			array(
				"admin_label" => true,
				"type" => "textfield",
				"heading" => esc_html__("Term ids", "woo-tour"),
				"param_name" => "term_ids",
				"value" => "",
				"description" => esc_html__("List of term ID, separated by a comma, Ex: 13,14", "woo-tour"),
			),
			array(
				"admin_label" => true,
				"type" => "textfield",
				"heading" => esc_html__("Count", "woo-tour"),
				"param_name" => "count",
				"value" => "",
				"description" => esc_html__("Number of term, default all", 'woo-tour'),
			),
			array(
				"admin_label" => true,
				"type" => "dropdown",
				"class" => "",
				"heading" => esc_html__("Order", 'woo-tour'),
				"param_name" => "order",
				"value" => array(
					esc_html__('ASC', 'woo-tour') => 'ASC',
					esc_html__('DESC', 'woo-tour') => 'DESC',
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
				 	esc_html__('Name', 'woo-tour') => 'name',
					esc_html__('Slug', 'woo-tour') => 'slug',
				 	esc_html__('Term id', 'woo-tour') => 'term_id',
					esc_html__('count', 'woo-tour') => 'count',
				 ),
				 "description" => ''
			),
			array(
				 "admin_label" => true,
				 "type" => "dropdown",
				 "class" => "",
				 "heading" => esc_html__("Column", 'woo-tour'),
				 "param_name" => "column",
				 "value" => array(
				 	esc_html__('1', 'woo-tour') => '1',
					esc_html__('2', 'woo-tour') => '2',
					esc_html__('3', 'woo-tour') => '3',
					esc_html__('4', 'woo-tour') => '4',
					esc_html__('5', 'woo-tour') => '5',
				 ),
				 "description" => ''
			),
	   )
	));
	}
}