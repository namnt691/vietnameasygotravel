<?php
class wt_Latest_Events_Widget extends WP_Widget {	

	function __construct() {
    	$widget_ops = array(
			'classname'   => 'wt-latest-tours-widget', 
			'description' => esc_html__('Latest Events','woo-tour')
		);
    	parent::__construct('wt-latest-events', esc_html__('WT - Latest Tour ','woo-tour'), $widget_ops);
	}


	function widget($args, $instance) {
		//ob_start();
		extract($args);
		
		$ids 			= empty($instance['ids']) ? '' : $instance['ids'];
		$title 			= empty($instance['title']) ? '' : $instance['title'];
		$title          = apply_filters('widget_title', $title);
		$cats 			= empty($instance['cats']) ? '' : $instance['cats'];
		$tags 			= empty($instance['tags']) ? '' : $instance['tags'];
		$number 		= empty($instance['number']) ? 5 : $instance['number'];
		$sort_by 		= empty($instance['sort_by']) ? '' : $instance['sort_by'];
		$order 		= empty($instance['order']) ? '' : $instance['order'];
		$style 		= empty($instance['style']) ? '' : $instance['style'];
		
		$args = woo_tour_query('product', $number, $order, $sort_by, $cats, $tags, $ids,'');
		$the_query = new WP_Query( $args );
		$html = $before_widget;
		$html .='<div class="wt-latest-event '.$style.'">';
		if ( $title ) $html .= $before_title . $title . $after_title; 
		if($the_query->have_posts()):
			while($the_query->have_posts()): $the_query->the_post();
				$wt_eventcolor = get_post_meta( get_the_ID(), 'wt_eventcolor', true );
				$bgev_color = '';
				if($wt_eventcolor!=""){
					$bgev_color = 'style="background-color:'.$wt_eventcolor.'"';
				}
				global $product;	
				$type = $product->get_type();
				$price ='';
				if($type=='variable'){
					$price = wt_variable_price_html();
				}else{
					if ( $price_html = $product->get_price_html() ) :
						$price = $price_html; 
					endif; 	
				}

				$html .='<div class="item">';
					if($style=='modern'){
						
					}else{
						if(has_post_thumbnail(get_the_ID())){
							$html .='<div class="thumb item-thumbnail">
								<a href="'.get_permalink(get_the_ID()).'" title="'.the_title_attribute('echo=0').'">
									<div class="item-thumbnail">
										'.get_the_post_thumbnail(get_the_ID(),'wethumb_85x85').'
										<span class="bg-overlay"></span>';
										if($price!=''){
											$html .='<span class="item-evprice" '.$bgev_color.'>'.$price.'</span>';
										}
										$html .='
									</div>
								</a>
							</div>';
						}
					}
					$html .='<div class="tour-details item-content">';
						if($style=='modern'){
							$bg_style ='';
							$image_src = wp_get_attachment_image_src( get_post_thumbnail_id(),'wethumb_460x307' );
							$bg_style = 'style="background-image:url('.esc_url($image_src[0]).');"';
							$html .='<div class="tour-bgimg" '.$bg_style.'><div class="bg-gra">';
						}
						$html .='
						<h3><a href="'.get_permalink(get_the_ID()).'" title="'.the_title_attribute('echo=0').'" class="main-color-1-hover">'.the_title_attribute('echo=0').'</a></h3>';
						global $_hide_text;
						$_hide_text = 1;
						if($style=='modern' && $price!=''){
							$html .='<span class="item-evdate">'.$price.'</span>';
						}
						$location = wt_taxonomy_info('wt_location','off');
						if($location != ''){
							$html .= '<div class="wt-location-arr"><i class="fa fa-map-marker"></i>'.$location.'</div>';
						}
						if($style=='modern'){
							$html .='</div></div>';
						}
						$html .='
					</div>';
				$html .='<div class="clearfix"></div></div>';
			endwhile;
		endif;
		$html .='</div>';
		$html .= $after_widget;
		echo $html;
		wp_reset_postdata();
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['ids'] = strip_tags($new_instance['ids']);
		$instance['sort_by'] = esc_attr($new_instance['sort_by']);
		$instance['order'] = esc_attr($new_instance['order']);
		$instance['style'] = esc_attr($new_instance['style']);
		$instance['tags'] = strip_tags($new_instance['tags']);
        $instance['cats'] = strip_tags($new_instance['cats']);
		$instance['number'] = absint($new_instance['number']);
		return $instance;
	}
	
	
	
	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$ids = isset($instance['ids']) ? esc_attr($instance['ids']) : '';
		$tags = isset($instance['tags']) ? esc_attr($instance['tags']) : '';
		$cats = isset($instance['cats']) ? esc_attr($instance['cats']) : '';
		$sort_by = isset($instance['sort_by']) ? esc_attr($instance['sort_by']) : '';
		$order = isset($instance['order']) ? esc_attr($instance['order']) : '';
		$style = isset($instance['style']) ? esc_attr($instance['style']) : '';
		$number = isset($instance['number']) ? absint($instance['number']) : 5;?>
        
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title:','woo-tour'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id("style"); ?>">
            <?php esc_html_e('Style','woo-tour');	 ?>:
            <select id="<?php echo $this->get_field_id("style"); ?>" name="<?php echo $this->get_field_name("style"); ?>">
                <option value="classic"<?php selected( $style, "classic" ); ?>><?php esc_html_e('Classic','woo-tour');?></option>
                <option value="modern"<?php selected( $style, "modern" ); ?>><?php esc_html_e('Modern','woo-tour');?></option>
            </select>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('ids'); ?>"><?php esc_html_e('ID list show:','woo-tour'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('ids'); ?>" name="<?php echo $this->get_field_name('ids'); ?>" type="text" value="<?php echo $ids; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tags'); ?>"><?php esc_html_e('Tags:','woo-tour'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>" type="text" value="<?php echo $tags; ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('cats'); ?>"><?php esc_html_e('Categories : (ID or Slug. Ex: 1, 2)','woo-tour'); ?></label> 
          <textarea rows="4" cols="46" id="<?php echo $this->get_field_id('cats'); ?>" name="<?php echo $this->get_field_name('cats'); ?>"><?php echo $cats; ?></textarea>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php esc_html_e('Number:','woo-tour'); ?></label>
            <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id("sort_by"); ?>">
            <?php esc_html_e('Order by','woo-tour');	 ?>:
            <select id="<?php echo $this->get_field_id("sort_by"); ?>" name="<?php echo $this->get_field_name("sort_by"); ?>">
                <option value="date"<?php selected( $sort_by, "date" ); ?>><?php esc_html_e('Latest','woo-tour');?></option>
                <option value="unexpired"<?php selected( $sort_by, "unexpired" ); ?>><?php esc_html_e('Unexpired','woo-tour');?></option>
                <option value="has_expired"<?php selected( $sort_by, "has_expired" ); ?>><?php esc_html_e('Has expired','woo-tour');?></option>
                <option value="title"<?php selected( $sort_by, "title" ); ?>><?php esc_html_e('Title','woo-tour');?></option>
                <option value="post__in"<?php selected( $sort_by, "post__in" ); ?>><?php esc_html_e('Post__in','woo-tour');?></option>
                <option value="rand"<?php selected( $sort_by, "rand" ); ?>><?php esc_html_e('Random','woo-tour');?></option>
            </select>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id("order"); ?>">
            <?php esc_html_e('Order','woo-tour');	 ?>:
            <select id="<?php echo $this->get_field_id("order"); ?>" name="<?php echo $this->get_field_name("order"); ?>">
                <option value="DESC"<?php selected( $order, "DESC" ); ?>><?php esc_html_e('DESC','woo-tour');?></option>
                <option value="ASC"<?php selected( $order, "ASC" ); ?>><?php esc_html_e('ASC','woo-tour');?></option>
            </select>
            </label>
        </p>
	<?php
	}
}
// register widget
if(!function_exists('wt_latest_tour_register_widgets')){
	function wt_latest_tour_register_widgets() {
		register_widget( 'wt_Latest_Events_Widget' );
	}
	add_action( 'widgets_init', 'wt_latest_tour_register_widgets' );
}