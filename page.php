<?php 
add_action( 'wp_head', 'head_additions' );
function head_additions(){
	global $post;
	if( has_post_thumbnail() ){
		$id = get_post_thumbnail_id();
		$src = wp_get_attachment_image_src( $id, 'feature-image' );
		$feature_image = $src[0];
	} else {
		$feature_image = get_bloginfo( 'template_directory' ). '/includes/images/feature-image-default.jpg';
	}
?><style type="text/css">
#images-frame{background-image: url(<?php echo $feature_image ?>)}
</style><?php	
}

get_header();
if( have_posts() ): while( have_posts() ): the_post();
?><h2 class="entry-title"><?php the_title() ?></h2><?php
/*
	ktree_print_filters_for('template_include');
	/*
	echo '<ol>';
	foreach( $_SERVER as $key => $value){
		echo '<li>'.$key.' => '.$value.'</li>';
	}
	echo '</ol>';
	*/
/**/
 the_content() ?><?php
	endwhile;
else:
	?><p>No post found.</p><?php
endif;
get_footer() ?>