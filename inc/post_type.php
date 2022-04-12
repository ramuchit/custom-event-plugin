<?php

class CustomPostType {

    public function __construct(){

        // Hook custom_post_type_custom_event() to the init action hook
        add_action('init', array($this,'custom_post_type_custom_event'));

        //To add custom meta boxes
        add_action( 'add_meta_boxes', array($this,'custom_event_custom_box') );

        //Hook for saving custom meta data
        add_action( 'save_post', array($this,'save_custom_event_meta_box_data'));
         
    }

    public function custom_post_type_custom_event() {

        // Set the labels, this variable is used in the $args array
        $labels = array(
            'name'               => __( 'Custom Events' ),
            'singular_name'      => __( 'Custom Event' ),
            'add_new'            => __( 'Add New Custom Event' ),
            'add_new_item'       => __( 'Add New Custom Event' ),
            'edit_item'          => __( 'Edit Custom Event' ),
            'new_item'           => __( 'New Custom Event' ),
            'all_items'          => __( 'All Custom Events' ),
            'view_item'          => __( 'View Custom Event' ),
            'search_items'       => __( 'Search Custom Event' ),
            'featured_image'     => 'Poster',
            'set_featured_image' => 'Add Poster'
        );

        // The arguments for our post type, to be entered as parameter 2 of register_post_type()
        $args = array(
            'labels'            => $labels,
            'description'       => 'Holds our custom event post specific data',
            'public'            => true,
            'menu_position'     => 5,
            'supports'          => array( 'title', 'editor', 'thumbnail', 'excerpt','comments', 'custom-fields' ),
            'has_archive'       => true,
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'query_var'         => true,
            'menu_icon'         => 'dashicons-calendar-alt',
        );

        // Call the actual WordPress function
        register_post_type( 'custom_event', $args);
    }

    public function custom_event_custom_box() {
        add_meta_box('custom_event_box_id', __('Additional Event Details'),array($this,'custom_event_custom_box_html'), 'custom_event');
    }

    public function custom_event_custom_box_html($post){
        $event_date = get_post_meta($post->ID,'_custom_event_date',true);
        $event_location = get_post_meta($post->ID,'_custom_event_location',true);
        $event_url = get_post_meta($post->ID,'_custom_event_url',true);

       // Add a nonce field so we can check for it later.
        wp_nonce_field( 'custom_event_nonce', 'custom_event_nonce' );?>
        <div>
            <label for="custom_event_date">Event Date</label>
            <input type="date" name="custom_event_date" id="custom_event_date" value="<?php echo $event_date;?>" class="code" required/>
        </div><br>
        <div>
            <label for="custom_event_location">Event Location</label>
            <input type="text" name="custom_event_location" id="custom_event_location" value="<?php echo $event_location;?>" class="code"/>
        </div><br>
        <div>
            <label for="custom_event_url">Event URL</label>
            <input type="text" name="custom_event_url" id="custom_event_url" value="<?php echo $event_url;?>" class="code"/>
        </div>
        
      <?php  
    }

    public function save_custom_event_meta_box_data( $post_id ) {

        // Check if our nonce is set.
        if ( ! isset( $_POST['custom_event_nonce'] ) ) {
            return;
        }

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $_POST['custom_event_nonce'], 'custom_event_nonce' ) ) {
            return;
        }

       
        // Check the user's permissions.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        /* OK, it's safe for us to save the data now. */

        // Make sure that it is set.
        if ( ! isset( $_POST['custom_event_date'] ) ) {
            return;
        }

        // Sanitize user input.
        $custom_event_date = sanitize_text_field( $_POST['custom_event_date'] );
        $custom_event_location = sanitize_text_field( $_POST['custom_event_location'] );
        $custom_event_url = sanitize_text_field( $_POST['custom_event_url'] );

        // Update the meta field in the database.
        update_post_meta( $post_id, '_custom_event_date', $custom_event_date );
        update_post_meta( $post_id, '_custom_event_location', $custom_event_location );
        update_post_meta( $post_id, '_custom_event_url', $custom_event_url );
    }

}

$obj = new CustomPostType();