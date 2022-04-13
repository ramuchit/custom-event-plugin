<?php

function insert_page_on_activation() {
  if ( ! current_user_can( 'activate_plugins' ) )
    return;

  $new_page = array(
    'post_type'     => 'page',               // Post Type Slug eg: 'page', 'post'
    'post_title'    => 'Custom Event List',    // Title of the Content
    'post_content'  => '[custom-event-list]',  // Content
    'post_status'   => 'publish',            // Post Status
    'post_author'   => 1,                    // Post Author ID
    'post_name'     => 'custom-event-list'     // Slug of the Post
  );
  if (!get_page_by_path( $page_slug, OBJECT, 'page')) { // Check If Page Not Exits
      $new_page_id = wp_insert_post($new_page);
  }
}

register_activation_hook( __FILE__, 'insert_page_on_activation' ); 

function add_custom_css(){ 
  ?>
    <style>
      .grid-container {
        display: grid;
        grid-template-columns: auto auto auto;
        background-color: #2196F3;
        padding: 10px;
      }
      .grid-item {
        background-color: rgba(255, 255, 255, 0.8);
        border: 1px solid rgba(0, 0, 0, 0.8);
        padding: 20px;
        font-size: 15px;
        text-align: center;
      }
      .wp-container-7 > * {
        max-width: 100% !important;
      }
    </style>
  <?php 
}

add_action('wp_head','add_custom_css');

?>