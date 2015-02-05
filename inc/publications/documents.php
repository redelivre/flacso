<?php

class Publications
{
	function __construct()
	{
		$this->_customs = array(
			'author' => array
			(
					'slug' => 'custom-author',
					'title' => __('Author', 'flacso'),
					'tip' => __('Publication Author', 'flacso'),
			), 
			'url' => array
			(
					'slug' => 'publication-url',
					'title' => __('URL', 'flacso'),
					'tip' => __('web site address', 'flacso'),
			),
			'country' => array (
					'slug' => 'publication-country',
					'title' => __ ( 'Country', 'flacso' ),
					//'tip' => _n( 'country', 'country', 1, 'flacso' )
			),
			'reference' => array (
					'slug' => 'publication-reference',
					'title' => __ ( 'Reference', 'flacso' ),
					//'tip' => __ ( '', 'flacso' ),
			)
		);
		
		add_action('init', array($this, 'init'));
		//add_action('init', array($this, 'rewrite_rules'));
		//add_action('template_redirect', array($this, 'form'));
		//add_action('wp_ajax_resetpass', array($this, 'form'));
		//add_action('wp_ajax_nopriv_resetpass', array($this, 'form'));
		//add_filter('query_vars', array($this, 'print_variables'));
		//add_filter('archive_template', array($this, 'archiveTemplate'));
		//add_filter('single_template', array($this, 'singleTemplate'));
		add_action( 'save_post', array( $this, 'save' ) );
		//add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts') );
	}

	function init()
	{
		$this->Add_custom_Post();
		
		$permissoes = array(
			'administrator' => array('Novo' => false, 'Caps' => array
			(
				'delete_publications',
				'delete_private_publications',
				'edit_publication',
				'edit_publications',
				'edit_private_publications',
				'publish_publications',
				'read_publication',
				'read_private_publications',
				'delete_published_publications',
				'edit_published_publications',
				'edit_published_publication',
				'edit_others_publications',
				'edit_others_publication',
				'delete_others_publications',
				'delete_others_publication'
			)),
			'contributor' => array('Novo' => false, 'Caps' => array
			(
				'read_publication',
			)),
			'subscriber' => array('Novo' => false, 'Caps' => array
			(
				'read_publication',
			)),
			'author' => array('Novo' => false, 'Caps' => array
			(
				'read_publication',
			)),
			'editor' => array('Novo' => false, 'Caps' => array
			(
				'read_publication',
			)),
		);
		
		$this->roles_install($permissoes);
		
	}
	
	function Add_custom_Post()
	{
		$labels = array
		(
				'name' => __('Publications','flacso'),
				'singular_name' => __('Publication','flacso'),
				'add_new' => __('Add new','flacso'),
				'add_new_item' => __('Add new publication','flacso'),
				'edit_item' => __('Edit publication','flacso'),
				'new_item' => __('New Publication','flacso'),
				'view_item' => __('View Publication','flacso'),
				'search_items' => __('Search Publication','flacso'),
				'not_found' =>  __('Publication not found','flacso'),
				'not_found_in_trash' => __('Publication not found in the trash','flacso'),
				'parent_item_colon' => '',
				'menu_name' => __('Publications','flacso')
	
		);
	
		$args = array
		(
				'label' => __('Publications','flacso'),
				'labels' => $labels,
				'description' => __('Publications','flacso'),
				'public' => true,
				'publicly_queryable' => true, // public
				//'exclude_from_search' => '', // public
				'show_ui' => true, // public
				'show_in_menu' => true,
				'menu_position' => 5,
				'menu_icon' => 'dashicons-book',
				'capability_type' => array('publication','publications'),
				'map_meta_cap' => true,
				'hierarchical' => false,
				'supports' => array('title', 'editor', 'author', 'excerpt', 'trackbacks','thumbnail', 'revisions', 'comments'),
				'register_meta_box_cb' => array($this, 'flacso_publication_custom_meta'), // função para chamar na edição
				'taxonomies' => array('post_tag','category'), // Taxionomias já existentes relaciondas, vamos criar e registrar na sequência
				'permalink_epmask' => 'EP_PERMALINK ',
				'has_archive' => true, // Opção de arquivamento por slug
				'rewrite' => true,
				'query_var' => true,
				'can_export' => true//, // veja abaixo
				//'show_in_nav_menus' => '', // public
				//'_builtin' => '', // Core
				//'_edit_link' => '' // Core
	
		);
	
		register_post_type("publication", $args);
	}
	
