<?php 
wp_enqueue_script( 'jquery-cross-slide', get_bloginfo( 'template_directory' ). '/includes/js/jquery.cross-slide.js', array( 'jquery' ), 0.6 );
wp_enqueue_script( 'cross-slide-init', get_bloginfo('url'). '?getscript=cross-slide-init', array( 'jquery-cross-slide' ), 0.1 );

get_header() ?>
	<?php if( have_posts() ): while( have_posts() ): the_post(); ?>
		<?php the_content() ?>
	<?php	endwhile; ?>
	<?php endif; ?>
	<?php 
	/*
	Displays widgets below home content.
	*/
	if( is_active_sidebar( 'sidebar-front-page' ) ): 
		echo '<div id="sidebar-front-page">';
		dynamic_sidebar( 'sidebar-front-page' );
		echo '</div>';
	endif;
	?>
<?php get_footer() ?>