<?php
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	IF(isset($_SESSION['Dspr']) && isset($_SESSION['user_id'])){
		header ('location: dashboard');
	}ELSEIF(!isset($_SESSION['Dspr']) && isset($_SESSION['user_id'])){ 
		header ('location: login');
	}ELSEIF(isset($_SESSION['Dspr']) && !isset($_SESSION['user_id'])){ 
		unset($_SESSION['Dspr']);
		header ('location: ../');
	}ELSE{
		header ('location: ../');
	}
?>