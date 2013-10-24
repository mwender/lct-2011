<?php
/* Load the core theme framework. */
require_once( trailingslashit( TEMPLATEPATH ) . 'hybrid-core/hybrid.php' );
$theme = new Hybrid();
include_once( TEMPLATEPATH. '/includes/fns/fns.tweets.php' );
include_once( TEMPLATEPATH. '/includes/fns/fns.widgets.php' );

/*
* knoxtree_theme_setup() - initializes the theme
*/
add_action( 'after_setup_theme', 'knoxtree_theme_setup', 10 );
function knoxtree_theme_setup(){
	
	/* Add Hybrid Core supported features */
	add_theme_support( 'hybrid-core-widgets' );
	add_theme_support( 'hybird-core-shortcodes' );
	add_theme_support( 'hybrid-core-seo' );
	add_theme_support( 'hybrid-core-drop-downs' );
	add_theme_support( 'hybrid-core-menus' );
	add_theme_support( 'hybrid-core-post-meta-box' );
	
	/* Add Hybrid Core extensions */
	add_theme_support( 'post-layouts' );
	add_theme_support( 'post-stylesheets' );
	add_theme_support( 'breadcrumb-trail' );
	add_theme_support( 'cleaner-gallery' );
	
	/* Actions and filters */
	add_action('parse_request', 'ktree_parse_request');
	add_filter( 'breadcrumb_trail', 'ktree_breadcrumb_trail' );
	add_filter( 'the_excerpt_rss', 'ktree_rss_post_thumbnail' );
	add_filter( 'the_content_feed', 'ktree_rss_post_thumbnail' );
	add_filter( 'comment_form_default_fields', 'ktree_comment_fields' );
	add_filter( 'comment_form_field_comment' ,'ktree_comment_form_comment_field' );
	
	/* Scripts and Styles */
	//if( is_admin() ) wp_enqueue_script( 'picto-admin', get_bloginfo( 'wpurl' ). '?get=adminjs&type=text/javascript', array( 'jquery' ), '1.0' );
	//if( is_admin() ) wp_enqueue_style( 'picto-admin', get_bloginfo( 'wpurl' ). '?get=admincss&type=text/css', null, '1.0' );
	//if( !is_admin() ) wp_enqueue_script( 'hoverintent', get_bloginfo( 'template_directory' ). '/includes/js/superfish/js/hoverIntent.js', array( 'jquery' ), '1.0' );
	//if( !is_admin() ) wp_enqueue_script( 'superfish', get_bloginfo( 'template_directory' ). '/includes/js/superfish/js/superfish.js', array( 'jquery' ), '1.0' );
	//if( !is_admin() ) wp_enqueue_script( 'supersubs', get_bloginfo( 'template_directory' ). '/includes/js/superfish/js/supersubs.js', array( 'jquery' ), '1.0' );
	//if( !is_admin() ) wp_enqueue_style( 'superfish', get_bloginfo( 'template_directory' ). '/includes/js/superfish/css/superfish.css', null, '1.0', 'all' );
	//if( !is_admin() ) wp_enqueue_script( 'superfish-init', get_bloginfo( 'wpurl' ). '?get=superfish&type=text/javascript', array( 'jquery' ), '1.0' );
	//if( !is_admin() ) wp_enqueue_style( 'picto-colors', get_bloginfo( 'wpurl' ). '?get=colors&type=text/css', null, '1.0', 'all' );
	
	/* Setup Featured Images and Custom Image Sizes */
	add_theme_support( 'post-thumbnails', array( 'page' ) );	
	add_image_size( 'feature-image', 408, 231 );
	//add_image_size( 'featured-excerpt-image', 138, 999 );
	//add_image_size( 'thumbnail-image', 133, 133, true );
	//add_image_size( 'widget-image', 40, 40, true );
	
	/* Register navigation bars */
	//if( function_exists( 'register_nav_menu' ) ) register_nav_menu( 'primary', 'The main menu located in the theme\'s header.' );
	
	/* Register Sidebars */
	register_sidebar( array( 'name' => 'Header', 'id' => 'sidebar-masthead', 'description' => 'Located on the right side of the site header. Useful for adding a site search or social media icons/links.', 'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h2 class="widgettitle">', 'after_title' => '</h2>' ) );
	register_sidebar( array( 'name' => 'Front Page', 'id' => 'sidebar-front-page', 'description' => 'Any widgets added to this sidebar will appear below the body text on the front page.', 'before_widget' => '<div id="%1$s" class="widget grid_4 %2$s">', 'after_widget' => '</div>', 'before_title' => '<h2 class="widgettitle">', 'after_title' => '</h2>' ) );
	register_sidebar( array( 'name' => 'Blog', 'id' => 'sidebar-blog', 'description' => 'Appears next to your posts and archive views.', 'before_widget' => '<div id="%1$s" class="widget grid_4 %2$s">', 'after_widget' => '</div>', 'before_title' => '<h2 class="widgettitle">', 'after_title' => '</h2>' ) );
	//require_once( trailingslashit( TEMPLATEPATH) . 'includes/functions/functions-widgets.php' );
	
	/* Load Theme Settings Page */
	if ( is_admin() ){
		add_theme_support( 'hybrid-core-theme-settings' );
		//wp_enqueue_script( 'pictocolorpicker', get_bloginfo( 'template_directory' ). '/includes/js/colorpicker/js/colorpicker.js', array( 'jquery' ), '1.0' );
		//wp_enqueue_script( 'pictocolorpicker-init', get_bloginfo( 'wpurl' ). '?get=colorpicker&type=text/javascript', array( 'colorpicker' ), '1.0' );
		//wp_enqueue_style( 'pictocolorpicker', get_bloginfo( 'template_directory' ). '/includes/js/colorpicker/css/colorpicker.css', null, '1.0' );
		//wp_enqueue_style( 'pictocolorpicker-css', get_bloginfo( 'wpurl' ). '?get=colorpickercss&type=text/css', null, '1.0' );
		//require_once( trailingslashit( TEMPLATEPATH ) . 'includes/functions/functions-admin.php' );		
	}
}

