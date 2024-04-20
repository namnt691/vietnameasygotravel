<?php
global $columns,$number_excerpt,$img_size;
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
$wt_eventcolor = get_post_meta( get_the_ID(), 'wt_eventcolor', true );
$bgev_color = '';
if($wt_eventcolor!=""){
	$bgev_color = 'style="background-color:'.$wt_eventcolor.'"';
}

?>
<div class="item-post-n">
	<figure class="ex-modern-blog">
		<div class="image">
			<a href="<?php the_permalink(); ?>" class="link-more">
				<?php the_post_thumbnail($img_size);?>
            </a>
            <?php
			$location = wt_taxonomy_info('wt_location','off');
			if($location != ''){
				echo '<div class="wt-location-arr"><i class="fa fa-map-marker"></i>'.$location.'</div>';
			}
			wt_onsale_check ();?>
		</div>
		<div class="grid-content">
			<figcaption>
				<h3><a href="<?php the_permalink(); ?>" class="link-more"><?php the_title();?></a></h3>
				<div class="wt-more-meta">
				<?php
					wt_custom_date_html();
					if($price!=''){
						echo  '<span><i class="fa fa-shopping-basket"></i>'.$price.'</span>';
					}
					echo wt_meta_html();
				?>
				</div>
				<div class="grid-excerpt"><?php if($number_excerpt!=0){ echo wp_trim_words(get_the_excerpt(),$number_excerpt,$more = '...'); }?></div>
				<?php do_action('exwt_grid_after_shortdes');?>
			</figcaption>
			<?php 
			$off_ssocial = get_option('wt_ssocial');
			if($off_ssocial!='off'){?>
				<div class="ex-social-share" <?php if($columns!=1){ echo $bgev_color;}?> id="ex-social-<?php echo get_the_ID();?>">
					<?php 
					if($columns==1 && $wt_eventcolor!=''){
						echo '<style type="text/css" scoped>
						.wt-grid-shortcode.wt-grid-column-1 figure.ex-modern-blog .ex-social-share#ex-social-'.get_the_ID().' ul li a{ background-color:'.$wt_eventcolor.'}
						</style>';
					}
					echo wt_social_share();?>
				</div>
			<?php }?>
		</div>
	</figure>    
</div>