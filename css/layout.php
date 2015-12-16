<?php
/*
Serve up CSS based on what kind of layout is being used
*/

// Get the category's ID and make it a variable for use later on if this is a category archive
if( is_category() )
	$categoryID = get_query_var( 'cat' );


// Establish some variables to determine the layout of a custom content type post
if( is_singular() && ! is_singular( array ( 'post', 'page', 'attachment' ) ) ) {
	$customContentType = get_post_type();
	$customContentType_object = get_post_type_object( $customContentType );
	//$customContentType_extend = $customContentType_object->wip_layout_options;
	//if( $customContentType_extend == false )
		$standardLayout = $customContentType_object->single_layout;
}


// Establish some variables to determine the layout of a custom content type archive
if( is_post_type_archive() && ( ! is_post_type_archive( array ( 'post', 'page', 'attachment' ) ) || ! is_tax() ) ) {
	$customContentType = get_queried_object()->name;
	$customContentType_object = get_post_type_object( $customContentType );
	$customContentType_extend = $customContentType_object->archive_widget;
	if( $customContentType_extend == false )
		$standardLayout = $customContentType_object->archive_layout;
}


// Establish some variables to determine the layout of a custom taxonomy
if( is_tax() && ! is_post_type_archive() ) {
	$customTaxonomy = get_query_var( 'taxonomy' );
	$customTaxonomy_object = get_taxonomy( $customTaxonomy );
	$customTaxonomy_extend = $customTaxonomy_object->wip_taxonomy_options;
	if( $customTaxonomy_extend == true ) {
		if( is_tax( $customTaxonomy ) )
			$customTaxonomy_termID = get_queried_object()->term_id;
	} else {
		$standardLayout = $customTaxonomy_object->archive_layout;
	}
}


// Layout variables
$fullWidth = "	#secondary, #additional, #fourth { display:none; }";
$sideBar = "	#main { clear:none; width:65.5%; }\n	#secondary { clear:none; margin-left:6%; width:26%; }\n	#additional, #fourth { display:none; }\n";
$twoColumns = "	#main { clear:none; width:47%; }\n	#secondary { clear:none; margin-left:4%; width:47%; }\n	#additional, #fourth { display:none; }\n";
$threeColumns = "	#main { clear:none; width:30.5%; }\n	#secondary { clear:none; margin:0 0 0 3%; width:30.5%; }\n	#additional { clear:none; margin:0 0 0 3%; width:30.5%; }\n	#fourth { display:none; }\n";
$fourColumns = "	#main { clear:none; width:22%; }\n	#secondary { clear:none; margin:0 0 0 3%; width:22%; }\n	#additional { clear:none; margin:0 0 0 3%; width:22%; }\n	#fourth { clear:none; margin:0 0 0 3%; width:22%; }\n";


// Some standard stuff
echo "<style type=\"text/css\" media=\"screen\">\n";
echo "h1, h1 a, h3, h3 a { color:#" . $wipOptions['headersOdd'] . "; }\n";
echo "h2, h2 a, h4, h4 a { color:#" . $wipOptions['headersEven'] . "; }\n";
echo "#cougmeter_container { display:block; height:207px; margin:0 0 15px 0; overflow:hidden; position:relative; width:193px; }
#cougmeter_progress { background-color:#981E32; bottom:12px; float:left; position:absolute; width:100%; }
#cougmeter_container img { display:block; float:left; position:absolute; }
#cougmeter_container #arrow { background:transparent url('http://alumni.cahnrs.wsu.edu/files/2011/10/meter-arrow.png') no-repeat; bottom:2px; color:#981E32; font-size:10px; font-weight:bold; height:16px; left:5px; padding:3px 0 0 12px; position:absolute; width:101px; }";
echo "\n@media only screen and (min-width:769px) {\n";


