<?php
/**
 * @package Flacso
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'media' ); ?>>
	
	<div class="entry-image pull-left">
		<?php
		if ( get_post_type() == 'document' ) {
			$image_size = 'document';
		}
		else {
			$image_size = 'thumbnail';
		}
		?>
	
		<a href="<?php the_permalink(); ?>">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( $image_size ); ?>
			<?php else :
				flacso_the_dummy_image( $image_size );
			endif; ?>
		</a>
	</div><!-- .entry-image -->
	
	<div class="media-body">
		<header class="entry-header">
			<?php the_title( sprintf( '<h1 class="entry-title media-heading"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>

			<?php if ( 'post' == get_post_type() ) : ?>
			<div class="entry-meta">
				<?php flacso_posted_on(); ?>
			</div><!-- .entry-meta -->
			<?php endif; ?>
		</header><!-- .entry-header -->

		<div class="entry-content entry-content--summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-content -->
	</div><!-- .media-body -->
	<a href="<?php the_permalink(); ?>" class="read-more pull-right"><?php _e( 'Read more', 'flacso' ); ?><span class="more-sign">+</span></a>
</article><!-- #post-## -->