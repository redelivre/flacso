<?php

/**
 * New post type Document
 */
require get_template_directory() . '/inc/documents/documents.php';

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
	
	$taxs = array(
		'Event' => array('event', true),
		'Areas and Programs' => array('program', false),
		'Publications' => array('publication', true),
		'Communication'=> array('communication', true),
		'Higher Education' => array('higher-education', false),
		'Project' => array('project', true)
	);
	
	foreach ( $taxs as $label => $tax)
	{
		$ret = flacso_register_tax($label, $tax[0], array('document', 'post', 'page'), $tax[1]);
	}
	
}
add_action('init', 'flacso_create_taxs');

function flacso_register_tax($name, $slug, $post_types, $plural = true)
{
	$s = $plural ? 's' : '';
	$labels = array
	(
			"name" => __("{$name}{$s}", "flacso"),
			"singular_name" => __("{$name}", "flacso"),
			"search_items" => __("Search for {$name}{$s}","flacso"),
			"all_items" => __("All {$name}{$s}","flacso"),
			"parent_item" => __( "Parent {$name}","flacso"),
			"parent_item_colon" => __( "Parent {$name}:","flacso"),
			"edit_item" => __("Edit {$name}","flacso"),
			"update_item" => __("Update {$name}","flacso"),
			"add_new_item" => __("Add new {$name}","flacso"),
			"add_new" => __("Add new","flacso"),
			"new_item_name" => __("New {$name}","flacso"),
			"view_item" => __("View {$name}","flacso"),
			"not_found" =>  __("No {$name} found","flacso"),
			"not_found_in_trash" => __("No {$name} found in the trash","flacso"),
			"menu_name" => __("{$name}{$s}","flacso")
	);
	
	$args = array
	(
			"label" => __("{$name}s","flacso"),
			"labels" => $labels,
			"public" => true,
			"capabilities" => array("assign_terms" => "edit_documents",
					"edit_terms" => "edit_documents"),
			//"show_in_nav_menus" => true, // Public
			// "show_ui" => "", // Public
			"hierarchical" => true,
			//"update_count_callback" => "", //Contar objetos associados
			"rewrite" => true,
			//"query_var" => "",
			//"_builtin" => "" // Core
	);
	
	return register_taxonomy($slug, $post_types, $args);
}