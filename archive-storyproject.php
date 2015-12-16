<?php
/*
Template for archives of all kinds except author
*/
$storySets = array();
$storySets[0] = array( 'n' => 3, 'c' => 'set-large-left' , 'o' => array(0,1,2,2,1) );
$storySets[1] = array( 'n' => 3, 'c' => 'set-large-right' , 'o' => array(0,1,2,2,1) );
//$storySets[1] = array( 'n' => 4, 'c' => 'set-large-right' );
//$storySets[2] = array( 'n' => 3, 'c' => 'set-medium-equal' );
//==== CREATE STORY ARRAY ===================+
$storyproject = array();

$args = array( 'post_type' => 'storyproject' , 'posts_per_page' => 100 );
$spQuery = new WP_Query( $args );
if ( $spQuery->have_posts() ) {
	while ( $spQuery->have_posts() ) {
		$spQuery->the_post(); 
		//==== PARSE DATA ===================+
		$tierMeta = get_post_meta( get_the_ID() , 'storyproject-tier' , true );
		$tierVal = ( $tierMeta )? $tierMeta : 3;
		$sQuote = get_post_meta( get_the_ID() , 'storyproject-quote' , true ); 
		$sRank = get_post_meta( get_the_ID() , 'storyproject-rank' , true );
		$sVideo = get_post_meta( get_the_ID() , 'storyproject-video' , true );
		$sSubt = get_post_meta( get_the_ID() , 'storyproject-subtitle' , true );
		$vidClass = ( $sVideo )? ' has-video': '';
		//==== CREATE STORY ARRAY ===================+
		$iWide = false; $iTall = false;
		if ( has_post_thumbnail() ) {
			$iWide = get_the_post_thumbnail( get_the_ID(), 'gallery-tall4x6' , array('class'=>'gallery-image') );
			$iTall = get_the_post_thumbnail( get_the_ID(), 'gallery-wide4x3' , array('class'=>'gallery-image') ); 
    	};
		//==== CREATE STORY ARRAY ===================+
		$storyproject[] = array(
			'title' => get_the_title(),
			'content' => get_the_content(),
			'tier' => $tierVal,
			'image-tall' => $iWide,
			'image-wide' => $iTall,
			'quote' => $sQuote,
			'summary' => get_the_excerpt(),
			'link' => '<a href="'. get_permalink() .'" class="story-link'.$vidClass.'" >',
			'rank' => $sRank,
			'id' => get_the_ID(),
			'video' => $sVideo,
			'subtitle' => $sSubt,
		);
	} 
};
//==== SHUFFLE STORY ARRAY ===================+
shuffle( $storyproject );
//==== BUILD TIERS ===================+
$tiers = array();
if( $storyproject ){
	$tiers[0] = array();
	$tiers[1] = array();
	$tiers[2] = array();
	foreach( $storyproject as $story ){
		if ( 1 == $story['tier'] ){ $tiers[0][] =  $story; }
		else if( 2 == $story['tier'] ){ $tiers[1][] =  $story; }
		else {$tiers[2][] =  $story; };
	}
};
//==== BUILD TEIR PRIORITIES ===================+
foreach( $tiers as $tk => $td ){
	$p1 = array(); $p2 = array(); $p3 = array();
	foreach( $td as $storyT ){
		if ( 1 == $storyT['rank'] ){ $p1[] =  $storyT; }
		else if( 2 == $storyT['rank'] ){ $p2[] =  $storyT; }
		else { $p3[] =  $storyT; };
	}
	$tiers[$tk] = array_merge($p1, $p2 , $p3 );
}
$s = 0; // CURRENT SET INDICATOR
$sn = 0; // NEW CREATED SETS INDICATOR
$st = 0; // CURRENT SET TYPE INDICATOR
$sc = 0; // STORY COUNT INDICATOR
//$ti = array(0,0,0);
$setArray = array(); // SET BUILT OUT AS AN ARRAY
while( $sc < count( $storyproject ) ){
	$st = ( $st < count( $storySets ) )? $st : 0 ;
	$cSetType = $storySets[$st];
	$tA = array();
	foreach ( $cSetType['o'] as $sI ){ // SET ITEM IS A TIER
		if( $tiers[$sI] ){ $cT = $sI;}// CHECK TO SEE IF ANYTING LEFT IN THAT TIER
		else if( $si != 1 && $tiers[1] ){ $cT = 1;} // IF NOT EQUAL TO TIER CHECK TIER 1
		else if( $si != 2 && $tiers[2] ){ $cT = 2;} // IF NOT EQUAL TO TIER IF NOT CHECK TIER 2
		else if( $si != 0 && $tiers[0] ){ $cT = 0;}// IF NOT EQUAL TO TIER IF NOT CHECK TIER 3
		else {$cT = 3;}
		if( $cT < 3 ){
			$wideSpacer = '<img src="'. get_template_directory_uri() .'/images/spacer-wide.gif" class="spacer-image wide-image" />';
			$tallSpacer = '<img src="'. get_template_directory_uri() .'/images/spacer-tall.gif" class="spacer-image tall-image" />';
			if( $sI == '0' ){ 
				$sClass = ' large-feature-tall';
				$tiers[$cT][0]['spacer'] = $tallSpacer;
				$tiers[$cT][0]['type'] = 'tall';
			}
			else if( $sI == '1' ){ 
				$sClass = ' medium-feature-wide';
				$tiers[$cT][0]['spacer'] = $wideSpacer;
				$tiers[$cT][0]['type'] = 'wide';
			}
			else if( $sI == '2' ){ 
				$sClass = ' small-feature-tall';
				$tiers[$cT][0]['spacer'] = $tallSpacer;
				$tiers[$cT][0]['type'] = 'tall';
			}
			else { 
				$sClass = '';
			};
			$tiers[$cT][0]['class'] = 'story-item ' . $cSetType['c'] . $sClass;
			$tA[] = $tiers[$cT][0]; // ADD STORY TO ARRAY
			unset($tiers[$cT][0]); // REMOVE STORY FROM ARRAY
			$tiers[$cT] = array_values( $tiers[$cT] ); // RESET ARRAY VALUES
		}
	};
	$setArray[]= $tA;
	$st++;
	$sc = $sc + count( $cSetType['o'] );
};
//==== START PAGE ===================+
get_header();?>
	<div id="main" class="story-project-archive">
		<div id="story-project">
			<h1>Voices of Extension Story Project</h1>
			<p>For the past century, WSU Extension has applied research and education to improve the quality of life throughout Washington.</p>
			<p>As part of our yearlong celebration of Extension's 100th birthday, CAHNRS Communications invites former and current students, faculty, staff, and friends to share how WSU Extension programs, services, and people have enriched their lives.</p>
			<p>Whether Extension has helped you become a better gardener or farmer, improve how your family eats or manages its finances, or teach your child about citizenship, we want to hear about it. We will continue to record the stories that make up the Voices of Extension until we reach 100!<br />
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
<!-- AddThis Button END --></p>
      <h2><a href="http://www.surveygizmo.com/s3/1511186/Story-Project-Form" alt="share your story">Submit your story here &raquo;</a></h2>
        <?php
		foreach( $setArray as $set ){
			echo'<ul class="set-wrapper" >';
			foreach( $set as $story ){
				$sImg = ( $story['type'] == 'tall' )? $story['image-tall']: $story['image-wide'];
				echo '<li class="'.$story['class'].'" data-id="'.$story['id'].'">';
					echo $story['spacer'];
					echo $story['link'];
					echo $sImg;
					if( $story['video'] ){
								echo '<span href="#" class="has-video-icon" ></span>';
							}
					echo '<ul class="caption">';
						echo '<li class="caption-bg"></li>';
						echo '<li class="caption-inner">';
							echo '<span class="story-title">'; 
								echo $story['title']; 
								if($story['subtitle']) echo '<br /><strong class="story-subtitle">' . $story['subtitle'] . '</strong>';
							echo '</span>';
							if( $story['video'] ){
								echo '<span href="#" class="myButton video-button" >Watch Video</span>';
							}
							echo '<span class="story-summary">' . $story['summary'] . '</span>';
							echo '<span class="story-quote">"' . $story['quote'] . '"</span>';
						echo '</li>';
						echo '<li class="caption-link">';
							echo '<span href="#" class="myButton" >My Story</span>';
						echo '</li>';
					echo '</ul>';
					echo '</a>';
				echo '</li>';
			}
			echo '</ul>';
		}
		;?>
	</div></div><!-- #main -->
<?php get_footer();?>