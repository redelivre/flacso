<?php
/**
 * The front page template file.
 *
 * @package Flacso
 */

get_header(); ?>

	<div class="row">
		<div class="col-md-4">
			<?php flacso_the_menu(); ?>
	  	</div>

	  	<div class="col-md-8">
	  		<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Explicabo quo maxime architecto velit dolores mollitia pariatur amet possimus eaque facere cupiditate, vero iusto sint reiciendis esse adipisci maiores minima odit!</p>
	  	</div>
	</div><!-- .row -->

	<div class="row">
		<div class="col-md-12">
			<h3>Biblioteca</h3>
		</div>
	</div><!-- .row -->

	<div class="row">
		<div class="col-md-12">
			<h3>Notícias</h3>
		</div>
	</div><!-- .row -->

	<div class="row">
		<div class="col-md-12">
			<h3>Destaques</h3>
		</div>
	</div><!-- .row -->

<?php get_footer(); ?>
