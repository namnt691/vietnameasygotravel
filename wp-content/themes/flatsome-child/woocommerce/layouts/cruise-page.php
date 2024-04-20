<?php
global $product;
?>

<div class="section-content relative">
	<div class="row">
		<div class="col small-12 large-12">
			<div class="breadcrumb">
			<?php echo custom_breadcrumbsgg('product_cat'); ?>
			</div>

		</div>
	</div>
</div>

<div class="ProCategoryPage">
	<div class="desc-product">
		<div class="row">
			<div class="col  medium-12 small-12 large-12 ">
				<h1>
					<?php

					if (is_product_category()) :
						printf(__('%s', 'flatsome'), '<span>' . single_cat_title('', false) . '</span>');
					elseif (is_search()) :
						printf(__('Kết quả tìm kiếm với từ khóa: %s', 'flatsome'), '<span>' . get_search_query() . '</span>');
					endif;
					?></h1>
				<div class="product-well">
					<div class="ba-text-fpt">
						<?php echo category_description(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="tourBoxCategory">


		<div class="row">
			<?php
			$ids = array();
			while (have_posts()) : the_post(); {
			?>

					<div class="col  medium-4 small-12 large-4 ">
						<?php
						$price = get_post_meta(get_the_ID(), '_regular_price', true);
						$saleprice = get_post_meta(get_the_ID(), '_sale_price', true);
						?>
						<?php
						$field = get_field_object('Pro_F1');
						$value = $field['value'];
						$label = $field['choices'][$value];
						global $sitepress;
						$current_language = $sitepress->get_current_language();
						?>

						<div class="item">
							<div class="homeProItem">

								<div class="homeProThumb">
									<a href="<?php echo get_the_permalink(); ?>" title="<?php echo get_the_title(); ?>">
										<img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large') ?>" alt="<?php echo get_the_title(); ?>" />
									</a>
								</div>
								<div class="homeProContent">
									<div class="homeProTitle">
										<a href="<?php echo get_the_permalink(); ?>" title="<?php echo get_the_title(); ?>">
											<?php echo get_the_title(); ?>
										</a>
									</div>
									<div class="homeProField">
										<div class="prox-custom">

											<div class="prox--custoe prox-confi"><strong>
													<?php
													if ($current_language == 'vi') {
														echo "Loại:";
													}
													if ($current_language == 'en') {
														echo "Category:";
													}
													?>
												</strong><?php echo get_post_meta(get_the_ID(), 'Cruises_Category', true); ?></div>
											<div class="prox--custoe prox-timego"><strong>
													<?php
													if ($current_language == 'vi') {
														echo "Điểm đến:";
													}
													if ($current_language == 'en') {
														echo "Destination:";
													}
													?> </strong><?php echo get_post_meta(get_the_ID(), 'Cruises_Destination', true); ?></div>
											<div class="prox--custoe prox-hotel"><strong>
													<?php
													if ($current_language == 'vi') {
														echo "Hình thức:";
													}
													if ($current_language == 'en') {
														echo "CAPACITY:";
													}
													?> </strong><?php echo get_post_meta(get_the_ID(), 'Cruises_Capacity', true); ?>
											</div>
										</div>
									</div>
									<div class="homeProPrice">
										<div class="ProPriceGr">
											<?php echo GetPrice($saleprice, $price); ?>
										</div>
									</div>
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