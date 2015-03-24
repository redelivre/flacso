<?php
/**
 * Template name: Library
 *
 * This is the template that displays the library. You can
 * use it to display all your categories in a list view
 *
 * @package Flacso
 */

get_header(); ?>
	
	<div class="col-md-9 col-md-push-3">
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="page-header">
						<?php the_title( '<h1 class="page-title">', '</h1>' ); ?>
					</header><!-- .page-header -->

					<div class="entry-content">
						<?php the_content(); ?>
						<?php
							wp_link_pages( array(
								'before' => '<div class="page-links">' . __( 'Pages:', 'flacso' ),
								'after'  => '</div>',
							) );
						?>
					</div>
				</article>

				<?php endwhile; ?>

				<?php
				if(get_query_var('publication-type', false) || get_query_var('cat', false) || get_query_var('post_tag', false))
				{
					$tax_query = '"", ""';
					if(get_query_var('publication-type', false))
					{
						$tax_query = '"publication-type", '.get_query_var('publication-type', false);
					}
					elseif(get_query_var('cat', false)) 
					{
						$tax_query = '"cat", '.get_query_var('cat', false);
					}
					else 
					{
						$tax_query = '"post_tag", '.get_query_var('post_tag', false);
					}
					?>
					<div class="general-list archive"><span class="icon-spin6 animate-spin icon--large hidden"></span>
					</div>
					<script type="text/javascript">
					<!--
						jQuery(document).ready(function () {
							flacso_tax_click(<?php echo $tax_query; ?>);
						});
					//-->
					</script>
					<?php
				}
				else 
				{
					$args = array(
						'orderby' 	=> 'name',
						'order' 	=> 'ASC',
						'parent' 	=> 0,
					);
	
					$publications = get_terms( 'publication-type', $args );
					?>
					<?php if ( $publications ) : ?>
					<div class="general-list"><span class="icon-spin6 animate-spin icon--large hidden"></span>
						<?php
						foreach( $publications as $publication ) :
							$publication_link = get_term_link( $publication );
							if( is_gea() )
							{
								$publication_link .= (strpos($publication_link, '?') ? '&' : '?').'gea=GEA';
							}
						?>
						<div class="general-list__item media clear">
							<div class="pull-left">
								<a href="<?php echo 'javascript:flacso_tax_click(\'publication-type\', '.$publication->term_id.');' ?>" class="icon icon--large icon--rounded">
						    	<?php
						    		if ( function_exists( 'get_tax_meta' ) ) {
						    			echo '<span class="' . get_tax_meta( $publication->term_id, 'flacso_icon_picker' ) . '"></span>';
						    		}
						    	?>
						    	</a>
							</div>
							<div class="media-body">
							    <h1 class="general-list__title page-title media-heading"><a href="<?php echo 'javascript:flacso_tax_click(\'publication-type\', '.$publication->term_id.');'; ?>"><?php echo $publication->name; ?></a></h1>
							    <?php if ( ! empty( $publication->description ) ) : ?>
							    <div class="taxonomy-description"><?php echo $publication->description; ?></div>
								<?php endif; ?>
							    <a href="<?php echo 'javascript:flacso_tax_click(\'publication-type\', '.$publication->term_id.');'; ?>" class="read-more pull-right"><?php _e( 'Read more', 'flacso' ); ?><span class="more-sign">+</span></a>
							</div><!-- .media-body -->
					    </div><!-- .general-list__item.media -->
					    <?php endforeach; ?>
					</div><!-- .general-list -->
					<?php endif; 
				}?>
			</main><!-- #main -->
		</div><!-- #primary -->
	</div><!-- .col-md-# -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
