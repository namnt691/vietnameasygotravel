<?php
global $woocommerce, $post,$wt_main_purpose; 
?>
<div class="wt-content-custom exwt-col12">
    <div class="wt-info-top">
        <div class="tour-details">
        	<?php 
			if($wt_main_purpose=='woo'){
				the_post_thumbnail('full');
            }else{
				$image_src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID),'full' );
				$bg_img = '';
				if(isset($image_src[0])){
					//$bg_img = ' background:url('.esc_url($image_src[0]).');';
				}?>
                <div class="tour-info-left" style=" <?php echo $bg_img;?> background-size: cover; background-position: center center; ">
                    <h1 class="ev-title">
                        <?php the_title();?>
                    </h1>
                    <?php 
                    global $product;	
                    $type = $product->get_type();
                    $price ='';

                    // if(function_exists('wc_get_rating_html')){
                    //     $rating_html = wc_get_rating_html($product->get_average_rating());
                    // }else{
                    //     $rating_html = $product->get_rating_html();
                    // }
                    // if ( get_option( 'woocommerce_enable_review_rating' ) != 'no' && $rating_html){
                    //         echo '<div class="wt-rating">
                    //             <div class="woocommerce-wt-rating woocommerce">
                    //                 '.$rating_html.'
                    //             </div>
                    //         </div>';
                    // }

                    if($type=='variable'){
                        $price = wt_variable_price_html();
                    }else{
                          if ( $price_html = $product->get_price_html() ) :
                              $price = $price_html; 
                          endif; 	
                    }?>
                    <h3 class="tour-price"><?php echo $price;?></h3>
                    <?php

                    ?>
                    <div class="button-scroll exwt-btn exwt-btn-default"><?php 
                    esc_html_e('Book Now','woo-tour');
                    ?>
                    </div>
                </div>
                <div class="tour-info-right">
                    <?php wootour_template_plugin('tour-meta'); ?>
                </div>
            <?php }?> 
    	</div>
	</div>
    <div class="content-dt"><?php echo apply_filters('the_content',get_the_content($post->ID));?></div>
	<style type="text/css">.woocommerce .wt-main.layout-2 .images{ display:none !important}</style>
</div>
<div class="clear"></div>