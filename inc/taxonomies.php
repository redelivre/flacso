<?php

/**
 * New post type Publication
 */
require get_template_directory() . '/inc/publications/publications.php';

function flacso_create_taxs()
{
	// Gea Generic end-place archive
	
	$gea_label = get_theme_mod('flacso_gea_name', false);
	if($gea_label == false)
	{
		$gea_label = "Generic end-place archive";
	}
	
	$labels = array
	(
			"name" => $gea_label,
			"singular_name" => $gea_label,
			"search_items" => __("Search for", 'flacso')." $gea_label",
			"all_items" => __("All", 'flacso')." $gea_label",
			"parent_item" =>  __("Parent", 'flacso')." $gea_label",
			"parent_item_colon" =>  __("Parent", 'flacso')." $gea_label:",
			"edit_item" => __("Edit", 'flacso')." $gea_label",
			"update_item" => __("Update", 'flacso')." $gea_label",
			"add_new_item" => __("Add new", 'flacso')." $gea_label",
			"add_new" => __("Add new", 'flacso'),
			"new_item_name" => __("New", 'flacso')." $gea_label",
			"view_item" => __("View", 'flacso')." $gea_label",
			"not_found" =>  __("No", 'flacso')." $gea_label ".__("found", 'flacso'),
			"not_found_in_trash" => __("No", 'flacso')." $gea_label ".__("found in the trash", 'flacso'),
			"menu_name" => "GEA"
	);
	
	$gea_abreviation = get_theme_mod('flacso_gea_abreviation', false);
	if($gea_abreviation != false)
	{
		$labels['menu_name'] = $gea_abreviation;
	}
	
	$args = array
	(
			'label' => __($gea_label,'flacso'),
			'labels' => $labels,
			'public' => true,
			'capabilities' => array('assign_terms' => 'edit_publications',
					'edit_terms' => 'edit_publications'),
			//'show_in_nav_menus' => true, // Public
			// 'show_ui' => '', // Public
			'hierarchical' => true,
			//'update_count_callback' => '', //Contar objetos associados
			'rewrite' => true,
			//'query_var' => '',
			//'_builtin' => '' // Core
	);
	
	register_taxonomy('gea', array('publication', 'post'), $args);
	
	$taxs = array(
		'Event' => array('event', array('publication', 'post'), true),
		'Areas and Programs' => array('program', array('publication', 'post'), false),
		'Publication Type' => array('publication-type', array('publication'), true),
		'Higher Education' => array('higher-education', array('publication', 'post'), false),
		'Project' => array('project', array('publication', 'post'), true),
		'Countr' => array('country', array('publication', 'post'), 'ies', 'y' ),
		'Year' => array('year', array('publication', 'post'), true),
		'Territor' => array('territory', array('publication', 'post'), 'ies', 'y'),
	);
	
	foreach ( $taxs as $label => $tax)
	{
		if(count($tax) == 4)
		{
			$ret = flacso_register_tax($label, $tax[0], $tax[1], $tax[2], $tax[3]);
		}
		else 
		{
			$ret = flacso_register_tax($label, $tax[0], $tax[1], $tax[2]);
		}
	}
	
	if(!term_exists('2014') )
	{
		for ($i = 2000; $i < 2021; $i++)
		{
			wp_insert_term(
				$i, // the term
				'year', // the taxonomy
				array(
					'description'=> $i,
				)
			);
		}
	}
	
	if(!term_exists(__('National', 'flacso')) )
	{
		foreach ( array(__('National', 'flacso'), __('International', 'flacso'), __('State', 'flacso') ) as $territory )
		{
			wp_insert_term($territory, 'territory');
		}
	}


    // Register new taxonomy which applies to attachments
    
    $tax_language = 'language';

    $labels = array(
        'name'              => 'Languages',
        'singular_name'     => 'Language',
        'search_items'      => 'Search Languages',
        'all_items'         => 'All Languages',
        'parent_item'       => 'Parent Language',
        'parent_item_colon' => 'Parent Language:',
        'edit_item'         => 'Edit Language',
        'update_item'       => 'Update Language',
        'add_new_item'      => 'Add New Language',
        'new_item_name'     => 'New Language Name',
        'menu_name'         => 'Language',
    );
 
    $args = array(
        'labels' 			=> $labels,
        'hierarchical' 		=> true,
        'query_var' 		=> true,
        'rewrite' 			=> true,
        'show_admin_column' => false,
    );
 
    register_taxonomy( $tax_language, 'attachment', $args );

    if( ! term_exists( 'Português', $tax_language ) )
	{
		foreach ( array( 'Português', 'English', 'Español' ) as $term ) {
			wp_insert_term(
				$term,			// The term
				$tax_language	// The taxonomy
			);
		}
	}
	
}
add_action('init', 'flacso_create_taxs');

