<?php
/*
Custom header stuff
*/

function twentytwelve_custom_header_setup() {

	$args = array(
		'height'								 => 500,
		'width'									=> 1000,
		'max-width'							=> 1500,
		'header-text'						 => false,
		'flex-height'						=> true,
		'flex-width'						 => true,
		'random-default'				 => false,
		'admin-head-callback'		=> 'twentytwelve_admin_header_style',
		'admin-preview-callback' => 'twentytwelve_admin_header_image',
	);

	add_theme_support( 'custom-header', $args );

}
add_action( 'after_setup_theme', 'twentytwelve_custom_header_setup' );


// Styles the header image preview on the Appearance > Header admin panel.
function twentytwelve_admin_header_style() {

	if( get_header_image() ) {
		?>
			<style type="text/css">
			.appearance_page_custom-header #headimg { background:url(<?php header_image(); ?>) center center no-repeat; background-size:cover; border:none; display:block; font-family:"Lucida Grande", "Lucida Sans Unicode", Arial,san-serif; height:450px; position:relative; width:100%; }
			#siteImage_overlay {
				background:-moz-linear-gradient(top, rgba(238,239,241,0) 0%, rgba(238,239,241,1) 100%); /* FF3.6+ */
				background:-webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(238,239,241,0)), color-stop(100%,rgba(238,239,241,1))); /* Chrome, Safari4+ */
				background:-webkit-linear-gradient(top, rgba(238,239,241,0) 0%,rgba(238,239,241,1) 100%); /* Chrome10+, Safari5.1+ */
				background:-o-linear-gradient(top, rgba(238,239,241,0) 0%,rgba(238,239,241,1) 100%); /* Opera 11.10+ */
				background:-ms-linear-gradient(top, rgba(238,239,241,0) 0%,rgba(238,239,241,1) 100%); /* IE10+ */
				background:linear-gradient(to bottom, rgba(238,239,241,0) 0%,rgba(238,239,241,1) 100%); /* W3C */
				filter:progid:DXImageTransform.Microsoft.gradient( startColorstr='#00eeeff1', endColorstr='#eeeff1',GradientType=0 ); /* IE6-9 */
				display:block; height:450px; position:absolute; width:100%;
			}
			#indexFix { display:block; height:450px; position:absolute; width:100%; z-index:1; }
			#network { height:80px; margin:0 auto; max-width:960px; padding:0; width:100%; }
			#wsuLogo { clear:none; display:block; float:left; margin-left:0; text-align:center; width:72px; }
			#wsuLogo a { display:block; margin:0 auto; width:72px; }
			#wsuLogo a span { background:url("<?php bloginfo('template_url'); ?>/images/wsu-logo-m.png")	left top no-repeat; display:block; height:64px; text-indent:-9999px; width:72px; }
			#wsuNav { background:none; clear:none; color:#5e6a71; display:block; font-size:x-small; font-weight:normal; line-height:2em; list-style-image:none; height:26px; margin:0 0 0 7.625%; padding:5px 0 0; text-align:right; text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.75); width:92.375%; }
			#wsuNav li { display:inline; padding:0 0 0 20px; }
			#wsuNav a { text-decoration:none; color:#fff; }
			#wsuSearch{ display:inline; height:14px; }
			#wsuSearch_input { border:none; color:#5e6a71; font-family:"Lucida Grande", "Lucida Sans Unicode", Arial,san-serif; font-size:x-small; height:14px; margin:0; padding:0 0 0 5px; vertical-align: baseline; width:125px; -webkit-border-radius:0; border-radius:0; }
			input::-webkit-input-placeholder { color:#5e6a71; font-family:"Lucida Grande", "Lucida Sans Unicode", Arial,san-serif; }
			input:-moz-placeholder { color:#5e6a71; font-family:"Lucida Grande", "Lucida Sans Unicode", Arial,san-serif; }
			input:-ms-input-placeholder { color:#5e6a71; font-family:"Lucida Grande", "Lucida Sans Unicode", Arial,san-serif; }
			input[placeholder], [placeholder], *[placeholder] { color:#5e6a71; font-family:"Lucida Grande", "Lucida Sans Unicode", Arial,san-serif; }
			#wsuSearch_submit { background:url("<?php bloginfo('template_url'); ?>/images/search.png") center no-repeat #fff; border:none; height:14px; margin:0; padding:0; text-indent:-999em; width:14px; }
			#cahnrsNav { background:#981e32; clear:both; display:block; float:left; height:30px; margin-left:0; width:100%; }
			#cahnrsNav ul { float:none; height:30px; list-style:none; margin:0; padding:0; text-align:center; }
			#cahnrsNav li { display:inline-block; float:none; height:30px; line-height:30px; margin:0; padding:0 1.25%; text-align:center; }
			#cahnrsNav li:hover { background:#5e6a71; }
			#cahnrsNav a { color:#fff; font-weight:normal; padding:0; text-decoration:none; }
			#network h1 { display:block; font-size:1.5em; font-weight:normal; letter-spacing:-0.06em; margin:0 0 0 12%; padding-top:12px; text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.75); }
			#network h1 a { color:#fff; text-decoration:none; }
			#container { clear:both; margin:auto; max-width:960px; padding-left:0.5434%; padding-right:0.5434%; width:98.913%; }
			#breadcrumbs { color:#fff; display:block; float:left; font-size:.8333em; height:17px; padding-top:8px; text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.75); width:100%; }
			#breadcrumbs ul { display:inline; float:right; line-height:1em; list-style-image:none; margin:0; padding:0; text-align:right; width:30%; }
			#breadcrumbs ul li { display:inline; padding:0px 0px 0px 15px; position:relative; }
			#breadcrumbs ul li a { color:#fff; text-decoration:none; }
			h1#siteName { background:url("<?php bloginfo('template_url'); ?>/images/line.gif") 0 29px repeat-x; clear:both; display:block; float:left; font-size:1.5em; font-weight:bold; line-height:30px; margin:0; padding:0 0 0 2%; text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.75); width:98%; }
			h1#siteName a { color:#fff; text-decoration:none; }
			#siteNav { background:rgba(238,239,241,.70); clear:both; float:left; font-size:0.9167em; line-height:1.2em; margin:10px 0 0; min-height:275px; padding:0; width:20%; }
			#siteNav ul { border-bottom:1px solid #B6BCBF; border-top:none; display:block; list-style:none; margin:0; padding:0; }
			#siteNav ul li { border-bottom:1px solid #fff; border-top:1px solid #B6BCBF; margin:0; padding:0; }
			#siteNav ul li:first-child { border-top:none; }
			#siteNav a { color:#fff; display:block; color:#5e6a71; padding:5px 5% 5px 13%; text-decoration:none; }
			#siteNav a:hover { background:#fff; color:#262a2d; }
			#content {
				background:-moz-linear-gradient(top, rgba(255,255,255,.90) 0%, rgba(255,255,255,1) 100%); /* FF3.6+ */
				background:-webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(255,255,255,.90)), color-stop(100%,rgba(255,255,255,1))); /* Chrome, Safari4+ */
				background:-webkit-linear-gradient(top, rgba(255,255,255,.90) 0%,rgba(255,255,255,1) 100%); /* Chrome10+, Safari5.1+ */
				background:-o-linear-gradient(top, rgba(255,255,255,.90) 0%,rgba(255,255,255,1) 100%); /* Opera 11.10+ */
				background:-ms-linear-gradient(top, rgba(255,255,255,.90) 0%,rgba(255,255,255,1) 100%); /* IE10+ */
				background:linear-gradient(to bottom, rgba(255,255,255,.90) 0%,rgba(255,255,255,1) 100%); /* W3C */
				filter:progid:DXImageTransform.Microsoft.gradient( startColorstr='#e6ffffff', endColorstr='#ffffff',GradientType=0 ); /* IE6-9 */
				clear:none; display:block; float:left; height:235px; margin:10px 0 0; padding:20px 2%; width:76%; }
			#main h1 { font-size:26px; font-weight:normal; letter-spacing:normal; line-height:31px; margin:0px; padding:0 0 6px 0; }
			#main p { color: #2D2D2D; font-size:12px; line-height:19px; margin:0px; padding:0 0 12px; }
			</style>
		<?php
	}

}


