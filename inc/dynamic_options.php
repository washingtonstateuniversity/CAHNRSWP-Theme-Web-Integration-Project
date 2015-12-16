<?php
/*
Dynamic page builder for page.php.
*/

/*
 * Name: newRadioSelection
 * Description: Generates a new radio button selection group for a widget
 * Variables:
 *			$id (string): Widget ID. This is the name of the widget type (like CTypeFeed)
 *			$optionName (string): The name of the option for saving to the widget's array
 *			$label (string): What to put before the radio buttons
 *			$optionArray (array of strings) : All the options how you want them to appear to the user
 *			$valueDictionary (dictionary): Contains all the available options to show the user as keys for the field values
 *			$selected (string): Value of the selected option, used for displaying saved options
 * Return: String
 */
function newRadioSelection( $id, $optionName, $label, $valueDictionary, $selected='' ) {
	//Initialize optionArray
	$optionArray = array_keys( $valueDictionary );
	
	//Begin new radio selection area
	$construct = $label;
	
	//Loop through each option in the array
	foreach( $optionArray as $option ) {
		//Grab the ID for that option
		$optionId = $valueDictionary[$option];
		
		//Create radio selection to add to list
		$construct .= '<br /><label for="' . esc_attr( $id . '_' . $optionId ) . '"><input type="radio" id="' . esc_attr( $id . '_' . $optionId ) . '" name="_dynamic[' . esc_attr( $id . '_' . $optionName ) . ']" value="' . esc_attr( $valueDictionary[ $option ] ) . '" ';
		if( $valueDictionary[$option] == $selected )
			$construct .= ' checked';
		$construct .= '/> ' . esc_html( $option ) . '</label>';
	}
	
	return $construct;
}

/*
 * Name: newDropDown
 * Description: Generates a new drop down selection menu
 * Variables:
 *			$id (string): Widget ID. This is the name of the widget type (like CTypeFeed)
 *			$optionName (string): The name of the option for saving to the widget's array
 *			$label (string): What to put before the drop down
 *			$valueDictionary (dictionary): Contains all the available options to show the user as keys for the field values
 *			$showSelect (boolean): Set to true to include "(Select)" at the top of the options
 *			$selected (string): Value of the selected option, used for displaying saved options
 * Return: String
 */
function newDropDown( $id, $optionName, $label, $valueDictionary, $showSelect=false, $selected='' ) {
	//Initialize optionArray
	$optionArray = array_keys( $valueDictionary );
	
	//Start drop down selection
	$construct = '<label for="' . esc_attr( $id . '_' . $optionName ) . '">' . esc_html( $label );
	$construct .= '<select id="' . esc_attr( $id . '_' . $optionName ) . '" name="_dynamic[' . esc_attr( $id . '_' . $optionName ) . ']">';
	
	//Show "(Select)"?
	if( $showSelect )
		$construct .= '<option value="">(Select)</option>';
	
	//Loop through each available
	foreach( $optionArray as $option ) {
		//Create option with associated value
		if( $valueDictionary[$option] == $selected )
			$construct .= '<option selected value="' . esc_attr( $valueDictionary[$option] ) . '">' . esc_html( $option ) . '</option>';
		else
			$construct .= '<option value="' . esc_attr( $valueDictionary[$option] ) . '">' . esc_html( $option ) . '</option>';
	}
	
	//Complete drop down
	$construct .= '</select></label>';
	
	return $construct;
}

/*
 * Name: newCheckbox
 * Description: Generates a new checkbox
 * Variables:
 *			$id (string): Widget ID. This is the name of the widget type (like CTypeFeed)
 *			$optionName (string): The name of the option for saving to the widget's array
 *			$label (string): What to put before the drop down
 *			$value (string): Value of the checkbox
 *			$checked (boolean): True for default checked, false for empty
 *			$selected (string): Used for checking if the option has been checked on a saved widget
 * Return: String
 */
function newCheckbox( $id, $optionName, $label, $value, $checked=false, $selected='' ) {
	//Build checkbox
	$construct = '<label for="' . esc_attr( $id . '_' . $optionName ) . '">';
	$construct .= '<input type="checkbox" id="' . esc_attr( $id . '_' . $optionName ) . '" name="_dynamic[' . esc_attr( $id . '_' . $optionName ) . ']" value="' . esc_attr( $value ) . '" ';
	
	//Should the box be checked?
	if( $checked || $selected )
			$construct .= 'checked';
	
	//Finish checkbox
	$construct .= '/> ' . esc_html( $label ) . '</label>';

	return $construct;
}

/*
 * Name: newTextbox
 * Description: Generates a new textbox
 * Variables:
 *			$id (string): Widget ID. This is the name of the widget type (like CTypeFeed)
 *			$optionName (string): The name of the option for saving to the widget's array
 *			$label (string): What to put before the textbox
 *			$value (string): Contents of the textbox
 * Return: String
 */
function newTextbox( $id, $optionName, $label, $value ) {
	//Build textbox
	$construct = '<label for="' . esc_attr( $id . '_' . $optionName ) . '">' . esc_html( $label ) . '<br />';
	$construct .= '<input id="' . esc_attr( $id . '_' . $optionName ) . '" name="_dynamic[' . esc_attr( $id . '_' . $optionName ) . ']" type="text" value="' . esc_attr( $value ) . '" /></label>';

	return $construct;
}

