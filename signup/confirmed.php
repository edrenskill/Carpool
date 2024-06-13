<?php 	
require_once '../settings/connect.php';
if(session_id() == '') { session_start(); } // START SESSIONS 
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include_once("includes/header.php"); ?>
    </head>
    <body>

        <div id="wrapper">

            <?php include_once("includes/nav.php"); ?>

            <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <h1 class="page-header">Signup Confirmation</h1>
                        </div>
                        <!-- /.col-lg-12 -->
                    </div>
					
					<?php IF(isset($_SESSION['act_ID'])): ?>
					
						<div class="row">
							<h3>Congratulation!</h3>
							<p>You are now a candidate to become a member of Carpool Express Co.</p>
							<p>Activate your account by availing CE card from any Pick-up/Drop-off points near you.</p> 
							<div style="display:table">
								<div id="artlabel" style="width:300px"><h4>Your Temporary Account ID Number:</h4></div><div id="artdetail" style="margin-right:20px"><h4><span style="color:#FF0000"><?php echo $_SESSION['act_ID']; ?></span></h4></div>
							</div>
							<hr />
							<form name="proceed" id="proceed" method="post" action="../myaccount/logprocess">
								<div style="display:none">
									<input name="uname" type="text" id="txtbox" value="<?php echo $_SESSION['uname']; ?>" />
									<input name="pword" type="password" id="txtbox" value="<?php echo $_SESSION['pass']; ?>" />
								</div>
								<div id="artdetail">
									<h4>Already have CE Card? Click <button type="submit" class="btn btn-primary btn-sm" value="Continue" id="Login" name="Login" />Continue</button> to Activate your account.</h4>
								</div>
								<?php unset($_SESSION['uname']); unset($_SESSION['pass']); unset($_SESSION['fname']);?>
							</form>	
						</div>
					
					<?php ELSE: ?>
						<div class="row">
							<h2>Your session has expired!</h2>
						</div>
					<?php ENDIF; ?>
					
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /#page-wrapper -->

        </div>
        <!-- /#wrapper -->

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
