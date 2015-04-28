<?php
/**
 * Template name: GEA Home Page
 *
 * This template is used for the GEA home page
 *
 * @package Flacso
 */

get_header(); ?>

	<div class="col-md-9 col-md-push-3">
		<section class="feature featured-intro featured-intro--gea">
			<div class="row">
			  	<div class="col-md-12">
			  		<?php while ( have_posts() ) : the_post(); ?>
			  			<article id="post-<?php the_ID(); ?>" <?php post_class( 'clear hentry--no-margin' ); ?>>
			  				<div class="row">
								<div class="col-sm-4">
									<header class="entry-header">
										<?php if ( has_post_thumbnail() ) : ?>
										<div class="entry-image">
											<?php the_post_thumbnail( 'featured' ); ?>
										</div><!-- .entry-image -->
										<?php endif; ?>
									</header><!-- .entry-header -->
								</div>

								<div class="col-sm-8">
									<h1 class="entry-title"><?php the_title(); ?></h1>
									<div class="entry-content">
										<?php the_content(); ?>
									</div><!-- .entry-content -->
									<?php edit_post_link( __( 'Edit', 'flacso' ), '<span class="edit-link">', '</span>' ); ?>
								</div>
							</div><!-- .row -->
						</article><!-- #post-## -->
					<?php endwhile; ?>
			  	</div>
			</div><!-- .row -->
		</section><!-- .featured-intro -->

		<section class="gea-news">
				<div class="row">
					<div class="col-md-7">
						<h4 class="area-title">Notícias</h4>
					</div>
					<div class="col-md-5">
						<?php get_search_form(); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<?php
						$noticias = new WP_Query( array (
							'post_type' => 'post',
							'posts_per_page' => 3,
							'ignore_sticky_posts' => true,
							'tax_query' => array(
								array(
									'taxonomy' => 'gea',
									'field'    => 'slug',
									'terms'    => 'gea',
								),
							),
						) );
						?>
						<?php if ( $noticias->have_posts() ) : while ( $noticias->have_posts() ) : $noticias->the_post(); ?>
							<article id="post-<?php the_ID(); ?>" <?php post_class( 'media archive' ); ?>>
								<div class="entry-image pull-left">
									<a href="<?php the_permalink(); ?>">
										<?php if ( has_post_thumbnail() ) : ?>
											<?php the_post_thumbnail( 'thumbnail' ); ?>
										<?php endif; ?>
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
						<?php endwhile; endif; ?>
						<?php wp_reset_postdata(); ?>
						<a href="<?php get_term_link('GEA', 'gea'); ?>" class="more-news pull-right"><?php _e( 'More News', 'flacso' ); ?></a>
					</div>
				</div><!-- .row -->
		</section><!-- .featured-news -->
	</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
