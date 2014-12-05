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
					'orderby' => 'name',
					'order' => 'ASC',
					'parent' => 0
				);

				$categories = get_categories($args);
				?>
				<?php if ( $categories ) : ?>
				<div class="general-list">
					<?php foreach( $categories as $category ) : ?>
					<div class="general-list__item clear">
						
					    <h1 class="general-list__title page-title"><a href="<?php echo get_category_link( $category->term_id ); ?>"><?php echo $category->name; ?></a></h1>
					    <?php if ( ! empty( $category->description ) ) : ?>
					    <div class="taxonomy-description"><?php echo $category->description; ?></div>
						<?php endif; ?>
					    <a href="<?php echo get_category_link( $category->term_id ); ?>" class="read-more pull-right"><?php _e( 'Read more', 'flacso' ); ?><span class="more-sign">+</span></a>
				    </div><!-- .general-list__item -->
				    <?php endforeach; ?>
				</div><!-- .general-list -->
				<?php endif; ?>

			</main><!-- #main -->
		</div><!-- #primary -->
	</div><!-- .col-md-# -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
