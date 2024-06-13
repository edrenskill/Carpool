<?php
require_once '../../settings/connect.php';
if(session_id() == '') { page_protect(); } // START SESSIONS
if(!isset($_SESSION['ADM'])): header('location: login'); ENDIF;

//Change Occupation
//$user_ID = $_SESSION['user_id'];
foreach($_POST as $key => $value) { $data[$key] = filter($value); }

IF(isset($data["setamount"])):
	//Fetching Values from URL
	$amount=$data['amount1'];
	if(isset($amount)): $_SESSION['temp_amount'] = $amount; ENDIF;
ENDIF;

IF(isset($data["resetamount"])):
	unset($_SESSION['temp_amount']);
ENDIF;

if(isset($data["validate"]))
{	
	//trim and lowercase ID
	$tID = STRTOUPPER($data['tID']);
	$dID = $data['dID'];
	$load = $data['load'];
	
	IF ($tID == '' || $dID == ''):
		echo "Please Enter Valid ID Numbers";
	ELSE:
		//sanitize TID
		$tID = filter_var($tID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);

		//check record in db
		$tresults = mysqli_query($link, "SELECT terminal_ID FROM `".DB_PREFIX."terminal` WHERE `terminal_ID`='{$tID}' AND `operational` = '1'");
		//return total count
		$TID_exist = mysqli_num_rows($tresults); //total records
		IF($TID_exist):
			//sanitize DID
			$dID = filter_var($dID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);

			//check record in db
			$dresults = mysqli_query($link, "SELECT user_ID FROM `".DB_PREFIX."users` WHERE `terminal_ID`='{$tID}' AND `user_ID` = '{$dID}' AND userlevel=2");
			//return total count
			$DID_exist = mysqli_num_rows($dresults); //total records
			IF($DID_exist):
				// do all transactions here
				echo 3;
				
				$date = date('Y-m-d');
				$time = date('H:i:s');
				$user_ID = $_SESSION['act_ID'];
				$datetime = $date." ".$time;
				
				//GENERATE TRANSACTION REFERENCE CODE
				$batch = mysqli_real_escape_string($link, date("Y"));
				$gen_transcode = $batch.mysqli_real_escape_string($link, GenKey());
				$duplicates = mysqli_query($link, "SELECT transaction_no FROM ".DB_PREFIX."terminalload_wallet WHERE transaction_no='$gen_transcode'");
				WHILE(mysqli_fetch_array($duplicates)){
					$gen_transcode = $batch.mysqli_real_escape_string($link, GenID());
				}
				$gen_transcode = STRTOUPPER($gen_transcode);
				
				//GET TRANSACTION CODE DETAILS
				$d_transcode = mysqli_fetch_array(mysqli_query($link, "SELECT transaction_code  FROM ".DB_PREFIX."transactioncode WHERE transaction_code = 'RLD'")); 
				$dtcode = $d_transcode['transaction_code'];

				//TERMINAL
				$d_prev_loadwallet = mysqli_fetch_array(mysqli_query($link, "SELECT `ending_balance` FROM `".DB_PREFIX."terminalload_wallet` WHERE `terminal_ID` = '".$tID."' AND `primary` = '1'")); 
				$terminal_cur_balance = $d_prev_loadwallet['ending_balance'];
				$terminal_new_balance = $d_prev_loadwallet['ending_balance'] + $load;

				//Update Terminal Load Wallet
				mysqli_query($link, "UPDATE `".DB_PREFIX."terminalload_wallet` SET `primary`=0  WHERE `terminal_ID`='".$tID."' AND `primary`=1");

				//TERMINAL Load Wallet
				$sql_insert = "INSERT into `".DB_PREFIX."terminalload_wallet` (`terminal_ID`,`transaction_date`,`transaction_time`,`transaction_no`,`transaction_code`,`credit`,`ending_balance`,`dispatcher_ID`,`primary`)
				VALUES ('$tID','$date','$time','$gen_transcode','$dtcode','$load','$terminal_new_balance','$dID','1')";
				mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysql_error());
				
				//Terminal Transaction
				$transaction_sql_insert = "INSERT into `".DB_PREFIX."terminaltrans` (`ref_no`,`trans_date`,`trans_time`,`transaction_code`,`user_ID`,`credit`,`terminal_ID`)
				VALUES ('$gen_transcode','$date','$time','$dtcode','$user_ID','$load','$tID')";
				mysqli_query($link, $transaction_sql_insert) or die("Insertion Failed:" . mysql_error());

				//TRANSACTION HISTORY
				$sql_insert = "INSERT into `".DB_PREFIX."transaction_history` (`date`,`transaction_type`,`transaction_number`,`user_ID`)
				VALUES ('$datetime','$dtcode','$gen_transcode','$user_ID')";
				mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysql_error());	
			ELSE:
				echo 2;
			ENDIF;
		ELSE:
			echo 1;
		ENDIF;
	ENDIF;
}



