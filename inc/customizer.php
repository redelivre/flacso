<?php
/**
 * Flacso Theme Customizer
 *
 * @package Flacso
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function flacso_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	/*
     * Front Page Content
     */
    $wp_customize->add_section( 'flacso_front_page_content', array(
        'title'    => __( 'Front Page Content', 'flacso' ),
        'priority' => 60,
    ) );
    
    // Featured Page
    $wp_customize->add_setting( 'flacso_featured_page', array(
        'default'       => 1,
        'capability'    => 'edit_theme_options'
    ) );

    $wp_customize->add_control( 'flacso_featured_page', array(
        'label'    	=> __( 'Featured Page', 'flacso' ),
        'type'		=> 'dropdown-pages',
        'section'  	=> 'flacso_front_page_content',
        'settings' 	=> 'flacso_featured_page'
    ) );
    
    /*
     * GEA
    */
    $wp_customize->add_section( 'flacso_gea', array(
    		'title'    => __( 'GEA Settings', 'flacso' ), // TODO Better name
    		//'priority' => 60,
    ) );
    
    // Labels
    $wp_customize->add_setting( 'flacso_gea_name', array(
    		'default'       => '',
    		'capability'    => 'edit_theme_options'
    ) );
    
    $wp_customize->add_control( 'flacso_gea_name', array(
    		'label'    	=> __( 'GEA Custom Label', 'flacso' ),
    		'section'  	=> 'flacso_gea',
    		'settings' 	=> 'flacso_gea_name'
    ) );
    $wp_customize->add_setting( 'flacso_gea_abreviation', array(
    		'default'       => '',
    		'capability'    => 'edit_theme_options'
    ) );
    
    $wp_customize->add_control( 'flacso_gea_abreviation', array(
    		'label'    	=> __( 'GEA Custom Abreviation', 'flacso' ),
    		'section'  	=> 'flacso_gea',
    		'settings' 	=> 'flacso_gea_abreviation'
    ) );

}
add_action( 'customize_register', 'flacso_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function flacso_customize_preview_js() {
	wp_enqueue_script( 'flacso_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'flacso_customize_preview_js' );