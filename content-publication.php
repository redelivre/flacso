<?php
/**
 * Single content for publication post type
 * @package Flacso
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'row' ); ?>>
	
	<div class="col-md-3">
		<div class="entry-image">
			<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( 'publication' );
			}
			else {
				flacso_the_dummy_image( 'publication' );
			}
			?>
		</div><!-- .entry-image -->
		<?php echo flacso_the_publication_download_list(); ?>
	</div>

	<div class="col-md-9">
		<header class="entry-header">
			<div class="entry--general-info">
				<?php
				// Publication type
				flacso_the_terms( 'publication-type', '', true );

				// Publication year
				flacso_the_terms( 'year', ' &bull; ' );

				// Publication country
				flacso_the_terms( 'country', ' &bull; ' );
				?>
			</div>
			<?php the_title( '<h1 class="entry-title media-heading">', '</h1>' ); ?>

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

		<div class="entry-content">
			<?php the_content(); ?>
			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'flacso' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->

		<div class="entry-share">
			<?php flacso_entry_share(); ?>
		</div><!-- .entry-share -->
	</div><!-- .col-md-9 -->

</article><!-- #post-## -->
