<?php

class FlacsoSettingsPage
{
	/**
	 * Holds the values to be used in the fields callbacks
	 */
	private $options;

	/**
	 * Start up
	 */
	public function __construct()
	{
		add_action( 'admin_menu', array( $this, 'add_theme_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	/**
	 * Add options page
	 */
	public function add_theme_page()
	{
		// This page will be under "Settings"
		add_options_page(
		__('Data Import', 'flacso'),
		__('Data Import', 'flacso'),
		'manage_options',
		'flacso-import-admin',
		array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page()
	{
		// Set class property
		$this->options = get_option( 'flacso_theme_options', array() );
		?>
        <div class="wrap">
            <h2><?php _e('Configurações do Tema Flacso', 'flacso') ?></h2>           
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'flacso_option_group' );   
                do_settings_sections( 'flacso-import-admin' );
                submit_button("Importar Csv", 'secundary', 'importcsv' );
                submit_button("Importar Gea Csv", 'secundary', 'importcsvgea' );
                submit_button("Importar Gea Docs Csv", 'secundary', 'importcsvgeadocs' );
                submit_button(); 
            ?>
            </form>
            <div id="result">
            </div>
        </div>
        <?php
        /**
         * TODO create funtion to reset gea tax
         */
        /*$cat = get_category_by_slug('flacso-midia');
        $args = array(
        	'post_type' => 'post',
        	'posts_per_page' => -1,
        	'category__not_in' => array($cat->term_id),
        	'tax_query' => array(
        			array(
        					'taxonomy' => 'gea',
        					'field'    => 'slug',
        					'terms'    => 'GEA',
        					'operator' => 'NOT IN',
        			)
        	),
        );
        $query = new WP_Query($args);
        while ($query->have_posts())
        {
        	$query->the_post();
        	wp_set_object_terms(get_the_ID(), $cat->term_id, 'category');
        }
        wp_reset_postdata();*/
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
		if(array_key_exists('page', $_REQUEST) && $_REQUEST['page'] == 'flacso-import-admin')
		{
			$path = get_template_directory_uri() . '/js';
			wp_register_script('flacso_options_scripts', $path . '/flacso_options_scripts.js', array('jquery'));
			
			wp_enqueue_script('flacso_options_scripts');
					
			wp_localize_script( 'flacso_options_scripts', 'flacso_options_scripts_object',
			array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
		}
		add_action( 'wp_ajax_ImportarCsv', array($this, 'ImportarCsv_callback') );
		add_action( 'wp_ajax_nopriv_ImportarCsv', array($this, 'ImportarCsv_callback') );
		add_action( 'wp_ajax_ImportarCsvGea', array($this, 'ImportarCsvGea_callback') );
		add_action( 'wp_ajax_nopriv_ImportarCsvGea', array($this, 'ImportarCsvGea_callback') );
		add_action( 'wp_ajax_ImportarCsvGeaDocs', array($this, 'ImportarCsvGeaDocs_callback') );
		add_action( 'wp_ajax_nopriv_ImportarCsvGeaDocs', array($this, 'ImportarCsvGeaDocs_callback') );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
       
        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        _e('Configurações personalizadas do Tema: Flacso', 'flacso');
    }
    
    protected $logfilename = 'csv_import.log';
    
    public static function log($msn, $print_r = false)
    {
    	if($print_r)
    	{
    		print_r($msn."<br/>");
    		file_put_contents(dirname(__FILE__)."/csv_import.log", print_r($msn.PHP_EOL, true), FILE_APPEND);
    	}
    	else
    	{
	    	echo $msn;
	    	$msn = str_replace("<br/>", PHP_EOL, $msn);
	    	$msn = str_replace("<br>", PHP_EOL, $msn);
	    	if(strpos($msn, PHP_EOL) === false ) $msn .= PHP_EOL;
	    	file_put_contents(dirname(__FILE__)."/csv_import.log", $msn, FILE_APPEND);
    	}
    }
    
    public static function newLog()
    {
    	file_put_contents(dirname(__FILE__)."/csv_import.log", date('Y-m-d').'\n');
    }
    
    public function ImportarCsv_callback()
    {
    	FlacsoSettingsPage::newLog();
    	
    	echo '<div id="result">';
    	
    	if(function_exists('mapasdevista_get_coords') )
    	{
    		include_once dirname(__FILE__).'/import/data-importer.php';
    		$imp = new dataImporter();
    		$imp->debug = false;
    		$imp->gea = false;
    		$imp->img_url = "http://flacso.org.br/portal/intranet/flacsomidia/imagenes/";
    		$imp->ImportCsV();
    		
    	}
    	echo '</div>';
    	die();
    }
    
    public function ImportarCsvGea_callback()
    {
    	FlacsoSettingsPage::newLog();
    	 
    	echo '<div id="result">';
    	 
    	if(function_exists('mapasdevista_get_coords') )
    	{
    		include_once dirname(__FILE__).'/import/data-importer.php';
    		$imp = new dataImporter();
    		$imp->debug = false;
    		$imp->gea = true;
    		$imp->img_url = "http://flacso.org.br/gea/administracion/gea_clipping/imagenes/";
    		$imp->ImportCsV('/tmp/importgea.csv');
    
    	}
    	echo '</div>';
    	die();
    }
    
    public function ImportarCsvGeaDocs_callback()
    {
    	FlacsoSettingsPage::newLog();
    
    	echo '<div id="result">';
    
    	if(function_exists('mapasdevista_get_coords') )
    	{
    		include_once dirname(__FILE__).'/import/data-importer.php';
    		$imp = new dataImporter();
    		$imp->debug = false;
    		$imp->gea = true;
    		$imp->img_url = "http://flacso.org.br/gea/documentos/archivos/";
    		$imp->ImportDocsCsV();
    
    	}
    	echo '</div>';
    	die();
    }

}

if( is_admin() )
    $flacso_settings_page = new FlacsoSettingsPage();
