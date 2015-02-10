<?php
/**
 * Single content for publication post type
 * @package Flacso
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'row' ); ?>>

	<header class="entry-header">
		<div class="entry--general-info">
			<?php
			// Project status
			flacso_the_terms( 'status', '' );
			?>
		</div>
		<?php the_title( '<h1 class="entry-title media-heading">', '</h1>' ); ?>

		<div class="entry-meta">
			<?php
			// Coordenation / Coordernação
			$coordenation = get_post_meta( $post->ID, 'coordenacao', true );
			if ( ! empty ( $coordenation ) ) {
				printf( '<div class="coordenation">' . __( 'Coordenação: %1$s', 'flacso' ) . '</div>', $coordenation );
			}

			// Sponsorship / Patrocínio
			$sponsorship = get_post_meta( $post->ID, 'patrocinio', true );
			if ( ! empty ( $sponsorship ) ) {
				printf( '<div class="sponsorship">' . __( 'Patrocínio: %1$s', 'flacso' ) . '</div>', $sponsorship );
			}

			// Funding / Financiamento
			$funding = get_post_meta( $post->ID, 'financiamento', true );
			if ( ! empty ( $funding ) ) {
				printf( '<div class="funding">' . __( 'Financiamento: %1$s', 'flacso' ) . '</div>', $funding );
			}

			// Partnership / Parceria
			$partnership = get_post_meta( $post->ID, 'parceria', true );
			if ( ! empty ( $partnership ) ) {
				printf( '<div class="partnership">' . __( 'Instituições parceiras: %1$s', 'flacso' ) . '</div>', $partnership );
			}
			?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'flacso' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<?php
	$programs = get_post_meta( $post->ID, '_flacso-program-relation', false );
	if ( ! empty ( $programs ) ) :
	?>
	<div class="entry-program">
		<?php
		foreach ( $programs as $program ) :
			$program_links[] = '<a href="' . get_page_link( $program ) . '">' . get_post_field( 'post_title', $program ) . '</a>';
		endforeach;

		printf( __( 'Este projeto pertence ao(s) programa(s) %s', 'flacso' ), join( ', ', $program_links ) );
		?>
		<?php //printf( '<div class="partnership">' . __( 'Instituições parceiras: %1$s', 'flacso' ) . '</div>', $programs ); ?>
	</div>
	<?php endif; ?>

	<div class="entry-share">
		<?php flacso_entry_share(); ?>
	</div><!-- .entry-share -->

</article><!-- #post-## -->
