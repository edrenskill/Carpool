<?php
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	$sql = mysqli_query($link, "UPDATE ".DB_PREFIX."driver_status SET status=0 WHERE date_to<=now() AND status=1");
	IF(!isset($_SESSION['user_id'])): header('location: login'); 
	ELSE: header("location:account_details"); ENDIF;
?>