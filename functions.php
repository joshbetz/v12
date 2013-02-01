<?php
/**
 * v12 functions and definitions
 *
 * @package v12
 * @since v12 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since v12 1.0
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

if ( ! defined( 'v12_LEFT_NAV_ARROW' ) )
	define( 'v12_LEFT_NAV_ARROW', '&larr;' );

if ( ! defined( 'v12_RIGHT_NAV_ARROW' ) )
	define( 'v12_RIGHT_NAV_ARROW', '&rarr;' );

/**
 * v12 Theme
 */
class v12_Theme {

	const VERSION = '1.0.1';

	function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'widgets_init', array( $this, 'widgets_init' ) );

		// Scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Theme
		add_action( 'wp_title', array( $this, 'wp_title' ), 10, 2 );
		add_action( 'after_setup_theme', array( $this, 'theme_setup' ) );
		add_action( 'comment_form_before', array( $this, 'comments_reply_script' ) );
	}

	function init() {
		// Link post format
		add_filter( 'the_title', array( $this, 'link_titles' ), 10, 2 );
		add_filter( 'post_link', array( $this, 'link_post_links' ) );
		add_filter( 'the_permalink', array( $this, 'link_permalinks' ) );

		// Main nav menu
		add_filter( 'wp_page_menu_args', array( $this, 'home_page_menu_item' ) );
		add_filter( 'nav_menu_css_class', array( $this, 'menu_item_has_children' ), 10, 3 );

		// Customize shortlink presentation
		add_filter( 'the_shortlink', array( $this, 'shortlink_remove_protocol' ), 10, 4 );
	}

	function theme_setup() {
		// This theme styles the visual editor with editor-style.css to match the theme style.
		add_editor_style();

		// This theme uses post formats
		add_theme_support( 'post-formats', array( 'link', 'image', 'video', 'audio', 'quote', 'status' ) );

		// Post thumbnails are displayed above the article.
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 804, 322, true );

		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );

		// This theme uses wp_nav_menu() for the main nav.
		register_nav_menu( 'primary', __( 'Primary Menu', 'v12' ) );

		// Add support for custom background.
		add_theme_support( 'custom-background' );

		// Add translation support
		load_theme_textdomain( 'v12', get_template_directory() . '/languages' );
	}

	function enqueue_scripts() {
		// Theme CSS and Javascript
		wp_enqueue_style( 'v12', get_template_directory_uri() . '/css/style.css', false, self::VERSION );
		wp_enqueue_script( 'v12', get_template_directory_uri() . '/js/main.min.js', array( 'jquery', 'plugins' ), self::VERSION, true );

		wp_enqueue_script( 'plugins', get_template_directory_uri() . '/js/plugins.min.js', array( 'jquery' ), self::VERSION, true );
		wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/js/vendors/modernizr-2.6.2.min.js' );

		wp_enqueue_style( 'prettify', get_template_directory_uri() . '/js/vendors/google-code-prettify/prettify.css', false, self::VERSION );
	}

	function comments_reply_script() {
		if ( get_option( 'thread_comments' ) )
			wp_enqueue_script( 'comment-reply' );
	}

	function wp_title( $title, $sep ) {
		global $paged, $page;

		if ( is_feed() )
			return $title;

		// Add the blog name.
		$title .= get_bloginfo( 'name' );

		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			$title = "$title $sep $site_description";

		// Add a page number if necessary.
		if ( $paged >= 2 || $page >= 2 )
			$title = "$title $sep " . sprintf( __( 'Page %s', 'v12' ), max( $paged, $page ) );

		return $title;
	}

	function widgets_init() {
		register_sidebar( array(
			'name' => __( 'Main Sidebar', 'v12' ),
			'id' => 'sidebar-1',
			'description' => __( 'Appears on posts and pages except the optional Homepage template, which uses its own set of widgets', 'v12' ),
			'before_widget' => '<aside id="%1$s" class="node %2$s">',
			'after_widget' => "</aside>",
			'before_title' => '<h1 class="widget-title">',
			'after_title' => '</h1>',
		) );
	}

	function menu_item_has_children( $classes, $item, $args ) {
		if ( ( is_object( $args ) && isset( $args->has_children ) && $args->has_children ) || ( is_array( $args ) && isset( $args['has_children'] ) && $args['has_children'] ) )
			$classes[] = 'menu-item-has-children';
		return $classes;
	}

	function home_page_menu_item( $args ) {
		$args['show_home'] = true;
		return $args;
	}

	function shortlink_remove_protocol( $link, $shortlink, $text, $title ) {
		return sprintf( '<a rel="shortlink" href="%s" title="%s">%s</a>', esc_url( $shortlink ), $title, str_replace( array( 'https://', 'http://' ), '', $shortlink ) );
	}

	function link_post_links( $link ) {
		global $post;

		if ( is_feed() && has_post_format( 'link', $post->ID ) ) {
			return get_post_meta($post->ID, '_format_link_url', true);
		}

		return $link;
	}

	function link_permalinks( $link ) {
		global $post;

		if ( has_post_format( 'link', $post->ID ) ) {
			return get_post_meta($post->ID, '_format_link_url', true);
		}

		return $link;
	}

	function link_titles( $title, $id ) {
		if ( has_post_format( 'link', $id ) && !is_admin() ) {
			if ( is_feed() )
				return '&rarr; ' . $title;
		}

		return $title;
	}
}

$v12_theme = new v12_Theme();

class v12_Walker_Nav_Menu extends Walker_Nav_Menu {

	function __construct() {
		add_filter( 'wp_nav_menu_args', array( __CLASS__, 'items_wrap' ) );
	}

