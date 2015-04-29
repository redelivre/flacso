<?php

/**
 * Register widgets
 *
 * @since Flacso 1.0 
 */
function flacso_register_widgets() {
	register_widget( 'Flacso_Library_Widget' );
	register_widget( 'Flacso_GEA_Documentation_Center' );
}
add_action( 'widgets_init', 'flacso_register_widgets' );

/**
 * Library Widget
 * List all items under 'publication-type' taxonomy
 *
 * @since Flacso 1.0
 */
class Flacso_Library_Widget extends WP_Widget {

	public function __construct() {
		$widget_ops = array( 'classname' => 'widget_flacso_library', 'description' => __( 'Display a list with the elements inside Flacso Library', 'flacso' ) );
		parent::__construct( 'widget_flacso_library', __( 'Flacso Library', 'flacso' ), $widget_ops );
	}

	/**
	 * Outputs the HTML for this widget.
	 *
	 * @param array An array of standard parameters for widgets in this theme
	 * @param array An array of settings for this widget instance
	 * @return void Echoes it's output
	 **/
	function widget( $args, $instance )
	{
		
		if ( is_page_template( 'page-templates/library.php' ) ) // do not show in library template as issue #152
			return;

		/**
		 * Filter the widget title.
		 *
		 * @since 2.6.0
		 *
		 * @param string $title    The widget title. Default 'Pages'.
		 * @param array  $instance An array of the widget's settings.
		 * @param mixed  $id_base  The widget ID.
		 */
		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

		extract($args);
		echo $before_widget;
			
		echo $before_title;
		echo $title;
		echo $after_title;

		flacso_the_publication_types();

		echo $after_widget;

	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args( (array) $new_instance, array( 'title' => '') );
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete( 'widget_flacso_custom_posts', 'widget' );
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = $instance['title'];
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'flacso' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
	<?php
	}
}

/**
 * Library Widget
 * List all items under 'publication-type' taxonomy
 *
 * @since Flacso 1.0
 */
class Flacso_GEA_Documentation_Center extends WP_Widget {

	public function __construct() {
		$widget_ops = array( 'classname' => 'widget_flacso_gea_documentation_widget', 'description' => __( 'The Documentation Center counter widget', 'flacso' ) );
		parent::__construct( 'widget_flacso_gea_documentation_widget', __( 'GEA Documentation Center', 'flacso' ), $widget_ops );
	}

	/**
	 * Outputs the HTML for this widget.
	 *
	 * @param array An array of standard parameters for widgets in this theme
	 * @param array An array of settings for this widget instance
	 * @return void Echoes it's output
	 **/
	function widget( $args, $instance ) {

		/**
		 * Filter the widget title.
		 *
		 * @since 2.6.0
		 *
		 * @param string $title    The widget title. Default 'Documentation Center'.
		 * @param array  $instance An array of the widget's settings.
		 * @param mixed  $id_base  The widget ID.
		 */
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Documentation Center', 'flacso' ) : $instance['title'], $instance, $this->id_base );
		$link = esc_url( $instance['link'] );
		if(array_key_exists('page_id', $instance)) $page_id = (int) $instance['page_id'];
		else $page_id = 0;

		extract($args);

		echo $before_widget;

		if($page_id > 0) echo '<a href="' . get_page_link( $page_id ) . '" class="documentation-center-link">';
			
		echo '<span class="icon icon-globe"></span>';
		echo $before_title;
		echo $title;
		echo $after_title;

		$noticias = new WP_Query( array (
			'post_type' => 'publication',
			'ignore_sticky_posts' => true,
			'tax_query' => array(
				array(
					'taxonomy' => 'gea',
					'field'    => 'slug',
					'terms'    => 'gea',
				),
			),
		) );		

		echo '<p>' . sprintf( __( 'Estão disponíveis %s documentos sobre políticas de educação superior no Brasil e na América Latina.', 'flacso' ), '<span class="documentation-center-counter">' . $noticias->found_posts . '</span>' ) . '</p>';
		echo '<p class="documentation-center-highlight">' . __( 'Clique aqui para acessar o Centro de Documentação e pesquise por tema, fonte, região ou país', 'flacso' ) . '</p>';
		echo '<p>' . __( 'Acesso gratuito para o download de arquivos.', 'flacso' ) . '</p>';

		if($page_id > 0) echo '</a>';

		echo $after_widget;

	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args( (array) $new_instance, array( 'title' => '', 'page_id' => 0 ) );
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['link'] = esc_url( $new_instance['link'] );
		$instance['page_id'] = (int) $new_instance['page_id'];

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete( 'widget_flacso_custom_posts', 'widget' );
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'link' => '', 'page_id' => 0 ) );
		$title = $instance['title'];
		$link = $instance['link'];
		$page_id = $instance['page_id'];
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'flacso' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>"><?php _e( 'Page:', 'flacso' ); ?></label>
			<?php
			$args = array(
		        'id' => $this->get_field_id('page_id'),
		        'name' => $this->get_field_name('page_id'),
		        'selected' => $page_id
		    );
		    wp_dropdown_pages( $args );
		    ?>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>"><?php _e( 'Link:', 'flacso' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'link' ) ); ?>" type="text" value="<?php echo esc_attr( $link ); ?>" />
		</p>
	<?php
	}
}