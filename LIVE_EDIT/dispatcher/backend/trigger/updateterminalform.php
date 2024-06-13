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