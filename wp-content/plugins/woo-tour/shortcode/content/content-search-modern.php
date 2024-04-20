<?php
global $ID,$show_filters,$search_people;
wp_enqueue_style('wt-pickadate');
wp_enqueue_style('wt-pickadate-date');
wp_enqueue_style('wt-pickadate-time');
wp_enqueue_script( 'wt-pickadate' );
wp_enqueue_script( 'wt-pickadate-date');
wp_enqueue_script( 'wt-pickadate-time');
wp_enqueue_script( 'wt-pickadate-legacy');
$wt_calendar_lg = get_option('wt_calendar_lg');
if($wt_calendar_lg!=''){
	wp_enqueue_script( 'wt-pickadate-'.$wt_calendar_lg );
}
?>
<div class="wt-search-modern">
	<?php if($show_filters=='yes'){?>
		<div class="wt-srel wt-sft">
			<span><?php esc_html_e( 'Filter', 'woo-tour' ); ?><i class="fa fa-angle-down"></i></span>
		</div>
	<?php }?>
	<div class="wt-srel wt-sdt wt-departure">
		<span><?php esc_html_e( 'Choose my date', 'woo-tour' ); ?></span>
		<input type="text" value="" name="date"/>
		<?php
		$trsl_mtext = $trsl_dtext = array();
		$trsl_mtext [1]= esc_html__('January','woo-tour');
		$trsl_mtext [2]= esc_html__('February','woo-tour');
		$trsl_mtext [3]= esc_html__('March','woo-tour');
		$trsl_mtext [4]= esc_html__('April','woo-tour');
		$trsl_mtext [5]= esc_html__('May','woo-tour');
		$trsl_mtext [6]= esc_html__('June','woo-tour');
		$trsl_mtext [7]= esc_html__('July','woo-tour');
		$trsl_mtext [8]= esc_html__('August','woo-tour');
		$trsl_mtext [9]= esc_html__('September','woo-tour');
		$trsl_mtext [10]= esc_html__('October','woo-tour');
		$trsl_mtext [11]= esc_html__('November','woo-tour');
		$trsl_mtext [12]= esc_html__('December','woo-tour');
		$trsl_mtext = str_replace('\/', '/', json_encode($trsl_mtext));
		$trsl_dtext [1]= esc_html__('Sun','woo-tour');
		$trsl_dtext [2]= esc_html__('Mon','woo-tour');
		$trsl_dtext [3]= esc_html__('Tue','woo-tour');
		$trsl_dtext [4]= esc_html__('Wed','woo-tour');
		$trsl_dtext [5]= esc_html__('Thu','woo-tour');
		$trsl_dtext [6]= esc_html__('Fri','woo-tour');
		$trsl_dtext [7]= esc_html__('Sat','woo-tour');
		$trsl_dtext = str_replace('\/', '/', json_encode($trsl_dtext));
		$wt_calendar_lg = get_option('wt_calendar_lg');
		if($wt_calendar_lg!=''){
			wp_enqueue_script( 'wt-pickadate-'.$wt_calendar_lg );
		}
		echo '
		<input type="hidden" name="wt_langu" value='.$wt_calendar_lg.'>
		<input type="hidden" name="wt_daytrsl" value="'.esc_attr(str_replace(' ', '\u0020', $trsl_dtext)).'">
		<input type="hidden" name="wt_montrsl" value="'.esc_attr(str_replace(' ', '\u0020', $trsl_mtext)).'">';
		?>
	</div>
	<div class="wt-srel wt-sps">
		<span><?php esc_html_e( 'How many people ?', 'woo-tour' ); ?></span>
		<input type="number" min="0" value="" name="people"/>
	</div>
	<div class="wt-srel wt-ste">
		<span><?php echo esc_html__('I want to travel to...','woo-tour'); ?></span>
		<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" placeholder="" />
	</div>
	<div class="wt-srel wt-find-bt">
		<input type="hidden" name="post_type" value="product" />
		<button type="submit" id="searchsubmit" class="exwt-btn exwt-btn-default wt-product-search-submit" <?php if(isset($ID) && $ID!=''){?> data-id ="we-s<?php echo esc_attr($ID);?>" <?php }?> >
			<?php esc_html_e( 'Find a tour', 'woo-tour' );?>
		</button>
	</div>
</div>