<?php
require_once '../../settings/connect.php';
if(session_id() == '') { page_protect(); } // START SESSIONS

$vehicle_ID = $_SESSION['vehicleID'];

//Change Plate
IF(isset($_POST["changeplate"])):
	//Fetching Values from URL
	$plate=strtoupper($_POST['plate1']);
	
	IF($plate == ""): 
		echo "Error, Please enter Valid Plate Number";
	ELSE:
		//Insert query 
		  $query = mysqli_query($link, "UPDATE `".DB_PREFIX."vehicles` SET plate_number='{$plate}' WHERE unit_ID='{$vehicle_ID}'");

		 if($query){
		  echo "Plate Number has been updated successfully";
		 }
	ENDIF;
ENDIF;


//Change driver1
IF(isset($_POST["changedname"])):
	//Fetching Values from URL
	$dname=strtoupper($_POST['dname1']);
	
	$check_record = mysqli_query($link, "SELECT driver_ID1 FROM ".DB_PREFIX."vehicles WHERE unit_ID='{$vehicle_ID}'");
	$count_record=mysqli_num_rows($check_record);
	IF($count_record): 
		$query = mysqli_query($link, "UPDATE `".DB_PREFIX."vehicles` SET driver_ID1='{$dname}' WHERE unit_ID='{$vehicle_ID}'");
	ELSE:
	$sql_contact = "INSERT INTO ".DB_PREFIX."vehicles  (unit_ID,driver_ID1) VALUES ('$vehicle_ID', '$dname')";	  
	  mysqli_query($link, $sql_contact) or die("Insertion Failed:" . mysqli_error($link));
	ENDIF;
	 if($query){
	  echo "Driver ID updated successfully";
	 };
ENDIF;

//Change driver2
IF(isset($_POST["changed2name"])):
	//Fetching Values from URL
	$d2name=strtoupper($_POST['d2name1']);
	
	$check_record = mysqli_query($link, "SELECT driver_ID2 FROM ".DB_PREFIX."vehicles WHERE unit_ID='{$vehicle_ID}'");
	$count_record=mysqli_num_rows($check_record);
	IF($count_record): 
		$query = mysqli_query($link, "UPDATE `".DB_PREFIX."vehicles` SET driver_ID2='{$d2name}' WHERE unit_ID='{$vehicle_ID}'");
	ELSE:
	$sql_contact = "INSERT INTO ".DB_PREFIX."vehicles  (unit_ID,driver_ID2) VALUES ('$vehicle_ID', '$d2name')";	  
	  mysqli_query($link, $sql_contact) or die("Insertion Failed:" . mysqli_error($link));
	ENDIF;
	 if($query){
	  echo "Driver ID updated successfully";
	 };
ENDIF;

//Change Maker
IF(isset($_POST["changemake"])):
	//Fetching Values from URL
	$make=strtoupper($_POST['make1']);
	
	IF($make == ""): 
		echo "Error, Please enter Valid Vehicle Manufacturer Name";
	ELSE:
		//Insert query 
		  $query = mysqli_query($link, "UPDATE `".DB_PREFIX."vehicles` SET make='{$make}' WHERE unit_ID='{$vehicle_ID}'");

		 if($query){
		  echo "Make / Brand has been updated successfully";
		 }
	ENDIF;
ENDIF;

//Change Year
IF(isset($_POST["changemodel"])):
	//Fetching Values from URL
	$model=$_POST['model1'];
	
	IF($model == ""): 
		echo "Error, Please enter Valid Vehicle Year model";
	ELSE:
		//Insert query 
		  $query = mysqli_query($link, "UPDATE `".DB_PREFIX."vehicles` SET model='{$model}' WHERE unit_ID='{$vehicle_ID}'");

		 if($query){
		  echo "Year model has been updated successfully";
		 }
	ENDIF;
ENDIF;

//Change Chassis
IF(isset($_POST["changechassis"])):
	//Fetching Values from URL
	$chassis=$_POST['chassis1'];
	
	IF($chassis == ""): 
		echo "Error, Please enter Valid Vehicle Chassis Number";
	ELSE:
		//Insert query 
		  $query = mysqli_query($link, "UPDATE `".DB_PREFIX."vehicles` SET chassis='{$chassis}' WHERE unit_ID='{$vehicle_ID}'");

		 if($query){
		  echo "Chassis Number has been updated successfully";
		 }
	ENDIF;
ENDIF;

//Change Engine
IF(isset($_POST["changeengine"])):
	//Fetching Values from URL
	$engine=$_POST['engine1'];
	
	IF($engine == ""): 
		echo "Error, Please enter Valid Vehicle Engine Number";
	ELSE:
		//Insert query 
		  $query = mysqli_query($link, "UPDATE `".DB_PREFIX."vehicles` SET engine='{$engine}' WHERE unit_ID='{$vehicle_ID}'");

		 if($query){
		  echo "Engine Number has been updated successfully";
		 }
	ENDIF;
ENDIF;

//Change Capacity
IF(isset($_POST["changecapacity"])):
	//Fetching Values from URL
	$capacity=$_POST['capacity1'];
	
	IF($capacity == ""): 
		echo "Error, Please enter Valid Vehicle capacity";
	ELSE:
		//Insert query 
		  $query = mysqli_query($link, "UPDATE `".DB_PREFIX."vehicles` SET capacity='{$capacity}' WHERE unit_ID='{$vehicle_ID}'");

		 if($query){
		  echo "Capacity has been updated successfully";
		 }
	ENDIF;
ENDIF;
?>