// Markup to be displayed for the header image preview on the Appearance > Header admin panel
function twentytwelve_admin_header_image() {
	if( get_header_image() ) {
		?>
      <div id="headimg">
        <div id="siteImage_overlay"></div>
        <div id="indexFix">
          <div id="network">
            <div id="wsuLogo"><a href="http://wsu.edu/" title="Washington State University" onclick="return false;"><span>Washington State University</span></a></div>
            <ul id="wsuNav">
              <li><a href="http://index.wsu.edu/" onclick="return false;">A-Z Index</a></li>
              <li><a href="http://www.about.wsu.edu/statewide/" onclick="return false;">Statewide</a></li>
              <li><a href="https://portal.wsu.edu/" onclick="return false;">zzusis</a></li>
              <li><a href="http://www.wsu.edu/" onclick="return false;">WSU Home</a></li>
              <li>
                <form name="wsu_headersearch" method="get" action="http://search.wsu.edu/Default.aspx" id="wsuSearch" onsubmit="this.submit();return false;">
                  <input name="q" type="text" value="" placeholder="Search WSU Web/People" id="wsuSearch_input" /><input type="submit" value="Search" id="wsuSearch_submit" onclick="return false;" />
                </form>
              </li>
            </ul>
            <h1><a href="http://cahnrs.wsu.edu/" title="College of Agricultural, Human, and Natrual Resource Sciences and WSU Extension" onclick="return false;">College of Agricultural, Human, and Natural Resource Sciences and WSU Extension</a></h1>
          </div>
          <div id="cahnrsNav">
            <ul>
              <li><a href="#" onclick="return false;">Prospective Students</a></li>
              <li><a href="#" onclick="return false;">Current Students</a></li>
              <li><a href="#" onclick="return false;">Graduate Students</a></li>
              <li><a href="#" onclick="return false;">Research &amp; Extension</a></li>
              <li><a href="#" onclick="return false;">Alumni &amp; Friends</a></li>
              <li><a href="#" onclick="return false;">Faculty &amp; Staff</a></li>
            </ul>
          </div>
          <div id="container" class="clearfix">
            <div id="breadcrumbs">
              <ul>
                <li><a href="http://news.cahnrs.wsu.edu/" onclick="return false;">Newsroom</a></li>
                <li><a href="http://cahnrs.wsu.edu/events/" onclick="return false;">Calendar</a></li>
                <li><a href="http://cahnrsdb.wsu.edu/newdirectory/findName.aspx" onclick="return false;">Directory</a></li>
              </ul>
            </div>
            <h1 id="siteName"><a href="<?php bloginfo( 'url' ); ?>" onclick="return false;"><?php bloginfo( 'name' ); ?></a></h1>
            <div id="siteNav">
              <ul>
                <li><a href="#" onclick="return false;">Link</a></li>
                <li><a href="#" onclick="return false;">Link</a></li>
                <li><a href="#" onclick="return false;">Link</a></li>
                <li><a href="#" onclick="return false;">Link</a></li>
                <li><a href="#" onclick="return false;">Link</a></li>
              </ul>
            </div>
            <div id="content">
              <div id="main">
                <h1>Page Title</h1>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eu magna massa, eu venenatis dui. Suspendisse at accumsan mi. Aenean pharetra condimentum sagittis. Cras sapien odio, fringilla in accumsan sit amet, mollis ut ligula. Nam leo massa, semper in adipiscing id, fringilla eget dolor. Duis libero erat, ultrices vel accumsan a, iaculis vitae dui. In hac habitasse platea dictumst. Integer placerat sem ut urna luctus a luctus justo scelerisque.</p>
              </div>
            </div>
        </div>
      </div>
		<?php
	}
}
?>