<?php
/**
 * @package v12
 * @since v12 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('node'); ?>>

	<section class="quote">
		<?php
			$source = get_post_meta( $post->ID, '_format_quote_source_name', true );
			$url = get_post_meta( $post->ID, '_format_quote_source_url', true );
			$blockquote = strpos( get_the_content(), '<blockquote' ) === false ? false : true;

			if ( $url && !$blockquote )
				echo "<blockquote cite='$url'>";
			elseif ( !$blockquote )
				echo "<blockquote>";

			the_content();

			if ( $url && $source )
				printf( '<cite><a href="%s">%s</a></cite>', esc_url( $url ), $source );
			elseif ( $source )
				echo "<cite>$source</cite>";

			if ( !$blockquote )
				echo "</blockquote>";
		?>
	</section>

	<?php get_template_part( 'loop-meta' ); ?>

</article>
