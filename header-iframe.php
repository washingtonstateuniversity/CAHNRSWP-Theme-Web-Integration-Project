<?php
/*
Displays everything up to <div id="main">
*/

// A couple variables used all the time
$wipOptions = get_option( 'wipOptions' );
$uploadDirectory = wp_upload_dir();

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
		<link rel="shortcut icon" href="<?php bloginfo( 'url' ); ?>/favicon.ico" type="image/x-icon" />
		<?php
			include( get_stylesheet_directory() . "/css/layout.php" );

			wp_head();

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
					echo '<script language="JavaScript" type="text/javascript">var navcurrentpage="' . get_tax_meta( $categoryID, 'navOverride' ) . '";</script>';
				if( ( is_category( $categoryID ) || in_category( $categoryID ) ) && get_tax_meta( $categoryID, 'customCSS' ) )
					echo '<link rel="stylesheet" href="' . get_tax_meta( $categoryID, 'customCSS' ) . '" type="text/css" media="all" />';
			}
		?>
		<script type="text/javascript"> Shadowbox.init(); </script>
        <?php if( isset( $_GET['iframe-view'] ) ) { 
			echo '<style type="text/css">';
			echo 'body {background-position: 0px -40px !important;}';
			echo '</style>';
		}?>
	</head>
	<body<?php if( $wipBodyMeta ) echo ' ' . esc_attr( $wipBodyMeta ); /* Include custom body attribute stuff if there is any*/ ?>>
<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-PC9VFJ"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-PC9VFJ');</script>
<!-- End Google Tag Manager -->