function flacso_register_tax($name, $slug, $post_types, $plural = true, $single = '')
{

	if(is_bool($plural))
	{
		$s = $plural ? 's' : '';
	}
	elseif(is_string($plural)) 
	{
		$s = $plural;
	}
	
	$labels = array
	(
			"name" => __("{$name}{$s}", "flacso"),
			"singular_name" => __("{$name}{$single}", "flacso"),
			"search_items" => __("Search for {$name}{$s}","flacso"),
			"all_items" => __("All {$name}{$s}","flacso"),
			"parent_item" => __( "Parent {$name}{$single}","flacso"),
			"parent_item_colon" => __( "Parent {$name}{$single}:","flacso"),
			"edit_item" => __("Edit {$name}{$single}","flacso"),
			"update_item" => __("Update {$name}{$single}","flacso"),
			"add_new_item" => __("Add new {$name}{$single}","flacso"),
			"add_new" => __("Add new","flacso"),
			"new_item_name" => __("New {$name}{$single}","flacso"),
			"view_item" => __("View {$name}{$single}","flacso"),
			"not_found" =>  __("No {$name}{$single} found","flacso"),
			"not_found_in_trash" => __("No {$name}{$single} found in the trash","flacso"),
			"menu_name" => __("{$name}{$s}","flacso")
	);
	
	$args = array
	(
			"label" => __("{$name}{$s}","flacso"),
			"labels" => $labels,
			"public" => true,
			"capabilities" => array("assign_terms" => "edit_publications",
					"edit_terms" => "edit_publications"),
			//"show_in_nav_menus" => true, // Public
			// "show_ui" => "", // Public
			"hierarchical" => true,
			//"update_count_callback" => "", //Contar objetos associados
			"rewrite" => true,
			//"query_var" => "",
			//"_builtin" => "" // Core
	);

	// Add another argument if we're using publication type	
	if ( $slug == 'publication-type' ) {
		$args['meta_box_cb'] = 'flacso_publication_type_meta_box';
	}
	
	return register_taxonomy($slug, $post_types, $args);
}

/**
 * Callback function for taxonomy meta boxes
 * 
 * A simple callback function for 'meta_box_cb' argument
 * inside register_taxonomy() that replaces the regular
 * checkboxes with a plain dropdown list
 * 
 * @param  [type] $post [description]
 * @param  [type] $box  [description]
 * @link   http://wordpress.stackexchange.com/a/148965
 */
function flacso_publication_type_meta_box( $post, $box ) {

	$defaults = array( 'taxonomy' => 'category' );
	
	if ( ! isset( $box['args'] ) || ! is_array( $box['args'] ) ) {
	    $args = array();
	}
	else {
	    $args = $box['args'];
	}

	extract( wp_parse_args($args, $defaults), EXTR_SKIP );
	$tax = get_taxonomy( $taxonomy );
	?>
	
	<div id="taxonomy-<?php echo $taxonomy; ?>" class="categorydiv">
	        <?php 
	        $name = ( $taxonomy == 'category' ) ? 'post_category' : 'tax_input[' . $taxonomy . ']';
	        echo "<input type='hidden' name='{$name}[]' value='0' />"; // Allows for an empty term set to be sent. 0 is an invalid Term ID and will be ignored by empty() checks.
	        
	        $term_obj = wp_get_object_terms( $post->ID, $taxonomy ); //_log($term_obj[0]->term_id)

	        if ( ! empty( $term_obj ) ) {
	
		        wp_dropdown_categories( array(
		        	'taxonomy'			=> $taxonomy,
		        	'hide_empty'		=> 0,
		        	'name'				=> "{$name}[]",
		        	'selected'			=> $term_obj[0]->term_id,
		        	'orderby'			=> 'name',
		        	'hierarchical'		=> 0,
		        	'show_option_none'	=> '&mdash;',
		        	'class'				=> 'widefat'
		        ) );
	    	}
	    	else {
	    		echo '<p>' . sprintf( __( 'We could not find any publication types. <a href="%s">Please add one</a>.' ), admin_url( 'edit-tags.php?taxonomy=publication-type' ) )  . '</p>';
	    	}
	        ?>
	</div>
	<?php
}
