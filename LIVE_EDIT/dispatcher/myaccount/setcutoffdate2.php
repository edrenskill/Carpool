<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS

	IF(isset($_SESSION['frommyaccount'])):
		$location = "account_details";
	ELSEIF(isset($_SESSION['drivertransac'])):
		$location = "driver_transaction_details";
	ELSEIF(isset($_SESSION['usertransac'])):
		$location = "user_transaction_details";
	ENDIF;
	
	foreach($_POST as $key => $value) { $data[$key] = filter($value); }
	
	$_SESSION['error'] = "";
	
	IF(isset($_POST) && array_key_exists('setcutdate',$_POST)):

		$selectdate1 = $data['selectdate1'];
		$selectdate2 = $data['selectdate2'];
	
		IF(strtotime($selectdate1) > strtotime($selectdate2)):
			$_SESSION['error'] = "<span style='color:#FF0000;'>Please enter valid date.</span>";
		ELSEIF(false === strtotime($selectdate1) || false === strtotime($selectdate2)):
			$_SESSION['error'] = "<span style='color:#FF0000;'>One of the date field cannot be blank.</span>";
		ELSE:
			$_SESSION['cutstart'] = $selectdate1;
			$_SESSION['cutend'] = $selectdate2;
			$_SESSION['selecteddate'] = 1;
		ENDIF;
		header ("location: ".$location);
	ENDIF;
?>