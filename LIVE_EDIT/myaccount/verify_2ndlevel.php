<?php
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
		
		IF(WM()):
				header("Location: ../backend/");
		ELSEIF(Dispatcher()):
				header("location: ../dispatcher/login");
		ELSE:
				header("Location: account_details");
		ENDIF;
?>