/*
 * Name: initializeWidget
 * Description: Generates the code for starting a new widget
 * Variables:
 *			$id (string): Widget ID. This is the name of the widget type (like CTypeFeed)
 *			$name (string): The widget title/name
 * Return: String
 */
function initializeWidget( $id, $name ) {
	$construct = '<div id="' . esc_attr( $id ) . '" class="widget ui-draggable"><div class="widget-top"><div class="widget-title-action">';
	$construct .= '<a class="widget-action hide-if-no-js" href="#available-widgets"></a>';
	$construct .= '<a class="widget-control-edit hide-if-js" href="/wp-admin/widgets.php?editwidget=links-2&sidebar=sidebar-1&key=3"><span class="edit">Edit</span><span class="add">Add</span></a>';
	$construct .= '</div><div class="widget-title"><h4>' . esc_html( $name ) . '</h4></div></div><div class="widget-inside"><div class="widget-content">';
	
	return $construct;
}

/*
 * Name: endWidget
 * Description: Generates the closing code for a created widget
 * Variables:
 *			$description (string): The description of the widget
 * Return: String
 */
function endWidget( $description ) {
	$construct = '</div><p><a class="widget-control-remove" href="#remove">Delete</a> | <a class="widget-control-close" href="#close">Close</a></p>';
	$construct .= '</div><div class="widget-description">' . esc_html( $description ) . '</div></div>';

	return $construct;
}

/*
 * Name: convertToDictionary
 * Description: Takes a WordPress category objects array and converts it to a dictionary for widget use
 * Variables:
 *			$categories (array of category objects): The entire list of categories as returned by get_categories()
 * Return: String
 */
function convertToDictionary( $categories ) {
	$newArray = array();
	foreach( $categories as $category ) {
		$newArray[$category->cat_name] = $category->term_id;
	}
	
	return $newArray;
}