// A big ridiculous conditional to determine which layout styles to apply
if( is_singular() ) { 
	if(
			( is_singular( array ( 'post', 'page' ) ) && ( isset( $wipLayoutMeta['layout'] ) && $wipLayoutMeta['layout'] != '' ) ) ||
			( ( is_singular( $customContentType ) && $customContentType_extend == true ) && ( isset( $wipLayoutMeta['layout'] ) && $wipLayoutMeta['layout'] != '' ) )
	) {		
		if( $wipLayoutMeta['layout'] == '0' ) echo $fullWidth;
		if( $wipLayoutMeta['layout'] == '1' ) echo $sideBar;
		if( $wipLayoutMeta['layout'] == '2' ) echo $twoColumns;
		if( $wipLayoutMeta['layout'] == '4' ) echo $threeColumns;
		if( $wipLayoutMeta['layout'] == '5' ) echo $fourColumns;
	} else if( is_singular( $customContentType ) && $customContentType_extend == false ) { // A custom content type with a standard layout
		if( $standardLayout == 'full' ) echo $fullWidth;
		if( $standardLayout == 'sidebar' ) echo $sideBar;
		if( $standardLayout == 'two' ) echo $twoColumns;
		if( $standardLayout == 'three' ) echo $threeColumns;
		if( $standardLayout == 'four' )	echo $fourColumns;
	} else {
		echo $fullWidth;
	}
} else if( is_home() ) {
	if( is_active_sidebar( 'index' ) )
		echo $sideBar;
	else
		echo $fullWidth;
} else if( is_archive() ) {
	if(
			( ( is_archive() && is_active_sidebar( 'archive' ) ) && ! is_category() && ! is_tag() ) ||
			( is_category() && ( ! empty( $catDesc ) || is_active_sidebar( 'category_archive' ) || is_active_sidebar( 'category_' . $categoryID . '_widget' ) ) ) ||
			( is_tag() && ( ! empty( $tagDesc ) || is_active_sidebar( 'tag_archive' ) ) ) ||
			( is_author() )
		)
	{
		echo $sideBar;
	} else if( is_post_type_archive() ) {
		if( ( is_post_type_archive( $customContentType ) && $customContentType_extend == true ) && is_active_sidebar( $customContentType . '_archive' ) ) {
			echo $sideBar;
		}
		else if( is_post_type_archive( $customContentType ) && $customContentType_extend == false ) {
			if( $standardLayout == 'full' ) echo $fullWidth;
			if( $standardLayout == 'sidebar' ) echo $sideBar;
			if( $standardLayout == 'two' ) echo $twoColumns;
			if( $standardLayout == 'three' ) echo $threeColumns;
			if( $standardLayout == 'four' ) echo $fourColumns;
		} else {
			echo $fullWidth;
		}
	} else if( is_tax() ) {
		if( is_tax( $customTaxonomy ) && ( $customTaxonomy_extend == true ) && ( term_description() || is_active_sidebar( $customTaxonomy . '_archive' ) || is_active_sidebar( $customTaxonomy . '_' . $customTaxonomy_termID . '_archive' ) ) ) {
			echo $sideBar;
		} else if( is_tax( $customTaxonomy ) && ( $customTaxonomy_extend == false ) ) {
			if( $standardLayout == 'full' ) echo $fullWidth;
			if( $standardLayout == 'sidebar' ) echo $sideBar;
			if( $standardLayout == 'two' ) echo $twoColumns;
			if( $standardLayout == 'three' ) echo $threeColumns;
			if( $standardLayout == 'four' ) echo $fourColumns;
		} else {
			echo $fullWidth;
		}
	} else {
		echo $fullWidth;
	}
} else {
	echo $fullWidth;
}


// Slideshow or header image
if(
		( isset( $wipLayoutMeta['page_type'] ) && $wipLayoutMeta['page_type'] == 'dynamic' && isset( $wipLayoutMeta['slideshow'] ) && $wipLayoutMeta['slideshow'] == 'show' && $wipDynamicMeta['wipHomeArray'] != '' ) ||
		( is_front_page() && ( isset( $wipOptions['indexSlide'] ) && $wipOptions['indexSlide'] ) && isset( $wipOptions['slidesCategory'] ) && isset( $wipOptions['slideCount'] ) && !is_paged() ) ||
		( get_header_image() )
) {
	?>
		#wsusearch_input { border:none; }
		#network h1 a { color:#fff; }
		#wsunav a { color:#fff; }
		#breadcrumbs { color:#fff; }
		#breadcrumbs a { color:#e7e9ea; }
		#breadcrumbs ul li a { color:#fff; }
		h1#sitename, #toolbar { background:url("<?php bloginfo('template_url'); ?>/images/line.gif") 0 29px repeat-x; }
		#toolbar ul li { color:#fff; }
		#toolbar ul li a { color:#fff; }
		h1#sitename a { color:#fff; }
		#sitenav, #content { margin-top:10px; }
    #network h1, h1#sitename { text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.75); }
		#wsunav, #breadcrumbs, #toolbar { text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.75); }
<?php
}


