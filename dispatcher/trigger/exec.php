<?php
require_once '../../settings/connect.php';
if(session_id() == '') { page_protect(); } // START SESSIONS

foreach($_POST as $key => $value) { $data[$key] = filter($value); }
$terminal_ID = $_SESSION['terminal_ID'];

if(isset($data["checkval"]))
{	
	//trim and lowercase ID
	$mID = $data['mID'];
	IF ($mID == '' || $mID == '0'):
		echo "
			<span class='text-warning text-center'>Please Enter Valid ID Numbers</span>
			<script>$('#memberID').val('');$('#memberID').focus();</script>
		";
	ELSEIF (strlen($mID) < 10 || strlen($mID) > 10):
		echo "
			<span class='text-warning text-center'>CE Card number must be at least but not exceeding 10 characters.</span>
			<script>$('#memberID').val('');$('#memberID').focus();</script>
		";
	ELSE:
		//sanitize MID
		$mID = filter_var($mID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);

		$check_card = mysqli_query($link, "SELECT status, disposed FROM ".DB_PREFIX."idcards WHERE card_number='{$mID}'");
		$check_result = mysqli_num_rows($check_card);
		IF($check_result):
			$check_stat = mysqli_fetch_assoc($check_card);
			IF($check_stat['status'] == 1 && $check_stat['disposed'] == 1):
		
				//check record in db
				$idsearch = mysqli_query($link, "SELECT user_ID, account_status, approval FROM ".DB_PREFIX."users WHERE user_ID='{$mID}' AND userlevel=1");
				$ID_result = mysqli_num_rows($idsearch); //total records
				IF($ID_result):
					WHILE($check_ID = mysqli_fetch_array($idsearch)){
						$status = $check_ID['account_status'];
						$approval = $check_ID['approval'];
						IF($approval == 0):
							echo "Account For Activation";
						ELSEIF($approval == 1):
							IF($status == 1):
								echo "Suspended Account";
							ELSEIF($status == 2):
								echo "Banned Account";
							ELSE:
								$mresults = mysqli_query($link, "SELECT `ending_balance` FROM `".DB_PREFIX."account` WHERE `user_ID`='".$mID."' AND `primary` = '1'");
								//return total count
								$MID_exist = mysqli_num_rows($mresults); //total records
								IF($MID_exist):
									$balance = mysqli_fetch_array($mresults);
									echo "<span class='text-warning text-center'>Remaining Load Balance: <h3>₱".number_format($balance['ending_balance'],2)."</h3></span>";
								ELSE:
									echo "<span class='text-warning text-center'>Remaining Load Balance: <h3>₱0.00</h3></span>";
								ENDIF;
							ENDIF;
						ENDIF;
					}
				ELSE:
					echo 1;
				ENDIF;
			ELSEIF($check_stat['status'] == 0 && $check_stat['disposed'] == 1):
				echo "<span class='text-warning text-center'><h3>Card for activation!</h3></span>";
			ELSEIF($check_stat['status'] == 0 && $check_stat['disposed'] == 0):
				echo "<span class='text-warning text-center'><h3>Card not yet sold!</h3></span>";
			ENDIF;
		ELSE:
			echo 1;
		ENDIF;
	ENDIF;
}

