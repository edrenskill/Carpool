<?php
require_once '../../settings/connect.php';
if(session_id() == '') { page_protect(); } // START SESSIONS

foreach($_POST as $key => $value) { $data[$key] = filter($value); }
$terminal_ID = $_SESSION['terminal_ID'];
// Board Passenger
if(isset($data["checkcard"]))
{	
	//trim and lowercase ID
	$mID = $data['mID'];
	IF ($mID == '' || $mID == '0'):
		echo "Please Enter Valid ID Numbers";
	ELSE:
		//sanitize MID
		$mID = filter_var($mID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);

		//check record in db
		$idsearch = mysqli_query($link, "SELECT `user_ID`,`account_status` FROM `".DB_PREFIX."users` WHERE `user_ID`='{$mID}' AND `userlevel` = '1'");
		$ID_result = mysqli_num_rows($idsearch); //total records

		IF($ID_result):
			$driver_update = mysqli_fetch_assoc($idsearch);
			$driver_stat = $driver_update['account_status'];
			IF($driver_stat==1):
					echo "<h3 class='text-warning text-center'>Account Suspended!</h3>"; // SUCCESS
			ELSEIF($driver_stat==2):
					echo "<h3 class='text-warning text-center'>Account Banned!</h3>"; // SUCCESS
			ELSE:
		
			
			
			
	///////////////////////////////////	Determine Card Type /////////////////////	
				$cardtype = mysqli_fetch_assoc(mysqli_query($link, "SELECT `card_type` FROM `".DB_PREFIX."idcards` WHERE `card_number`='{$mID}'"));
				$cType = $cardtype['card_type'];
	/////////////////////////////////////////////////////////////////////////////			
			
				$mresults = mysqli_query($link, "SELECT `ending_balance` FROM `".DB_PREFIX."account` WHERE `user_ID`='".$mID."' AND `primary` = '1'");
				//return total count
				$MID_exist = mysqli_num_rows($mresults); //total records
				IF($MID_exist):
					$balance = mysqli_fetch_array($mresults);
					$prev_bal = $balance['ending_balance'];

					$terminal_due = mysqli_fetch_array(mysqli_query($link, "SELECT (member_dailydues) AS fare FROM `".DB_PREFIX."terminal` WHERE `terminal_ID`='".$terminal_ID."'"));
					$Terminal_fare = $terminal_due['fare'];
					
					IF($cType == 1):
						$fare = $Terminal_fare;
						$discounted_fare = 0;
					ELSEIF($cType == 2):
						$discount = 20;
						$discounted_fare = ($discount / 100) * $Terminal_fare;
						$fare = $Terminal_fare - $discounted_fare;
					ENDIF;

					IF($prev_bal < $fare):
						echo "<h3 class='text-danger text-center'>Insufficient Fund!</h3>";
					ELSE:
						// GET SELECTED UNIT INFO
						$selected_unit = mysqli_fetch_array(mysqli_query($link, "SELECT B.driver_ID, A.capacity, B.trip_ID, B.current_passenger, B.vehicle_ID FROM ".DB_PREFIX."vehicles A, ".DB_PREFIX."vehicle_trip_schedule B WHERE  A.unit_ID=B.vehicle_ID AND B.terminal_ID='{$terminal_ID}' AND B.selected = 1"));
						
						$capacity = $selected_unit['capacity'];
						$vehicle_ID = $selected_unit['vehicle_ID'];
						$new_total_pas = $selected_unit['current_passenger'] + 1;
						$new_available_seat = $capacity - $new_total_pas;
						$driverID = $selected_unit['driver_ID'];
						$tripID = $selected_unit['trip_ID'];
						
						// Check if already tapped in
						$check_tapped = mysqli_num_rows(mysqli_query($link, "SELECT trip_ID FROM ".DB_PREFIX."member_trip_history WHERE user_ID='{$mID}' AND trip_ID='{$tripID}'"));
						IF(!$check_tapped):
						
							$date = date('Y-m-d');
							$time = date('H:i:s');
							$user_ID = $mID;
							$datetime = $date." ".$time;
							$ctcode = "SF";
							
							//GENERATE TRANSACTION REFERENCE CODE
							$batch = mysqli_real_escape_string($link, date("Y"));
							$gen_transcode = $batch.mysqli_real_escape_string($link, GenKey());
							$duplicates = mysqli_query($link, "SELECT ref_no FROM ".DB_PREFIX."terminaltrans WHERE ref_no='$gen_transcode'");
							WHILE(mysqli_fetch_array($duplicates)){
								$gen_transcode = $batch.mysqli_real_escape_string($link, GenKey());
							}
							$gen_transcode = STRTOUPPER($gen_transcode);

							//update vehicle status
							$query = mysqli_query($link, "UPDATE `".DB_PREFIX."vehicle_trip_schedule` SET current_passenger='{$new_total_pas}' WHERE vehicle_ID='{$vehicle_ID}'");

							IF($query):
								
								//update passenger trip History
								$sql_member_trip_history = "INSERT into `".DB_PREFIX."member_trip_history` (`user_ID`,`trip_ID`) VALUES ('$mID','$tripID')";
								mysqli_query($link, $sql_member_trip_history) or die("Trip History Update Failed:" . mysqli_error($link));
								
								//MEMBER
								$c_prev_loadwallet = mysqli_fetch_array(mysqli_query($link, "SELECT ending_balance  FROM ".DB_PREFIX."account WHERE user_ID = '".$mID."' AND `primary` = 1")); 
								$member_cur_balance = $c_prev_loadwallet['ending_balance'];
								$member_new_balance = $member_cur_balance - $fare;

								//Update Account
								mysqli_query($link, "UPDATE `".DB_PREFIX."account` SET `primary`=0  WHERE `user_ID`='".$mID."' AND `primary`=1");

								$sql_insert = "INSERT into `".DB_PREFIX."account` (`user_ID`,`transaction_date`,`transaction_time`,`transaction_no`,`transaction_code`,`trip_ID`,`regular_fare`,`discount`,`debit`,`ending_balance`,`terminal_ID`,`primary`)
								VALUES ('$mID','$date','$time','$gen_transcode','$ctcode','$tripID','$Terminal_fare','$discounted_fare','$fare','$member_new_balance','$terminal_ID','1')";
								mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysql_error());
								
								//UPDATE Terminal Transaction
								$transaction_sql_insert = "INSERT into `".DB_PREFIX."terminaltrans` (`ref_no`,`trans_date`,`trans_time`,`transaction_code`,`user_ID`,`service_fee`,`terminal_ID`)
								VALUES ('$gen_transcode','$date','$time','$ctcode','$mID','$fare','$terminal_ID')";
								mysqli_query($link, $transaction_sql_insert) or die("Insertion Failed:" . mysql_error());						
								
								$remaining_bal = $prev_bal - $fare;
								
								echo "<script>$('#discount').text('".$discounted_fare."');</script>";
								
								echo "
								<span class='list-group-item'>
									<i class='fa fa-info-circle fa-1x'></i>Discount :
									<span class='pull-right text-info'><em><b>".number_format($discounted_fare,2)."</b></em></span>
								</span>
								<span class='list-group-item'>
									<i class='fa fa-info-circle fa-1x'></i>Remaining Balance :
									<span class='pull-right text-info'><em><b>".number_format($remaining_bal,2)."</b></em></span>
								</span>
								<span class='list-group-item'>
									<i class='fa fa-info-circle fa-1x'></i>Previous Balance :
									<span class='pull-right text-info'><em><b>".number_format($prev_bal,2)."</b></em></span>
								</span>
								<script>$('#dispatch').prop('disabled', false);$('#releaseunit').prop('disabled', true);$('#total_pass').text('".$new_total_pas."');$('#capacity').val(".$new_total_pas.");$('#seatavailable').text('".$new_available_seat."');$('#collected_fare').text('".$fare."');</script>
								";
								
								$check_capacity = mysqli_num_rows(mysqli_query($link, "SELECT current_passenger FROM ".DB_PREFIX."vehicle_trip_schedule WHERE current_passenger='{$capacity}' AND terminal_ID='{$terminal_ID}' AND selected=1"));
								IF($check_capacity):

									//trim and lowercase ID
									$dispatcher_ID = $_SESSION['act_ID'];
									$service_fee = $data['SFEE'];

									$sql_insert = "INSERT into ".DB_PREFIX."vehicle_trip_history (`driver_ID`,`passenger`,`terminal_ID`,`dispatcher_ID`,`time_date`,`vehicle_ID`,`trip_ID`)
									VALUES ('$driverID','$new_total_pas','$terminal_ID','$dispatcher_ID','$datetime','$vehicle_ID','$tripID')";
									mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysql_error());
									
									$sum_fare = mysqli_fetch_assoc(mysqli_query($link, "SELECT SUM(debit) AS totalfare FROM ".DB_PREFIX."account WHERE trip_ID='{$tripID}'"));
									
									$total_fare = $sum_fare['totalfare'];
									
									$sql_insert = "INSERT into ".DB_PREFIX."driver_account (`driver_ID`,`terminal_ID`,`trip_ID`,`total_fare`,`service_fee`,`incentive_percentage`)
									VALUES ('$driverID','$terminal_ID','$tripID','$total_fare','$service_fee','0')";
									mysqli_query($link, $sql_insert) or die(mysql_error());
		
									mysqli_query($link, "DELETE FROM ".DB_PREFIX."vehicle_trip_schedule WHERE terminal_ID='{$terminal_ID}' AND vehicle_ID='{$vehicle_ID}' AND selected=1");

									echo "<h4>Unit with Plate No.: <span class='text-danger'>".$plate."</span> has been dispatched with <span class='text-danger'>".$capacity."</span> passenger(s) on board.</h4>";
									echo "<script>$('#memberid').prop('disabled', true);$('#dispatch').prop('disabled', true);</script>";
								ENDIF;
							ENDIF;
						ELSE:
							echo "<h3 class='text-danger text-center'>Already On-board</h3>";
						ENDIF;
					ENDIF;
				ELSE:
					echo "<h3 class='text-danger text-center'>Insufficient Fund!</h3>";
				ENDIF;
			ENDIF;
		ELSE:
			echo "<h3 class='text-danger text-center'>Card is Invalid!</h3>";
		ENDIF;
	ENDIF;
}

