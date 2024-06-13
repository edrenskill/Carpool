<?php 
	include '../settings/connect.php';
	session_start();
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
                            <h1 class="page-header">New Unit Confirmation</h1>
                        </div><!-- /.col-lg-12 -->
						
						<div class="col-lg-4" style="width:275">
							<div class="panel panel-primary">
								<div class="panel-heading">
									New Unit Has Been Added
								</div>
								<div class="panel-body">
								<?php
									IF (!isset($_SESSION['plate'])):
										echo "No New Unit added!<br/>Please Click <a href='add_vehicle'><i class='fa fa-bus'></i> Add New Unit</a>";
									ELSE: ?>
										Owner ID Number :<span style="color:#FF0000"><?= $_SESSION['v_owner']; ?></span><br/>
										Driver ID Number :<span style="color:#FF0000"><?= $_SESSION['v_driver']; ?></span><br/>
										Plate Number :<span style="color:#FF0000"><?= $_SESSION['plate']; ?></span><br/>
										Seating Capacity :<span style="color:#FF0000"><?= $_SESSION['capacity']; ?></span><br/>
										Make / Brand :<span style="color:#FF0000"><?= $_SESSION['make']; ?></span><br/>
										Year Model :<span style="color:#FF0000"><?= $_SESSION['model']; ?></span><br/>
										Chassis Number :<span style="color:#FF0000"><?= $_SESSION['chassis']; ?></span><br/>
										Engine Number :<span style="color:#FF0000"><?= $_SESSION['engine']; ?></span>
									<?php
									ENDIF;
									?>
								</div>
								<div class="panel-footer">
									<?php
										IF (isset($_SESSION['plate'])):
											unset($_SESSION['v_owner']); unset($_SESSION['v_driver']); unset($_SESSION['plate']); unset($_SESSION['make']); unset($_SESSION['model']); unset($_SESSION['chassis']); unset($_SESSION['capacity']); unset($_SESSION['engine']);unset($_SESSION['act_no']);unset($_SESSION['dact_no']);
											echo '<a href="add_vehicle"><i class="fa fa-bus"></i> Add Another Unit</a>';
										ENDIF;
									?>
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
