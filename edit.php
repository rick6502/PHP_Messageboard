<?php
require ("includes/functions.php");

session_start();
if(!$_SESSION['login'] || !$_SESSION['admin'])
{
    header('Location: login.php');
    exit();
}


$message = '';

if(count($_POST) > 0)	
{
/*
	echo $_SESSION['postID'].'<br/>';
	echo count($_POST).'<br/>';
	 foreach($_POST as $post)
	 {
		 echo $post.'<br/>';
	 }
 */   
	
    $fieldInput = validateFields($_POST);
    $fileInput  = isValidFile($_FILES['file']);

    if($fieldInput != false && $fileInput != false)
    {
        $fieldInput['file'] = $_FILES['file']['tmp_name'];
        updatePost($fieldInput, $_SESSION['postID']);
		header('Location: index.php');
		exit();
    }
    else
    {
        $message = '<div class="alert alert-warning alert-dismissable text-center">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Invalid input!
                    </div>';
    }
}
else
{
	$postID = trim($_GET['id']);
	$postID = filter_var ( $postID, FILTER_SANITIZE_NUMBER_INT);
	$postID = preg_replace("/(\+|-)/", "",$postID);
	
	$postData = getPost($postID);
	
	if($postData)
	{
		$_SESSION['postID']=$postID;
		$_SESSION['firstName']=$postData['firstName'];	
		$_SESSION['lastName']=$postData['lastName'];
		$_SESSION['title']=$postData['title'];	
		$_SESSION['comment']=$postData['comment'];
		$_SESSION['priority']=$postData['priority'];	
	
	}
	else
	{
		$message = '<div class="alert alert-danger text-center">
				Post ID not found!
			</div>';
		// blank out placeholders 	
		$_SESSION['firstName']='';	
		$_SESSION['lastName']='';
		$_SESSION['title']='';	
		$_SESSION['comment']='';
		$_SESSION['priority']=1;		
	}
/*	
	foreach($postData as $post)
	 {
		 echo $post.'<br/>';
	 }
*/ 
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

    <div class="container">

        <div class="row">
            <div class="col-md-6 col-md-offset-3">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-md-offset-3">               
                <?php echo $message; ?>
            </div>
        </div>

	   <form role="form" method="post" action="edit.php" enctype="multipart/form-data">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Edit Post</h4>
				</div>
				<div class="modal-body">
						<div class="form-group">
							<input class="form-control" placeholder="First Name" name="firstName" value="<?php echo $_SESSION['firstName'] ?>">
						</div>
						<div class="form-group">
							<input class="form-control" placeholder="Last Name" name="lastName" value="<?php echo $_SESSION['lastName'] ?>">
						</div>
						<div class="form-group">
							<label>Title</label>
							<input class="form-control" placeholder="" name="title" value="<?php echo $_SESSION['title'] ?>">
						</div>
						<div class="form-group">
							<label>Comment</label>
							<textarea class="form-control" rows="3" name="comment"><?php echo $_SESSION['comment'] ?></textarea>
						</div>
						<div class="form-group">
							<label>Priority</label>
							<select class="form-control" name="priority" value="<?php echo $_SESSION['Priority'] ?>" >
								<option value="1">Important</option>
								<option value="2">High</option>
								<option value="3">Normal</option>
							</select>
						</div>
						<div class="form-group">
							<label>Image</label>
							<input type="file" name="file"  />
						</div>
				</div>
				<div class="modal-footer">
					<input type="submit" class="btn btn-primary" value="Post!"/>
				</div>
			</div><!-- /.modal-content -->
		</form>
	</div>
</div>
	
</body>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</html>