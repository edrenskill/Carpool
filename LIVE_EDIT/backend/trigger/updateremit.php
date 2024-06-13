<?php
require_once '../../settings/connect.php';
if(session_id() == '') { page_protect(); } // START SESSIONS

//Change Occupation
$user_ID = $_SESSION['user_id'];
//foreach($_POST as $key => $value) { $data[$key] = filter($value); }

IF(isset($_POST) && array_key_exists('updateremit',$_POST)):

	//CUT OFF SETTINGS				
	$terminal_settings = mysqli_fetch_array(mysqli_query($link, "SELECT cut_off FROM ".DB_PREFIX."terminal_settings"));
	$cutoff_time = $terminal_settings['cut_off'];
	$date = date('Y-m-d',strtotime("-1 days"));

	IF(!isset($_SESSION['selecteddate'])):
		$cutoff_start = $date." ".$cutoff_time;
		$cutoff_end = date('Y-m-d')." ".$cutoff_time;
	ELSE:
		$cutoff_start = $_SESSION['cutstart']." ".$cutoff_time;
		$cutoff_end = $_SESSION['cutend']." ".$cutoff_time;
	ENDIF;

	$DID = $_POST['ID'];
	$Rcode = $_POST['Rcode'];
	$Records_counter = 0;

	foreach( $DID as $key => $ID ) {
		
		$RemittanceCode = $Rcode[$key];
		IF(isset($RemittanceCode) && $RemittanceCode!=''):
			$Records_counter += 1;
			$records = mysqli_query($link, "SELECT A.driver_ID, A.trip_ID, B.trip_ID FROM ".DB_PREFIX."vehicle_trip_history A, ".DB_PREFIX."driver_account B WHERE B.remitted=0 AND B.trip_ID=A.trip_ID AND `time_date` BETWEEN '{$cutoff_start}' AND '{$cutoff_end}' AND A.driver_ID='{$ID}'");
			WHILE($drivers = mysqli_fetch_array($records)){
				$Trip_ID = $drivers['trip_ID'];
				//Update Terminal Load Wallet
				mysqli_query($link, "UPDATE `".DB_PREFIX."driver_account` SET `bank_ref_no`='{$RemittanceCode}', `remitted`='1'  WHERE `trip_ID`='{$Trip_ID}' AND `driver_ID`='{$ID}'");
			}
		ENDIF;
	}
	
	$_SESSION['updatedRecords'] = "A total of ".$Records_counter." Remittance has been updated";
	header ("location: ../payout");
	Exit;
	

ENDIF;
?>