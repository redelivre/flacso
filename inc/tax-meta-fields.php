<?php
/**
 * Flacso tax meta class
 *
 * Insert meta fields for taxonomies. Current being used only with 
 * 'publication-type' taxonomy under 'Document' post type
 * 
 * @link https://github.com/bainternet/Tax-Meta-Class
 * @package Flacso
 */

function load_custom_wp_admin_style() {
    wp_register_style( 'flacso-icons', get_template_directory_uri() . '/css/flacso.css' );
    wp_enqueue_style( 'flacso-icons' );
}
add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );

//include the main class file
require_once( 'Tax-meta-class/Tax-meta-class.php' );

if ( is_admin() ) {
    /* 
    * prefix of meta keys, optional
    */
    $prefix = 'flacso_';
    /* 
    * configure your meta box
    */
    $config = array(
        'id' => 'flacso_icons',          // meta box id, unique per meta box
        //'title' => 'Demo Meta Box',          // meta box title
        'pages' => array( 'publication-type' ),        // taxonomy name, accept categories, post_tag and custom taxonomies
        'context' => 'normal',            // where the meta box appear: normal (default), advanced, side; optional
        'fields' => array(),            // list of meta fields (can be added by field arrays)
        'local_images' => false,          // Use local or hosted images (meta box images for add/remove)
        'use_with_theme' => true          //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
    );


    /*
    * Initiate your meta box
    */
    $my_meta =  new Tax_Meta_Class($config);

    /*
    * Add fields to your meta box
    */
   
    $icon_prefix = 'icon-';
    $icon_list = array();

    $slices = json_decode( file_get_contents( get_stylesheet_directory() . '/font/config.json' ), true );
   
    if ( $slices ) {
        //$icon_prefix = $slices[]
        $slices = $slices['glyphs'];
        foreach ( $slices as $slice ) {
            $icon_class = $icon_prefix . $slice['css'];
            $icon_list[$icon_class] = '<span class="' . $icon_class . '"></span>';
        }
    }

    $my_meta->addRadio(
        $prefix . 'icon_picker',
        $icon_list,
        array(
            'name'=> __( 'Icon', 'flacso' )
        )
    );

    /*
    * Don't Forget to Close up the meta box decleration
    */
    //Finish Meta Box Decleration
    $my_meta->Finish();
}