	function flacso_publication_custom_meta()
	{
		add_meta_box("publication_meta", __("Publication Details", 'flacso'), array($this, 'publication_meta'), 'publication', 'side', 'default');
		//add_meta_box("second_image_meta", __("Publication Header Image", 'flacso'), array($this, 'second_image_meta'), 'publication', 'side', 'default');
	}
	
	protected $_customs = array();
	
	function getFields()
	{
		$post = array(
			'post_title' => array(
				'slug' => 'post_title',
				'title' => __('Publication name', 'flacso'),
				'tip' => '',
				'required' => true,
				'buildin' => true
			),
			'post_content' => array(
				'slug' => 'post_content',
				'title' => __('Description', 'flacso'),
				'tip' => __('Maximum 300 characters', 'flacso'),
				'required' => true,
				'type' => 'wp_editor',
				'buildin' => true
			),
		);
		
		return array_merge($post, $this->_customs);
	}
	
	function publication_meta()
	{
		global $post;
		
		$custom = get_post_custom($post->ID);
		if(!is_array($custom)) $custom = array();
		
		$disable_edicao = '';
		
		/*if (
				!($post->post_status == 'draft' ||
				$post->post_status == 'auto-draft' ||
				$post->post_status == 'pending')
		)
		{
			$disable_edicao = 'readonly="readonly"';
		}*/
		
		wp_nonce_field( 'publication_meta_inner_custom_box', 'publication_meta_inner_custom_box_nonce' );
		
		foreach ($this->_customs as $key => $campo )
		{
			$slug = $campo['slug'];
			$dado = array_key_exists($slug, $custom) ? array_pop($custom[$slug]) : '';
			
			
			?>
			<p>
				<label for="<?php echo $slug; ?>" class="<?php echo 'label_'.$slug; ?>"><?php echo $campo['title'] ?>:</label>
				<input <?php echo $disable_edicao ?> id="<?php echo $slug; ?>"
					name="<?php echo $slug; ?>"
					class="<?php echo $slug.(array_key_exists('type', $campo) && $campo['type'] == 'date' ? 'hasdatepicker' : '') ; ?> "
					value="<?php echo $dado; ?>" />
			</p>
			<?php
			
		}
	}
	
	function second_image_meta($post)
	{
		$stored_meta = get_post_meta( $post->ID, 'thumbnail2', true)
		?>
		<p>
		    <label for="meta-image" class="flacso-second-image-meta"><?php _e( 'File Upload', 'flacso' )?></label>
		    <input type="text" name="thumbnail2" id="meta-image" value="<?php if ( isset ( $stored_meta ) ) echo $stored_meta; ?>" />
		    <input type="button" id="meta-image-button" class="button" value="<?php _e( 'Choose or Upload an Image', 'flacso' )?>" />
		</p>
		<?php
	}
	
	const NEW_DOCUMENT_PAGE = 'new-publication';
	
	function print_variables($public_query_vars) {
		$public_query_vars[] = self::NEW_DOCUMENT_PAGE;
		return $public_query_vars;
	}
	
	function rewrite_rules()
	{
		add_rewrite_rule(self::NEW_DOCUMENT_PAGE.'(.*)', 'index.php?'.self::NEW_DOCUMENT_PAGE.'=true$matches[1]', 'top');
		flush_rewrite_rules();
	}
	
	function form()
	{
		if(get_query_var(self::NEW_DOCUMENT_PAGE) == true)
		{
			//wp_enqueue_script('jquery-ui-datepicker-ptbr', WP_CONTENT_URL.'/themes/flacso/publications/js/jquery.ui.datepicker-pt-BR.js', array('jquery-ui-datepicker'));
			//wp_enqueue_script('date-scripts',WP_CONTENT_URL.'/themes/flacso/publications/js/date_scripts.js', array( 'jquery-ui-datepicker-ptbr'));
			wp_enqueue_script('new-publication', get_template_directory_uri().'/inc/publications/js/new-publication.js', array( 'jquery'));
			
			get_header();
			$file_path = get_stylesheet_directory() . '/new-publication.php';
			if(file_exists($file_path))
			{
				include $file_path;
			}
			else
			{
				include dirname(__FILE__) . '/new-publication.php';;
			}
			get_footer();
			exit();
		}
	}
	
