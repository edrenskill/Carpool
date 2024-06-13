<?php
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	if(!isset($_SESSION['Dspr'])): header('location: ../'); ENDIF;

		$terminal_ID = $_SESSION['terminal_ID'];

		//SELECT CURRENT UNIT DATA
		$selected_unit = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM ".DB_PREFIX."vehicle_trip_schedule WHERE terminal_ID='".$terminal_ID."' AND selected='1'"));
		$available_units = mysqli_query($link, "SELECT * FROM ".DB_PREFIX."vehicle_trip_schedule WHERE terminal_ID='".$terminal_ID."' AND selected='0'");
		$selected_vehicle_ID = $selected_unit['vehicle_ID'];		

		$unit_query = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM ".DB_PREFIX."vehicles WHERE `unit_ID`='".$selected_vehicle_ID."'"));
		$fare_query = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM ".DB_PREFIX."terminal WHERE `terminal_ID`='".$terminal_ID."'"));
		$driver_ID = $selected_unit['driver_ID'];
		$plate_no = $unit_query['plate_number'];
		$capacity = $unit_query['capacity'];
		$passenger = $selected_unit['current_passenger'];
		$fare = $fare_query['member_dailydues'];

		// Check Vehicle Run
		$date = date('Y-m-d',strtotime('today'));
		$records = mysqli_query($link, "SELECT COUNT(driver_ID) AS total FROM ".DB_PREFIX."vehicle_trip_history WHERE driver_ID='{$driver_ID}' AND terminal_ID='{$terminal_ID}' AND date(time_date)='{$date}'");
		
		$total_records = mysqli_fetch_array($records);
		$total_trips = $total_records['total'];
		
		//DETERMINE SERVICE FEE RATE
		IF($total_trips > 0):
			$service_fee = $fare_query['regular_service_fee'];
		ELSE:
			$service_fee = $fare_query['initial_service_fee'];
		ENDIF;
		
		$available_seat = $capacity - $passenger;

		$driver_info = mysqli_fetch_array(mysqli_query($link, "SELECT fname,lname,suffix FROM ".DB_PREFIX."users WHERE user_ID = '".$driver_ID."'"));
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

                            <h3 class="page-header">
								Commuter Terminal --- Today <?= date("F d, Y", strtotime($date)); ?>
								
								<span class="pull-right" id="tcharge" name="tcharge">
									<i class="fa fa-money fa-1x"></i> Driver's Terminal Fee: <span class="pull-right medium"  style="color:#003399;"><em><b><?= $service_fee; ?></b></em></span>
								</span>
								
							</h3>

                        </div><!-- /.col-lg-12 -->
                    </div><!-- /.row -->
					<div class="row">

						<div class="col-lg-4">
							<div class="panel panel-primary">
								<div class="panel-heading">
									Tap Commuter ID or Enter Manually
								</div>
								<div class="panel-body">
										<fieldset>
											<form id="ReadIDCard" autocomplete="off">
												<div class="form-group input-group">
													<span class="input-group-addon"><li class="fa fa-credit-card"></li></span>
													<input class="form-control" placeholder="Commuter ID" id="memberid" name="memberid" class="memberid" type="text" value="" onkeypress="return isNumberKey(event)" autofocus <?php IF(!isset($selected_vehicle_ID)): ?>disabled<?php ENDIF; ?> />
													<span class="input-group-btn">
														<button type="submit"  class="btn btn-default <?php IF(!isset($selected_vehicle_ID)): ?>disabled<?php ENDIF; ?>" id="readcard" name="readcard"><i class="fa fa-arrow-right"></i></button>
													</span>
												</div>
											</form>
										</fieldset>
								</div>
								<div class="panel-footer">
									<h4><span class="valcheck" id="valcheck" name="valcheck"></span></h4>
								</div>
							</div>
						</div>
						<!-- /.col-lg-4 -->

						<div class="col-lg-4">
							<div class="panel panel-green">
								<div class="panel-heading">
									Vehicle Detail
								</div>
								<div class="panel-body">
									<div class="list-group" id="vehicle_dispatch">
										<?php IF(isset($selected_vehicle_ID)): ?>
										<span class="list-group-item">
											<i class="fa fa-bus fa-1x"></i> Vehicle ID
											<input id="VehicleID" name="VehicleID" type="hidden" value="<?= $selected_vehicle_ID; ?>" />
											<input id="DriverID" name="DriverID" type="hidden" value="<?= $driver_ID; ?>" />
											<input id="totalcapacity" name="totalcapacity" type="hidden" value="<?= $capacity; ?>" />
											<input id="capacity" name="capacity" type="hidden" value="<?= $passenger; ?>" />
											<span class="pull-right medium" style="color:#003399;"><em><b><?= $selected_vehicle_ID; ?></b></em></span>
										</span>
										<span class="list-group-item">
											<i class="fa fa-bus fa-1x"></i> Plate No.
											<span class="pull-right medium" style="color:#003399;"><em><b><?= $plate_no; ?></b></em></span>
										</span>
										<span class="list-group-item">
											<i class="fa fa-barcode fa-1x"></i> Driver ID
											<span class="pull-right medium" style="color:#003399;"><em><b><?= $driver_ID; ?></b></em></span>
										</span>
										<span class="list-group-item">
											<i class="fa fa-user fa-1x"></i> Driver Name
											<span class="pull-right medium" style="color:#003399;">
												<em>
												<b>
													<span class="fname"><?= substr($driver_info['fname'], 0, 1); ?>.</span>
													<span class="lname"><?= $driver_info['lname']; ?></span>
													<?php IF($driver_info['suffix'] != ""): echo ", ".$driver_info['suffix']; ENDIF; ?>
												</b>
												</em>
											</span>
										</span>
										<span class="list-group-item">
											<i class="fa fa-info-circle fa-1x"></i> Seating Capacity
											<span class="pull-right medium" style="color:#003399;"><em><b><?= $capacity; ?></b></em></span>
										</span>
										<span class="list-group-item">
											<i class="fa fa-group fa-1x"></i> Passenger on Board
											<span class="pull-right medium" style="color:#003399;"><em><b id="total_pass"><?= $passenger; ?></b></em></span>
										</span>
										<span class="list-group-item">
											<i class="fa fa-info-circle fa-1x"></i> Available Seat(s)
											<span class="pull-right medium"  style="color:#003399;"><em><b id="seatavailable"><?= $available_seat; ?></b></em></span>
										</span>
										<span class="list-group-item">
											<i class="fa fa-money fa-1x"></i> Fare
											<span class="pull-right medium"  style="color:#003399;"><em><b id="collected_fare"><?= $fare; ?></b></em></span>
											<input id="fare" name="fare" type="hidden" value="<?= $fare; ?>" />
											<input id="service_fee" name="service_fee" type="hidden" value="<?= $service_fee; ?>" />
										</span>
										<span class="list-group-item">
											<i class="fa fa-money fa-1x"></i> Discount
											<span class="pull-right medium"  style="color:#003399;"><em><b id="discount">0.00</b></em></span>
										</span>
										<span class="list-group-item">
											<i class="fa fa-road fa-1x"></i> Number of Trip(s) Made
											<span class="pull-right medium"  style="color:#003399;"><em><b><?= $total_trips; ?></b></em></span>
										</span>
										<?php ELSE: ?>
										<span class="list-group-item">
											<i style="color:#FF0000" class="fa fa-warning fa-1x"></i> No Unit Selected!
										</span>
										<?php ENDIF; ?>
									</div>
								</div>
								<div class="panel-footer">								
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
									<div class="list-group">
										<span class="list-group-item">
											<i class="fa fa-bus fa-1x"></i> Plate No.
											<span class="pull-right medium"><i class="fa fa-user fa-1x"></i> Driver Name</span>
										</span>
										<?php 
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
						
						<?php IF(isset($selected_vehicle_ID)): ?>
						<div class="col-lg-4 pull-right" name="buttons" id="buttons">
							<div class="panel panel-green">
								<div class="panel-heading">
									Action
								</div>
								<div class="panel-body">
									<button type="submit" id="dispatch" name="dispatch" class="btn btn-primary btn-lg btn-block" <?=($passenger==0)? "Disabled" : "";?>>Dispatch Unit <i class="fa fa-road"></i></button>
									<?php IF(isset($selected_vehicle_ID)): ?><button type="submit" id="releaseunit" name="releaseunit" class="btn  btn-danger btn-lg btn-block" <?=($passenger!=0)? "Disabled" : "";?>>Release Unit <i class="fa fa-rocket"></i></button><?php ENDIF; ?>
								</div>
							</div>
						</div>
						<?php ENDIF; ?>
						
						
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

		<script type="text/javascript">		
			// NUMBERS ONLY
			function isNumberKey(evt)
			{
				var charCode = (evt.which) ? evt.which : event.keyCode
				if (charCode > 31 && (charCode < 48 || charCode > 57))
				return false;
				return true;
			}

			$(document).ready(function() {

				//Balance Checking - dispatcher
				$('#ReadIDCard').submit(function (e) {

					// Prevent form submission which refreshes page
					e.preventDefault();

					// Serialize data
					var formData = $(this).serialize();

					var mID = $("#memberid").val();
					var SFEE = $("#service_fee").val();
					var onboard = $("#capacity").val();
					var totalcap = $("#totalcapacity").val();
					
					if(mID == ''|| mID == 0 ){
						$('#valcheck').show();
						$('#valcheck').html("<h3 class='text-danger text-center'>Please Scan Card.</h3>");
						$('#valcheck').delay(3000).fadeOut();
					}
					else{
						$.post('trigger/readcard', { 'SFEE': SFEE, 'mID': mID, checkcard: 1}, function(data) {
							totalcap = totalcap - 1;

							if(onboard == totalcap){
								$('#vehicle_dispatch').html();
								$('#valcheck').show();
								$('#valcheck').html(data);
								$('#tcharge').html('');
								$('#buttons').hide();
								$('#vehicle_dispatch').hide();
							}else{
								$('#valcheck').show();
								$('#valcheck').html(data);
								$('#memberid').val('');
								$("#memberid").focus();
								$('#valcheck').delay(3000).fadeOut();
							}
						});
					}
				});
				
				//Unit Dispatch - dispatcher
				$("#dispatch").click(function(){
					var VID = $("#VehicleID").val();
					var DID = $("#DriverID").val();
					var CAP = $("#capacity").val();
					var SFEE = $("#service_fee").val();
					var FARE = $("#fare").val();
					
						$.post('trigger/readcard', { 'VID': VID, 'DID': DID, 'CAP': CAP, 'SFEE': SFEE, 'FARE': FARE, dispatch: 1}, function(data) {
							var checkdata = data;
							$('#vehicle_dispatch').html(checkdata);
							$('#tcharge').html('');
						});
					
				});
				
				// Unit Release
				$("#releaseunit").click(function(){
					var VID = $("#VehicleID").val();
					var DID = $("#DriverID").val();
			
						$.post('trigger/readcard', { 'VID': VID, 'DID': DID, releaseunit: 1}, function(data) {
							var checkdata = data;
							$('#vehicle_dispatch').html(checkdata);
							$('#tcharge').html('');
						});
					
				});
            });
		</script>

    </body>
</html>
