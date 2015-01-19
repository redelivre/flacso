<?php
/**
 * Content for post type 'Agenda'
 * 
 * @package Flacso
 */
?>

<article id="post-<?php the_ID(); ?>">

		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

			<?php if ( has_post_thumbnail() ) : ?>
				<div class="entry-image">
					<?php the_post_thumbnail( 'singular' ); ?>
				</div><!-- .entry-image -->
			<?php endif; ?>
		</header><!-- .entry-header -->

		<?php echo flacso_the_agenda_list(); ?>

		<div class="entry-content">
			<?php the_content(); ?>
			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'flacso' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->

		<div class="entry-share">
			<?php flacso_entry_share(); ?>
		</div><!-- .entry-share -->

		<footer class="entry-footer clear">
			<?php flacso_entry_footer(); ?>
		</footer><!-- .entry-footer -->
</article><!-- #post-## -->
