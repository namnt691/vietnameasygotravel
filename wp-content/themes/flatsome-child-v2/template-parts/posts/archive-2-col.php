<div class="row row-large <?php if (flatsome_option('blog_layout_divider')) echo 'row-divided '; ?>">
	
	<div class="large-12 col">
		<div class="pagecategory-box">

			<h1 class="heading-page">
				<?php

				if (is_category()) :
					printf(__('%s', 'flatsome'), '<span>' . single_cat_title('', false) . '</span>');

				elseif (is_tag()) :
					printf(__('Tag Archives: %s', 'flatsome'), '<span>' . single_tag_title('', false) . '</span>');

				elseif (is_search()) :
					printf(__('Search Results for: %s', 'flatsome'), '<span>' . get_search_query() . '</span>');

				elseif (is_author()) :
					/* Queue the first post, that way we know
				 * what author we're dealing with (if that is the case).
				*/
					the_post();
					printf(__('Author Archives: %s', 'flatsome'), '<span class="vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '" title="' . esc_attr(get_the_author()) . '" rel="me">' . get_the_author() . '</a></span>');
					/* Since we called the_post() above, we need to
				 * rewind the loop back to the beginning that way
				 * we can run the loop properly, in full.
				 */
					rewind_posts();

				elseif (is_day()) :
					printf(__('Daily Archives: %s', 'flatsome'), '<span>' . get_the_date() . '</span>');

				elseif (is_month()) :
					printf(__('Monthly Archives: %s', 'flatsome'), '<span>' . get_the_date('F Y') . '</span>');

				elseif (is_year()) :
					printf(__('Yearly Archives: %s', 'flatsome'), '<span>' . get_the_date('Y') . '</span>');

				elseif (is_tax('post_format', 'post-format-aside')) :
					_e('Asides', 'flatsome');

				elseif (is_tax('post_format', 'post-format-image')) :
					_e('Images', 'flatsome');

				elseif (is_tax('post_format', 'post-format-video')) :
					_e('Videos', 'flatsome');

				elseif (is_tax('post_format', 'post-format-quote')) :
					_e('Quotes', 'flatsome');

				elseif (is_tax('post_format', 'post-format-link')) :
					_e('Links', 'flatsome');

				else :
					_e('', 'flatsome');

				endif;
				?>
			</h1>
			<?php
			if (is_category()) :
				// show an optional category description
				$category_description = category_description();
				if (!empty($category_description)) :
					echo apply_filters('category_archive_meta', '<div class="taxonomy-description">' . $category_description . '</div>');
				endif;

			elseif (is_tag()) :
				// show an optional tag description
				$tag_description = tag_description();
				if (!empty($tag_description)) :
					echo apply_filters('tag_archive_meta', '<div class="taxonomy-description">' . $tag_description . '</div>');
				endif;

			endif;
			?>
		</div>
	</div>
	<div class="large-8 col">



		<div class="row">


			<?php if (have_posts()) : ?>

				<?php

				$ids = array();
				while (have_posts()) : the_post();
					array_push($ids, get_the_ID());
				endwhile;
				$ids = implode(',', $ids);
				?>


				<?php
				$ids = array();
				while (have_posts()) : the_post(); {
				?>



						<div class="col  medium-12 small-12 large-12">
							<div class="blog-post">
								<div class="thumbnail">
									<a href="<?php echo get_the_permalink(); ?>" title="<?php echo get_the_title() ?>">
										<img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large') ?>" alt="<?php echo get_the_title() ?>" />
									</a>
								</div>
								<div class="views">
									<?php echo get_the_modified_date(); ?>
								</div>
								<h2 class="title">
									<a href="<?php echo get_the_permalink(); ?>" title="<?php echo get_the_title() ?>">
										<?php echo get_the_title() ?>
									</a>
								</h2>
								<p class="description">
									<?php echo get_post_meta(get_the_ID(), 'Post_Description', true); ?>
								</p>
								<a href="<?php echo get_the_permalink(); ?>" class="btn" title="Xem thêm">
									Xem thêm
								</a>
							</div>


						</div>



				<?php
					}
				endwhile;
				?>
				<?php flatsome_posts_pagination(); ?>
		</div>





	<?php else : ?>

		<?php get_template_part('template-parts/posts/content', 'none'); ?>

	<?php endif; ?>
	</div> <!-- .large-9 -->

	<div class="post-sidebar large-4 col">
		<?php get_sidebar(); ?>
	</div><!-- .post-sidebar -->
</div>