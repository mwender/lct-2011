<?php
/**
 
INSTRUCTIONS: The code on this page adds a `Tweets` custom post_type. In addition, adding the
shortcode [hashtag_tweets number="5" hashtag=""] to a page will make that page retrieve
tweets with the specified hashtag and insert them as `Tweets` in the WordPress database.

*/
$labels = array(
	'label' 				=> 'Tweets',
	'name' 					=> 'Tweets',
	'singular_name' 		=> 'Tweet',
	'add_new' 				=> _x( 'Add New', 'Tweet' ),
	'add_new_item'			=> 'Add New Tweet',
	'edit_item'				=> 'Edit Tweet',
	'new_item'				=> 'New Tweet',
	'view_item'				=> 'View Tweet',
	'search_items'			=> 'Search Tweets',
	'not_found'				=> 'No tweets found',
	'not_found_in_trash' 	=> 'No tweets found in Trash',
);
$args = array( 
	'public' => true,
	'menu_position' => 5,
	'menu_icon' => get_bloginfo( 'template_directory' ). '/includes/images/icon.tweet.png',
	'has_archive' => true,
	'supports' => array( 'title', 'editor', 'custom-fields' ),
	'labels' => $labels
);
register_post_type( 'Tweet', $args );

add_action( 'admin_head', 'tweets_admin_head' );
function tweets_admin_head(){
?>
<style>
div.wrap div.icon32-posts-tweet{background-image: url(<?php bloginfo( 'template_directory' ) ?>/includes/images/icon32.tweet.png) !important; background-position: 0 0!important;}
</style>
<?php
}

add_shortcode( 'hashtag_tweets', 'tweets_tweets_by_hashtag' );
function tweets_tweets_by_hashtag( $atts, $content = null ){
	extract(shortcode_atts(array(
		'hashtag' => 'ineedhope',
		'number' => 5
	), $atts ) );
	
	$api_url = 'http://search.twitter.com/search.json';
	$raw_response = wp_remote_get("$api_url?q=%23$hashtag&rpp=$number");

        if ( is_wp_error($raw_response) ) {
            $output = "<p>Failed to update from Twitter!</p>\n";
            $output .= "<!--{$raw_response->errors['http_request_failed'][0]}-->\n";
            $output .= get_option('twitter_hash_tag_cache');
        } else {
            if ( function_exists('json_decode') ) {
                $response = get_object_vars(json_decode($raw_response['body']));
                for ( $i=0; $i < count($response['results']); $i++ ) {
                    $response['results'][$i] = get_object_vars($response['results'][$i]);
                }
            } else {
                include(ABSPATH . WPINC . '/js/tinymce/plugins/spellchecker/classes/utils/JSON.php');
                $json = new Moxiecode_JSON();
                $response = @$json->decode($raw_response['body']);
            }

            $output = "<div class='twitter-hash-tag'>\n";
            foreach ( $response['results'] as $result ) {
                $text = $result['text'];
                $user = $result['from_user'];
                $image = $result['profile_image_url'];
                $user_url = "http://twitter.com/$user";
                $source_url = "$user_url/status/{$result['id']}";
                $status_id = $result['id_str'];

                $text = preg_replace('|(https?://[^\ ]+)|', '<a href="$1">$1</a>', $text);
                $text = preg_replace('|@(\w+)|', '<a href="http://twitter.com/$1">@$1</a>', $text);
                $text = preg_replace('|#(\w+)|', '<a href="http://search.twitter.com/search?q=%23$1">#$1</a>', $text);

                $output .= "<div>";

                //if ( $images )
                $output .= "<a href='$user_url'><img src='$image' alt='$user' /></a>";
                $output .= "<a href='$user_url'>$user</a>: $text <a href='$source_url'>&raquo;</a></div>\n";
	            // insert tweet into WP database
	            //*
	            if( tweets_tweet_exists( $status_id ) == false && is_numeric( $status_id ) ){
		            $tweet = array(
		            	'post_title' => $status_id,
		            	'post_content' => "<a href=\"$user_url\"><img src=\"$image\" alt=\"$user\" class=\"alignleft\" /></a><a href=\"$user_url\">$user</a>: $text <a class=\"permalink\" href=\"$source_url\" target=\"_blank\">&raquo;</a>",
		            	'post_status' => 'pending',
		            	'post_type' => 'tweet'
		            );
		            wp_insert_post( $tweet );
	            }
	            /**/
            }
            $output .= "<div class='view-all'><a href='http://search.twitter.com/search?q=%23$hashtag'>" . __('View All') . "</a></div>\n";
            $output .= "</div>\n";
            

        }

        return $output;
}