/*
 * ktree_comment_fields() - modify the default fields for the comments form
 */
function ktree_comment_fields( $fields ){
	$fields['author'] = '<div class="form-author req"><input type="text" class="text-input" name="author" id="author" value="' . esc_attr( $commenter['comment_author'] ) . '" size="40"' . $aria_req . ' /> <label>Author*</label></div>';
	$fields['email'] = '<div class="form-email req"><input type="text" class="text-input" name="email" id="email" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="40"' . $aria_req . ' /> <label>Email*</label></div>';
	$fields['url'] = '<div class="form-url req"><input type="text" class="text-input" name="url" id="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="40"/>  <label>Website</label></div>';
	return $fields;
}

/*
 * ktree_comment_form_comment_field() - modify the comment form "comment" field
 */
function ktree_comment_form_comment_field( $comment_field ){
	$comment_field = '<div class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"  onfocus="if(this.value==this.defaultValue)this.value=\'\';" onblur="if(this.value==\'\')this.value=this.defaultValue;">' . _x( 'Add your comment here...', 'noun' ) . '</textarea></div>';
	return $comment_field;
}

/*
 * add a class to the div surrounding the breadcrumb trail
 */
function ktree_breadcrumb_trail( $content ){
	$classes = array( 'grid_12', 'alpha', 'omega' );
	//if( is_page() ) $classes[] = 'push_3';
	$content = str_replace( '<div class="breadcrumb breadcrumbs">', '<div class="breadcrumb breadcrumbs '.implode( ' ', $classes ).'">', $content );
	return $content;
}

/*
* setup query vars
*/
add_action('parse_request', 'ktree_parse_request');
function ktree_parse_request($wp){
	if( array_key_exists('getscript', $wp->query_vars) ){		
		$get = $wp->query_vars['getscript'];
		switch($get){
			case 'admincss':
				$type = 'text/css';
				$return = ktree_parse_file( 'css/admin.css' );
			break;
			case 'adminjs':
				$type = 'text/javascript';
				$return = ktree_parse_file( 'js/admin.js' );
			break;
			case 'cross-slide-init':
				$type = 'application/json';
				global $wpdb;
				$parentid = $wp->query_vars['pid'];
				$parentid = $wpdb->get_var( 'SELECT ID FROM '.$wpdb->posts.' WHERE post_title="Home" AND post_status="publish" AND post_type="page"' );
				$args = array(
							'post_type' => 'attachment',
							'posts_per_page' => -1,
							'post_status' => null,
							'orderby' => 'menu_order',
							'post_parent' => $parentid,
							'order' => 'ASC'
						);
				$images = get_posts( $args );
				foreach( $images as $image ){
					$urls[] = "\n\t\t\t".'{ src: \''.wp_get_attachment_url( $image->ID ). '\' }';	
				}
				$urls = implode( ',', $urls );							
				$return = sprintf( 'jQuery(document).ready(function($){
	$(function(){
		$(\'#images-frame\').crossSlide({ 
			sleep: 4, 
			fade: 1
		}, [%s
		]);
	});
});', $urls );
			break;			
			case 'superfish':
				$type = 'text/javascript';
				$return = ktree_parse_file( 'js/superfish-init.js' );
			break;
		}
		header('Content-type: '.$type.'; charset=utf-8');
		header("Expires: Wed, 28 Jul 1997 02:00:00 GMT");
		header("Cache-Control: no-cache, must-revalidate");
		header("Pragma: no-cache");				
		die($return);
	}
}

add_filter('query_vars', 'ktree_query_vars');
function ktree_query_vars( $vars ) {
    $vars[] = 'getscript';
    $vars[] = 'pid';
    return $vars;
}

/*
 * ktree_parse_file() - opens a file under /includes/ and returns its contents
 */
function ktree_parse_file( $file = '' ){
	$filepath = dirname( __FILE__ ). '/includes/';
	$fullpath = $filepath.$file;
	if( !empty( $file ) && file_exists( $fullpath ) ){
		ob_start();
		include( $fullpath );
		$return = ob_get_contents();
		ob_end_clean();		
	} else {
		$return = 'File ('.$file.') not found. Make sure your file is located in '.$filepath;
	}
	return $return;
}

/**
 * ktree_print_filters_for() - prints the filters used on a given hook
 */
function ktree_print_filters_for( $hook = '' ) {
    global $wp_filter;
    if( empty( $hook ) || !isset( $wp_filter[$hook] ) )
        return;

    print '<pre>';
    print_r( $wp_filter[$hook] );
    print '</pre>';
}
?>