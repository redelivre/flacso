<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Flacso
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function flacso_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'flacso_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function flacso_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	if ( is_search() || is_page_template( 'page-templates/news.php' ) || is_page_template( 'page-templates/child-page-list.php' ) || is_page_template( 'page-templates/project-list.php' ) ) {
		$classes[] = 'archive';
	}

	if ( is_gea() ) {
		$classes[] = 'gea';
	}

	return $classes;
}
add_filter( 'body_class', 'flacso_body_classes' );

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function flacso_wp_title( $title, $sep ) {
	if ( is_feed() ) {
		return $title;
	}

	global $page, $paged;

	// Add the blog name
	$title .= get_bloginfo( 'name', 'display' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title .= " $sep $site_description";
	}

	// Add a page number if necessary:
	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
		$title .= " $sep " . sprintf( __( 'Page %s', 'flacso' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'flacso_wp_title', 10, 2 );

/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function flacso_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}
add_action( 'wp', 'flacso_setup_author' );

/**
 * Callback for wp_list_comments
 * @param  [type] $comment [description]
 * @param  [type] $args    [description]
 * @param  [type] $depth   [description]
 */
function flacso_comments_list($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);

	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
?>
	<<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? 'media' : 'media parent' ) ?> id="comment-<?php comment_ID() ?>">
	
	<article id="div-comment-<?php comment_ID() ?>" class="comment-body">
		<?php if ( $args['avatar_size'] != 0 ) : ?>
		<div class="comment-author--image media-left">
			<?php echo get_avatar( $comment ); ?>
		</div><!-- .comment-author--image.media-left -->
		<?php endif; ?>

		<div class="media-body">
			<div class="comment-meta">
				<span class="comment-author vcard">
					<?php printf( __( '<cite class="fn media-heading">%s</cite>' ), get_comment_author_link() ); ?>
				</span><!-- .comment-author.vcard -->

				<span class="comment-metadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>">
					<?php
						/* translators: 1: date, 2: time */
						printf( __('%s'), get_comment_date() ); ?></a><?php edit_comment_link( __( '(Edit)' ), '  ', '' );
					?>
				</span><!-- .comment-metadata -->
			</div><!-- .comment-meta. -->

			<div class="comment-content">
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<p class="comment-awaiting-moderation bg-warning text-warning"><?php _e( 'Your comment is awaiting moderation.' ); ?></p>
				<?php endif; ?>
				<?php comment_text(); ?>
			</div><!-- .comment-content -->

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</div><!-- .media-body -->
	</article><!-- .comment-body -->
<?php
}

/**
 * Remove gallery styles
 */
add_filter( 'use_default_gallery_style', '__return_false' );

/**
 * Change gallery defaults on the front end
 */
function flacso_change_gallery_defaults( $out, $pairs, $atts ) {

    $atts = shortcode_atts( array(
      'columns'	=> 8,
      'link'	=> 'file'

    ), $atts );
 
    $out['columns'] = $atts['columns'];
    $out['link'] = $atts['link'];
 
    return $out;
 
}
add_filter( 'shortcode_atts_gallery', 'flacso_change_gallery_defaults', 10, 3 );

/**
 * Remove WordPress's default padding on images with captions
 *
 * @param int $width Default WP .wp-caption width (image width + 10px)
 * @return int Updated width to remove 10px padding
 */
function remove_caption_padding( $width ) {
	return $width - 10;
}
add_filter( 'img_caption_shortcode_width', 'remove_caption_padding' );

function flacso_exclude_gea($wp_query)
{
	if($wp_query->is_archive && !is_gea())
	{
		$tax_query = array(
			'taxonomy' => 'gea',
			'field'    => 'slug',
			'terms'    => 'GEA',
			'operator' => 'NOT IN',
		);
		$wp_query->tax_query->queries[] = $tax_query;
		$wp_query->query_vars['tax_query'] = $wp_query->tax_query->queries;
	}
}
add_action( 'pre_get_posts', 'flacso_exclude_gea' );

/**
 * Filter post type Agenda and change past events behavior
 *
 * This should be automatically done in the post type. However, the
 * main code has yet to be updated
 * 
 * @param object $wp_query [description]
 * @link https://github.com/redelivre/redelivre/blob/master/src/wp-content/mu-plugins/agenda/agenda.php
 */
function flacso_filter_agenda( $wp_query ) {
    
    if (is_admin()) return;
    
    if (isset($wp_query->query_vars['post_type']) && $wp_query->query_vars['post_type'] === 'agenda' && is_post_type_archive('agenda')) {
        
        
        if (!isset($wp_query->query_vars['meta_query']) || !is_array($wp_query->query_vars['meta_query'])) {
            $wp_query->query_vars['meta_query'] = array();
        }
        
        $wp_query->query_vars['orderby'] = 'meta_value';
        $wp_query->query_vars['order'] = 'ASC';
        $wp_query->query_vars['meta_key'] = '_data_inicial';
        
        if ($wp_query->query_vars['paged'] > 0 || (isset($_GET['eventos']) && $_GET['eventos'] == 'passados')) {
            array_push($wp_query->query_vars['meta_query'],
                array(
                    'key' => '_data_final',
                    'value' => date('Y-m-d'),
                    'compare' => '<=',
                    'type' => 'DATETIME',
                )
            );

            $wp_query->query_vars['order'] = 'DESC';

        } else {
            $wp_query->query_vars['posts_per_page'] = -1;
            array_push($wp_query->query_vars['meta_query'],
                array(
                    'key' => '_data_final',
                    'value' => date('Y-m-d'),
                    'compare' => '>=',
                    'type' => 'DATETIME'
                )
            );
        }
    }
}
add_action('pre_get_posts', 'flacso_filter_agenda');
?>