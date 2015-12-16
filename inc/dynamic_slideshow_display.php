<?php
/*
Template for slideshows
*/

foreach( $cType as $cType_value ) {


/* "Posts" content type */
	if( substr( $cType_value, 0, 10 ) == 'cTypePosts' ) {
		$limit = $wipDynamicMeta[$cType_value.'_number'];
		$category = $wipDynamicMeta[$cType_value.'_category'];
		$count = 0;
		$page = 1;
		$postList = new WP_Query( array( 'cat'=>$category, 'posts_per_page'=>$limit, 'paged'=>$page ) );						

		// Loop through posts
		while( $postList->have_posts() && $count < $limit ) {
			$toDisplay = $postList->next_post(); // Grab post object
			$imageData = wp_get_attachment_image_src( get_post_thumbnail_id( $toDisplay->ID ), "slideshow-wsu" ); // Grab featured image

			// Verify presence of featured image
			if( $imageData ) {
				$imageUrl = $imageData[0]; // Get image url
				$excerpt = $toDisplay->post_excerpt; // Get post excerpt

				// Verify image meets requirements to be shown
				if( $excerpt != '' ) {
					$count++;
					$text = str_replace("\n", '', $excerpt);
					$text = str_replace("\r", '', $text);
					$text = str_replace('"', '\"', $text);
					echo '{ "image" : "' . esc_url( $imageUrl ) . '", "url" : "' . get_permalink( $toDisplay->ID ) . '", "text" : "' . esc_js( $text ) . '" }, ';
					// If limit isn't hit, make a new query and grab the next page
					if( !$postList->have_posts() && $count < $limit )
					{
						// Increase page, get new set of posts
						$page++;
						$postList = new WP_Query( array( 'cat'=>$category, 'posts_per_page'=>$limit, 'paged'=>$page ) );
					}
				}
			}
		}
	}


/* "Feed" content type. Simplepie is bundled with WordPress - documentation at http://www.simplepie.org/wiki/. */
	if( substr( $cType_value, 0, 9 ) == 'cTypeFeed' ) {
		// Get the feed items to show based on the URL and number of items selected by the user.
		if( function_exists('fetch_feed') ) {
			// Change the default feed cache recreation period to 1 hour - http://codex.wordpress.org/Plugin_API/Filter_Reference/wp_feed_cache_transient_lifetime
			add_filter( 'wp_feed_cache_transient_lifetime' , 'feedCache_hour' );

			// Get the feed
			$feed = fetch_feed($wipDynamicMeta[$cType_value.'_url']);

			remove_filter( 'wp_feed_cache_transient_lifetime' , 'feedCache_hour' );

			if( is_wp_error( $feed ) ) {
				echo 'This feed cannot be parsed.';
			} else {
				$count = 0;
				$limit = $wipDynamicMeta[$cType_value.'_number'];
				$feedItems = $feed->get_items();
	
				// Number conversions for display limit control
				if( $limit != '-1' )
					$limit = intval( $limit ); // Widget is set to limit
				else
					$limit = $feed->get_item_quantity(); // Widget set to unlimited
	
				foreach( $feedItems as $item ) {
					if( $count < $limit ) {
						
						$imageElement = $item->get_item_tags('', 'image');
						$image = $imageElement[0]['data'];
						$headlineElement = $item->get_item_tags('', 'headline');
						$headline = $headlineElement[0]['data'];
						
						if( $image != '' && $headline != '' ) {

							$headline = str_replace( "\n", '', $headline );
							$headline = str_replace( "\r", '', $headline );
							$headline = str_replace( '"', '\"', $headline );

							echo '{ "image" : "' . esc_url( $image ) . '", "url" : "' . $item->get_permalink() . '", "text" : "' . esc_js( $headline ) . '" }, ';

						}

					} else {
						break; // Display limit reached, break out of foreach
					}
				}
			}
		}
	}


/* "Links" content type. */
	if( substr( $cType_value, 0, 10 ) == 'cTypeLinks' ) {
		$link = get_bookmarks( 'category=' . $wipDynamicMeta[$cType_value.'_category'] . '&limit=' . $wipDynamicMeta[$cType_value.'_number'] . '&orderby=link_id&order=DESC' );
		foreach( $link as $link_value ) {
			if( $link_value->link_image != '' ) {
				if( $link_value->link_notes && !isset( $link_value->link_description ) ) {
					$text = $link_value->link_notes;
					$text = str_replace("\n", '', $text);
					$text = str_replace("\r", '', $text);
					$text = str_replace('"', '\"', $text);
					echo '{ "image" : "' . esc_url( $link_value->link_image ) . '", "url" : "' . esc_url( $link_value->link_url ) . '", "text" : "' . esc_js( $text ) . '" }, ';
				} else {
					echo '{ "image" : "' . esc_url( $link_value->link_image ) . '", "url" : "' . esc_url( $link_value->link_url ) . '", "text" : "<h1>' . esc_js( $link_value->link_name ) . '</h1><h4>' . esc_js( $link_value->link_description ) . '</h4>" }, ';
				}
			}
		}
	}


}
?>