// More slideshow
if(
		( isset( $wipLayoutMeta['page_type'] ) && $wipLayoutMeta['page_type'] == 'dynamic' && isset( $wipLayoutMeta['slideshow'] ) && $wipLayoutMeta['slideshow'] == 'show' && $wipDynamicMeta['wipHomeArray'] != '' ) ||
		( is_front_page() && ( isset( $wipOptions['indexSlide'] ) && $wipOptions['indexSlide'] ) && isset( $wipOptions['slidesCategory'] ) && isset( $wipOptions['slideCount'] ) && !is_paged() )
) {
	?>
		#home { clear:none; display:block; float:left; height:230px; margin:0; padding:20px 0 0 2%; width:98%; }
		ul#featurethumbnails { float:right; height:216px; list-style:none; overflow:hidden; padding:0; margin:0; }
		ul#featurethumbnails li { background:#fff; height:25px; margin-bottom:9px; padding:1px; width:50px; }
		#featurethumbnails a { background-position:center center; background-repeat:no-repeat; background-size:cover; display:block; height:25px; width:50px; }
		#featureinfo { float:right; height:210px; margin-right:2%; }
		#featureinfo td { text-align:right; vertical-align:bottom; }
		/*#screen { background:rgba(0,0,0,.40); padding:10px; }*/
		#featuretext h1 { display:block; color:#fff; font-family:"Times New Roman",Times,serif; font-size:24px; font-style:italic; font-weight:bold; line-height:24px; margin:0; padding:0 2px 5px; }
		#featuretext h4 { display:block; clear:both; color:#fff; font-family:"Lucida Grande","Lucida Sans Unicode",Arial,san-serif; font-size:14px; font-weight:bold; margin:0; padding:0 1px 5px; }
		#featurelink{ display:block; clear:both; margin:0; padding:0; }
<?php
}

// More header image
if( get_header_image() ) {
	?>
		#siteimage { background:url(<?php header_image(); ?>) center center no-repeat; background-size:cover; display:block; width:100%; height:450px; position:absolute; z-index:-2;
    	filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php header_image(); ?>', sizingMethod='scale');
      -ms-filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php header_image(); ?>', sizingMethod='scale');
    }
		#siteimage_overlay {
    	background: -moz-linear-gradient(top, rgba(255,255,255,0) 0%, rgba(238,239,241,1) 100%); /* FF3.6+ */
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(255,255,255,0)), color-stop(100%,rgba(238,239,241,1))); /* Chrome, Safari4+ */
			background: -webkit-linear-gradient(top, rgba(255,255,255,0) 0%,rgba(238,239,241,1) 100%); /* Chrome10+, Safari5.1+ */
			background: -o-linear-gradient(top, rgba(255,255,255,0) 0%,rgba(238,239,241,1) 100%); /* Opera 11.10+ */
			background: -ms-linear-gradient(top, rgba(255,255,255,0) 0%,rgba(238,239,241,1) 100%); /* IE10+ */
			background: linear-gradient(to bottom, rgba(255,255,255,0) 0%,rgba(238,239,241,1) 100%); /* W3C */
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00ffffff', endColorstr='#eeeff1',GradientType=0 ); /* IE6-9 */
			height:450px;
			width:100%;
		}
		#sitenav { background:#eeeff1; }
		#sitenav { background:rgba(238,239,241,.70); }
		#content { background:rgba(255,255,255,.90); }
    #content {
   		background: -moz-linear-gradient(top, rgba(255,255,255,.90) 0%, rgba(255,255,255,1) 275px, rgba(255,255,255,1) 100%); /* FF3.6+ */
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(255,255,255,.90)), color-stop(275px,rgba(255,255,255,1)), color-stop(100%,rgba(255,255,255,1))); /* Chrome,Safari4+ */
			background: -webkit-linear-gradient(top, rgba(255,255,255,.90) 0%,rgba(255,255,255,1) 275px,rgba(255,255,255,1) 100%); /* Chrome10+,Safari5.1+ */
			background: -o-linear-gradient(top, rgba(255,255,255,.90) 0%,rgba(255,255,255,1) 275px,rgba(255,255,255,1) 100%); /* Opera 11.10+ */
			background: -ms-linear-gradient(top, rgba(255,255,255,.90) 0%,rgba(255,255,255,1) 275px,rgba(255,255,255,1) 100%); /* IE10+ */
			background: linear-gradient(to bottom, rgba(255,255,255,.90) 0%,rgba(255,255,255,1) 275px,rgba(255,255,255,1) 100%); /* W3C */
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#e6ffffff', endColorstr='#ffffff',GradientType=0 ); /* IE6-9 */
    }
	<?php
}

echo "\n}\n</style>\n";
?>