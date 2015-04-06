<?php
/**
 * Archive content
 * 
 * @package Flacso
 */
?>

<?php $post_type = get_post_type(); ?>

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
				<?php
					flacso_the_terms('category', '', true, true );
				?>
			</div><!-- .entry-meta -->
			<?php the_title( sprintf( '<h1 class="entry-title media-heading"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>

			<?php if ( 'post' == $post_type ) : ?>
			<div class="entry-meta">
				<?php flacso_posted_on(); ?>
			</div><!-- .entry-meta -->
			<?php endif; ?>
		</header><!-- .entry-header -->
	</div><!-- .media-body -->
</article><!-- #post-## -->