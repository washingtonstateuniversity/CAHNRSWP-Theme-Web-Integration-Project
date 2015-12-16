<?php
/*
The Front page by default, or takes the place of page.php for the page selected as the Posts page under Reading settings.
*/

get_header();

if( wp_get_theme()->Name == 'WSU' && $wipOptions['indexSlide'] && $wipOptions['slidesCategory'] && $wipOptions['slideCount'] && !is_paged() ) {
	$wipOptions = get_option( 'wipOptions' );
	
	if( !$wipOptions['designKey'] )
		$design = 'b';
	else
		$design = substr( stripslashes( $wipOptions['designKey'] ), 3, 1 );
	?>
	<div id="home">
		<div id="slideshow">
		<?php
    	query_posts( 'cat=' . $wipOptions['slidesCategory'] . '&posts_per_page=' . $wipOptions['slideCount'] );
			while( have_posts() ) {
				the_post();
				if( has_post_thumbnail() ) {
					$img_data = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), "slideshow-wsu" );
					?>
					<div style="background-image:url(<?php echo esc_url( $img_data[0] ); ?>);">
						<table>
              <tr>
                <td>
                  <?php
                    echo get_the_excerpt();
									?>
										<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><img src="<?php bloginfo( 'stylesheet_directory' ); ?>/images/more-button-<?php echo $design; ?>.png" width="83" height="28" alt="Read more" /></a>
                </td>
              </tr>
            </table>
					</div>
			<?php
				}
			}
			wp_reset_query();
		?>
		</div>
	</div>
	<?php
}

if( have_posts() ) {
?>
	<div id="main">
		<h1>Latest Posts</h1><?php // not sure what to do for the title ?>
		<?php // Call loop.php for displaying the loop
			get_template_part( 'loop' );
		?>
	</div><!-- #main -->
	<?php // If the "Posts Page" widget area is active
	if( is_active_sidebar( 'index' ) ) {
		?>
		<div id="secondary">
			<?php dynamic_sidebar( 'index' ); ?>
		</div><!-- #secondary -->
		<?php
	}
}

get_footer();
?>