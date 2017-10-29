<?php
require ("includes/functions.php");

session_start();
if(!$_SESSION['login'] || !$_SESSION['admin'])
{
    header('Location: login.php');
    exit();
}

$usersData = [];
$usersData = getUsersData();
$count = count($usersData);

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
                <h3 class="login-panel text-center text-muted">Users</h3>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <a href="/search.php" class="btn btn-default"><i class="fa fa-arrow-circle-left"> </i> Back</a>
                <hr/>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-md-offset-3">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        Users
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>DOB</th>
                                </tr>
                                </thead>	
                                <tbody>
									<?php
									 foreach($usersData as $user)
                                        {
											echo '<tr>';
											echo '<td>'.$user['firstName'].'</td>';
											echo '<td>'.$user['lastName'].'</td>';
											echo '<td>'.$user['dob'].'</td>';
											echo '</tr>';
										}
									?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->

            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <p class="text-center text-muted">
                    Total users: <?php echo $count ?>
                </p>
            </div>
        </div>

    </div>
</div>

</body>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</html>
