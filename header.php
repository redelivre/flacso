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
<div id="page" class="hfeed site">

	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'flacso' ); ?></a>

	<header id="masthead" class="site-header" role="banner">
		
				<?php if ( is_gea() ) : ?>
					<div class="site-branding clear">
						<a class="site-branding__image_link" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
							<img src="<?php echo get_template_directory_uri() . '/images/header--gea.png'; ?>" alt="GEA">
						</a>
					</div><!-- .site-branding -->
				<?php else : ?>
				<div class="container">
						<div class="row">
					<div class="site-complementary  wrapper--small clear">
						
						<div class="col-md-9">
							<nav id="site-navigation--top" class="site-navigation top-navigation pull-right" role="navigation">
								<?php wp_nav_menu( array( 'theme_location' => 'top', 'depth' => 1 ) ); ?>
							</nav><!-- #site-navigation -->
						</div>
						<div class="col-md-3">
							<?php get_search_form(); ?>
						</div>
					</div><!-- .site-complementary -->
								</div><!-- .row -->
					</div><!-- .container -->	
					<div class="site-branding clear">
						<?php if ( get_header_image() ) : ?>
						<a class="site-branding__image_link" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
							<img src="<?php header_image(); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" height="<?php echo esc_attr( get_custom_header()->height ); ?>" alt="">
						</a>
						<div class="container">
							<div class="row">
								<div class="col-md-12">
									<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
									<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
								</div>
							</div><!-- .row -->
						</div><!-- .container -->
						<?php endif; // End header image check. ?>
					</div><!-- .site-branding -->
				<?php endif; ?>
	</header><!-- #masthead -->

	<div id="content" class="site-content<?php if ( ! is_front_page() ) : echo ' container'; endif; ?>">
		<div class="row">
