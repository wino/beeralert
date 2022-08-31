<?php
header('Content-type: application/xml');
date_default_timezone_set('America/New_York');
include('bars.php');

$date = date(DateTime::RFC2822);
$guid = time();
$rsshead = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
<rss version=\"2.0\">
<channel>
<title>All Beers On Tap</title>
<link>http://renttopsail.com/beeralert/allbeerfeed.php?id=$guid</link>
<description>All Beers On Tap</description>
<language>en-us</language>
<lastBuildDate>$date</lastBuildDate>
";
$rssfoot = "</channel>
</rss>";
$rssall = $rsshead.'';

foreach ($bars as $bar => $name) {
	include('beers.php');
	$prevfile = strtolower($bar).'prevall';
	$prev = file_get_contents('prev/'.$prevfile);
	$prev = explode("\n", chop($prev));
	$new = array_diff($beers, $prev);
	$old = array();

	if (!count($new) && !count($old)) {
		$frss = file_get_contents('prev/'.$prevfile.'.rss');
		$rssall .= $frss."\n";
		continue;
	}

	$f = fopen('prev/'.$prevfile, 'w');
	fputs($f, implode("\n", $beers));

	$msg = '';
	if (count($new)) {
		$msg .= $name.':'.implode(", ", $new);
	}
	if (count($old)) {
		$msg .= " Gone: ";//<br>";//\n";
		$msg .= implode(", ", $old);
	}

	$rss = "<item>
<guid isPermaLink=\"false\">$guid</guid>
<title><![CDATA[$bar on $date]]></title>
<description><![CDATA[$msg]]></description>
<pubDate>$date</pubDate>
<link>http://renttopsail.com/beeralert/allbeerfeed.php?id=$guid</link>
</item>
";
	$rssall .= $rss;
	$frss = fopen('prev/'.$prevfile.'.rss', 'w');
	fputs($frss, $rss);
	fclose($frss);
}

$rssall .= "</channel>
</rss>";

echo $rssall;
?>
