<?php
header('Content-type: application/xml');
if (!isset($bar)) {
	return;
}

include('beers.php');
$prevfile = strtolower($bar).'prev';
$prev = file_get_contents('prev/'.$prevfile);
$prev = explode("\n", chop($prev));
$new = array_diff($beers, $prev);
$old = array(); //$bar == 'BR' ? array_diff($prev, $beers) : array();

$date = date(DateTime::RFC2822);
$guid = time();

$header = '<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0">
<channel>
';
$header .= "<title>$bar On Tap</title>
<link>http://renttopsail.com/beeralert/{$bar}feed.php?id=$guid</link>
<description>$bar On Tap</description>
<language>en-us</language>
<lastBuildDate>$date</lastBuildDate>
";
$footer = '</channel>
</rss>';
if (!count($new) && !count($old)) {
	$frss = fopen('prev/'.$prevfile.'.rss', 'r');
	fpassthru($frss);
	fclose($frss);
	exit;
}

$f = fopen('prev/'.$prevfile, 'w');
fputs($f, implode("\n", $beers));

$msg = '';
if (count($new)) {
	//$msg .= "New: ";//<br>";//\n";
	$msg .= implode(", ", $new);
}
if (count($old)) {
	//$msg .= "\n\n";
	$msg .= " Gone: ";//<br>";//\n";
	$msg .= implode(", ", $old);
}

$rss = $header;
$rss .= "<item>
<guid isPermaLink=\"false\">$guid</guid>
<title><![CDATA[$bar on $date]]></title>
<description><![CDATA[$msg]]></description>
<pubDate>$date</pubDate>
<link>http://renttopsail.com/beeralert/{$bar}feed.php?id=$guid</link>
</item>
";
$rss .= $footer;

echo $rss;
$frss = fopen('prev/'.$prevfile.'.rss', 'w');
fputs($frss, $rss);
fclose($frss);
?>
