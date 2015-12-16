<?php
/*
WIP functions and definitions.

Sets up the theme and provides helper functions, some or which are used as custom template tags 
while others are attached to action and filter hooks to change core WordPress functionality.

Functions wrapped in an "if( ! function_exists( ) )" statement can be overridden
by children themes (see http://codex.wordpress.org/Child_Themes for more info).

For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
*/


// Set the content width - dictates image and auto-embed widths... would be nice to hook layout options into this somehow
if( !isset( $content_width ) )
	$content_width = 495;


// Remove a bunch of the stuff Wordpress adds to the header
remove_action( 'wp_head', 'feed_links_extra', 3 ); 
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'index_rel_link' );
remove_action( 'wp_head', 'parent_post_rel_link_wp_head', 10, 0 );
remove_action( 'wp_head', 'start_post_rel_lin_wp_head', 10, 0 );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action( 'wp_head', 'wp_generator' );


// Remove version info from head and feeds
function removeWPVersion() {
	return '';
}
add_filter( 'the_generator', 'removeWPVersion');


// Register menus (in its own function because we want to be able to override it in child themes)
if( ! function_exists( 'overridableSetup' ) ) {
	function overridableSetup() {
		register_nav_menus( array(
			'primary' => __( 'Navigation' ),
			'horizontal' => __( 'Horizontal (optional - please limit to 6 top-level items)' ),
			'featured' => __( 'Featured Navigation (optional)' ),
			'mobile' => __( 'Mobile Navigation (optional)' )
		) );
	}
	require( get_template_directory() . '/inc/custom_header.php' );
}
add_action( 'after_setup_theme', 'overridableSetup' );


// Theme options (again its own function because we may want to override it in child themes)
if( ! function_exists( 'themeOptions' ) ) {
	function themeOptions() {
		if( is_admin() )
			require( get_template_directory() . '/inc/theme_options.php' );
	}
}
add_action( 'after_setup_theme', 'themeOptions' );


