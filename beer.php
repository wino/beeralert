<?php

$bar = @$_GET['bar'];
switch ($bar) {
case '':
	include('bars.php');
	foreach ($barheights as $bar => $height) {
		$links .= '<a href="#'.$bar.'">'.$bar.'</a> ';
		$iframes .= '<a name="'.$bar.'"><iframe src="beer.php?bar='.$bar.'" width="500" height='.$height.'></iframe>'."\n";
		}
	echo "$links<br>\n\n$iframes";
	break;
default:
	include('beers.php');
	$beers = implode('<br>', $beers);
	echo "<b>$bar</b><br>$beers<br><br>";
	break;
}

echo '
</body>
</html>
';
return;

switch ($bar) {
case '':
	$bars = array(
		'trophy' => 'Trophy',
		'tasty' => 'Tasty',
		//'times' => 'Times',
		'state' => 'State of Beer',
	);
	$links = $iframes = '';
	foreach ($bars as $aname => $name) {
		$links .= '<a href="#'.$aname.'">'.$name.'</a> ';
		$iframes .= '<a name="'.$aname.'"><iframe src="'.($aname == 'times2' ? 'http://www.raleightimesbar.com/sites/times/files/specials_block/menus/Full%20Draft%20Menu.pdf' : 'beer.php?bar='.$aname).'" width="600" height='.(in_array($aname, array('br', 'bb', 'stag', 'times')) ? 350 : 160).'></iframe>'."\n";
	}
	echo "$links<br>\n\n$iframes";
	break;

case 'trophy':
	echo '<h3>Trophy</h3>'."\n";
	$x = file_get_contents('http://www.trophybrewing.com');
	$pos = strpos($x, '<ul class="wp-cpl-widget');
	$pos2 = strpos($x, '</ul>', $pos+20);
	$x = substr($x, $pos+41, $pos2-$pos-36);
	$x = preg_replace('#<img width="50" height="50" src="http://trophybrewing.com/wp/wp-content/uploads/2013/(.*).png" class="attachment-wp-cpl-post-thumb wp-post-image" alt="(.*)" />#', '', $x);
	$x = preg_replace('#<li class="wp-cpl wp-cpl-(even|odd)"><span class="wp-thumb-overlay"><span class="thumb_lay"><a href="http://trophybrewing.com/(.*)/"></a></span></span>#', '', $x);
	$x = str_replace('</li>', '<br>', $x);
	$x = str_replace('<a ', '<a target="_blank"', $x);
	echo $x;
	break;

case 'tasty':
	echo '<h3><a target="_blank" href="http://dtr.tastybeverageco.com/">Tasty</a></h3>'."\n";
	$x = file_get_contents('http://dtr.tastybeverageco.com/');
	$pos = strpos($x, '<ul>', strpos($x, 'On Tap Today'));
	$pos2 = strpos($x, '</ul>', $pos);
	$x = substr($x, $pos, $pos2-$pos);
	$x = str_replace('<span', '<td', $x);
	$x = str_replace('</span>', '</td>', $x);
	$x = str_replace('<li>', '<tr>', $x);
	$x = str_replace('</li>', '</tr>', $x);
	$x = str_replace('<ul>', '<table>', $x);
	$x = str_replace('</ul>', '</table>', $x);
	echo $x;
	/*
	$a = split('table>', $x);
	//echo '<table>'.$a[1];//.'table>';

	$tr = split('<tr', substr($a[1], 0, -2));
	for ($i=1; $i<count($tr); $i++) {
		$td = split('<td', $tr[$i]);
		$name = split('h5>', $td[1]);
		$name = substr($name[1], 0, -2);
		$style = split('p>', $td[2]);
		$style = substr($style[1], 0, -2);
		$perc = split('p>', $td[3]);
		$perc = substr($perc[1], 0, -2);
		echo "$name - $style ($perc)<br>\n";
	}
	*/
	break;

case 'times':
	//src above
	//echo '<h3>Times</h3>'."\n";
	//echo '<a href="http://www.raleightimesbar.com/sites/times/files/specials_block/menus/Full%20Draft%20Menu.pdf">drafts pdf</a>';
	$im = new imagick();
	$im->readImage('http://www.raleightimesbar.com/sites/times/files/specials_block/menus/Full%20Draft%20Menu_0.pdf');
	$im->setIteratorIndex(0);
	$im->setImageFormat('jpg');
	header('Content-Type: image/jpeg');
	echo $im;
	break;

case 'state':
	echo '<h3><a href="http://stateof.beer/">State of Beer</a></h3>';
	echo '<h3><a href="https://www.facebook.com/pages/Ridgewood-Wine-Beer-Company/169374169835018?sk=app_350090741787212">Ridgewood FB Tap List</a></h3>';
	echo '<h3><a href="https://www.facebook.com/VillageDraftHouse/app_208195102528120">Village Draft House FB Tap List</a></h3>';
	break;
}
?>
</body>
</html>
