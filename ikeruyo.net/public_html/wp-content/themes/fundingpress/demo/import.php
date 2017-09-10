<?php
global $wpdb;

function rcopy($src, $dst)
{

	if (is_dir($src))
	{
		mkdir($dst);
		$files = scandir($src);
		foreach ($files as $file)
		if ($file != "." && $file != "..") rcopy("$src/$file", "$dst/$file");
	}
	else if (file_exists($src)) copy($src, $dst);
}

$base = dirname(__FILE__);

if (@file_exists(dirname(dirname(dirname(dirname($base))))."/wp-load.php"))
{
	$wpblogheader = (dirname(dirname(dirname(dirname($base)))))."/wp-load.php";
}
else if (@file_exists(dirname(dirname($base))."/wp-load.php"))
{
	$wpblogheader = dirname(dirname($base))."/wp-load.php";
}
else if ($wpblogheader != false)
{
	$wpblogheader = str_replace("\\", "/", $path);
}

//error handling
if ( ! file_exists( $wpblogheader ) ) {
die ( "error" );
} elseif (file_exists($wpblogheader)) {
	require ($wpblogheader);
}


$table_prefix = $wpdb->prefix;
//drop, create and insert data for commentmeta

$siteurl = home_url();

include_once('import_countries.php');
include_once('import_layerslider.php');
include_once('import_options.php');
include_once('import_postmeta.php');
include_once('import_posts.php');
include_once('import_term_relationships.php');
include_once('import_term_taxonomy.php');
include_once('import_terms.php');
include_once('import_user_meta.php');
include_once('import_users.php');

rcopy("uploads/", "../../../uploads/");

echo "success";



?>