<?php get_header(); /* Template Name: Шаблон - Добавить недвижимость (page-add-realty) */ 

$container   = get_theme_mod( 'understrap_container_type' );

?>
<div class="wrapper" id="page-wrapper">

	<div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">

		<div class="row">

			<main class="site-main" id="main">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'loop-templates/content', 'page' ); ?>
					
					<?= Forms::build('add_realty') ?>
					
					<?php
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
					?>

				<?php endwhile; // end of the loop. ?>

			</main><!-- #main -->

		<!-- Do the right sidebar check -->

	</div><!-- .row -->

</div><!-- Container end -->

</div><!-- Wrapper end -->




<?php get_footer(); ?>
