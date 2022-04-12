<?php 

function custom_event_list_callback() {
	$output = '<div class="list_tax_archive">';
    if(isset($_SESSION['event_post_message'])){
       $output.='<h4>'.$_SESSION['event_post_message'].'</h4>'; 
       unset($_SESSION['event_post_message']);
    }
    $meta_query =  array(
            'key' => '_custom_event_date',
            'value' => date('Y-m-d'),
            'type' => 'DATE',
            'compare' => '>=',
            'meta_key' => '_custom_event_date',
            "orderby" => "start_date",
            "order" => "ASC"
        );
    $args = array ('post_type' => 'custom_event','posts_per_page' => '-1','meta_query'=>$meta_query);
    // The Query
    $posts = get_posts($args);

    if( empty($posts)){
    	return;
    }
         
    $output .= '<div class="term_archive">';

    foreach($posts as $post){
        $output .= '<div>
        <a href="'.get_permalink( $post ).'">'.get_the_title( $post ).'</a>
        |'.get_post_meta( $post->ID,'_custom_event_date',true).'
        <iframe width="100%" height="500" src="https://maps.google.com/maps?q='.get_post_meta( $post->ID,'_custom_event_location',true).'&output=embed"></iframe>
        <a class="button button-primary" href="'.APPLICATION_REDIRECT_URL.'?g_redirect='.$post->ID.'&title='.get_the_title( $post ).'&date='.get_post_meta( $post->ID,'_custom_event_date',true).'">Send to Google Calendar</a>
        </div>';
    }

    $output .= '</div>';
	$output .= '</div>';

	return $output ;
}

add_shortcode('custom-event-list','custom_event_list_callback');