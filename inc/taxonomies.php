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
	
	$labels = array
	(
			'name' => __('Generic end-place archive', 'flacso'),
			'singular_name' => __('Generic end-place archive', 'flacso'),
			'search_items' => __('Search for generic end-place archive','flacso'),
			'all_items' => __('All generic end-place archive','flacso'),
			'parent_item' => __( 'Parent generic end-place archive','flacso'),
			'parent_item_colon' => __( 'Parent generic end-place archive:','flacso'),
			'edit_item' => __('Edit generic end-place archive','flacso'),
			'update_item' => __('Update generic end-place archive','flacso'),
			'add_new_item' => __('Add new generic end-place archive','flacso'),
			'add_new' => __('Add new','flacso'),
			'new_item_name' => __('New generic end-place archive','flacso'),
			'view_item' => __('View generic end-place archive','flacso'),
			'not_found' =>  __('No generic end-place archive found','flacso'),
			'not_found_in_trash' => __('No generic end-place archive found in the trash','flacso'),
			'menu_name' => __('Gea','flacso')
	);
	
	$args = array
	(
			'label' => __('Generic end-place archive','flacso'),
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