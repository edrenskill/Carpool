<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	if(!isset($_SESSION['ADM'])): header('location: login'); ENDIF;
	
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
		
		IF(isset($_SESSION['frompayout'])):
			header ("location: payout");
		ELSEIF(isset($_SESSION['fromremitted'])):
			header ("location: remitted_payout");
		ELSEIF(isset($_SESSION['USERStransac'])):
			header ("location: users_log");
		ENDIF;
		
	$_SESSION['USERStransac'] = 1;
	ENDIF;
?>