// Reloading
if(isset($data["validate"]))
{	
	//trim and lowercase ID
	$mID = $data['mID'];
	$load_amount = $data['load'];
	$wallet = $data['wallet'];
	
	IF($load_amount > $wallet): echo 1; exit; ENDIF;
	
	IF ($mID == '' || strlen($mID) < 1):
		echo 2;		
		exit;
	ELSE:	
		//sanitize TID
		$mID = filter_var($mID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);

		$results = mysqli_query($link, "SELECT `user_ID` FROM ".DB_PREFIX."users WHERE `user_ID`='".$mID."' AND `userlevel`='1'");
		$account_exist = mysqli_num_rows($results);

		IF($account_exist): 

			// Check wallet balance
			
				$date = date('Y-m-d');
				$time = date('H:i:s');

				$dispatcher_ID = $_SESSION['act_ID'];
				
				$batch = mysqli_real_escape_string($link, date("Y"));
				$gen_transcode = $batch.mysqli_real_escape_string($link, GenKey());
				$duplicates = mysqli_query($link, "SELECT transaction_no FROM ".DB_PREFIX."terminalload_wallet WHERE transaction_no='$gen_transcode'");
				WHILE(mysqli_fetch_array($duplicates)){
					$gen_transcode = $batch.mysqli_real_escape_string($link, GenKey());
				}
				$gen_transcode = STRTOUPPER($gen_transcode);				

				$dtcode = "LDC";
				$ctcode = "RLD";

				//TERMINAL
				$d_prev_loadwallet = mysqli_fetch_array(mysqli_query($link, "SELECT ending_balance  FROM ".DB_PREFIX."terminalload_wallet WHERE terminal_ID = '".$terminal_ID."' AND `primary` = 1")); 
				$terminal_cur_balance = $d_prev_loadwallet['ending_balance'];
				$terminal_new_balance = $d_prev_loadwallet['ending_balance'] - $load_amount;

				//Update Terminal Load Wallet
				mysqli_query($link, "UPDATE `".DB_PREFIX."terminalload_wallet` SET `primary`=0  WHERE `terminal_ID`='".$terminal_ID."' AND `primary`=1");

				// Load Wallet
				$sql_insert = "INSERT into `".DB_PREFIX."terminalload_wallet` (`terminal_ID`,`transaction_date`,`transaction_time`,`transaction_no`,`transaction_code`,`debit`,`ending_balance`,`dispatcher_ID`,`primary`)
				VALUES ('$terminal_ID','$date','$time','$gen_transcode','$dtcode','$load_amount','$terminal_new_balance','$dispatcher_ID','1')";
				mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysql_error());
				
				//Terminal Transaction
				$transaction_sql_insert = "INSERT into `".DB_PREFIX."terminaltrans` (`ref_no`,`trans_date`,`trans_time`,`transaction_code`,`user_ID`,`debit`,`cash_on_hand`,`terminal_ID`)
				VALUES ('$gen_transcode','$date','$time','$dtcode','$mID','$load_amount','$load_amount','$terminal_ID')";
				mysqli_query($link, $transaction_sql_insert) or die("Insertion Failed:" . mysql_error());

				//MEMBER
				$c_prev_loadwallet = mysqli_fetch_array(mysqli_query($link, "SELECT ending_balance  FROM ".DB_PREFIX."account WHERE user_ID = '".$mID."' AND `primary` = 1")); 
				$member_cur_balance = $c_prev_loadwallet['ending_balance'];
				$member_new_balance = $c_prev_loadwallet['ending_balance'] + $load_amount;

				//Update Account
				mysqli_query($link, "UPDATE `".DB_PREFIX."account` SET `primary`=0  WHERE `user_ID`='".$mID."' AND `primary`=1");

				$sql_insert = "INSERT into `".DB_PREFIX."account` (`user_ID`,`transaction_date`,`transaction_time`,`transaction_no`,`transaction_code`,`credit`,`ending_balance`,`terminal_ID`,`primary`)
				VALUES ('$mID','$date','$time','$gen_transcode','$ctcode','$load_amount','$member_new_balance','$terminal_ID','1')";
				mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysql_error());
				
				//check username in db
				$membername = mysqli_fetch_array(mysqli_query($link, "SELECT CONCAT(fname, ' ', lname)  AS fullname FROM ".DB_PREFIX."users WHERE `user_ID`='".$mID."'"));
				$name = STRTOUPPER($membername['fullname']);

			echo 4;
			exit;

		ELSE:
		
			echo 3;
			exit;
		
		ENDIF;
		
		
	ENDIF;	
}


