<?php
/**
 * @package v12
 * @since v12 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('node'); ?>>

	<?php if ( is_page() ) : ?>

		<header class="article-header">
			<h1><?php the_title(); ?></h1>
		</header>

	<?php else : ?>

		<header class="article-header">
			<?php if ( get_the_title() ) : ?>
				<?php if ( 'link' == get_post_format() ):

					$link = get_post_meta($post->ID, '_format_link_url', true);
					if ( empty( $link ) ) $link = v12_url_grabber( get_the_content() ); ?>

					<h1 class="article-title"><a href="<?php echo $link; ?>" rel="bookmark"><?php the_title(); ?>&nbsp;<span class="rightarrow">&rarr;</span></a></h1>

				<?php elseif ( is_single() ): ?>

					<h1 class="article-title"><?php the_title(); ?></h1>

				<?php else: ?>

					<h1 class="article-title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'v12' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>

				<?php endif; ?>
			<?php endif; ?>

			<?php get_template_part( 'loop-meta' ); ?>
		</header>

	<?php endif; ?>

	<?php
		if ( get_post_format() == 'audio' ) {
			$audio = get_post_meta( $post->ID, '_format_audio_embed', true );
			if ( !empty ( $audio ) ) {
				$url = esc_url( $audio );
				if ( $embed = wp_oembed_get( $url ) )
					echo $embed;
				elseif ( !empty( $url ) ) {
					printf( '<audio controls src="%s"></audio>', $url );
				} else {
					echo $audio;
				}
			}
		} elseif ( get_post_format() == 'video' ) {
			$video = get_post_meta( $post->ID, '_format_video_embed', true );
			if ( !empty( $video ) ) {
				$url = esc_url( $video );
				if ( $embed = wp_oembed_get( $url ) )
					echo $embed;
				elseif ( !empty( $url ) ) {
					printf( '<video controls src="%s"></video>', $url );
				} else {
					echo $video;
				}
			}
		}
	?>

	<?php if ( ! is_search() ) : ?>

		<section class="article-content">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'v12' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'v12' ), 'after' => '</div>' ) ); ?>
		</section>

	<?php else : ?>

		<section class="article-content">
			<?php the_excerpt(); ?>
		</section>

	<?php endif; ?>

</article>
