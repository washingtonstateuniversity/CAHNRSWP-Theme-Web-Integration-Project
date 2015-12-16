<?php
/*
Allow admins to define the look and feel of their site
*/

function wipRegisterSettings() {
	register_setting( 'wipThemeOptions', 'wipOptions', 'wipValidateOptions' );
}
add_action( 'admin_init', 'wipRegisterSettings' );


function wipThemeOptions() {
	add_theme_page( 'Theme Options', 'Theme Options', 'edit_theme_options', 'theme_options', 'wipThemeOptions_page' );
}
add_action( 'admin_menu', 'wipThemeOptions' );


// The content of the options page
function wipThemeOptions_page() {

	// Check whether the form has just been submitted
	if( ! isset( $_REQUEST['settings-updated'] ) )
		$_REQUEST['settings-updated'] = false;

	$settings = get_option( 'wipOptions' );

	?>
	<div class="wrap">
		<div class='icon32' id='icon-themes'><br></div>
		<h2>Theme Options</h2>
		<?php
			if( $_REQUEST['settings-updated'] )
				echo '<div class="updated fade"><p><strong>Options saved</strong></p></div>';
		?>
		<form method="post" action="options.php">
		<?php
			settings_fields( 'wipThemeOptions' );
		?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row">AddThis ID</th>
				<td>Register at <a href="http://www.addthis.com/" target="_blank">http://www.addthis.com/</a> if you want to be able to track how visitors are using your site's share tools.<br />
					<input id="addThis" name="wipOptions[addThis]" type="text" value="<?php	esc_attr_e($settings['addThis']); ?>"	size="11" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Google analytics ID</th>
				<td>This site has built-in stats, but you can insert your ID here if you also want to track site use with Google Analytics.<br />
					<input id="googleAnalytics" name="wipOptions[googleAnalytics]" type="text" value="<?php	esc_attr_e($settings['googleAnalytics']); ?>" size="11" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Toolbar</th>
				<td>
					<label for="tbBreadcrumb"><input type="checkbox" id="tbBreadcrumb" name="wipOptions[tbBreadcrumb]" value="1" <?php checked( true, $settings['tbBreadcrumb'] ); ?> /> Show breadcrumb navigation</label><br />
					<label for="tbPrintLink"><input type="checkbox" id="tbPrintLink" name="wipOptions[tbPrintLink]" value="1" <?php checked( true, $settings['tbPrintLink'] ); ?> /> Show Print link</label><br />
					<label for="tbShareLink"><input type="checkbox" id="tbShareLink" name="wipOptions[tbShareLink]" value="1" <?php checked( true, $settings['tbShareLink'] ); ?> /> Show AddThis Share link</label><br />
					<label for="tbSearch"><input type="checkbox" id="tbSearch" name="wipOptions[tbSearch]" value="1" <?php checked( true, $settings['tbSearch'] ); ?> /> Show site search form</label>
				</td>
			</tr>
			<?php
      	// If the "Reading Settings" > "Front page displays" is set to "Your latest posts", show this option
				if( get_option( 'show_on_front' ) == 'posts' ) {
					global $category, $slideCount;
					$category = get_categories();
					$slideCount = array( '1', '2', '3', '4', '5', '6', '7', '8', '9', '10' );
					?>
						<tr valign="top">
							<th scope="row">Homepage Slideshow</th>
							<td>The slideshow cycles through the featured images of posts from the selected category.<br />
								<label for="indexSlide"><input type="checkbox" id="indexSlide" name="wipOptions[indexSlide]" value="1" <?php checked( true, $settings['indexSlide'] ); ?> /> Add Slideshow</label><br />
								Category <select id="slidesCategory" name="wipOptions[slidesCategory]">
									<option value="">(Select)</option>
									<?php 
										foreach( $category as $category_value ) {
											?>
												<option value="<?php echo $category_value->term_id; ?>"<?php selected( $settings['slidesCategory'], $category_value->term_id ); ?>><?php echo $category_value->cat_name; ?></option>
											<?php
										}
									?>
								</select><br />
								Number of Slides <select id="slideCount" name="wipOptions[slideCount]">
									<option value="">(Select)</option>
									<?php
										foreach( $slideCount as $slideCount_value ) {
											?>
												<option value="<?php echo $slideCount_value; ?>"<?php selected( $settings['slideCount'], $slideCount_value ); ?>><?php echo $slideCount_value; ?></option>
											<?php
										}
									?>
								</select>
							</td>
						</tr>
					<?php
				}
			?>
			<tr valign="top">
				<th scope="row">Posts, Pages, and Archives settings</th>
				<td>
					<div style="float:left;margin-right:5%;width:20%;">
						Show publication date on<br />
						<label for="postPubDate"><input type="checkbox" id="postPubDate" name="wipOptions[postPubDate]" value="1" <?php checked( true, $settings['postPubDate'] ); ?> /> Posts</label><br />
						<label for="pagePubDate"><input type="checkbox" id="pagePubDate" name="wipOptions[pagePubDate]" value="1" <?php checked( true, $settings['pagePubDate'] ); ?> /> Pages</label><br />
						<label for="loopPubDate"><input type="checkbox" id="loopPubDate" name="wipOptions[loopPubDate]" value="1" <?php checked( true, $settings['loopPubDate'] ); ?> /> Archives</label>
					</div>
					<div style="float:left;margin-right:5%;width:20%;">
						Show author on<br />
						<label for="postAuthor"><input type="checkbox" id="postAuthor" name="wipOptions[postAuthor]" value="1" <?php checked( true, $settings['postAuthor'] ); ?> /> Posts</label><br />
						<label for="pageAuthor"><input type="checkbox" id="pageAuthor" name="wipOptions[pageAuthor]" value="1" <?php checked( true, $settings['pageAuthor'] ); ?> /> Pages</label><br />
						<label for="loopAuthor"><input type="checkbox" id="loopAuthor" name="wipOptions[loopAuthor]" value="1" <?php checked( true, $settings['loopAuthor'] ); ?> /> Archives</label>
					</div>
					<div style="float:left;margin-right:5%;width:20%;">
						Enable AddThis share tools on<br />
						<label for="postShare"><input type="checkbox" id="postShare" name="wipOptions[postShare]" value="1" <?php checked( true, $settings['postShare'] ); ?> /> Posts</label><br />
						<label for="pageShare"><input type="checkbox" id="pageShare" name="wipOptions[pageShare]" value="1" <?php checked( true, $settings['pageShare'] ); ?> /> Pages</label>
					</div>
					<div style="float:left;margin-right:5%;width:20%;">
						Show categories and tags on<br />
						<label for="postTaxonomy"><input type="checkbox" id="postTaxonomy" name="wipOptions[postTaxonomy]" value="1" <?php checked( true, $settings['postTaxonomy'] ); ?> /> Posts</label><br />
						<label for="loopTaxonomy"><input type="checkbox" id="loopTaxonomy" name="wipOptions[loopTaxonomy]" value="1" <?php checked( true, $settings['loopTaxonomy'] ); ?> /> Archives</label>
					</div>
					<div style="clear:both;padding-top:12px;">
						Show post comment count on<br />
						<label for="loopComments"><input type="checkbox" id="loopComments" name="wipOptions[loopComments]" value="1" <?php checked( true, $settings['loopComments'] ); ?> /> Archives</label>
					</div>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Heading Colors</th>
				<td>
                  
          <table class="headercolors">
            <tr>
              <td colspan="4" style="padding:0 0 3px;">h1 and h3</td>
            </tr>
            <tr>
              <td><input type="radio" id="9o" name="wipOptions[headersOdd]" style="display:none;" value="981e32" <?php checked( '981e32', $settings['headersOdd'] ); ?> /><label for="9o" class="crimson"></label></td>
              <td><input type="radio" id="5o" name="wipOptions[headersOdd]" style="display:none;" value="5e6a71" <?php checked( '5e6a71', $settings['headersOdd'] ); ?> /><label for="5o" class="grey"></label></td>
              <td><input type="radio" id="2o" name="wipOptions[headersOdd]" style="display:none;" value="262a2d" <?php checked( '262a2d', $settings['headersOdd'] ); ?> /><label for="2o" class="black"></label></td>
              <td><input type="radio" id="0o" name="wipOptions[headersOdd]" style="display:none;" value="003c69" <?php checked( '003c69', $settings['headersOdd'] ); ?> /><label for="0o" class="blue"></label></td>
            </tr>
            <tr>
              <td><input type="radio" id="4o" name="wipOptions[headersOdd]" style="display:none;" value="452325" <?php checked( '452325', $settings['headersOdd'] ); ?> /><label for="4o" class="brown"></label></td>
              <td><input type="radio" id="bo" name="wipOptions[headersOdd]" style="display:none;" value="b6bf00" <?php checked( 'b6bf00', $settings['headersOdd'] ); ?> /><label for="bo" class="green"></label></td>
              <td><input type="radio" id="eo" name="wipOptions[headersOdd]" style="display:none;" value="ec7a08" <?php checked( 'ec7a08', $settings['headersOdd'] ); ?> /><label for="eo" class="orange"></label></td>
              <td><input type="radio" id="3o" name="wipOptions[headersOdd]" style="display:none;" value="3cb6ce" <?php checked( '3cb6ce', $settings['headersOdd'] ); ?> /><label for="3o" class="aqua"></label></td>
            </tr>
          </table>
                                                          
          <table style="float:left;" class="headercolors">
						<tr>
              <td colspan="4" style="padding:0 0 3px;">h2 and h4</td>
            </tr>
            <tr>
              <td><input type="radio" id="9e" name="wipOptions[headersEven]" style="display:none;" value="981e32" <?php checked( '981e32', $settings['headersEven'] ); ?> /><label for="9e" class="crimson"></label></td>
              <td><input type="radio" id="5e" name="wipOptions[headersEven]" style="display:none;" value="5e6a71" <?php checked( '5e6a71', $settings['headersEven'] ); ?> /><label for="5e" class="grey"></label></td>
              <td><input type="radio" id="2e" name="wipOptions[headersEven]" style="display:none;" value="262a2d" <?php checked( '262a2d', $settings['headersEven'] ); ?> /><label for="2e" class="black"></label></td>
              <td><input type="radio" id="0e" name="wipOptions[headersEven]" style="display:none;" value="003c69" <?php checked( '003c69', $settings['headersEven'] ); ?> /><label for="0e" class="blue"></label></td>
            </tr>
            <tr>
              <td><input type="radio" id="4e" name="wipOptions[headersEven]" style="display:none;" value="452325" <?php checked( '452325', $settings['headersEven'] ); ?> /><label for="4e" class="brown"></label></td>
              <td><input type="radio" id="be" name="wipOptions[headersEven]" style="display:none;" value="b6bf00" <?php checked( 'b6bf00', $settings['headersEven'] ); ?> /><label for="be" class="green"></label></td>
              <td><input type="radio" id="ee" name="wipOptions[headersEven]" style="display:none;" value="ec7a08" <?php checked( 'ec7a08', $settings['headersEven'] ); ?> /><label for="ee" class="orange"></label></td>
              <td><input type="radio" id="3e" name="wipOptions[headersEven]" style="display:none;" value="3cb6ce" <?php checked( '3cb6ce', $settings['headersEven'] ); ?> /><label for="3e" class="aqua"></label></td>
            </tr>
          </table>

				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Footer Information</th>
				<td>Physical address.<br />
					<input id="footerInfo" name="wipOptions[footerInfo]" type="text" value="<?php	esc_attr_e( $settings['footerInfo'] ); ?>" size="50" /><br />
					Phone number.<br />
					<input id="footerPhone" name="wipOptions[footerPhone]" type="text" value="<?php	esc_attr_e( $settings['footerPhone'] ); ?>" size="50" /><br />
					"Contact" link (prefix with <strong>mailto:</strong> if using an email address).<br />
					<input id="footerContact" name="wipOptions[footerContact]" type="text" value="<?php	esc_attr_e( $settings['footerContact'] ); ?>" size="50" />
				</td>
			</tr>
		</table>
		<p class="submit"><input type="submit" class="button-primary" value="Save Options" /></p>
	</form>
</div>
<?php
}

