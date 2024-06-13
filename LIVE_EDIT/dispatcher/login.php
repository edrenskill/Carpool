<?php
	include '../settings/connect.php';
	if(session_id() == '') { session_start(); } // START SESSIONS
	
		IF(Dispatcher()): 
			IF($_SESSION['Dspr']): header ('location: index'); ENDIF;
		ELSE: header ("location: ../"); ENDIF;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
       <?php include_once('includes/header.php'); ?>
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
                            <h3 class="panel-title">Dispatcher Authentication</h3>
							<?php // Display error message
								if(!empty($_SESSION['error_msg']))  { echo "<p style=\"color:#FF0000; font-size: 14px; padding: 30px 5px 0 20px\" id=\"message\">".$_SESSION['error_msg']."</p>"; }
								unset($_SESSION['error_msg']);
							?>
                        </div>
                        <div class="panel-body">
                            <form role="form" action="logprocess" method="post" name="adminform" id="adminform">
                                <fieldset>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="User name/E-mail" name="username" type="username" autofocus>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                    </div>
                                    <!-- Change this to a button or input when using this as a form -->
									<input type="submit" class="btn btn-lg btn-success btn-block" value="Login" id="Login" name="Login" />
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- jQuery -->
        <script src="js/jquery.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="js/bootstrap.min.js"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="js/metisMenu.min.js"></script>

        <!-- Custom Theme JavaScript -->
        <script src="js/startmin.js"></script>

    </body>
</html>