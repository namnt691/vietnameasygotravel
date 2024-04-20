<?php
class Widget_wootour_Search extends WP_Widget {	

	function __construct() {
    	$widget_ops = array(
			'classname'   => 'wootour-search', 
			'description' => esc_html__('Events Search ','woo-tour')
		);
    	parent::__construct('course-search-widget', esc_html__('WT - Tour Search ','woo-tour'), $widget_ops);
	}


	function widget($args, $instance) {
		ob_start();
		extract($args);
		
		$ids 			= empty($instance['ids']) ? '' : $instance['ids'];
		$title 			= empty($instance['title']) ? '' : $instance['title'];
		$title          = apply_filters('widget_title', $title);
		$cats 			= empty($instance['cats']) ? '' : $instance['cats'];

		
		$args = array(
			'hide_empty'        => false, 
			'include'           => explode(",",$cats)
		); 
		
		$terms = get_terms('wt_location', $args);
		
		echo $before_widget;
		if ( $title ) echo $before_title . $title . $after_title; ?>
        <div class="wt-search-form">
        <form role="search" method="get" id="searchform" class="wt-product-search-form" action="<?php echo home_url(); ?>/">
        	<div class="exwt-input-group">
            
            <?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){ ?>
              <div class="exwt-input-group-btn wt-search-dropdown">
                <button name="wt_location" type="button" class="exwt-btn exwt-btn-default wt-product-search-dropdown-button wt-showdrd"><span class="button-label"><?php echo esc_html__('All','woo-tour'); ?></span> <span class="fa fa-angle-down"></span></button>
                <ul class="wt-dropdown-select">
                  <li><a href="#" data-value=""><?php echo esc_html__('All','woo-tour'); ?></a></li>
                  <?php 
				  foreach ( $terms as $term ) {
				  	echo '<li><a href="#" data-value="'. $term->slug .'">'. $term->name .'</a></li>';
				  }
				  ?>
                </ul>
              </div><!-- /btn-group -->
            <?php } //if have terms ?>
            
              <input type="text" value="<?php the_search_query(); ?>" name="s" id="s" placeholder="<?php echo esc_html__('Search','woo-tour'); ?>" class="form-control" />
              <input type="hidden" name="post_type" value="product" />
              <input type="hidden" name="wt_location" class="wt-product-search-cat" value="" />
              <span class="exwt-input-group-btn">
              	<button type="submit" id="searchsubmit" class="exwt-btn exwt-btn-default wt-product-search-submit" ><i class="fa fa-search"></i></button>
              </span>
            </div>
        </form>
        </div>
        <script>
		jQuery(document).ready(function(e) {
            jQuery(".wt-search-dropdown:not(.wt-sfilter)").on('click', 'li a', function(){
			  jQuery(".wt-search-dropdown:not(.wt-sfilter) .wt-product-search-dropdown-button .button-label").html(jQuery(this).text());
			  jQuery(".wt-product-search-cat").val(jQuery(this).data('value'));
			  jQuery(".wt-product-search-dropdown").removeClass('open');
			  return false;
			});
        });
		</script>
        <?php
		echo $after_widget;
		$output_string = ob_get_contents();
		ob_end_clean();
		echo $output_string;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
        $instance['cats'] = strip_tags($new_instance['cats']);
		return $instance;
	}
	
	
	
	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$cats = isset($instance['cats']) ? esc_attr($instance['cats']) : '';?>
        
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title:','woo-tour'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
        <p>
          <label for="<?php echo $this->get_field_id('cats'); ?>"><?php esc_html_e('Included Locations (IDs. Ex: 68, 86)','woo-tour'); ?></label> 
          <textarea rows="4" cols="46" id="<?php echo $this->get_field_id('cats'); ?>" name="<?php echo $this->get_field_name('cats'); ?>"><?php echo $cats; ?></textarea>
        </p>
<?php
	}
}
// register widget
if(!function_exists('wt_search_register_widgets')){
	function wt_search_register_widgets() {
		register_widget( 'Widget_wootour_Search' );
	}
	add_action( 'widgets_init', 'wt_search_register_widgets' );
}

