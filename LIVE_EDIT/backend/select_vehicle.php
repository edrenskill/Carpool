<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	IF(!isset($_SESSION['ADM'])): header('location: login'); ENDIF;

	foreach($_POST as $key => $value) { $data[$key] = filter($value); }	
	
	$_SESSION['member_batch'] = "";
	$err = "";
	
	IF(isset($_POST) && array_key_exists('submitunit',$_POST)):

		IF(!isset($data['unitID']) || $data['unitID'] == ""):
			$err = "Please enter Valid Unit ID";
		ELSE:
				$unitID = $data['unitID'];
				
				$query = "SELECT COUNT(*) AS `vehicle` FROM `".DB_PREFIX."vehicles` WHERE unit_ID='{$unitID}'";
				$row = mysqli_fetch_array(mysqli_query($link,$query));
				$totalv = $row['vehicle'];
				
				IF($totalv):
					$_SESSION['vehicleID'] = $data['unitID'];

					HEADER('Location: vehicle_details');
					EXIT();
				ELSE:
					$err = "No record on file";
				ENDIF;
		ENDIF;
	ENDIF;

?>

<html lang="en">
<head>
   <?php include_once('includes/header.php'); ?>

	<script type="text/javascript" src="../profile/assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="../profile/assets/js/jquery.form.min.js"></script>
		
</head>
<body>

	<div id="wrapper">

		<?php include_once('includes/navigation.php'); ?>
		
		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">Select Vehicle ID</h1>
				</div><!-- /.col-lg-12 -->
			</div><!-- /.row -->

			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="col-lg-4" style="width:275">
								<div class="panel panel-primary">
									<div class="panel-heading">
										Enter Vehicle ID
									</div>
									<div class="panel-body">
										<form action="select_vehicle" method="post" enctype="multipart/form-data" id="VehicleForm">
											<div class="panel-body" id="enterVehicleID">
												<fieldset>
													<div class="form-group">
														<input class="form-control" type="text" name="unitID" id="unitID" value="" PLACEHOLDER="Vehicle Unit ID">
													</div>
													<button class="btn btn-lg btn-primary btn-block" type="submit"  id="submitunit" name="submitunit" value="submitunit" />Continue</button>
												</fieldset>
											</div>
										</form>
									</div>
									<div class="panel-footer">
										<div id="output" align="center">
											<?php IF(isset($err)): echo $err; ENDIF; ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div><!-- /#page-wrapper -->
		</div>
	</div>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="js/metisMenu.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="js/startmin.js"></script>
<script src="js/dataTables/dataTables.bootstrap.min.js"></script>

</body>
</html>