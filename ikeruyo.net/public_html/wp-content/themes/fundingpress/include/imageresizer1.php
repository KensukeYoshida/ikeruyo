<?php
require_once('../../../../wp-load.php');
 $holder = wp_upload_dir();

$uploaddir = $holder['path']."/";
if (strpos($_POST['file'], '.png') !== false) {
	//it's a png
	$image = imagecreatefrompng($_POST['file']);
} else {
	//it's a jpg
	$image = imagecreatefromjpeg($_POST['file']);
}
$fileexplode =  explode ("/", $_POST['file']);

$dest_image = $uploaddir.'cropped_'.$fileexplode[count($fileexplode) - 1];

$x = $_POST['x'];
$x2 = $_POST['x2'];
$y = $_POST['y'];
$y2 = $_POST['y2'];

$currwidth = round(abs($x - $x2));
$currheight = round(abs($y - $y2));

if ($x < $x2) {
	$usex = $x;
} else {
	$usex = $x2;
}


if ($y < $y2) {
	$usey = $y;
} else {
	$usey = $y2;
}
$img = imagecreatetruecolor(250, 250);
imagecopyresampled($img,$image, 0,0, $usex, $usey, 250, 250, $currwidth, $currheight);
if (strpos($_POST['file'], '.png') !== false) {
	//it's a png
	imagepng ($img, $dest_image, 0);
} else {
	//it's a jpg
	imagejpeg($img, $dest_image, 85);
}
imagedestroy($img);
unlink ($_POST['file']);
echo $holder['url']."/".'cropped_'.$fileexplode[count($fileexplode) - 1]."_-*-_".$dest_image;
?>