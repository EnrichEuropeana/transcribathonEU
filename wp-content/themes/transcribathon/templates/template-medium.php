<?php
/**
 * This template displays full width pages.
 *
 * @package vantage
 * @since vantage 1.0
 * @license GPL 2.0
 * 
 * Template Name: Full Medium Page
 */

get_header(); ?>

	<div id="primary-medium-width" class="content-area">
		<div id="content" class="site-content" role="main">
        	<?php echo do_shortcode( '[tutorial_menu]' ); ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

				<?php if ( comments_open() || '0' != get_comments_number() ) : ?>
					<?php comments_template( '', true ); ?>
				<?php endif; ?>

			<?php endwhile; // end of the loop. ?>

		</div><!-- #content .site-content -->
	</div><!-- #primary .content-area -->

<?php get_footer(); ?>