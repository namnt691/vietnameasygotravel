<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$wt_sidebar = '';
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
$wt_shop_view = get_option('wt_shop_view');
if($wt_shop_view!=''){
	$clss .= ' wt-default-'.$wt_shop_view;
}
global $wt_main_purpose;
$wt_main_purpose = get_option('wt_main_purpose');
?>
<div class="exwt-container">
	<div id="wtmain-content" class="exwt-row">
    
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

		<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>

			<h1 class="page-title"><?php woocommerce_page_title(); ?></h1>

		<?php endif; ?>

		<?php
			/**
			 * woocommerce_archive_description hook.
			 *
			 * @hooked woocommerce_taxonomy_archive_description - 10
			 * @hooked woocommerce_product_archive_description - 10
			 */
			do_action( 'woocommerce_archive_description' );
		?>

		<?php
		if($wt_shop_view=='table' && !is_search()){
			do_action( 'woocommerce_before_shop_loop' );
			if ( have_posts() ) :?>
            <div class="wt-table-lisst <?php echo 'table-style-2';?>">
			<table class="wt-table">
                <tbody>
				<?php
				$style = 2;
				global $style;
				echo '<ul class="products columns-3">';
				woocommerce_maybe_show_product_subcategories($loop_html = '');
				echo '</ul>';
                while ( have_posts() ) : the_post();
                    //include wt_get_plugin_url().'shortcode/content/content-table.php';
					wootour_template_plugin('table', true);
                endwhile;?>
                </tbody>
			</table>
            </div>
            <?php
			woocommerce_pagination();
			endif;
			/* -- remove
			$tax_name = '';
			$tax_id = '';
			if(is_tax('product_cat')){
				echo do_shortcode('[wt_table count="1000" style="2" posts_per_page="'.get_option('posts_per_page').'" cat="'.get_queried_object()->term_id.'"]');
			}elseif(is_tax('product_tag')){
				echo do_shortcode('[wt_table count="1000" style="2" posts_per_page="'.get_option('posts_per_page').'" tag="'.get_queried_object()->term_id.'"]');
			}elseif(is_tax('wt_location' )){
				echo do_shortcode('[wt_table count="1000" style="2" posts_per_page="'.get_option('posts_per_page').'" location="'.get_queried_object()->term_id.'"]');
			}else{
				echo do_shortcode('[wt_table count="1000" style="2" posts_per_page="'.get_option('posts_per_page').'"]');
			}
			--*/
		}else if ( have_posts() ) : ?>

			<?php
				/**
				 * woocommerce_before_shop_loop hook.
				 *
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				do_action( 'woocommerce_before_shop_loop' );
			?>

			<?php woocommerce_product_loop_start(); ?>

				<?php woocommerce_maybe_show_product_subcategories($loop_html = ''); ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php wc_get_template_part( 'content', 'product' ); ?>

				<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>

			<?php
				/**
				 * woocommerce_after_shop_loop hook.
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
			?>

		<?php elseif ( ! woocommerce_output_product_categories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

			<?php wc_get_template( 'loop/no-products-found.php' ); ?>

		<?php endif; ?>

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
