<?php
/*
Template Name: Tweet Archive
*/
wp_enqueue_script( 'jquery' );

$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$post_status = array( 'publish' );
if( is_user_logged_in() && current_user_can( 'publish_posts' ) ){
	$post_status[] = 'pending';
	//$post_status[] = 'trash';
	$posts_per_page = 20;
} else {
	$posts_per_page = 10;
}
$args = array( 'post_type' => array( 'tweet' ), 'posts_per_page' => $posts_per_page, 'paged' => $paged, 'post_status' => implode( ',', $post_status ) );

$temp_query = $wp_query;
$wp_query = new WP_Query( $args );

remove_filter( 'the_content', 'wpautop' );

$post_statuses = array( 'pending', 'publish', 'trash' );

if( is_user_logged_in() && current_user_can( 'publish_posts' ) ) add_action( 'wp_head', 'head_additions' );
function head_additions(){
?>
<script type="text/javascript">
String.prototype.ucFirst = function()
{
    return this.charAt(0).toUpperCase() + this.substring(1);
}

jQuery( function( $ ){
	$( '.edit-tweet a' ).click( function(e){
		var tid = $(this).parent().parent().attr( 'id' );
		tid = tid.replace( 'tweet-', '' );
		var tweetaction = $(this).attr( 'rel' );
		$.getJSON( '<?php bloginfo( 'wpurl' ) ?>', 
			{get: 'tweetupdate', tweetaction: tweetaction, tid: tid}, 
			function(data){
				if( data.update_status == true ){
					$( 'li#tweet-' + data.tid + ' div#status-' + data.tid ).removeClass().addClass( 'status ' + data.tweetaction ).html( data.tweetaction.ucFirst() );
					$( 'li#tweet-' + data.tid + ' div.edit-tweet a' ).removeClass().addClass( 'button' );
					$( 'li#tweet-' + data.tid + ' a[rel="' + data.tweetaction + '"]').addClass( data.tweetaction );
				} else {
					$( 'li#tweet-' + data.tid + ' div#status-' + data.tid ).removeClass().addClass( 'status alert' ).html( 'Error! Try again.' );
				}
		});
		e.preventDefault();
	});
});
</script>
<?php
}

add_action( 'wp_head', 'bkgrd_additions' );
function bkgrd_additions(){
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

get_header() ?>

		<h2 class="archive-title">Archive: Tweets</h2>
		<?php if( have_posts() ): ?>
			<ul class="tweetlist">
			<?php while( have_posts() ): the_post(); ?>
				<li id="tweet-<?php the_ID() ?>" <?php post_class() ?>><?php the_content() ?><br class="clear" /><?php 
				if( is_user_logged_in() && current_user_can( 'publish_posts' ) ){
					echo '<div class="edit-tweet">';
					$tweet_status = get_post_status();
					echo '<div id="status-'.get_the_ID().'" class="status '.$tweet_status.'">'.ucfirst( $tweet_status ).'</div>';
					foreach( $post_statuses as $status ){
						$class = array();
						$class[] = 'button';
						if( $status == $tweet_status ) $class[] = $status;
						echo ' <a rel="'.$status.'" class="'.implode( ' ', $class ).'" href="#">'.ucfirst( $status ).'</a>';
					}
					echo '</div>';
				}
				?></li>
			<?php endwhile; ?>
			</ul>
		<?php if( function_exists( 'wp_pagenavi' ) ) wp_pagenavi(); ?>
		<?php else: ?>
			<p>No Tweets Found.</p>
		<?php endif; ?>

<?php $wp_query = $temp_query; ?>
<?php get_footer() ?>