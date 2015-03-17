<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Flacso
 */

/**
 * Display the main menu
 */
function flacso_the_menu() {
	$nav_class = ( is_admin_bar_showing() ) ? ' main-navigation--admin-bar' : '';
	?>
	<nav id="site-navigation" class="site-navigation main-navigation<?php echo $nav_class; ?>" role="navigation">
		<button class="menu-toggle" aria-controls="menu" aria-expanded="false"><?php _e( 'Menu', 'flacso' ); ?></button>
		<?php
		wp_nav_menu( array(
            'theme_location'    => 'main',
            'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
            'walker'            => new wp_bootstrap_navwalker()
        ));
		
		// Top menu inside <nav> to unify both navigations in small screens
		?>
		<div class="top-navigation--main-menu">
			<?php
	        wp_nav_menu( array( 'theme_location' => 'top', 'depth' => 1 ) );
	        get_search_form();
        	?>
        </div><!-- .top-navigation--main-menu -->
	</nav><!-- #site-navigation -->  	
	<?php
}

if ( ! function_exists( 'flacso_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 */
function flacso_paging_nav() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'flacso' ); ?></h1>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'flacso' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'flacso' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'flacso_adv_search_nav' ) ) :

function flacso_adv_searchget_pagenum_link($page, $link = false, $next = false)
{
	if($link)
	{
		if($next)
		{
			$label = __( 'Next Page &raquo;' );
				
			/**
			 * Filter the anchor tag attributes for the next posts page link.
			 *
			 * @since 2.7.0
			 *
			 * @param string $attributes Attributes for the anchor tag.
			*/
			$attr = apply_filters( 'next_posts_link_attributes', '' );
			
			return '<a href="' . "javascript:flacso_adv_searchget_pagenum(".$page.");" . "\" $attr>" . preg_replace('/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $label) . '</a>';
		}
		else 
		{
			$label = __( '&laquo; Previous Page' );
				
			/**
			 * Filter the anchor tag attributes for the previous posts page link.
			 *
			 * @since 2.7.0
			 *
			 * @param string $attributes Attributes for the anchor tag.
			*/
			$attr = apply_filters( 'previous_posts_link_attributes', '' );
			return '<a href="' . "javascript:flacso_adv_searchget_pagenum(".$page.");" . "\" $attr>". preg_replace( '/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $label ) .'</a>';
		}
	}
	return "javascript:flacso_adv_searchget_pagenum(".$page.");";
}

/**
 * Display navigation to next/previous set of posts when applicable.
*/
function flacso_adv_search_nav()
{
	if( is_singular() )
		return;
	
	global $wp_query;
	
	/** Stop execution if there's only 1 page */
	if( $wp_query->max_num_pages <= 1 )
		return;
	
	global $paged;
	
	$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
	$max   = intval( $wp_query->max_num_pages );
	
	/** Add current page to the array */
	if ( $paged >= 1 )
		$links[] = $paged;
	
	/** Add the pages around the current page to the array */
	if ( $paged >= 3 ) {
		$links[] = $paged - 1;
		$links[] = $paged - 2;
	}
	
	if ( ( $paged + 2 ) <= $max ) {
		$links[] = $paged + 2;
		$links[] = $paged + 1;
	}
	
	echo '<div class="navigation"><ul>' . "\n";
	
	/** Previous Post Link */
	if ( get_previous_posts_link() )
		printf( '<li>%s</li>' . "\n", flacso_adv_searchget_pagenum_link($paged - 1, true) );
	
	/** Link to first page, plus ellipses if necessary */
	if ( ! in_array( 1, $links ) ) {
		$class = 1 == $paged ? ' class="active"' : '';
	
		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, ( flacso_adv_searchget_pagenum_link( 1 ) ), '1' );
	
		if ( ! in_array( 2, $links ) )
			echo '<li>…</li>';
	}
	
	/** Link to current page, plus 2 pages in either direction if necessary */
	sort( $links );
	foreach ( (array) $links as $link ) {
		$class = $paged == $link ? ' class="active"' : '';
		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, ( flacso_adv_searchget_pagenum_link( $link ) ), $link );
	}
	
	/** Link to last page, plus ellipses if necessary */
	if ( ! in_array( $max, $links ) ) {
		if ( ! in_array( $max - 1, $links ) )
			echo '<li>…</li>' . "\n";
	
		$class = $paged == $max ? ' class="active"' : '';
		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, ( flacso_adv_searchget_pagenum_link( $max ) ), $max );
	}
	
	/** Next Post Link */
	if ( get_next_posts_link() )
		printf( '<li>%s</li>' . "\n", flacso_adv_searchget_pagenum_link($paged + 1, true, true) );
	
	echo '</ul><input type="hidden" name="adv-search-paged" value="'.$paged.'"> </div>' . "\n";
}
endif;

