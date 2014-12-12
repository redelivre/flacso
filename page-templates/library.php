<?php
/**
 * Template name: Library
 *
 * This is the template that displays the library. You can
 * use it to display all your categories in a list view
 *
 * @package Flacso
 */

get_header(); ?>
	
	<div class="col-md-9 col-md-push-3">
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">

				<header class="page-header">
					<?php the_title( '<h1 class="page-title">', '</h1>' ); ?>
				</header><!-- .page-header -->

				<?php
				$args = array(
					'orderby' 	=> 'name',
					'order' 	=> 'ASC',
					'parent' 	=> 0,
				);

				$publications = get_terms( 'publication', $args );
				?>
				<?php if ( $publications ) : ?>
				<div class="general-list">
					<?php foreach( $publications as $publication ) : ?>
					<?php $publication_link = get_term_link( $publication ); ?>
					<div class="general-list__item media clear">
						<a class="pull-left" href="<?php echo $publication_link; ?>">
					    	<img alt="" src="http://placehold.it/150x150/0eafff/ffffff.png" />
						</a>
						<div class="media-body">
						    <h1 class="general-list__title page-title media-heading"><a href="<?php echo $publication_link; ?>"><?php echo $publication->name; ?></a></h1>
						    <?php if ( ! empty( $publication->description ) ) : ?>
						    <div class="taxonomy-description"><?php echo $publication->description; ?></div>
							<?php endif; ?>
						    <a href="<?php echo $publication_link; ?>" class="read-more pull-right"><?php _e( 'Read more', 'flacso' ); ?><span class="more-sign">+</span></a>
						</div><!-- .media-body -->
				    </div><!-- .general-list__item.media -->
				    <?php endforeach; ?>
				</div><!-- .general-list -->
				<?php endif; ?>

			</main><!-- #main -->
		</div><!-- #primary -->
	</div><!-- .col-md-# -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
