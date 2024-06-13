<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	if(!isset($_SESSION['Dspr'])): header('location: login'); ENDIF;
	$terminal_ID = $_SESSION['terminal_ID'];
?>

<html lang="en">
<head>
   <?php include_once('includes/header.php'); ?>
</head>
<body>

	<div id="wrapper">

		<?php
			include_once('includes/navigation.php'); 
		?>

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
						
							<div class="panel panel-primary">
								<div class="panel-heading">
									Unit Terminal Login Panel
								</div>
								<div class="panel-body">
									<div class="panel-body" id="enterNewID">
										<fieldset>
										<form id="logunitform" autocomplete="off">
											<div class="form-group">
												<h4><span class="DriverIDCheck" id="DriverIDCheck" name="DriverIDCheck"></span></h4>
											</div>
											<div class="form-group">
												<input class="form-control" placeholder="Enter Driver's ID" id="DriverID" name="DriverID" autofocus onkeypress="return isNumberKey(event)">
											</div>
											<button type="submit" class="btn btn-lg btn-success btn-block" id="loginunit" name="loginunit"/>Log-In</button>
										</form>
										</fieldset>
									</div>
								</div>
								<div class="panel-footer">
									<span class='text-center'><small>Tap Driver's ID Card or enter Card number Manually</small></span>
								</div>
							</div>
						</div>
						<!-- /.col-lg-4 -->

						<div class="col-lg-4">
							<div class="panel panel-yellow">
								<div class="panel-heading">
									Available Unit
								</div>
								
								
								<div class="panel-body">
									<div class="list-group" id="newvehiclelist">
										<span class="list-group-item">
											<i class="fa fa-bus fa-1x"></i> Plate No.
											<span class="pull-right medium"><i class="fa fa-user fa-1x"></i> Driver Name</span>
										</span>
										<?php 
											$available_units = mysqli_query($link, "SELECT * FROM ".DB_PREFIX."vehicle_trip_schedule WHERE terminal_ID='".$terminal_ID."' AND selected='0'");
											WHILE($available = mysqli_fetch_array($available_units)){ 
											
											$all_vehicle_ID = $available['vehicle_ID'];		

											$all_unit_query = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM ".DB_PREFIX."vehicles WHERE `unit_ID`='".$all_vehicle_ID."'"));
											$all_driver_ID = $available['driver_ID'];
											$all_plate_no = $all_unit_query['plate_number'];
											$unit_ID = $all_unit_query['unit_ID'];

											$all_driver_info = mysqli_fetch_array(mysqli_query($link, "SELECT fname,lname,suffix FROM ".DB_PREFIX."users WHERE user_ID = '".$all_driver_ID."'"));
										?>
										<span class="list-group-item">
											<a href="unit_login_bridge?unitID=<?= $unit_ID; ?>">
											<span class="text-muted medium" style="color:#003300;"><em><b><?= $all_plate_no; ?></b></em></span>
											<span class="pull-right medium" style="color:#003300;">
												<em>
													<b>
														<span class="fname"><?= $all_driver_info['fname']; ?></span>
														<span class="lname"><?= $all_driver_info['lname']; ?></span>
														<?php IF($all_driver_info['suffix'] != ""): echo ", ".$all_driver_info['suffix']; ENDIF; ?>
													</b>
												</em>
											</span>
											</a>
										</span>
											<?php } ?>

									</div>
								</div>
								<div class="panel-footer">
								</div>
							</div>
						</div>
						<!-- /.col-lg-4 -->
						
						
						<div class="col-lg-4">
						
							<div class="panel panel-primary">
								<div class="panel-heading">
									Release all unit(s)
								</div>
								<div class="panel-body">
									<div class="panel-body" id="enterNewID">
										<fieldset>            
											<button type="button" data-toggle="modal" data-target="#releaseallunit" class="btn btn-lg btn-success btn-block" id="releaseunit" name="releaseunit"/>Release all Unit(s)</button>
										</fieldset>
									</div>
								</div>
								<div class="panel-footer"></div>
							</div>
							<div class="12u(xsmall)" style="color:#0000FF;" id="make-displaymess">
									<span class="releasemessage" id="releasemessage"></span>
								</div>
							<div class="modal fade" id="releaseallunit" tabindex="-1" role="dialog" aria-labelledby="ReleaseUnitmod" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											<h4 class="modal-title" id="ReleaseUnitmodmod">Release all unit?</h4>
										
											<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
											<button type="submit" class="btn btn-primary" id="releaseallloggedunit" name="releaseallloggedunit" data-dismiss="modal">Yes</button>
										</div>
									</div>
								</div>
							</div>
							
						</div>
						<!-- /.col-lg-4 -->
					</div>

                </div><!-- /.container-fluid -->
            </div><!-- /#page-wrapper -->

	</div>

	<!-- jQuery -->
    <script src="js/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="js/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/startmin.js"></script>

<!-- DataTables JavaScript -->
        <!-- Page-Level Demo Scripts - Tables - Use for reference -->
        <script>
			// NUMBERS ONLY
			function isNumberKey(evt)
			{
				var charCode = (evt.which) ? evt.which : event.keyCode
				if (charCode > 31 && (charCode < 48 || charCode > 57))
				return false;
				return true;
			}
		
            $(document).ready(function() {
				//Assign
				$('#logunitform').submit(function (e) {

					// Prevent form submission which refreshes page
					e.preventDefault();

					// Serialize data
					var formData = $(this).serialize();

					var dID = $("#DriverID").val();
					var IDcount = $("#DriverID").val().length;
					if(dID == ''|| dID == 0 || IDcount < 10){
						$('#DriverIDCheck').html("<span class='text-warning text-center'>Invalid Driver ID</span>");
					}
					else{
						$.post('trigger/exec', { 'dID': dID, loginunit: 1}, function(data) {
							var checkdata = data;

							if(checkdata == 1){
								$('#DriverIDCheck').html("<span class='text-warning'>Driver Already Logged-in!</span>");
							}
							else{
								$('#DriverIDCheck').html(data);
								$('#DriverID').val('');
								$("#DriverID").focus();
								$("#newvehiclelist").load("components/new_logged_unit");
							}
						});
					}
				});
				
				$("#releaseallloggedunit").click(function(){
					$.post("trigger/updateunitform",{releaseit: 1},
						function(data) {
							$('#releasemessage').html(data);
							$('#releasemessage').delay().fadeIn();
							$('#releasemessage').delay(3000).fadeOut();
						}
					);
					$(".make").text($("#make").val());
				});
            });
        </script>
</body>
</html>
