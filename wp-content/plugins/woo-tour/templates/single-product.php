<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$wt_sidebar = get_post_meta(get_the_ID(),'wt_sidebar',true);
if($wt_sidebar==''){
	$wt_sidebar = get_option('wt_sidebar','right');
}
get_header( 'shop' );
$wt_layout = wootour_global_layout();
if($wt_layout=='layout-3'){
	$wt_layout ='layout-2 layout-3';
}
$clss ='';
if(!is_active_sidebar('wootour-sidebar')){
	$clss = 'no-sidebar';
}
$clss .= ' wt-list-view';
$wt_click_remove = get_option('wt_click_remove','');
if($wt_click_remove=='yes'){
	$clss .= ' wt-remove-click';
}
global $wt_main_purpose;
$wt_main_purpose = get_option('wt_main_purpose');
$wt_layout_purpose = get_post_meta(get_the_ID(),'wt_layout_purpose',true);
if($wt_main_purpose=='custom' && $wt_layout_purpose!='tour'){
	$wt_main_purpose = 'woo';
}
?>
<div class="exwt-container">
	<div id="wtmain-content" class="exwt-row<?php if($wt_main_purpose=='woo'){ echo ' hidden-info-event';}?>">
    
    <div id="content" class="wt-main <?php echo $wt_layout.' '.$clss.' '; echo $wt_sidebar!='hide'?'exwt-col9':'exwt-col12' ?><?php echo ($wt_sidebar == 'left') ? " revert-layout":"";?>">
		<?php
            /**
             * woocommerce_before_main_content hook.
             *
             * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
             * @hooked woocommerce_breadcrumb - 20
             */
            do_action( 'woocommerce_before_main_content' );
        ?>
    
            <?php while ( have_posts() ) : the_post(); ?>
    
                <?php wc_get_template_part( 'content', 'single-product' ); ?>
    
            <?php endwhile; // end of the loop. ?>
    
        <?php
            /**
             * woocommerce_after_main_content hook.
             *
             * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
             */
            do_action( 'woocommerce_after_main_content' );
        ?>
	</div>
    <?php 
	if($wt_sidebar != 'hide'){?>
        <div class="wt-sidebar exwt-col3">
        <?php
            /**
             * woocommerce_sidebar hook.
             *
             * @hooked woocommerce_get_sidebar - 10
             */
            dynamic_sidebar('wootour-sidebar');
        ?>
        </div>
    <?php }?>
    </div>
</div>
<?php get_footer( 'shop' ); ?>