if ( ! function_exists( 'flacso_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 */
function flacso_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'flacso' ); ?></h1>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="nav-previous">%link</div>', _x( '<span class="meta-nav">&larr;</span>&nbsp;%title', 'Previous post link', 'flacso' ) );
				next_post_link(     '<div class="nav-next">%link</div>',     _x( '%title&nbsp;<span class="meta-nav">&rarr;</span>', 'Next post link',     'flacso' ) );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'flacso_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function flacso_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	echo '<span class="posted-on">' . $time_string . '</span>';

}
endif;

if ( ! function_exists( 'flacso_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function flacso_entry_footer() {
	// Source
	flacso_the_source();
	
	// Hide category and tag text for pages.
	if ( in_array( get_post_type(), array( 'post', 'publication' ) ) ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( __( ', ', 'flacso' ) );
		if ( $categories_list && flacso_categorized_blog() ) {

			echo '<div class="cat-links tax-links"><span class="tax-name">' . __( 'Categorias', 'flacso' ) . '</span>' . $categories_list . '</div>';
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', __( ', ', 'flacso' ) );
		if ( $tags_list ) {
			echo '<div class="tag-links tax-links"><span class="tax-name">' . __( 'Tags', 'flacso' ) . '</span>' . $tags_list . '</div>';
		}
	}

	edit_post_link( __( 'Edit', 'flacso' ), '<span class="edit-link">', '</span>' );
}
endif;

if ( ! function_exists( 'flacso_entry_share' ) ) :
/**
 * Prints HTML with share buttons
 */
function flacso_entry_share() {

	$permalink = wp_get_shortlink();
	?>
	<!-- ul? -->
	<ul class="entry-share__list clearfix">
		<li class="entry-share__item"><?php _e( 'Share', 'flacso' ); ?></li>
		<li class="entry-share__item entry-share__item--twitter"><a href="https://twitter.com/home?status=<?php echo $permalink; ?>" class="share-link share-link--twitter icon-twitter">Twitter</a></li>
		<li class="entry-share__item entry-share__item--facebook"><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $permalink; ?>" class="share-link share-link--facebook icon-facebook">Facebook</a></li>
		<li class="entry-share__item entry-share__item--googleplus"><a href="https://plus.google.com/share?url=<?php echo $permalink; ?>" class="share-link share-link--googleplus icon-gplus">Google+</a></li>
	</ul>
	<?php
}
endif;

if ( ! function_exists( 'flacso_the_publication_types' ) ) :
/**
 * Prints HTML with all the publication types linked to their
 * archive. Used on front page and inside Library Widget
 */
function flacso_the_publication_types() {
	$a = array(
		'orderby' 	=> 'name',
		'order' 	=> 'ASC',
		'parent' 	=> 0,
	);

	$publications = get_terms( 'publication-type', $a );
	?>
	<?php if ( $publications ) : ?>
	<ul class="taxonomy-list clear">
		<?php foreach( $publications as $publication ) : ?>
			<?php $publication_link = get_term_link( $publication ); ?>
			<li class="">
				<a href="<?php echo $publication_link; ?>">
	    		<?php
	    		if ( function_exists( 'get_tax_meta' ) ) {
	    			echo '<span class="icon icon--rounded ' . get_tax_meta( $publication->term_id, 'flacso_icon_picker' ) . '"></span>';
	    		}
		    	?>
			   	<?php echo $publication->name; ?>
			    </a>
		    </li><!-- .general-list__item.media -->
	    <?php endforeach; ?>
	</ul><!-- .taxonomy-list -->
	<?php
	endif;
}
endif;

if ( ! function_exists( 'flacso_the_publication_download_list' ) ) :
/**
 * Creates a list with general publication info
 */
function flacso_the_publication_general_info() {
	// Publication type
	flacso_the_terms( 'publication-type', '', true, true );

	// Publication year
	flacso_the_terms( 'year', ' &bull; ' );

	// Publication country
	flacso_the_terms( 'country', ' &bull; ' );
}
endif;

if ( ! function_exists( 'flacso_the_publication_download_list' ) ) :
/**
 * Prints HTML with share buttons
 */
function flacso_the_publication_download_list() {
	$allowed_medias = array(
		'application/pdf', // PDF (.pdf)
		'application/msword', // Microsoft Word (.doc)
		'application/vnd.oasis.opendocument.text' // OpenOffice (.odt)
	);

	$documents = get_attached_media( $allowed_medias );

	if ( $documents ) : ?>
		<div class="entry-download">
			<?php foreach ( $documents as $document ) : ?>
				<?php
				$terms = get_the_terms( $document->ID, 'language' );
										
				if ( $terms && ! is_wp_error( $terms ) ) {

					$term_list = array();
					$documents_list = '';

					foreach ( $terms as $term ) {
						$term_list[] = $term->name;
					}
		
					$documents_list =  __( 'Download', 'flacso' ) . ' (' . join( ', ', $term_list ) . ')';
				}
				else {
					$documents_list =  __( 'Download', 'flacso' );
				}
				?>

				<a class="button download-link btn-block" href="<?php echo $document->guid; ?>"><?php echo $documents_list; ?></a>
			<?php endforeach; ?>
		</div><!-- .entry-download -->
	<?php
	endif;
}
endif;

if ( ! function_exists( 'flacso_the_source' ) ) :
/**
 * Prints HTML with a mix between 'fonte' and 'url' custom fields
 */
function flacso_the_source() {
	global $post;

	$url = get_post_meta( $post->ID, 'url', true );
	if ( ! empty ( $source ) ) {
		$url = esc_url( $url );
	}

	$source = get_post_meta( $post->ID, 'fonte', true );
	if ( empty ( $source ) && ! empty ( $url ) ) {
		$source_link = '<a href="' . $url . '">' . $url . '</a>';
		printf( '<div class="source">' . __( 'Source: %1$s', 'flacso' ) . '</div>', $source_link );
	}
	elseif ( ! empty ( $source ) && ! empty ( $url ) ) {
		$source_link = '<a href="' . $url . '">' . $source . '</a>';
		printf( '<div class="source">' . __( 'Source: %1$s', 'flacso' ) . '</div>', $source_link );
	}
	elseif ( ! empty ( $source ) ) {
		printf( '<div class="source">' . __( 'Source: %1$s', 'flacso' ) . '</div>', $source );
	}
}
endif;

if ( ! function_exists( 'flacso_the_terms' ) ) :
/**
 * Prints HTML with the selected taxonomy
 * @param  string  $taxonomy The taxonomy slug
 * @param  string  $before 	 The html printed before
 * @param  boolean $link     Whether to print the terms with links
 */
function flacso_the_terms( $taxonomy = '', $before = '', $link = false, $adv_search = false ) {
	global $post;

	if ( empty ( $taxonomy ) ) {
		return;
	}

	$terms = get_the_terms( $post->ID, $taxonomy );
							
	if ( $terms && ! is_wp_error( $terms ) ) { 

		$terms_list = array();

		foreach ( $terms as $term ) {
			if ( $link )
			{
				if($adv_search)
				{
					$terms_list[] = '<a href="javascript:flacso_tax_click(\''.$taxonomy.'\', '.$term->term_id.');">' . $term->name . '</a>';
				}
				else
				{
					$terms_list[] = '<a href="' . get_term_link( $term->slug, $taxonomy ) . '">' . $term->name . '</a>';
				}
			}
			else {
				$terms_list[] = $term->name;
			}
		}
							
		$output = join( ", ", $terms_list );
		$output = '<span class="tax-term tax-term--' . $taxonomy . '">' . $output . '</span>';

		if ( ! empty ( $before ) ) {
			$output = '<span class="tax-term--sep">' . $before . '</span>' . $output;
		}

		echo $output;

	}

}
endif;

if ( ! function_exists( 'flacso_the_agenda_list' ) ) :
/**
 * Prints HTML with dates, place and source link for Agenda post type
 */
function flacso_the_agenda_list() {
	global $post;
	?>
	
	<ul class="entry-agenda list-unstyled">
		<?php if ( $date_start = get_post_meta( $post->ID, '_data_inicial', true ) ) : ?>
		<li class="entry-agenda__item">
			<span>Data</span>
			<?php
			$date_end = get_post_meta( $post->ID, '_data_final', true );
			if ( $date_end && $date_end != $date_start ) :
				/* translators: Initial & final date for the event */
				printf(
					__( '%1$s to %2$s', 'flacso' ),
					date( 'd/m/y', strtotime( $date_start ) ),
					date( 'd/m/y', strtotime( $date_end ) )
				);
			else :
				echo date( 'd/m/y', strtotime( $date_start ) );
			endif;
			?>
		</li><!-- .entry-agenda__item -->
		<?php endif; ?>
		
		<?php if ( $time = get_post_meta( $post->ID, '_horario', true ) ) : ?>
		<li class="entry-agenda__item">
			<span>Horário</span>
			<?php echo $time; ?>
		</li><!-- .entry-agenda__item -->
		<?php endif; ?>
		
		<?php if ( $location = get_post_meta( $post->ID, '_onde', true ) ) : ?>
		<li class="entry-agenda__item">
			<span>Local</span>
			<?php echo $location; ?>
		</li><!-- .entry-agenda__item -->
		<?php endif; ?>
		
		<?php if ( $link = get_post_meta( $post->ID, '_link', true ) ) : ?>
		<li class="entry-agenda__item">
			<span>Mais informações</span>
			<a href="<?php echo esc_url( $link ); ?>"><?php echo esc_url( $link ); ?></a>
		</li><!-- .entry-agenda__item -->
		<?php endif; ?>
	</ul><!-- .entry-agenda -->
	<?php
}
endif;

if ( ! function_exists( 'flacso_the_dummy_image' ) ) :
/**
 * Prints HTML for a dummy image
 */
function flacso_the_dummy_image( $post_type = 'post' ) {

	if ( $post_type == 'publication' ) {
		$placeholder_size = '176x234';	
	}
	else {
		$placeholder_size = '150x150';
	}

	echo '<img alt="Image" src="http://placehold.it/' . $placeholder_size . '/eeeeee/cccccc&text=Imagem" />';

}
endif;


if ( ! function_exists( 'the_archive_title' ) ) :
/**
 * Shim for `the_archive_title()`.
 *
 * Display the archive title based on the queried object.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the title. Default empty.
 * @param string $after  Optional. Content to append to the title. Default empty.
 */
function the_archive_title( $before = '', $after = '' ) {
	if ( is_category() ) {
		$title = sprintf( __( 'Category: %s', 'flacso' ), single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		$title = sprintf( __( 'Tag: %s', 'flacso' ), single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		$title = sprintf( __( 'Author: %s', 'flacso' ), '<span class="vcard">' . get_the_author() . '</span>' );
	} elseif ( is_year() ) {
		$title = sprintf( __( 'Year: %s', 'flacso' ), get_the_date( _x( 'Y', 'yearly archives date format', 'flacso' ) ) );
	} elseif ( is_month() ) {
		$title = sprintf( __( 'Month: %s', 'flacso' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'flacso' ) ) );
	} elseif ( is_day() ) {
		$title = sprintf( __( 'Day: %s', 'flacso' ), get_the_date( _x( 'F j, Y', 'daily archives date format', 'flacso' ) ) );
	} elseif ( is_tax( 'post_format', 'post-format-aside' ) ) {
		$title = _x( 'Asides', 'post format archive title', 'flacso' );
	} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
		$title = _x( 'Galleries', 'post format archive title', 'flacso' );
	} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
		$title = _x( 'Images', 'post format archive title', 'flacso' );
	} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
		$title = _x( 'Videos', 'post format archive title', 'flacso' );
	} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
		$title = _x( 'Quotes', 'post format archive title', 'flacso' );
	} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
		$title = _x( 'Links', 'post format archive title', 'flacso' );
	} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
		$title = _x( 'Statuses', 'post format archive title', 'flacso' );
	} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
		$title = _x( 'Audio', 'post format archive title', 'flacso' );
	} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
		$title = _x( 'Chats', 'post format archive title', 'flacso' );
	} elseif ( is_post_type_archive() ) {
		$title = sprintf( __( 'Archives: %s', 'flacso' ), post_type_archive_title( '', false ) );
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
		$title = sprintf( __( '%1$s: %2$s', 'flacso' ), $tax->labels->singular_name, single_term_title( '', false ) );
	} else {
		$title = __( 'Archives', 'flacso' );
	}

	/**
	 * Filter the archive title.
	 *
	 * @param string $title Archive title to be displayed.
	 */
	$title = apply_filters( 'get_the_archive_title', $title );

	if ( ! empty( $title ) ) {
		echo $before . $title . $after;
	}
}
endif;

