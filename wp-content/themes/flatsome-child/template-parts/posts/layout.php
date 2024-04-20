<?php
do_action('flatsome_before_blog');
?>

<?php if (!is_single() && get_theme_mod('blog_featured', '') == 'top') {
	get_template_part('template-parts/posts/featured-posts');
} ?>

		<?php if (!is_single() && get_theme_mod('blog_featured', '') == 'content') {
			get_template_part('template-parts/posts/featured-posts');
		} ?>

		<?php
		if (is_single()) {

			get_template_part('template-parts/posts/single');
			//comments_template();
		} elseif (get_theme_mod('blog_style_archive', '') && (is_archive() || is_search())) {

			get_template_part('template-parts/posts/archive', get_theme_mod('blog_style_archive', ''));
		} else {

			get_template_part('template-parts/posts/archive', get_theme_mod('blog_style', 'normal'));
		}
		?>

<?php do_action('flatsome_after_blog');
