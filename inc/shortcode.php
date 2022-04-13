<?php 

function custom_event_list_callback() {

    $output = '<div class="custom_event">';

    if(isset($_SESSION['event_post_message'])){
       $output.='<h4>'.$_SESSION['event_post_message'].'</h4>'; 
       unset($_SESSION['event_post_message']);
    }
   
    $args = array (
        'post_type'         => 'custom_event',
        'posts_per_page'    => '-1',
        'meta_key'          => '_custom_event_date', //name of date field
        'orderby'           => 'meta_value', 
        'order'             => 'ASC',
        'meta_query'        =>  array(
                                        'key'       => '_custom_event_date', 
                                        'meta_key'  => '_custom_event_date'
                                    )
    );

    // The Query
    $posts = get_posts($args);

    if( empty($posts)){
        return;
    }
    $output .= '<div class="grid-container">';
    foreach($posts as $post){ 
          
        $output .= '<div class="grid-item">';
        $output .= '<b><a href="'.get_permalink( $post ).'">'.get_the_title( $post ).'</a></b>' ;
        $output .= '<iframe width="100%" height="350" src="https://maps.google.com/maps?q='.get_post_meta( $post->ID,'_custom_event_location',true).'&output=embed"></iframe>';
        $output .= 'Date-'.get_post_meta( $post->ID,'_custom_event_date',true).' | ';
        $output .= '<a href="'.get_post_meta( $post->ID,'_custom_event_url',true).'">Source Link</a> |';
        $output .= '<a class="button button-primary" href="'.APPLICATION_REDIRECT_URL.'?g_redirect='.$post->ID.'&title='.get_the_title( $post ).'&date='.get_post_meta( $post->ID,'_custom_event_date',true).'"><button>Send to Google Calendar</button></a>';

        $output .= '</div>';
    }
    $output .= '</div></div>';

    return $output ;
}

add_shortcode('custom-event-list','custom_event_list_callback');