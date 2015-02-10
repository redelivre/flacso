<?php

class FlacsoCustomFields
{
	function __construct()
	{
		$this->_customs = array(
			/* EX:  'author' => array
			(
					'slug' => 'custom-author',
					'title' => __('Author', 'flacso'),
					'tip' => __('Document Author', 'flacso'),
			),*/
			'fonte' => array
			(
					'slug' => 'fonte',
					'title' => __('Fonte', 'flacso'),
					//'tip' => __('', 'flacso'),
			),
			'editoria' => array
			(
					'slug' => 'editoria',
					'title' => __('Editoria', 'flacso'),
					//'tip' => __('', 'flacso'),
			),
			'url' => array
			(
					'slug' => 'url',
					'title' => __('Url', 'flacso'),
					'tip' => __('Source Address or external link', 'flacso'),
			)
		);
		
		add_action('init', array($this, 'init'));
		add_action( 'save_post', array( $this, 'save' ) );
		add_action( 'save_post', array( $this, 'save_rel_metas' ) );
		add_action( 'add_meta_boxes_post', array( $this, 'custom_meta' ) );
		add_action( 'add_meta_boxes', array( $this, 'special_metas' ) );
	}

	function init()
	{
		
	}
	
	function custom_meta()
	{
		add_meta_box("custom_post_meta", __("Post Details", 'flacso'), array($this, 'meta'), 'post', 'advanced', 'core');
	}
	
	function special_metas($postType)
	{
		$types = array('post', 'publication');
		if(in_array($postType, $types))
		{
			add_meta_box("project_rel_meta", __("Projeto", 'flacso'), array($this, 'project_rel_meta'), null, 'advanced', 'core');
			add_meta_box("program_rel_meta", __("Areas e Programas", 'flacso'), array($this, 'program_rel_meta'), null, 'advanced', 'core');
		}
	}
	
	protected $_customs = array();
	
	function getFields()
	{
		$post = array(
			
		);
		
		return array_merge($post, $this->_customs);
	}
	
	function meta()
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
		
		wp_nonce_field( 'custom_fields_meta_inner_custom_box', 'custom_fields_meta_inner_custom_box_nonce' );
		
		foreach ($this->_customs as $key => $campo )
		{
			$slug = $campo['slug'];
			$dado = array_key_exists($slug, $custom) ? array_pop($custom[$slug]) : '';
			
			
			?>
			<p>
				<label for="<?php echo $slug; ?>" class="<?php echo 'label_'.$slug; ?>"><?php echo $campo['title'] ?>:</label>
				<input <?php echo $disable_edicao ?> id="<?php echo $slug; ?>"
					name="<?php echo $slug; ?>"
					class="widefat <?php echo $slug.(array_key_exists('type', $campo) && $campo['type'] == 'date' ? 'hasdatepicker' : '') ; ?> "
					value="<?php echo $dado; ?>" />
			</p>
			<?php
		}
		$slug = 'project-rel';
		?>
		<p>
			<label for="<?php echo $slug; ?>" class="<?php echo 'label_'.$slug; ?>"><?php _e('Projeto(s) relacionado(s)', 'flacso'); ?>:</label>
			<input <?php echo $disable_edicao ?> id="<?php echo $slug; ?>"
				name="<?php echo $slug; ?>"
				class="widefat dropdown-project-rel"
				value="<?php echo $dado; ?>" />
		</p>
		<?php
		
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
		if ( ! isset( $_POST['custom_fields_meta_inner_custom_box_nonce'] ) )
		{
			return $post_id;
		}
		
		$nonce = $_POST['custom_fields_meta_inner_custom_box_nonce'];
		
		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'custom_fields_meta_inner_custom_box' ) )
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
		if ( 'post' == $_POST['post_type'] )
		{
			if ( ! current_user_can( 'edit_post', $post_id ) )
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
	}
	
	public function save_rel_metas($post_id = null)
	{
		
		if(is_null($post_id))
		{
			$post_id = get_the_ID();
		}
		
		delete_metadata(get_post_type($post_id), $post_id, '.flacso-project-relation');
		
		if(array_key_exists('project_rel_meta_input', $_POST))
		{
			foreach ($_POST['project_rel_meta_input'] as $id)
			{
				add_post_meta($post_id, '.flacso-project-relation', $id);
				echo "$id : $post_id ";
			}
		}
		
		delete_metadata(get_post_type($post_id), $post_id, '.flacso-program-relation');
		
		if(array_key_exists('program_rel_meta_input', $_POST))
		{
			foreach ($_POST['program_rel_meta_input'] as $id)
			{
				add_post_meta($post_id, '.flacso-program-relation', $id);
				echo "$id : $post_id ";
			}
		}
	} 
	
	public function project_rel_meta()
	{
		global $post;
		global $wp_query;
		
		$temp_query = clone $wp_query;
		$temp_post = clone $post;
		
		$checked = get_post_meta($post->ID, '.flacso-project-relation', false);

		$data = array();
		
		$args = array(
				'post_status' => 'publish',
				'post_type' => 'project',
				'orderby' => 'title',
				'order'   => 'ASC',
		);
		
		$query = new WP_Query($args);
		
		while ( $query->have_posts() )
		{
			$query->the_post();
			$data[] = array('id' => get_the_ID(), 'name' => get_the_title(), 'checked' => in_array(get_the_ID(), $checked));
		}
		
		$wp_query = clone $temp_query;
		$post = clone $temp_post;
		
		wp_reset_postdata();
		
		flacso_metabox_checkbox('project_rel_meta', $post, $data);
	}
	
	public function program_rel_meta()
	{
		global $post;
		global $wp_query;
		
		$temp_query = clone $wp_query;
		$temp_post = clone $post;
		
		$checked = get_post_meta($post->ID, '.flacso-program-relation', false);
		
		$page = false;
		
		$pages = get_pages(array(
			'meta_key' => '_wp_page_template',
			'meta_value' => 'page-templates/child-page-list.php'
		));

		if (is_array($pages) && count($pages) > 0)
		{

			$page = $pages[0];
			
			wp_reset_query();
			
			$args = array(
				'post_parent' => $page->ID,
				'post_status' => 'publish',
				'post_type' => 'page',
				'orderby' => 'title',
				'order'   => 'ASC',
			);
			
			$query = new WP_Query($args);
			
			$data = array();
		
			while ( $query->have_posts() )
			{
				$query->the_post();
				$data[] = array('id' => get_the_ID(), 'name' => get_the_title(), 'checked' => in_array(get_the_ID(), $checked));
			}
		
			flacso_metabox_checkbox('program_rel_meta', $post, $data);
		
		}
		
		$wp_query = clone $temp_query;
		$post = clone $temp_post;
		wp_reset_postdata();
	}
}

$CustomFields_global = new FlacsoCustomFields();

?>