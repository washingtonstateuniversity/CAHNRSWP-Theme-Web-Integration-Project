<?php
/*
Template for archives of all kinds except author
*/

get_header();

$catDesc = category_description();
$tagDesc = tag_description();
$categoryID = false;

if( is_category() )
		$categoryID = get_query_var('cat');

if( have_posts() ) {
	?>
	<div id="main">
		<h1>
			<?php
			if( is_category() ) {
				if( is_month() )
					printf( single_cat_title( '', false ) . ' - ' . get_the_time( 'F Y' ) );
				else if( is_year() )
					printf( single_cat_title( '', false ) . ' - ' . get_the_time( 'Y' ) );
				else if( is_category() )
					printf( single_cat_title( '', false ) );
			} else if ( is_day() ) {
				printf( __( 'Archive for %s' ), get_the_time() );
			} else if( is_month() ) {
				printf( __( 'Archive for %s' ), get_the_time( __( 'F Y', '' ) ) );
			}	else if( is_year() ) {
				printf( __( 'Archive for %s' ), get_the_time( __( 'Y', '' ) ) );
			} else if( is_tag() ) {
				printf( single_tag_title( '', false ) );
			} else {
				echo 'Archives';
			}
			
			if( $categoryID && ! get_tax_meta( $categoryID, 'magazine' ) ) {
				?>
        	<a href="feed/" title="RSS Feed" id="archivefeedlink"><img src="<?php bloginfo( 'template_directory' ); ?>/images/archive-feed.png" width="20" height="20" alt="RSS feed" /></a>
        <?php
			}
			?>
		</h1>
		<?php
			// Custom stuff for Reconnect
			if( is_category() && get_tax_meta( $categoryID, 'magazine' ) ) {
				if( is_month() ) {
					$year = date( get_the_time( 'Y' ) );
					$month = date( get_the_time( 'n' ) );
				} else {
					global $query_string;
					get_posts( $query_string . 'posts_per_page=1' );
					the_post();
					$year = get_the_time( 'Y' );
					$month = get_the_time( 'n' );
					rewind_posts();
				}
				
				$childCategory = get_term_children( get_query_var('cat'), 'category' );

				if( $childCategory ) {
					foreach($childCategory as $childCategory_value) {

						$childCategory_args = array( 'cat' => $childCategory_value, 'year' => $year, 'monthnum' => $month, 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => -1 );

						$childCategory_query = new WP_Query( $childCategory_args );
						
						if( $childCategory_query->have_posts() ) {
							$term = get_term_by( 'id', $childCategory_value, 'category' );
							echo '<h4>' . $term->name . '</h4>';
							echo '<ul>';
							while( $childCategory_query->have_posts() ) {
								$childCategory_query->the_post();
									?>
										<li><a href="<?php the_permalink(); ?>" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
									<?php
							}
							echo '</ul>';
						}

						wp_reset_postdata();

					}
				}
			} else {
				// Call loop.php for displaying the loop
				get_template_part( 'loop' );
			}
		?>
	</div><!-- #main -->
	<?php // If this is a category archive, get the category's ID and make it a variable for use later on
	if( is_category() )
		$categoryID = get_query_var('cat');
	
	// If one of the relevant widget areas is active, or a category or tag has a description, display it
	if(
			( is_archive() && !is_category() && !is_tag() && is_active_sidebar( 'archive' ) ) ||
			( is_category() && ( ! empty( $catDesc ) || is_active_sidebar( 'category_archive' ) || is_active_sidebar( 'category_' . $categoryID . '_widget' ) ) ) ||
			( is_tag() && ( ! empty( $tagDesc ) || is_active_sidebar( 'tag_archive' ) ) )
	)
	{
	?>
	<div id="secondary">
		<?php
			if( ! is_category() && ! is_tag() && is_active_sidebar( 'archive' ) )
				dynamic_sidebar( 'archive' );

			if( is_category() ) {
				if( ! empty( $catDesc ) )
					echo apply_filters( 'category_archive_meta', $catDesc ); // @todo escape properly, possibly wp_kses_post()
				if( is_active_sidebar( 'category_archive' ) )
					dynamic_sidebar( 'category_archive' );
				if( is_active_sidebar( 'category_' . $categoryID . '_widget' ) )
					dynamic_sidebar( 'category_' . $categoryID . '_widget' );
			}

			if( is_tag() ) {
				if( ! empty( $tagDesc ) )
					echo apply_filters( 'tag_archive_meta', $tagDesc ); // @todo escape properly, possibly wp_kses_post()
				if( is_active_sidebar( 'tag_archive' ) )
					dynamic_sidebar( 'tag_archive' );
			}
		?>
	</div><!-- #secondary -->
	<?php
	}

}

get_footer();
?>