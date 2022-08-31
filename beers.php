<?php
date_default_timezone_set('America/New_York');
libxml_use_internal_errors(true); // libxml catches errors instead of throwing
$beers = array();
switch ($bar) {
case 'TBC':
	$x = @file_get_contents('http://tastybeverageco.com');
	$doc = new DOMDocument;
	$doc->loadHTML($x);
	$xp = new DomXPath($doc);
	$el = $doc->getElementById('DraftMenu');
	$xml = simplexml_import_dom($el);
	foreach ($xml->ul->li as $r) {
		$brew = trim($r->span[0]->h4);
		$brewery = trim($r->span[0]->p[0]);
		//$style = trim($r->span[1]->p);
		$beers[] = "$brewery $brew";// $style"; 
	}
	break;
case 'TBP':
/* can't hit url?
	//$x = @file_get_contents('https://www.trophybrewing.com/morgan-draft-list/');
	$x = fakeget('https://www.trophybrewing.com', '/morgan-draft-list/');
var_dump($x);
	$doc = new DOMDocument;
	$doc->loadHTML($x);
	$xp = new DomXPath($doc);
	$el = $xp->query("//div[contains(@class, 'menu-items')]");
	$xml = simplexml_import_dom($el->item(0));
var_dump($xml);
	foreach ($xml->div as $div) {
		$beers[] = (string)$div->div->a[0][0];
	}
*/
	break;
case 'TBM':
/* images
	$x = @file_get_contents('http://trophybrewing.com/trophy-brewing-on-maywood-tap-list/');
	$doc = new DOMDocument;
	$doc->loadHTML($x);
	$xp = new DomXPath($doc);
	$el = $xp->query("//div[contains(@class, 'wp-cpl-sc-wrap')]");
	$xml = simplexml_import_dom($el->item(0));
	foreach ($xml->div as $div) {
		if (!$div->h2) continue;
		$beers[] = (string)$div->h2->a[0][0];
	}
*/
	break;
case 'SOB':
	/*$x = @file_get_contents('https://docs.google.com/document/d/e/2PACX-1vTVdrd54qD40Uvt4Y9RMM8CTWhjHCdKSEzcf5SsLTJIUvbmcj5COQ8rqGtLtKe8BX4QaiGHOP8fzb-g/pub');
	$doc = new DOMDocument;
	$doc->loadHTML($x);
	$xp = new DomXPath($doc);
	//$els = $xp->query("//span[contains(@class, 'c2')]");
	$el = $xp->query("//div[@id='contents']");//span[contains(@class, 'c2')]");
	$xml = simplexml_import_dom($el->item(0));
	$all = $names = $descs = array();
	foreach ($xml->p as $span) {
		if (strpos($span->span, 'Draft List') !== false || $span->span == '') continue;
		if ($span->span == 'Wine List') break;
		$all[] = $span->span;
	}
	for ($i=0; $i<count($all); $i++) {
		if ($i % 2 == 0) $names[] = $all[$i];
		else $descs[] = $all[$i];
	}
	for ($i=0; $i<count($names); $i++) {
		$beers[] = $names[$i].' - '.@$descs[$i];
	}*/
	break;
default:
	return;
}
$beers = array_unique($beers);
sort($beers);

function fakeget($url, $path)
{
/*
	$f = fsockopen($url, 80);
	$header = "GET $path HTTP/1.1\r\n".
		"Host: $url\n". "User-Agent: Mozilla\r\n".
		"Content-Type: application/x-www-form-urlencoded\r\n".
		"Content-Length: 0\r\n\r\n\r\n";
	fputs($f, $header);
	$x = '';
	while (!feof($f)) {
		$s = fgets($f, 4096);
		$x .= $s; 
		if (strpos($s, '</html>') !== false) break;
	}
	fclose($f);
*/
echo $url.$path."<br>\n";
return '';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url.$path);
	curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
	$x = curl_exec($ch);
var_dump(curl_error($ch));
	curl_close($ch);
	return $x;
}