IF(isset($data["setamount"])):
	//Fetching Values from URL
	$amount=$data['amount1'];
	if(isset($amount)): $_SESSION['temp_amount'] = $amount; ENDIF;
ENDIF;

IF(isset($data["resetamount"])):
	unset($_SESSION['temp_amount']);
ENDIF;





// LOGIN UNIT
if(isset($data["loginunit"]))
{	
	//trim and lowercase ID
	$dID = $data['dID'];
	
	//sanitize TID
	$dID = filter_var($dID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
	
	IF ($dID == '' || $dID == '0'):
		echo "Please Enter Valid Driver ID";
	ELSE:

		//check ID in db
		$driver = mysqli_query($link, "SELECT user_ID, account_status FROM ".DB_PREFIX."users WHERE user_ID='{$dID}' AND (userlevel=7 OR userlevel=10)");
		$driver_exist = mysqli_num_rows($driver);
		IF($driver_exist):
			$driver_update = mysqli_fetch_assoc($driver);
			$driver_stat = $driver_update['account_status'];
			IF($driver_stat==1):
					echo "<h3 class='text-warning text-center'>Driver Account Suspended!</h3>"; // SUCCESS
			ELSEIF($driver_stat==2):
					echo "<h3 class='text-warning text-center'>Driver Account Banned!</h3>"; // SUCCESS
			ELSE:
				// unit status 0-pending: 1-peocessing, 2-approved/active
				$selected_unit = mysqli_query($link, "SELECT unit_ID, unit_status, application_status FROM ".DB_PREFIX."vehicles WHERE (driver_ID1='{$dID}' OR driver_ID2='{$dID}')");
				
				//return total count
				$unit_exist = mysqli_num_rows($selected_unit); //total records
				IF($unit_exist):
				
					$this_unit = mysqli_fetch_array($selected_unit);
					
					$unit_stat = $this_unit['unit_status'];
					$app_stat = $this_unit['application_status'];
					
					IF($unit_stat == 1):
						echo "<h3 class='text-warning text-center'>This unit is suspended.</h3>";
					ELSEIF($unit_stat == 2):
						echo "<h3 class='text-warning text-center'>This unit has been banned.</h3>";
					ELSEIF($unit_stat == 0):
						IF($app_stat == 0):
							echo "<h3 class='text-warning text-center'>Newly applied unit.</h3>";
						ELSEIF($app_stat == 1):
							echo "<h3 class='text-warning text-center'>Unit papers are under process.</h3>";
						ELSEIF($app_stat == 2):
				
							
							$unit_ID = $this_unit['unit_ID'];
							$date = date('Y-m-d');
							$time = date('H:i:s');
							$datetime = $date." ".$time;

							$check_unit_in_bay = mysqli_query($link, "SELECT vehicle_ID FROM ".DB_PREFIX."vehicle_trip_schedule WHERE vehicle_ID='{$unit_ID}' AND selected=1");
							$check_in_bay = mysqli_num_rows($check_unit_in_bay);
							IF(!$check_in_bay):

								$check_unit_entry = mysqli_query($link, "SELECT vehicle_ID, selected FROM ".DB_PREFIX."vehicle_trip_schedule WHERE vehicle_ID='{$unit_ID}' AND terminal_ID='{$terminal_ID}'");
								$check_entry = mysqli_num_rows($check_unit_entry);
								IF(!$check_entry):
									
									$check_other_unit_entry = mysqli_query($link, "SELECT vehicle_ID FROM ".DB_PREFIX."vehicle_trip_schedule WHERE vehicle_ID='{$unit_ID}' AND terminal_ID!='{$terminal_ID}' AND selected=0");
									$check_other_entry = mysqli_num_rows($check_other_unit_entry);
									IF($check_other_entry):
										$sql_delete = "DELETE FROM ".DB_PREFIX."vehicle_trip_schedule WHERE vehicle_ID='{$unit_ID}' AND terminal_ID!='{$terminal_ID}' AND selected=0";				
										mysqli_query($link, $sql_delete) or die("Delete Failed:" . mysqli_error($link));
									ENDIF;

									//GENERATE TRANSACTION REFERENCE CODE
									$gen_tripID = mysqli_real_escape_string($link, GenKey());
									$duplicates = mysqli_query($link, "SELECT trip_ID FROM ".DB_PREFIX."vehicle_trip_schedule WHERE trip_ID='{$gen_tripID}'");
									WHILE(mysqli_fetch_array($duplicates)){
										$gen_tripID = mysqli_real_escape_string($link, GenKey());
									}
									$duplicates = mysqli_query($link, "SELECT trip_ID FROM ".DB_PREFIX."vehicle_trip_history WHERE trip_ID='{$gen_tripID}'");
									WHILE(mysqli_fetch_array($duplicates)){
										$gen_tripID = mysqli_real_escape_string($link, GenKey());
									}
									$gen_tripID = STRTOUPPER($gen_tripID);
									
									$sql_insert = "INSERT into `".DB_PREFIX."vehicle_trip_schedule` (`login_date`,`terminal_ID`, `vehicle_ID`,`driver_ID`,`trip_ID`) 
									VALUES ('$datetime','$terminal_ID','$unit_ID','$dID','$gen_tripID')";				
									mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));

									echo "<h3 class='text-success text-center'>Unit is now Logged-in</h3>"; // SUCCESS
								ELSE:
									echo "<h3 class='text-warning text-center'>Unit is already Logged-in</h3>"; // SUCCESS
								ENDIF;
							ELSE:
								echo "<h3 class='text-warning text-center'>This unit is already in loading bay.</h3>"; // SUCCESS
							ENDIF;
						ENDIF;
					ENDIF;
				ELSE:
					echo "<h3 class='text-warning text-center'>Driver does not have unit to drive yet</h3>"; // SUCCESS
				ENDIF;
			ENDIF;
		ELSE:
			echo "<h3 class='text-warning text-center'>Invalid Driver ID</h3>"; // SUCCESS
		ENDIF;
	ENDIF;
		
