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
	add_image_size( 'singular', 825, 619, false );
	add_image_size( 'document', 176, 234, true );
	add_image_size( 'document--small', 150, 212, true );


	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'main' => __( 'Main Menu', 'flacso' ),
		'top' => __( 'Top Menu', 'flacso' ),
		'top-gea' => __( 'Top Menu (GEA)', 'flacso' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Remove support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	remove_theme_support( 'post-formats' );

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
		'name'          => __( 'Sidebar (GEA)', 'flacso' ),
		'id'            => 'sidebar-main-gea',
		'description'   => __( 'The main sidebar for GEA', 'flacso' ),
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
		'name'          => __( 'Footer Widget Area (GEA)', 'flacso' ),
		'id'            => 'sidebar-footer-gea',
		'description'	=> __( 'The widget area on the GEA footer', 'flacso' ),
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

	wp_enqueue_script( 'flacso-bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array( 'jquery' ), '3.3.2', true );

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
	
	wp_enqueue_script('dropdown-checkbox', get_template_directory_uri() . '/js/dropdown-checkbox.js', array('jquery'));
	wp_register_style( 'dropdown-checkbox', get_template_directory_uri() . '/css/dropdown-checkbox.css' );
	wp_enqueue_style( 'dropdown-checkbox' );
	
	wp_enqueue_script('adv-search-box', get_template_directory_uri() . '/js/adv-search-box.js', array('jquery'));
	
}
add_action( 'wp_enqueue_scripts', 'flacso_scripts' );

function flacso_create_dropdown_checkbox($inputname, $taxonomy, $taxonomy_obj)
{
	$terms = get_terms($taxonomy);
	if(count($terms) > 0)
	{
		//Procurar por gea automáticamente caso em gea space
		$checked = '';
		$dlstyle = '';
		
		if($taxonomy == 'gea' && is_gea())
		{
			$checked = 'checked="checked"';
			$dlstyle = 'style="display:none;"';
		}
		?>
	
		<dl class="dropdown-checkbox" <?php echo $dlstyle ?> > 
		    <dt>
		    	<label class="dropdown-checkbox-header-label"><?php echo $taxonomy_obj->labels->name; ?></label>
		    	<div class="clickable">
			      <span class="hida"><?php echo $taxonomy_obj->labels->search_items; ?></span>    
			      <p class="multiSel"></p>
			      <span class="caret"></span>
		      	</div> 
		    </dt>
		  
		    <dd>
		        <div class="mutliSelect">
		            <ul><?php
		            	foreach ($terms as $term)
		            	{?>
			                <li>
			                	<label>
				                    <input type="checkbox" name="<?php echo "{$inputname}[]"; ?>" value="<?php echo $term->term_id; ?>" autocomplete="off" <?php echo $checked; ?> />
				                    <?php echo $term->name; ?>
				                </label>
			                </li><?php
		            	}?>
		            </ul>
		        </div>
		    </dd>
		</dl><?php
	} 	
}

function get_search_adv()
{
	
	global $wp_taxonomies;
	
	$types = array('post', 'publication');?>
	
	<div class="adv-search-box-custom-field">
		<input id="adv-search-box-button-top" type="submit" value="Pesquisar" class="search-submit adv-search-box-button">
	</div><?php
	
	$not_in = array('post_format');
	
	foreach ($wp_taxonomies as $taxonomy => $wp_taxonomy)
	{
		if( ! in_array($taxonomy, $not_in))
		{
			if( count(array_intersect($types, $wp_taxonomy->object_type)) > 0 )
			{
				flacso_create_dropdown_checkbox('adv-search-box-'.$taxonomy, $taxonomy, $wp_taxonomy);
			}
		}
	}
	global $CustomFields_global;
	global $Publication_global;
	
	$fields = array_merge($CustomFields_global->getFields(), $Publication_global->getFields());
	
	?><div class="adv-search-box-custom-field">
		<label><?php _e("Publication Title", "flacso"); ?></label>
		<input class="search-field" type="search" title="<?php echo __("Search for documents with that text", 'flacso'); ?>" name="adv-search-box-input-post_content" value="" placeholder="" autocomplete="off" />
	</div><?php
	
	foreach ($fields as $field)
	{
		if(!in_array($field['slug'], array('post_content', 'post_title')))
		{?>
			<div class="adv-search-box-custom-field">
				<label><?php echo $field['title']; ?></label>
				<input class="search-field" type="search" title="<?php echo __("Pesquisar por", 'flacso').": ".$field['title']; ?>" name="<?php echo 'adv-search-box-input-'.$field['slug']; ?>" value="" placeholder="" autocomplete="off" />
			</div><?php
		}
	}?>
	<div class="adv-search-box-custom-field">
		<input id="adv-search-box-button" type="submit" value="Pesquisar" class="search-submit adv-search-box-button">
	</div><?php
}

function flacso_adv_search_callback()
{
	$checkeds = array_key_exists('checked', $_POST) && is_array($_POST['checked']) ? $_POST['checked'] : array();
	$fields = array_key_exists('fields', $_POST) && is_array($_POST['fields']) ? $_POST['fields'] : array();
	echo '<div class="general-list">';
	
	/*print_r($checkeds);
	print_r($fields);*/
	
	$taxs = array();
	foreach ($checkeds as $checked)
	{
		if(!array_key_exists(sanitize_text_field($checked['name']), $taxs)) $taxs[sanitize_text_field($checked['name'])] = array();
		
		$taxs[sanitize_text_field($checked['name'])][] = sanitize_text_field($checked['value']);
	}
	
	$gea = false;
	
	
	$geaid = array();
	foreach ($taxs as $tax => $terms)
	{
		if($tax == 'gea')
		{
			$gea = true;
			$geaid = $terms;
			continue;
		}
		$tax_query[] = array(
			'taxonomy' => $tax,
			'field'    => 'term_id',
			'terms'    => $terms,
		);
	}
	
	if(count($tax_query) > 1)
	{
		$tax_query['relation'] = 'OR';
	}
	
	if($gea)
	{
		if( count($tax_query) > 0 )
		{
			$tax_query_gea = array();
			$tax_query_gea['relation'] = 'AND';
			$tax_query_gea[] = array(
					'taxonomy' => 'gea',
					'field'    => 'term_id',
					'terms'    => $geaid,
			);
			$tax_query = array_merge($tax_query_gea, $tax_query);
		}
		else 
		{
			$tax_query = array(array(
					'taxonomy' => 'gea',
					'field'    => 'term_id',
					'terms'    => $geaid,
			));
		}
	}
	
	$post_content = false;
	
	$meta_query = array();
	
	foreach ($fields as $field)
	{
		if(!in_array($field['name'], array('post_content', 'post_title')) && trim(sanitize_text_field($field['value'])) != "")
		{
			$meta_query[] = array(
				'key'     => sanitize_text_field($field['name']),
				'value'   => "%".sanitize_text_field($field['value'])."%",
				'compare' => 'LIKE',
			);
		}
		elseif($field['name'] == 'post_content' )
		{
			$post_content = sanitize_text_field($field['value']);
		}
	}
	if(count($meta_query) > 1) $meta_query[] = array('relation' => 'OR');
	
	$args = array(
		'post_type' => array('post', 'publication'),
		'post_status' => 'publish',
		//'suppress_filters' => false,
	);

	if(count($tax_query) > 1)
	{
		$args['tax_query'] = $tax_query;
	}
	
	if(count($meta_query) > 1)
	{
		$args['meta_query'] = $meta_query;
	}
	
	if( ! empty($post_content) )
	{
		$args['s'] = $post_content;
	}

	var_dump($args);//die();
	
	$query = new WP_Query($args);
	while ($query->have_posts())
	{
		$query->the_post();
		get_template_part( 'content', 'publication' );
	}
	echo '</div>';
	die();
}
add_action( 'wp_ajax_nopriv_flacso_adv_search', 'flacso_adv_search_callback');
add_action( 'wp_ajax_flacso_adv_search', 'flacso_adv_search_callback');

function flacso_search_where($where)
{
	if(
		(array_key_exists('action', $_POST) && $_POST['action'] == 'flacso_adv_search') &&
		(strpos($where, 'postmeta') !== false && strpos($where, 'term_relationships') !== false ) 
	)
	{
		//echo "<pre>";//$where\n";
		$begin = strpos($where, '(');
		$end = strpos($where, ') AND', $begin);
		$tax = substr($where, $begin, $end -3);
		$where = substr($where, $end + 1, -1);
		//echo("$begin : $end\n");
		//echo("$where OR $tax) </pre>");
		return "$where OR $tax) ";
	}
	return $where;
}
add_filter('posts_where', 'flacso_search_where');

function flacso_search_join($join)
{
	echo "<pre>$join</pre>";
	return $join;
}
//add_filter('posts_join', 'flacso_search_join');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom widgets.
 */
require get_template_directory() . '/inc/widgets.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Taxonomies
 */
require get_template_directory() . '/inc/taxonomies.php';

/**
 * Custom Fields
 */
require get_template_directory() . '/inc/custom-fields.php';

/** 
 * Custom fields for taxonomies
 */
require get_template_directory() . '/inc/tax-meta-fields.php';

/**
 * Opções do Tema 
 */
require get_template_directory() . '/inc/options.php';

/**
 * Register Custom Navigation Walker
 */ 
require get_template_directory() . '/inc/wp-bootstrap-navwalker.php';

/**
 * Back-end functions
 */
require get_template_directory() . '/inc/back-end.php';
