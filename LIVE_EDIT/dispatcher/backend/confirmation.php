<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	if(!isset($_SESSION['ADM'])): header('location: login'); ENDIF;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include ('includes/header.php'); ?>
    </head>
    <body>

        <div id="wrapper">

            <?php include ('includes/navigation.php'); ?>

            <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <h1 class="page-header">New Member Confirmation</h1>
                        </div><!-- /.col-lg-12 -->
						
						<div class="col-lg-4" style="width:275">
							<div class="panel panel-primary">
								<div class="panel-heading">
									New Member Has Been Added
								</div>
								<div class="panel-body">
									Account ID Number :<span style="color:#FF0000"><?php echo $_SESSION['act_no']; ?></span>
									Account Name :<span style="color:#FF0000"><?php echo $_SESSION['fname']; ?></span>
								</div>
								<div class="panel-footer">
									<?php unset($_SESSION['uname']); unset($_SESSION['pass']); unset($_SESSION['fname']);?>
									<a href="application"><i class="fa fa-download"></i> Add Another Account</a>
									
								</div>
							</div>
						</div>					
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div><!-- /#page-wrapper -->

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
