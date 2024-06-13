<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	if(!isset($_SESSION['ADM'])): header('location: login'); ENDIF;

	foreach($_GET as $key => $value) { $data[$key] = filter($value); }

	IF(isset($data['dispatcherID'])): // Generate ID
	
		$dispatcherID = $data['dispatcherID'];
		$TID = $data['TID'];
		mysqli_query($link, "UPDATE ".DB_PREFIX."users SET terminal_ID='' WHERE user_ID='{$dispatcherID}' AND terminal_ID='{$TID}'");
		header ("location: edit_terminal");
	ENDIF;
?>