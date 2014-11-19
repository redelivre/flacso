<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Flacso
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site container">

	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'flacso' ); ?></a>

	<header id="masthead" class="site-header" role="banner">
		<div class="row">
			<div class="site-complementary clear">
				<div class="col-md-6">
					<nav id="site-navigation--top" class="site-navigation top-navigation" role="navigation">
						<?php wp_nav_menu( array( 'theme_location' => 'top' ) ); ?>
					</nav><!-- #site-navigation -->
				</div>
				<div class="col-md-6">
					<?php get_search_form(); ?>
				</div>
			</div><!-- .site-complementary -->

		<div class="site-branding clear">
			<?php if ( get_header_image() ) : ?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<img src="<?php header_image(); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" height="<?php echo esc_attr( get_custom_header()->height ); ?>" alt="">
			</a>
			<?php endif; // End header image check. ?>
			<div class="col-md-12">
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
			</div>
		</div><!-- .site-branding -->
		</div>
	</header><!-- #masthead -->

	<div id="content" class="site-content">
