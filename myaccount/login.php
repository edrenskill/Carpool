<?php
	include '../settings/connect.php';
	if(session_id() == '') { session_start(); } // START SESSIONS
	IF($_SESSION['user_id']): header('location: ../myaccount/'); ENDIF;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
		<?php include_once("includes/header.php"); ?>
    </head>
    <body>
	
	<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<!-- Top Navigation: Left Menu -->
        <ul class="nav navbar-nav navbar-left navbar-top-links">
            <li><a href="../"><i class="fa fa-home fa-fw"></i> Home Index</a></li>
        </ul>
	</nav>

        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="login-panel panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">User Login</h3>
							<?php // Display error message
								if(!empty($_SESSION['error_msg']))  { echo "<p style=\"color:#FF0000; font-size: 14px; padding: 30px 5px 0 20px\" id=\"message\">".$_SESSION['error_msg']."</p>"; }
								unset($_SESSION['error_msg']);
							?>
                        </div>
                        <div class="panel-body">
                            <form role="form" action="logprocess" method="post" name="login" id="login">
                                <fieldset>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="User name/E-mail" name="uname" id="uname" type="username" autofocus>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Password" name="pword" id="pword" type="password" value="">
                                    </div>
                                    <div class="checkbox" style="float:left">
                                        <label>
                                            <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                        </label>
                                    </div>
									<div style="float:right">
                                        <label>
                                            <a href="../signup/register">Register</a>
                                        </label>
                                    </div>
                                    <!-- Change this to a button or input when using this as a form -->
									<input type="submit" class="btn btn-lg btn-success btn-block" value="Login" id="Login" name="Login" />
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
			<?php include_once("includes/footer.php"); ?>
        </div>
	  </div>
	

        <!-- jQuery -->
        <script src="../dispatcher/js/jquery.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="../dispatcher/js/bootstrap.min.js"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="../dispatcher/js/metisMenu.min.js"></script>

        <!-- Custom Theme JavaScript -->
        <script src="../dispatcher/js/startmin.js"></script>

    </body>
</html>
