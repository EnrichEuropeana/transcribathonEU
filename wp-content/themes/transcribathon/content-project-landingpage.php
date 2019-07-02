<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package vantage
 * @since vantage 1.0
 * @license GPL 2.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-main">
		<!-- page header, including meta-slider -->
		<?php do_action('vantage_entry_main_top') ?>

		<!-- bubbles -->
		<div class="entry-content bubbles">  
			<a class="bubble search theme-color-background" href="/documents/"><i class="fal fa-search"></i><br />&nbsp;<br />Search <br />documents</a>
			<a class="bubble big transcribe-now theme-color-background" href=""><i class="fal fa-pen"></i><br />&nbsp;<br />Transcribe <br />now</a>
			<a class="bubble help theme-color-background" href=""><i class="fal fa-question-circle"></i><br />&nbsp;<br />How to <br />transcribe</a>
		</div>

		<div class="entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'vantage' ), 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->

		<?php do_action('vantage_entry_main_bottom') ?>

	</div>

</article><!-- #post-<?php the_ID(); ?> -->

		

				