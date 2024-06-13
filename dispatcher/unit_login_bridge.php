<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	if(!isset($_SESSION['Dspr'])): header('location: login'); ENDIF;
	
	$terminal_ID = $_SESSION['terminal_ID'];
	foreach($_GET as $key => $value) { $data[$key] = filter($value); }
	
	IF(isset($data['unitID'])):
		$unit_ID = $data['unitID'];
		
		
		$check_unit_in_bay = mysqli_query($link, "SELECT vehicle_ID FROM ".DB_PREFIX."vehicle_trip_schedule WHERE terminal_ID='{$terminal_ID}' AND selected=1");
		$check_in_bay = mysqli_num_rows($check_unit_in_bay);
		IF(!$check_in_bay):
			mysqli_query($link, "UPDATE ".DB_PREFIX."vehicle_trip_schedule SET selected='1' WHERE vehicle_ID='{$unit_ID}' AND terminal_ID='{$terminal_ID}'");
			header ("location: boarding");
		ELSE:
			$_SESSION['activebay'] = 1;
			header ("location: baywarning");
		ENDIF;
	ENDIF;
?>