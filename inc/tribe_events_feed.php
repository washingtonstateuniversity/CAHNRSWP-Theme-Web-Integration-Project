<?php
/*
--- LEGACY: Keeping in case needed for future reference ---

RSS2 Feed Template for displaying RSS2 Tribe Events feed.
*/

header('Content-Type: ' . feed_content_type('rss-http') . '; charset=' . get_option('blog_charset'), true);
$more = 1;

echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>'; ?>

<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
  xmlns:gd='http://schemas.google.com/g/2005'
	<?php do_action('rss2_ns'); ?>
>

<channel>
	<title><?php bloginfo_rss('name'); wp_title_rss(); ?></title>
	<atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
	<link><?php bloginfo_rss('url') ?></link>
	<description><?php bloginfo_rss("description") ?></description>
	<lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></lastBuildDate>
	<language><?php bloginfo_rss( 'language' ); ?></language>
	<sy:updatePeriod><?php echo apply_filters( 'rss_update_period', 'hourly' ); ?></sy:updatePeriod>
	<sy:updateFrequency><?php echo apply_filters( 'rss_update_frequency', '1' ); ?></sy:updateFrequency>
	<?php do_action('rss2_head'); ?>
	<?php while( have_posts()) : the_post(); ?>
	<item>
		<title><?php the_title_rss() ?></title>
		<link><?php the_permalink_rss() ?></link>
		<comments><?php comments_link_feed(); ?></comments>
		<pubDate><?php echo date( 'D, d M Y H:i:s +0000', strtotime( get_post_meta( $post->ID, '_EventStartDate', true ) ) ); ?></pubDate>
		<dc:creator><?php the_author() ?></dc:creator>
		<?php
    	if( get_the_terms( $post->ID, 'tribe_events_cat' ) )
			{
				foreach( get_the_terms( $post->ID, 'tribe_events_cat' ) as $category )
				{
					echo '<category><![CDATA[' . $category->name . ']]></category>';
				}
			}
		?>
		<guid isPermaLink="false"><?php the_guid(); ?></guid>
		<description><![CDATA[<?php
			// Event stuff
			$eventMetaPrefix = '_Event';
			$venueMetaPrefix = '_Venue';
			$eventStart = date( 'M d g:ia', strtotime( get_post_meta( $post->ID, $eventMetaPrefix . 'StartDate', true ) ) );
			$eventCost = get_post_meta( $post->ID, $eventMetaPrefix . 'Cost', true );
			$venueID = get_post_meta( $post->ID, $eventMetaPrefix . 'VenueID', true );
			$venuePermalink = get_permalink( $venueID );
			$venueName = get_post_meta( $venueID, $venueMetaPrefix . 'Venue', true );
			$venueAddress = get_post_meta( $venueID, $venueMetaPrefix . 'Address', true );
			$venueCity = get_post_meta( $venueID, $venueMetaPrefix . 'City', true );
			$venueState = get_post_meta( $venueID, $venueMetaPrefix . 'State', true );
			
			// Put content together
			$content = '<p>When: ' . esc_html( $eventStart );
			if( $venueName )
				$content .=  '<br />Where: <a href="' . esc_url( $venuePermalink ) . '">' . esc_html( $venueName ) . '</a>';
			if( $venueAddress )
				$content .=  '<br />' . esc_html( $venueAddress );
			if( $venueCity )
				$content .=  '<br />' . esc_html( $venueCity );
			if( $venueState )
				', ' . esc_html( $venueState );
			if( $eventCost )
				$content .= '<br />Cost: ' . esc_html( $eventCost );
			$content .= '</p>';

			echo $content;
			the_excerpt_rss();
		?>]]></description>
		<?php $content = get_the_content_feed('rss2'); ?>
		<?php if ( strlen( $content ) > 0 ) : ?>
		<content:encoded><![CDATA[<?php echo $content; ?>]]></content:encoded>
		<?php else : ?>
		<content:encoded><![CDATA[<?php the_excerpt_rss(); ?>]]></content:encoded>
		<?php endif; ?>
		<wfw:commentRss><?php echo esc_url( get_post_comments_feed_link(null, 'rss2') ); ?></wfw:commentRss>
		<slash:comments><?php echo (int) get_comments_number(); ?></slash:comments>
		<?php rss_enclosure(); ?>
		<?php do_action('rss2_item'); ?>
	</item>
	<?php endwhile; ?>
</channel>
</rss>