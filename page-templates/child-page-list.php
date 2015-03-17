<?php
/**
 * Template name: Child Page List
 *
 * This template creates a list of child pages (if the page has them)
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
				$args = array (
					'post_type'		=> 'page',
					'post_parent'	=> $post->ID,
					'orderby'		=> 'title',
					'order'			=> 'ASC'
				);

				$child_pages = new WP_Query( $args );

				if( $child_pages->have_posts() ) : while( $child_pages->have_posts() ) : $child_pages->the_post();

					get_template_part( 'content' );

				endwhile; endif;

				?>

			</main><!-- #main -->
		</div><!-- #primary -->
	</div><!-- .col-md-# -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
