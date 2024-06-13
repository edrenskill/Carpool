<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	if(!isset($_SESSION['ADM'])): header('location: login'); ENDIF;

	foreach($_GET as $key => $value) { $data[$key] = filter($value); }

	IF(isset($data['member'])): // Generate ID
		$member_ID = $data['member'];
		$_SESSION['newmember'] = $member_ID;
		header ("location: gen_id");
	ELSEIF(isset($data['memberID'])): // Member Profile
		$member_ID = $data['memberID'];
		$_SESSION['memberID'] = $member_ID;
		header ("location: user_profile");
	ELSEIF(isset($data['unitID'])): // Unit Details
		$unit_ID = $data['unitID'];
		$_SESSION['vehicleID'] = $unit_ID;
		header ("location: vehicle_details");
	ELSEIF(isset($data['trip_history'])): // Trip History
		$unit_ID = $data['trip_history'];
		$_SESSION['vehicleID'] = $unit_ID;
		header ("location: trip_history");
	ELSEIF(isset($data['terminal'])): // Terminal 
		$terminal_ID = $data['terminal'];
		$_SESSION['terminal'] = $terminal_ID;
		header ("location: edit_terminal");
	ELSEIF(isset($data['transaction_details'])): // Terminal 
		$driver_ID = $data['transaction_details'];
		$cutoffstart = $data['cos'];
		$cutoffend = $data['cof'];
		$_SESSION['driverID'] = $driver_ID;
		//$_SESSION['cos'] = $cutoffstart;
		//$_SESSION['cof'] = $cutoffend;
		header ("location: trip_details_report");
	ELSEIF(isset($data['suspend_details'])): // Terminal 
		$suspend_ID = $data['suspend_details'];
		$_SESSION['suspendID'] = $suspend_ID;
		header ("location: suspend_details_report");
	ELSEIF(isset($data['newID'])): // newID 
		$action_ID = $data['newID'];
		$_SESSION['IDusers'] = $action_ID;
		header ("location: new_userID");

///////////// BEGIN --- FROM MEMBER SEARCH --- CHANGE USER/MEMBER ACCOUNT STATUS ///////////////
	ELSEIF(isset($data['suspendID'])): // SuspendID 
		$_SESSION['IDusers'] = $data['suspendID'];
		header ("location: suspend");
		
	ELSEIF(isset($data['unsuspendID'])): // Unsuspend 
		$_SESSION['IDusers'] = $data['unsuspendID'];
		$_SESSION['unit_status'] = $data['unit_status'];
		header ("location: notify");
		
	ELSEIF(isset($data['banID'])): // ban 
		$_SESSION['IDusers'] = $data['banID'];;
		header ("location: ban");
///////////// BEGIN --- Vehicle Action --- CHANGE UNIT/UNIT STATUS ///////////////
	ELSEIF(isset($data['suspendUNITID'])): // SuspendID 
		$_SESSION['vehicleID'] = $data['suspendUNITID'];
		header ("location: unit_suspend");
		
	ELSEIF(isset($data['unsuspendUNITID'])): // Unsuspend 
		$_SESSION['vehicleID'] = $data['unsuspendUNITID'];
		$_SESSION['unit_status'] = $data['unit_status'];
		header ("location: unit_unsuspend");
		
	ELSEIF(isset($data['banUNITID'])): // ban 
		$_SESSION['vehicleID'] = $data['banUNITID'];;
		header ("location: unit_ban");
///////////// BEGIN --- Vehicle Action --- CHANGE APPLICATION STATUS ///////////////
	ELSEIF(isset($data['processing'])): // SuspendID 
		$_SESSION['vehicleID'] = $data['processing'];
		header ("location: processing");
		
	ELSEIF(isset($data['grandted'])): // Unsuspend 
		$_SESSION['vehicleID'] = $data['grandted'];
		header ("location: grandted");

	ELSEIF(isset($data['denied'])): // ban 
		$_SESSION['vehicleID'] = $data['denied'];;
		header ("location: denied");
	ELSEIF(isset($data['deniedappealed'])): // ban 
		$_SESSION['vehicleID'] = $data['deniedappealed'];;
		header ("location: appealed");	
	ELSEIF(isset($data['deniedfinalappealed'])): // ban 
		$_SESSION['vehicleID'] = $data['deniedfinalappealed'];;
		header ("location: Final_Appealed");
///////////// END --- FROM MEMBER SEARCH --- CHANGE USER/MEMBER ACCOUNT STATUS ///////////////
		
	ELSEIF(isset($data['history'])): // history
		$action_ID = $data['history'];
		$_SESSION['IDusers'] = $action_ID;
		header ("location: users_log");
	ENDIF;
?>