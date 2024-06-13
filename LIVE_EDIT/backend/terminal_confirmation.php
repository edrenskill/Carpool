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
                            <h1 class="page-header">New Terminal Confirmation</h1>
                        </div><!-- /.col-lg-12 -->
						
						<div class="col-lg-4" style="width:275">
							<div class="panel panel-primary">
								<div class="panel-heading">
									New Terminal Has Been Added
								</div>
								<div class="panel-body">
								<?php
									IF (!isset($_SESSION['TID'])):
										echo "No new Terminal added!<br/>Please Click <a href='add_terminal'><i class='fa fa-road'></i> Add New Terminal</a>";
									ELSE: ?>
										Dispatcher ID Number :<span style="color:#FF0000"><?= $_SESSION['DID']; ?></span><br/>
										Terminal ID Number :<span style="color:#FF0000"><?= $_SESSION['TID']; ?></span><br/>
										Terminal Name :<span style="color:#FF0000"><?= $_SESSION['TName']; ?></span><br/>
										Trip Origin :<span style="color:#FF0000"><?= $_SESSION['TOrigin']; ?></span><br/>
										Trip Destination :<span style="color:#FF0000"><?= $_SESSION['TDestination']; ?></span><br/>
										Trip Fare :<span style="color:#FF0000"><?= $_SESSION['TFare']; ?></span><br/>
										Trip Terminal Charge :<span style="color:#FF0000"><?= $_SESSION['TCharge']; ?></span><br/>
										Trip Initial Charge :<span style="color:#FF0000"><?= $_SESSION['initial']; ?></span><br/>
										Member Incentive :<span style="color:#FF0000"><?= $_SESSION['TIncentive']; ?></span><br/>
									<?php
									ENDIF;
									?>
								</div>
								<div class="panel-footer">
									<?php
										IF (isset($_SESSION['TID'])):
											unset($_SESSION['DID']); unset($_SESSION['TID']); unset($_SESSION['TName']); unset($_SESSION['TOrigin']); unset($_SESSION['TDestination']); unset($_SESSION['TFare']); unset($_SESSION['TCharge']); unset($_SESSION['initial']); unset($_SESSION['TIncentive']);
											echo '<a href="add_terminal"><i class="fa fa-road"></i> Add Another Terminal</a>';
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
