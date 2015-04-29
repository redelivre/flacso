<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Flacso
 */
?>

<div class="col-md-3 col-md-pull-9">
	<div id="secondary" class="widget-area widget-area--main" role="complementary">
		<?php
		if ( is_gea() ) {
			dynamic_sidebar( 'sidebar-main-gea' );
			if ( is_page_template( 'page-templates/library.php' ) ) : ?>
				<div class="flacso-search-adv-entry widget" role="complementary">
					<?php get_search_adv(); ?>
				</div>
			<?php endif;
		}
		else {
			flacso_the_menu();
			if ( is_page_template( 'page-templates/library.php' ) ) : ?>
				<div class="flacso-search-adv-entry widget" role="complementary">
					<?php get_search_adv(); ?>
				</div>
			<?php endif;
			dynamic_sidebar( 'sidebar-main' );
		}
		?>

		
	</div><!-- #secondary -->
</div><!-- .col-md-# -->
