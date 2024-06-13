<?php
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	if(!isset($_SESSION['Dspr'])): header('location: ../'); ENDIF;
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include_once('includes/header.php'); ?>
	</head>
    <body>

        <div id="wrapper">

            <?php include ('includes/navigation.php'); ?>

            <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">

                            <h1 class="page-header">Commuter Terminal</h1>

                        </div><!-- /.col-lg-12 -->
                    </div><!-- /.row -->
					<div class="row">
						<div class="col-lg-4">
							<div class="panel panel-green">
								<div class="panel-heading">
									Loading Bay status
								</div>
								<div class="panel-body">
									<div class="list-group" id="vehicle_dispatch">
										<h3>Loading Bay is still occupied by other unit</h3>
									</div>
								</div>
								<div class="panel-footer">
								<form action="boarding" method="post">
								<button type="submit" id="dispatch" name="dispatch" class="btn btn-primary btn-lg btn-block">Continue<i class="fa fa-road"></i></button>
								</form>
								</div>
							</div>
						</div>
						<!-- /.col-lg-4 -->
					</div>

                </div><!-- /.container-fluid -->
            </div><!-- /#page-wrapper -->

        </div><!-- /#wrapper -->

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
