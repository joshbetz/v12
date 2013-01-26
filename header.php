<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package v12
 * @since v12 1.0
 */
?><!doctype html>
<html class="no-js wf-loading" <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<title><?php wp_title( ' &mdash; ', true, 'right' ); ?></title>
	<meta name="viewport" content="width=device-width">
	<script type="text/javascript">
		(function() {
			var config = {
				kitId: 'bbx6dms',
				scriptTimeout: 3000
			};
			var h=document.getElementsByTagName("html")[0];h.className+=" wf-loading";var t=setTimeout(function(){h.className=h.className.replace(/(\s|^)wf-loading(\s|$)/g," ");h.className+=" wf-inactive"},config.scriptTimeout);var tk=document.createElement("script"),d=false;tk.src='//use.typekit.net/'+config.kitId+'.js';tk.type="text/javascript";tk.async="true";tk.onload=tk.onreadystatechange=function(){var a=this.readyState;if(d||a&&a!="complete"&&a!="loaded")return;d=true;clearTimeout(t);try{Typekit.load(config)}catch(b){}};var s=document.getElementsByTagName("script")[0];s.parentNode.insertBefore(tk,s)
		})();
	</script>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

	<div id="wrap" class="grid">
		<header id="header">
			<div id="site-title"><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></div>
			<div id="site-description"><?php bloginfo('description'); ?></div>
			<nav id="main-navigation">
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => '', 'fallback_cb' => 'wp_page_menu' ) ); ?>
				<?php get_search_form(); ?>
			</nav>
		</header>
