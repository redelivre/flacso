<?php
/**
 * Flacso functions and definitions
 *
 * @package Flacso
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 825; /* pixels */
}

if ( ! function_exists( 'flacso_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function flacso_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Flacso, use a find and replace
	 * to change 'flacso' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'flacso', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	// Image sizes
	add_image_size( 'featured', 328, 328, true );
	add_image_size( 'archive', 328, 246, true );
	add_image_size( 'singular', 825, 619, true );
	add_image_size( 'document', 176, 234, true );
	add_image_size( 'document--small', 150, 212, true );


	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'main' => __( 'Main Menu', 'flacso' ),
		'top' => __( 'Top Menu', 'flacso' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'flacso_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Add support for excerpt in pages
	add_post_type_support( 'page', 'excerpt' );
}
endif; // flacso_setup
add_action( 'after_setup_theme', 'flacso_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function flacso_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'flacso' ),
		'id'            => 'sidebar-main',
		'description'   => __( 'The main sidebar', 'flacso' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h4 class="widget-title area-title area-title--secondary">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer Widget Area', 'flacso' ),
		'id'            => 'sidebar-footer',
		'description'	=> __( 'The widget area on the footer', 'flacso' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s col-md-3">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h4 class="widget-title area-title area-title--secondary">',
		'after_title'   => '</h4>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Banners Widget Area', 'flacso' ),
		'id'            => 'sidebar-banners',
		'description'	=> __( 'The widget area for banners', 'flacso' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s banners-cycle">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h4 class="widget-title area-title area-title--secondary">',
		'after_title'   => '</h4>',
	) );
	
}
add_action( 'widgets_init', 'flacso_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function flacso_scripts() {
	wp_enqueue_style( 'flacso-style', get_stylesheet_uri() );

	// Google Fonts
    wp_register_style( 'flacso-fonts', 'http://fonts.googleapis.com/css?family=Lato:300,700' );
    wp_enqueue_style( 'flacso-fonts' );

    // Icon fonts by Fontello
    wp_register_style( 'flacso-icons', get_template_directory_uri() . '/css/flacso.css' );
    wp_enqueue_style( 'flacso-icons' );

	wp_enqueue_script( 'flacso-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'flacso-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	
	// Banners Cycle
	wp_enqueue_script('jquery-cycle2', get_template_directory_uri() . '/js/jquery.cycle2.min.js', array('jquery'));
	wp_enqueue_script('jquery-cycle2-carousel', get_template_directory_uri() . '/js/jquery.cycle2.carousel.min.js', array('jquery-cycle2'));
	wp_enqueue_script('jquery-cycle2-swipe', get_template_directory_uri() . '/js/jquery.cycle2.swipe.min.js', array('jquery-cycle2'));
	wp_enqueue_script('banners-scroller', get_template_directory_uri() . '/js/banners_scroller.js', array('jquery-cycle2'));
	wp_register_style( 'flacso-banners-cycle', get_template_directory_uri() . '/css/banners-cycle.css' );
	wp_enqueue_style( 'flacso-banners-cycle' );
	
}
add_action( 'wp_enqueue_scripts', 'flacso_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Taxonomies
 */
require get_template_directory() . '/inc/taxonomies.php';
