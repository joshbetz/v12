<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package v12
 * @since v12 1.0
 */

get_header(); ?>

<section id="content" class="col-2-3">

	<article id="post-0" class="post error404 not-found node">
		<header class="article-header">
			<h1 class="article-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'v12' ); ?></h1>
		</header><!-- .article-header -->

		<section class="article-content">
			<p><?php _e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'v12' ); ?></p>

			<?php get_search_form(); ?>

			<?php v12_possibly_related_posts(); ?>

			<?php the_widget( 'WP_Widget_Recent_Posts' ); ?>

			<div class="widget">
				<h2 class="widgettitle"><?php _e( 'Most Used Categories', 'v12' ); ?></h2>
				<ul>
				<?php wp_list_categories( array( 'orderby' => 'count', 'order' => 'DESC', 'show_count' => 1, 'title_li' => '', 'number' => 10 ) ); ?>
				</ul>
			</div>
		</section>
	</article>

</section>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
