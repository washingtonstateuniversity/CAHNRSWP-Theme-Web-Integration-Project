<?php
/*
Displays everything up to the opening of <div id="content">.
*/

$wipOptions = get_option( 'wipOptions' );

// Post- and page-specific variables
if( is_single() || is_page() ) {
	$wipLayoutMeta = get_post_meta( $post->ID, '_layout', true );
	$wipHeadMeta = get_post_meta( $post->ID, 'head', true );
	$wipBodyMeta = get_post_meta( $post->ID, 'body', true );
	if ( is_page() )
		$wipDynamicMeta = get_post_meta( $post->ID, '_dynamic', true );
} else {
	$wipDynamicMeta = false;
	$wipLayoutMeta = false;
	$wipHeadMeta = false;
	$wipBodyMeta = false;
}

// Taxonomy-specific variables
$catDesc = category_description();
$tagDesc = tag_description();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
		<title><?php
			wp_title( '' );
			if( wp_title( '', false ) )
				echo ' - ';
			bloginfo( 'name' );
			echo ' | ';
			bloginfo( 'description' );
		?></title>
		<?php
			wp_head();

			include( get_template_directory() . "/css/layout.php" );

			// Include custom header stuff if there is any (haven't thoroughly tested this escaping)
			if( $wipHeadMeta ) {
				$allowedHeaderTags = array(
					'link' => array(
						'href' => array(),
						'rel' => array(),
						'type' => array(),
						'media' => array()
					),
					'style' => array(
						'type' => array(),
						'media' => array()
					),
					'script' => array(
						'type' => array(),
						'src' => array()
					)
				);
				echo wp_kses( $wipHeadMeta, $allowedHeaderTags );
			}

			// If navigation override is in place
			$categories = get_the_category();
			foreach( $categories as $category ) {
				$categoryID = $category->cat_ID;
				if( get_tax_meta( $categoryID, 'navOverride' ) )
					echo '<script language="JavaScript" type="text/javascript">var navcurrentpage="' . esc_js( get_tax_meta( $categoryID, 'navOverride' ) ) . '";</script>';
			}

			// If the "Dynamic" and "Slideshow" options are selected and the "Home" field is not empty, echo the slideshow script
			if(
					( isset( $wipLayoutMeta['page_type'] ) && $wipLayoutMeta['page_type'] == 'dynamic' &&
						isset( $wipLayoutMeta['slideshow'] ) && $wipLayoutMeta['slideshow'] == 'show' &&
						$wipDynamicMeta['wipHomeArray'] != ''
					) ||
					( is_front_page() &&
						( isset( $wipOptions['indexSlide'] ) && $wipOptions['indexSlide'] ) &&
						isset( $wipOptions['slidesCategory'] ) &&
						isset( $wipOptions['slideCount'] ) &&
						!is_paged()
					)
			) {
				?>
					<script type="text/javascript">
						jQuery(function($) {	
							$('headerimgs').bgimgSlideshow( {
								photos : [ <?php
															/* @todo a little worried about what gets output via dynamic_slideshow_display */
															$cType = explode( ",", $wipDynamicMeta['wipHomeArray'] );
															include ( get_template_directory() . "/inc/dynamic_slideshow_display.php" );
											?> ]
							});
						});
					</script>
				<?php
			}
		?>
		<script type="text/javascript"> Shadowbox.init(); </script>
	</head>
	<body<?php if( $wipBodyMeta ) echo ' ' . esc_attr( $wipBodyMeta ); // Include custom body attribute stuff if there is any ?>>

		<div id="header">

			<?php
				if( get_header_image() &&
						( !isset( $wipLayoutMeta['slideshow'] ) && $wipOptions['indexSlide'] != '1' )
				)
					echo '<div id="siteimage"><div id="siteimage_overlay"></div></div>';
			?>

			<div id="network">

				<div id="wsulogo">
					<a href="http://wsu.edu/" title="Washington State University"><span>Washington State University</span></a>
				</div>

				<ul id="wsunav">
					<li><a href="http://index.wsu.edu/">A-Z Index</a></li>
					<li><a href="http://www.about.wsu.edu/statewide/">Statewide</a></li>
					<li><a href="https://portal.wsu.edu/">zzusis</a></li>
					<li><a href="http://www.wsu.edu/">WSU Home</a></li>
					<li>
						<form name="wsusearch" method="get" action="http://search.wsu.edu/Default.aspx" id="wsusearch" onsubmit="this.submit();return false;">
							<input name="cx" value="013644890599324097824:kbqgwamjoxq" type="hidden" />
							<input name="cof" value="FORID:11" type="hidden" />
							<input name="sa" value="Search" type="hidden" />
							<input name="fp" value="true" type="hidden" />
							<input id="wsusearch_input" name="q" type="text" value="Search WSU Web/People" onclick="erasetextboxwsu();" onblur="checktextboxwsu();" /><input type="submit" value="Search" id="wsusearch_submit" />
						</form>
					</li>
				</ul>

				<h2><a href="http://cahnrs.wsu.edu/" title="College of Agricultural, Human, and Natrual Resource Sciences and WSU Extension">CAHNRS and WSU Extension</a></h2>
				<h1><a href="http://cahnrs.wsu.edu/" title="College of Agricultural, Human, and Natrual Resource Sciences and WSU Extension">College of Agricultural, Human, and Natural Resource Sciences and WSU Extension</a></h1>

			</div><!-- #network -->

			<div id="cahnrsnav">
				<?php
					// If this is a multisite, take the horizontal menu from the top site
					if( is_multisite() ) {
						switch_to_blog(1);
						wp_nav_menu( array( 'theme_location' => 'horizontal', 'fallback_cb' => 'horizontalNavFallback' ) );
						restore_current_blog();
					} else {
						wp_nav_menu( array( 'theme_location' => 'horizontal', 'fallback_cb' => 'horizontalNavFallback' ) );
					}
				?>
			</div><!-- #cahnrsnav -->

		</div><!-- #header -->

		<div id="wrapper" class="clearfix">

			<div id="breadcrumbs">
				<?php
					// If the "Show breadcrumb" option is selected
					if( $wipOptions['tbBreadcrumb'] ) {
						?>
							<p>
								<?php // If this is the home page, display just the site name - otherwise make it a link.
									if( is_front_page () ) {
										bloginfo( 'name' );
									} else {
										?>
										<a href="<?php bloginfo( 'url' ); ?>"><?php bloginfo( 'name' ); ?></a> &raquo;
										<?php 
									}
									// The rest of the breadcrumb stuff
									if( function_exists( 'breadcrumbs' ) )
										breadcrumbs( array( 'singular_post_taxonomy' => 'category' ) );
								?>
							</p>
						<?php
					}
				?>
				<ul>
					<li><a href="http://news.cahnrs.wsu.edu/">Newsroom</a></li>
					<li><a href="http://cahnrs.wsu.edu/events/">Calendar</a></li>
					<li><a href="http://cahnrsdb.wsu.edu/newdirectory/findName.aspx">Directory</a></li>
				</ul>
			</div><!-- #breadcrumbs -->

			<h1 id="sitename"><a href="<?php bloginfo( 'url' ); ?>"><?php bloginfo( 'name' ); ?></a></h1>

			<div id="mobilenav">
				<form name="menu">
					<select name="compoundnav" id="compoundnav" onchange="location=document.menu.compoundnav.options[document.menu.compoundnav.selectedIndex].value;" value="go">
						<option value="network" selected="selected">Navigation</option>
						<?php
							$locations = get_nav_menu_locations();
							if( isset( $locations['mobile'] ) && $locations['mobile'] != '' )
								$menu = wp_get_nav_menu_object( $locations['mobile'] );
							else
								$menu = wp_get_nav_menu_object( $locations['primary'] );

							$menuItem = wp_get_nav_menu_items($menu->term_id);

							echo '<optgroup label="' . get_bloginfo( 'name' ) . '">';

							foreach( (array) $menuItem as $key => $menuItem_value ) {
								$title = $menuItem_value->title;
								$url = $menuItem_value->url;
								echo '<option value="' . esc_url( $url ) . '">' . esc_html( $title ) . '</option>';
							}

							echo '</optgroup>';
						?>
						<optgroup label="CAHNRS and Extension">
							<option value="http://academic.cahnrs.wsu.edu/prospective/">Prospective Students</option>
							<option value="http://academic.cahnrs.wsu.edu/current/">Current Students</option>
							<option value="http://academic.cahnrs.wsu.edu/graduate/">Graduate Students</option>
							<option value="http://ext.cahnrs.wsu.edu/">Research &amp; Extension</option>
							<option value="http://alumni.cahnrs.wsu.edu/">Alumni &amp; Friends</option>
							<option value="http://fs.cahnrs.wsu.edu/">Faculty &amp; Staff</option>
						</optgroup>
						<optgroup label="Washington State University">
							<option value="http://index.wsu.edu/">A-Z Index</option>
							<option value="http://www.about.wsu.edu/statewide/">Statewide</option>
							<option value="https://portal.wsu.edu/">zzussis</option>
							<option value="http://www.wsu.edu/">WSU Home</option>
							<option value="https://www.applyweb.com/public/inquiry?wsuuinq">Request Info</option>
							<option value="http://admission.wsu.edu/visits/index.html">Visit</option>
							<option value="http://admission.wsu.edu/applications/index.html">Apply</option>
							<option value="http://cahnrsalumni.wsu.edu/give/">I Want to Give</option>
						</optgroup>
					</select>
				</form>
				<div id="compoundsearch_toggle">
					<a href="#compoundsearch">Search</a>
				</div>
		<div id="sitenav_toggle">
					<a href="#sitenav">Menu</a>
				</div>
			</div><!-- #mobilenav -->

			<div id="compoundsearch">
				<form action="<?php bloginfo( 'url' ); ?>/" class="msearch" method="get" name="sitesearchm" onsubmit="this.submit();return false;">
					<input class="msearch_input" name="s" type="text" value="Search" onclick="erasetextboxlocalm();" onblur="checktextboxlocalm();" /><input class="msearch_submit" type="submit" value="Search" />
				</form>
				<form action="http://search.wsu.edu/Default.aspx" class="msearch" method="get" name="wsusearchm" onsubmit="this.submit();return false;">
					<input name="cx" value="013644890599324097824:kbqgwamjoxq" type="hidden" />
					<input name="cof" value="FORID:11" type="hidden" />
					<input name="sa" value="Search" type="hidden" />
					<input name="fp" value="true" type="hidden" />
					<input class="msearch_input" name="q" type="text" value="Search WSU Web/People" onclick="erasetextboxwsu();" onblur="checktextboxwsu();" /><input class="msearch_submit" type="submit" value="Search" />
				</form>
			</div><!-- #compoundsearch -->

			<div id="toolbar">
				<ul>
					<?php
						// If the "Show AddThis Share link" option is selected
						if( $wipOptions['tbShareLink'] )
							echo '<li><a class="addthis_button">Share</a></li>';

						// If the "Show Print link" option is selected
						if( $wipOptions['tbPrintLink'] )
							echo '<li><a href="javascript:popPrintPage();">Print</a></li>';

						if( $wipOptions['tbSearch'] ) {
							?><li>
								<form name="sitesearch" method="get" id="sitesearch" action="<?php bloginfo( 'url' ); ?>/" onsubmit="this.submit();return false;">
									<input id="sitesearch_input" type="text" name="s" value="Search" onclick="erasetextboxlocal();" onblur="checktextboxlocal();" /><input type="submit" value="Search" id="sitesearch_submit" />
								</form>
							</li><?php
						}
					?>
				</ul>
			</div><!-- #toolbar -->

			<?php
				// Slideshow div if appropriate
				if(
					( isset( $wipLayoutMeta['page_type'] ) && $wipLayoutMeta['page_type'] == 'dynamic' &&
						isset( $wipLayoutMeta['slideshow'] ) && $wipLayoutMeta['slideshow'] == 'show' &&
						$wipDynamicMeta['wipHomeArray'] != ''
					) ||
					( is_front_page() &&
						( isset( $wipOptions['indexSlide'] ) && $wipOptions['indexSlide'] ) &&
						isset( $wipOptions['slidesCategory'] ) &&
						isset( $wipOptions['slideCount'] ) &&
						!is_paged()
					)
				)
					echo '<div id="home"></div>';
			?>

			<div id="sitenav">
				<?php
					// The featured navigation menu
					if( isset( $locations['featured'] ) && $locations['featured'] != '' ) {
						echo '<div id="featured">';
						wp_nav_menu( array( 'theme_location'=>'featured', 'fallback_cb'=>false ) );
						echo '</div>';
					}

					// The Navigation menu
					wp_nav_menu( array( 'theme_location'=>'primary', 'fallback_cb'=>false ) );

					// The "Navigation Column" widget, if active
					if( is_active_sidebar( 'navigation' ) ) {
						echo '<div id="widgetcontainer">';
						dynamic_sidebar( 'navigation' );
						echo '</div>';
					}
				?>
			</div><!-- #sitenav -->

			<div id="content">