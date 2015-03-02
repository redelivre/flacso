<?php
/**
 * Single content for Project post type
 *
 * @package Flacso
 */

get_header(); ?>

	<div class="col-md-9 col-md-push-3">
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class( 'row' ); ?>>

					<header class="entry-header">
						<div class="entry--general-info">
							<?php
							// Project status
							flacso_the_terms( 'status', '' );
							?>
						</div>
						<?php the_title( '<h1 class="entry-title media-heading">', '</h1>' ); ?>

						<div class="entry-meta">
							<?php
							// Coordenation / Coordernação
							$coordenation = get_post_meta( $post->ID, 'coordenacao', true );
							if ( ! empty ( $coordenation ) ) {
								printf( '<div class="coordenation">' . __( 'Coordenação: %1$s', 'flacso' ) . '</div>', $coordenation );
							}

							// Sponsorship / Patrocínio
							$sponsorship = get_post_meta( $post->ID, 'patrocinio', true );
							if ( ! empty ( $sponsorship ) ) {
								printf( '<div class="sponsorship">' . __( 'Patrocínio: %1$s', 'flacso' ) . '</div>', $sponsorship );
							}

							// Funding / Financiamento
							$funding = get_post_meta( $post->ID, 'financiamento', true );
							if ( ! empty ( $funding ) ) {
								printf( '<div class="funding">' . __( 'Financiamento: %1$s', 'flacso' ) . '</div>', $funding );
							}

							// Partnership / Parceria
							$partnership = get_post_meta( $post->ID, 'parceria', true );
							if ( ! empty ( $partnership ) ) {
								printf( '<div class="partnership">' . __( 'Instituições parceiras: %1$s', 'flacso' ) . '</div>', $partnership );
							}
							?>
						</div><!-- .entry-meta -->
					</header><!-- .entry-header -->

					<div class="entry-content">
						<?php the_content(); ?>
						
						<?php
						/* Program / Project relation */
						$args = array (
							'post_type'	=> 'post',
							'ignore_sticky_posts' => true,
							'posts_per_page' => 5,
							'meta_query' => array(
								array(
									'key'     => '_flacso-project-relation',
									'value'   => $post->ID,
									'compare' => 'IN',
								),
							)
						);

						$news = new WP_Query( $args );

						if( $news->have_posts() ) : ?>
							<div class="archive archive--secondary">
								<h3 class="archive--secondary__header">Últimas notícias do projeto</h3>
								<?php
								while( $news->have_posts() ) : $news->the_post();
									get_template_part( 'content' );
								endwhile; ?>
							</div>
							<?php wp_reset_postdata(); ?>
						<?php endif; ?>
					</div><!-- .entry-content -->

					<?php
					/* Program / Project relation */
					$programs = get_post_meta( $post->ID, '_flacso-program-relation', false );
					if ( ! empty ( $programs ) ) :
					?>
					<div class="entry-program">
						<?php
						foreach ( $programs as $program ) :
							$program_links[] = '<a href="' . get_page_link( $program ) . '">' . trim( get_post_field( 'post_title', $program ) ) . '</a>';
						endforeach;

						printf( __( 'Este projeto pertence ao(s) programa(s) %s', 'flacso' ), join( ', ', $program_links ) );
						?>
						<?php //printf( '<div class="partnership">' . __( 'Instituições parceiras: %1$s', 'flacso' ) . '</div>', $programs ); ?>
					</div>
					<?php endif; ?>

					<div class="entry-share">
						<?php flacso_entry_share(); ?>
					</div><!-- .entry-share -->

				</article><!-- #post-## -->


				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
				?>

			<?php endwhile; // end of the loop. ?>

			</main><!-- #main -->
		</div><!-- #primary -->
	</div><!-- .col-md-# -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