if ( ! function_exists( 'the_archive_description' ) ) :
/**
 * Shim for `the_archive_description()`.
 *
 * Display category, tag, or term description.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the description. Default empty.
 * @param string $after  Optional. Content to append to the description. Default empty.
 */
function the_archive_description( $before = '', $after = '' ) {
	$description = apply_filters( 'get_the_archive_description', term_description() );

	if ( ! empty( $description ) ) {
		/**
		 * Filter the archive description.
		 *
		 * @see term_description()
		 *
		 * @param string $description Archive description to be displayed.
		 */
		echo $before . $description . $after;
	}
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function flacso_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'flacso_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'flacso_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so flacso_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so flacso_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in flacso_categorized_blog.
 */
function flacso_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'flacso_categories' );
}
add_action( 'edit_category', 'flacso_category_transient_flusher' );
add_action( 'save_post',     'flacso_category_transient_flusher' );

/**
 * Get pages that use a certain Page Template File
 * @param  string $page_template_file The page template file name
 * @return array  $page_template An array with pages
 */
function flacso_get_page_by_post_template( $page_template_file = '' ) {
	
	if ( empty( $page_template_file ) ) {
		return;
	}

	$page_template = get_posts(array (
		'post_type' => 'page',
		'meta_key' => '_wp_page_template',
		'meta_value' => $page_template_file
	) );

	if ( $page_template ) {
		return $page_template;
	}
}

