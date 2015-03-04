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
 * Remove unnecessary metaboxes from posts, pages and publications
 */
function flacso_manage_metaboxes() {

	foreach ( array( 'post', 'page', 'publication' ) as $post_type ) {
		remove_meta_box( 'authordiv', $post_type, 'normal' ); // Author Metabox
		remove_meta_box( 'postcustom', $post_type, 'normal' ); // Custom Fields Metabox
		remove_meta_box( 'slugdiv', $post_type, 'normal' ); // Slug Metabox
		remove_meta_box( 'trackbacksdiv', $post_type, 'normal' ); // Trackback Metabox
	}

	// Remove menu pages
	remove_menu_page( 'edit-comments.php' );  

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
