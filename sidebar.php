<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Flacso
 */
?>

<div class="col-md-3 col-md-pull-9">
	<div id="secondary" class="widget-area widget-area--main" role="complementary">
		<?php flacso_the_menu(); ?>
		
		<?php
		if ( is_active_sidebar( 'sidebar-main' ) ) {
			dynamic_sidebar( 'sidebar-main' );
		}
		?>
	</div><!-- #secondary -->
</div><!-- .col-md-# -->
