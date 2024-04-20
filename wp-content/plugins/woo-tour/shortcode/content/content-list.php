<?php
global $img_size,$phone,$quotes_link;
global $product;	
$type = $product->get_type();
$price ='';
if($type=='variable'){
	$price = wc_price($product->get_variation_price('min'));
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
$id= get_the_ID();
?>
<div class="item-post-n">
	<h3><a href="<?php echo get_permalink( $id ); ?>" class="link-more"><?php the_title();?></a></h3>
    <figure class="ex-modern-blog">
		<div class="image">
			<a href="<?php echo get_permalink( $id ); ?>" class="link-more">
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
				<div class="wt-more-meta">
				<?php
					if($price!=''){
						if( $type=="variable"){
							echo '<span class="wt-pr-la">'.esc_html__('Starting From','woo-tour').'</span>';
						}
						echo  '<span><i class="fa fa-shopping-basket"></i>'.$price.'</span>';
					}
                    $exwf_sgpp = exwt_get_layout_purpose($product->get_id());
                    if($exwf_sgpp!='woo'){
                        echo wt_meta_html();
                        if($price!=''){
        					echo '<span class="wt-pr-la wt-ed-la">'.esc_html__('Per person','woo-tour').'</span>';
                        }
                    }
				?>
				</div>
				<div class="grid-excerpt">
                    <?php wt_custom_date_html();?>
                    <?php the_excerpt();?></div>
                <?php do_action('exwt_list_after_shortdes');?>
			</figcaption>
            <?php 
            $off_ssocial = get_option('wt_ssocial');
            if($off_ssocial!='off'){?>
    			<div class="ex-social-share" id="ex-social-<?php echo get_the_ID();?>">
    				<?php 
    				echo wt_social_share();?>
    			</div>
            <?php }?>
		</div>
	</figure>
    <div class="wt-ext-info">
	<?php
    $wt_accom_service = get_post_meta( $id, 'wt_accom_service', false );
    ?>
        <div class="woo-tour-accompanied">
        	<div class="exwt-row">
                <div class="exwt-col8">
                    <?php if(!empty($wt_accom_service)){?>
                    <div class="wt-sche-detail tour-service">
                        <?php 
                        $i = 0;
                        foreach($wt_accom_service as $item){
                            $i++ ?>
                                <span><?php echo $item; ?></span>
                                <?php 
                            if($i%5==0 && count($wt_accom_service)!=$i){
                                ?>
                                </div>
                                <div class="wt-sche-detail tour-service">
                                <?php
                            }
                        }?>
                    </div>
                    <?php } ?>
                </div>
                <div class="exwt-col4">
                    <?php if( $quotes_link!=''){?>
                        <a class="exwt-btn we-button wt-getct"  href="<?php echo esc_url($quotes_link);?>">
                            <?php echo esc_html__('Customise & Get Quote','woo-tour');?>
                        </a>
                    <?php }?>
                    <a class="exwt-btn we-button" <?php echo $bgev_color;?> href="<?php echo get_permalink( $id ); ?>">
                        <?php echo esc_html__('View Details','woo-tour');?>
                    </a>
                    <?php if( $phone!=''){?>
                    	<span><?php echo esc_html__('Prefer to phone?','woo-tour').'<strong> '.$phone.'</strong>';?></span>
                    <?php }?>
                </div>
            </div>
        </div>
   	</div>
</div>