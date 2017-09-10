<?php

require_once('../../../../wp-load.php');
$file = $_POST['file'];
$holder = wp_upload_dir();
if ((substr($file, -4, 4) == ".jpg") OR (substr($file, -4, 4) == ".png") OR (substr($file, -5, 5) == ".jpeg")) {
	//it is an image
	//check path
	$thelen = strlen($holder['path']);
	if (substr($file, 0, $thelen) == $holder['path']) {
		//in correct path
		if (unlink($file)) {
			//file unlink success
			echo "ok";
		} else {
			echo "nop";
		}
	}


}


?>