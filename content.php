<?php
/**
 * @package Flacso
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'media' ); ?>>
	
	<?php if ( has_post_thumbnail() ) : ?>
	<div class="entry-image pull-left">
		<a href="<?php the_permalink(); ?>">
			<?php
			if ( get_post_type() == 'document' ) {
				$image_size = 'document--small';	
			}
			else {
				$image_size = 'thumbnail';
			}

			the_post_thumbnail( $image_size );
			?>
		</a>
	</div><!-- .entry-image -->
	<?php endif; ?>

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
</article><!-- #post-## -->