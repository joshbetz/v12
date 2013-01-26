<?php if ( in_array( get_post_format(), array('', 'standard', 'gallery') ) ) : ?>
	<div class="post-meta">
		<span class="byline">By <a class="author" rel="author" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php echo get_the_author(); ?></a></span> &mdash;
		<time datetime="<?php echo get_the_date( 'c' ); ?>"><?php the_date(); ?></time>
	</div>
<?php elseif ( in_array( get_post_format(), array('quote', 'status', 'link') ) ) : ?>
	<div class="post-meta">
		Posted: <time datetime="<?php echo get_the_date( 'c' ); ?>"><?php the_date(); ?></time> &middot;
		<?php the_shortlink(); ?>
	</div>
<?php else : ?>
	<div class="post-meta">
		Posted: <time datetime="<?php echo get_the_date( 'c' ); ?>"><?php the_date(); ?></time>
	</div>
<?php endif; ?>
