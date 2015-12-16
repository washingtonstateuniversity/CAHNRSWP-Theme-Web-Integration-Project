<?php
/*
The template for displaying the dynamic content of a page, if it has any
*/
/*
$wipDynamicCCT = get_post_types( $args = array( 'public' => true, '_builtin' => false ) ); // Find custom content types, see if they need to be widgets
if( $wipDynamicCCT )
{
	foreach( $wipDynamicCCT as $wipDynamicCCT_value )
	{
		$customContentType = get_post_type_object( $wipDynamicCCT_value );
		if( $customContentType->dynamic_widget == true )
			$cctWidget_name = $customContentType->label;
			$cctWidget_display = $customContentType->widget_display;
	}
}
*/

foreach( $cType as $cType_value ) {
	// Define the h tag and a fallback in case one isn't selected
	if( isset( $dynamicMeta[$cType_value.'_hTag'] ) )
		$hTag = $dynamicMeta[$cType_value.'_hTag'];
	else
		$hTag = '2';

	if( isset( $dynamicMeta[$cType_value.'_displayAs'] ) )
		$display = $dynamicMeta[$cType_value.'_displayAs'];
	else
		$display = 'paragraph';

	// If displaying the page content, this is all that's needed
	if( substr( $cType_value, 0, 9 ) == 'cTypePage' ) {
		echo apply_filters( 'the_content', $content[substr( $cType_value, 10, 11 )] );
	// Otherwise, there are a bunch of options to deal with
	} else {
		// If the widget has been given a title, display it
		if( isset( $dynamicMeta[$cType_value.'_title'] ) )
			echo '<h' . (int) $hTag . '>' . esc_html( $dynamicMeta[$cType_value.'_title'] ) . '</h' . (int) $hTag . '>';


// LINK WIDGET - above the general "list" conditional as it may need to be handled differently
		if( substr( $cType_value, 0, 10 ) == 'cTypeLinks' ) {
			// If there is no specific link category chosen, show them all
			if( !isset( $dynamicMeta[$cType_value.'_category'] ) ) {
				$taxonomy = 'link_category';
				$term = get_categories( array( 'hide_empty' => 1, 'taxonomy' => 'link_category' ) );

				if( $term ) {
					foreach( $term as $term_value ) {
						if( $display == "list" )
							$categoryTitle = '4';
						else
							$categoryTitle = '3';

						echo '<h' . $categoryTitle . '>' . $term_value->name .'</h' . $categoryTitle . '>';

						if( $display == 'list' )
							echo '<ul>';

						$bookmark = get_bookmarks( array(
							'orderby' => 'name',
							'order' => 'ASC',
							'category' => $term_value->term_id
						) );

						foreach( $bookmark as $bookmark_value ) {
							if( $display == 'list' )
								echo '<li><p>';
							else
								echo '<h4 style="clear:both;">';
								
//							echo '<a href="' . esc_url( $bookmark_value->link_url ) . '">' . $bookmark_value->link_name . '</a>';
                          echo '<a href="' . esc_url( $bookmark_value->link_url ) . '" target="' . esc_attr( $bookmark_value->link_target ) . '">' . $bookmark_value->link_name . '</a>';							
							
							if( $display == 'list' )
								echo '</p>';
							else
								echo '</h4>';

							if( ( isset( $dynamicMeta[$cType_value.'_desc'] ) && $dynamicMeta[$cType_value.'_desc'] == 'show' ) && ( $bookmark_value->link_notes != '' ) )
								echo '<p>' . $bookmark_value->link_notes . '</p>';

							if( $display == 'list' )
								echo '</li>';
						}

						if( $display == 'list' )
							echo '</ul>';
					}
				}
			} else { // Otherwise, show only the links in the chosen category
				$bookmark = get_bookmarks( array(
					'orderby' => 'name',
					'order' => 'ASC',
					'category' => $dynamicMeta[$cType_value.'_category']
				));

				if( $display == 'list' )
					echo '<ul>';

				foreach( $bookmark as $bookmark_value ) {
					if( $display == 'list' )
						echo '<li><p>';
					else
						echo '<h4 style="clear:both;">';
						
//					echo '<a href="' . esc_url( $bookmark_value->link_url ) . '">' . $bookmark_value->link_name . '</a>';
                      echo '<a href="' . esc_url( $bookmark_value->link_url ) . '" target="' . esc_attr( $bookmark_value->link_target ) . '">' . $bookmark_value->link_name . '</a>';
					
					if( $display == 'list' )
						echo '</p>';
					else
						echo '</h4>';

					if( ( isset( $dynamicMeta[$cType_value.'_desc'] ) && $dynamicMeta[$cType_value.'_desc'] == 'show' ) && ( $bookmark_value->link_notes != '' ) )
						echo '<p>' . $bookmark_value->link_notes . '</p>';

					if( $display == 'list' )
						echo '</li>';
				}

				if( $display == 'list' )
								echo '</ul>';
			}
		}


// POSTS WIDGET
		if( substr( $cType_value, 0, 10 ) == 'cTypePosts' ) {
			// Query posts based on selection options
			$postListArgs = array( 
				'cat' => $dynamicMeta[$cType_value.'_category'],
				'posts_per_page' => $dynamicMeta[$cType_value.'_number'],
				'orderby' => $dynamicMeta[$cType_value.'_order'],
			);

			if ( $dynamicMeta[$cType_value.'_order'] == 'title' )
				$postListArgs['order'] = 'ASC';

			$postList = new WP_Query( $postListArgs );
			
			//$postList = new WP_Query( array( 'orderby'=>'Title', 'cat'=>$dynamicMeta[$cType_value.'_category'], 'posts_per_page'=>$dynamicMeta[$cType_value.'_number'] ) );

			// Display as list or paragraphs?
			if( $display == 'list' )
				echo '<ul>';

			// Loop through posts
//    		query_posts($postList , '&orderby=title&order=ASC');
			while( $postList->have_posts() ) {
				// Grab a single post to display
				$postList->the_post();
				
				global $more;
				$more = 0;

				// Display format settings
				if( $display == 'list' )
					echo '<li><p>';
				else
					echo '<div><h4 style="clear:both;">';

				// Output link
				$linkSegment = '<a href="' . get_permalink() . '" title="' . get_the_title() . '">';
				echo $linkSegment . get_the_title() . '</a>';

				// Continue display format
				if( $display == 'list' )
					echo '</p>';
				else
					echo '</h4>';

				// Show the post date if the "Show Post Dates" option is selected.
				if( isset( $dynamicMeta[$cType_value.'_date'] ) )
					echo '<h6>' . get_the_time('F j, Y') . '</h6>';

				// Show the post teaser if the "Show Post Teaser" option is selected.
				if( isset( $dynamicMeta[$cType_value.'_desc'] ) ) {
					// Check for more tag
					$tagPosition = strpos($post->post_content, '<!--more-->');

					if( $tagPosition !== false )
						the_content( '' ); // More tag present, grab everything before it. Clean it up with trim
					else
						the_excerpt(); // No More tag, automatically generate excerpt using WordPress' excerpt filter
				}
				
				// Show the post teaser if the "Show Post Teaser" option is selected.
				if( isset( $dynamicMeta[$cType_value.'_featureImg'] ) ) {
					if( has_post_thumbnail() )
						the_post_thumbnail('medium');
				}

				// Finish list item
				if( $display == 'list' )
					echo '</li>';
				else
					echo '</div>';				

			}
			wp_reset_postdata();
			// Close out list
			if( $display == 'list' )
				echo '</ul>';
		}


// MOST COMMENTED WIDGET
		if( substr( $cType_value, 0, 18 ) == 'cTypeMostCommented' ) {
			// Grab posts, sorted by comment count
			$popular = new WP_Query( array( 'orderby'=>'comment_count', 'posts_per_page'=>$dynamicMeta[$cType_value.'_number'], 'paged'=>'1' ) ) ;

			while( $popular->have_posts() ) {
				$toDisplay = $popular->next_post();
				echo '<p><a href="' . get_permalink( $toDisplay->ID ) . '" title="' . $toDisplay->post_title . '">' . $toDisplay->post_title . '</a> '; comments_number( '(0)', '(1)', '(%)' ); echo '</p>';
			}

		}


// FEED WIDGET. Simplepie is bundled with WordPress - documentation at http://www.simplepie.org/wiki/. */
		if( substr( $cType_value, 0, 9 ) == 'cTypeFeed' ) {
			if( function_exists( 'fetch_feed' ) ) {
				
				add_filter( 'wp_feed_cache_transient_lifetime' , 'feedCache_hour' ); // Change the default feed cache recreation period to 1 hour

				$feed = fetch_feed( $dynamicMeta[$cType_value.'_url'] ); // Get the feed
				
				if( is_wp_error( $feed ) ) {
					echo 'This feed cannot be parsed.';
				} else {
					if( $display == 'list' )
							echo '<ul>';

					$limit = $feed->get_item_quantity( $dynamicMeta[$cType_value.'_number'] );
					$item = $feed->get_items( 0, $limit );

					foreach( $item as $item_value ) {
						/* @var $item_value SimplePie_Item */
						if( $display == 'list' )
							echo '<li><p style="padding-bottom:0;">';
						else
							echo '<h4 style="clear:both;">';
						
						echo '<a href="' . esc_url( $item_value->get_permalink() ) . '" title="' . esc_attr( $item_value->get_title() ) . '" target="_blank">' . esc_html( $item_value->get_title() ) . '</a>';
						
						if( $display == 'list' )
							echo '</p>';
						else
							echo '</h4>';

						// Show item pubdate if the "Show Item Dates" option is selected.
						if( isset( $dynamicMeta[$cType_value.'_date'] ) )
							echo '<h6>' . esc_html( $item_value->get_date() ) . '</h6>';

						// Show item description if the "Show Item Descriptions" option is selected.
						if( isset( $dynamicMeta[$cType_value.'_desc'] ) )
							echo wp_kses_post( $item_value->get_description() );

						if( $display == 'list' )
							echo '</li>';
					}

					if( $display == 'list' )
						echo '</ul>';

				}

				remove_filter( 'wp_feed_cache_transient_lifetime' , 'feedCache_hour' );

			}
		}


// EVENT WIDGET - also leverages Simplepie
		if( substr($cType_value, 0, 11 ) == 'cTypeEvents' ) {

			add_filter( 'wp_feed_cache_transient_lifetime', 'feedCache_minute' ); // Change the default feed cache recreation period to 1 minute

			// Feed parsing, initializing, etc.
			$feed = fetch_feed( 'http://cahnrs.wsu.edu/events/feed/' ); // The feed

			if( !is_wp_error( $feed ) ) {

				$feed->enable_order_by_date( false ); // Turned off because we're going to (re)sort by <gd:when> tag
				$feed->init(); // Initialize the feed 
				$feed->handle_content_type(); // Make sure the content is being served out to the browser properly
				$feedItems = $feed->get_item_quantity(); // Number of items in the feed
				$sortedEvent = array(); // An array used later for re-sorting the items based on the <gd:when> tag
	
				// Widget settings
				$limit = $dynamicMeta[$cType_value.'_number'];
				if( $limit == '-1' )
					$limit = $feed->get_item_quantity(); // Number of items to display set to "unlimited", so get the total number of items in the feed
				else
					$limit = intval( $limit ); // Number of items to display set to an interger
				$description = $dynamicMeta[$cType_value.'_desc'];
				$linkBack = $dynamicMeta[$cType_value.'_link'];
				$category = $dynamicMeta[$cType_value.'_category'];
				$region = $dynamicMeta[$cType_value.'_region'];
				$authorship = $dynamicMeta[$cType_value.'_authorship'];
				if( isset( $authorship ) && $authorship == 'page' )
					$author = strtolower( get_the_author() );
				elseif( isset( $authorship ) && $authorship == 'custom' && isset( $dynamicMeta[$cType_value.'_author'] ) )
					$author = strtolower( $dynamicMeta[$cType_value.'_author'] );
	
				for( $count = 0, $index = 0; $index < $feedItems; $index++ ) { // Exp 1) Start $count and $index at 0; Exp 2) If $index is less than the total number of feed items, continue; Exp 3) Increment $index at the end of each iteration
					$item = $feed->get_item( $index ); // Get each feed item starting with the first,
					if( $count < $limit ) { // and continue as long as the number of items to display is less than the limit (or until Exp 2 in the For loop returns false). Here instead of the For loop because the result is only taken from the last part of Exp 2
						// Individual event (feed item) author
						$postAuthor = strtolower( $item->get_author()->get_name() );
		
						// Individual event time info
						$when = $item->get_item_tags( 'http://schemas.google.com/g/2005', 'when' ); // Get the Google-namespaced <gd:when> tag
						$date = $when[0]['attribs']['']['startTime']; // Get the startTime attribute
						$sortDate = SimplePie_Misc::parse_date($date); // Convert to UNIX timestamp - this will be used for sorting
						$displayDate = date('M d, g:i a', $sortDate); // A more readable date for displaying
		
						// Individual location info
						$where = $item->get_item_tags( 'http://schemas.google.com/g/2005', 'where' ); // Get the Google-namespaced <gd:where> tag
						$venueName = $where[0]['attribs']['']['valueString']; // Get the valueString attribute (the venue name)
						$venueLink = $where[0]['child']['http://schemas.google.com/g/2005']['entryLink'][0]['attribs']['']['href']; // Get the entryLink href value (the venue link)
						$venueAddress = $where[0]['child']['http://schemas.google.com/g/2005']['entryLink'][0]['child']['http://schemas.google.com/g/2005']['entry'][0]['child']['http://schemas.google.com/g/2005']['postalAddress'][0]['data']; // Get the... event address
		
						// Individual event cost info
						$extendedProperty = $item->get_item_tags( 'http://schemas.google.com/g/2005', 'extendedProperty' ); // Get the Google-namespaced <gd:extendedProperty> tag
						$cost = $extendedProperty[0]['attribs']['']['value']; // Get the cost
		
						// Individual event categories
						$eventCategories = array();
						if( $item->get_category() ) {
							foreach( $item->get_categories() as $cat ) {
								$eventCategories[] = $cat->get_term();
							}
						}

						// Show feed items that meet criteria based on selected options
						if(
								isset( $limit ) &&
								( !isset( $category ) || ( isset( $category ) && in_array( $category, $eventCategories ) ) ) && // Categories
								( !isset( $region ) || ( isset( $region ) && in_array( $region, $eventCategories ) ) ) && // Region
								( !isset( $authorship ) || ( isset( $authorship ) && $author == $postAuthor ) ) // Author
							)
							$show = true;
						else
							$show = false;
		
						if( $show ) { // If an item fits the criteria to be shown, index by $sortDate, format as it should be displayed, and increment $count
							$sortedEvent[$sortDate] = '<strong><a href="' . esc_url( $item->get_permalink() ) . '" title="' . esc_attr( $item->get_title() ) . '" target="_blank">' . esc_html( $item->get_title() ) . '</a></strong><br />' . $displayDate;
							if( $where )
								$sortedEvent[$sortDate] .= ', <a href="' . esc_url( $venueLink ) . '" title="' . esc_attr( $venueAddress )	. '"	target="_blank">' . esc_html( $venueName ) . '</a>';
							if( $cost )
								$sortedEvent[$sortDate] .= '<br />Cost: $' . esc_html( $cost );
							if( isset( $description ) && $description == "show" )
								$sortedEvent[$sortDate] .= $item->get_description();
							$count++;
						}
					}
				}
	
				ksort( $sortedEvent ); // Sort the events to be shown
	
				if( $display == 'list' &&	$count != 0	)
					echo '<ul>';
	
				// Loop through the sorted array of events
				foreach( $sortedEvent as $event ) {
					if( $display == 'list' )
						echo '<li>';
					else
						echo '<p>';
					echo $event;
					if( $display == 'list' )
						echo '</li>';
					else
						echo '</p>';
				}
	
				if( $display == 'list' &&	$count != 0	)
					echo '</ul>';

				if( $count == 0 )
					echo '<p>No upcoming events listed at this time.</p>';

			}

			remove_filter( 'wp_feed_cache_transient_lifetime', 'feedCache_minute' ); // Remove the cache-busting filter

			if( isset( $linkBack ) && $linkBack == "show" )
				echo '<p><a href="http://cahnrs.wsu.edu/events/">View all CAHNRS events &raquo;</a></p>';

		}


// DOCUMENTS WIDGET
		if( substr( $cType_value, 0, 9 ) == 'cTypeDocs' ) {
			// A custom loop for the Attachment content type, allowing only for documents. Might need a bit of work
			$docsLoopArgs = array( 
				'tax_query' => array(
					array(
						'taxonomy' => 'media_category',
						'field' => 'id',
						'terms' => array( $dynamicMeta[$cType_value.'_category'] )
					)
				),
				'post_type' => 'attachment', 
				'post_mime_type' => 'application/pdf,application/msword,application/vnd.ms-excel,application/vnd.ms-powerpoint',
				'post_status' => 'inherit',
				'posts_per_page' => $dynamicMeta[$cType_value.'_number'],
				'orderby' => $dynamicMeta[$cType_value.'_order'],
			);

			if ( $dynamicMeta[$cType_value.'_order'] == 'title' )
				$docsLoopArgs['order'] = 'ASC';

			$docsLoop = new WP_Query( $docsLoopArgs );

			if( $docsLoop->have_posts() ) {
				if( $display == 'list' )
					echo '<ul>';

				while( $docsLoop->have_posts() ) {
					$docsLoop->the_post();

					if( $display == 'list' )
						echo '<li>';

					// Show attachment description if the "Show Item Descriptions" option is selected.
					if( isset( $dynamicMeta[$cType_value.'_desc'] ) )
						the_content();

					if( $display == 'list' )
						echo '</li>';

				}

				if( $display == 'list' )
					echo '</ul>';
			}
		}


// LOCATION WIDGET
		if( substr($cType_value,0,13) == 'cTypeLocation' ) {
			try {
				$client = new SoapClient( 'http://cahnrswebservices.wsu.edu/CAHNRSDirectoryLookup/EmployeeLookupService.asmx?WSDL' );

				// Request variables
				$appID = "00E049C0-C7D9-4D9E-B590-6A78B8049137";
				$locationID = $dynamicMeta[$cType_value.'_location'];

				// Parameters wrapped in second array because of the way Microsoft uses doc/lit 
				$parameters = array('parameters' => array( 'locationID'=> $locationID, 'appid'=> $appID ) );

				// Get results, strip down to needed piece
				$rawResults = $client->__soapCall( 'LocationLookup', $parameters );
				$results = $rawResults->LocationLookupResult;

				// Display the result
				echo '<p>' . esc_html( $results->FirstContactTitle ) . ': ' . esc_html( $results->FirstContactName ) . '<br />';
				
				if( $results->SecondContactTitle != '' )
					echo esc_html( $results->SecondContactTitle ) . ': ' . esc_html( $results->SecondContactName ) . '<br />';

				echo esc_html( $results->Mailing ) . ' (mailing)<br />';
				echo esc_html( $results->Street ) . ' (street)<br />';
				echo esc_html( $results->CityStateZip ) . '<br />';
				echo 'Phone: ' . esc_html( $results->Phone ) . '<br />';

				if( $results->FAX != '' )
					echo 'Fax: ' . esc_html( $results->FAX ) . '<br />';

				if( $results->Email != '' )
					echo 'Email: ' . '<a href="mailto:' . esc_attr( $results->Email ) . '">' . esc_html( $results->Email ) . '</a><br />';

				if( $results->Hours != '' )
					echo 'Hours: ' . esc_html( $results->Hours ) . '<br />';

				echo '</p>';
			} catch( Exception $e ) {
				// Whoops
				echo '<div class="error"><p>Request error.</p></div>';
			}
		}


// EMPLOYEE WIDGET
		if( substr( $cType_value, 0, 14 ) == 'cTypeEmployees' ) {
			try {
				$client = new SoapClient( 'http://cahnrswebservices.wsu.edu/CAHNRSDirectoryLookup/EmployeeLookupService.asmx?WSDL' );

				// Request variables
				$appID = "00E049C0-C7D9-4D9E-B590-6A78B8049137";
				$locationID = $dynamicMeta[$cType_value.'_location'];

				// Parameters wrapped in second array because of the way Microsoft uses doc/lit 
				$parameters = array( 'parameters' => array( 'locationID'=> $locationID, 'appid'=> $appID, 'Sort'=>'Name' ) );

				// Get results, strip down to needed piece
				$rawResults = $client->__soapCall( 'EmployeesByLocation', $parameters );
				$employeeArray = $rawResults->EmployeesByLocationResult->EmployeeList->Employee;

				foreach( $employeeArray as $employee ) {
					echo '<p><a href="http://cahnrsdb.wsu.edu/newdirectory/individualDisplay.aspx?personID=' . urlencode( $employee->PersonID ) . '">' . esc_html( $employee->Name ) . '</a><br />';
					echo esc_html( $employee->Title ) . '<br />';
					echo '<a href="mailto:' . esc_attr( $employee->Email ) . '">' . esc_html( $employee->Email ) . '</a><br />';
					echo esc_html( $employee->Phone ) . '<br />';
					echo esc_html( $employee->Building ) . '</p>';
				}
			} catch( Exception $e ) {
				// Whoops
				echo '<div class="error"><p>Request error.' . $e . '</p></div>';
			}
		}


// DIRECTORY WIDGET
		if( substr( $cType_value, 0, 14 ) == 'cTypeDirectory' ) {
			try {
				$client = new SoapClient( 'http://cahnrswebservices.wsu.edu/CAHNRSDirectoryLookup/EmployeeLookupService.asmx?WSDL' );

				// Request variables
				$appID = "00E049C0-C7D9-4D9E-B590-6A78B8049137";
				$nid = $dynamicMeta[$cType_value.'_nid'];

				// Parameters wrapped in second array because of the way Microsoft uses doc/lit 
				$parameters = array( 'parameters' => array( 'networkID'=> $nid, 'appid'=> $appID ) );

				// Get results, strip down to needed piece
				$rawResults = $client->__soapCall( 'EmployeeLookup', $parameters );
				$employee = $rawResults->EmployeeLookupResult;

				echo '<p>' . esc_html( $employee->Name )	. '<br />';
				echo esc_html( $employee->Title ) . '<br />';
				echo esc_html( $employee->Email ) . '<br />';
				echo esc_html( $employee->Phone ) . '<br />';
				echo esc_html( $employee->Office ) . '<br />';
				echo esc_html( $employee->Building ) . '</p>';
			} catch( Exception $e ) {
				// Whoops
				echo '<div class="error"><p>Request error.' . $e . '</p></div>';
			}
		}
/*
// CUSTOM CONTENT TYPE WIDGET
		if( substr( $cType_value, 0, -2 ) == 'cType' . $cctWidget_name )
		{
			echo $cctWidget_display;
		}
*/

	}
}
?>