// Unit Release
if(isset($data["releaseunit"]))
{	
	//trim and lowercase ID
	$VID = $data['VID'];
	$DID = $data['DID'];
	
	//sanitize MID
	$VID = filter_var($VID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
		
	mysqli_query($link, "DELETE FROM ".DB_PREFIX."vehicle_trip_schedule WHERE driver_ID='{$DID}' AND terminal_ID='{$terminal_ID}' AND vehicle_ID='{$VID}' AND selected=1");
	echo "<h4>Unit with Plate No.: <span class='text-danger'>".$plate."</span> has been release from loading bay without passenger(s) on board</h4>";
	echo "<script>$('#releaseunit').prop('disabled', true);$('#memberid').prop('disabled', true);$('#dispatch').prop('disabled', true);</script>";
}

















// Unit Dispatch
if(isset($data["dispatch"]))
{	
	//trim and lowercase ID
	$VID = $data['VID'];
	$DID = $data['DID'];
	$capacity = $data['CAP'];
	$dispatcher_ID = $_SESSION['act_ID'];
	$service_fee = $data['SFEE'];
	
	//sanitize MID
	$VID = filter_var($VID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);

	// GET SELECTED UNIT INFO
	$selected_unit = mysqli_fetch_array(mysqli_query($link, "SELECT A.plate_number, A.capacity, B.trip_ID, B.current_passenger, B.vehicle_ID FROM ".DB_PREFIX."vehicles A, ".DB_PREFIX."vehicle_trip_schedule B WHERE A.unit_ID=B.vehicle_ID AND B.terminal_ID = '{$terminal_ID}' AND B.selected = 1"));

	$vehicle_ID = $selected_unit['vehicle_ID'];
	$plate = $selected_unit['plate_number'];
	$tripID = $selected_unit['trip_ID'];

	$date = date('Y-m-d');
	$time = date('H:i:s');
	$datetime = $date." ".$time;
		
	$sql_insert = "INSERT into ".DB_PREFIX."vehicle_trip_history (`driver_ID`,`passenger`,`terminal_ID`,`dispatcher_ID`,`time_date`,`vehicle_ID`,`trip_ID`)
	VALUES ('$DID','$capacity','$terminal_ID','$dispatcher_ID','$datetime','$VID','$tripID')";
	mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysql_error());
	
	$sum_fare = mysqli_fetch_assoc(mysqli_query($link, "SELECT SUM(debit) AS totalfare FROM ".DB_PREFIX."account WHERE trip_ID='{$tripID}'"));
	
	$total_fare = $sum_fare['totalfare'];
	
	$sql_insert = "INSERT into ".DB_PREFIX."driver_account (`driver_ID`,`terminal_ID`,`trip_ID`,`total_fare`,`service_fee`,`incentive_percentage`)
	VALUES ('$DID','$terminal_ID','$tripID','$total_fare','$service_fee','0')";
	mysqli_query($link, $sql_insert) or die(mysql_error());
			
	mysqli_query($link, "DELETE FROM ".DB_PREFIX."vehicle_trip_schedule WHERE driver_ID='{$DID}' AND terminal_ID='{$terminal_ID}' AND vehicle_ID='{$vehicle_ID}' AND selected=1");
	echo "<h4>Unit with Plate No.: <span class='text-danger'>".$plate."</span> has been dispatched with <span class='text-danger'>".$capacity."</span> passenger(s) on board.</h4>";
	echo "<script>$('#memberid').prop('disabled', true);$('#dispatch').prop('disabled', true);</script>";
}
?>