	/**
	 * Default post information to use when populating the "Write Post" form customized for sulution.
	 *
	 * @since 2.0.0
	 *
	 * @param string $post_type A post type string, defaults to 'post'.
	 * @return WP_Post Post object containing all the default post data as attributes
	 */
	function get_default_post_to_edit( $post_type = 'publication', $create_in_db = false ) {
		global $wpdb;
	
		$post_title = '';
		if ( !empty( $_REQUEST['post_title'] ) )
			$post_title = esc_html( stripslashes( $_REQUEST['post_title'] ));
	
		$post_content = '';
		if ( !empty( $_REQUEST['content'] ) )
			$post_content = esc_html( stripslashes( $_REQUEST['content'] ));
	
		$post_excerpt = '';
		if ( !empty( $_REQUEST['excerpt'] ) )
			$post_excerpt = esc_html( stripslashes( $_REQUEST['excerpt'] ));
	
		if ( $create_in_db ) {
			$post_id = wp_insert_post( array( 'post_title' => __( 'Auto Draft' ), 'post_type' => $post_type, 'post_status' => 'pending' ) );
			$post = get_post( $post_id );
			if ( current_theme_supports( 'post-formats' ) && post_type_supports( $post->post_type, 'post-formats' ) && get_option( 'default_post_format' ) )
				set_post_format( $post, get_option( 'default_post_format' ) );
		} else {
			$post = new stdClass;
			$post->ID = 0;
			$post->post_author = '';
			$post->post_date = '';
			$post->post_date_gmt = '';
			$post->post_password = '';
			$post->post_type = $post_type;
			$post->post_status = 'pending';
			$post->to_ping = '';
			$post->pinged = '';
			$post->comment_status = get_option( 'default_comment_status' );
			$post->ping_status = get_option( 'default_ping_status' );
			$post->post_pingback = get_option( 'default_pingback_flag' );
			$post->post_category = get_option( 'default_category' );
			$post->page_template = 'default';
			$post->post_parent = 0;
			$post->menu_order = 0;
			$post = new WP_Post( $post );
		}
	
		$post->post_content = apply_filters( 'default_content', $post_content, $post );
		$post->post_title   = apply_filters( 'default_title',   $post_title, $post   );
		$post->post_excerpt = apply_filters( 'default_excerpt', $post_excerpt, $post );
		$post->post_name = '';
	
		return $post;
	}
	
	/**
	 * Inclui os arquivos do tema relacionados com
	 * a listagem de publications e retorna o template
	 * a ser usado.
	 *
	 * @param string $archiveTemplate
	 * @return string
	 */
	public function archiveTemplate($archiveTemplate)
	{
		global $post;
	
		if (get_post_type($post) == "publication" || is_post_type_archive('publication'))
		{
			if(file_exists(get_stylesheet_directory()."/archive-publication.php"))
			{
				$archive_template = get_stylesheet_directory()."/archive-publication.php";
			}
			else
			{
				$archiveTemplate = $this->themeFilePath('archive-publication.php');
			}
		}
	
		return $archiveTemplate;
	}
	
