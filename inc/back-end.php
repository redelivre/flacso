<?php
/**
 * Remove comments support for pages
 */
function flacso_remove_page_comments_support() {
	remove_post_type_support( 'page', 'comments' );
	remove_post_type_support( 'post', 'comments' );
}
add_action( 'init', 'flacso_remove_page_comments_support' );

/**
 * Manage metaboxes across post types
 */
function flacso_manage_metaboxes() {

	// Remove menu pages
	remove_menu_page( 'edit-comments.php' );  

	// Remove generic meta boxes
	foreach ( array( 'post', 'page', 'publication' ) as $post_type ) {
		remove_meta_box( 'authordiv', $post_type, 'normal' ); // Author Metabox
		remove_meta_box( 'postcustom', $post_type, 'normal' ); // Custom Fields Metabox
		remove_meta_box( 'slugdiv', $post_type, 'normal' ); // Slug Metabox
		remove_meta_box( 'trackbacksdiv', $post_type, 'normal' ); // Trackback Metabox
	}

	
	/**
	 * Remove custom meta box for publication type modified by a callback
	 * and readd it as a high priority meta box
	 *
	 * @see flacso_taxonomy_dropdown_meta_box() The meta box callback
	 */
	remove_meta_box( 'publication-typediv', 'publication', 'side' );
	add_meta_box( 'publication-typediv', __( 'Publication Type', 'flacso' ), 'flacso_taxonomy_dropdown_meta_box', 'publication', 'advanced', 'high', array( 'taxonomy' => 'publication-type' ));

}
add_action( 'admin_menu','flacso_manage_metaboxes' );

/**
 * Set default image link to file
 */
function flacso_set_image_default_link_type() {
	
	$image_set = get_option( 'image_default_link_type' );
	
	if ( $image_set !== 'file' ) {
		update_option( 'image_default_link_type', 'file');
	}
}
add_action( 'admin_init', 'flacso_set_image_default_link_type', 10 );

/**
 * Custom Post Columns
 *
 * @param array $columns
 * @return array $new_columns
 */
function flacso_post_edit_columns( $columns ) {
	// Remove unnecessary columns
	unset(
		$columns['author'],
		$columns['comments']
	);

	// Add custom columns
	$new_columns = array();

  	foreach( $columns as $key => $title ) {
    	if ( $key == 'tags' ) {
      		$new_columns['publication-type'] = __( 'Publication', 'flacso' );
      	}
    
    	$new_columns[$key] = $title;
  	}

  	return $new_columns;

}
add_filter( 'manage_publication_posts_columns' , 'flacso_post_edit_columns' );

/**
 * Custom Post Columns Data
 *
 * @param string $column
 * @param int $post_id
 */
function flacso_post_edit_columns_content( $column, $post_id ) {
	$post_type = 'publication';
	$taxonomy = 'publication-type';
    
    if ( $column == 'publication-type' ) {
	    $terms = get_the_terms( $post_id , $taxonomy );
        
        if ( $terms && ! is_wp_error( $terms ) ) {
        	
        	$output = array();

			foreach ( $terms as $term ) {
				$output[] = '<a href="' . admin_url( 'edit.php?' . $taxonomy . '='.  $term->slug . '&post_type=' . $post_type ) . '">' . $term->name . '</a>';
			}

			echo join( ', ', $output );
								
        }
    }
}
add_action( 'manage_posts_custom_column' , 'flacso_post_edit_columns_content', 10, 2 );

/**
 * Add character counter to the excerpt meta box
 * @link http://wpsnipp.com/index.php/excerpt/add-a-character-counter-to-excerpt-metabox/
 */
function flaso_excerpt_counter(){
    echo '<script>
    jQuery(document).ready(function()
    {
    	if(jQuery("#excerpt").length > 0)
    	{
			jQuery("#postexcerpt .inside").after("<div style=\"background: #f7f7f7; padding: 4px 10px; font-size: 12px;\">Contagem de caracteres: <span id=\"excerpt_counter\"></span></div>");
		    jQuery("#excerpt_counter").val(jQuery("#excerpt").val().length);
		    jQuery("#excerpt").keyup( function()
		    {
		    	jQuery("#excerpt_counter").html(jQuery("#excerpt").val().length);
			});
    	}
	});</script>';
}
add_action( 'admin_head-post.php', 'flaso_excerpt_counter');
add_action( 'admin_head-post-new.php', 'flaso_excerpt_counter');

/**
 * Add and modify admin styles
 */
