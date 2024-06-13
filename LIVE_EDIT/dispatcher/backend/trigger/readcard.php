<?php
require_once '../../settings/connect.php';
if(session_id() == '') { session_start(); } // START SESSIONS

foreach($_POST as $key => $value) { $data[$key] = filter($value); }

// Change New ID
if(isset($data["checknewcard"]))
{	
	//trim and lowercase ID
	$NCID = $data['NCID'];
	$OID = $_SESSION['IDusers'];
	
	IF ($NCID == '' || $NCID == '0'):
		die ("<div class='alert alert-danger'>Please Enter Card Number</div>");
	ELSE:
		//sanitize MID
		$NCID = filter_var($NCID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);

		//check record in db
		$cardsearch = mysqli_query($link, "SELECT `card_number`, `status` FROM `".DB_PREFIX."idcards` WHERE `card_number`='{$NCID}'");
		$CARD_result = mysqli_num_rows($cardsearch); //total records

		IF($CARD_result):
			$card_status = mysqli_fetch_array($cardsearch);
			$status = $card_status['status'];
			IF($status == 1):
				die("<div class='alert alert-danger'>Card number is invalid</div>");
			ELSE:
			
				$checkuserlevel = mysqli_fetch_array(mysqli_query($link, "SELECT userlevel FROM ".DB_PREFIX."users WHERE user_ID='{$OID}'"));
				$user_level = $checkuserlevel['userlevel']; 
				IF($user_level == 7):
					$driverpos = mysqli_query($link, "SELECT driver_ID1 FROM ".DB_PREFIX."vehicles WHERE driver_ID1='{$OID}'");
					$count = mysqli_num_rows($driverpos);
					IF($count):
						$condition = "driver_ID1='{$NCID}' WHERE driver_ID1='{$OID}'";
					ELSE:
						$driverpos = mysqli_query($link, "SELECT driver_ID1 FROM ".DB_PREFIX."vehicles WHERE driver_ID2='{$OID}'");
						$count = mysqli_num_rows($driverpos);
						IF($count):
						$condition = "driver_ID2='{$NCID}' WHERE driver_ID2='{$OID}'";
						ENDIF;
					ENDIF;
				ELSEIF($user_level == 8):
					$condition = "owner_ID='{$NCID}' WHERE owner_ID='{$OID}'";
				ELSEIF($user_level == 10):
					$condition = "owner_ID='{$NCID}', driver_ID1='{$NCID}' WHERE owner_ID='{$OID}' AND driver_ID1='{$OID}'";
				ENDIF;

				// UPDATE ID AND ACTIVATE ACCOUNT
				$query = mysqli_query($link, "UPDATE ".DB_PREFIX."users SET  user_ID='{$NCID}', approval=1 WHERE user_ID='{$OID}'");
				$query = mysqli_query($link, "UPDATE ".DB_PREFIX."idcards SET status='1' WHERE card_number='{$NCID}'");
				$query = mysqli_query($link, "UPDATE ".DB_PREFIX."account SET  user_ID='{$NCID}' WHERE user_ID='{$OID}'");
				$query = mysqli_query($link, "UPDATE ".DB_PREFIX."bank_accounts SET  user_ID='{$NCID}' WHERE user_ID='{$OID}'");
				$query = mysqli_query($link, "UPDATE ".DB_PREFIX."member_trip_history SET  user_ID='{$NCID}' WHERE user_ID='{$OID}'");
				$query = mysqli_query($link, "UPDATE ".DB_PREFIX."member_incentives SET  user_ID='{$NCID}' WHERE user_ID='{$OID}'");				
				$query = mysqli_query($link, "UPDATE ".DB_PREFIX."vehicle_trip_history SET  driver_ID='{$NCID}' WHERE driver_ID='{$OID}'");
				$query = mysqli_query($link, "UPDATE ".DB_PREFIX."contacts SET  UID='{$NCID}' WHERE UID='{$OID}'");
				$query = mysqli_query($link, "UPDATE ".DB_PREFIX."driver_account SET  driver_ID='{$NCID}' WHERE driver_ID='{$OID}'");
				$query = mysqli_query($link, "UPDATE ".DB_PREFIX."terminaltrans SET  user_ID='{$NCID}' WHERE user_ID='{$OID}'");
				$query = mysqli_query($link, "UPDATE ".DB_PREFIX."transaction_history SET  user_ID='{$NCID}' WHERE user_ID='{$OID}'");
				$query = mysqli_query($link, "UPDATE ".DB_PREFIX."account_statua SET  user_ID='{$NCID}' WHERE user_ID='{$OID}'");
				$query = mysqli_query($link, "UPDATE ".DB_PREFIX."terminalload_wallet SET  dispatcher_ID='{$NCID}' WHERE dispatcher_ID='{$OID}'");
				$query = mysqli_query($link, "UPDATE ".DB_PREFIX."user_log_history SET  user_ID='{$NCID}' WHERE user_ID='{$OID}'");
				$query = mysqli_query($link, "UPDATE ".DB_PREFIX."driver_credentials SET  user_ID='{$NCID}' WHERE user_ID='{$OID}'");
				
				

				$query = mysqli_query($link, "UPDATE ".DB_PREFIX."vehicles SET  {$condition}");
		
				die("
					<script>
						$('#activate').prop('disabled', true);
						$('#newCardID').prop('disabled', true);
						$('#activated').show('slow');
						$('#activatefield').hide();
					</script>
					<div class='alert alert-success'>Your account has been updated!</div>
					<div class='alert alert-success'>New User ID: ".$NCID."</div>
					
				");
				
				unset($_SESSION['IDusers']); 
			ENDIF;
		ELSE:
			die("<div class='alert alert-danger'>Card number not found!</div>");
		ENDIF;
	ENDIF;
}
?>