<?php
require_once '../../settings/connect.php';
if(session_id() == '') { page_protect(); } // START SESSIONS

$member_ID = $_SESSION['act_ID'];
foreach($_POST as $key => $value) { $data[$key] = filter($value); }

//Change name
IF(isset($data["changename"])):
	//Fetching Values from URL
	$fname=$data['fname1'];
	$mname=$data['mname1'];
	$lname=$data['lname1'];

	//Insert query 
	  $query = mysqli_query($link, "UPDATE `".DB_PREFIX."users` SET fname='{$fname}', mname='{$mname}', lname='{$lname}' WHERE user_ID='{$member_ID}'");

	 if($query){
	  echo "Name updated successfully";
	 }
ENDIF;

//Change dob
IF(isset($data["changedob"])):
	//Fetching Values from URL
	$dob=$data['dob1'];

	//Insert query 
	  $query = mysqli_query($link, "UPDATE `".DB_PREFIX."users` SET dob='{$dob}' WHERE user_ID='{$member_ID}'");

	 if($query){
	  echo "Birthday updated successfully";
	 }
ENDIF;

//Change Occupation
IF(isset($data["changeoccu"])):
	//Fetching Values from URL
	$occu=$data['occu1'];

	//Insert query 
	  $query = mysqli_query($link, "UPDATE `".DB_PREFIX."users` SET occupation='{$occu}' WHERE user_ID='{$member_ID}'");

	 if($query){
	  echo "Occupation updated successfully";
	 }
ENDIF;

//Change Religion
IF(isset($data["changereli"])):
	//Fetching Values from URL
	$reli=$data['reli1'];

	//Insert query 
	  $query = mysqli_query($link, "UPDATE `".DB_PREFIX."users` SET religion='{$reli}' WHERE user_ID='{$member_ID}'");

	 if($query){
	  echo "Religion updated successfully";
	 }
ENDIF;

//Change TIN
IF(isset($data["changetin"])):
	//Fetching Values from URL
	$tin=$data['tin1'];

	//Insert query 
	  $query = mysqli_query($link, "UPDATE `".DB_PREFIX."users` SET TIN='{$tin}' WHERE user_ID='{$member_ID}'");

	 if($query){
	  echo "TIN updated successfully";
	 }
ENDIF;




//Change Email
IF(isset($_POST["changecontact"])):
	//Fetching Values from URL
	$email=$_POST['email1'];
	$tele=$_POST['tele1'];
	$mob=$_POST['mob1'];
	$fax=$_POST['fax1'];

	//Insert query 
	  $query = mysqli_query($link, "UPDATE `".DB_PREFIX."users` SET email='{$email}' WHERE user_ID='{$member_ID}'");
	  $query = mysqli_query($link, "UPDATE `".DB_PREFIX."users` SET tel='{$tele}' WHERE user_ID='{$member_ID}'");
	  $query = mysqli_query($link, "UPDATE `".DB_PREFIX."users` SET mobile='{$mob}' WHERE user_ID='{$member_ID}'");
	  $query = mysqli_query($link, "UPDATE `".DB_PREFIX."users` SET fax='{$fax}' WHERE user_ID='{$member_ID}'");

	 if($query){
	  echo "Contact updated successfully";
	 }
ENDIF;

//Change Address
IF(isset($data["changeaddr"])):
	//Fetching Values from URL
	$addr=$data['addr1'];

	//Insert query 
	  $query = mysqli_query($link, "UPDATE `".DB_PREFIX."users` SET address='{$addr}' WHERE user_ID='{$member_ID}'");

	 if($query){
	  echo "Address updated successfully";
	 }
ENDIF;

IF(isset($data["changedropaddr"])):
	//Fetching Values from URL
	$country=$data['country1'];
	$region=$data['region1'];
	$province=$data['province1'];
	$city=$data['city1'];
	$barangay=$data['barangay1'];

	//Insert query 
	  $query = mysqli_query($link, "UPDATE `".DB_PREFIX."users` SET country='{$country}', region='{$region}', province='{$province}', city='{$city}', barangay='{$barangay}' WHERE user_ID='{$member_ID}'");

	 if($query){
	  echo "Address updated successfully";
	 }
ENDIF;

//Change ZIP
IF(isset($data["changezip"])):
	//Fetching Values from URL
	$zip=$data['zip1'];

	//Insert query 
	  $query = mysqli_query($link, "UPDATE `".DB_PREFIX."users` SET zip_code='{$zip}' WHERE user_ID='{$member_ID}'");

	 if($query){
	  echo "ZIP Code updated successfully";
	 }
ENDIF;


//Contact Person

IF(isset($_POST["changecmobile"])):
	//Fetching Values from URL
	$cmobile=$data['cmobile1'];

	//Insert query 
	$check_record = mysqli_query($link, "SELECT UID FROM ".DB_PREFIX."contacts WHERE UID='{$member_ID}'");
	$count_record=mysqli_num_rows($check_record);
	IF($count_record):
	  $query = mysqli_query($link, "UPDATE ".DB_PREFIX."contacts SET mobile='{$cmobile}' WHERE UID='{$member_ID}'");
	//  echo $member_ID;
	ELSE:
	 $sql_contact = "INSERT INTO ".DB_PREFIX."contacts  (UID,mobile) VALUES ('$member_ID', '$cmobile')";	  
	  mysqli_query($link, $sql_contact) or die("Insertion Failed:" . mysqli_error($link));
