<?php

/**
 * New post type Document
 */
require get_template_directory() . '/inc/documents/documents.php';

function flacso_create_taxs()
{
	$labels = array
	(
			'name' => __('Document Types', 'flacso'),
			'singular_name' => __('Document Type', 'flacso'),
			'search_items' => __('Search for Document Types','flacso'),
			'all_items' => __('All Document Types','flacso'),
			'parent_item' => __( 'Parent Document Type','flacso'),
			'parent_item_colon' => __( 'Parent Document Type:','flacso'),
			'edit_item' => __('Edit Document Type','flacso'),
			'update_item' => __('Update Document Type','flacso'),
			'add_new_item' => __('Add new Document Type','flacso'),
			'add_new' => __('Add new','flacso'),
			'new_item_name' => __('New Document Type','flacso'),
			'view_item' => __('View Document Type','flacso'),
			'not_found' =>  __('No Document Type found','flacso'),
			'not_found_in_trash' => __('No Document Type found in the trash','flacso'),
			'menu_name' => __('Document Types','flacso')
	);
	
	$args = array
	(
			'label' => __('Document Types','flacso'),
			'labels' => $labels,
			'public' => true,
			'capabilities' => array('assign_terms' => 'edit_documents',
					'edit_terms' => 'edit_documents'),
			//'show_in_nav_menus' => true, // Public
			// 'show_ui' => '', // Public
			'hierarchical' => true,
			//'update_count_callback' => '', //Contar objetos associados
			'rewrite' => true,
			//'query_var' => '',
			//'_builtin' => '' // Core
	);
	
	$ret = register_taxonomy('document_type', array('document'), $args);
	
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
			'capabilities' => array('assign_terms' => 'edit_documents',
					'edit_terms' => 'edit_documents'),
			//'show_in_nav_menus' => true, // Public
			// 'show_ui' => '', // Public
			'hierarchical' => true,
			//'update_count_callback' => '', //Contar objetos associados
			'rewrite' => true,
			//'query_var' => '',
			//'_builtin' => '' // Core
	);
	
	register_taxonomy('gea', array('document', 'post', 'page'), $args);
	
}
add_action('init', 'flacso_create_taxs');