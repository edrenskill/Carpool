<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	if(!isset($_SESSION['ADM'])): header('location: login'); ENDIF;
	
	date_default_timezone_set("Asia/Manila");
	
	IF(isset($_SESSION['application_type'])):
		$member_type = $_SESSION['application_type'];
		$ownerID = $_SESSION['act_no'];
		IF(isset($_SESSION['dact_no'])):
			$D_Acct = $_SESSION['dact_no'];
		ENDIF;
	ENDIF;

	FOREACH($_POST as $key => $value) { $data[$key] = filter($value); }
	$err = array();
	IF(isset($_POST) && array_key_exists('doAdd',$_POST)):
		$Rname = strtoupper($data['RouteName']);
		$vowner = $data['ownerID'];
		$vdriver1 = $data['driverID1'];
		$vdriver2 = $data['driverID2'];
		$plate = strtoupper($data['plate']);
		$chassis = strtoupper($data['chassis']);
		$engine = strtoupper($data['engine']);

		IF(!isset($Rname) || $Rname == ""): $err[] = "Applied Route"; ENDIF;

		IF($vowner == ""): $err[] = "Vehicle Owner ID";
		ELSE:
			$results = mysqli_query($link, "SELECT user_ID FROM ".DB_PREFIX."users WHERE user_ID='$vowner' AND (userlevel = '8' || userlevel = '10')");
			$vuserid_exist = mysqli_num_rows($results); //total records
			IF(!$vuserid_exist): $err[] = "Vehicle Owner ID 1 doesn't exist"; ENDIF;
		ENDIF;

		//IF($vdriver1 == ""): $err[] = "Vehicle Driver ID";
		//ELSE:
		IF($vdriver1 !== ""):
			$dresults1 = mysqli_query($link, "SELECT user_ID FROM ".DB_PREFIX."users WHERE user_ID='$vdriver1' AND userlevel = '7' OR userlevel = '10'");
			$duserid_exist1 = mysqli_num_rows($dresults1); //total records
			IF(!$duserid_exist1): 
				$err[] = "Driver ID doesn't exist"; 
			ELSE:
				$checked = mysqli_num_rows(mysqli_query($link, "SELECT ID FROM ".DB_PREFIX."vehicles WHERE driver_ID1='{$vdriver1}' OR driver_ID2='{$vdriver1}'"));
				IF($checked!=0):
					$err[] = "Driver already assigned to other unit!";
				ENDIF;
			ENDIF;
		ENDIF;
		
		IF($vdriver2!==""):
			IF(!isset($vdriver1)):
				$err[] = "Driver 1 must be filled up first";
			ELSEIF($$vdriver2 == $vdriver1):
				$err[] = "Driver 2 must not be the same as driver 1 ID";
			ELSE:
				$dresults2 = mysqli_query($link, "SELECT user_ID FROM ".DB_PREFIX."users WHERE user_ID='$vdriver2' AND userlevel = '7' OR userlevel = '10'");
				$duserid_exist2 = mysqli_num_rows($dresults2); //total records
				IF(!$duserid_exist2): 
					$err[] = "Driver ID 2 doesn't exist";
				ELSE:
					$checked = mysqli_num_rows(mysqli_query($link, "SELECT ID FROM ".DB_PREFIX."vehicles WHERE driver_ID1='{$vdriver2}' OR driver_ID2='{$vdriver2}'"));
					IF($checked!=0):
						$err[] = "Driver already assigned to other unit!";
					ENDIF;
				ENDIF;
			ENDIF;
		ENDIF;
		
		IF($plate == ""): $err[] = "Plate Number";
		ELSE:
			$presults = mysqli_query($link, "SELECT ID FROM ".DB_PREFIX."vehicles WHERE plate_number='$plate'");
			$plate_exist = mysqli_num_rows($presults); //total records
			IF($plate_exist): $err[] = "Vehicle with the same Plate Number is already exist!"; ENDIF;
		ENDIF;
		
		IF($chassis == ""): $err[] = "Chassis Number";
		ELSE:
			$cresults = mysqli_query($link, "SELECT ID FROM ".DB_PREFIX."vehicles WHERE chassis='$chassis'");
			$chassis_exist = mysqli_num_rows($cresults); //total records
			IF($chassis_exist): $err[] = "Vehicle with the same Chassis Number is already exist!"; ENDIF;
		ENDIF;
		
		IF($engine == ""): $err[] = "Chassis Number";
		ELSE:
			$eresults = mysqli_query($link, "SELECT ID FROM ".DB_PREFIX."vehicles WHERE engine='$engine'");
			$egine_exist = mysqli_num_rows($eresults); //total records
			IF($engine_exist): $err[] = "Vehicle with the same Engine Number is already exist!"; ENDIF;
		ENDIF;
		
		IF($data['make_model'] == ""): $err[] = "Maker / Brand"; ENDIF;
		IF($data['yearmodel'] == ""): $err[] = "Year Model"; ENDIF;
		IF($data['capacity'] == ""): $err[] = "Capacity"; ENDIF;

		IF(!empty($err)):
			$_SESSION['post']['ownerID'] = $data['ownerID'];
			$_SESSION['post']['driverID1'] = $data['driverID1'];
			$_SESSION['post']['driverID2'] = $data['driverID2'];
			$_SESSION['post']['plate'] = $data['plate'];
			$_SESSION['post']['make_model'] = $data['make_model'];
			$_SESSION['post']['yearmodel'] = $data['yearmodel'];
			$_SESSION['post']['chassis'] = $data['chassis'];
			$_SESSION['post']['engine'] = $data['engine'];
			$_SESSION['post']['capacity'] = $data['capacity'];

		ELSEIF(empty($err)):
			$make = strtoupper($data['make_model']);
			$year = $data['yearmodel'];
			$capacity = $data['capacity'];

			$gen_unitID = $plate.mysqli_real_escape_string($link, GenID());

			$duplicates = mysqli_query($link, "SELECT unit_ID FROM ".DB_PREFIX."vehicles WHERE unit_ID='$gen_unitID'");
			WHILE(mysqli_fetch_array($duplicates)){
				$gen_unitID = $plate.mysqli_real_escape_string($link, GenID());
				WHILE($gen_unitID <= 2018500000){
					$gen_unitID = $plate.mysqli_real_escape_string($link, GenID());
				}
			}

			//Vehicle
			$sql_vehicle = "INSERT into `".DB_PREFIX."vehicles` (`unit_ID`,`plate_number`,`make`,`model`,`chassis`,`engine`,`capacity`,`owner_ID`, `driver_ID1`, `driver_ID2`,`terminal_ID`) 
			VALUES ('$gen_unitID','$plate','$make','$year','$chassis','$engine','$capacity','$vowner','$vdriver1','$vdriver2','$Rname')";

			mysqli_query($link, $sql_vehicle) or die("Insertion Failed:" . mysqli_error($link));

			//Driver
			//mysqli_query($link, "UPDATE `".DB_PREFIX."users` SET unit_ID='$gen_unitID' WHERE user_ID='$vdriver' AND userlevel = '7'");

			//Operator / driver
			//mysqli_query($link, "UPDATE `".DB_PREFIX."users` SET unit_ID='$gen_unitID' WHERE user_ID='$vowner' AND (userlevel = '8' || userlevel = '10')");

			$_SESSION['v_owner'] = $vowner;
			$_SESSION['v_driver'] = $vdriver1;
			$_SESSION['plate'] = $plate;
			$_SESSION['make'] = $make;
			$_SESSION['model'] = $year;
			$_SESSION['chassis'] = $chassis;
			$_SESSION['engine'] = $engine;
			$_SESSION['capacity'] = $capacity;

			HEADER('Location: vehicle_confirmation');
		ENDIF;
	ENDIF;

