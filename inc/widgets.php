<?php

/**
 * Register widgets
 *
 * @since Flacso 1.0 
 */
function flacso_register_widgets() {

	register_widget( 'Flacso_Library_Widget' );
	
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
	function widget( $args, $instance ) {

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

		$a = array(
			'orderby' 	=> 'name',
			'order' 	=> 'ASC',
			'parent' 	=> 0,
		);

		$publications = get_terms( 'publication-type', $a );
		?>
		<?php if ( $publications ) : ?>
		<ul class="taxonomy-list clear taxonomy-list--inline clear">
			<?php foreach( $publications as $publication ) : ?>
				<?php $publication_link = get_term_link( $publication ); ?>
				<li class="">
					<a href="<?php echo $publication_link; ?>">
		    		<?php
		    		if ( function_exists( 'get_tax_meta' ) ) {
		    			echo '<span class="icon icon--rounded ' . get_tax_meta( $publication->term_id, 'flacso_icon_picker' ) . '"></span>';
		    		}
			    	?>
				   	<?php echo $publication->name; ?>
				    </a>
			    </li><!-- .general-list__item.media -->
		    <?php endforeach; ?>
		</ul><!-- .taxonomy-list -->
		<?php
		endif;


		echo $after_widget;

	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args( (array) $new_instance, array( 'title' => '') );
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