//	echo "<script>alert('".$nID."');</script>";
}

IF(isset($data["setcommuter"])):
	IF($_SESSION['accounttable'] == 'block'){
		$_SESSION['accounttable'] = 'none';
	}ELSE{
		$_SESSION['accounttable'] = 'block';
	}
	$_SESSION['terminaltable'] = 'none';
	$_SESSION['vehicletable'] = 'none';
	$_SESSION['loadingtable'] = 'none';
ENDIF;

IF(isset($data["setterminal"])):
	$_SESSION['accounttable'] = 'none';
	IF($_SESSION['terminaltable'] == 'block'){
		$_SESSION['terminaltable'] = 'none';
	}ELSE{
		$_SESSION['terminaltable'] = 'block';
	}
	$_SESSION['vehicletable'] = 'none';
	$_SESSION['loadingtable'] = 'none';
ENDIF;

IF(isset($data["setvehicle"])):
	$_SESSION['accounttable'] = 'none';
	$_SESSION['terminaltable'] = 'none';
	IF($_SESSION['vehicletable'] == 'block'){
		$_SESSION['vehicletable'] = 'none';
	}ELSE{
		$_SESSION['vehicletable'] = 'block';
	}
	$_SESSION['loadingtable'] = 'none';
ENDIF;

IF(isset($data["setloading"])):
	$_SESSION['accounttable'] = 'none';
	$_SESSION['terminaltable'] = 'none';
	$_SESSION['vehicletable'] = 'none';
	IF($_SESSION['loadingtable'] == 'block'){
		$_SESSION['loadingtable'] = 'none';
	}ELSE{
		$_SESSION['loadingtable'] = 'block';
	}
ENDIF;



?>