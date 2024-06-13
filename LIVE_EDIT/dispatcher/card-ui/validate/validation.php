<?php
include_once('../../settings/connect.php');
session_start();

// CHECK MEMBER ID
if(isset($_POST["cnumber"]))
{
	//check if its ajax request, exit script if its not
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
		die();
	}

	$memberid = $_POST["cnumber"]; 
	//sanitize code
	$memberid = filter_var($memberid, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);	
	
	//check username in db
	$results = mysqli_query($link, "SELECT user_ID FROM ".DB_PREFIX."users WHERE user_ID='$memberid'");
	
	//return total count
	$memberid_exist = mysqli_num_rows($results); //total records
	
	if(strlen($memberid) < 10 || strlen($memberid) > 10) {
		die('Please enter valid CEC number or at least but not more than 10 digits long 
			<script>$("#cardicon").html("<img src=\'../profile/images/not-available.png\' />");</script>
		');
	}elseif($memberid_exist) {
		die('<script>$("#cardicon").html("<img src=\'../profile/images/available.png\' />");</script>');
	}else{
		die('CEC Card ID does not exist
			<script>$("#cardicon").html("<img src=\'../profile/images/not-available.png\' />");</script>
		');
	}
}
?>