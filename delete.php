<?php
require ("includes/functions.php");

$message = 'Selected post was not found.';

session_start();
if(!$_SESSION['login'] || !$_SESSION['admin'])
{
    header('Location: login.php');
    exit();
}

if (count($_GET) > 0)
{
	$postID = trim($_GET['id']);
	$postID = filter_var ( $postID, FILTER_SANITIZE_NUMBER_INT);
	$postID = preg_replace("/(\+|-)/", "",$postID);

	$deleteDone = deletePost($postID);
	if($deleteDone === true)
	{
		$message = 'Post ID #'.$postID.' was deleted.';
	}
	else
	{
		$message = 'Post ID #'.$postID.' was not found.';
	}
}
	

?>
<!DOCTYPE html>
<html>
<head>
    <title>Message Board Project</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<div id="wrapper">
	<h1> <?php echo $message ?> </h1>
	<div >
		<a class="btn btn-sm btn-default" href="search.php">Search</a>
	</div>
</div>


</body>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</html>
