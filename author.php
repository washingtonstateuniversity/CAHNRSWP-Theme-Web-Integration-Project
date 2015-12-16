<?php
/*
Author archives
*/

get_header();

// Queue the first post to find out which author this is
// Reset on line 19 with rewind_posts in order to run the loop of posts
if( have_posts() ) {

	the_post();
	?>
	<div id="main">
		<h1><?php echo esc_html( get_the_author() ); ?></h1>
    <?php
			if( get_the_author_meta( 'description' ) )
    		the_author_meta( 'description' );

			rewind_posts();
			get_template_part( 'loop' );
		?>
	</div><!-- #main -->
	<div id="secondary">
		<h4>Contact <?php the_author_meta( 'display_name' ); ?></h4>
		<?php
			// If the "Title" or "Department" fields have data, spit it out
			if( get_the_author_meta( 'title' ) || get_the_author_meta( 'dept' ) ) {
				echo '<p>';
	
				if( get_the_author_meta( 'title' ) ) {
					the_author_meta('title');
					echo ',<br />';
				}
	
				if( get_the_author_meta( 'dept' ) )
					the_author_meta('dept');
	
				echo '</p>';
			}
			
			//Since "user_email" will always be present, conditions are needed only on the "Office" and "Phone" fields
			echo '<p>';
	
			if( get_the_author_meta( 'office' ) ) {
				the_author_meta('office');
				echo '<br />';
			}
	
			if( get_the_author_meta( 'phone' ) ) {
				echo 'Phone: ';
				the_author_meta('phone');
				echo '<br />';
			}
	
			echo 'Email: <a href="mailto:';
			the_author_meta('user_email');
			echo '">';
			the_author_meta('user_email');
			echo '</a>';
	
			echo '</p>';
		?>
	</div><!-- #secondary -->
	<?php

}

get_footer();
?>