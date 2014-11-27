<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Flacso
 */
?>

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
					<div class="site-info">
						<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'flacso' ) ); ?>"><?php printf( __( 'Proudly powered by %s', 'flacso' ), 'WordPress' ); ?></a>
						<span class="sep"> | </span>
						<?php printf( __( 'Theme: %1$s by %2$s.', 'flacso' ), 'Flacso', '<a href="http://ethymos.com.br" rel="designer">Ethymos</a>' ); ?>
					</div><!-- .site-info -->
				</div>
			</div><!-- .row -->
		</div><!-- .container -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
