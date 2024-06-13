<?php
require_once '../../settings/connect.php';
if(session_id() == '') { session_start(); } // START SESSIONS

foreach($_POST as $key => $value) { $data[$key] = filter($value); }

// Board Passenger
if(isset($data["checkcard"]))
{	
	//trim and lowercase ID
	$CID = $data['CID'];
	$TID = $data['TID'];
	
	IF ($TID == '' || $TID == '0'):
		die ("<div class='alert alert-danger'>Please enter Temporary ID number</div>");
	ELSEIF ($CID == '' || $CID == '0'):
		die ("<div class='alert alert-danger'>Please enter CE card number</div>");
	ELSE:
	
	
	
		$TID = filter_var($TID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);

		//check record in db
		$tempcardsearch = mysqli_query($link, "SELECT `user_ID` FROM `".DB_PREFIX."users` WHERE `user_ID`='{$TID}'");
		$ID_result = mysqli_num_rows($tempcardsearch); //total records

		IF($ID_result):

			//sanitize MID
			$CID = filter_var($CID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);

			//check record in db
			$cardsearch = mysqli_query($link, "SELECT `card_number`, `terminal_ID`, `status` FROM `".DB_PREFIX."idcards` WHERE `card_number`='{$CID}'");
			$CARD_result = mysqli_num_rows($cardsearch); //total records

			IF($CARD_result):
				$card_status = mysqli_fetch_array($cardsearch);
				$terminal_ID = $card_status['terminal_ID'];
				$status = $card_status['status'];
				IF($status == 1):
					die("<div class='alert alert-danger'>Card number is invalid</div>");
				ELSE:
				
					$date = date('Y-m-d');
					$time = date('H:i:s');
					
					//GENERATE TRANSACTION NUMBER
					$batch = mysqli_real_escape_string($link, date("Y"));
					$gen_transcode = $batch.mysqli_real_escape_string($link, GenKey());
					$duplicates = mysqli_query($link, "SELECT transaction_no FROM ".DB_PREFIX."terminalload_wallet WHERE transaction_no='$gen_transcode'");
					WHILE(mysqli_fetch_array($duplicates)){
						$gen_transcode = $batch.mysqli_real_escape_string($link, GenKey());
					}
					$gen_transcode = STRTOUPPER($gen_transcode);
				
					// GET INITIAL CARLOAD SETTINGS
					$initialcardload = mysqli_fetch_array(mysqli_query($link, "SELECT initial_card_load FROM ".DB_PREFIX."terminal_settings"));
					$nitial_load = $initialcardload['initial_card_load'];
					
					// UPDATE ID AND ACTIVATE ACCOUNT
					$md5_id = md5($CID);
					$query = mysqli_query($link, "UPDATE ".DB_PREFIX."users SET approval='1', user_ID='{$CID}', md5_id='{$md5_id}' WHERE user_ID='{$TID}'");
					$query = mysqli_query($link, "UPDATE ".DB_PREFIX."idcards SET status='1' WHERE card_number='{$CID}'");
					
					$sql_insert = "INSERT into `".DB_PREFIX."account` (`user_ID`,`transaction_date`,`transaction_time`,`transaction_no`,`transaction_code`,`credit`,`ending_balance`,`terminal_ID`,`primary`)
					VALUES ('$CID','$date','$time','$gen_transcode','ACT','$nitial_load','$nitial_load','$terminal_ID','1')";
					mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysql_error());

					die("
						<script>
							$('#Activate').prop('disabled', true);
							$('#CardID').prop('disabled', true);
							$('#activated').show('slow');
							$('#activatefield').hide();
						</script>
						<div class='alert alert-success'>Your account has been activated!</div>
						<p>Please click <a href='login' style='text_decoration:underline;color:#0000FF;'>Login</a> to continue.</p>
					");
					unset($_SESSION['act_ID']); unset($_SESSION['full_name']);
				ENDIF;
			ELSE:
				die("<div class='alert alert-danger'>CE Card number not valid!</div>");
			ENDIF;
		ELSE:
			die("<div class='alert alert-danger'>Temporary ID number not found!</div>");
		ENDIF;
	ENDIF;
}
?>