// Set up theme defaults and register support for various WordPress features
if( ! function_exists( 'wipThemeSetup' ) ) {
	function wipThemeSetup() {
		add_editor_style( 'css/editor.css' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_image_size( 'slideshow-wsu', 775, 250, true );
	}
}
add_action( 'after_setup_theme', 'wipThemeSetup' );


// Enqueues scripts and styles for front-end
if( ! function_exists( 'scriptsStyles' ) ) {
	function scriptsStyles()
	{
		global $wp_query, $wipOptions, $wipDynamicMeta, $wipLayoutMeta;

		$wipOptions = get_option( 'wipOptions' );
        if ( is_single() ) {
            $postID = $wp_query->post->ID;
            $wipDynamicMeta = get_post_meta( $postID, '_dynamic', true );
            $wipLayoutMeta = get_post_meta( $postID, '_layout', true );
        }

		wp_enqueue_style( 'wipStyle', get_stylesheet_uri() );
		wp_enqueue_style( 'shadowboxStyle', get_template_directory_uri() . '/css/shadowbox.css' );
		wp_enqueue_script( 'jquery' );
		if( is_singular() && get_option( 'thread_comments' ) )
			wp_enqueue_script( 'comment-reply' );
		wp_enqueue_script( 'wipjsRespond', get_template_directory_uri() . '/js/respond.min.js', array(), null );
		wp_enqueue_script( 'wipjsShadowbox', get_template_directory_uri() . '/js/shadowbox.js', array(), null );
		wp_enqueue_script( 'wipjsInit', get_template_directory_uri() . '/js/init.js', array(), null, true );
		if(
				(
					isset( $wipLayoutMeta['page_type'] ) && $wipLayoutMeta['page_type'] == 'dynamic' &&
					isset( $wipLayoutMeta['slideshow'] ) && $wipLayoutMeta['slideshow'] == 'show' &&
					$wipDynamicMeta['wipHomeArray'] != ''
				) ||
				(
					is_front_page() &&
					isset( $wipOptions['indexSlide'] ) &&
					isset( $wipOptions['slidesCategory'] ) &&
					isset( $wipOptions['slideCount'] ) &&
					!is_paged()
				)
			) {
			$buttonSrc = get_template_directory_uri() . '/images/button-w.png';
			wp_register_script( 'wipjsSlideshow', get_template_directory_uri() . '/js/slideshow.js', array(), null );
			wp_enqueue_script( 'wipjsSlideshow' );
			wp_localize_script( 'wipjsSlideshow', 'buttonSrc', $buttonSrc );
		}
		if( $wipOptions['tbPrintLink'] ) {
			$printCSS = get_template_directory_uri() . '/css/print.css';
			wp_register_script( 'wsujsPrint', get_template_directory_uri() . '/js/print.js', array(), null );
			wp_enqueue_script( 'wsujsPrint' );
			wp_localize_script( 'wsujsPrint', 'printCSS', $printCSS );
		}
	}
	add_action( 'wp_enqueue_scripts', 'scriptsStyles' );
}


// Enqueue CSS for Theme Options page
function themeOptionsStyle() {	
	if( isThemeOptionsPage() )
		wp_enqueue_style( 'wipThemeOptionStyle', get_template_directory_uri() . '/css/theme_options.css' ); 
} 


// Check if we're on our options page	
function isThemeOptionsPage() {
	$screen = get_current_screen();
	if( is_object( $screen ) && $screen->id == 'appearance_page_theme_options' )
		return true;
	else
		return false;
}
add_action( 'admin_enqueue_scripts', 'themeOptionsStyle' ); 


// Integrated plugins
include_once 'plugins/class_taxonomy_meta.php';
include_once 'plugins/breadcrumbs.php';
include_once 'plugins/media_categories.php';
//include_once 'plugins/tinymce_config.php';
if( !class_exists( 'WPAlchemy_MetaBox' ) )
	include_once 'plugins/class_metabox.php';


// Fallback function for Featured Nav
function horizontalNavFallback() {
	echo "<ul>
		<li><a href=\"http://academic.cahnrs.wsu.edu/\">Prospective Students</a></li>
		<li><a href=\"http://academic.cahnrs.wsu.edu/\">Current Students</a></li>
		<li><a href=\"http://academic.cahnrs.wsu.edu/\">Graduate Students</a></li>
		<li><a href=\"http://res-ext.cahnrs.wsu.edu/\">Research &amp; Extension</a></li>
		<li><a href=\"http://alumni.cahnrs.wsu.edu/\">Alumni &amp; Friends</a></li>
		<li><a href=\"http://fs.cahnrs.wsu.edu/\">Faculty &amp; Staff</a></li>
	</ul>";
}


// Register widgetized areas
if( ! function_exists( 'wipWidgets' ) ) {
	function wipWidgets()
	{
		register_sidebar( array(
			'name' =>	__( 'Navigation Column' ),
			'id' => 'navigation',
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '<h3>',
			'after_title' => '</h3>',
		) );
		register_sidebar( array(
			'name' =>	__( 'Upcoming Events Widget Right Column' ),
			'id' => 'upcomingevents',
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '<h3>',
			'after_title' => '</h3>',
		) );
		register_sidebar( array(
			'name' =>	__( 'Posts Page Sidebar' ),
			'id' => 'index',
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '<h3>',
			'after_title' => '</h3>',
		) );
		register_sidebar( array(
			'name' =>	__( 'Archives Sidebar' ),
			'id' => 'archive',
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '<h3>',
			'after_title' => '</h3>',
		) );
		register_sidebar( array(
			'name' =>	__( 'Category Archives Sidebar' ),
			'id' => 'category_archive',
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '<h3>',
			'after_title' => '</h3>',
		) );
		register_sidebar( array(
			'name' =>	__( 'Tag Archives Sidebar' ),
			'id' => 'tag_archive',
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '<h3>',
			'after_title' => '</h3>',
		) );
		$postCategory = get_categories();
		foreach( $postCategory as $postCategory_value ) {
			$categoryID = $postCategory_value->term_id;
			if( get_tax_meta( $categoryID, 'widget' ) ) {
				register_sidebar( array(
					'name' =>	__( $postCategory_value->cat_name . ' Category Archive' ),
					'id' => 'category_' . $categoryID . '_widget',
					'before_widget' => '',
					'after_widget' => '',
					'before_title' => '<h3>',
					'after_title' => '</h3>',
				) );
			}
		}
	}
}
add_action( 'widgets_init', 'wipWidgets' );


// Remove container div from menus
function wipRemoveMenuWrap( $args = '' ) {
	$args['container'] = false;
	return $args;
}
add_filter( 'wp_nav_menu_args', 'wipRemoveMenuWrap' );


// Remove More tag jump link.
function wipRemoveMoreJump( $link ) {
	$offset = strpos( $link, '#more-' );
	if( $offset )
		$end = strpos( $link, '"', $offset );
	if( $end )
		$link = substr_replace( $link, '', $offset, $end-$offset );
	return $link;
}
add_filter( 'the_content_more_link', 'wipRemoveMoreJump' );


// Removes empty span generated by the More tag
function removeEmptySpan( $content ) {
	return preg_replace( "(<p><span id=\"more-[0-9]{1,}\"></span></p>)", "", $content );
}
add_filter( 'the_content', 'removeEmptySpan' );


/*
--- LEGACY: Keeping in case needed for future reference ---
// A custom feed template for Tribe Events - http://codex.wordpress.org/Customizing_Feeds.
function tribeEventsFeed( $for_comments ) {
	$eventsRSSTemplate = get_template_directory() . '/inc/tribe_events_feed.php';
	if( get_query_var( 'post_type' ) == 'tribe_events' and file_exists( $eventsRSSTemplate ) )
		load_template( $eventsRSSTemplate );
	else
		do_feed_rss2( $for_comments ); // Call default function
}
remove_all_actions( 'do_feed_rss2' );
add_action( 'do_feed_rss2', 'tribeEventsFeed', 10, 1 );
*/


// Add Google GData namespace to the RSS feed for leaveraging with events - went with it for no other reason than it seemed like a good idea at the time
function feedAddNamespace() {
	echo "xmlns:gd='http://schemas.google.com/g/2005'"; // use the Google GData schema - https://developers.google.com/gdata/docs/1.0/elements
	//echo 'xmlns:mycustomfields="'.  get_bloginfo('wpurl').'"'."\n"; // could be useful?
}
add_action('rss2_ns', 'feedAddNamespace');


// Add extra information to the RSS feed - http://codex.wordpress.org/Plugin_API/Action_Reference/rss2_item
function feedCustomInfo() {
	global $post;
	// If a post has a featured image and a manual excerpt, add them via <image> and <headline> tags respectively
	if( has_post_thumbnail( $post->ID ) && !empty( $post->post_excerpt ) ) {
		$thumbnailID = get_post_thumbnail_id( $post->ID );
		$image = wp_get_attachment_image_src( $thumbnailID, "slideshow-wsu" );
		echo '<image>' . esc_url( $image[0] ) . '</image>';
		echo '<headline><![CDATA[' . get_the_excerpt() . ']]></headline>';
	}
	// For events, add start time, location, etc. via some GData tags
	if( get_post_type( $post ) == 'tribe_events' ) {
		$eventCost = get_post_meta( $post->ID, '_EventCost', true );
		$venueID = get_post_meta( $post->ID, '_EventVenueID', true );
		$venuePermalink = get_permalink( $venueID );
		$venueName = get_post_meta( $venueID, '_VenueVenue', true );
		$venueAddress = get_post_meta( $venueID, '_VenueAddress', true );
		$venueCity = get_post_meta( $venueID, '_VenueCity', true );
		$venueState = get_post_meta( $venueID, '_VenueState', true );
		if( get_the_terms( $post->ID, 'tribe_events_cat' ) ) {
			foreach( get_the_terms( $post->ID, 'tribe_events_cat' ) as $category ) {
				echo '<category><![CDATA[' . $category->name . ']]></category>';
			}
		}
		echo '<gd:when startTime="' . date( 'D, d M Y H:i:s', strtotime( get_post_meta( $post->ID, '_EventStartDate', true ) ) ) . '" endTime="' . date( 'D, d M Y H:i:s', strtotime( get_post_meta( $post->ID, '_EventEndDate', true ) ) ) . '"/>';
		if( $venueName ) {
			echo '<gd:where rel="http://schemas.google.com/g/2005#event" valueString="' . htmlspecialchars( $venueName ) . '">';
				echo '<gd:entryLink href="' . esc_url( $venuePermalink ) . '">';
					echo '<gd:entry>';
					if( $venueAddress ) {
						echo '<gd:postalAddress>' . esc_html( $venueAddress );
						if( $venueCity )
							echo ', ' . esc_html( $venueCity );
						if( $venueState )
							echo ', ' . esc_html( $venueState );
						echo '</gd:postalAddress>';
					}
						//echo '<gd:phoneNumber>(212) 555-1212</gd:phoneNumber>';
						//echo '<gd:email address="info@joespub.com"/>';
					echo '</gd:entry>';
				echo '</gd:entryLink>';
			echo '</gd:where>';
		}
		// Could include Event Coordinator info, but don't know if it's worth it
		//echo '<gd:who email="email@address.com" rel="http://schemas.google.com/g/2005#event.organizer" valueString="email@address.com"/>';
		if( $eventCost )
			echo '<gd:extendedProperty name="cost" value="' . esc_attr( $eventCost ) . '"></gd:extendedProperty>';
	}
}
add_action('rss2_item', 'feedCustomInfo');


// Do auto excerpts for the feed item description of feature posts
function feedCustomExcerpt( $content ) {
	global $post;
	// If post has featured image and a manual excerpt, we want to use an automatic excerpt for the feed item description
	$tagPosition = strpos( $post->post_content, '<!--more-->' );		
	if( $tagPosition !== false )
		$excerpt = trim( substr( $post->post_content, 0, $tagPosition ) ); // More tag present, grab everything before it. Clean it up with trim
	else
		$excerpt = apply_filters( 'get_the_excerpt', $post->post_excerpt ); // No More tag, generate excerpt using the WordPress excerpt filter
	$content = apply_filters( 'the_content', $excerpt );	
	return $content;
}
add_filter( 'the_excerpt_rss', 'feedCustomExcerpt' );


// Change the default feed cache recreation period to 1 hour - http://codex.wordpress.org/Plugin_API/Filter_Reference/wp_feed_cache_transient_lifetime
function feedCache_hour( $seconds ) {
	return 3600;
}


// Change the default feed cache recreation period to 1 minute
function feedCache_minute( $seconds ) {
	return 60;
}

/*
// Custom Login screen style
function wipCustomLoginStyle() { 
	echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('template_url') . '/css/login.css" />';
}
add_action( 'login_head', 'wipCustomLoginStyle' );
*/

// Display navigation to next/previous pages when applicable
function wipPaginationNav() {
	global $wp_query;
	if( $wp_query->max_num_pages > 1 ) {
		?>
			<div style="clear:both;margin-top:20px;overflow:auto;width:100%;">
				<p class="half"><?php next_posts_link( __( '&laquo; Older Posts' ) ); ?></p>
				<p class="half" style="text-align:right;"><?php previous_posts_link( __( 'Newer Posts &raquo;' ) ); ?></p>
			</div>
		<?php
	}
}


// Customize fields on user profile page
function wipCustomProfileFields( $customFields ) {
	// Custom fields to add
	$customFields['dept'] = 'Department';
	$customFields['title'] = 'Title';
	$customFields['phone'] = 'Phone Number';
	$customFields['office'] = 'Office Location';
	// Existing fields to remove
	unset( $customFields['aim'] );
	unset( $customFields['jabber'] );
	unset( $customFields['yim'] );
	return $customFields;
}
add_filter( 'user_contactmethods', 'wipCustomProfileFields', 10, 1 );


// Allow basic HTML in the user profile biographies, category descriptions, link descriptions and notes
function wipFilterKses( $text ) {
	 return strip_tags ($text, '<a><br><h1><h2><h3><h4><h5><h6><img><p><form><input>' );
}
$wipDescription = array( 'pre_user_description', 'pre_term_description', 'pre_link_description', 'pre_link_notes' );

foreach( $wipDescription as $wipDescription_value ) {
	remove_filter( $wipDescription_value, 'wp_filter_kses' );
	add_filter( $wipDescription_value, 'wipFilterKses' );
}


// Add custom meta data fields to select taxonomies
if( ! function_exists('wipCustomTaxonomy') ) {
	function wipCustomTaxonomy() {
		if( is_admin() ) {

			$includeTaxonomyMeta = array( 'category' ); // Array of taxonomy types to include the WIP custom taxonomy options
			
			$wipTaxonomy = get_taxonomies( array( 'public' => true, '_builtin' => false ), 'objects' ); // Find custom taxonomies, see if they include the WIP custom taxonomy options
			if( $wipTaxonomy ) {
				foreach( $wipTaxonomy as $wipTaxonomy_value ) {
					if( isset( $wipTaxonomy_value->wip_taxonomy_options ) && $wipTaxonomy_value->wip_taxonomy_options == true )
						array_push( $includeTaxonomyMeta, $wipTaxonomy_value->name );
				}
			}

			// Custom fields for taxonomies
			$categoryMetaConfig = array( 'pages' => $includeTaxonomyMeta, 'use_with_theme' => true );
			$categoryMeta = new Tax_Meta_Class( $categoryMetaConfig );
			$categoryMeta->addCheckbox( 'widget', array( 'name'=> 'Widgetize', 'desc'=> 'Add a widget area to the archive sidebar' ) );
			$categoryMeta->addCheckbox( 'widgetPost', array( 'name'=> '', 'desc'=> 'Extend the category widget area to its posts' ) );
			$categoryMeta->addCheckbox( 'magazine', array( 'name'=> '', 'desc'=> 'Digital magazine archive (displays sub-categories and posts therein)' ) );
			//$categoryMeta->addCheckbox( 'author', array( 'name'=> '', 'desc'=> 'Show post author on archive' ) );
			//$categoryMeta->addCheckbox( 'pubDate', array( 'name'=> '', 'desc'=> 'Show post publish date on archive' ) );
			//$categoryMeta->addCheckbox( 'taxonomy', array( 'name'=> '', 'desc'=> 'Show post categories and tags on archive' ) );
			//$categoryMeta->addCheckbox( 'comment', array( 'name'=> '', 'desc'=> 'Show number of comments per post on archive' ) );
			$categoryMeta->addText('navOverride', array( 'name'=> 'Navigation Override', 'desc'=> 'The URL of the navigation link to be set as current when viewing posts in this category' ) );
			$categoryMeta->addText('customCSS', array( 'name'=> 'Custom CSS', 'desc'=> 'The URL of a stylesheet to apply to posts in this category and its archive' ) );
			$categoryMeta->Finish();
		}
	}
}
add_action( 'init', 'wipCustomTaxonomy' );


// Add custom meta data fields to select post types
//function wipCustomContent()
//{

	$includeLayoutMeta = array( 'page', 'post' ); // Array of post types to include WIP Layout Options on
	$includeDynamicMeta = array( 'page' ); // Array of post types to include WIP Dynamic Options on
	
	$wipCCT = get_post_types( $args = array( 'public' => true, '_builtin' => false ) ); // Find custom content types, see if they inclue the WIP Layout and Dynamic options
	if( $wipCCT ) {
		foreach( $wipCCT as $wipCCT_value ) {
			$customContentType = get_post_type_object( $wipCCT_value );
			if( $customContentType->wip_layout_options == true )
				array_push( $includeLayoutMeta, $wipCCT_value );
			if( $customContentType->wip_dynamic_options == true )
				array_push( $includeDynamicMeta, $wipCCT_value );
		}
	}

	// WPAlchemy custom metaboxes - layout options for pages and posts
	$wipLayout = new WPAlchemy_MetaBox( array(
		'id' => '_layout',
		'title' => 'Layout Options',
		'context' => 'normal',
		'priority' => 'high',
		'template' => get_template_directory().'/inc/layout_options.php',
		'types' => $includeLayoutMeta,
	) );

	// WPAlchemy custom metaboxes - dynamic options for pages
	$wipDynamic = new WPAlchemy_MetaBox( array(
		'id' => '_dynamic',
		'title' => 'Dynamic Page Options',
		'context' => 'normal',
		'priority' => 'high',
		'template' => get_template_directory().'/inc/dynamic_options.php',
		'types' => $includeDynamicMeta,
		'head_filter' => 'wpDynamicIncludes',
	) );

	// Stuff to plop in the header for the dynamic options (not sure if this is being done properly)
	function wpDynamicIncludes() {
		/* echo '<link rel="stylesheet" href="' . get_template_directory_uri() . '/css/dynamic_interface.css" type="text/css" media="all" />';
		echo '<script type="text/javascript" src="' . get_template_directory_uri() . '/js/dynamic_page_interface.js"></script>';
		echo '<script type="text/javascript" src="' . get_template_directory_uri() . '/js/layout.js"></script>';*/
		/* Don added these wp_enqueue_style updates per Phil's notes to make the WIP theme compatible with Wordpress 3.8 and higher 5/27/2015 */
		wp_enqueue_style( 'dynamic-interface-style', get_template_directory_uri() . '/css/dynamic_interface.css' );
		wp_enqueue_script( 'dynamic-interface-script', get_template_directory_uri() . '/js/dynamic_page_interface.js', array( 'jquery-ui-draggable' ) );
		wp_enqueue_script( 'layout-script', get_template_directory_uri() . '/js/layout.js' );
	}

//}
//add_action( 'init', 'wipCustomContent' );

/*
// Remove Administrator from dropdowns
function ftHideAdminstratorEditableRoles( $roles ) {
	if( isset( $roles['administrator'] ) && !current_user_can('install_themes') )
		unset( $roles['administrator'] );
	return $roles;
}
add_action( 'editable_roles' , 'ftHideAdminstratorEditableRoles' );


// Hide Administrator from list of users
function ftHideAdministratorUser() {
	if( !current_user_can( 'install_themes' ) ) {
		?>
		<script type='text/javascript' >
			jQuery(document).ready(function(){
				jQuery(".subsubsub > .administrator").remove(); // Remove administrator filter
				jQuery(".check-column > .administrator").parent().parent().remove(); // Remove administrator account from list
			});
		</script>
		<?php
	}
}
add_action( 'admin_head', 'ftHideAdministratorUser' );*/


?>