function savedWidgets( $widgetList, $wipDynamicMeta, $masterDictionary/*, $cctWidget_name, $cctWidget_saved*/ ) {
	foreach( $widgetList as $widget ) {
		$type = strstr( $widget, '_', true );
		
		// Grab name for labelling widget
		// Page content and documents are special in that their widget name doesn't contain their full display name
		if( $type == 'cTypePage' )
			$name = 'Page Content';
		elseif( $type == 'cTypeDocs' )
			$name = 'Documents';
		elseif( $type == 'cTypeMostCommented' )
			$name = 'Most Commented';
		else
			$name = substr( $type, 5 );

		echo initializeWidget( $widget, $name );

		if( $type == 'cTypePage' ) {
			// No fields to add to widget just some information
			echo '<p class="sshide">Content section ' . strval( intval( substr( $widget, 10, 11 ) ) + 1 ) . ' from this page. Create separate sections by using the "More" tag.</p>';
			echo '<p class="ssshow">Page content cannot be used in the slideshow. Please delete this widget.</p>';
		} elseif( $type == 'cTypePosts' ) {														
		// POSTS widget
			echo '<p class="sshide">' . newTextbox( $widget, $masterDictionary['nameDictionary']['title'], 'Section Title', $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['title']] ) . '</p>';
			echo '<p class="sshide">' . newDropDown( $widget, $masterDictionary['nameDictionary']['heading'], 'Title Heading Tag', $masterDictionary['headingDictionary'], true, $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['heading']] ) . '</p>';
			echo '<p>' . newDropDown( $widget, $masterDictionary['nameDictionary']['category'], 'Category', convertToDictionary( get_categories() ), true, $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['category']] ) . '</p>';
			echo '<p>' . newDropDown( $widget, $masterDictionary['nameDictionary']['count'], 'Number of Posts to Show', $masterDictionary['itemCountDictionary'], true, $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['count']] ) . '</p>';
			echo '<p class="sshide">' . newCheckbox( $widget, $masterDictionary['nameDictionary']['date'], 'Show Post Dates', 'show', false, $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['date']] ) . '<br />';
			echo newCheckbox( $widget, $masterDictionary['nameDictionary']['teaser'], 'Show Post Teasers', 'show', false, $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['teaser']]	) . '<br />';
			echo newCheckbox( $widget, $masterDictionary['nameDictionary']['thumb'], 'Show Post Featured Image', 'show', false, $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['thumb']]	) . '</p>';
			echo '<p class="sshide">' . newRadioSelection( $widget, $masterDictionary['nameDictionary']['display'], 'Display Posts as', $masterDictionary['displayDictionary'], $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['display']] ) . '</p>';
			echo '<p class="sshide">' . newRadioSelection( $widget, $masterDictionary['nameDictionary']['order'], 'Order Posts by', $masterDictionary['orderDictionary'], $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['order']] ) . '</p>';
		} elseif( $type == 'cTypeMostCommented' ) {														
		// MOST COMMENTED widget (TODO: Consolidate option into POSTS widget)
			echo '<p class="sshide">' . newTextbox( $widget, $masterDictionary['nameDictionary']['title'], 'Section Title', $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['title']] ) . '</p>';
			echo '<p class="sshide">' . newDropDown( $widget, $masterDictionary['nameDictionary']['heading'], 'Title Heading Tag', $masterDictionary['headingDictionary'], false, $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['heading']] ) . '</p>';
			echo '<p>' . newDropDown( $widget, $masterDictionary['nameDictionary']['count'], 'Number of Posts to Show', $masterDictionary['itemCountDictionary'], false, $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['count']] ) . '</p>';
		} elseif( $type == 'cTypeFeed' ) {
		// FEED widget
			echo '<p class="sshide">' . newTextbox( $widget, $masterDictionary['nameDictionary']['title'], 'Section Title', $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['title']] ) . '</p>';
			echo '<p class="sshide">' . newDropDown( $widget, $masterDictionary['nameDictionary']['heading'], 'Title Heading Tag', $masterDictionary['headingDictionary'], true, $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['heading']] ) . '</p>';
			echo '<p>' . newTextbox( $widget, $masterDictionary['nameDictionary']['url'], 'Feed URL', $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['url']] ) . '</p>';
			echo '<p>' . newDropDown( $widget, $masterDictionary['nameDictionary']['count'], 'Number of Items to Show', $masterDictionary['itemCountDictionary'] , true, $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['count']]	) . '</p>';
			echo '<p class="sshide">' . newCheckbox( $widget, $masterDictionary['nameDictionary']['date'], 'Show Item Dates', 'show', false, $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['date']] ) . '<br />';
			echo newCheckbox( $widget, $masterDictionary['nameDictionary']['teaser'], 'Show Item Teasers', 'show', false, $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['teaser']] ) . '</p>';
			echo '<p class="sshide">' . newRadioSelection( $widget, $masterDictionary['nameDictionary']['display'], 'Display Items as', $masterDictionary['displayDictionary'], $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['display']] ) . '</p>';
		} elseif( $type == 'cTypeEvents' ) {
		// EVENTS widget
			// Widget can't be used in the slideshow
			echo '<p class="ssshow">Events cannot be used in the slideshow. Please delete this widget.</p>';
			echo '<p class="sshide">' . newTextbox( $widget, $masterDictionary['nameDictionary']['title'], 'Section Title', $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['title']] ) . '</p>';
			echo '<p class="sshide">' . newDropDown( $widget, $masterDictionary['nameDictionary']['heading'], 'Title Heading Tag', $masterDictionary['headingDictionary'], true, $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['heading']] ) . '</p>';
			echo '<p class="sshide">' . newDropDown( $widget, $masterDictionary['nameDictionary']['count'], 'Number of Events to Show', $masterDictionary['itemCountDictionary'], true, $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['count']]	) . '</p>';
			echo '<p class="sshide">' . newCheckbox( $widget, $masterDictionary['nameDictionary']['teaser'], 'Show Event teasers', 'show', false, $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['teaser']]	) . '</p>';
			echo '<p class="sshide">' . newRadioSelection( $widget, $masterDictionary['nameDictionary']['display'], 'Display Items as', $masterDictionary['displayDictionary'], $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['display']] ) . '</p>';
			echo '<p class="sshide">' . newCheckbox( $widget, $masterDictionary['nameDictionary']['link'], 'Show Link to Full CAHNRS Calendar', 'show', false, $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['link']]	) . '</p>';
			echo '<p class="sshide"><strong>Filter by:</strong></p>';
			echo '<p class="sshide">' . newDropDown( $widget, $masterDictionary['nameDictionary']['category'], 'Category', $masterDictionary['eventsDictionary'], true, $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['category']] ) . '</p>';
			echo '<p class="sshide">' . newDropDown( $widget, $masterDictionary['nameDictionary']['region'], 'Region', $masterDictionary['regionDictionary'], true, $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['region']] ) . '</p>';
			echo '<p class="sshide">' . newRadioSelection( $widget, $masterDictionary['nameDictionary']['author'], 'Author', $masterDictionary['authorDictionary'], $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['author']] );
			echo newTextbox( $widget, 'author', '', $wipDynamicMeta[$widget . '_' . 'author'] ) . '</p>';
		} elseif( $type == 'cTypeLinks' ) {
		// LINKS widget
			echo '<p class="sshide">' . newTextbox( $widget, $masterDictionary['nameDictionary']['title'], 'Section Title', $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['title']] ) . '</p>';
			echo '<p class="sshide">' . newDropDown( $widget, $masterDictionary['nameDictionary']['heading'], 'Title Heading Tag', $masterDictionary['headingDictionary'], true, $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['heading']] ) . '</p>';
			echo '<p>' . newDropDown( $widget, $masterDictionary['nameDictionary']['category'], 'Category', convertToDictionary( get_categories( array( 'hide_empty' => 0, 'taxonomy' => 'link_category' ) ) ), true, $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['category']] ) . '</p>';
			echo '<p class="ssshow">';
			echo newDropDown( $widget, $masterDictionary['nameDictionary']['count'], 'Number of Events to Show', $masterDictionary['itemCountDictionary'], true, $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['count']]	) . '</p>';
			echo '<p class="sshide">' . newCheckbox( $widget, $masterDictionary['nameDictionary']['teaser'], 'Show Link Notes', 'show', false, $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['teaser']] ) . '</p>';
			echo '<p class="sshide">' . newRadioSelection( $widget, $masterDictionary['nameDictionary']['display'], 'Display Items as', $masterDictionary['displayDictionary'], $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['display']] ) . '</p>';
		} elseif( $type == 'cTypeDocs' ) {
		// DOCUMENTS widget
			// Widget can't be used in the slideshow
			echo '<p class="ssshow">Documents cannot be used in the slideshow. Please delete this widget.</p>';
			echo '<p class="sshide">' . newTextbox( $widget, $masterDictionary['nameDictionary']['title'], 'Section Title', $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['title']] ) . '</p>';
			echo '<p class="sshide">' . newDropDown( $widget, $masterDictionary['nameDictionary']['heading'], 'Title Heading Tag', $masterDictionary['headingDictionary'], true, $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['heading']] ) . '</p>';
			echo '<p class="sshide">' . newDropDown( $widget, $masterDictionary['nameDictionary']['category'], 'Category', convertToDictionary( get_categories( array( 'hide_empty' => 0, 'taxonomy' => 'media_category' ) ) ), true, $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['category']] ) . '</p>';
			echo '<p class="sshide">' . newDropDown( $widget, $masterDictionary['nameDictionary']['count'], 'Number of Documents to Show', $masterDictionary['itemCountDictionary'], true, $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['count']]	) . '</p>';
			echo '<p class="sshide">' . newCheckbox( $widget, $masterDictionary['nameDictionary']['teaser'], 'Show Document Descriptions', 'show', false, $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['teaser']] ) . '</p>';
			echo '<p class="sshide">' . newRadioSelection( $widget, $masterDictionary['nameDictionary']['display'], 'Display Items as', $masterDictionary['displayDictionary'], $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['display']] ) . '</p>';
			echo '<p class="sshide">' . newRadioSelection( $widget, $masterDictionary['nameDictionary']['order'], 'Order Items by', $masterDictionary['orderDictionary'], $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['order']] ) . '</p>';
		} elseif( $name == 'Location' ) {
		// LOCATION widget
			// Widget can't be used in the slideshow
			echo '<p class="ssshow">Location cannot be used in the slideshow. Please delete this widget.</p>';
			
			echo '<p class="sshide">' . newTextbox( $widget, $masterDictionary['nameDictionary']['title'], 'Section Title', $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['title']] ) . '</p>';
			echo '<p class="sshide">' . newDropDown( $widget, $masterDictionary['nameDictionary']['heading'], 'Title Heading Tag', $masterDictionary['headingDictionary'], true, $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['heading']] ) . '</p>';
			echo '<p class="sshide">' . newTextbox( $widget, $masterDictionary['nameDictionary']['location'], 'Location ID', $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['location']] ) . '</p>';
		} elseif( $name == 'Employees' ) {
		// EMPLOYEES widget (TODO: Consolidate Directory integration into a single widget?)
			// Widget can't be used in the slideshow
			echo '<p class="ssshow">Employees cannot be used in the slideshow. Please delete this widget.</p>';
			echo '<p class="sshide">' . newTextbox( $widget, $masterDictionary['nameDictionary']['title'], 'Section Title', $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['title']] ) . '</p>';
			echo '<p class="sshide">' . newDropDown( $widget, $masterDictionary['nameDictionary']['heading'], 'Title Heading Tag', $masterDictionary['headingDictionary'], true, $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['heading']] ) . '</p>';
			echo '<p class="sshide">' . newTextbox( $widget, $masterDictionary['nameDictionary']['location'], 'Location ID', $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['location']] ) . '</p>';
		} elseif( $name == 'Directory' ) {
		// DIRECTORY widget (TODO: Consolidate Directory integration into a single widget?)
			// Widget can't be used in the slideshow
			echo '<p class="ssshow">Directory cannot be used in the slideshow. Please delete this widget.</p>';
			echo '<p class="sshide">' . newTextbox( $widget, $masterDictionary['nameDictionary']['title'], 'Section Title', $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['title']] ) . '</p>';
			echo '<p class="sshide">' . newDropDown( $widget, $masterDictionary['nameDictionary']['heading'], 'Title Heading Tag', $masterDictionary['headingDictionary'], true, $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['heading']] ) . '</p>';
			echo '<p class="sshide">' . newTextbox( $widget, $masterDictionary['nameDictionary']['nid'], 'Network ID', $wipDynamicMeta[$widget . '_' . $masterDictionary['nameDictionary']['nid']] ) . '</p>';
		}
