<?php
/*
Layout options for page.php and single.php

The option selected here determines the last digit of the WSU CSS Design key

Uses WPAlchemy class - visit http://www.farinspace.com/wpalchemy-metabox/ for documentation
*/

global $wipCCT_dynamic;
?>
<p class="description">For multiple column layouts, separate content with the More tag ( <img src="<?php bloginfo( 'template_url' ); ?>/images/more-tag.png" alt="Insert More Tag (Alt + Shift + T)" height="20" width="20" style="vertical-align:bottom;" /> ).</p>

<?php $mb->the_field('layout'); ?>

<p style="float:left;width:20%;">
	<input type="radio" name="<?php $mb->the_name(); ?>" id="wip-layout-0" value="0"<?php $mb->the_radio_state('0');
	global $post;
	$postID = $post->ID;
	if( ( get_post_status( $postID ) == 'auto-draft' ) || !isset($layoutMeta['layout']) ) // Full width checked by default
		echo ' checked="checked"';
	?>/> <label for="<?php echo esc_attr( $wipLayoutOption_value ); ?>">Full Width</label><br />
	<img src="<?php bloginfo( 'template_directory' ); ?>/images/0.jpg" alt="Full Width" height="75" width="100" />
</p>

<p style="float:left;width:20%;">
	<label for="wip-layout-1"><input type="radio" name="<?php $mb->the_name(); ?>" id="wip-layout-1" value="1"<?php $mb->the_radio_state('1'); ?>/> Right Sidebar<br />
	<img src="<?php bloginfo( 'template_directory' ); ?>/images/1.jpg" alt="Right Sidebar" height="75" width="100" /></label>
</p>

<p style="float:left;width:20%;">
	<label for="wip-layout-2"><input type="radio" name="<?php $mb->the_name(); ?>" id="wip-layout-2" value="2"<?php $mb->the_radio_state('2'); ?>/> Two Equal<br />
	<img src="<?php bloginfo( 'template_directory' ); ?>/images/2.jpg" alt="Two Equal" height="75" width="100" /></label>
</p>
<?php /*
<p style="float:left;width:20%;">
	<label for="wip-layout-3"><input type="radio" name="<?php $mb->the_name(); ?>" id="wip-layout-3" value="3"<?php $mb->the_radio_state('3'); ?>/> Left Sidebar<br />
	<img src="<?php bloginfo( 'stylesheet_directory' ); ?>/images/3.jpg" alt="Left Sidebar" height="75" width="100" /></label>
</p>
*/ ?>
<p style="float:left;width:20%;">
	<label for="wip-layout-4"><input type="radio" name="<?php $mb->the_name(); ?>" id="wip-layout-4" value="4"<?php $mb->the_radio_state('4'); ?>/> Three Equal<br />
	<img src="<?php bloginfo( 'template_directory' ); ?>/images/4.jpg" alt="Three Equal" height="75" width="100" /></label>
</p>
<p style="float:left;width:20%;">
	<label for="wip-layout-5"><input type="radio" name="<?php $mb->the_name(); ?>" id="wip-layout-5" value="5"<?php $mb->the_radio_state('5'); ?>/> Four Equal<br />
	<img src="<?php bloginfo( 'template_directory' ); ?>/images/5.jpg" alt="Four Equal" height="75" width="100" /></label>
</p>

<?php // Check to see if this is a Page or a Post.
$post_id = isset( $_GET['post']) ? $_GET['post'] : NULL ;
$post_type = isset( $_GET['post_type']) ? $_GET['post_type'] : NULL ;
$post_type = $post_id ? get_post_type($post_id) : $post_type ;

$includeDynamicOption = array( 'page' );
/*
$wipCCT = get_post_types( $args = array( 'public' => true, '_builtin' => false ) ); 
if( $wipCCT )
{
	foreach( $wipCCT as $wipCCT_value )
	{
		$customContentType = get_post_type_object( $wipCCT_value );
		if( $customContentType->wip_dynamic_options == true )
			array_push( $includeDynamicOption, $wipCCT_value );
	}
}
*/

// Show the following options only if this is a Page (not a Post)
if( in_array( $post_type, $includeDynamicOption ) ) {
	// Dynamic page
	$mb->the_field('page_type');
	?>
	<p style="float:left;width:25%;">
		<label for="wipDynamic"><input type="checkbox" name="<?php $mb->the_name(); ?>" id="wipDynamic" value="dynamic"<?php $mb->the_checkbox_state('dynamic'); ?>/> Dynamic</label>
	</p>

	<?php
  // Give the user the option to hide the page title.
	$mb->the_field('page_title');
	?>
	<p style="float:left;width:25%;"<?php if( wp_get_theme()->Name == 'Campaign' ) echo ' id="wiphidden"';?>>
		<label for="wipPageTitle"><input type="checkbox" name="<?php $mb->the_name(); ?>" id="wipPageTitle" value="hide"<?php $mb->the_checkbox_state('hide'); ?>/> Hide page title</label>
	</p>

	<?php 
	// A slideshow
	$mb->the_field('slideshow');
	?>
	<p style="float:left;width:25%;display:none;" id="slide-option">
		<label for="slideshow"><input type="checkbox" name="<?php $mb->the_name(); ?>" id="slideshow" value="show"<?php $mb->the_checkbox_state('show'); ?>/> Slideshow</label>
	</p>

	<?php 
	// A slideshow
	$mb->the_field('slideshow_order');
	?>
	<p style="float:left;width:25%;display:none;" id="slide-order<?php if( wp_get_theme()->Name == 'WIP' ) echo ' wiphidden';?>">
		<label for="slideshowOrder"><input type="checkbox" name="<?php $mb->the_name(); ?>" id="slideshowOrder" value="random"<?php $mb->the_checkbox_state('random'); ?>/> Randomize slideshow order</label>
	</p>

<?php
}
?>
<br class="clear">