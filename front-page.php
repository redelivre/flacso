<?php
/**
 * The front page template file.
 *
 * @package Flacso
 */

get_header(); ?>

	<div class="row">
		<div class="col-md-4">
			<?php flacso_the_menu(); ?>
	  	</div>

	  	<div class="col-md-8">
	  		<?php
	  		// Featured Page via Customizer
	  		$page_id = (int) get_theme_mod( 'flacso_featured_page');

	  		if ( $page_id > 0 ) :
	  			$featured_page = new WP_Query( array( 'page_id' => $page_id ) );

	  			if ( $featured_page->have_posts() ) : while ( $featured_page->have_posts() ) : $featured_page->the_post(); ?>
		  			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<header class="entry-header">
							<div class="entry-image">
								<a href="<?php the_permalink(); ?>" rel="bookmark">
									<?php
									if ( has_post_thumbnail() ) :
										the_post_thumbnail( 'large' );
									else :
										echo '<img src="http://placehold.it/350x262/">';
									endif;
									?>
								</a>
							</div><!-- .entry-image -->
						</header><!-- .entry-header -->

						<div class="entry-content entry-content--summary lead">
							<?php the_excerpt(); ?>
						</div><!-- .entry-content -->
					</article><!-- #post-## -->
				<?php
				endwhile; endif;
	  		endif;
	  		?>
	  	</div>
	</div><!-- .row -->

	<div class="row">
		<div class="col-md-12">
			<h3>Biblioteca</h3>
		</div>
	</div><!-- .row -->

	<div class="row">
		<div class="col-md-12">
			<h3>Notícias</h3>
		</div>
			<?php
			$noticias = new WP_Query( array ( 'posts_per_page' => 3, 'ignore_sticky_posts' => true ) );

			if ( $noticias->have_posts() ) : while ( $noticias->have_posts() ) : $noticias->the_post(); ?>

				<div class="col-md-4">
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<header class="entry-header">
							<div class="entry-image">
								<a href="<?php the_permalink(); ?>" rel="bookmark">
									<?php
									if ( has_post_thumbnail() ) :
										the_post_thumbnail( 'large' );
									else :
										echo '<img src="http://placehold.it/350x262/">';
									endif;
									?>
								</a>
							</div><!-- .entry-image -->
							
							<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
							<?php if ( 'post' == get_post_type() ) : ?>
							<div class="entry-meta">
								<?php flacso_posted_on(); ?>
							</div><!-- .entry-meta -->
							<?php endif; ?>
						</header><!-- .entry-header -->

						<div class="entry-content entry-content--summary">
							<?php the_excerpt(); ?>
						</div><!-- .entry-content -->
					</article><!-- #post-## -->
				</div>

			<?php endwhile; endif;

			?>
	</div><!-- .row -->

	<div class="row">
		<div class="col-md-12">
			<h3>Destaques</h3>
		</div>
	</div><!-- .row -->

<?php get_footer(); ?>
