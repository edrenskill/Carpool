<?php
include_once('../../../settings/connect.php');
session_start();

//check we have username post var
if(isset($_POST["username"]))
{
	//check if its ajax request, exit script if its not
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
		die();
	}
	
	//trim and lowercase username
	$username =  $_POST["username"]; 

	//sanitize username
	$username = filter_var($username, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);

	//check username in db
	$results = mysqli_query($link, "SELECT ID FROM ".DB_PREFIX."users WHERE username='$username'");

	//return total count
	$username_exist = mysqli_num_rows($results); //total records
	//if value is more than 0, username is not available
	if($username_exist) {
		die('<img src="'.getBaseUrl().'myaccount/images/not-available.png" />Username is not available');
	}else{
		die('<img src="'.getBaseUrl().'myaccount/images/available.png" />');
	}
}

//EMAIL
if(isset($_POST["email"]))
{
	//check if its ajax request, exit script if its not
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
		die();
	}
	//trim email
	$email =  trim($_POST["email"]); 
	
	//sanitize email
	$email = filter_var($email, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
	
	//check email in db
	$results = mysqli_query($link, "SELECT id FROM ".DB_PREFIX."users WHERE email='$email'");
	
	//return total count
	$email_exist = mysqli_num_rows($results); //total records
	
	//if value is more than 0, email is not available
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		die('<img src="'.getBaseUrl().'myaccount/images/not-available.png" />Email is invalid');
	}elseif($email_exist) {
		die('<img src="'.getBaseUrl().'myaccount/images/not-available.png" />Email is not available');
	}else{
		die('<img src="'.getBaseUrl().'myaccount/images/available.png" />');
	}	
}

// CHECK PWORD
if(isset($_POST["pass"]))
{
	//check if its ajax request, exit script if its not
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
		die();
	}

	$pword = $_POST["pass"]; 
	//sanitize code
	$pword = filter_var($pword, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);	

	if(strlen($pword) < 8 ) {
		die('<img src="'.getBaseUrl().'myaccount/images/not-available.png" />Password must be at least 8 characters');
	}else{
		die('<img src="'.getBaseUrl().'myaccount/images/available.png" />');
	}
}

if(isset($_POST["pass2"]))
{
	//check if its ajax request, exit script if its not
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
		die();
	}
	
	$pword1 = $_POST['pass1'];
	$pword2 = $_POST["pass2"];
	//sanitize code
	$pword1 = filter_var($pword1, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);	
	$pword2 = filter_var($pword2, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);	

	if($pword2 != $pword1) {
		die('<img src="'.getBaseUrl().'myaccount/images/not-available.png" />Password does not match');
	}else{
		die('<img src="'.getBaseUrl().'myaccount/images/available.png" />');
	}
}


// CHECK MEMBER ID FOR LOADING
if(isset($_POST["memid"]))
{
	//check if its ajax request, exit script if its not
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
		die();
	}

	$memberid = $_POST["memid"]; 
	//sanitize code
	$memberid = filter_var($memberid, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);	
	
	//check username in db
	$results = mysqli_query($link, "SELECT user_ID FROM ".DB_PREFIX."users WHERE user_ID='$memberid'");
	
	//return total count
	$memberid_exist = mysqli_num_rows($results); //total records
	
	if(strlen($memberid) < 10 ) {
		die('<img src="'.getBaseUrl().'myaccount/images/not-available.png" />Please enter valid Member ID Number or at least 10 characters long');
		//die('<img src="images/not-available.png" />Please enter valid Member ID Number or at least 7 character long<script type="text/javascript"> alert("Please enter valid Member ID Number or at least 7 character long");</script>');
	}elseif($memberid_exist) {
		die('<img src="'.getBaseUrl().'myaccount/images/available.png" />');
	}else{
		die('<img src="'.getBaseUrl().'myaccount/images/not-available.png" />Member ID does not exist');
		//die('<img src="images/not-available.png" />Member ID does not exist<script type="text/javascript"> alert("Member ID does not exist");</script>');
	}
}

if(isset($_POST["memberid2"]))
{
	//check if its ajax request, exit script if its not
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
		die();
	}

	$memberid1 = $_POST['memberid1'];
	$memberid2 = $_POST["memberid2"];
	//sanitize code
	$memberid1 = filter_var($memberid1, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);	
	$memberid2 = filter_var($memberid2, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);	

	if($memberid2 != $memberid1) {
		die('<img src="'.getBaseUrl().'myaccount/images/not-available.png" />Member ID did not match');
	}else{
		die('<img src="'.getBaseUrl().'myaccount/images/available.png" />');
	}
}

// CHECK AMOUNT
if(isset($_POST["amount"]))
{
	//check if its ajax request, exit script if its not
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
		die();
	}

	$amount = $_POST["amount"]; 
	//sanitize code
	$amount = filter_var($amount, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);	

	$terminal_ID = $_SESSION['terminal_ID'];
	$loadwallet = mysqli_fetch_array(mysqli_query($link, "SELECT ending_balance  FROM ".DB_PREFIX."terminalload_wallet WHERE terminal_ID = '".$terminal_ID."' AND `primary` = 1")); 
	$cur_balance = $loadwallet['ending_balance'];
	
	if($amount == "" || (!isset($amount))) {
		die('<img src="'.getBaseUrl().'myaccount/images/not-available.png" />Please Select Amount');
	}elseif($amount > $cur_balance){
		die('<img src="'.getBaseUrl().'myaccount/images/not-available.png" />Insufficient fund');
	}else{
		die('<img src="'.getBaseUrl().'myaccount/images/available.png" />');
	}
}


//check for valid vehicle ID for admin use registration for driver
if(isset($_POST["vID"]))
{
	//check if its ajax request, exit script if its not
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
		die();
	}
	
	$vID =  $_POST["vID"]; 

	//sanitize username
	$vID = filter_var($vID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);

	//check username in db
	$results = mysqli_query($link, "SELECT A.ID, A.plate_number, A.owner_ID, B.fname, B.lname FROM ".DB_PREFIX."vehicles A, ".DB_PREFIX."users B WHERE A.unit_ID='$vID' AND B.user_ID=A.owner_ID");

	//return total count
	$unit_exist = mysqli_num_rows($results); //total records
	//if value is more than 0, Unit is available
	if($unit_exist) {
		$owner = mysqli_fetch_assoc($results);
		$plate = $owner['plate_number'];
		$opt = STRTOUPPER($owner['fname']." ".$owner['lname']);
		
		die('<img src="'.getBaseUrl().'myaccount/images/available.png" />Plate No.: <strong>'.$plate.'</strong> Owner\'s Name: <strong>'.$opt.'</strong>');		
	}else{
		die('<img src="'.getBaseUrl().'myaccount/images/not-available.png" />Vehicle ID not found!');
	}
}
?>