<?php echo '<?xml version="1.0" encoding="UTF-8"?>',
'<rss xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" xmlns:slash="http://purl.org/rss/1.0/modules/slash/" xmlns:feedburner="http://rssnamespace.org/feedburner/ext/1.0" version="2.0">',
	'<channel>',
		'<title>'.$info['title'].'</title>',
		'<link>'.$info['link'].'</link>',
		'<description>'.$info['description'].'</description>',
		'<lastBuildDate>'.$info['build_date'].'</lastBuildDate>',
		'<sy:updatePeriod>hourly</sy:updatePeriod>',
		'<sy:updateFrequency>1</sy:updateFrequency>',
		'<language>'.$info['language'].'</language>',
		'<generator>'.$info['generator'].'</generator>',
		'<atom10:link xmlns:atom10="http://www.w3.org/2005/Atom" rel="self" type="application/rss+xml" href="'.$info['feed_link'].'" />',
		'<atom10:link xmlns:atom10="http://www.w3.org/2005/Atom" rel="hub" href="http://pubsubhubbub.appspot.com/" />';
		
	foreach ($items as $key => $item)
	{
		echo '<item>',
			'<title>'.html_entity_decode($item['title']).'</title>',
			'<description><![CDATA['.$item['description'].']]></description>',
			'<link>'.$item['link'].'</link>',
			'<pubDate>'.$item['pubDate'].'</pubDate>',
			'<dc:creator>'.$item['creator'].'</dc:creator>',
			'<guid>'.$item['guid'].'</guid>',
		'</item>';
	}
	echo '</channel>',
'</rss>';
