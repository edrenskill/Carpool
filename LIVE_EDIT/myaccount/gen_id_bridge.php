<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS

	foreach($_GET as $key => $value) { $data[$key] = filter($value); }

	IF(isset($data['driver_ID'])): // Terminal 
		$driver_ID = $data['driver_ID'];
		$_SESSION['driverID'] = $driver_ID;
		header ("location: driver_transaction_details");
	ElSEIF(isset($data['transaction_details'])): // driver 
		$_SESSION['batch_ref'] = $data['transaction_details'];
		header ("location: user_transaction_details");
	ENDIF;
?>