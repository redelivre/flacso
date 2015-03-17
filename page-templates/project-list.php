<?php
/**
 * Template name: Project List
 *
 * This template creates a list of projects separated by their status
 *
 * @package Flacso
 */

get_header(); ?>
	
	<div class="col-md-9 col-md-push-3">
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="page-header">
						<?php the_title( '<h1 class="page-title">', '</h1>' ); ?>
					</header><!-- .page-header -->

					<div class="entry-content">
						<?php the_content(); ?>
						<?php
							wp_link_pages( array(
								'before' => '<div class="page-links">' . __( 'Pages:', 'flacso' ),
								'after'  => '</div>',
							) );
						?>
					</div>
				</article>

				<?php endwhile; ?>

				<?php
				$statuses = array(
					'em-andamento' 	=> __( 'Projetos em andamento', 'flacso' ),
					'realizado'		=> __( 'Projetos realizados', 'flacso' )
				);

				if ( $statuses ) {
					echo '<div class="archive archive--secondary">';
					foreach( $statuses as $status => $title ) {
						$args = array (
							'post_type'			=> 'project',
							'orderby'			=> 'title',
							'order'				=> 'ASC',
							'posts_per_page'	=> -1,
							'tax_query'		=> array(
								array (
									'taxonomy'	=> 'status',
									'field'		=> 'slug',
									'terms'		=> $status
								)
							)
						);

						$projects = new WP_Query( $args );

						if( $projects->have_posts() ) {

							echo '<h3 class="archive--secondary__header">' . $title . '</h3>';

							while( $projects->have_posts() ) : $projects->the_post();

								get_template_part( 'content' );

							endwhile;
						}
					}
					echo '</div>';
				}
				?>

			</main><!-- #main -->
		</div><!-- #primary -->
	</div><!-- .col-md-# -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
