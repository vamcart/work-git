<?php
function vam_render() {
		// SimplePie instance
		$feed = new SimplePie();
		// We'll process this feed with all of the default options.
		$url = array('https://updates.vamshop.com/feed/'.$_SERVER['HTTP_HOST']);
		// Set which feed to process.
		$feed->set_cache_location(DIR_FS_CATALOG . DIR_FS_CACHE);
		// Set which feed to process.
		$feed->set_feed_url($url);
		// Run SimplePie.
		$feed->init();
		$feed->handle_content_type();
		if ($feed->get_item_quantity() > 0) {
			echo '<div class="notification">';
		   echo '<ul>';
			foreach ($feed->get_items(0,1) as $item) {
				echo '<li>';
				echo '<h3><strong><a href="' . $item->get_permalink() .'" target="_blank">' . $item->get_title() .'</a></strong></h3>';
				echo '<p>' . $item->get_description() . '</p>';
				echo '</li>';
		 
			}
			echo '</ul>';
			echo '</div>';
			exit;
		}	
}