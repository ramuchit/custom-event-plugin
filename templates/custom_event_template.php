<?php get_header(); ?>
	<main id="main" class="content-wrapper">
		<?php 
			if(isset($_SESSION['event_post_message'])){
		       	echo '<h4>'.$_SESSION['event_post_message'].'</h4>'; 
		       	unset($_SESSION['event_post_message']);
	    	}
   	
			if(have_posts()){ ?>
				<div class="grid-container">
				<?php
				while(have_posts()){
					the_post();
					$url = APPLICATION_REDIRECT_URL.'?g_redirect='.$post->ID.'&title='.get_the_title( $post ).'&date='.get_post_meta( $post->ID,'_custom_event_date',true);
					?>
					<div class="grid-item">
						<b><a href="<?php echo get_permalink( $post ); ?>"><?php echo get_the_title( $post );?></a></b>
        				<iframe width="100%" height="350" src="https://maps.google.com/maps?q=<?php echo get_post_meta( $post->ID,'_custom_event_location',true);?>&output=embed"></iframe>
       					<p> Date-<?php echo get_post_meta( $post->ID,'_custom_event_date',true);?> </p>
        				<a href="<?php echo get_post_meta( $post->ID,'_custom_event_url',true);?>">Source Link</a>
     					<a class="button button-primary" href="<?php echo $url ;?>"><button>Send to Google Calendar</button></a>
     				</div>
					<?php
				}
			}
		?>
	</main>
<?php get_footer(); ?>