?>

<!DOCTYPE HTML>
<html>
	<head>
		<?php include ('includes/header.php'); ?>
		<script src="js/jquery.min.js"></script>

		<script type="text/javascript">
			$(document).ready( function() {
				$('#message').delay(5000).fadeOut();
				
				// IF OWNERID ENTERED
				$("#ownerID").blur(function (e) {
					//removes spaces from id
					$(this).val($(this).val().replace(/\s/g, ''));
					var ownerID = $(this).val();
					
					if(ownerID.length < 4){$("#owner-result").html('');return;}

					if(ownerID.length >= 4){
						$("#owner-result").html('<img src="../myaccount/images/loading.gif" />');
						$.post('trigger/exec', {'ownerID':ownerID}, function(data) {
							$("#owner-result").html(data);
						});
					}
				});
				
				// IF DRIVERID1 ENTERED
				$("#driverID1").blur(function (e) {
					//removes spaces from id
					$(this).val($(this).val().replace(/\s/g, ''));
					var driverID1 = $(this).val();
					
					if(driverID1.length < 4){$("#driver1-result").html('');return;}

					if(driverID1.length >= 4){
						$("#driver1-result").html('<img src="../myaccount/images/loading.gif" />');
						$.post('trigger/exec', {'driverID1':driverID1}, function(data) {
							$("#driver1-result").html(data);
						});
					}
				});
				
				$("#driverID2").blur(function (e) {
					//removes spaces from id
					$(this).val($(this).val().replace(/\s/g, ''));
					var driverID2 = $(this).val();
					var driver1ID = $("#driverID1").val();
					
					if(driverID2.length < 4){$("#driver2-result").html('');return;}

					if(driverID2.length >= 4){
						$("#driver2-result").html('<img src="../myaccount/images/loading.gif" />');
						$.post('trigger/exec', {'driverID2nd':driverID2, 'driver1ID':driver1ID}, function(data) {
							$("#driver2-result").html(data);
						});
					}
				});
				
				// IF PLATE ENTERED
				$("#plate").blur(function (e) {
					//removes spaces from id
					$(this).val($(this).val().replace(/\s/g, ''));
					var plate = $(this).val();
					
					if(plate.length < 4){$("#plate-result").html('');return;}

					if(plate.length >= 4){
						$("#plate-result").html('<img src="../myaccount/images/loading.gif" />');
						$.post('trigger/exec', {'plate':plate}, function(data) {
							$("#plate-result").html(data);
						});
					}
				});
				
				// IF CHASSIS ENTERED
				$("#chassis").blur(function (e) {
					//removes spaces from id
					$(this).val($(this).val().replace(/\s/g, ''));
					var chassis = $(this).val();
					
					if(chassis.length < 4){$("#chassis-result").html('');return;}

					if(chassis.length >= 4){
						$("#chassis-result").html('<img src="../myaccount/images/loading.gif" />');
						$.post('trigger/exec', {'chassis':chassis}, function(data) {
							$("#chassis-result").html(data);
						});
					}
				});
				
				// IF ENGINE ENTERED
				$("#engine").blur(function (e) {
					//removes spaces from id
					$(this).val($(this).val().replace(/\s/g, ''));
					var engine = $(this).val();
					
					if(engine.length < 4){$("#engine-result").html('');return;}

					if(engine.length >= 4){
						$("#engine-result").html('<img src="../myaccount/images/loading.gif" />');
						$.post('trigger/exec', {'engine':engine}, function(data) {
							$("#engine-result").html(data);
						});
					}
				});
				
			});		

			// NUMBERS ONLY
			function isNumberKey(evt)
			{
				var charCode = (evt.which) ? evt.which : event.keyCode
				if (charCode > 31 && (charCode < 48 || charCode > 57))
				return false;
				return true;
			}

			// FORM VALIDATION
			function validateForm()
			{			
				var RouteName = document.forms["addvehicle"]["RouteName"].value;
				var vowner = document.forms["addvehicle"]["ownerID"].value;
				var vdriver1 = document.forms["addvehicle"]["driverID1"].value;
				var plate = document.forms["addvehicle"]["plate"].value;
				var make_model = document.forms["addvehicle"]["make_model"].value;
				var yearmodel = document.forms['addvehicle']['yearmodel'].value;
				
				var chassis = document.forms["addvehicle"]["chassis"].value;
				var engine = document.forms["addvehicle"]["engine"].value;
				var capacity = document.forms["addvehicle"]["capacity"].value;
				
				if (RouteName==null || RouteName=="") { alert("Select Route Applying For"); return false; }
				if (vowner==null || vowner=="") { alert("Enter vehicle owner's ID"); return false; }
			 // if (vdriver1==null || vdriver1=="") { alert("Enter vehicle driver's ID"); return false; }
				if (plate==null || plate=="") { alert("Enter plate number"); return false; }
				if (make_model==null || make_model=="") { alert("Enter Maker or Brand"); return false; }
				if (yearmodel==null || yearmodel=="") { alert("Enter Year Model"); return false; }
				if (chassis==null || chassis=="") { alert("Enter Chassis Number"); return false; }
				if (engine==null || engine=="") { alert("Enter Engine Number"); return false; }
				if (capacity==null || capacity=="") { alert("Enter Capacity"); return false; }
			}

			
			$(function() { 
			   $('#yearmodel').datepicker( {
					changeMonth: false,
					changeYear: true,
					showButtonPanel: false,
					dateFormat: 'yy',
					onClose: function(dateText, inst) { 
						  $(this).datepicker('setDate', new Date('2017'));
					}
				}).focus(function () {
					$(".ui-datepicker-month").hide();
					$(".ui-datepicker-calendar").hide();
				});
			});

		</script>
	</head>
	<body>
		<!-- Wrapper -->
			<div id="wrapper">
				<?php include ('includes/navigation.php'); ?>

				<!-- Main -->
					<div id="page-wrapper">
					
					<div class="row">
						<div class="col-lg-12">
							<h1 class="page-header"><i class="fa fa-bus fa-fw"></i> Vehicle Registration</h1>
							<?php // Display error message
							IF(!empty($err)) : 
								echo "<p><div class=\"msg\" id=\"message\"><span style=\"color:#ff0000\">Please check the following fields:</span><br />"; FOREACH ($err as $e) { echo "$e <br />"; } echo "</div></p>"; 

								$field_owner = @$_SESSION['post']['ownerID'];
								$field_driver1 = $_SESSION['post']['driverID1'];
								$field_driver2 = $_SESSION['post']['driverID2'];
								$field_plate = @$_SESSION['post']['plate'];
								$field_make = @$_SESSION['post']['make_model'];
								$field_model = @$_SESSION['post']['yearmodel'];
								$field_chassis = @$_SESSION['post']['chassis'];
								$field_engine = @$_SESSION['post']['engine'];
								$field_capacity = @$_SESSION['post']['capacity'];
							ENDIF;
							?>
						</div><!-- /.col-lg-12 -->
					</div><!-- /.row -->
					
					
					
				
						<!-- SIGN UP -->
							<section id="add_vehicle">
								<div class="container">
									<span style="color:#003399">Note: <b>Field with asterisk (<span style="color:#FF0000">*</span>) are required.</b></span><br /><br />
										<form role="form" name="addvehicle" id="addvehicle" method="post" action="add_vehicle" onsubmit="return validateForm()">
											
											<div class="form-group" style="width:600px">
											<label>Select Applied Route<span style="color:#FF0000">*</span><span id="route-result"  style="padding-left:10px;"></span></label>
											
												<select class="form-control" name="RouteName" id="RouteName" style="width:300px">
													<option value="">-Select Route-</option>
													<?php
														$route_applied = mysqli_query($link, "SELECT terminal_ID, route_origin, route_destination FROM ".DB_PREFIX."terminal");
														WHILE($route = mysqli_fetch_array($route_applied)){
															$TID = $route['terminal_ID'];
															$applied_route = strtoupper($route['route_origin']." - ".$route['route_destination']);
													?>
													<option value="<?= $TID; ?>"><?= $applied_route; ?></option>
														<?php } ?>
												</select>
											</div>
											
											<div class="form-group" style="width:600px">
												<label>Operator's ID<span style="color:#FF0000">*</span><span id="owner-result"  style="padding-left:10px;"></span></label>
												<input class="form-control" type="text" name="ownerID" id="ownerID" value="<?php 
													IF(isset($field_owner)): 
														echo $field_owner;
													ELSEIF(isset($ownerID)):
														echo $ownerID;
													ENDIF; 
													?>" style="width:300px" PLACEHOLDER="Operator's ID" onkeypress="return isNumberKey(event)" <?php IF(isset($ownerID)): echo"readonly"; ENDIF; ?>/>
											</div>
											
											<div class="form-group" style="width:600px">
												<label>Driver's ID<span id="driver1-result"  style="padding-left:10px;"></span><span id="driver2-result"  style="padding-left:10px;"></span></label>
												<div>
													<input class="form-control" type="text" name="driverID1" id="driverID1" value="<?php 
														IF(isset($field_driver1)): 
															echo $field_driver1; 
														ELSEIF(isset($ownerID) && $member_type == 10):
															echo $ownerID;
														ELSEIF(isset($D_Acct) && $member_type == 8):
															echo $D_Acct;
														ENDIF; 
													?>" style="width:300px; margin:0 0 10px 0;" PLACEHOLDER="Driver's 1 ID" onkeypress="return isNumberKey(event)" <?php IF(isset($ownerID) && $member_type == 10): echo"readonly"; ENDIF; ?>/>
													<input class="form-control" type="text" name="driverID2" id="driverID2" value="<?php IF(isset($field_driver2)): echo $field_driver2; ENDIF; ?>" style="width:300px;" PLACEHOLDER="Driver's 2 ID (Optional)" onkeypress="return isNumberKey(event)"/>
												</div>
											</div>

											<div class="form-group" style="width:600px">
												<label>Plate Number<span style="color:#FF0000">*</span><span id="plate-result"  style="padding-left:10px;"></span></label>
												<input class="form-control" type="text" name="plate" id="plate" value="<?php IF(isset($field_plate)): echo $field_plate; ENDIF; ?>" style="width:300px" maxlength="7" PLACEHOLDER="Plate Number" />
											</div>
											
											<div class="form-group" style="width:600px">
												<label>Make / Brand<span style="color:#FF0000">*</span></label>
												<input class="form-control" type="text" name="make_model" id="make_model" value="<?php IF(isset($field_make)): echo $field_make; ENDIF; ?>" style="width:300px" PLACEHOLDER="Make / Brand" />
											</div>
											
											<div class="form-group" style="width:600px">
												<label>Year Model<span style="color:#FF0000">*</span></label>
												<input class="form-control" type="text" name="yearmodel" id="yearmodel" value="<?php IF(isset($field_model)): echo $field_model; ENDIF; ?>" style="width:300px" PLACEHOLDER="Model" maxlength="4" onkeypress="return isNumberKey(event)"/>
											</div>
											
											<div class="form-group" style="width:600px">
												<label>Chassis Number<span style="color:#FF0000">*</span><span id="chassis-result"  style="padding-left:10px;"></span></label>
												<input class="form-control" type="text" name="chassis" id="chassis" value="<?php IF(isset($field_chassis)): echo $field_chassis; ENDIF; ?>" style="width:300px" PLACEHOLDER="Chassis Number" />
											</div>
											
											<div class="form-group" style="width:600px">
												<label>Engine Number<span style="color:#FF0000">*</span><span id="engine-result"  style="padding-left:10px;"></span></label>
												<input class="form-control" type="text" name="engine" id="engine" value="<?php IF(isset($field_engine)): echo $field_engine; ENDIF; ?>" style="width:300px" PLACEHOLDER="Engine Number" />
											</div>
											
											<div class="form-group" style="width:600px">
												<label>Capacity<span style="color:#FF0000">*</span></label>
												<input class="form-control" type="text" name="capacity" id="capacity" value="<?php IF(isset($field_capacity)): echo $field_capacity; ENDIF; ?>" style="width:300px" PLACEHOLDER="Capacity" maxlength="2" onkeypress="return isNumberKey(event)"/>
											</div>

											<div  class="form-group">
												<button class="btn btn-success" type="submit" name="doAdd" id="doAdd" value="doAdd">Submit</button>
											</div>
											<?php unset($_SESSION['post'], $_SESSION['act_no'], $_SESSION['dact_no']); ?>
										</form>
								</div>
							</section>
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

<!-- DataTables JavaScript -->
<script src="js/dataTables/jquery.dataTables.min.js"></script>
<script src="js/dataTables/dataTables.bootstrap.min.js"></script>

<!-- Page-Level Demo Scripts - Tables - Use for reference -->
<script src="js/dashboard.js"></script>

	</body>
	

</html>