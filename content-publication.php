<?php
/**
 * Archive content for Publication post type
 * 
 * @package Flacso
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'media' ); ?>>
	
	<?php $post_type = get_post_type(); ?>
	<div class="entry-image pull-left">
		<a href="<?php the_permalink(); ?>">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'publication' ); ?>
			<?php endif; ?>
		</a>
		<?php echo flacso_the_publication_download_list(); ?>
	</div><!-- .entry-image -->
	
	<div class="media-body">
		<header class="entry-header">
			<div class="entry-meta entry-meta--tax entry-meta--basic">
				<?php flacso_the_publication_general_info(); ?>
			</div><!-- .entry-meta -->
			<?php the_title( sprintf( '<h1 class="entry-title media-heading"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>

			<div class="entry-meta">
				<?php
				// Custom author
				$custom_author = get_post_meta( $post->ID, 'custom-author', true );
				if ( ! empty ( $custom_author ) ) {
					printf( '<div class="custom-author">' . __( 'Author: %1$s', 'flacso' ) . '</div>', $custom_author );
				}

				// Source
				flacso_the_source();

				// Higher Education
				flacso_the_terms( 'higher-education', 'Categorias: ' );

				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', __( ', ', 'flacso' ) );
				if ( $tags_list ) {
					printf( '<div class="tags-links">' . __( 'Tags: %1$s', 'flacso' ) . '</div>', $tags_list );
				}
				?>
			</div><!-- .entry-meta -->
		</header><!-- .entry-header -->

		<div class="entry-content entry-content--summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-content -->
	</div><!-- .media-body -->
	<a href="<?php the_permalink(); ?>" class="read-more pull-right"><?php _e( 'Read more', 'flacso' ); ?><span class="more-sign">+</span></a>
</article><!-- #post-## -->