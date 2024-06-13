<?php
require_once '../../settings/connect.php';
if(session_id() == '') { page_protect(); } // START SESSIONS

IF(isset($_SESSION['terminal'])): $terminal_ID = $_SESSION['terminal']; ENDIF;
FOREACH ($_POST as $key => $value) { $data[$key] = filter($value); }
	
	IF(isset($_POST["changetname"])):
		//Fetching Values from URL
		$tname=$data['tname1']; 

		//Insert query 
		$check_record = mysqli_query($link, "SELECT terminal_ID FROM ".DB_PREFIX."terminal WHERE terminal_ID='{$terminal_ID}'");
		$count_record=mysqli_num_rows($check_record);
		IF($count_record):
		  $query = mysqli_query($link, "UPDATE ".DB_PREFIX."terminal SET terminal_name='{$tname}' WHERE terminal_ID='{$terminal_ID}'");
		ENDIF;
		if($query){
		  echo "Terminal name updated successfully";
		};
	ENDIF;
	
	//Dispatcher
	IF(isset($_POST["newdid"])):
		//check if its ajax request, exit script if its not
		if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
			die();
		}

		//trim ID
		$DID1 =  $data['did1'];
		$TID = $data['tid'];

		//sanitize ID
		$DID1 = filter_var($DID1, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
		$TID = filter_var($TID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);

		//check ID in db
		$results = mysqli_query($link, "SELECT user_ID, terminal_ID FROM ".DB_PREFIX."users WHERE user_ID='{$DID1}' AND userlevel='2'");

		//return total count
		$record_exist = mysqli_num_rows($results); //total records
		//if value is more than 0, username is not available
		if($record_exist) {
			
			$check = mysqli_fetch_array($results);
			$terminal = $check['terminal_ID'];
			IF($terminal != "" && $terminal == $TID):
				die ('<img src="'.getBaseUrl().'myaccount/images/not-available.png" /> Dispatcher already assigned to this terminal!');
			ELSEIF($terminal != "" && $terminal != $TID):
				die ('<img src="'.getBaseUrl().'myaccount/images/not-available.png" /> Dispatcher already assigned to other terminal!');
			ELSE:
				mysqli_query($link, "UPDATE ".DB_PREFIX."users SET terminal_ID='{$TID}' WHERE user_ID='{$DID1}'");
				die ('<img src="'.getBaseUrl().'myaccount/images/available.png" />New Dispatcher has been added to this terminal.');
			ENDIF;
			
		}else{
			die ('<img src="'.getBaseUrl().'myaccount/images/not-available.png" />Invalid Dispatcher ID!');
		}
	ENDIF;
	
	//origin
	IF(isset($_POST["changeorigin"])):
	//Fetching Values from URL
	$origin=$data['origin1']; 

	//Insert query 
	$check_record = mysqli_query($link, "SELECT terminal_ID FROM ".DB_PREFIX."terminal WHERE terminal_ID='{$terminal_ID}'");
	$count_record=mysqli_num_rows($check_record);
	IF($count_record):
	  $query = mysqli_query($link, "UPDATE ".DB_PREFIX."terminal SET route_origin='{$origin}' WHERE terminal_ID='{$terminal_ID}'");
	ENDIF;
	 if($query){
	  echo "Route origin updated successfully";
	 };
	ENDIF;
	
	//R destination
	IF(isset($_POST["changedestination"])):
	//Fetching Values from URL
	$destination=$data['destination1']; 

	//Insert query 
	$check_record = mysqli_query($link, "SELECT terminal_ID FROM ".DB_PREFIX."terminal WHERE terminal_ID='{$terminal_ID}'");
	$count_record=mysqli_num_rows($check_record);
	IF($count_record):
	  $query = mysqli_query($link, "UPDATE ".DB_PREFIX."terminal SET route_destination='{$destination}' WHERE terminal_ID='{$terminal_ID}'");
	ENDIF;
	 if($query){
	  echo "Route destination updated successfully";
	 };
	ENDIF;
	
	//fare
	
	IF(isset($_POST["changefare"])):
	//Fetching Values from URL
	$fare=$data['fare1']; 

	//Insert query 
	$check_record = mysqli_query($link, "SELECT terminal_ID FROM ".DB_PREFIX."terminal WHERE terminal_ID='{$terminal_ID}'");
	$count_record=mysqli_num_rows($check_record);
	IF($count_record):
	  $query = mysqli_query($link, "UPDATE ".DB_PREFIX."terminal SET member_dailydues='{$fare}' WHERE terminal_ID='{$terminal_ID}'");
	ENDIF;
	 if($query){
	  echo "Fare updated successfully";
	 };
	ENDIF;
	
	//Regular Terminal Charge
	IF(isset($_POST["changecharge"])):
	//Fetching Values from URL
	$initial=$data['charge1']; 

	//Insert query 
	$check_record = mysqli_query($link, "SELECT terminal_ID FROM ".DB_PREFIX."terminal WHERE terminal_ID='{$terminal_ID}'");
	$count_record=mysqli_num_rows($check_record);
	IF($count_record):
	  $query = mysqli_query($link, "UPDATE ".DB_PREFIX."terminal SET regular_service_fee='{$initial}' WHERE terminal_ID='{$terminal_ID}'");
	ENDIF;
	 if($query){
	  echo "Regular service fee updated successfully";
	 };
	ENDIF;
	
	//Initial Terminal Charge
	
	IF(isset($_POST["changeinitial"])):
	//Fetching Values from URL
	$initial=$data['initial1']; 

	//Insert query 
	$check_record = mysqli_query($link, "SELECT terminal_ID FROM ".DB_PREFIX."terminal WHERE terminal_ID='{$terminal_ID}'");
	$count_record=mysqli_num_rows($check_record);
	IF($count_record):
	  $query = mysqli_query($link, "UPDATE ".DB_PREFIX."terminal SET initial_service_fee='{$initial}' WHERE terminal_ID='{$terminal_ID}'");
	ENDIF;
	 if($query){
	  echo "Initial service fee updated successfully";
	 };
	ENDIF;
	
	//incentive
	
	IF(isset($_POST["changeincentive"])):
	//Fetching Values from URL
	$incentive=$data['incentive1']; 

	//Insert query 
	$check_record = mysqli_query($link, "SELECT terminal_ID FROM ".DB_PREFIX."terminal WHERE terminal_ID='{$terminal_ID}'");
	$count_record=mysqli_num_rows($check_record);
	IF($count_record):
	  $query = mysqli_query($link, "UPDATE ".DB_PREFIX."terminal SET incentive_percentage='{$incentive}' WHERE terminal_ID='{$terminal_ID}'");
	ENDIF;
	 if($query){
	  echo "Incentive updated successfully";
	 };
	ENDIF;
	
	//opetational
	
	IF(isset($_POST["changeoperational"])):
		//Fetching Values from URL
		$operational=$data['operational1'];

		//Insert query 
		$check_record = mysqli_query($link, "SELECT terminal_ID FROM ".DB_PREFIX."terminal WHERE terminal_ID='{$terminal_ID}'");
		$count_record=mysqli_num_rows($check_record);
		IF($count_record):
		  $query = mysqli_query($link, "UPDATE ".DB_PREFIX."terminal SET operational='{$operational}' WHERE terminal_ID='{$terminal_ID}'");
			if($operational==1): 
				echo "Set terminal status to Operational"; 
			elseif ($operational==0): 
				echo "Set terminal status to Pending"; 
			ENDIF; 
		ENDIF;
	ENDIF;
?>