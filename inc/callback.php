<?php 

include_once(plugin_dir_path(__FILE__).'../lib/google_calendar_api.php');

if(isset($_GET['g_redirect'])) {
    $url = 'https://accounts.google.com/o/oauth2/auth?scope=' . urlencode('https://www.googleapis.com/auth/calendar') . '&redirect_uri=' . APPLICATION_REDIRECT_URL . '&response_type=code&client_id=' . APPLICATION_ID . '&access_type=offline';
    $_SESSION['event_post_title'] = $_GET['title'];
    $_SESSION['event_post_date'] = $_GET['date'];
    
    header('Location: '.$url);
    die();
}

if(isset($_GET['code'])) {
    $CODE = $_GET['code'];
    $capi = new GoogleCalendarApi();
    $data = $capi->GetAccessTokenRefresh(APPLICATION_ID, APPLICATION_REDIRECT_URL, APPLICATION_SECRET, $CODE);

    $access_token = $data['access_token'];

    $user_timezone = $capi->GetUserCalendarTimezone($data['access_token']);
    $calendar_id = 'primary';
    $event_title = $_SESSION['event_post_title'] ;

// Event starting & finishing at a specific time
    // $full_day_event = 0;
    // $event_time = ['start_time' => '2021-12-15T13:00:00', 'end_time' => '2021-12-15T13:15:00'];

// Full day event
    $full_day_event = 1;
    $event_time = ['event_date' => $_SESSION['event_post_date']];

// Create event on primary calendar
    $event_id = $capi->CreateCalendarEvent($calendar_id, $event_title, $full_day_event, $event_time, $user_timezone, $data['access_token']);

    echo '<h3>New event added</h3>';
    echo 'event Id:-'.$event_id;
    sleep(2);
    $_SESSION['event_post_message'] = $_SESSION['event_post_title'].' added in your google calendar on '.$_SESSION['event_post_date'];
    unset($_SESSION['event_post_title']);
    unset($_SESSION['event_post_date']);
    header('Location: '.APPLICATION_REDIRECT_URL);
    die();
}