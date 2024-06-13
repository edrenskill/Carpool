<?php
require_once '../../settings/connect.php';
if(session_id() == '') { page_protect(); } // START SESSIONS

$terminal_ID = $_SESSION['terminal_ID'];

//Change Maker
IF(isset($data["releaseit"])):
	//Insert query 
	 mysqli_query($link, "DELETE FROM ".DB_PREFIX."vehicle_trip_schedule WHERE terminal_ID='{$terminal_ID}' AND selected=0");
	 echo "All units has been released";
ENDIF;
?>