function flacso_admin_styles() {
    echo'
    <style type="text/css">
    	/* Add dashicons to custom post types */
	    #dashboard_right_now li.publication-count a:before,
		#dashboard_right_now li.publication-count span:before {
		  content: "\f330";
		}
		#dashboard_right_now li.project-count a:before,
		#dashboard_right_now li.project-count span:before {
		  content: "\f322";
		}
		#dashboard_right_now li.agenda-count a:before,
		#dashboard_right_now li.agenda-count span:before {
		  content: "\f145";
		}
    	/* Apply a max-width to dropdowns */
    	.wp-admin select {
    		max-width: 100%;
    	}
    	/* Increase height of excerpt textarea */
        #excerpt {
    		height: 100px;
    		resize: vertical;
    	}
    </style>
    ';
}
add_action( 'admin_head', 'flacso_admin_styles' );

/**
 * Create a metabox checklist with a custom data
 * @param string $name Slug of data for postback
 * @param WP_Post|int $post 
 * @param array $data Format array( item1 => array('id' => value, 'name' => label, 'checked' => true ), item2 => array('id' => value, 'name' => label, , 'checked' => false))
 */
function flacso_metabox_checkbox( $name, $post, $data )
{?>
	<div id="<?php echo $name; ?>-all" class="tabs-panel">
		<ul class="categorychecklist form-no-clear"><?php
			foreach ($data as $data_item)
			{
				$checked = array_key_exists('checked', $data_item) && $data_item['checked'] ? ' checked="checked" ' : ''; ?>
				<li id="<?php echo $name."-".$data_item['id']; ?>" >
					<label class="selectit">
						<input id="in-<?php echo $name."-".$data_item['id']; ?>" type="checkbox" name="<?php echo $name; ?>_input[]" value="<?php echo $data_item['id']; ?>" <?php echo $checked; ?> >
							<?php echo $data_item['name']; ?>
					</label>
				</li><?php 
			}?>
		</ul>
	</div><?php
}

/**
 * Add custom post types to At a Glance dashboard widget
 * 
 * @param  array  $items [description]
 * @return array $items    [description]
 * @link http://www.hughlashbrooke.com/2014/02/wordpress-add-items-glance-widget/
 */
function flacso_manage_dashboard_glance_iems( $items = array() ) {

    $post_types = array( 'publication', 'project', 'agenda' );
    
    foreach( $post_types as $type ) {

        if( ! post_type_exists( $type ) ) {
        	continue;
        }

        $num_posts = wp_count_posts( $type );
        
        if( $num_posts ) {
            
            $published = intval( $num_posts->publish );
            $post_type = get_post_type_object( $type );
            
            $text = _n( '%s ' . $post_type->labels->singular_name, '%s ' . $post_type->labels->name, $published, 'flacso' );
            $text = sprintf( $text, number_format_i18n( $published ) );
            
            if ( current_user_can( $post_type->cap->edit_posts ) ) {
                $output = '<a href="edit.php?post_type=' . $post_type->name . '">' . $text . '</a>';  
                echo '<li class="' . $post_type->name . '-count">' . $output . '</li>'; 
            } else {
                $output = '<span>' . $text . '</span>';  
                echo '<li class="' . $post_type->name . '-count">' . $output . '</li>';  
            }
        }
    }
    
    return $items;
}
add_filter( 'dashboard_glance_items', 'flacso_manage_dashboard_glance_iems', 10, 1 );

/**
 * Manage views above WP List Table
 * 
 * @param  array $views An array with the possible views
 * @return array $new_views The new array
 */
function flacso_manage_views( $views ) {
    global $wp_query;

    $new_views = array();

    $gea_query = array(
        'post_type'   => 'post',
        //'post_status' => 'publish',
    	'tax_query' => array(
    		array(
			'taxonomy' => 'gea',
			'field'    => 'slug',
			'terms'    => 'GEA',
			)
		)
    );

    $result = new WP_Query( $gea_query );

    foreach ( $views as $key => $value ) {

    	if ( $key == 'publish' ) {
    		$class = ( isset( $wp_query->query_vars['gea'] ) && $wp_query->query_vars['gea'] == 'gea' ) ? ' class="current"' : '';

		    $new_views['gea'] = sprintf(
		    	__( '<a href="%s"'. $class .'>GEA <span class="count">(%d)</span></a>', 'flacso' ),
		        admin_url( 'edit.php?post_type=post&gea=gea' ),
		        $result->found_posts
		    );

		    // Readd 'publish' view
		    $new_views[$key] = $value;
    	}
    	else {
    		$new_views[$key] = $value;
    	}
    }

    return $new_views;
}
add_filter( 'views_edit-post', 'flacso_manage_views' );

?>