//	  echo $member_ID."1";
	ENDIF;
	 if($query){
	  echo "Address updated successfully";
	 };
	ENDIF;

IF(isset($data["changecname"])):
	//Fetching Values from URL
	$cname=$data['cname1'];

	//Insert query 
	$check_record = mysqli_query($link, "SELECT UID FROM ".DB_PREFIX."contacts WHERE UID='{$member_ID}'");
	$count_record=mysqli_num_rows($check_record);
	IF($count_record):
	  $query = mysqli_query($link, "UPDATE ".DB_PREFIX."contacts SET contact_person='{$cname}' WHERE UID='{$member_ID}'");
	//  echo $member_ID;
	ELSE:
	 $sql_contact = "INSERT INTO ".DB_PREFIX."contacts  (UID,contact_person) VALUES ('$member_ID', '$cname')";	  
	  mysqli_query($link, $sql_contact) or die("Insertion Failed:" . mysqli_error($link));
//	  echo $member_ID."1";
	ENDIF;
	 if($query){
	  echo "Address updated successfully";
	 };
	ENDIF;


IF(isset($data["changecaddr"])):
	//Fetching Values from URL
	$caddr=$data['caddr1'];

	//Insert query 
	$check_record = mysqli_query($link, "SELECT UID FROM ".DB_PREFIX."contacts WHERE UID='{$member_ID}'");
	$count_record=mysqli_num_rows($check_record);
	IF($count_record):
	  $query = mysqli_query($link, "UPDATE ".DB_PREFIX."contacts SET address1='{$caddr}' WHERE UID='{$member_ID}'");
	//  echo $caddr;
	ELSE:
	 $sql_contact = "INSERT INTO ".DB_PREFIX."contacts  (UID,address1) VALUES ('$member_ID', '$caddr')";	  
	  mysqli_query($link, $sql_contact) or die("Insertion Failed:" . mysqli_error($link));
	//  echo $member_ID."1";
	ENDIF;	
ENDIF;

IF(isset($_POST["changedropcaddr"])):
	//Fetching Values from URL
	$ccountry=$data['ccountry1'];
	$cregion=$data['cregion1'];
	$cprovince=$data['cprovince1'];
	$ccity=$data['ccity1'];
	$cbarangay=$data['cbarangay1'];

	$check_record = mysqli_query($link, "SELECT UID FROM ".DB_PREFIX."contacts WHERE UID='{$member_ID}'");
	$count_record=mysqli_num_rows($check_record);
	IF($count_record):
	//Insert query 
	 $query = mysqli_query($link, "UPDATE `".DB_PREFIX."contacts` SET country='{$ccountry}', region='{$cregion}', province='{$cprovince}', city_municipality='{$ccity}', barangay='{$cbarangay}' WHERE UID='{$member_ID}'");
	//echo $member_ID;
//	echo $ccountry.$cregion.$cprovince.$ccity.$cbarangay;
	ELSE:
	 $sql_contact = "INSERT INTO ".DB_PREFIX."contacts  (UID,country,region,province,city_municipality,barangay) VALUES ('$member_ID', '$ccountry', '$cregion', '$cprovince', '$ccity', '$cbarangay')";	  
	  mysqli_query($link, $sql_contact) or die("Insertion Failed:" . mysqli_error($link));
	  echo $member_ID."1";
	ENDIF;
	  
	  
	 if($query){
	  echo "Address updated successfully";
	 }
ENDIF;
	//bank change
IF(isset($_POST["changebank"])):
	//Fetching Values from URL
	$bank=$data['bank1'];
	$account=$data['account1'];


	$check_record = mysqli_query($link, "SELECT user_ID FROM ".DB_PREFIX."bank_accounts WHERE user_ID='{$member_ID}'");
	$count_record=mysqli_num_rows($check_record);
	$Bank_Name = mysqli_fetch_array(mysqli_query($link, "SELECT name, Abbreviation FROM ".DB_PREFIX."banks WHERE ID={$bank}"));
	
	IF($count_record):
		//Insert query 
		$query = mysqli_query($link, "UPDATE `".DB_PREFIX."bank_accounts` SET bank_ID='{$bank}', account_no='{$account}' WHERE user_ID='{$member_ID}'");
		
		echo $Bank_Name['name']." - (".$Bank_Name['Abbreviation'].")";
	ELSE:
		$sql_contact = "INSERT INTO ".DB_PREFIX."bank_accounts  (user_ID,bank_ID,account_no,status) VALUES ('$member_ID','$bank', '$account', '1')";	  
		mysqli_query($link, $sql_contact) or die("Insertion Failed:" . mysqli_error($link));

		echo $Bank_Name['name']." - (".$Bank_Name['Abbreviation'].")";
	ENDIF;
ENDIF;;
	

?>