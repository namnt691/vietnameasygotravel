<?php
global $wt_sidebar, $woocommerce, $product,$exwt_sgct,$layout;
?>
<div class="exwt-info-sp exwt-layout-3">
    <div class="exwt-details">
        <?php 
        do_action('exwt_before_single_event');
        wc_print_notices();
        ?>
        <div class="exwt-top-imgs" >
            <div class="exwt-info-ct1">
                <?php woocommerce_show_product_images(); ?>
        	</div>
        </div>
        <div class="exwt-heading">
            <h1 class="exwt-title">
                <?php the_title();?>
            </h1>
            <div class="exwt-shortdes"><?php woocommerce_template_single_excerpt()?></div>
        </div>
        <div class="exwt-info">
                <div class="exwt-info-ct1">
                    <div class="exwt-booking-form">
                        <div class="exwt-evprice">
                            <h3 class="<?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) ); ?>"><?php echo exwt_ss_change_price_html($product->get_price_html(),$product); ?></h3><?php echo exwt_show_reviews( get_the_ID()); ?>
                        </div>
                        <?php
                        exwt_add_to_cart();
                        do_action('exwt_after_atc_form')
                        ?>
                    </div>
                </div>
            <div class="exwt-info-ct2">
                <?php wootour_template_plugin('tour-meta');?>
                <div class="clear"></div>
            </div>
            <div class="exwt-info-ct3">
                <?php 
                woocommerce_output_product_data_tabs();
                do_action('exwt_after_content_3');
                wootour_template_plugin('tour-acco');
                $nb_rlt = $wt_sidebar!='hide' ? 3 : 4;
                $nb_rlt = apply_filters('exwt_number_related_items',$nb_rlt);
                $related_products = wc_get_related_products(get_the_ID(),$nb_rlt);
                if ( $related_products ):
                    ?>
                    <div class="exwt-related-event woo-event-schedu">
                        <h3 class="exwt-dh-title"><?php echo esc_html__( 'Related Tours', 'exthemes' ) ?></h3>
                        <div class="wt-grid-shortcode gr-classic wt-dismasonry <?php echo $wt_sidebar!='hide' ? 'wt-grid-column-3' : 'wt-grid-column-4';?>" id="grid-<?php echo $ID;?>">
                            <div class="ct-grid">
                            <div class="grid-container">
                            <?php
                            global $number_excerpt,$img_size;
                            $img_size = 'wethumb_460x307';
                            $number_excerpt = '0';
                            foreach ( $related_products as $related_product ) : ?>
                                <?php
                                $post_object = get_post( $related_product );
                                setup_postdata( $GLOBALS['post'] =& $post_object );
                                wootour_template_plugin('shop', false);
                            endforeach; ?>
                            </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <?php
                endif;
                wp_reset_postdata();
                ?>
            </div>
            
        </div>
        <div class="clear"></div>
        <?php do_action('exwt_after_main_content');?>
    </div>
</div>
<?php