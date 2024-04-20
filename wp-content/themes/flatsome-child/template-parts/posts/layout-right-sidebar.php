<?php
do_action('flatsome_before_blog');
?>

<div class='blog-pagee'>

	<div class="row" style="justify-content: center;">

		<div class="large-12 col">
			

		</div>
		<div class="large-10 col">

			<?php if (!is_single() && flatsome_option('blog_featured') == 'top') {
				get_template_part('template-parts/posts/featured-posts');
			} ?>



			<?php if (!is_single() && flatsome_option('blog_featured') == 'content') {
				get_template_part('template-parts/posts/featured-posts');
			} ?>
			<?php
			if (is_single()) {
				get_template_part('template-parts/posts/single');

				comments_template();
			} elseif (flatsome_option('blog_style_archive') && (is_archive() || is_search())) {

				get_template_part('template-parts/posts/archive', flatsome_option('blog_style_archive'));
			} else {

			?>



				<?php get_template_part('template-parts/posts/archive', flatsome_option('blog_style')); ?>

			<?php
			}
			?>


		</div>

	</div>
</div>
<?php
do_action('flatsome_after_blog');
?>
<style>
	.page-wrapper {
		padding-top: 0;
		padding-bottom: 0;
	}
</style>