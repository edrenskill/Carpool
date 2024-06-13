<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	if(!isset($_SESSION['Dspr'])): header('location: login'); ENDIF;
	
	foreach($_GET as $key => $value) { $data[$key] = filter($value); }
	
	IF(isset($data['memberID'])):
		$member_ID = $data['memberID'];
		$_SESSION['memberID'] = $member_ID;
		header ("location: user_profile");
	ELSEIF(isset($data['unitID'])): // Unit Details
		$unit_ID = $data['unitID'];
		$_SESSION['vehicleID'] = $unit_ID;
		header ("location: vehicle_details");
	ENDIF;
?>