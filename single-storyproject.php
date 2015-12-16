<?php
get_header();?>
	<div id="main">
    	<div id="story-project-single">
        <?php if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			$tierMeta = get_post_meta( $post->ID , 'storyproject-tier' , true );
			$tierVal = ( $tierMeta )? $tierMeta : 3;
			$sQuote = get_post_meta( $post->ID , 'storyproject-quote' , true );
			$sVideo = get_post_meta( $post->ID , 'storyproject-video' , true );
			$sSubtitle = get_post_meta( $post->ID , 'storyproject-subtitle' , true );
			echo '<div id="single-storyproject">';
			if( $sVideo ){
				echo '<iframe width="100%" height="315" src="//www.youtube.com/embed/'.$sVideo.'" frameborder="0" allowfullscreen></iframe>';
			} 
			echo '<h1>'; 
				the_title(); 
			echo '</h1>';
			if( $sSubtitle ){echo '<h2>'.$sSubtitle.'</h2>';}
			echo '<div class="share-bar">';?>
            <!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style ">
<a class="addthis_button_preferred_1"></a>
<a class="addthis_button_preferred_2"></a>
<a class="addthis_button_preferred_3"></a>
<a class="addthis_button_preferred_4"></a>
<a class="addthis_button_compact"></a>
<a class="addthis_counter addthis_bubble_style"></a>
</div>
<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-536b884d401488c2"></script>
<!-- AddThis Button END -->
            <?php
			echo '</div>';
			echo '<div class="story-content">';
				the_content();
			echo '</div>';
			// END STORY LAYOUT
			echo '</div>'; 
			//
			// Post Content here
			//
		} // end while
	} // end if ?>
		</div><div id="story-project-single-right">
        	<h3>More Stories</h3>
            <?php
			$args = array( 'post_type' => 'storyproject' , 'posts_per_page' => 3 , 'orderby' => 'rand');
			$spQuery = new WP_Query( $args );
			if ( $spQuery->have_posts() ) {
				while ( $spQuery->have_posts() ) {
					$spQuery->the_post(); 
					//==== PARSE DATA ===================+
					$tierMeta = get_post_meta( get_the_ID() , 'storyproject-tier' , true );
					$tierVal = ( $tierMeta )? $tierMeta : 3;
					echo '<a href="'. get_permalink() .'" class="story-link" >';
					if( has_post_thumbnail() ){
						the_post_thumbnail( get_the_ID(), 'gallery-tall4x6' , array('class'=>'gallery-image-side') );
					}
					echo '<h4>'; the_title(); echo '</h4>';
					echo '</a>';
					echo '<hr />';
				} 
			};
			?>
        </div>
    </div><!-- #main -->
<?php get_footer();?>