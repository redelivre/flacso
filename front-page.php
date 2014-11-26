<?php
/**
 * The front page template file.
 *
 * @package Flacso
 */

get_header(); ?>

	<section class="wrapper featured-intro">
		<div class="container">
			<div class="row">
				<div class="col-md-3">
					<?php flacso_the_menu(); ?>
			  	</div>

			  	<div class="col-md-9">
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
												the_post_thumbnail( 'archive' );
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
		</div><!-- .container -->
	</section><!-- .featured-intro -->

	<section class="wrapper featured-category">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h4 class="area-title area-title--secondary">Biblioteca</h3>
					<?php wp_tag_cloud(); ?>
				</div>
			</div><!-- .row -->
		</div><!-- .container -->
	</section><!-- .featured-category-->

	<section class="wrapper featured-news">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h4 class="area-title">Notícias</h3>
				</div>
					<?php
					$noticias = new WP_Query( array ( 'posts_per_page' => 3, 'ignore_sticky_posts' => true ) );

					if ( $noticias->have_posts() ) : while ( $noticias->have_posts() ) : $noticias->the_post(); ?>

						<div class="col-md-4">
							<article id="post-<?php the_ID(); ?>" <?php post_class( 'hentry--columns' ); ?>>
								<header class="entry-header">
									<div class="entry-meta">
										<?php the_category(', '); ?>
									</div><!-- .entry-meta -->
									<div class="entry-image">
										<a href="<?php the_permalink(); ?>" rel="bookmark">
											<?php
											if ( has_post_thumbnail() ) :
												the_post_thumbnail( 'archive' );
											else :
												echo '<img src="http://placehold.it/350x262/">';
											endif;
											?>
										</a>
									</div><!-- .entry-image -->
									
									<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
								</header><!-- .entry-header -->

								<div class="entry-content entry-content--summary">
									<?php the_excerpt(); ?>
								</div><!-- .entry-content -->
							</article><!-- #post-## -->
						</div>

					<?php endwhile; endif;

					?>
			</div><!-- .row -->
		</div><!-- .container -->
	</section><!-- .featured-news -->

	<section class="wrapper featured-links">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h4 class="area-title">Destaques</h3>
					<img src="http://placehold.it/95" />
					<img src="http://placehold.it/95" />
					<img src="http://placehold.it/95" />
					<img src="http://placehold.it/95" />
					<img src="http://placehold.it/95" />
					<img src="http://placehold.it/95" />
					<img src="http://placehold.it/95" />
					<img src="http://placehold.it/95" />
					<img src="http://placehold.it/95" />
					<img src="http://placehold.it/95" />
					<img src="http://placehold.it/95" />
				</div>
			</div><!-- .row -->
		</div><!-- .container -->
	</section><!-- .featured-links -->

<?php get_footer(); ?>
