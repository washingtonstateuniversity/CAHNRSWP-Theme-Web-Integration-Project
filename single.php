<?php
/*
Template for displaying Posts.
*/

$wipOptions = get_option( 'wipOptions' );
$layoutMeta = $wipLayout->the_meta();
$redirect = get_post_meta( $post->ID, 'redirect', true );

// If there is custom redirect metadata on this page 
if( $redirect ) {
	wp_redirect( $redirect );
	die();
} else {
	get_header();

	while( have_posts() ) {
		the_post();

		// If a layout other than "Full Width" is selected, split the post content at the more tags
		if( isset( $layoutMeta['layout'] ) &&
				( $layoutMeta['layout'] != '' || $layoutMeta['layout'] != '0' )
		) {
			// Double angle brackets are necessary for the pattern to match.
			$content = preg_split( "<<!--more-->>", $post->post_content );
		}

		// Show the title across all columns if a two equal, three, or four column layout
		if( isset( $layoutMeta['layout'] ) &&
				( $layoutMeta['layout'] != '0' && $layoutMeta['layout'] != '1' )
			)
		{
			?>
				<h1 id="pagetitle"><?php the_title(); ?></h1>
			<?php
		}

		?>
		<div id="main">
			<?php
				// Show the title if a full or sidebar layout
				if( !isset( $layoutMeta['layout'] ) ||
						( isset( $layoutMeta['layout'] ) &&
							( $layoutMeta['layout'] == '0' || $layoutMeta['layout'] == '1' )
						)
				) {
					?>
						<h1><?php the_title(); ?></h1>
					<?php
				}
			
				// If "Show publication Date on posts" option is selected, do it.
				if( $wipOptions['postPubDate'] ) {
					?>
						<h6><?php the_time('F j, Y'); ?></h6>
					<?php
				}
		
				// If "Show author on posts" option is selected, do it.
				if( $wipOptions['postAuthor'] ) {
					?>
						<h5>By <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php the_author(); ?></a></h5>
					<?php
				}
	
				// Determine which layout is being used and output content appropriately
				if( !isset( $layoutMeta['layout'] ) ||
						( $layoutMeta['layout'] == '' || $layoutMeta['layout'] ==  '0' )
				) {
					the_content();
				} else {
					echo apply_filters( 'the_content', $content[0] );
					if( preg_match( '/<!--more(.*?)?-->/', $post->post_content ) )
						echo apply_filters( 'the_content', $content[1] );
				}
	
				if( $wipOptions['postTaxonomy'] ) {
					?>
						<div class="met-com">
							<h6>Filed under <?php the_category(', '); the_tags( ', tagged ', ', ', '');?></h6>
						</div>
					<?php
				}
	
				if( $wipOptions['postShare'] ) {
					?>
						<div class="addthis_toolbox addthis_default_style">
							<a class="addthis_button_preferred_1"></a>
							<a class="addthis_button_preferred_2"></a>
							<a class="addthis_button_preferred_3"></a>
							<a class="addthis_button_preferred_4"></a>
							<a class="addthis_button_compact"></a>
							<a class="addthis_counter addthis_bubble_style"></a>
						</div>
					<?php
				}

				comments_template();
			?>
		</div><!-- #main -->
			<?php 
        // If a multiple-column layout is selected, display the "secondary" div.
        if( isset( $layoutMeta['layout'] ) &&
					( $layoutMeta['layout'] == '1' || $layoutMeta['layout'] == '2' || $layoutMeta['layout'] == '3' || $layoutMeta['layout'] == '4' )
				) {
          ?>
            <div id="secondary">
              <?php
                if( preg_match( '/<!--more(.*?)?-->/', $post->post_content ) )
                  echo apply_filters( 'the_content', $content[2] );

                // If this is in a category	which has extended its widget area to posts, show the widget area
                $categories = get_the_category();
								foreach( $categories as $category ) {
									$categoryID = $category->cat_ID;
									if( is_active_sidebar( 'category_' . $categoryID . '_widget' ) )
										dynamic_sidebar( 'category_' . $categoryID . '_widget' );
								}
              ?>
            </div><!-- #secondary -->
          <?php	
        }


        // If the Three Column layout is selected, display the Additional div.
        if( isset( $layoutMeta['layout'] ) &&
						( $layoutMeta['layout'] == '4' || $layoutMeta['layout'] == '5' )
				) {
          ?>
            <div id="additional">
              <?php
                if( preg_match( '/<!--more(.*?)?-->/', $post->post_content ) )
                  echo apply_filters( 'the_content', $content[3] );
              ?>
            </div><!-- #additional -->
          <?php
        }


				// If the Four Column layout is selected, display the Fourth div.
        if( isset( $layoutMeta['layout'] ) && $layoutMeta['layout'] == '5' ) {
          ?>
            <div id="fourth">
              <?php
                if( preg_match( '/<!--more(.*?)?-->/', $post->post_content ) )
                  echo apply_filters( 'the_content', $content[4] );
              ?>
            </div><!-- #fourth -->
          <?php
        }

	}

	get_footer();

}
?>
