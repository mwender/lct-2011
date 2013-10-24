<?php
/**
 * Hope424 Text widget class
 *
 */
add_action('widgets_init', create_function('', 'return register_widget("Hope424_Widget_Text");')); 
class Hope424_Widget_Text extends WP_Widget {

	function Hope424_Widget_Text() {
		$widget_ops = array('classname' => 'widget_text', 'description' => __('Arbitrary text or HTML with the option for adding a subtitle.'));
		$control_ops = array('width' => 400, 'height' => 350);
		$this->WP_Widget('hope424text', __('Hope424 Text'), $widget_ops, $control_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base);
		$subtitle = apply_filters( 'widget_title', empty( $instance['subtitle'] ) ? '' : $instance['subtitle'], $instance, $this->id_base);
		$text = apply_filters( 'widget_text', $instance['text'], $instance );
		echo $before_widget;
        if ( $title ){
			echo $before_title;
			echo $title;
			if( $subtitle ) echo '<span class="subtitle">'.$subtitle.'</span>';
			echo $after_title; 
		} ?>
			<div class="textwidget"><?php echo $instance['filter'] ? wpautop($text) : $text; ?></div>
		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		
		$instance['title'] = strip_tags($new_instance['title']);
		if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
		else
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
		
		$instance['subtitle'] = strip_tags( $new_instance['subtitle'] );
		if ( current_user_can('unfiltered_html') )
			$instance['subtitle'] =  $new_instance['subtitle'];
		else
			$instance['subtitle'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['subtitle']) ) ); // wp_filter_post_kses() expects slashed
		
		$instance['filter'] = isset($new_instance['filter']);
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'subtitle' => '', 'text' => '' ) );
		$title = strip_tags( $instance['title'] );
		$subtitle = strip_tags( $instance['subtitle'] );
		$text = esc_textarea( $instance['text'] );
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
         <p>
          <label for="<?php echo $this->get_field_id('subtitle'); ?>"><?php _e('Subtitle:'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('subtitle'); ?>" name="<?php echo $this->get_field_name('subtitle'); ?>" type="text" value="<?php echo $subtitle; ?>" />
        </p>
		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>

		<p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs'); ?></label></p>
<?php
	}
}
?>