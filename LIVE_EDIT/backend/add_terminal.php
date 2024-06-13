<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	IF(!isset($_SESSION['ADM']) && isset($_SESSION['user_id'])){ 
		header ('location: login');
	}ELSEIF(isset($_SESSION['ADM']) && !isset($_SESSION['user_id'])){ 
		unset($_SESSION['ADM']);
		header ('location: ../index');
	}ELSEIF(!isset($_SESSION['ADM']) && !isset($_SESSION['user_id'])){ 
		header ('location: ../index');
	}
	
	date_default_timezone_set("Asia/Manila");	
	
	FOREACH($_POST as $key => $value) { $data[$key] = filter($value); }
	$err = array();
	IF(isset($_POST) && array_key_exists('doAdd',$_POST)):
		
		$DID = $data['dispatcherID'];
		$tName = strtoupper($data['TerminalName']);
		$tOrigin = strtoupper($data['origin']);
		$tDestination = strtoupper($data['destination']);
		$tFare = $data['fare'];
		$tCharge = $data['charge'];
		$Initial = $data['initial'];
		$tIncentive = $data['incentive'];
		
		if(isset($data['Tstatus']) && 
		   $_POST['Tstatus'] == '1') 
		{
			$operational = 1;
		}
		else
		{
			$operational = 0;
		}


