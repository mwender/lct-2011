<?php 
/*
Template Name: Tickets Table
*/

add_shortcode( 'tickets', 'ticket_table' );
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
.list-table{width: 100%; font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; border: 1px solid #c0aa85; border-spacing: 0; border-collapse: collapse; color: #991b1e}
.list-table th, .list-table td{border: 1px solid #c0aa85; padding: 8px}
.list-table .button{text-align: center}
.list-table .button a{padding: 4px 8px; border-radius: 3px; -moz-border-radius: 3px; -webkit-border-radius: 3px; font-weight: bold; font-size: 14px; text-decoration: none; border: 1px solid #991b1e; background-color: #eee; color: #991b1e}
.list-table .button a:hover{text-decoration: none; background-color: #fff}
.alt{background-color: #f6f3ed}

#images-frame{background-image: url(<?php echo $feature_image ?>)}
.warning{border: 1px solid #991b1e; background: #eee; padding: 1em; font-family: "Helvetica Neue", Arial, sans-serif; color: #000;}
.warning strong{color: #991b1e}
</style><?php	
}

get_header();
if( have_posts() ): while( have_posts() ): the_post();
?><h2 class="entry-title"><?php the_title() ?></h2><?php
	the_content();
	endwhile;
else:
	?><p>No post found.</p><?php
endif;
get_footer(); 


function ticket_table( $atts ){
	global $post;
	
	extract( shortcode_atts( array(
		'foo' => 'something'
	), $atts ) );
	
	$events = get_post_meta( $post->ID, 'events', true );
	
	if( !empty( $events ) ){
		$events = explode( "\n", $events );
		$html = '<table class="list-table"><col width="70%" /><col width="30%" />';
		$x = 0;
		foreach( $events as $event ){
			$event = explode( '|', $event );
			( $x % 2 )? $class = ' class="alt"' : $class = '';
			$html.= '<tr'.$class.'><td>'.$event[0].'</td><td class="button"><a href="'.$event[1].'" title="'.$event[0].'" target="_blank">Get Tickets!</a></td></tr>';
			$x++;
		}
		$html.= '</table>';
	} else {
		$html = '<p class="warning"><strong>No Events Found</strong><br />Add an "Events" table to this page by creating a custom field called <code>events</code>. For the value, enter one event per line in the following format: <code>_EVENT-TITLE_|_EVENT-ID_</code></p>';
	}
	
	return $html;
}
?>