<?php
/*
Comments and pingbacks.
*/
	
// Callback for displaying the comments and pingbacks
function wipListComments( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch( $comment->comment_type ) {
		case 'pingback' :
		case 'trackback' :
			?>
			<li class="post pingback">
				<p><?php _e( 'Pingback:' ); comment_author_link(); edit_comment_link( __( 'Edit' ) ); ?></p>
			<?php
			break;
		default :
	?>
	<li>
		<div id="comment-<?php comment_ID(); ?>">
			<p><?php
				printf( __( '<strong>%1$s</strong> said on %2$s: ' ),
					sprintf( '%s', get_comment_author() ),
					sprintf( '%1$s', get_comment_date() )
				);
	
				edit_comment_link( __( 'Edit' ) );
			?></p>
			<?php
      	if( $comment->comment_approved == '0' ) {
					?>
					<em class="comment-awaiting-moderation">Your comment is awaiting moderation.</em>
					<?php
      	}

				comment_text();
			?>
			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply <span>&darr;</span>' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div>
		</div>
	<?php
		break;
	}
}

// Customize the fields of the comment form
function wipCustomFields( $fields ) {
	$fields =  array(
		'author' => '<p><input id="author" name="author" type="text" value="" size="30" /> <label for="author">Name</label> *</p>',
		'email'  => '<p><input id="email" name="email" type="text" value="" size="30" /> <label for="email">Email</label> *</p>',
		'url'    => '<p><input id="url" name="url" type="text" value="" size="30" /> <label for="url">Website</label></p>',
		);

	return $fields;
}

add_filter('comment_form_default_fields','wipCustomFields');

// Remove URL field
function remove_comment_fields( $fields ) {
    unset($fields['url']);
    return $fields;
}
add_filter('comment_form_default_fields','remove_comment_fields');

// If the post is password protected
if( post_password_required() ) {
	?>
	<p>This post is password protected. Enter the password to view any comments.</p>
	<?php // Stop the rest of comments.php from being processed, but don't kill the script entirely - the rest of the template needs to load
	return;
}

// List the comments if there are any
if( have_comments() ) {
	?>
	<h3 style="clear:both;"><?php printf( _n( 'One comment on &ldquo;%2$s&rdquo;', '%1$s comments on &ldquo;%2$s&rdquo;', get_comments_number() ),
		number_format_i18n( get_comments_number() ), get_the_title() ); ?></h3>
	<ol>
		<?php wp_list_comments( array( 'callback' => 'wipListComments' ) ); ?>
	</ol>
	<?php // If there are comments to navigate through, offer some navigation
	if( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) {
		?>
		<div>
			<p class="half"><?php previous_comments_link( __( '&larr; Older Comments' ) ); ?></p>
			<p class="half" style="text-align:right;"><?php next_comments_link( __( 'Newer Comments &rarr;' ) ); ?></p>
		</div>
		<?php
	}
}

// Customize the comment field itself
comment_form(
	array(
		'comment_field' => '<p><label for="comment">Comment</label><br /><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
		'title_reply' => 'Leave a Comment',
		'Leave a Reply to %s' => '<h4>Reply to %s</h4>',
		'comment_notes_after' => ''
	)
);
?>