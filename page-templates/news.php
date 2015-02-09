<?php
/**
 * Template name: News
 *
 * A template for archive-type page with a custom loop
 *
 * @package Flacso
 */

get_header(); ?>
	
	<div class="col-md-9 col-md-push-3">
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">

			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<?php
						the_title( '<h1 class="page-title">', '</h1>' );
						the_archive_description( '<div class="taxonomy-description">', '</div>' );
					?>
				</header><!-- .page-header -->

				<?php

				// Get current page and append to custom query parameters array
				$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

				// Define custom query parameters
				$args = array (
					'post_type'	=> 'post',
					'ignore_sticky_posts' => true,
					'paged' => $paged
				);

				// Instantiate custom query
				$news = new WP_Query( $args );

				// Pagination fix
				$temp_query = $wp_query;
				$wp_query   = NULL;
				$wp_query   = $news;


				$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

				if( $news->have_posts() ) : while( $news->have_posts() ) : $news->the_post();
					get_template_part( 'content' );
				endwhile; endif;

				// Reset postdata
				wp_reset_postdata();
				?>

				<?php flacso_paging_nav(); ?>

			<?php else : ?>

				<?php get_template_part( 'content', 'none' ); ?>

			<?php endif;

			// Reset main query object
			$wp_query = NULL;
			$wp_query = $temp_query;
			?>

			</main><!-- #main -->
		</div><!-- #primary -->
	</div><!-- .col-md-# -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
