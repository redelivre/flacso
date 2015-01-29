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
		__('Configurações do Tema', 'flacso'),
		__('Configurações do Tema', 'flacso'),
		'manage_options',
		'flacso-setting-admin',
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
                do_settings_sections( 'flacso-setting-admin' );
                submit_button("Importar Csv", 'secundary', 'importcsv' );
                submit_button(); 
            ?>
            </form>
            <div id="result">
            </div>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
		if(array_key_exists('page', $_REQUEST) && $_REQUEST['page'] == 'flacso-setting-admin')
		{
			$path = get_template_directory_uri() . '/js';
			wp_register_script('flacso_options_scripts', $path . '/flacso_options_scripts.js', array('jquery'));
			
			wp_enqueue_script('flacso_options_scripts');
					
			wp_localize_script( 'flacso_options_scripts', 'flacso_options_scripts_object',
			array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
		}
		add_action( 'wp_ajax_ImportarCsv', array($this, 'ImportarCsv_callback') );
		add_action( 'wp_ajax_nopriv_ImportarCsv', array($this, 'ImportarCsv_callback') );
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
    		file_put_contents(dirname(__FILE__)."/csv_import.log", print_r($msn."/n", true), FILE_APPEND);
    	}
    	else
    	{
	    	echo $msn;
	    	$msn = str_replace("<br/>", "\n", $msn);
	    	$msn = str_replace("<br>", "\n", $msn);
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
    		$imp->debug = true;
    		$imp->ImportCsV();
    		
    	}
    	echo '</div>';
    	die();
    }

}

if( is_admin() )
    $flacso_settings_page = new FlacsoSettingsPage();
