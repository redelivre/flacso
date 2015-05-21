<?php
/**
 * Content for post type 'Agenda'
 * 
 * @package Flacso
 */
?>

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
			<div class="entry-meta">
				<?php the_category(' '); ?>
			</div><!-- .entry-meta -->
			<?php the_title( sprintf( '<h1 class="entry-title media-heading"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
		</header><!-- .entry-header -->

		<div class="entry-content entry-content--summary">
			<?php echo flacso_the_agenda_list(); ?>
			<?php the_excerpt(); ?>
		</div><!-- .entry-content -->
	</div><!-- .media-body -->
	<a href="<?php the_permalink(); ?>" class="read-more pull-right"><?php _e( 'Read more', 'flacso' ); ?><span class="more-sign">+</span></a>
</article><!-- #post-## -->
