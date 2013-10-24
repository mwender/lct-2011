<?php 
/*
Template Name: Donate - Authorize.net
*/
wp_enqueue_script('jquery-validate', get_bloginfo('template_directory').'/includes/js/jquery.validate.js', array('jquery'), 1.52);
wp_enqueue_script('jquery-validate-init', get_bloginfo('template_directory').'/includes/js/jquery.validate-init.js' );

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

.entry-content h4{margin: 2em 0 0 0;}
.hidden{display: none}
input, select, textarea{padding: .5em; font-size: 14px;}
input.submit{padding: .5em 1em}
textarea{width: 300px; height: 120px; clear: left; background: none}
label{display: block}
label.error{float: none; color: red; padding: 0 0 0 .25em; vertical-align: top; margin: 0 0 0 20px; border: none; background: none}
label#x_amount{font: 24px/35px bold "Helvetica Neue", Helvetica, Arial, sans-serif; color: #090}
label#x_amount input{text-align: right}
#regular-comments{height: 40px}
</style><?php	
}

get_header();
if( have_posts() ): while( have_posts() ): the_post();
?><h2 class="entry-title"><?php the_title() ?></h2>
<?php 
		if(!isset($_POST['step1'])){
			the_content();
			require_once( trailingslashit(TEMPLATEPATH).'includes/html/donate.form1.php');	
		} else {
			require_once( trailingslashit(TEMPLATEPATH).'includes/html/donate.form2.php');
		}
	endwhile;
else:
	?><p>No post found.</p><?php
endif;
get_footer() ?>