/**
 * Function to check if a post object is from gea place
 * @param WP_Post|int $post_data null for global post
 * @return boolean
 */
function is_gea($post_data = null)
{
	if( empty($post_data ) )
	{
		$post_data = get_post();
	}
	elseif(is_int($post_data) && $post_data > 0 )
	{
		$post_data = get_post($post_data);
	}
	
	if(is_front_page()) // front can contain both
	{
		return false;
	}
	
	if( empty($post_data ) || ( is_object($post_data) && get_class($post_data) == 'WP_Error' ) )
	{
		if(is_archive())
		{
			$gea_var = get_query_var('gea', false);
			if ($gea_var == 'GEA')
			{
				return true;
			}
		}
		return false;
	}
	
	if($post_data->post_type == 'page')
	{
		if(get_page_template_slug($post_data->ID) == 'page-templates/gea-home.php')
		{
			return true;
		}
		else 
		{
			$pages = get_posts(array(
				'post_type' => 'page',
				'meta_key' => '_wp_page_template',
				'meta_value' => 'page-templates/gea-home.php'
			));
			foreach ($pages as $page)
			{
				if($page->ID == $post_data->ID || $post_data->post_parent == $page->ID || (property_exists($post_data, 'ancestors') && is_array($post_data->ancestors) && in_array($page->ID, $post_data->ancestors) ))
				{
					return true;
				}
			}
		}
	}
	elseif(is_single($post_data))
	{
		if(has_term('gea', 'gea', $post_data))
		{
			return true;
		}
	}
	else 
	{
		$gea_var = get_query_var('gea', false);
		if ($gea_var == 'GEA')
		{
			return true;
		}
	}
	return false;
}

function flacso_filter_tag_link( $termlink, $term_id )
{
	return "javascript:flacso_tax_click('post_tag', $term_id);";
}

add_filter('tag_link', 'flacso_filter_tag_link', 10, 2);