/*		elseif( $type == 'cType' . $cctWidget_name )
		{
			echo $cctWidget_saved;
		}
*/
		echo endWidget( $widget['desc'] );
	}
}


// Variables
global $wipDynamicMeta,$post;
$wipDynamicMeta = get_post_meta( $post->ID, '_dynamic', TRUE );

/*
// Custom content types
$wipDynamicCCT = get_post_types( $args = array( 'public' => true, '_builtin' => false ) ); // Find custom content types, see if they need to be widgets
if( $wipDynamicCCT )
{
	foreach( $wipDynamicCCT as $wipDynamicCCT_value )
	{
		$customContentType = get_post_type_object( $wipDynamicCCT_value );
		if( $customContentType->dynamic_widget == true )
			$cctWidget = $customContentType->name;
			$cctWidget_name = $customContentType->label;
			$cctWidget_options = $customContentType->widget_options;
			$cctWidget_saved = $customContentType->widget_saved;
	}
}
*/

// Prebuilt dictionaries
$widgetList = array(
	'page' => array(
		'name' => 'Page Content',
		'id' => 'cTypePage',
		'desc' => 'Content from this page',
	),
	'posts' => array(
		'name' => 'Posts',
		'id' => 'cTypePosts',
		'desc' => 'Posts from a specific category',
	),
	'mostCommented' => array(
		'name' => 'Most Commented',
		'id' => 'cTypeMostCommented',
		'desc' => 'Lists posts with the most comments',
	),
	'feed' => array(
		'name' => 'Feed',
		'id' => 'cTypeFeed',
		'desc' => 'Items from an RSS feed',
	),
	'events' => array(
		'name' => 'Events',
		'id' => 'cTypeEvents',
		'desc' => 'Event feed from the CAHNRS master calendar',
	),
	'links' => array(
		'name' => 'Links',
		'id' => 'cTypeLinks',
		'desc' => 'Uploaded files from a specific category',
	),
	'documents' => array(
		'name' => 'Documents',
		'id' => 'cTypeDocs',
		'desc' => 'Uploaded files from a specific category',
	),
	'location' => array(
		'name' => 'Location',
		'id' => 'cTypeLocation',
		'desc' => 'Show location information like director, address, phone number and hours'
	),
	'employees' => array(
		'name' => 'Employees',
		'id' => 'cTypeEmployees',
		'desc' => 'Show a list of employees, their titles and contact information'
	),
	'directory' => array(
		'name' => 'Directory',
		'id' => 'cTypeDirectory',
		'desc' => 'Show directory information for a given network ID'
	)/*,
	$cctWidget => array(
		'name' => $cctWidget_name,
		'id' => 'cType' . $cctWidget_name,
		'desc' => 'Display ' . $cctWidget . ' based on selected options'
	)*/);

