<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
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
						if(is_gea() && get_query_var('publication-type') == 'cadernos' )
						{
							$title = 'Cadernos do GEA';
							$title = apply_filters( 'get_the_archive_title', $title );?>
							<h1 class="page-title">
								<?php echo $title; ?>
							</h1><?php
						}
						else
						{
							the_archive_title( '<h1 class="page-title">', '</h1>' );
						}
						the_archive_description( '<div class="taxonomy-description">', '</div>' );
						if(is_gea() && get_query_var('publication-type') == 'cadernos' )
						{?>
							<div class="taxonomy-description">
								O periódico Cadernos do GEA é uma publicação semestral que tem por objetivo divulgar reflexões sobre democratização do acesso ao ensino superior e questões relacionadas a políticas públicas de ação afirmativa. Pretende contribuir para a realização de debates com vistas à garantia dos direitos de acesso e permanência à educação pública.
							</div><?php
						}?>
				</header><!-- .page-header -->

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php
						/* Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'content', get_post_type() );
					?>

				<?php endwhile; ?>

				<?php flacso_paging_nav(); ?>

			<?php else : ?>

				<?php get_template_part( 'content', 'none' ); ?>

			<?php endif; ?>

			</main><!-- #main -->
		</div><!-- #primary -->
	</div><!-- .col-md-# -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
