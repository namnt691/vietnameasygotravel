<?php if (have_posts()) : ?>


	<?php /* Start the Loop */ ?>

	<?php while (have_posts()) : the_post(); ?>


		<?php
		if (flatsome_option('blog_post_style') == 'default' || flatsome_option('blog_post_style') == 'inline') {
			get_template_part('template-parts/posts/partials/entry-header', flatsome_option('blog_posts_header_style'));
		}
		?>
		<?php get_template_part('template-parts/posts/content', 'single'); ?>
		<!-- #-<?php the_ID(); ?> -->

	<?php endwhile; ?>

<?php else : ?>

	<?php get_template_part('no-results', 'index'); ?>

<?php endif; ?>