// DISABLE DISPATCHER ID REQUIRED FIELD
//		IF(!isset($DID) || $DID == ""): $err[] = "Dispatcher ID";
//		ELSE:
		IF($DID != "" || $DID != 0):
			$DIDresults = mysqli_query($link, "SELECT user_ID, terminal_ID FROM ".DB_PREFIX."users WHERE user_ID='{$DID}' AND userlevel = '2' AND approval=1");
			$ID_exist = mysqli_num_rows($DIDresults); //total records
			IF(!$ID_exist): 
				$err[] = "Invalid Dispatcher ID";
			ELSE:
				WHILE($ID_status = mysqli_fetch_array($DIDresults)){
					$checked = $ID_status['terminal_ID'];
					IF($checked!=0):
						$err[] = "Dispatcher was assigned to other terminal";
					ENDIF;
				}
			ENDIF;
		ENDIF;
		
		IF(!isset($tName) || $tName == ""): $err[] = "Terminal Name";
		ELSE:
			$results = mysqli_query($link, "SELECT terminal_name FROM ".DB_PREFIX."terminal WHERE terminal_name='{$tName}'");
			$terminal_exist = mysqli_num_rows($results); //total records
			IF($terminal_exist): $err[] = "Terminal already exist"; ENDIF;
		ENDIF;

		IF($tOrigin == ""): $err[] = "Trip Origin";	ENDIF;
		IF($tDestination == ""): $err[] = "Trip Destination"; ENDIF;
		IF($tFare == ""): $err[] = "Fare";	ENDIF;
		IF($tCharge == ""): $err[] = "Terminal Charge";	ENDIF;
		IF($tIncentive == ""): $err[] = "Incentive"; ENDIF;

		IF(!empty($err)):
			$_SESSION['post']['DispatcherID'] = $DID;
			$_SESSION['post']['TerminalName'] = $tName;
			$_SESSION['post']['origin'] = $tOrigin;
			$_SESSION['post']['destination'] = $tDestination;
			$_SESSION['post']['fare'] = $tFare;
			$_SESSION['post']['charge'] = $tCharge;
			$_SESSION['post']['incentive'] = $tIncentive;
			$_SESSION['post']['initial'] = $Initial;
			$_SESSION['post']['Tstatus'] = $operational;

		ELSEIF(empty($err)):
			$batch = mysqli_real_escape_string($link, date("Y"));
			$gen_terminalID = $batch.mysqli_real_escape_string($link, GenID());
			$duplicates = mysqli_query($link, "SELECT terminal_ID FROM ".DB_PREFIX."terminal WHERE terminal_ID='$gen_terminalID'");
			WHILE(mysqli_fetch_array($duplicates)){
				$gen_terminalID = $batch.mysqli_real_escape_string($link, GenID());
				WHILE($gen_terminalID <= 2018500000){
					$gen_terminalID = $batch.mysqli_real_escape_string($link, GenID());
				}
			}

			//Terminal
			$sql_terminal = "INSERT into `".DB_PREFIX."terminal` (`terminal_ID`,`terminal_name`,`route_origin`,`route_destination`,`member_dailydues`,`regular_service_fee`,`initial_service_fee`,`incentive_percentage`,`operational`) 
			VALUES ('$gen_terminalID','$tName','$tOrigin','$tDestination','$tFare','$tCharge','$Initial','$tIncentive','$operational')";

			mysqli_query($link, $sql_terminal) or die("Insertion Failed:" . mysqli_error($link));
			mysqli_query($link, "UPDATE `".DB_PREFIX."users` SET terminal_ID='$gen_terminalID' WHERE user_ID='$DID'");

			$_SESSION['DID'] = $DID;
			$_SESSION['TID'] = $gen_terminalID;
			$_SESSION['TName'] = $tName;
			$_SESSION['TOrigin'] = $tOrigin;
			$_SESSION['TDestination'] = $tDestination;
			$_SESSION['TFare'] = $tFare;
			$_SESSION['TCharge'] = $tCharge;
			$_SESSION['TIncentive'] = $tIncentive;
			$_SESSION['initial'] = $Initial;
			
			HEADER('Location: terminal_confirmation');
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
				
				// IF dispatcherID ENTERED
				$("#dispatcherID").blur(function (e) {
					//removes spaces from id
					$(this).val($(this).val().replace(/\s/g, ''));
					var dispatcherID = $(this).val();
					
					if(dispatcherID.length < 4){$("#dispatcher-result").html('At least 10 characters');return;}

					if(dispatcherID.length >= 4){
						$("#dispatcher-result").html('<img src="../myaccount/images/loading.gif" />');
						$.post('trigger/exec', {'dispatcherID':dispatcherID}, function(data) {
							$("#dispatcher-result").html(data);
						});
					}
				});
				
				// IF TERMINAL NAME ENTERED
				$("#TerminalName").blur(function (e) {
					var TerminalName = $("#TerminalName").val();
					
					if(TerminalName.length < 4){$("#terminal-result").html('');return;}

					if(TerminalName.length >= 4){
						$("#terminal-result").html('<img src="../myaccount/images/loading.gif" />');
						$.post('trigger/exec', {'TerminalName':TerminalName}, function(data) {
							$("#terminal-result").html(data);
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

				// DISABLE DISPATCHER ID AS REQUIRED
				//var DID = document.forms["addterminal"]["dispatcherID"].value;
				
				var tName = document.forms["addterminal"]["terminalName"].value;
				var tOrigin = document.forms["addterminal"]["origin"].value;
				var tDestination = document.forms["addterminal"]["destination"].value;
				var tFare = document.forms["addterminal"]["fare"].value;
				var tCharge = document.forms['addterminal']['charge'].value;
				var tIncentive = document.forms['addterminal']['incentive'].value;
				var initial = document.forms['addterminal']['initial'].value;
				
				// DISABLE DISPATCHER ID AS REQUIRED
				// if (DID==null || DID=="") { alert("Enter Dispatcher ID"); return false; }
				
				if (tName==null || tName=="") { alert("Enter Terminal Name"); return false; }
				if (tOrigin==null || tOrigin=="") { alert("Enter Trip Origin"); return false; }
				if (tDestination==null || tDestination=="") { alert("Enter Trip Destination"); return false; }
				if (tFare==null || tFare=="") { alert("Enter Terminal Fare"); return false; }
				if (tCharge==null || tCharge=="") { alert("Enter Vehicle Trip Charge"); return false; }
				if (tIncentive==null || tIncentive=="") { alert("Enter Trip Incentives"); return false; }
				if (initial==null || initial=="") { alert("Enter Initial Service Fee "); return false; }
			}

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
							<h1 class="page-header"><i class="fa fa-road fa-fw"></i> Terminal Registration</h1>
							<?php // Display error message
							IF(!empty($err)) : 
								echo "<p><div class=\"msg\" id=\"message\"><span style=\"color:#ff0000\">Please check the following fields:</span><br />"; FOREACH ($err as $e) { echo "$e <br />"; } echo "</div></p>"; 
								$field_DID = @$_SESSION['post']['DispatcherID'];
								$field_terminal = @$_SESSION['post']['terminalName'];
								$field_origin = $_SESSION['post']['origin'];
								$field_destination = $_SESSION['post']['destination'];
								$field_rate = @$_SESSION['post']['fare'];
								$field_charge = @$_SESSION['post']['charge'];
								$field_incentive = @$_SESSION['post']['incentive'];
								$field_initial = @$_SESSION['post']['initial'];
								$field_status = @$_SESSION['post']['Tstatus'];
							ENDIF;
							?>
						</div><!-- /.col-lg-12 -->
					</div><!-- /.row -->
					
					
					
				
						<!-- SIGN UP -->
							<section id="add_vehicle">
								<div class="container">
									<span style="color:#003399">Note: <b>Field with asterisk (<span style="color:#FF0000">*</span>) are required.</b></span><br /><br />
										<form role="form" name="addterminal" id="addterminal" method="post" action="add_terminal" onsubmit="return validateForm()">
											
											<div class="form-group" style="width:600px">
												<label>Dispatcher's ID<span id="dispatcher-result"  style="padding-left:10px;"></span></label>
												<input class="form-control" type="text" name="dispatcherID" id="dispatcherID" value="<?php IF(isset($field_DID)): echo $field_DID; ENDIF; ?>" style="width:300px" PLACEHOLDER="Dispatcher's ID" onkeypress="return isNumberKey(event)"/>
											</div>
											
											<div class="form-group" style="width:600px">
												<label>Terminal Name<span style="color:#FF0000">*</span><span id="terminal-result"  style="padding-left:10px;"></span></label>
												<input class="form-control" type="text" name="TerminalName" id="TerminalName" value="<?php IF(isset($field_terminalName)): echo $field_terminalName; ENDIF; ?>" style="width:300px" PLACEHOLDER="Terminal Name"/>
											</div>
											
											<div class="form-group" style="width:600px">
												<label>Route Origin<span style="color:#FF0000">*</span><span id="origin-result"  style="padding-left:10px;"></span><span id="driver2-result"  style="padding-left:10px;"></span></label>
												<div>
													<input class="form-control" type="text" name="origin" id="origin" value="<?php IF(isset($field_origin)): echo $field_origin; ENDIF; ?>" style="width:300px; margin:0 0 10px 0;" PLACEHOLDER="Route Origin"/>
												</div>
											</div>

											<div class="form-group" style="width:600px">
												<label>Route Destination<span style="color:#FF0000">*</span><span id="destination-result"  style="padding-left:10px;"></span></label>
												<input class="form-control" type="text" name="destination" id="destination" value="<?php IF(isset($field_destination)): echo $field_destination; ENDIF; ?>" style="width:300px" PLACEHOLDER="Route Destination"/>
											</div>
											
											<div class="form-group" style="width:600px">
												<label>Fare Rate<span style="color:#FF0000">*</span><span id="fare-result"  style="padding-left:10px;"></span></label>
												<input class="form-control" type="text" name="fare" id="fare" value="<?php IF(isset($field_rate)): echo $field_rate; ENDIF; ?>" style="width:300px" PLACEHOLDER="Fare Rate"  onkeypress="return isNumberKey(event)"/>
											</div>
											
											<div class="form-group" style="width:600px">
												<label>Terminal Charge<span style="color:#FF0000">*</span><span id="charge-result"  style="padding-left:10px;"></span></label>
												<input class="form-control" type="text" name="charge" id="charge" value="<?php IF(isset($field_charge)): echo $field_charge; ENDIF; ?>" style="width:300px" PLACEHOLDER="Terminal Charge" onkeypress="return isNumberKey(event)"/>
											</div>
											<div class="form-group" style="width:600px">
												<label>Initial Charge<span style="color:#FF0000">*</span><span id="initial-result"  style="padding-left:10px;"></span></label>
												<input class="form-control" type="text" name="initial" id="initial" value="<?php IF(isset($field_initial)): echo $field_initial; ENDIF; ?>" style="width:300px" PLACEHOLDER="Initial Charge" onkeypress="return isNumberKey(event)"/>
											</div>
											
											<div class="form-group" style="width:600px">
												<label>Incentive Percentage<span style="color:#FF0000">*</span><span id="incentive-result"  style="padding-left:10px;"></span></label>
												<input class="form-control" type="text" name="incentive" id="incentive" value="<?php IF(isset($field_incentive)): echo $field_incentive; ENDIF; ?>" style="width:300px" PLACEHOLDER="Incentive Percentage" onkeypress="return isNumberKey(event)"/>
											</div>

											<div class="form-group">
                                                <label>status</label>
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="Tstatus" id="Tstatus" type="checkbox" <?php IF(isset($field_status) && $field_status == 1): echo "checked"; ENDIF; ?> value="1">Operational
                                                    </label>
                                                </div>
                                            </div>

											<div  class="form-group">
												<button class="btn btn-success" type="submit" name="doAdd" id="doAdd" value="doAdd">Submit</button>
											</div>
											<?php unset($_SESSION['post']); ?>
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