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
					else :
						printf(__('Tìm kiếm', 'flatsome'), '<span>' . get_search_query() . '</span>');

					endif;
					?>


				</h1>
				<div class="product-well">
					<div class="ba-text-fpt">
						<?php echo category_description(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="tourBoxCategory">
		<?php
$CategoryPage = get_queried_object();
				$idCategory = $CategoryPage->term_id;
		$taxomaxyid = isset($_GET['location_id']) && $_GET['location_id'] ? $_GET['location_id'] : "";
		$thoigian = isset($_GET['time']) && $_GET['time'] ? $_GET['time'] : "";
		?>

		<div class="row">
			<?php
			if ($thoigian) {
				$args = array(
					'post_type' => 'product',
					'post_status' => 'publish',
					'posts_per_page' => 12,
					'orderby' => 'post_date',
					
					'order' => 'desc',
					'paged' => $paged,
					'meta_query' => array(
						'relation'      => 'AND',
						array(

							'key' => 'Pro_F1',
							'value'     => $thoigian,
						),
						array(

							'key' => 'detail_pr',
							'value'     => "Tour",
						),
					),
					'tax_query' => array(
						array(
							'taxonomy' => 'product_cat',
							'field' => 'term_id',
							'terms' => 	$taxomaxyid,
						)
					),
				);
			} else {
				$args = array(
					'post_type' => 'product',
					'post_status' => 'publish',
					'posts_per_page' => 12,
					'orderby' => 'post_date',
				'taxonomy' => 'product_cat',
					'hide_empty' => false,
					'parent' => $idCategory, 
					'order' => 'desc',
					'meta_query' => array(
						array(

							'key' => 'detail_pr',
							'value'     => "Tour",
						),
					),
						'tax_query' => array(
						array(
							'taxonomy' => 'product_cat',
							'field' => 'term_id',
							'terms' => 	$idCategory,
						)
					),
				);
			}
			$loop = new WP_Query($args);
			while ($loop->have_posts()) : $loop->the_post(); {
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
											<div class="prox--custoe prox-time">
												<strong>
													<?php
													if ($current_language == 'vi') {
														echo "Thời gian:";
													}
													if ($current_language == 'en') {
														echo "Time:";
													}
													?>
												</strong>
												<?php echo $label; ?>
											</div>
											<div class="prox--custoe prox-timego"><strong>
													<?php
													if ($current_language == 'vi') {
														echo "Khởi hành - Điểm đến:";
													}
													if ($current_language == 'en') {
														echo "Departure - Destination:";
													}
													?>
												</strong><?php echo get_post_meta(get_the_ID(), 'Pro_F2', true); ?></div>
											<div class="prox--custoe prox-hotel"><strong>
													<?php
													if ($current_language == 'vi') {
														echo "Thời gian khởi hành:";
													}
													if ($current_language == 'en') {
														echo "Departure time:";
													}
													?> </strong><?php echo get_post_meta(get_the_ID(), 'Pro_F3', true); ?></div>
											<div class="prox--custoe prox-tourin"><strong>
													<?php
													if ($current_language == 'vi') {
														echo "Hình thức:";
													}
													if ($current_language == 'en') {
														echo "Form:";
													}
													?> </strong><?php echo get_post_meta(get_the_ID(), 'Pro_F4', true); ?></div>
											<div class="prox--custoe prox-confi"><strong><?php
																							if ($current_language == 'vi') {
																								echo "Khách sạn:";
																							}
																							if ($current_language == 'en') {
																								echo "Hotel:";
																							}
																							?> </strong><?php echo get_post_meta(get_the_ID(), 'Pro_F5', true); ?></div>
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