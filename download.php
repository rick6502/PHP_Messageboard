<?php
require ("includes/functions.php");

if(isset($_GET['name']))
{
	$name = $_GET['name'];

	if(file_exists('uploads/' . $name))
	{
		header('Content-Disposition: attachment; filename=".'.$name.'"');	
		$image = imagecreatefromjpeg('uploads/' . $name);
		imagejpeg($image);

		imagedestroy($image);
		exit();

	}
}
else
{
	echo 'File not found';
}