// Dictionary collection
$masterDictionary = array(
	'nameDictionary' => array(
		'title' => 'title',
		'heading' => 'hTag',
		'category' => 'category',
		'count' => 'number',
		'date' => 'date',
		'teaser' => 'desc',
		'thumb' => 'featureImg',
		'display' => 'displayAs',
		'link' => 'link',
		'url' => 'url',
		'region' => 'region',
		'author' => 'authorship',
		'location' => 'location',
		'nid' => 'nid',
		'order' => 'order'
	 ),
	'headingDictionary' => array(
		'h1' => '1',
		'h2' => '2',
		'h3' => '3',
		'h4' => '4'
	),
	'itemCountDictionary' => array(
		'1' => '1',
		'2' => '2',
		'3' => '3',
		'4' => '4',
		'5' => '5',
		'10' => '10',
		'20' => '20',
		'25' => '25',
		'Unlimited' => '-1'
	),
	'displayDictionary' => array( 
		'List Items' => 'list',
		'Paragraphs' => 'paragraph'
	),
	'orderDictionary' => array( 
		'Date' => 'date',
		'Title' => 'title'
	),
	'eventsDictionary' => array(
		'4-H Youth Development' => '4-H Youth Development',
		'Agricultural Business' => 'Agricultural Business',
		'Animal Agriculture' => 'Animal Agriculture',
		'Communities and Economic Development' => 'Communities and Economic Development',
		'Conference' => 'Conference',
		'Energy and Climate Change' => 'Energy and Climate Change',
		'Environment and Natural Resources' => 'Environment and Natural Resources',
		'Fairs and Festivals' => 'Fairs and Festivals',
		'Field Day' => 'Field Day',
		'Food and Sensory Science' => 'Food and Sensory Science',
		'Gardening' => 'Gardening',
		'Genomics and Bioinformatics' => 'Genomics and Bioinformatics',
		'Grapes and Wine' => 'Grapes and Wine',
		'Irrigation and Water Management' => 'Irrigation and Water Management',
		'Mechanization and Equipment' => 'Mechanization and Equipment',
		'Nutrition and Healthy Families' => 'Nutrition and Healthy Families',
		'Organic and Diversified Agriculture' => 'Organic and Diversified Agriculture',
		'Pests, Diseases and Weeds' => 'Pests, Diseases and Weeds',
		'Sales' => 'Sales',
		'Seminar' => 'Seminar',
		'Soils and Fertility' => 'Soils and Fertility',
		'Tree Fruit' => 'Tree Fruit',
		'Turfgrass' => 'Turfgrass',
		'Vegetables and Small Fruits' => 'Vegetables and Small Fruits',
		'Weather and Climate' => 'Weather and Climate',
		'Wheat and Small Grains' => 'Wheat and Small Grains'
	 ),
	'regionDictionary' => array(
		'Puget Sound' => 'Puget Sound',
		'Eastern WA' => 'Eastern WA',
		'Western WA' => 'Western WA'
	 ),
	 'authorDictionary' => array(
		'My events' => 'page',
		'Events from:' => 'custom'
	 )
);

