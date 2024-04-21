<?php
global $product;
?>

<div class="row category-page-row">

	<div class="col large-3 hide-for-medium <?php flatsome_sidebar_classes(); ?>">
		<div id="shop-sidebar" class="sidebar-inner col-inner">
			<?php
			if (is_active_sidebar('shop-sidebar')) {
				dynamic_sidebar('shop-sidebar');
			} else {
				echo '<p>You need to assign Widgets to <strong>"Shop Sidebar"</strong> in <a href="' . get_site_url() . '/wp-admin/widgets.php">Appearance > Widgets</a> to show anything here</p>';
			}
			?>
		</div><!-- .sidebar-inner -->
	</div><!-- #shop-sidebar -->

	<div class="col large-9">
		<div class="row">
			<div class="col  medium-12 small-12 large-12 ">
				<div class="collections-filter" style="margin-bottom:0;">
					<div class="collections-filter__heading">

						<h1 class="heading">
							<?php echo single_cat_title(); ?>


						</h1>
					</div>
					<div class="collections-orderby">

					</div>
				</div>
				<div class="contentCategory_desc maxLine_3">
					<?php echo category_description(); ?>
				</div>
			</div>
		</div>
		<div class="row">
			<?php
			$ids = array();
			while (have_posts()) : the_post(); {
			?>

					<div class="col  medium-4 small-12 large-4 ">
						<div class="pro-item">
							<?php
							$price = get_post_meta(get_the_ID(), '_regular_price', true);
							$saleprice = get_post_meta(get_the_ID(), '_sale_price', true);
							echo GetSale($saleprice, $price);
							?>
							<div class="pro-thumb">
								<a href="<?php echo get_the_permalink(); ?>" title="<?php echo get_the_title(); ?>">
									<img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large') ?>" alt="<?php echo get_the_title(); ?>" />
								</a>
							</div>
							<div class="pro-content">
								<div class="pro-title">
									<a href="<?php echo get_the_permalink(); ?>" title="<?php echo get_the_title(); ?>">
										<?php echo get_the_title(); ?>
									</a>
								</div>
								<div class="pro-price-gr">
									<div class="pro-price">
										<?php echo GetPrice($saleprice, $price); ?>
									</div>
								</div>
								<div class="box-rating_star">
									<span class="box-rating_star_on"><i class="fa fa-star"></i></span>
									<span class="box-rating_star_on"><i class="fa fa-star"></i></span>
									<span class="box-rating_star_on"><i class="fa fa-star"></i></span>
									<span class="box-rating_star_on"><i class="fa fa-star"></i></span>
									<span class="box-rating_star_on"><i class="fa fa-star"></i></span>
								</div>
								<div class="pro-readmore">
									<a href="<?php echo get_the_permalink(); ?>" title="<?php echo get_the_title(); ?>">
										Mua ngay
									</a>
								</div>
							</div>
						</div>

					</div>

			<?php
				}
			endwhile;
			?>
			<div class="text-xs-right">
				<div class="d_page">
					<?php flatsome_posts_pagination(); ?>
				</div>
			</div>
		</div>


	</div>
</div>