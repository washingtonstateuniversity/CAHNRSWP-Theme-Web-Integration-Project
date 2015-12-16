<?php
/*
Template for displaying attachments.
*/

get_header();
?>
<div id="main">
	<?php
		while( have_posts() ) {
			the_post();
			?>
			<div style="display:block;overflow:auto;">
				<p class="half"><?php next_image_link( false, '&laquo; Previous Uploads' ); ?></p>
				<p class="half" style="text-align:right;"><?php previous_image_link( false, 'Newer Uploads &raquo;' ); ?></p>
			</div>
			<h1><?php the_title(); ?></h1>
			<?php
				$metadata = wp_get_attachment_metadata();
				printf( __( '<p>Uploaded %1$s. Full size <a href="%2$s" title="Link to full-size image">%3$s &times; %4$s</a> pixels.</p>'),
					get_the_time(),
					esc_url( wp_get_attachment_url() ),
					esc_html( $metadata['width'] ),
					esc_html( $metadata['height'] )
				);
				$imageSize = array( 'slideshow-wsu', 'large', 'medium', 'thumbnail' );
				foreach( $imageSize as $imageSize_value ) {
					$imageAttributes = wp_get_attachment_image_src( $post->ID, $imageSize_value );
					if( $imageAttributes[1] > '731' )
						echo '<p><a href="' . esc_url( $imageAttributes[0] ) . '">' . esc_html( $imageAttributes[1] ) . ' &times; ' . esc_html( $imageAttributes[2] ) . ' pixels</a></p>';
					else
						echo '<p><a href="' . esc_url( $imageAttributes[0] ) . '"><img src="' . esc_url( $imageAttributes[0] ) . '" width="' . esc_attr( $imageAttributes[1] ) . '" height="' . esc_attr( $imageAttributes[2] ) . '"></a></p>';
				}
		}
	?>
</div><!-- #main -->
<?php	get_footer(); ?>