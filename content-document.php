<?php
/**
 * Single content for Document post type
 * @package Flacso
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'row' ); ?>>
	
	<div class="col-md-3">
		<div class="entry-image">
			<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( 'document' );
			}
			else {
				flacso_the_dummy_image( 'document' );
			}
			?>
		</div><!-- .entry-image -->
		<?php echo flacso_the_document_download_list(); ?>
	</div>

	<div class="col-md-9">
		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title media-heading">', '</h1>' ); ?>

			<div class="entry-meta">
				<?php

				$country = get_post_meta( $post->ID, 'document-country', true );
				if ( ! empty ( $country ) ) {
					printf( '<div class="document-country">Country: ' . $country . '</div>' );
				}

				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list( __( ', ', 'flacso' ) );
				if ( $categories_list && flacso_categorized_blog() ) {
					printf( '<div class="cat-links">' . __( 'Categories: %1$s', 'flacso' ) . '</div>', $categories_list );
				}

				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', __( ', ', 'flacso' ) );
				if ( $tags_list ) {
					printf( '<div class="tags-links">' . __( 'Tags: %1$s', 'flacso' ) . '</div>', $tags_list );
				}

				$reference = get_post_meta( $post->ID, 'document-reference', true );
				if ( ! empty ( $reference ) ) {
					printf( '<div class="document-reference">Source: ' . $reference . '</div>' );
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