function tweets_tweet_exists( $status_id ){
	global $wpdb;
	if( is_numeric( $status_id )){
		$ID = $wpdb->get_var( 'SELECT ID FROM '.$wpdb->posts.' WHERE post_type="tweet" AND post_title="'.$status_id.'"' );
		if( $ID ){
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

/**
 * Tweets_ListTweets Class
 */
// register Tweets_ListTweets widget
add_action('widgets_init', create_function('', 'return register_widget("Tweets_ListTweets");')); 
class Tweets_ListTweets extends WP_Widget {
    /** constructor */
    function Tweets_ListTweets() {
        $options = array( 'classname' => 'tweets', 'description' => 'Displays recent "Tweets" post_types.');
        parent::WP_Widget( false, 'Tweets: Recent Tweets', $options );	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
		extract( $args );
		$title = apply_filters('widget_title', $instance['title']);
		$subtitle = apply_filters('widget_title', $instance['subtitle']);
		$number = $instance['number'];
        
        echo $before_widget; 
        if ( $title ){
			echo $before_title;
			echo $title;
			if( $subtitle ) echo '<span class="subtitle">'.$subtitle.'</span>';
			echo $after_title; 
		} 
		if( !is_numeric( $number ) ) $number = 3;
		$args = array( 'post_type' => 'tweet', 'posts_per_page' => $number );
		$tweets = new WP_Query( $args );
		?><ul class="tweetlist"><?php
		if( $tweets->have_posts() ): while( $tweets->have_posts() ): $tweets->the_post();
			?><li><?php the_content() ?></li><?php
			endwhile;
		else:
			?><li>No tweets found.</li><?php
		endif; ?>
        </ul>
        <div class="view-more-tweets"><a href="<?php bloginfo( 'wpurl' ) ?>/tweets/">View more Tweets &raquo;</a></div>
              <?php echo $after_widget; ?>
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['subtitle'] = strip_tags( $new_instance['subtitle'] );
		$instance['number'] = strip_tags( $new_instance['number'] );
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {				
        $title = esc_attr( $instance['title'] );
        $subtitle = esc_attr( $instance['subtitle'] );
        $number = esc_attr( $instance['number'] );
        ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
         <p>
          <label for="<?php echo $this->get_field_id('subtitle'); ?>"><?php _e('Sub-Title:'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('subtitle'); ?>" name="<?php echo $this->get_field_name('subtitle'); ?>" type="text" value="<?php echo $subtitle; ?>" />
        </p>
         <p>
          <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of Tweets:'); ?></label> 
          <input style="width: 60px" class="" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" />
        </p>
        <?php 
    }

} // class Tweets_ListTweets

/*
* setup query vars
*/
add_action('parse_request', 'tweets_parse_request');
function tweets_parse_request($wp){
	if( array_key_exists('get', $wp->query_vars) ){		
		$get = $wp->query_vars['get'];
		$type = 'text/javascript';
		switch( $get ){
			default:
				$type = 'application/json';
				if( is_user_logged_in() && current_user_can( 'publish_posts' ) ){
					if( array_key_exists( 'tweetaction', $wp->query_vars ) && array_key_exists( 'tid', $wp->query_vars ) ){
						$valid_tweetactions = array( 'publish', 'trash', 'pending' );

						$return['tweetaction'] = $wp->query_vars['tweetaction'];
						$return['tid'] = $wp->query_vars['tid'];
						global $wpdb;
						$return['post_date'] = $wpdb->get_var( 'SELECT post_date FROM '.$wpdb->posts.' WHERE post_type="tweet" AND ID='.$return['tid'] );
						
						if( in_array( $return['tweetaction'], $valid_tweetactions ) ){
							$tweet = array( 'ID' => $return['tid'], 'post_status' => $return['tweetaction'], 'post_date' => $return['post_date'], 'edit_date' => true );
							$status = wp_update_post( $tweet );
							if( is_numeric( $status ) && $status > 0 ){
								$return['update_status'] = true;
							} else {
								$return['update_status'] = false;
							}
						} else {
							$return['update_status'] = false;
						}
					}
					$return = json_encode( $return );
				}
			break;
		}
		header('Content-type: '.$type.'; charset=utf-8');
		header("Expires: Wed, 28 Jul 1997 02:00:00 GMT");
		header("Cache-Control: no-cache, must-revalidate");
		header("Pragma: no-cache");				
		die($return);
	}
}

add_filter('query_vars', 'tweets_query_vars');
function tweets_query_vars( $vars ) {
    $vars[] = 'get';
    $vars[] = 'tweetaction';
    $vars[] = 'tid';
    return $vars;
}
?>