function wipValidateOptions( $input ) {
	// Strip HTML from the fields
	$input['addThis'] = wp_filter_nohtml_kses( $input['addThis'] );
	$input['googleAnalytics'] = wp_filter_nohtml_kses( $input['googleAnalytics'] );
	$input['footerInfo'] = wp_filter_nohtml_kses( $input['footerInfo'] );
	$input['footerPhone'] = wp_filter_nohtml_kses( $input['footerPhone'] );
	$input['footerContact'] = wp_filter_nohtml_kses( $input['footerContact'] );
	$input['headersOdd'] = wp_filter_nohtml_kses( $input['headersOdd'] );
	$input['headersEven'] = wp_filter_nohtml_kses( $input['headersEven'] );
	if( get_option( 'show_on_front' ) == 'posts' ) {
		$input['slidesCategory'] = wp_filter_nohtml_kses( $input['slidesCategory'] );
		$input['slideCount'] = wp_filter_nohtml_kses( $input['slideCount'] );
	}
	
	// Verify that the input is a boolean value if a checkbox has been checked, otherwise void it
	$checkbox = array( 'shareTools', 'tbPrintLink', 'tbBreadcrumb', 'tbShareLink', 'tbSearch', 'postPubDate', 'pagePubDate', 'loopPubDate', 'postAuthor', 'pageAuthor', 'loopAuthor', 'postShare', 'pageShare', 'postTaxonomy', 'loopTaxonomy', 'loopComments' );
	if( get_option( 'show_on_front' ) == 'posts' )
		array_push($checkbox, 'indexSlide');
	
	foreach( $checkbox as $checkbox_value ) {
		if( ! isset( $input[$checkbox_value] ) )
			$input[$checkbox_value] = null;
		$input[$checkbox_value] = ( $input[$checkbox_value] == 1 ? 1 : 0 );
	}

	return $input;
}
?>