	/**
	 * Inclui os arquivos do tema relacionados com
	 * a página de uma publication e retorna o template
	 * a ser usado.
	 *
	 * @param string $singleTemplate
	 * @return string
	 */
	public function singleTemplate($singleTemplate)
	{
		global $post;
	
		if (get_post_type($post) == "publication" || is_post_type_archive('publication'))
		{
			if(file_exists(get_stylesheet_directory()."/single-publication.php"))
			{
				$singleTemplate = get_stylesheet_directory()."/single-publication.php";
			}
			else
			{
				$singleTemplate = $this->themeFilePath('single-publication.php');
			}
		}
	
		return $singleTemplate;
	}
	
	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save( $post_id )
	{
		/*
		 * We need to verify this came from the our screen and with proper authorization,
		* because save_post can be triggered at other times.
		*/
		
		// Check if our nonce is set.
		if ( ! isset( $_POST['publication_meta_inner_custom_box_nonce'] ) )
		{
			return $post_id;
		}
		
		$nonce = $_POST['publication_meta_inner_custom_box_nonce'];
		
		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'publication_meta_inner_custom_box' ) )
		{
			return $post_id;
		}
		
		// If this is an autosave, our form has not been submitted,
		//     so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		{
			return $post_id;
		}
	
		// Check the user's permissions.
		if ( 'publication' == $_POST['post_type'] )
		{
			if ( ! current_user_can( 'edit_publication', $post_id ) )
			{
				return $post_id;
			}
		}
		else
		{
			return $post_id;
		}
	
		/* OK, its safe for us to save the data now. */
		foreach ($this->_customs as $field)
		{
			if(array_key_exists($field['slug'], $_POST))
			{
				// Sanitize the user input.
				$mydata = sanitize_text_field( $_POST[$field['slug']] );
			
				// Update the meta field.
				update_post_meta( $post_id, $field['slug'], $mydata );
			}
		}
		
		if(array_key_exists('thumbnail2', $_POST))
		{
			update_post_meta($post_id, 'thumbnail2', $_POST['thumbnail2']); //TODO more sec
		}
		
	}
	
	/**
	 * Loads the image management javascript
	 */
	function admin_enqueue_scripts()
	{
		global $typenow;
		if( $typenow == 'publication' )
		{
			wp_enqueue_media();
	
			// Registers and enqueues the required javascript.
			wp_register_script( 'meta-box-image', get_template_directory_uri() . '/inc/publications/js/meta-box-image.js', array( 'jquery' ) );
			wp_localize_script( 'meta-box-image', 'meta_image',
			array(
			'title' => __( 'Choose or Upload an Image', 'flacso' ),
			'button' => __( 'Use this image', 'flacso' ),
			)
			);
			wp_enqueue_script( 'meta-box-image' );
		}
	}
	
	static function taxonomy_checklist($taxonomy = 'category', $parent = 0)
	{
		$args = array(
				'orderby' => 'id',
				'hide_empty'=> 0,
				'parent' => $parent,
				'hierarchical' => 0,
				'taxonomy'=>$taxonomy
	
		);
		$terms = get_terms($taxonomy, $args);
		//print_r($terms);
	
		if (!is_array($terms) || ( is_array($terms) && sizeof($terms) < 1 ) )
		{
			return;
		}
		if ($parent > 0)
		{?>
			<ul class='children'><?php
		}
		$index = 1;
		foreach ($terms as $term)
		{
			$name = $term->name;
			$input = '';
			if(strpos($name, '#input#') !== false)
			{
				$name = str_replace('#input#', '', $name);
				$value = array_key_exists($taxonomy.'_'.$term->term_id.'_input', $_REQUEST) ? $_REQUEST[$taxonomy.'_'.$term->term_id.'_input'] : ''; 
				$input = '<input type="text" class="taxonomy-'.$taxonomy.'-checkbox-text" name="'.$taxonomy.'_'.$term->term_id.'_input" id="taxonomy_'.$taxonomy.'_'.$term->slug.'_input" value="'.$value.'" />';
			}
			$checked = 
				isset($_REQUEST) &&
				array_key_exists("taxonomy_$taxonomy", $_REQUEST) &&
				((is_array($_REQUEST["taxonomy_$taxonomy"]) && array_search($term->term_id, $_REQUEST["taxonomy_$taxonomy"]) !== false ) ||
				(is_string($_REQUEST["taxonomy_$taxonomy"]) && $_REQUEST["taxonomy_$taxonomy"] == $term->term_id))
			? 'checked="checked"' : '';
			if($taxonomy == 'category' && get_query_var('cat')) // workaround for cat query var
			{
				$checked = get_query_var('cat') == $term->term_id ? 'checked="checked"' : '';
			}
			?>
			<li class="<?php echo $taxonomy ?>-group-col <?php echo $parent == 0 ? $taxonomy.'-group-col-'.$index : ''; ?>"><?php
				if($parent > 0 && $input == '')
				{?>
					<input type="checkbox" class="taxonomy-<?php echo $taxonomy ?>-checkbox" value="<?php echo $term->term_id; ?>" name="taxonomy_<?php echo $taxonomy; ?>[]" id="taxonomy_<?php echo $taxonomy; ?>_<?php echo $term->slug; ?>"
					<?php echo $checked; ?> autocomplete="off" /><?php
				}?>
				<label for="taxonomy_<?php echo $taxonomy; ?>_<?php echo $term->slug; ?>"><?php
					echo $name;?>
				</label><?php
				echo $input; 
				self::taxonomy_checklist($taxonomy, $term->term_id); ?>
			</li>
			<?php
			$index++;
		}
		if ($parent > 0)
		{?>
			</ul><?php
		}
	}
	
	function roles_install($permissoes)
	{
	
		// Criação das regras
		foreach ($permissoes as $nome => $permisao)
		{
			if($permisao['Novo'] == true)
			{
				$Role = get_role($permisao['From']);
					
				if(!is_object($Role))
				{
					throw new Exception(sprintf(__('Permissão original (%s) não localizada','flacso'),$permisao['From']));
				}
					
				$cap = $Role->capabilities;
				add_role($nome, $permisao["nome"], $cap);
			}
	
			$Role = get_role($nome);
			if(!is_object($Role))
			{
				throw new Exception(sprintf(__('Permissão %s não localizada','flacso'),$nome));
			}
	
			foreach ($permisao['Caps'] as $cap)
			{
					
				$Role->add_cap($cap);
			}
		}
	
	}
	
}

$Publication_global = new Publications();

?>
