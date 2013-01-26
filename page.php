<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package v12
 * @since v12 1.0
 */

get_header(); ?>

<section id="main" class="col-1">

	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'loop' ); ?>

		<?php
			// If comments are open or we have at least one comment, load up the comment template
			if ( comments_open() || '0' != get_comments_number() )
				comments_template();
		?>

	<?php endwhile; // end of the loop. ?>

</section>

<?php get_footer(); ?>
