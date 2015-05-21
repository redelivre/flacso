<?php
/**
 * Single content for Publication post type
 * 
 * @package Flacso
 */

get_header(); ?>

	<div class="col-md-9 col-md-push-3">
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

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
							<div class="entry-meta entry--general-info">
								<?php flacso_the_publication_general_info(); ?>
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

			<?php endwhile; // end of the loop. ?>

			</main><!-- #main -->
		</div><!-- #primary -->
	</div><!-- .col-md-# -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
