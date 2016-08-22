<?php
/*
Plugin Name:Easy Popular Posts Widget
Description:  By using this plugin you can show popular posts as a widget in your site through two categories . 1. Based on Comments . 2. Based on views of you posts. 
Author: Rakib
Version: 1.0
Author URI: http://mrakib.me
*/

// If this file is called directly, abort
if ( ! defined( 'WPINC' ) ) {
	die();
}

require_once ( plugin_dir_path( __FILE__ ) . 'assets/raeppw-post-counter.php' );

/**
* Load style sheet 
*/
function raeppw_easy_popular_posts_widget_style() {
    wp_enqueue_style( 'rawppw-style', plugin_dir_url( __FILE__ ) . 'assets/easy-popular-posts-widget.css' );
}
add_action( 'wp_enqueue_scripts', 'raeppw_easy_popular_posts_widget_style' );



/**
 * Adds popular_posts widget.
 */
class raeppw_popular_posts extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'raeppw_popular_posts', 
			__( 'Popular Posts' ), 
			array( 'description' => __( 'Displays the 5 most popular posts'), ) 
		);
	}

	/**
	 * Front-end display of widget.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		$sort_by = $instance['sort_by'];

		if($sort_by == "Comments") {
			raeppw_sort_by_comments();
		} else if($sort_by == "Post_Views_Count") {
			raeppw_sort_by_view();
		}
		
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 */
	public function form( $instance ) {

		$defaults = array(
			'sort_by' => 'Comments'
		);

		$instance = wp_parse_args( (array) $instance, $defaults );

		$sort_by = $instance['sort_by']; 

		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Popular Posts');


		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Popular Posts' );
		}
		?>


		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('sort_by'); ?>"><?php _e( 'Sort By:' ); ?></label> 
			<select id="<?php echo $this->get_field_id('sort_by'); ?>" name="<?php echo $this->get_field_name('sort_by'); ?>" class="widefat">
				<option <?php selected( $instance['sort_by'], 'Comments'); ?> value="Comments">Comments</option>
				<option <?php selected( $instance['sort_by'], 'Post_Views_Count'); ?> value="Post_Views_Count">Post Views Count</option>
			</select>
		</p>
		
		<?php 
	}

	/**
	 * Sanitization
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['sort_by'] = $new_instance['sort_by'];

		return $instance;
	}

} 

