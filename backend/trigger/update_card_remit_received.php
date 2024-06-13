<?php
require_once '../../settings/connect.php';
if(session_id() == '') { page_protect(); } // START SESSIONS

//Change Occupation
//foreach($_POST as $key => $value) { $data[$key] = filter($value); }

IF(isset($_POST) && array_key_exists('confirm',$_POST)):

	$DID = $_POST['ID'];
	$Rcode = $_POST['Rcode'];
	$Records_counter = 0;

	foreach( $DID as $key => $ID ) {
		
		$RemittanceCode = strtoupper($Rcode[$key]);
		IF(isset($RemittanceCode) && $RemittanceCode!=''):
			$Records_counter += 1;
			mysqli_query($link, "UPDATE ".DB_PREFIX."card_sale SET remit_confirmed='1'  WHERE card_number IN ({$ID}) AND remitted='1' AND r_code='{$RemittanceCode}'");
			mysqli_query($link, "UPDATE ".DB_PREFIX."terminaltrans SET remit_confirmed='1'  WHERE transaction_code='CS' AND remittance_code='{$RemittanceCode}' AND user_ID IN ({$ID})");
			 
		ENDIF;
	}
	$_SESSION['updatedRecords'] = "A total of ".$Records_counter." Remittance has been updated";
	header ("location: ../card_sale_accounts_receivable");
	Exit;
	

ENDIF;
?>