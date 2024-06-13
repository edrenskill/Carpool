<?php
require_once '../../settings/connect.php';
if(session_id() == '') { page_protect(); } // START SESSIONS

//Change Occupation
$user_ID = $_SESSION['user_id'];
$terminal_ID = $_SESSION['terminal_ID'];
foreach($_POST as $key => $value) { $data[$key] = filter($value); }

IF(isset($_POST) && array_key_exists('updateremit',$_POST)):

	$DID = $data['ID'];
	$Rcode = $data['Rcode'];
	$amount = $data['amount'];
	$Records_counter = 0;
	$date = date("Y-m-d");
	$time = date("h:i:s");

	foreach( $DID as $key => $ID ) {

		$RemittanceCode = strtoupper($Rcode[$key]);
		$total = $amount[$key];
		IF(isset($RemittanceCode) && $RemittanceCode!=''):
			$Records_counter += 1;
			$transaction_sql_insert = "INSERT into `".DB_PREFIX."remittance` (`terminal_ID`,`amount`,`date`,`time`,`status`,`reference_code`) VALUES ('$terminal_ID','$total','$date','$time','1','$RemittanceCode')";
			mysqli_query($link, $transaction_sql_insert) or die("Insertion Failed:" . mysql_error());

				$variable = str_replace(' ', '', $ID);
				$explode = explode(',',$variable);
				$res = array_combine($explode,$explode);
				foreach($res as $data => $da){
					mysqli_query($link, "UPDATE ".DB_PREFIX."terminaltrans SET remitted='1', remittance_code='{$RemittanceCode}' WHERE transaction_code='LDC' AND terminal_ID='{$terminal_ID}' AND ref_no='{$da}'") or die(mysql_error());
				}
		ENDIF;
	}
	$_SESSION['updatedRecords'] = "A total of ".$Records_counter." Remittance has been updated";
	header ("location: ../reloading");
	Exit;
ENDIF;
?>+