<?php
require ("includes/functions.php");


$phoneNumber = '';

if(isset($_POST['remember']) && $_POST['remember'] == 1)
{
    setcookie('phoneNumber', $_POST['phoneNumber'], time() + 60 * 60 * 24 * 20);    // 60 seconds, 60 minutes, 24 hours, 20 days
    $phoneNumber = $_POST['phoneNumber'];
}
elseif(isset($_COOKIE['phoneNumber']))
{
    $phoneNumber = $_COOKIE['phoneNumber'];
}

if(isset($_POST['phoneNumber']) && !isset($_POST['remember']))
{
    setcookie('phoneNumber', null, time() - 3600);
    $phoneNumber = '';
}


$message = '';

if(count($_POST) > 0)
{
	$loginData = checkLogin($_POST['phoneNumber'], $_POST['password']);

	if ($loginData != false)
	{
       session_start();
		if ($loginData['admin'] === true)
		{
			$_SESSION['admin'] = true;
		}
		else
		{
			$_SESSION['admin'] = false;
		}
        $_SESSION['login'] = true;
        $_SESSION['firstname'] = $loginData['firstname'];
        $_SESSION['lastname'] = $loginData['lastname'];
        header('Location: index.php');
        exit();
	}
	else
	{
		$_SESSION['login'] = false;
        $message = '<div class="alert alert-danger text-center">
                        Invalid login!
                    </div>';
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

    <div class="container">

        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <h1 class="login-panel text-center text-muted">Message Board Project</h1>
				<?php echo $message; ?>
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Please Sign In</h3>
                    </div>
                    <div class="panel-body">
                        <form name="login" role="form" action="login.php" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control"
                                           value="<?php echo $phoneNumber;?>"
                                           name="phoneNumber"
                                           placeholder="Phone Number"
                                           type="text"
                                        <?php echo empty($phoneNumber) ? 'autofocus' : ''; ?>
                                    />
                                </div>
                                <div class="form-group">
                                    <input class="form-control"
                                           name="password"
                                           placeholder="Password"
                                           type="password"
                                        <?php echo empty($phoneNumber) ? '' : 'autofocus'; ?>
                                    />
                                </div>
                                <div class="form-group">
                                    <input type="checkbox"
                                           value="1"
                                           name="remember"
                                        <?php echo empty($phoneNumber) ? '' : 'checked'; ?>
                                    />
                                    Remember Me
                                </div>
                                <input type="submit" class="btn btn-lg btn-success btn-block" value="Login"/>
                            </fieldset>
                        </form>
                    </div>
                </div>
                <a class="btn btn-sm btn-default" href="signup.php">Sign Up</a>
            </div>
        </div>

    </div>
</div>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
