<?php
/**
 * Template name: Infoflacso
 *
 * This is the template that displays the library. You can
 * use it to display all your categories in a list view
 *
 * @package Flacso
 */

get_header(); ?>

<script type="text/javascript">
  function resizeIframe(iframe) {
	iframe.height = jQuery('iframe').contents().height() + 4 + "px";
  }
</script>

	<div class="col-md-9 col-md-push-3">
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="page-header">
						<?php the_title( '<h1 class="page-title">', '</h1>' ); ?>
					</header><!-- .page-header -->

					<div class="entry-content">
						<iframe onload="setTimeout(resizeIframe(this), 50);" src="<?php echo admin_url( 'admin-ajax.php' ).'?action=get_infoflacso_content&id='.get_queried_object_id(); ?>" style="width: 100%;" ></iframe>
						<?php
							wp_link_pages( array(
								'before' => '<div class="page-links">' . __( 'Pages:', 'flacso' ),
								'after'  => '</div>',
							) );
						?>

						<?php
						/*
						 * If is child of our page template that serves Programs, display the
						 * related projects
						 */
						$parent_page_template = get_post_meta( $post->post_parent, '_wp_page_template', true );

						if ( $parent_page_template == 'page-templates/child-page-list.php' ) :

							// Loop through the terms
							$statuses = get_terms( 'status', array( 'hide_empty' => true ) );

							foreach ( $statuses as $status ) {

								$args = array (
									'post_type' => 'project',
									'posts_per_page' => -1,
									'meta_key'   => '_flacso-program-relation',
									'meta_value' => $post->ID,
									'tax_query' => array(
										array(
											'taxonomy' => 'status',
											'field'    => 'id',
											'terms'    => array( $status->term_id ),
										)
									)
								);
							
								$projects = new WP_Query( $args );

								if ( $projects->have_posts() ) : ?>

									<h3><?php echo $status->name; ?></h3>
									<ul>
										<?php while ( $projects->have_posts() ) : $projects->the_post(); ?>
											<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><?php edit_post_link( __( 'Editar projeto', 'flacso' ), '&nbsp;<span class="edit-link">', '</span>' ); ?></li>
										<?php endwhile; ?>
									</ul>
								<?php
								endif;

								wp_reset_postdata();
							}

						endif;
						?>
					</div><!-- .entry-content -->
					
					<?php edit_post_link( __( 'Edit', 'flacso' ), '<footer class="entry-footer"><span class="edit-link">', '</span></footer>' ); ?>
					
				</article><!-- #post-## -->


				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
				?>

			<?php endwhile; // end of the loop. ?>

			</main><!-- #main -->
		</div><!-- #primary -->
	</div><!-- .col-md-# -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
