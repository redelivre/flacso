<?php
/**
 * TThe template for displaying the Agenda archive
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Flacso
 */

global $paged;
$showing_past = ( $paged > 0 || ( array_key_exists('eventos', $_GET ) && $_GET['eventos'] == 'passados' ) );

get_header(); ?>

	<div class="col-md-9 col-md-push-3">
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">

				<header class="page-header">
					<?php  if ( $showing_past ) : ?>
						<h1 class="page-title"><?php _e( 'Eventos passados', 'flacso' ); ?></h1>
						<a class="view-events" href="<?php echo remove_query_arg( array('eventos', 'paged') ); ?>"><?php _e( 'Ver eventos futuros &rarr;', 'flacso' ); ?></a>
					<?php else: ?>
						<h1 class="page-title"><?php _e( 'Próximos eventos', 'flacso' ); ?></h1>
						<a class="view-events" href="<?php echo add_query_arg( 'eventos', 'passados' ); ?>"><?php _e( '&larr; Ver eventos passados', 'flacso' ); ?></a>
					<?php endif; ?>
				</header><!-- .page-header -->

				<?php if ( have_posts() ) : ?>
					<?php while ( have_posts() ) : the_post(); ?>
						
						<?php if ( $date_start = get_post_meta( $post->ID, '_data_inicial', true ) ) : ?>

						<article id="post-<?php the_ID(); ?>" <?php post_class( 'media' ); ?>>
							<div class="entry-image pull-left">
								<a href="<?php the_permalink(); ?>">
									<?php if ( has_post_thumbnail() ) : ?>
										<?php the_post_thumbnail( 'thumbnail' ); ?>
									<?php endif; ?>
								</a>
							</div><!-- .entry-image -->
							
							<div class="media-body">
								<header class="entry-header">
									<div class="entry-meta entry-meta--tax">
										<span aria-hidden="true" class="icon-calendar"></span>
										<?php
										$date_end = get_post_meta( $post->ID, '_data_final', true );
										if ( $date_end && $date_end != $date_start ) :
											/* translators: Initial & final date for the event */
											printf(
												__( '%1$s to %2$s', 'flacso' ),
												date( 'd/m/y', strtotime( $date_start ) ),
												date( 'd/m/y', strtotime( $date_end ) )
											);
										else :
											echo date( 'd/m/y', strtotime( $date_start ) );
										endif;
										?>
									</div><!-- .entry-meta -->
									<?php the_title( sprintf( '<h1 class="entry-title media-heading"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
								</header><!-- .entry-header -->

								<div class="entry-content entry-content--summary">
									<?php the_excerpt(); ?>
								</div><!-- .entry-content -->
							</div><!-- .media-body -->
							<a href="<?php the_permalink(); ?>" class="read-more pull-right"><?php _e( 'Read more', 'flacso' ); ?><span class="more-sign">+</span></a>
						</article><!-- #post-## -->

						<?php endif; ?>

					<?php endwhile; ?>
					
					<?php flacso_paging_nav(); ?>

				<?php else : ?>

					<div class="entry-content">
						<?php _e( 'Não há eventos nesta lista' ); ?>
					</div>

				<?php endif; ?>

			</main><!-- #main -->
		</div><!-- #primary -->
	</div><!-- .col-md-# -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
