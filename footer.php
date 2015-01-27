<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Flacso
 */
?>
		</div><!-- .row -->
	</div><!-- #content -->

	<footer id="colophon" class="site-footer wrapper" role="contentinfo">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<?php if ( is_active_sidebar( 'sidebar-footer' ) ) : ?>
			        <div id="tertiary" class="widget-area widget-area--footer clear" role="complementary">
			                <?php dynamic_sidebar( 'sidebar-footer' ); ?>
			        </div><!-- .widget-area--footer -->
			    	<?php endif; ?>
				</div>
			</div><!-- .row -->
		</div><!-- .container -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
