<?php
/*
The loop

Used by archive.php, author.php, index.php, search.php

Allows site owners to define loop settings through Theme Options or individual categories
*/

$theme = get_option( 'wipOptions' );


if( is_category() )
	$categoryID = get_query_var( 'cat' );


$option = array(
	'author' => array(
		'setting' => 'loopAuthor',
		'default' => '',
	),
	'pubdate' => array(
		'setting' => 'loopPubDate',
		'default' => '',
	),
	'taxonomy' => array(
		'setting' => 'loopTaxonomy',
		'default' => '',
	),
	'Comments' => array(
		'setting' => 'loopComments',
		'default' => '',
	),
);


foreach( $option as $option_value ) {
	if( is_category() && get_tax_meta( $categoryID, $option_value['setting'] ) )
		$option[$option_value['setting']] = get_tax_meta( $categoryID, $option_value['setting'] );
	else if( $theme[$option_value['setting']] )
		$option[$option_value['setting']] = $theme[$option_value['setting']];
	else
		$option[$option_value['setting']] = $option_value['default'];
}


while( have_posts() ) {
	the_post();
  ?>
  <h4 style="clear:both;"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>
  <?php
  if( ( $option['loopAuthor'] ) || ( $option['loopPubDate'] ) || is_search() ) {
		?>
		<h6>Posted <?php
   		if( $option['loopAuthor'] ) {
				echo 'by <a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">';
				the_author();
				echo '</a>';
			}
			if( ( $option['loopAuthor'] ) && ( $option['loopPubDate'] ) )
				echo ' | ';
			if( ( $option['loopPubDate'] ) || is_search() )
				the_time('F j, Y');
		?></h6>
		<?php
	}
	/*if( is_search() )
		the_excerpt(); // This isn't very useful if some excerpts have been crafted for use in slideshows
	else*/
		the_content('Read more &raquo;');

	if( ( $option['loopTaxonomy'] ) || ( $option['loopComments'] ) ) {
		?>
		<div class="met-com">
		<?php
			if( $option['loopTaxonomy'] ) {
				?>
				<h6 class="met">Filed under <?php the_category(', '); the_tags( ', tagged ', ', ', '');?></h6>
				<?php
			}
	
			if( $option['loopComments'] ) {
				?>
				<h6 class="com"><?php comments_number(); ?></h6>
				<?php
			}
		?>
		</div>
		<?php
	}
}

wipPaginationNav();
?>