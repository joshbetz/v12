<?php
/**
 * @package v12
 * @since v12 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('node'); ?>>

	<section class="status">
		<?php the_content(); ?>
	</section>

	<?php get_template_part( 'loop-meta' ); ?>

</article>