if(isset($data["assignval"]))
{	
	//trim and lowercase ID
	$oID = $data['oID'];
	$nID = $data['nID'];
	
	//sanitize TID
	$nID = filter_var($nID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
	$oID = filter_var($oID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
	
	IF ($nID == '' || $oID == '0'):
		echo "Please Enter Valid ID Numbers";
	ELSE:

		//check ID in db
		$cardresults = mysqli_query($link, "SELECT `card_number`, `status` FROM `".DB_PREFIX."idcards` WHERE `card_number`='{$nID}'");
		
		//return total count
		$NID_exist = mysqli_num_rows($cardresults); //total records
		IF($NID_exist):
			
			$card_status = mysqli_fetch_array($cardresults);
			IF($card_status['status'] == 0):

				//check record in db
				$idresults = mysqli_query($link, "SELECT user_ID FROM `".DB_PREFIX."users` WHERE `user_ID`='{$oID}'");				
				//return total count
				$OID_exist = mysqli_num_rows($idresults); //total records
				IF($OID_exist):
					// do all transactions here
					
					$date = date('Y-m-d');
					$time = date('H:i:s');
					$datetime = $date." ".$time;
					
					//Update USER ID
					mysqli_query($link, "UPDATE `".DB_PREFIX."users` SET `user_ID`='{$nID}', `approval`=1 WHERE `user_ID`='{$oID}'");
					
					//Update UPDATE CARD STATUS
					mysqli_query($link, "UPDATE `".DB_PREFIX."idcards` SET `status`='1', `disposed`='1' WHERE `card_number`='{$nID}'");
					
					//Update Contact
					mysqli_query($link, "UPDATE `".DB_PREFIX."contacts` SET `UID`='{$nID}' WHERE `UID`='{$oID}'");
					
					mysqli_query($link, "UPDATE ".DB_PREFIX."bank_accounts SET  user_ID='{$nID}' WHERE user_ID='{$oID}'");
					mysqli_query($link, "UPDATE ".DB_PREFIX."member_trip_history SET  user_ID='{$nID}' WHERE user_ID='{$oID}'");
					mysqli_query($link, "UPDATE ".DB_PREFIX."member_incentives SET  user_ID='{$nID}' WHERE user_ID='{$oID}'");				
					mysqli_query($link, "UPDATE ".DB_PREFIX."vehicle_trip_history SET  driver_ID='{$nID}' WHERE driver_ID='{$oID}'");
					mysqli_query($link, "UPDATE ".DB_PREFIX."vehicles SET  owner_ID='{$nID}' WHERE owner_ID='{$oID}'");
					mysqli_query($link, "UPDATE ".DB_PREFIX."vehicles SET  driver_ID1='{$nID}' WHERE driver_ID1='{$oID}'");
					mysqli_query($link, "UPDATE ".DB_PREFIX."vehicles SET  driver_ID2='{$nID}' WHERE driver_ID2='{$oID}'");
					mysqli_query($link, "UPDATE ".DB_PREFIX."contacts SET  UID='{$nID}' WHERE UID='{$oID}'");
					mysqli_query($link, "UPDATE ".DB_PREFIX."driver_account SET  driver_ID='{$nID}' WHERE driver_ID='{$oID}'");
					mysqli_query($link, "UPDATE ".DB_PREFIX."terminaltrans SET  user_ID='{$nID}' WHERE user_ID='{$oID}'");
					mysqli_query($link, "UPDATE ".DB_PREFIX."transaction_history SET  user_ID='{$nID}' WHERE user_ID='{$oID}'");
					mysqli_query($link, "UPDATE ".DB_PREFIX."account_statua SET  user_ID='{$nID}' WHERE user_ID='{$oID}'");
					mysqli_query($link, "UPDATE ".DB_PREFIX."terminalload_wallet SET  dispatcher_ID='{$nID}' WHERE dispatcher_ID='{$oID}'");
					mysqli_query($link, "UPDATE ".DB_PREFIX."user_log_history SET  user_ID='{$nID}' WHERE user_ID='{$oID}'");
					mysqli_query($link, "UPDATE ".DB_PREFIX."driver_credentials SET  user_ID='{$nID}' WHERE user_ID='{$oID}'");
						
					
					
					$checkDR = "../../myaccount/members/".$oID;

					if (file_exists($checkDR)) {
						rename("../../myaccount/members/".$oID,"../../myaccount/members/".$nID);
					}
					
					$_SESSION['newmember'] = $nID;
					
					echo "<h4>New Member ID: <strong>".$nID."</strong></h4>"; // SUCCESS
					
				ELSE:
					echo 3; // OLD ID DOES NOT EXIST
				ENDIF;
			ELSE:
				//echo "<script>alert('2');</script>"; 
				echo 2; // Not Available
			ENDIF;
		ELSE:
			echo 1; // Not Exist
		ENDIF;
	ENDIF;
		
//	echo "<script>alert('".$nID."');</script>";
}



if(isset($data["registercard"]))
{	
	//trim and lowercase ID
	$nID = $data['nID'];
	$tID = $data['tID'];
	$cType = $data['cType'];
	
	//sanitize TID
	$nID = filter_var($nID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
	$tID = filter_var($tID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
	
	IF ($nID == '' || $nID == '0'):
		echo "Please Enter Valid ID Numbers.";
	ELSEIF(!isset($cType) || $cType ==0):
		echo "Please Select Card Type.";
	ELSE:

		//check ID in db
		$cardresults = mysqli_query($link, "SELECT `card_number`, `status` FROM `".DB_PREFIX."idcards` WHERE `card_number`='{$nID}'");
		
		//return total count
		$NID_exist = mysqli_num_rows($cardresults); //total records
		IF($NID_exist):
			
			$card_status = mysqli_fetch_array($cardresults);
			IF($card_status['status'] == 0):

				echo 1;
				
			ELSE:
				echo 2; // Not Available
			ENDIF;
		ELSE:
			$_SESSION['CARDTYPE'] = $cType;
			$sql_insert = "INSERT into `".DB_PREFIX."idcards` (`card_number`,`terminal_ID`,`card_type`,`status`) VALUES ('$nID','$tID','$cType','0')";				
			mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
			
			echo "<h4>New Card with No. <strong>".$nID."</strong> has been added to database.</h4>"; // SUCCESS

		ENDIF;
	ENDIF;

}

IF(isset($data["printid"])):
	$cID = $data['printid'];
	mysqli_query($link, "UPDATE `".DB_PREFIX."users` SET `gen_cRD`=1 WHERE `user_ID`='{$CID}'");
					
ENDIF;




IF(isset($data["setcommuter"])):
	IF($_SESSION['commutertable'] == 'block'){
		$_SESSION['commutertable'] = 'none';
	}ELSE{
		$_SESSION['commutertable'] = 'block';
	}
	$_SESSION['terminaltable'] = 'none';
	$_SESSION['vehicletable'] = 'none';
	$_SESSION['loadingtable'] = 'none';
ENDIF;

IF(isset($data["setterminal"])):
	$_SESSION['commutertable'] = 'none';
	IF($_SESSION['terminaltable'] == 'block'){
		$_SESSION['terminaltable'] = 'none';
	}ELSE{
		$_SESSION['terminaltable'] = 'block';
	}
	$_SESSION['vehicletable'] = 'none';
	$_SESSION['loadingtable'] = 'none';
ENDIF;

IF(isset($data["setvehicle"])):
	$_SESSION['commutertable'] = 'none';
	$_SESSION['terminaltable'] = 'none';
	IF($_SESSION['vehicletable'] == 'block'){
		$_SESSION['vehicletable'] = 'none';
	}ELSE{
		$_SESSION['vehicletable'] = 'block';
	}
	$_SESSION['loadingtable'] = 'none';
ENDIF;

IF(isset($data["setloading"])):
	$_SESSION['commutertable'] = 'none';
	$_SESSION['terminaltable'] = 'none';
	$_SESSION['vehicletable'] = 'none';
	IF($_SESSION['loadingtable'] == 'block'){
		$_SESSION['loadingtable'] = 'none';
	}ELSE{
		$_SESSION['loadingtable'] = 'block';
	}
ENDIF;

//check we have ownerID post var
if(isset($data["ownerID"]))
{
	//check if its ajax request, exit script if its not
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
		die();
	}

	//trim ID
	$ownerID =  $data["ownerID"];

	//sanitize ID
	$ownerID = filter_var($ownerID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);

	//check ID in db
	$results = mysqli_query($link, "SELECT ID FROM ".DB_PREFIX."users WHERE user_ID='{$ownerID}' AND (userlevel='8' || userlevel='10')");

	//return total count
	$username_exist = mysqli_num_rows($results); //total records
	//if value is more than 0, username is not available
	if($username_exist) {
		die('<img src="'.getBaseUrl().'myaccount/images/available.png" />');
	}else{
		die('<img src="'.getBaseUrl().'myaccount/images/not-available.png" />Owner ID not found!');
	}
}

if(isset($data["driverID1"]))
{
	//check if its ajax request, exit script if its not
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
		die();
	}

	//trim ID
	$driverID1 =  $data["driverID1"];

	//sanitize ID
	$driverID1 = filter_var($driverID1, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);

	//check ID in db
	$Dresults = mysqli_query($link, "SELECT ID FROM ".DB_PREFIX."users WHERE user_ID='{$driverID1}' AND (userlevel = '7' OR userlevel = '10')");

	//return total count
	$driver1_exist = mysqli_num_rows($Dresults); //total records
	if($driver1_exist) {
		$checked = mysqli_num_rows(mysqli_query($link, "SELECT ID FROM ".DB_PREFIX."vehicles WHERE driver_ID1='{$driverID1}' OR driver_ID2='{$driverID1}'"));
		IF($checked!=0):
			die('<img src="'.getBaseUrl().'myaccount/images/not-available.png" />Driver already assigned to other unit!');
		ELSE:
			die('Driver 1 <img src="'.getBaseUrl().'myaccount/images/available.png" />');
		ENDIF;
	}else{
		die('<img src="'.getBaseUrl().'myaccount/images/not-available.png" />Driver 1 ID not found!');
	}
}

if(isset($data["driverID2nd"]))
{
	//check if its ajax request, exit script if its not
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
		die();
	}

	//trim ID
	$driverID2 = $data["driverID2nd"];
	$driver1ID = $data["driver1ID"];

	//sanitize ID
	$driverID2 = filter_var($driverID2, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
	$driver1ID = filter_var($driver1ID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
	
	IF(!$driver1ID):
		die('<img src="'.getBaseUrl().'myaccount/images/not-available.png" />Driver 1 must be filled first!');
	ELSEIF($driverID2 != $driver1ID):
		//check ID in db
		$results = mysqli_query($link, "SELECT ID FROM ".DB_PREFIX."users WHERE user_ID='{$driverID2}' AND (userlevel = '7' OR userlevel = '10')");

		//return total count
		$username_exist = mysqli_num_rows($results); //total records
		//if value is more than 0, username is not available
		if($username_exist) {
			$checked = mysqli_num_rows(mysqli_query($link, "SELECT ID FROM ".DB_PREFIX."vehicles WHERE driver_ID1='{$driverID2}' OR driver_ID2='{$driverID2}'"));
			IF($checked!=0):
				die('<img src="'.getBaseUrl().'myaccount/images/not-available.png" />Driver already assigned to other unit!');
			ELSE:
				die('Driver 2 <img src="'.getBaseUrl().'myaccount/images/available.png" />');
			ENDIF;
		}else{
			die('<img src="'.getBaseUrl().'myaccount/images/not-available.png" />Driver 2 ID not found!');
		}
	ELSE:
		die('<img src="'.getBaseUrl().'myaccount/images/not-available.png" />Driver 2 ID must not be the same as driver 1 ID');
	ENDIF;
}

//PLATE
if(isset($data["plate"]))
{
	//check if its ajax request, exit script if its not
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
		die();
	}

	//trim data
	$plate =  $data["plate"];

	//sanitize ID
	$plate = filter_var($plate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
	
	//check ID in db
	$results = mysqli_query($link, "SELECT ID FROM ".DB_PREFIX."vehicles WHERE plate_number='$plate'");

	//return total count
	$plate_exist = mysqli_num_rows($results); //total records
	//if value is more than 0, plate is not available
	if($plate_exist) {
		die('<img src="'.getBaseUrl().'myaccount/images/not-available.png" />Vehicle with the same Plate Number is already exist!');
	}else{
		die('<img src="'.getBaseUrl().'myaccount/images/available.png" />');
	}
}


//CHASSIS
if(isset($data["chassis"]))
{
	//check if its ajax request, exit script if its not
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
		die();
	}

	//trim data
	$chassis =  $data["chassis"];

	//sanitize ID
	$chassis = filter_var($chassis, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
	
	//check ID in db
	$results = mysqli_query($link, "SELECT ID FROM ".DB_PREFIX."vehicles WHERE chassis='$chassis'");

	//return total count
	$chassis_exist = mysqli_num_rows($results); //total records
	//if value is more than 0, chassis is not available
	if($chassis_exist) {
		die('<img src="'.getBaseUrl().'myaccount/images/not-available.png" />Vehicle with the same Chassis Number is already exist!');
	}else{
		die('<img src="'.getBaseUrl().'myaccount/images/available.png" />');
	}
}

//ENGINE
if(isset($data["engine"]))
{
	//check if its ajax request, exit script if its not
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
		die();
	}

	//trim data
	$engine =  $data["engine"];

	//sanitize ID
	$engine = filter_var($engine, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
	
	//check ID in db
	$results = mysqli_query($link, "SELECT ID FROM ".DB_PREFIX."vehicles WHERE engine='$engine'");

	//return total count
	$engine_exist = mysqli_num_rows($results); //total records
	//if value is more than 0, engine is not available
	if($engine_exist) {
		die('<img src="'.getBaseUrl().'myaccount/images/not-available.png" />Vehicle with the same Engine Number is already exist!');
	}else{
		die('<img src="'.getBaseUrl().'myaccount/images/available.png" />');
	}
}

//////////////////// DISPATCHER ////////////////
//check dispatcherID
if(isset($data["dispatcherID"]))
{
	//check if its ajax request, exit script if its not
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
		die();
	}

	//trim ID
	$dispatcherID =  $data["dispatcherID"];

	//sanitize ID
	$dispatcherID = filter_var($dispatcherID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);

	//check ID in db
	$results = mysqli_query($link, "SELECT ID, terminal_ID FROM ".DB_PREFIX."users WHERE user_ID='$dispatcherID' AND userlevel = '2' AND approval=1");

	//return total count
	$username_exist = mysqli_num_rows($results); //total records
	//if value is more than 0, username is not available
	if($username_exist) {
		WHILE($ID_status = mysqli_fetch_array($results)){
			$checked = $ID_status['terminal_ID'];
			IF($checked!=0):
				die('<img src="'.getBaseUrl().'myaccount/images/not-available.png" />Dispatcher was assigned to other terminal');
			ELSE:
				die('<img src="'.getBaseUrl().'myaccount/images/available.png" />');
			ENDIF;
		}
	}else{
		die('<img src="'.getBaseUrl().'myaccount/images/not-available.png" />Dispatcher ID not found!');
	}
}

//check terminal name exist
if(isset($data["TerminalName"]))
{
	//check if its ajax request, exit script if its not
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
		die();
	}

	//trim ID
	$terminalName =  $data["TerminalName"];

	//sanitize ID
	$terminalName = filter_var($terminalName, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);

	//check ID in db
	$results = mysqli_query($link, "SELECT terminal_name FROM ".DB_PREFIX."terminal WHERE terminal_name='{$terminalName}'");

	//return total count
	$terminalname_exist = mysqli_num_rows($results); //total records
	//if value is more than 0, username is not available
	if($terminalname_exist) {
		die('<img src="'.getBaseUrl().'myaccount/images/not-available.png" />Terminal Name already exist!');
	}else{
		die('<img src="'.getBaseUrl().'myaccount/images/available.png" />');
	}
}

if(isset($data['userID']))
{
	//check if its ajax request, exit script if its not
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
		die();
	}

	//trim ID
	$userID =  $data['userID'];

	//sanitize ID
	$userID = filter_var($userID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);

	//check ID in db
	$results = mysqli_query($link, "SELECT ".DB_PREFIX."bank_accounts.user_ID FROM ".DB_PREFIX."bank_accounts JOIN ".DB_PREFIX."users ON ".DB_PREFIX."bank_accounts.user_ID=".DB_PREFIX."users.user_ID WHERE ".DB_PREFIX."bank_accounts.user_ID='{$userID}' AND (".DB_PREFIX."users.userlevel=10 || ".DB_PREFIX."users.userlevel=7)");

	//return total count
	$record_exist = mysqli_num_rows($results); //total records
	//if value is more than 0, username is not available
	if($record_exist) {
		die('<img src="'.getBaseUrl().'myaccount/images/not-available.png" />Driver has existing account!');
	}else{
		die('<img src="'.getBaseUrl().'myaccount/images/available.png" />');
	}
}


