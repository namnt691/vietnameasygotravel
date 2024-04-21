<div class="BlogPageDetail">
	<div class="section-content relative">
		<div class="row">
			<div class="col small-12 large-12">
				<div class="breadcrumb">
				<?php echo custom_breadcrumbsgg('category'); ?>
				</div>

			</div>
		</div>
	</div>

	<div class="BlogPageDetailex-box">
		<div class="row">
			<div class="col aboutpage-content medium-9 small-12  large-9">
				<div class="col-inner">
					<h1 class="detailePostHeading">
						<?php the_title(); ?>
					</h1>
					<div class="detailPostContent">
						<?php echo do_shortcode('[ez-toc]') ?>
						<?php the_content(); ?>
					</div>




				</div>

			</div>
			<div class="col  medium-3 small-12  large-3">



				<?php
				global $sitepress;
				$current_language = $sitepress->get_current_language();

				if ($current_language == 'vi') {
				?>
					<?php echo do_shortcode('[block id="cot-phai-tin-moi"]') ?>
					<?php echo do_shortcode('[block id="cot-phai-tin-noi-bat"]') ?>
					<?php echo do_shortcode('[contact-form-7 id="00ef0f7" title="Liên hệ - Cột phải"]') ?>

				<?php
				}
				if ($current_language == 'en') {
				?>
					<?php echo do_shortcode('[block id="post-new-side-bar"]') ?>
					<?php echo do_shortcode('[block id="featured-news-right-column"]') ?>
					<?php echo do_shortcode('[contact-form-7 id="23b3bc9" title="Contact - Colum Right"]') ?>

				<?php
				}



				?>


			</div>
			<div class="col aboutpage-content relate-box medium-12 small-12  large-12">
				<div class="col-inner">
					<div class=" ">

						<div class="row">
							<div class="col small-12 large-12">
								<h3 class="relate-post">
									<?php
									global $sitepress;
									$current_language = $sitepress->get_current_language();
									?>
									<?php if ($current_language == 'vi') {
										echo do_shortcode('[block id="text-tim-hieu-them"]');
									}
									?>
									<?php if ($current_language == 'en') {
									 echo do_shortcode('[block id="text-learn-more"]');
									}
									?>

								</h3>
							</div>
						</div>
						<div class="row relate-row">
							<?php


							global $post;


							// get the custom post type's taxonomy terms
							$custom_taxterms = wp_get_object_terms(get_the_id(), 'category', array('fields' => 'ids'));
							// arguments	

							$taxonomy   = 'category'; // HERE define the targeted product attribute taxonomy

							$custom_taxterms = wp_get_object_terms($post->ID, 'category', array('fields' => 'ids'));
							$args = array(
								'post_type' => 'post',
								'post_status' => 'publish',
								'posts_per_page' => 9, // you may edit this number
								'orderby' => 'rand',
								'tax_query' => array(
									array(
										'taxonomy' => 'category',
										'field' => 'id',
										'terms' => $custom_taxterms
									)
								),
								'post__not_in' => array($post->ID),
							);
							$related_items = new WP_Query($args);
							// loop over query
							if ($related_items->have_posts()) :
								while ($related_items->have_posts()) : $related_items->the_post();
							?>
									<div class="col relateitem medium-4 small-12 large-4 ">
										<div class="homeblog-item">
											<div class="homeblog-thumb">
												<a href="<?php echo get_the_permalink(); ?>" title="<?php echo get_the_title() ?>">
													<img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large') ?>" alt="<?php echo get_the_title() ?>" />
												</a>
											</div>
											<div class="homeblog-content">
												<div class="homeblog-date">
													<?php echo get_the_modified_date(); ?>
												</div>
												<div class="homeblog-title">
													<a href="<?php echo get_the_permalink(); ?>" title="<?php echo get_the_title() ?>">
														<?php echo get_the_title() ?>
													</a>
												</div>
												<div class="homeblog-description">
													<?php echo get_post_meta(get_the_ID(), 'Post_Description', true); ?>
												</div>
											   <?php

                                global $sitepress;
                                $current_language = $sitepress->get_current_language();
                                $readmore = "Xem thêm";
                                if ($current_language == 'vi') {
                                    $readmore = "Xem thêm";
                                }
                                if ($current_language == 'en') {
                                    $readmore = "Read more";
                                }

                                ?>
                                <div class="homeblog-readmore">
                                    <a href="<?php echo get_the_permalink(); ?>" title="<?php echo  $readmore; ?>">
                                        <?php echo  $readmore; ?>
                                    </a>
                                </div>
											</div>
										</div>
									</div>
							<?php
								endwhile;
							endif;
							// Reset Post Data
							wp_reset_postdata();
							?>
						</div>
					</div>
				</div>
			</div>


		</div>
	</div>
</div>







<style>
	.page-wrapper {
		padding-top: 0;
		padding-bottom: 0;
	}
</style>