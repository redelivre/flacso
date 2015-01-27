<?php
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
    	if ( $key=='categories' ) {
      		$new_columns['publication'] = __( 'Publication', 'flacso' );
      	}
    
    	$new_columns[$key] = $title;
  	}

  	return $new_columns;

}
add_filter( 'manage_document_posts_columns' , 'flacso_post_edit_columns' );

/**
 * Custom Post Columns Data
 *
 * @param string $column
 * @param int $post_id
 */
function flacso_post_edit_columns_content( $column, $post_id ) {
	$post_type = 'document';
	$taxonomy = 'publication';
    
    if ( $column == 'publication' ) {
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