?>
<div class="widgets-holder-wrap ui-droppable" id="available-widgets">
	<div class="widget-holder">
		<p class="description">Drag content types from here to the layout below to populate this page with dynamic content.</p>
		<div id="widget-list">
		<?php
			foreach( $widgetList as $widget ) {
				// Grab widget ID/name
				$id = $widget['id'] . '___i__';
				$name = $widget['name'];

				echo initializeWidget( $id, $name );

				if( $name == 'Page Content' ) {
					// No fields to add to widget just some information
					echo '<p class="sshide">The content from this page. Create separate sections by using the "More" tag.</p>';
					echo '<p class="ssshow">Page content cannot be used in the slideshow. Please delete this widget.</p>';
				} elseif( $name == 'Posts' ) {														
				// POSTS widget
					echo '<p class="sshide">' . newTextbox( $id, $masterDictionary['nameDictionary']['title'], 'Section Title', '' ) . '</p>';
					echo '<p class="sshide">' . newDropDown( $id, $masterDictionary['nameDictionary']['heading'], 'Title Heading Tag', $masterDictionary['headingDictionary'], true ) . '</p>';
					echo '<p>' . newDropDown( $id, $masterDictionary['nameDictionary']['category'], 'Category', convertToDictionary( get_categories() ), true ) . '</p>';
					echo '<p>' . newDropDown( $id, $masterDictionary['nameDictionary']['count'], 'Number of Posts to Show', $masterDictionary['itemCountDictionary'], true ) . '</p>';
					echo '<p class="sshide">' . newCheckbox( $id, $masterDictionary['nameDictionary']['date'], 'Show Post Dates', 'show', false ) . '<br />';
					echo newCheckbox( $id, $masterDictionary['nameDictionary']['teaser'], 'Show Post Teasers', 'show', false ) . '<br />';
					echo newCheckbox( $id, $masterDictionary['nameDictionary']['thumb'], 'Show Post Featured Image', 'show', false ) . '</p>';
					echo '<p class="sshide">' . newRadioSelection( $id, $masterDictionary['nameDictionary']['display'], 'Display Posts as', $masterDictionary['displayDictionary'] ) . '</p>';
					echo '<p class="sshide">' . newRadioSelection( $id, $masterDictionary['nameDictionary']['order'], 'Order Posts by', $masterDictionary['orderDictionary'] ) . '</p>';
				} elseif( $name == 'Most Commented' ) {														
				// MOST COMMENTED widget (TODO: consolidate this option into POSTS widget)
					echo '<p class="sshide">' . newTextbox( $id, $masterDictionary['nameDictionary']['title'], 'Section Title', '' ) . '</p>';
					echo '<p class="sshide">' . newDropDown( $id, $masterDictionary['nameDictionary']['heading'], 'Title Heading Tag', $masterDictionary['headingDictionary'], true ) . '</p>';
					echo '<p>' . newDropDown( $id, $masterDictionary['nameDictionary']['count'], 'Number of Posts to Show', $masterDictionary['itemCountDictionary'], true ) . '</p>';
				} elseif( $name == 'Feed' ) {
				// FEED widget
					echo '<p class="sshide">' . newTextbox( $id, $masterDictionary['nameDictionary']['title'], 'Section Title', '' ) . '</p>';
					echo '<p class="sshide">' . newDropDown( $id, $masterDictionary['nameDictionary']['heading'], 'Title Heading Tag', $masterDictionary['headingDictionary'], true ) . '</p>';
					echo '<p>' . newTextbox( $id, $masterDictionary['nameDictionary']['url'], 'Feed URL', '' ) . '</p>';
					echo '<p>' . newDropDown( $id, $masterDictionary['nameDictionary']['count'], 'Number of Items to Show', $masterDictionary['itemCountDictionary'] , true ) . '</p>';
					echo '<p class="sshide">' . newCheckbox( $id, $masterDictionary['nameDictionary']['date'], 'Show Item Dates', 'show', false ) . '<br />';
					echo newCheckbox( $id, $masterDictionary['nameDictionary']['teaser'], 'Show Item Teasers', 'show', false ) . '</p>';
					echo '<p class="sshide">' . newRadioSelection( $id, $masterDictionary['nameDictionary']['display'], 'Display Items as', $masterDictionary['displayDictionary'] ) . '</p>';
				} elseif( $name == 'Events' ) {
				// EVENTS widget
					// Widget can't be used in the slideshow
					echo '<p class="ssshow">Events cannot be used in the slideshow. Please delete this widget.</p>';
					echo '<p class="sshide">' . newTextbox( $id, $masterDictionary['nameDictionary']['title'], 'Section Title', '' ) . '</p>';
					echo '<p class="sshide">' . newDropDown( $id, $masterDictionary['nameDictionary']['heading'], 'Title Heading Tag', $masterDictionary['headingDictionary'], true ) . '</p>';
					echo '<p class="sshide">' . newDropDown( $id, $masterDictionary['nameDictionary']['count'], 'Number of Events to Show', $masterDictionary['itemCountDictionary'], true ) . '</p>';
					echo '<p class="sshide">' . newCheckbox( $id, $masterDictionary['nameDictionary']['teaser'], 'Show Event teasers', 'show', false ) . '</p>';
					echo '<p class="sshide">' . newRadioSelection( $id, $masterDictionary['nameDictionary']['display'], 'Display Items as', $masterDictionary['displayDictionary'] ) . '</p>';
					echo '<p class="sshide">' . newCheckbox( $id, $masterDictionary['nameDictionary']['link'], 'Show Link to Full CAHNRS Calendar', 'show', false ) . '</p>';
					echo '<p class="sshide"><strong>Filter by:</strong></p>';
					echo '<p class="sshide">' . newDropDown( $id, $masterDictionary['nameDictionary']['category'], 'Category', $masterDictionary['eventsDictionary'], true ) . '</p>';
					echo '<p class="sshide">' . newDropDown( $id, $masterDictionary['nameDictionary']['region'], 'Region', $masterDictionary['regionDictionary'], true ) . '</p>';
					echo '<p class="sshide">' . newRadioSelection( $id, $masterDictionary['nameDictionary']['author'], 'Author', $masterDictionary['authorDictionary'] );
					echo newTextbox( $id, 'author', '', '' ) . '</p>';
				} elseif( $name == 'Links' ) {
				// LINKS widget
					echo '<p class="sshide">' . newTextbox( $id, $masterDictionary['nameDictionary']['title'], 'Section Title', '' ) . '</p>';
					echo '<p class="sshide">' . newDropDown( $id, $masterDictionary['nameDictionary']['heading'], 'Title Heading Tag', $masterDictionary['headingDictionary'], true ) . '</p>';
					echo '<p>' . newDropDown( $id, $masterDictionary['nameDictionary']['category'], 'Category', convertToDictionary( get_categories( array( 'hide_empty' => 0, 'taxonomy' => 'link_category' ) ) ), true ) . '</p>';
					echo '<p class="ssshow">' . newDropDown( $id, $masterDictionary['nameDictionary']['count'], 'Number of Links to Show', $masterDictionary['itemCountDictionary'], true ) . '</p>';
					echo '<p class="sshide">' . newCheckbox( $id, $masterDictionary['nameDictionary']['teaser'], 'Show Link Notes', 'show', false ) . '</p>';
					echo '<p class="sshide">' . newRadioSelection( $id, $masterDictionary['nameDictionary']['display'], 'Display Items as', $masterDictionary['displayDictionary'] ) . '</p>';
				} elseif( $name == 'Documents' ) {
				// DOCUMENTS widget
					// Widget can't be used in the slideshow
					echo '<p class="ssshow">Documents cannot be used in the slideshow. Please delete this widget.</p>';
					echo '<p class="sshide">' . newTextbox( $id, $masterDictionary['nameDictionary']['title'], 'Section Title', '' ) . '</p>';
					echo '<p class="sshide">' . newDropDown( $id, $masterDictionary['nameDictionary']['heading'], 'Title Heading Tag', $masterDictionary['headingDictionary'], true ) . '</p>';
					echo '<p class="sshide">' . newDropDown( $id, $masterDictionary['nameDictionary']['category'], 'Category', convertToDictionary( get_categories( array( 'hide_empty' => 0, 'taxonomy' => 'media_category' ) ) ), true ) . '</p>';
					echo '<p class="sshide">' . newDropDown( $id, $masterDictionary['nameDictionary']['count'], 'Number of Documents to Show', $masterDictionary['itemCountDictionary'], true ) . '</p>';
					echo '<p class="sshide">' . newCheckbox( $id, $masterDictionary['nameDictionary']['teaser'], 'Show Document Descriptions', 'show', false ) . '</p>';
					echo '<p class="sshide">' . newRadioSelection( $id, $masterDictionary['nameDictionary']['display'], 'Display Items as', $masterDictionary['displayDictionary'] ) . '</p>';
					echo '<p class="sshide">' . newRadioSelection( $id, $masterDictionary['nameDictionary']['order'], 'Order Items by', $masterDictionary['orderDictionary'] ) . '</p>';
				} elseif( $name == 'Location' ) {
				// LOCATION widget
					// Widget can't be used in the slideshow
					echo '<p class="ssshow">Location cannot be used in the slideshow. Please delete this widget.</p>';
					echo '<p class="sshide">' . newTextbox( $id, $masterDictionary['nameDictionary']['title'], 'Section Title', '' ) . '</p>';
					echo '<p class="sshide">' . newDropDown( $id, $masterDictionary['nameDictionary']['heading'], 'Title Heading Tag', $masterDictionary['headingDictionary'], true ) . '</p>';
					echo '<p class="sshide">' . newTextbox( $id, $masterDictionary['nameDictionary']['location'], 'Location ID', '' ) . '</p>';
				} elseif( $name == 'Employees' ) {
				// EMPLOYEES widget (TODO: Consolidate Directory integration into single widget?) 
					// Widget can't be used in the slideshow
					echo '<p class="ssshow">Employees cannot be used in the slideshow. Please delete this widget.</p>';
					echo '<p class="sshide">' . newTextbox( $id, $masterDictionary['nameDictionary']['title'], 'Section Title', '' ) . '</p>';
					echo '<p class="sshide">' . newDropDown( $id, $masterDictionary['nameDictionary']['heading'], 'Title Heading Tag', $masterDictionary['headingDictionary'], true ) . '</p>';
					echo '<p class="sshide">' . newTextbox( $id, $masterDictionary['nameDictionary']['location'], 'Location ID', '' ) . '</p>';
				} elseif( $name == 'Directory' ) {
				// DIRECTORY widget (TODO: Consolidate Directory integration into single widget?)
					// Widget can't be used in the slideshow
					echo '<p class="ssshow">Directory cannot be used in the slideshow. Please delete this widget.</p>';
					echo '<p class="sshide">' . newTextbox( $id, $masterDictionary['nameDictionary']['title'], 'Section Title', '' ) . '</p>';
					echo '<p class="sshide">' . newDropDown( $id, $masterDictionary['nameDictionary']['heading'], 'Title Heading Tag', $masterDictionary['headingDictionary'], true ) . '</p>';
					echo '<p class="sshide">' . newTextbox( $id, $masterDictionary['nameDictionary']['nid'], 'Network ID', '' ) . '</p>';
				} elseif( $name == $cctWidget_name ) {
					echo $cctWidget_options;
				}

				echo endWidget( $widget['desc'] );
			}

			// These keep track of the number of their respective content types being used
			foreach( $widgetList as $widgetSettings ) {
				$widgetID = $widgetSettings['id'];
				
				//Metabox ($mb) only works when it's done this way.
				?>
				<input class="multi_number" id="<?php echo esc_attr( $widgetID ); ?>" name="<?php echo esc_attr( $mb->the_name( $widgetID.'_multi' ) ); ?>" type="hidden" value="<?php if( !empty( $wipDynamicMeta[$widgetID.'_multi'] ) ) echo esc_attr( $wipDynamicMeta[$widgetID.'_multi'] ); else echo '0'; ?>">
				<?php
			}
		?>
		</div>
		<br class="clear" />
	</div>
	<br class="clear" />
