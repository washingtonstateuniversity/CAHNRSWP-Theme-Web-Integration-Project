<?php
/*
Template for displaying search results.
*/

get_header();

?>
<div id="main">
	<h1>Search Results for "<?php printf( get_search_query() ); ?>"</h1>
<?php
	if( have_posts() ) {
		// Call loop.php for displaying the loop
		get_template_part( 'loop' );
	} else {
		?>
			<p>We're sorry, but there were no results for "<?php the_search_query(); ?>".</p>
		<?php 
	}
	get_search_form();
?>
</div><!-- #main -->
<?php get_footer(); ?>