<?php
/**
 * The front page template file.
 *
 * @package Flacso
 */

get_header(); ?>

	<section class="feature featured-intro">
		<div class="container">
			<div class="row">
				<div class="col-md-3">
					<?php flacso_the_menu(); ?>
			  	</div>

			  	<div class="col-md-9">
			  		<?php
			  		// Featured Page via Customizer
			  		$page_id = (int) get_theme_mod( 'flacso_featured_page' );

			  		if ( $page_id > 0 ) :
			  			$featured_page = new WP_Query( array( 'page_id' => $page_id ) );

			  			if ( $featured_page->have_posts() ) : while ( $featured_page->have_posts() ) : $featured_page->the_post(); ?>
				  			<article id="post-<?php the_ID(); ?>" <?php post_class( 'clear hentry--no-margin' ); ?>>
				  				<div class="row">
									<div class="col-sm-4 col-md-4">
										<header class="entry-header">
											<?php if ( has_post_thumbnail() ) : ?>
											<div class="entry-image">
												<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_post_thumbnail( 'featured' ); ?></a>
											</div><!-- .entry-image -->
											<?php endif; ?>
										</header><!-- .entry-header -->
									</div>

									<div class="col-sm-8 col-md-8">
										<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
										<div class="entry-content entry-content--summary">
											<?php the_excerpt(); ?>
										</div><!-- .entry-content -->
										<a href="<?php the_permalink(); ?>" class="read-more pull-right"><?php _e( 'Read more', 'flacso' ); ?><span class="more-sign">+</span></a>
									</div>
								</div><!-- .row -->
							</article><!-- #post-## -->
						<?php
						endwhile; endif;
					elseif ( current_user_can( 'edit_theme_options' ) ) : ?>
					<div class="alert alert-info" role="alert">
  						<?php
  						global $wp_customize;
						if ( isset( $wp_customize ) ) {
						    echo 'Você pode destacar uma página de seu interesse no menu à sua esquerda, dentro da opção <em>Front Page Content</em>. Aqui, será mostrado o resumo dessa página e, se houver, a sua imagem destacada.';
						}
						else {
							echo 'Você pode destacar uma página de seu interesse. Aqui, será mostrado o resumo dessa página e, se houver, a sua imagem destacada. <a href="' . admin_url( 'customize.php' ) . '" class="alert-link">Faça isso agora.</a>';
						}
						?>
					</div><!-- .alert.alert-info -->
					<?php
			  		endif;
			  		?>
			  	</div>
			</div><!-- .row -->
		</div><!-- .container -->
	</section><!-- .featured-intro -->

	<section class="wrapper feature featured-category">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h4 class="area-title area-title--secondary">Biblioteca</h4>
					<?php
					// Search for pages that use Library Page Template
					$page_library = flacso_get_page_by_post_template( 'page-templates/library.php' );
					
					$page_library_permalink = "#";
					
					if(is_array($page_library))
					{
						foreach ( $page_library as $page ) {
							// Flacso's library doesn't have a parent
							if ( $page->post_parent == 0 ) {
								$page_library_permalink = get_permalink( $page->ID );
							}
						}
					}
					flacso_the_publication_types(true); ?>
					<div class="col-md-2 read-more--prefix">
						<div class="read-more-content">
							<?php _e('Look', 'flacso'); ?>&nbsp;<a href="<?php echo $page_library_permalink; ?>" class="read-more"><?php _e( 'Full Library', 'flacso' ); ?><span class="more-sign more-sign--alternate">+</span></a>
						</div>
					</div>
				</div>
			</div><!-- .row -->
		</div><!-- .container -->
	</section><!-- .featured-category-->

	<section class="wrapper feature featured-links">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h4 class="area-title">Destaques</h3>
					<div class="col-md-1 col-sm-1 col-xs-2 banners-cycle-button banners-cycle-prev" >
						<div class="banners-cycle-ball" id="banners-cycle-prev">
						</div>
					</div> 
					<div class="col-md-10 col-sm-10 col-xs-8 banners-widget-area">
						<?php
						if ( is_active_sidebar( 'sidebar-banners' ) )
						{
							dynamic_sidebar( 'sidebar-banners' );
						}
						?>
					</div>
					<div class="col-md-1 col-sm-1 col-xs-2 banners-cycle-button" >
						<div class="banners-cycle-ball" id="banners-cycle-next">
						</div>
					</div>
				</h4>
			</div><!-- .row -->
		</div><!-- .container -->
	</section><!-- .featured-links -->

	<section class="wrapper feature featured-news">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h4 class="area-title">Notícias</h3>
						<?php
						$noticias_page = get_page_by_title( 'Notícias' );
						$noticias_permalink = ( ! empty( $noticias_page ) ) ? $noticias_page->guid : '';
						?>
						<a href="<?php echo $noticias_permalink; ?>" class="read-more read-more--absolute"><?php _e( 'Read more', 'flacso' ); ?><span class="more-sign">+</span></a>
				</div>
					<?php
					$noticias = new WP_Query( array ( 'posts_per_page' => 3, 'ignore_sticky_posts' => true ) );

					if ( $noticias->have_posts() ) : while ( $noticias->have_posts() ) : $noticias->the_post(); ?>

						<div class="col-md-4">
							<article id="post-<?php the_ID(); ?>" <?php post_class( 'hentry--columns clear' ); ?>>
								<header class="entry-header">
									<?php if ( has_post_thumbnail() ) : ?>
									<div class="entry-image">
										<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_post_thumbnail( 'archive' ); ?></a>
									</div><!-- .entry-image -->
									<?php endif; ?>

									<div class="entry-meta">
										<?php the_category(' '); ?>
									</div><!-- .entry-meta -->
									
									<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
								</header><!-- .entry-header -->

								<div class="entry-content entry-content--summary">
									<?php the_excerpt(); ?>
								</div><!-- .entry-content -->
								<a href="<?php the_permalink(); ?>" class="read-more pull-right"><?php _e( 'Read more', 'flacso' ); ?><span class="more-sign">+</span></a>
							</article><!-- #post-## -->
						</div>

					<?php endwhile; endif; ?>
			</div><!-- .row -->
		</div><!-- .container -->
	</section><!-- .featured-news -->

<?php get_footer(); ?>
