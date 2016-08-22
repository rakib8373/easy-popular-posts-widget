<?php 


function raeppw_most_views($postID) {
	$view_meta = 'views';
	
	$total_view = get_post_meta( $postID, $view_meta, true);
	
	if( $total_view == '' ) {
		$total_view = 0;
		delete_post_meta( $postID, $view_meta);
		add_post_meta( $postID, $view_meta, '0');
	}else{
		
		$total_view++;
		update_post_meta($postID, $view_meta, $total_view);
	}
}


remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);


function raeppw_popular_post_count($post_id){
	if( !is_single() ) return;
	if( !is_user_logged_in() ) {
		if ( empty( $post_id ) ) {
			global $post;
			$post_id = $post->ID;
		}
		raeppw_most_views($post_id);
	}
}
add_action('wp_head', 'raeppw_popular_post_count');


function raeppw_views_column($defaults){
	$defaults['post_views'] = __('View Count');
	return $defaults;
}
add_filter('manage_posts_columns','raeppw_views_column');


function raeppw_display_views($column_name){
	if($column_name === 'post_views'){
		echo (int) get_post_meta(get_the_ID(), 'views', true);
	}
}
add_action('manage_posts_custom_column','raeppw_display_views',5,2);



function raeppw_register_popular_posts_widget() {
    register_widget( 'raeppw_popular_posts' );
}
add_action( 'widgets_init', 'raeppw_register_popular_posts_widget' );


function raeppw_sort_by_view() {
	echo '<ul>';
		$query_args = array(
                        'post_type' => 'post',
                        'posts_per_page' => 5,
                        'meta_key' => 'views',
                        'orderby' => 'meta_value_num',
                        'order' => 'DESC',
                        'ignore_sticky_posts' => true
                );
                $the_query = new WP_Query( $query_args );
		if ( $the_query->have_posts() ) : 

           
            while ( $the_query->have_posts() ) : $the_query->the_post(); 
                echo '<li><a href="' . get_the_permalink() . '" class="eye">' . get_the_title() . ' (' . (int) get_post_meta(get_the_ID(), 'views', true) . ')</a></li>';	
            endwhile;
        endif;
        echo '</ul>';
}

function raeppw_sort_by_comments() {
		echo '<ul>';
		$query_args = array(
                        'post_type' => 'post',
                        'posts_per_page' => 5,
                        'orderby' => 'comment_count',
                        'order' => 'DESC',
                        'ignore_sticky_posts' => true
                );
                $the_query = new WP_Query( $query_args );
		if ( $the_query->have_posts() ) : 

            
            while ( $the_query->have_posts() ) : $the_query->the_post(); 
                echo '<li><a href="' . get_the_permalink() . '" class="comment">' . get_the_title() . ' (' . get_comments_number( get_the_ID() ) . ')</a></li>';	
            endwhile;
        endif;
        echo '</ul>';
}





?>