</div>

<div id="wip-layout-wrap">

	<div id="wip-h" class="layoutwrap" style="display:none;">
		<div id="wipHome" class="postbox widgets-sortables ui-sortable">
			<p class="description">Slideshow. Only cycles through 1) Posts with a featured image; 2) Links with an "Image Address" set; or 3) Post feeds from within the CAHNRS/Extension network.</p>
			<?php
				if( !empty( $wipDynamicMeta['wipHomeArray'] ) ) {
					$cType = explode( ",", $wipDynamicMeta['wipHomeArray'] );
					savedWidgets( $cType, $wipDynamicMeta, $masterDictionary/*, $cctWidget_name, $cctWidget_saved*/ );
				}
			?>
		</div>
	</div>

	<div id="wip-m" class="layoutwrap">
		<div id="wipMain" class="postbox widgets-sortables ui-sortable">
			<?php
				if( !empty( $wipDynamicMeta['wipMainArray'] ) ) {
					$cType = explode( ",", $wipDynamicMeta['wipMainArray'] );
					savedWidgets( $cType, $wipDynamicMeta, $masterDictionary/*, $cctWidget_name, $cctWidget_saved*/ );
				}
			?>
		</div>
	</div>

	<div id="wip-s" class="layoutwrap" style="display:none;">
		<div id="wipSecondary" class="postbox widgets-sortables ui-sortable">
			<?php
				if( !empty( $wipDynamicMeta['wipSecondaryArray'] ) ) {
					$cType = explode( ",", $wipDynamicMeta['wipSecondaryArray'] );
					savedWidgets( $cType, $wipDynamicMeta, $masterDictionary/*, $cctWidget_name, $cctWidget_saved*/ );
				}
			?>
		</div>
	</div>

	<div id="wip-a" class="layoutwrap" style="display:none;">
		<div id="wipAdditional" class="postbox widgets-sortables ui-sortable">
			<?php
				if( !empty( $wipDynamicMeta['wipAdditionalArray'] ) ) {
					$cType = explode( ",", $wipDynamicMeta['wipAdditionalArray'] );
					savedWidgets( $cType, $wipDynamicMeta, $masterDictionary/*, $cctWidget_name, $cctWidget_saved*/ );
				}
			?>
		</div>
	</div>
  
  <div id="wip-f" class="layoutwrap" style="display:none;">
		<div id="wipFourth" class="postbox widgets-sortables ui-sortable">
			<?php
				if( !empty( $wipDynamicMeta['wipFourthArray'] ) ) {
					$cType = explode( ",", $wipDynamicMeta['wipFourthArray'] );
					savedWidgets( $cType, $wipDynamicMeta, $masterDictionary/*, $cctWidget_name, $cctWidget_saved*/ );
				}
			?>
		</div>
	</div>

	<br class="clear" />

	<?php $mb->the_field( 'wipHomeArray' ); ?>
	<input id="wipHomeArray" name="<?php esc_attr( $mb->the_name() ); ?>" type="hidden" value="<?php esc_attr( $mb->the_value() ); ?>" />
	<?php $mb->the_field( 'wipMainArray' ); ?>
	<input id="wipMainArray" name="<?php esc_attr( $mb->the_name() ); ?>" type="hidden" value="<?php esc_attr( $mb->the_value() ); ?>" />
	<?php $mb->the_field( 'wipSecondaryArray' ); ?>
	<input id="wipSecondaryArray" name="<?php esc_attr( $mb->the_name() ); ?>" type="hidden" value="<?php esc_attr( $mb->the_value() ); ?>" />
	<?php $mb->the_field( 'wipAdditionalArray' ); ?>
	<input id="wipAdditionalArray" name="<?php esc_attr( $mb->the_name() ); ?>" type="hidden" value="<?php esc_attr( $mb->the_value() ); ?>" />
  <?php $mb->the_field( 'wipFourthArray' ); ?>
	<input id="wipFourthArray" name="<?php esc_attr( $mb->the_name() ); ?>" type="hidden" value="<?php esc_attr( $mb->the_value() ); ?>" />

</div>