	/**
	 * @see wp_nav_menu()
	 *
	 * @param array $args Arguments for wp_nav_menu
	 */
	static function items_wrap( $args ) {
		$args['container'] = '';
		$args['items_wrap'] = '<nav id="%1$s" class="%2$s">%3$s</nav>';
		$args['fallback_cb'] = '__return_false';
		return $args;
	}

	/**
	 * @see Walker::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of page. Used for padding.
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<div class=\"sub-menu\">\n";
	}

	/**
	 * @see Walker::end_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of page. Used for padding.
	 */
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</div>\n";
	}

	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param int $current_page Menu item ID.
	 * @param object $args
	 */
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
		$attributes .= $id . $value . $class_names;

		$item_output = ! empty( $args->before ) ? $args->before : '';
		$item_output .= '<a'. $attributes .'>';
		$item_output .= ! empty( $args->links_before ) ? $args->links_before : '';
		$item_output .= apply_filters( 'the_title', $item->title, $item->ID );
		$item_output .= ! empty( $args->links_after ) ? $args->links_after : '';
		$item_output .= '</a>';
		$item_output .= ! empty( $args->after ) ? $args->after : '';

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 * @see Walker::end_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Page data object. Not used.
	 * @param int $depth Depth of page. Not Used.
	 */
	function end_el( &$output, $item, $depth = 0, $args = array() ) {
		$output .= "\n";
	}
}

if ( ! function_exists( 'v12_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since v12 1.0
 */
function v12_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback node">
		<p><?php _e( 'Pingback:', 'v12' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'v12' ), ' ' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class('node'); ?> id="comment-<?php comment_ID(); ?>">
		<article>
			<footer>
				<div class="comment-author vcard">
					<?php echo get_avatar( $comment, 64 ); ?>
					<?php printf( '<cite class="fn">%s</cite>', get_comment_author_link() ); ?>
				</div><!-- .comment-author .vcard -->

				<div class="comment-meta commentmetadata">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
						<time datetime="<?php comment_time( 'c' ); ?>">
							<?php printf( __( '%1$s', 'v12' ), get_comment_date() ); ?>
						</time>
					</a>
					<?php edit_comment_link( __( '(Edit)', 'v12' ), ' ' ); ?>
				</div><!-- .comment-meta .commentmetadata -->
			</footer>

			<div class="comment-content">
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<p class="waiting"><em><?php _e( 'Your comment is awaiting moderation.', 'v12' ); ?></em></p>
				<?php endif; ?>

				<?php comment_text(); ?>
			</div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->

	<?php
			break;
	endswitch;
}
endif; // ends check for v12_comment()

if ( ! function_exists( 'v12_content_nav' ) ):
/**
 * Display navigation to next/previous pages when applicable
 *
 * @since _s 1.0
 */
function v12_content_nav( $nav_id = 'navigation' ) {
	global $wp_query;

	$nav_class = 'navigation paging-navigation';
	if ( is_single() )
		$nav_class = 'navigation post-navigation';

	?>
	<nav role="navigation" id="<?php echo $nav_id; ?>" class="<?php echo $nav_class; ?>">
		<h1 class="assistive-text"><?php _e( 'Post navigation', 'v12' ); ?></h1>

	<?php if ( is_single() ) : // navigation links for single posts ?>

		<?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-nav">' . _x( v12_LEFT_NAV_ARROW, 'Previous post link', 'v12' ) . '</span> %title' ); ?>
		<?php next_post_link( '<div class="nav-next">%link</div>', '%title <span class="meta-nav">' . _x( v12_RIGHT_NAV_ARROW, 'Next post link', 'v12' ) . '</span>' ); ?>

	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>

		<?php if ( get_next_posts_link() ) : ?>
		<div class="nav-previous"><?php next_posts_link( sprintf( __( '%s Older posts', 'v12' ), '<span class="meta-nav">' . v12_LEFT_NAV_ARROW . '</span>' ) ); ?></div>
		<?php endif; ?>

		<?php if ( get_previous_posts_link() ) : ?>
		<div class="nav-next"><?php previous_posts_link( sprintf( __( 'Newer posts %s', 'v12' ), '<span class="meta-nav">' . v12_RIGHT_NAV_ARROW . '</span>' ) ); ?></div>
		<?php endif; ?>

	<?php endif; ?>

	</nav><!-- #<?php echo $nav_id; ?> -->
	<?php
}
endif; // _s_content_nav

if ( ! function_exists( 'v12_url_grabber' ) ) :
/**
 * Grab the first URL in a link post so we can use it as the href
 */
function v12_url_grabber( $content ) {
	if ( ! preg_match( '/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', $content, $matches ) )
		return false;

	return esc_url_raw( $matches[1] );
}
endif;

if ( ! function_exists( 'v12_possibly_related_posts' ) ) :
/**
 * On 404 pages, run a search based on the URI.
 * Maybe we'll come up with what they were looking for
 * and avoid forcing them to do a search
 */
function v12_possibly_related_posts() {
	if ( ! is_404() )
		return;

	$uri = esc_url( $_SERVER['REQUEST_URI'] );
	$uri = array_pop( explode( '/', $uri ) );

	$search = trim( preg_replace( '@[_-]@', ' ', $uri ) );
	$posts = get_posts( array( 's' => $search ) );

	if ( count( $posts ) == 0 )
		return;

	$related = "<h2>Maybe these will help?</h2>";
	$related .= "<ul>";
	foreach ( $posts as $post ) {
		$title = $post->post_title;
		$permalink = get_permalink( $post->ID );
		$related .= "<li><a href='$permalink'>$title</a></li>";
	}
	$related .= "</ul>";

	echo $related;
}
endif;