if(isset($data['did']))
{
	//check if its ajax request, exit script if its not
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
		die();
	}

	//trim ID
	$DID1 =  $data['did'];
	$TID = $data['tid'];

	//sanitize ID
	$DID1 = filter_var($DID1, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
	$TID = filter_var($TID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);

	//check ID in db
	
	$results = mysqli_query($link, "SELECT CONCAT(fname,' ',lname) AS fullname, user_ID, terminal_ID FROM ".DB_PREFIX."users WHERE user_ID='{$DID1}' AND userlevel='2'");

	//return total count
	$record_exist = mysqli_num_rows($results); //total records
	//if value is more than 0, username is not available
	if($record_exist) {
		
		$check = mysqli_fetch_array($results);
		$fullname = $check['fullname'];
		$terminal = $check['terminal_ID'];
		IF($terminal != "" && $terminal == $TID):
			die ('<img src="'.getBaseUrl().'myaccount/images/not-available.png" /> Dispatcher already assigned to this terminal!');
		ELSEIF($terminal != "" && $terminal != $TID):
			die ('<img src="'.getBaseUrl().'myaccount/images/not-available.png" /> Dispatcher already assigned to other terminal!');
		ELSE:
			die ('<img src="'.getBaseUrl().'myaccount/images/available.png" /> Dispatcher Name:<span class=\'text-success\'><strong>'.$fullname.'</strong></span> - ID:<span class=\'text-success\'><strong>'.$DID1.'</strong></span>');
		ENDIF;
		
	}else{
		die ('<img src="'.getBaseUrl().'myaccount/images/not-available.png" />Invalid Dispatcher ID!');
	}
}
?>