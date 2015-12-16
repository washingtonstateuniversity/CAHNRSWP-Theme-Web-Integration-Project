<?php
/**
 * Adapted from http://wordpress.org/extend/plugins/breadcrumbs-plus/
 */

function breadcrumbs( $args = '' ) {

	$defaults = array( 'separator' => '&raquo;', 'front_page' => false, 'show_blog' => false, 'singular_post_taxonomy' => 'category', 'echo' => true );
	$args = apply_filters( 'breadcrumbs_args', $args );
	$args = wp_parse_args( $args, $defaults );

	if( is_front_page() && !$args['front_page'] )
		return apply_filters( 'breadcrumbs', false );

	$separator = ( !empty( $args['separator'] ) ) ? "{$args['separator']}" : "/";
	$items = breadcrumbs_get_items( $args );
	$breadcrumbs = '';
	//$breadcrumbs .= $title;
	$breadcrumbs .= join( " {$separator} ", $items );
	$breadcrumbs = apply_filters( 'breadcrumbs', $breadcrumbs );

	if( !$args['echo'] )
		return $breadcrumbs;
	else
		echo $breadcrumbs;

}

function breadcrumbs_get_items( $args ) {
	
	global $wp_query;
	$item = array();
	
	if( is_home() ) {
		$home_page = get_page( $wp_query->get_queried_object_id() );
		$item = array_merge( $item, breadcrumbs_get_parents( $home_page->post_parent ) );
		$item['last'] = get_the_title( $home_page->ID );
	} else if( is_singular() ) {
		$post = $wp_query->get_queried_object();
		$post_id = (int) $wp_query->get_queried_object_id();
		$post_type = $post->post_type;
		$post_type_object = get_post_type_object( $post_type );

		if( 'post' === $wp_query->post->post_type && $args['show_blog'] )
			$item[] = '<a href="' . get_permalink( get_option( 'page_for_posts' ) ) . '">' . get_the_title( get_option( 'page_for_posts' ) ) . '</a>';

		if( 'page' !== $wp_query->post->post_type ) {
			if( function_exists( 'get_post_type_archive_link' ) && !empty( $post_type_object->has_archive ) )
				$item[] = '<a href="' . get_post_type_archive_link( $post_type ) . '" title="' . esc_attr( $post_type_object->labels->name ) . '">' . $post_type_object->labels->name . '</a>';

			if( isset( $args["singular_{$wp_query->post->post_type}_taxonomy"] ) && is_taxonomy_hierarchical( $args["singular_{$wp_query->post->post_type}_taxonomy"] ) ) {
				$terms = wp_get_object_terms( $post_id, $args["singular_{$wp_query->post->post_type}_taxonomy"] );
				$item = array_merge( $item, breadcrumbs_get_term_parents( $terms[0], $args["singular_{$wp_query->post->post_type}_taxonomy"] ) );
			}
			else if( isset( $args["singular_{$wp_query->post->post_type}_taxonomy"] ) )
				$item[] = get_the_term_list( $post_id, $args["singular_{$wp_query->post->post_type}_taxonomy"], '', ', ', '' );
			}

			if( ( is_post_type_hierarchical( $wp_query->post->post_type ) || 'attachment' === $wp_query->post->post_type ) && $parents = breadcrumbs_get_parents( $wp_query->post->post_parent ) )
				$item = array_merge( $item, $parents );

			$item['last'] = get_the_title();
		} else if( is_archive() ) {
			if( is_category() || is_tag() || is_tax() ) {
				$term = $wp_query->get_queried_object();
				$taxonomy = get_taxonomy( $term->taxonomy );
				if ( ( is_taxonomy_hierarchical( $term->taxonomy ) && $term->parent ) && $parents = breadcrumbs_get_term_parents( $term->parent, $term->taxonomy ) )
					$item = array_merge( $item, $parents );
				$item['last'] = $term->name;
			} else if( function_exists( 'is_post_type_archive' ) && is_post_type_archive() ) {
				$post_type_object = get_post_type_object( get_query_var( 'post_type' ) );
				$item['last'] = $post_type_object->labels->name;
		} else if( is_date() ) {
			if( is_day() )
				$item['last'] = __( 'Archives for ', 'breadcrumbs' ) . get_the_time( 'F j, Y' );
			else if( is_month() )
				$item['last'] = __( 'Archives for ', 'breadcrumbs' ) . single_month_title( ' ', false );
			else if( is_year() )
				$item['last'] = __( 'Archives for ', 'breadcrumbs' ) . get_the_time( 'Y' );
	} else if( is_author() )
		$item['last'] = __( 'Archives by: ', 'breadcrumbs' ) . get_the_author_meta( 'display_name', $wp_query->post->post_author );
	}
	else if( is_search() )
		$item['last'] = __( 'Search results for "', 'breadcrumbs' ) . stripslashes( strip_tags( get_search_query() ) ) . '"';
	else if( is_404() )
		$item['last'] = __( 'Page Not Found', 'breadcrumbs' );
	return apply_filters( 'breadcrumbs_items', $item );
}

function breadcrumbs_get_parents( $post_id = '', $separator = '/' ) {
	$parents = array();

	if( $post_id == 0 )
		return $parents;

	while( $post_id ) {
		$page = get_page( $post_id );
		$parents[]	= '<a href="' . get_permalink( $post_id ) . '" title="' . esc_attr( get_the_title( $post_id ) ) . '">' . get_the_title( $post_id ) . '</a>';
		$post_id = $page->post_parent;
	}

	if( $parents )
		$parents = array_reverse( $parents );

	return $parents;

}

function breadcrumbs_get_term_parents( $parent_id = '', $taxonomy = '', $separator = '/' ) {

	$html = array();
	$parents = array();

	if( empty( $parent_id ) || empty( $taxonomy ) )
		return $parents;

	while( $parent_id ) {
		$parent = get_term( $parent_id, $taxonomy );
		$parents[] = '<a href="' . get_term_link( $parent, $taxonomy ) . '" title="' . esc_attr( $parent->name ) . '">' . $parent->name . '</a>';
		$parent_id = $parent->parent;
	}

	if( $parents )
		$parents = array_reverse( $parents );

	return $parents;

}
?>