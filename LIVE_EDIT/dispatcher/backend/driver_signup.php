<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	if(!isset($_SESSION['ADM'])): header('location: login'); ENDIF;
	
	date_default_timezone_set("Asia/Manila");
	
	$_SESSION['step'] =2;

	FOREACH($_POST as $key => $value) { $data[$key] = filter($value); }
	$err = array();
	IF(isset($_POST) && array_key_exists('doRegister',$_POST)):

		// VALIDATE USERNAME
		$username =  strtolower(trim($data["username"])); //trim and lowercase username
		$username = filter_var($username, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH); //sanitize username

		IF($username == ""): $err[] = "Valid username";
		ELSE: 
			$results = mysqli_query($link, "SELECT id FROM ".DB_PREFIX."users WHERE username='$username'");
			$username_exist = mysqli_num_rows($results); //total records
			IF($username_exist): $err[] = "Username already exist"; ENDIF; // already exist
		ENDIF;

		IF($data['pass1'] == "" || strlen($data['pass1']) < 8): $err[] = "Password cannot be blank or at least 8 characters";
		ELSEIF($data['pass2'] == "" || $data['pass2'] != $data['pass1']): $err[] = "Password do not match"; ENDIF;

		IF($data['fname'] == "" || $data['mname'] == "" || $data['lname'] == ""): $err[] = "Complete Name (First, Middle and Last name)"; ENDIF;
		IF($data['dob'] == ""): $err[] = "Date of Birth"; ENDIF;
		IF(!isset($data['gender']) || $data['gender'] == ""): $err[] = "Gender"; ENDIF;
		IF($data['address'] == ""): $err[] = "Address"; ENDIF;
		IF($data['country'] == ""): $err[] = "Country"; ENDIF;
		IF($data['region'] == ""): $err[] = "Region"; ENDIF;
		IF($data['province'] == ""): $err[] = "Province"; ENDIF;
		IF($data['city'] == ""): $err[] = "City"; ENDIF;
		IF($data['barangay'] == ""): $err[] = "Barangay"; ENDIF;

		IF($data['cfname'] == ""  || $data['clname'] == ""): $err[] = "Complete Contact Person's Name (First name, Last name)"; ENDIF;
		IF($data['ccontact'] == "" || $data['ccontact'] == 0): $err[] = "Contact Person's Number"; ENDIF;
		IF(!isset($data['contactaddsame']) || $data['contactaddsame'] != 1){
			IF($data['caddress'] == ""): $err[] = "Address"; ENDIF;
			IF($data['ccountry'] == ""): $err[] = "Country"; ENDIF;
			IF($data['cregion'] == ""): $err[] = "Region"; ENDIF;
			IF($data['cprovince'] == ""): $err[] = "Province"; ENDIF;
			IF($data['ccity'] == ""): $err[] = "City"; ENDIF;
			IF($data['cbarangay'] == ""): $err[] = "Barangay"; ENDIF;
		}

		// VALIDATE EMAIL
		$email =  trim($_POST["email"]); //trim email
		$email = filter_var($email, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH); //sanitize email

		$results = mysqli_query($link, "SELECT id FROM ".DB_PREFIX."users WHERE email='$email'");
		$email_exist = mysqli_num_rows($results);
		IF(!filter_var($email, FILTER_VALIDATE_EMAIL)): "Email is not valid";
		ELSEIF($email_exist): $err[] = "Email already exist";
		ENDIF;
		
		IF($data['contact'] == "" || $data['contact'] == 0): $err[] = "Contact Number"; ENDIF;
		
		//For Driver/Operator and Driver
		IF($data['dl'] == ""): $err[] = "Driver's License"; ENDIF;
		IF($data['dl_expire'] == "" || $data['dl_expire'] == 0): $err[] = "Driver's License Expiry Date"; ENDIF;
		
		IF(!empty($err)):
			$_SESSION['post']['username'] = $data['username'];
			$_SESSION['post']['pass1'] = $data['pass1'];
			$_SESSION['post']['pass2'] = $data['pass2'];
			$_SESSION['post']['fname'] = $data['fname'];
			$_SESSION['post']['mname'] = $data['mname'];
			$_SESSION['post']['lname'] = $data['lname'];
			$_SESSION['post']['suffix'] = $data['suffix'];
			$_SESSION['post']['dob'] = $data['dob'];
			$_SESSION['post']['gender'] = $data['gender'];
			$_SESSION['post']['address'] = $data['address'];
			$_SESSION['post']['country'] = $data['country'];
			$_SESSION['post']['region'] = $data['region'];
			$_SESSION['post']['province'] = $data['province'];
			$_SESSION['post']['city'] = $data['city'];
			$_SESSION['post']['barangay'] = $data['barangay'];
			$_SESSION['post']['email'] = $data['email'];

			$_SESSION['post']['dl']	=$data['dl'];
			$_SESSION['post']['dl_expire'] = $data['dl_expire'];
			$_SESSION['post']['nbi'] = $data['nbi'];
			$_SESSION['post']['nbi_expire'] = $data['nbi_expire'];
			$_SESSION['post']['police'] = $data['police'];
			$_SESSION['post']['police_expire'] = $data['police_expire'];

			$_SESSION['post']['contact'] = $data['contact'];

			$_SESSION['post']['cfname'] = $data['cfname'];
			$_SESSION['post']['clname'] = $data['clname'];
			$_SESSION['post']['ccontact'] = $data['ccontact'];
			
			IF(!isset($data['contactaddsame']) || $data['contactaddsame'] != 1){
				$_SESSION['post']['caddress'] = $data['caddress'];
				$_SESSION['post']['ccountry'] = $data['ccountry'];
				$_SESSION['post']['cregion'] = $data['cregion'];
				$_SESSION['post']['cprovince'] = $data['cprovince'];
				$_SESSION['post']['ccity'] = $data['ccity'];
				$_SESSION['post']['cbarangay'] = $data['cbarangay'];
			}

			HEADER('Location: ' . $_SESSION['REAL_REFERRER']);
		ELSEIF(empty($err)):

			$IDCardtype = $data['IDCardtype'];
			$username = $data['username'];
			$password = $data['pass1'];
			$fname = strtoupper($data['fname']);
			$mname = strtoupper($data['mname']);
			$lname = strtoupper($data['lname']);
			$suffix = strtoupper($data['suffix']);
			$dob = $data['dob'];
			$gender = $data['gender'];
			$address = $data['address'];
			$country = $data['country'];
			$region = $data['region'];
			$province = $data['province'];
			$city = $data['city'];
			$barangay = $data['barangay'];
			$email = $data['email'];
			$contact = $data['contact'];
			
			$dl = $data['dl'];
			$dl_expire = $data['dl_expire'];
			$nbi = $data['nbi'];
			$nbi_expire = $data['nbi_expire'];
			$police = $data['police'];
			$police_expire = $data['police_expire'];
			
			$cfname = STRTOUPPER($data['cfname']." ".$data['clname']." ".$data['csuffix']);
			$ccontact = $data['ccontact'];

			IF(!isset($data['contactaddsame']) || $data['contactaddsame'] != 1){
				$caddress = $data['caddress'];
				$ccountry = $data['ccountry'];
				$cregion = $data['cregion'];
				$cprovince = $data['cprovince'];
				$ccity = $data['ccity'];
				$cbarangay = $data['cbarangay'];		
			}else{
				$caddress = $data['address'];
				$ccountry = $data['country'];
				$cregion = $data['region'];
				$cprovince = $data['province'];
				$ccity = $data['city'];
				$cbarangay = $data['barangay'];	
			}

			$userip = $_SERVER['REMOTE_ADDR']; // User IP Address

			//$sha1pass = PwdHash($password); // stores sha1 of password
			$sha1pass = password_hash($password, PASSWORD_DEFAULT);
			$activcode = rand(1000,9999); // Activation code generation if enabled

			$gen_account = "TEMP-".mysqli_real_escape_string($link, GenID());

			$duplicates = mysqli_query($link, "SELECT user_ID FROM ".DB_PREFIX."users WHERE user_ID='$gen_account'");
			WHILE(mysqli_fetch_array($duplicates)){
				$gen_account = "TEMP-".mysqli_real_escape_string($link, GenID());
				WHILE($gen_account <= 2015500){
					$gen_account = "TEMP-".mysqli_real_escape_string($link, GenID());
				}
			}

			$sql_insert = "INSERT into `".DB_PREFIX."users` (`username`,`pword`,`userlevel`,`email`,`fname`,`mname`,`lname`,`suffix`,`gender`,`dob`,`address`,`country`,`region`,`province`,`city`,`barangay`,`mobile`,`regdate`,`ipadd`,`approval`,`active`,`user_ID`, `batch_ID`)
			VALUES ('$username','$sha1pass','7','$email','$fname','$mname','$lname','$suffix','$gender','$dob','$address','$country','$region','$province','$city','$barangay','$contact',now(),'$userip',0,'$activcode','$gen_account','$member_batch')";
				
			mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
			$user_id = mysqli_insert_id($link);
			$md5_id = md5($user_id);
			
			// INSERT CONTACT PERSON
			$sql_contact = "INSERT into `".DB_PREFIX."contacts` (`UID`,`contact_person`,`email`,`countrycode`,`mobile`,`address1`,`barangay`,`city_municipality`,`province`,`region`,`country`) 
			VALUES ('$gen_account','$cfname','$email','63','$ccontact','$caddress','$cbarangay','$ccity','$cprovince','$cregion','$ccountry')";
			mysqli_query($link, $sql_contact) or die("Insertion Failed:" . mysqli_error($link));
			
			// DRIVER
			$sql_credentials = "INSERT into `".DB_PREFIX."driver_credentials` (`driver_ID`,`NBI`,`NBI_expiry`,`drivers_license`,`DL_expiry`,`police_clearance`,`police_expiry`) 
			VALUES ('$gen_account','$nbi','$nbi_expire','$dl','$dl_expire','$police','$police_expire')";
			mysqli_query($link, $sql_credentials) or die("Insertion Failed:" . mysqli_error($link));

			// Update user md5
			mysqli_query($link, "UPDATE `".DB_PREFIX."users` SET md5_id='$md5_id' WHERE id='$user_id'");

			$_SESSION['dpass'] = $password;
			$_SESSION['dfname'] = $fname;
			$_SESSION['dact_no'] = $gen_account;
			
			HEADER('Location: add_vehicle');

		ENDIF;
	ENDIF;
	
	
?>

<!DOCTYPE HTML>
<html>
	<head>
		<?php include ('includes/header.php'); ?>
		
		<script type="text/javascript"> 
			$(document).ready( function() {
				$('#loginform').hide();
				$('#message').delay(3000).fadeOut();
				$('#loginform').delay(3500).fadeIn();
			});		
		</script>
		
		<script language="javascript" type="text/javascript">
			function clearText(field)
			{
				if (field.defaultValue == field.value) field.value = '';
				else if (field.value == '') field.value = field.defaultValue;
			}
		</script>

		<script src="js/jquery.min.js"></script>

		<script type="text/javascript">
		$(document).ready(function(){
			$(".country").change(function(){
				var id=$(this).val(); var dataString = 'id='+ id;				
				$.ajax({ type: "POST", url: "../myaccount/includes/location/region.php", data: dataString, cache: false, success: function(html){ $(".region").html(html); } });
				$('#region option').each(function(index, option) { $(option).remove(); }); var option = document.createElement("option"); option.text = "--Please Wait--"; option.value = ""; var select = document.getElementById("region"); select.appendChild(option);
				$('#province option').each(function(index, option) { $(option).remove(); }); var option = document.createElement("option"); option.text = "--Select Region First--"; option.value = ""; var select = document.getElementById("province"); select.appendChild(option); $('#province').prop('disabled', true);
				$('#city option').each(function(index, option) { $(option).remove(); }); var option = document.createElement("option"); option.text = "--Select Province First--"; option.value = ""; var select = document.getElementById("city"); select.appendChild(option); $('#city').prop('disabled', true);
				$('#barangay option').each(function(index, option) { $(option).remove(); }); var option = document.createElement("option"); option.text = "--Select City First--"; option.value = ""; var select = document.getElementById("barangay"); select.appendChild(option); $('#barangay').prop('disabled', true);
			});

			$(".region").change(function(){
				var id=$(this).val(); var dataString = 'id='+ id;
				$.ajax({ type: "POST", url: "../myaccount/includes/location/province.php", data: dataString, cache: false, success: function(html){ $(".province").html(html); } });
				$('#province option').each(function(index, option) { $(option).remove(); }); var option = document.createElement("option"); option.text = "--Please Wait--"; option.value = ""; var select = document.getElementById("province"); select.appendChild(option);
				$('#city option').each(function(index, option) { $(option).remove(); }); var option = document.createElement("option"); option.text = "--Select Province First--"; option.value = ""; var select = document.getElementById("city"); select.appendChild(option); $('#city').prop('disabled', true);				
				$('#barangay option').each(function(index, option) { $(option).remove(); }); var option = document.createElement("option"); option.text = "--Select City First--"; option.value = ""; var select = document.getElementById("barangay"); select.appendChild(option); $('#barangay').prop('disabled', true);
			});

			$(".province").change(function(){
				var id=$(this).val(); var dataString = 'id='+ id;
				$.ajax({ type: "POST", url: "../myaccount/includes/location/city.php", data: dataString, cache: false, success: function(html){ $(".city").html(html); } });
				$('#city option').each(function(index, option) { $(option).remove(); }); var option = document.createElement("option"); option.text = "--Please Wait--"; option.value = ""; var select = document.getElementById("city"); select.appendChild(option);
				$('#barangay option').each(function(index, option) { $(option).remove(); }); var option = document.createElement("option"); option.text = "--Select City First--"; option.value = ""; var select = document.getElementById("barangay"); select.appendChild(option); $('#barangay').prop('disabled', true);
			});

			$(".city").change(function(){
				var id=$(this).val(); var dataString = 'id='+ id;
				$.ajax({ type: "POST", url: "../myaccount/includes/location/barangay.php", data: dataString, cache: false, success: function(html){ $(".barangay").html(html); } });
				$('#barangay option').each(function(index, option) { $(option).remove(); }); var option = document.createElement("option"); option.text = "--Please Wait--"; option.value = ""; var select = document.getElementById("barangay"); select.appendChild(option);
			});

			// Contact Address

			$(".ccountry").change(function(){
				var id=$(this).val(); var dataString = 'id='+ id;				
				$.ajax({ type: "POST", url: "../myaccount/includes/location/cregion.php", data: dataString, cache: false, success: function(html){ $(".cregion").html(html); } });
				$('#cregion option').each(function(index, option) { $(option).remove(); }); var option = document.createElement("option"); option.text = "--Please Wait--"; option.value = ""; var select = document.getElementById("cregion"); select.appendChild(option);
				$('#cprovince option').each(function(index, option) { $(option).remove(); }); var option = document.createElement("option"); option.text = "--Select Region First--"; option.value = ""; var select = document.getElementById("cprovince"); select.appendChild(option); $('#cprovince').prop('disabled', true);
				$('#ccity option').each(function(index, option) { $(option).remove(); }); var option = document.createElement("option"); option.text = "--Select Province First--"; option.value = ""; var select = document.getElementById("ccity"); select.appendChild(option); $('#ccity').prop('disabled', true);
				$('#cbarangay option').each(function(index, option) { $(option).remove(); }); var option = document.createElement("option"); option.text = "--Select City First--"; option.value = ""; var select = document.getElementById("cbarangay"); select.appendChild(option); $('#cbarangay').prop('disabled', true);
			});

			$(".cregion").change(function(){
				var id=$(this).val(); var dataString = 'id='+ id;
				$.ajax({ type: "POST", url: "../myaccount/includes/location/cprovince.php", data: dataString, cache: false, success: function(html){ $(".cprovince").html(html); } });
				$('#cprovince option').each(function(index, option) { $(option).remove(); }); var option = document.createElement("option"); option.text = "--Please Wait--"; option.value = ""; var select = document.getElementById("cprovince"); select.appendChild(option);
				$('#ccity option').each(function(index, option) { $(option).remove(); }); var option = document.createElement("option"); option.text = "--Select Province First--"; option.value = ""; var select = document.getElementById("ccity"); select.appendChild(option); $('#ccity').prop('disabled', true);				
				$('#cbarangay option').each(function(index, option) { $(option).remove(); }); var option = document.createElement("option"); option.text = "--Select City First--"; option.value = ""; var select = document.getElementById("cbarangay"); select.appendChild(option); $('#cbarangay').prop('disabled', true);
			});

			$(".cprovince").change(function(){
				var id=$(this).val(); var dataString = 'id='+ id;
				$.ajax({ type: "POST", url: "../myaccount/includes/location/ccity.php", data: dataString, cache: false, success: function(html){ $(".ccity").html(html); } });
				$('#ccity option').each(function(index, option) { $(option).remove(); }); var option = document.createElement("option"); option.text = "--Please Wait--"; option.value = ""; var select = document.getElementById("ccity"); select.appendChild(option);
				$('#cbarangay option').each(function(index, option) { $(option).remove(); }); var option = document.createElement("option"); option.text = "--Select City First--"; option.value = ""; var select = document.getElementById("cbarangay"); select.appendChild(option); $('#cbarangay').prop('disabled', true);
			});

			$(".ccity").change(function(){
				var id=$(this).val(); var dataString = 'id='+ id;
				$.ajax({ type: "POST", url: "../myaccount/includes/location/cbarangay.php", data: dataString, cache: false, success: function(html){ $(".cbarangay").html(html); } });
				$('#cbarangay option').each(function(index, option) { $(option).remove(); }); var option = document.createElement("option"); option.text = "--Please Wait--"; option.value = ""; var select = document.getElementById("cbarangay"); select.appendChild(option);
			});

		});
	
	// Same Address as Member
    function showMe (box) {
        var chboxs = document.getElementsByName("contactaddsame");
        var vis = "block";
        for(var i=0;i<chboxs.length;i++) { 
            if(chboxs[i].checked){
             vis = "none";
                break;
            }
        }
        document.getElementById(box).style.display = vis;    
    }

	// NUMBERS ONLY
	function isNumberKey(evt)
	{
		var charCode = (evt.which) ? evt.which : event.keyCode
		if (charCode > 31 && (charCode < 48 || charCode > 57))
		return false;
		return true;
	}
	
	// COMPUTE BDAY		
	function submitBday() {
		var Q4A = "";
		var Bdate = document.getElementById('dob').value;
		var Bday = +new Date(Bdate);
		Q4A += "<div class='form-group' style='width:80px'><label>Age</label><input class='form-control' type='text' readonly name='age' PLACEHOLDER='AGE:" + ~~((Date.now() - Bday) / (31557600000)) + "' style='width:80' value='" + ~~((Date.now() - Bday) / (31557600000)) + "'></div>";
		var theBday = document.getElementById('Ageresult');
		theBday.innerHTML = Q4A;
	}

	// FORM VALIDATION
		function validateForm()
		{
			var username = document.forms["enroll"]["username"].value;
			
			var password1 = document.forms["enroll"]["pass1"].value;
			var pass1count = document.forms["enroll"]["pass1"].length;
			var password2 = document.forms["enroll"]["pass2"].value;
			
			var fname = document.forms["enroll"]["fname"].value;
			var mname = document.forms["enroll"]["mname"].value;
			var lname = document.forms["enroll"]["lname"].value;
			var dob = document.forms["enroll"]["dob"].value;
			var gender = document.forms['enroll'].elements['gender'];
			
			var address = document.forms["enroll"]["address"].value;
			var country = document.forms["enroll"]["country"].value;
			var region = document.forms["enroll"]["region"].value;
			var province = document.forms["enroll"]["province"].value;
			var city = document.forms["enroll"]["city"].value;
			var barangay = document.forms["enroll"]["barangay"].value;
			var email = document.forms["enroll"]["email"].value;
			
			var dl = document.forms["enroll"]["dl"].value;
			var dl_expire = document.forms["enroll"]["dl_expire"].value;
			
			var contact = document.forms["enroll"]["contact"].value;
			
			var cfname = document.forms["enroll"]["cfname"].value;
			var clname = document.forms["enroll"]["clname"].value;
			var ccontact = document.forms["enroll"]["ccontact"].value;

			if (username==null || username=="") { alert("Enter valid username"); return false; }
			
			if (password1==null || password1=="" || pass1count >  8) { alert("Please enter password or password must be atleast 8 charater"); return false; }
			if(password2 != password1) { alert("Password did not match"); return false; }
			
			if (fname==null || fname=="") { alert("First name must be filled out"); return false; }
			if (mname==null || mname=="") { alert("Middle name must be filled out"); return false; }
			if (lname==null || lname=="") { alert("Last name must be filled out"); return false; }
			if (dob==null || dob=="") { alert("Date of Birth must be filled out"); return false; }

			len=gender.length-1;
			chkvalueg='';
			for(i=0; i<=len; i++){ if(gender[i].checked)chkvalueg=gender[i].value; }
			if(chkvalueg==''){ alert('Gender must be selected.'); return false; }

			if (address==null || address=="") { alert("Address must be filled out"); return false; }
			if (country==null || country=="") { alert("Country must be selected"); return false; }
			if (region==null || region=="") { alert("Region must be selected"); return false; }
			if (province==null || province=="") { alert("Province must be selected"); return false; }
			if (city==null || city=="") { alert("City must be selected"); return false; }
			if (barangay==null || barangay=="") { alert("Barangay must be selected"); return false; }
//			if (email==null || email=="") { alert("Enter valid email"); return false; }
			
			if (contact==null || contact=="") { alert("Enter contact number"); return false; }
			
			if (cfname==null || cfname=="") { alert("Contact's First name must be filled out"); return false; }
			if (clname==null || clname=="") { alert("Contact's Last name must be filled out"); return false; }
			if (ccontact==null || ccontact=="") { alert("Enter contact person's number"); return false; }
			
			if (document.forms["enroll"]["contactaddsame"].checked == false) { 
				// Get Value
				var caddress = document.forms["enroll"]["caddress"].value;
				var ccountry = document.forms["enroll"]["ccountry"].value;
				var cregion = document.forms["enroll"]["cregion"].value;
				var cprovince = document.forms["enroll"]["cprovince"].value;
				var ccity = document.forms["enroll"]["ccity"].value;
				var cbarangay = document.forms["enroll"]["cbarangay"].value;			
				
				// Validate
				if (caddress==null || caddress=="") { alert("Contact Address must be filled out"); return false; }
				if (ccountry==null || ccountry=="") { alert("Contact Country must be selected"); return false; }
				if (cregion==null || cregion=="") { alert("Contact Region must be selected"); return false; }
				if (cprovince==null || cprovince=="") { alert("Contact Province must be selected"); return false; }
				if (ccity==null || ccity=="") { alert("Contact City must be selected"); return false; }
				if (cbarangay==null || cbarangay=="") { alert("Contact Barangay must be selected"); return false; }
			}
		}

			$(document).ready(function() {
				// IF USERNAME ENTERED
				$("#username").blur(function (e) {
					//removes spaces from username
					$(this).val($(this).val().replace(/\s/g, ''));
					var username = $(this).val();
					
					if(username.length < 4){$("#user-result").html('');return;}

					if(username.length >= 4){
						$("#user-result").html('<img src="../myaccount/images/loading.gif" />');
						$.post('../myaccount/includes/validation/validate', {'username':username}, function(data) {
							$("#user-result").html(data);
						});
					}
				});

				// PASS VALIDATION
				$("#pass1").blur(function (e) {
					//removes spaces
					$(this).val($(this).val().replace(/\s/g, ''));
					var pass = $(this).val();

					if(pass.length < 1){$("#pass-result").html('');return;}

					if(pass.length >= 1){
						$("#pass-result").html('<img src="../myaccount/images/loading.gif" />');
						$.post('../myaccount/includes/validation/validate', {'pass':pass}, function(data) {
							$("#pass-result").html(data);
						});
					}
				});	

				// PASS CONFIRMATION
				$("#pass2").blur(function (e) {
					//removes spaces
					$(this).val($(this).val().replace(/\s/g, ''));
					var pass1 = document.forms["enroll"]["pass1"].value;
					var pass2 = $(this).val();
					
					if(pass2.length < 1){$("#pass2-result").html('');return;}

					if(pass2.length >= 1){
						$("#pass2-result").html('<img src="../myaccount/images/loading.gif" />');
						$.post('../myaccount/includes/validation/validate', {'pass1':pass1, 'pass2':pass2}, function(data) {
							$("#pass2-result").html(data);
						});
					}
				});	

				// IF EMAIL ENTERED
				$("#email").blur(function (e) {
					//removes spaces from email
					$(this).val($(this).val().replace(/\s/g, ''));
					var email = $(this).val();
					
					if(email.length < 1){$("#email-result").html('');return;}

					if(email.length >= 1){
						$("#email-result").html('<img src="../myaccount/images/loading.gif" />');
						$.post('../myaccount/includes/validation/validate', {'email':email}, function(data) {
							$("#email-result").html(data);
						});
					}
				});

			}); 
		</script>
	</head>
	<body>

		<div id="wrapper">

			<?php include_once('includes/navigation.php'); ?>

			<div id="page-wrapper">
				<div class="row">
					<div class="col-lg-12">
						<h3 class="page-header">Membership Form for <span class="text-success">Driver</span></h3>
						<?php // Display error message
							IF(!empty($err)) : 
								echo "<div class=\"alert alert-danger\"><strong>Please check the following fields:</strong><br />"; FOREACH ($err as $e) { echo "$e <br />"; } 

									$field_username = @$_SESSION['post']['username'];
									$field_pass1 = @$_SESSION['post']['pass1'];
									$field_pass2 = @$_SESSION['post']['pass2'];
									$field_fname = @$_SESSION['post']['fname'];
									$field_mname = @$_SESSION['post']['mname'];
									$field_lname = @$_SESSION['post']['lname'];
									$field_suffix = @$_SESSION['post']['suffix'];
									$field_dob = @$_SESSION['post']['dob'];
									$field_gender = @$_SESSION['post']['gender'];
									$field_address = @$_SESSION['post']['address'];
									$field_country = @$_SESSION['post']['country'];
									$field_region = @$_SESSION['post']['region'];
									$field_province = @$_SESSION['post']['province'];
									$field_city = @$_SESSION['post']['city'];
									$field_barangay = @$_SESSION['post']['barangay'];
									$field_email = @$_SESSION['post']['email'];

									$field_dl = @$_SESSION['post']['dl'];
									$field_dl_expire = @$_SESSION['post']['dl_expire'];
									$field_nbi = @$_SESSION['post']['nbi'];
									$field_nbi_expire = @$_SESSION['post']['nbi_expire'];
									$field_police = @$_SESSION['post']['police'];
									$field_police_expire = @$_SESSION['post']['police_expire'];
										
									$field_contact = @$_SESSION['post']['contact'];

									$field_cfname = @$_SESSION['post']['cfname'];
									$field_clname = @$_SESSION['post']['clname'];
									$field_ccontact = @$_SESSION['post']['ccontact'];
									$field_caddress = @$_SESSION['post']['caddress'];
									$field_ccountry = @$_SESSION['post']['ccountry'];
									$field_cregion = @$_SESSION['post']['cregion'];
									$field_cprovince = @$_SESSION['post']['cprovince'];
									$field_ccity = @$_SESSION['post']['ccity'];
									$field_cbarangay = @$_SESSION['post']['cbarangay'];
									
									echo "</div>";
								ENDIF;
							?>
					</div><!-- /.col-lg-12 -->
				</div><!-- /.row -->

				<div class="row">
					<div class="col-lg-12">
								
								<!-- SIGN UP -->
								<section id="signup">
									<div class="container">
										<span style="color:#003399">Note: <b>Field with asterisk (<span style="color:#FF0000">*</span>) are required.</b></span><br /><br />
											<form role="form" name="enroll" id="enroll" method="post" action="driver_signup" onsubmit="return validateForm()" autocomplete="off">
												<div class="form-group">
													<label>Username<span style="color:#FF0000">*</span></label>
													<input class="form-control" type="text" name="username" id="username" value="<?php IF(isset($field_username)): echo $field_username; ENDIF;?>" PLACEHOLDER="Username" style="width:300px" />
													<p class="help-block">Unique Username.</p>
													<span id="user-result"  style="padding-left:10px;"></span>
												</div>

												<div class="form-group">
													<label>Password<span style="color:#FF0000">*</span></label>
													<input class="form-control" type="password" name="pass1" id="pass1" value="" PLACEHOLDER="Password" style="width:300px" />
													<p class="help-block">Password Must be at least 8 Characters.</p>
													<span id="pass-result"  style="padding-left:10px"></span>
												</div>

												<div class="form-group">
													<label>Verify Password<span style="color:#FF0000">*</span></label>
													<input class="form-control" type="password" name="pass2" id="pass2" value="" PLACEHOLDER="Verify Password" style="width:300px" />
													<p class="help-block">Verify your password.</p>
													<span id="pass2-result"></span>
												</div>

												<div class="form-group">
													<label>Name<span style="color:#FF0000">*</span></label>
													<div>
														<input class="form-control pull-left" type="text" name="fname" id="fname" value="<?php IF(isset($field_fname)): echo $field_fname; ENDIF; ?>" PLACEHOLDER="First Name" style="margin-right:20px;width:180px"/>
														<input class="form-control pull-left" type="text" name="mname" id="mname" value="<?php IF(isset($field_mname)): echo $field_mname; ENDIF; ?>" PLACEHOLDER="Middle Name" style="margin-right:20px;width:180px"/>
														<input class="form-control pull-left" type="text" name="lname" id="lname" value="<?php IF(isset($field_lname)): echo $field_lname; ENDIF; ?>" PLACEHOLDER="Last Name" style="margin-right:20px;width:180px"/>
														<select class="form-control" name="suffix" id="suffix" style="width:100px">
															<option value="">e.g. Jr.</option>
															<option value="Jr." <?php IF(isset($field_suffix)): IF($field_suffix == "Jr."): echo "selected='selected'"; ENDIF; ENDIF; ?>>Jr.</option>
															<option value="Sr." <?php IF(isset($field_suffix)): IF($field_suffix == "Sr."): echo "selected='selected'"; ENDIF; ENDIF; ?>>Sr.</option>
															<option value="III" <?php IF(isset($field_suffix)): IF($field_suffix == "III"): echo "selected='selected'"; ENDIF; ENDIF; ?>>III</option>
														</select>
													</div>
												</div>

												<div class="form-group">										
													<label>Date of Birth<span style="color:#FF0000">*</span></label>
													<input type="text" id="cdate" value="<?php IF(isset($field_dob)): echo $field_dob; ELSE: echo date('m/d/Y'); ENDIF; ?>" style="display:none" />
													
													<?php
													$agedatemin = date('Y-m-d',strtotime("-70 year"));
													$agedatemax = date('Y-m-d',strtotime("-18 year"));
													?>
													
													<input class="form-control" type="date" name="dob" id="dob" PLACEHOLDER="mm/dd/YYYY" value="<?php IF(isset($field_dob)): echo $field_dob; ENDIF; ?>" onchange="submitBday()" style="width:145px" min="<?=$agedatemin;?>" max="<?=$agedatemax;?>" />
												</div>
												<span id="Ageresult" style="width:180px"></span>

												<div class="form-group">
													<label>Gender<span style="color:#FF0000">*</span></label>
													<div class="radio">
														<label>
															<input class="radiobox" type="radio" name="gender" id="gender1" value="1" <?php IF(isset($field_gender) && $field_gender == '1'): echo 'checked="checked"'; ENDIF; ?> />Male
														</label>
													</div>
													<div class="radio">
														<label>
															<input class="radiobox" type="radio" name="gender" id="gender2" value="2" <?php IF(isset($field_gender) && $field_gender == '2'): echo 'checked="checked"'; ENDIF; ?> />Female
														</label>
													</div>												
												</div>

												<div class="form-group" style="width:600px">
													<label>Member's Complete Address<span style="color:#FF0000">*</span></label>
													<input class="form-control" type="text" name="address" id="address" value="<?php IF(isset($field_address)): echo $field_address; ENDIF; ?>" PLACEHOLDER="Address" />
												</div>

												<div class="form-group" style="width:600px">
													<select name="country" id="country" class="form-control country select-wrapper">
														<option value="" selected="true" disabled="disabled">--Select Country--</option>
														<?php
															$sql = mysqli_query($link, "SELECT countries_name, countries_iso_code_2 FROM ".DB_PREFIX."countries WHERE enabled = 1");
															while ($country = mysqli_fetch_array($sql)){ ?>
														<option value="<?php echo $country['countries_iso_code_2']; ?>"><?php IF(isset($country)): echo $country['countries_name']; ENDIF; ?></option>
														<?php 
															} ?>
													</select>
												</div>

												<div class="form-group" style="width:600px">
													<select name="region" id="region" class="form-control region" disabled><option value="" selected="true" disabled="disabled">--Select Contry First--</option></select>
												</div>

												<div class="form-group" style="width:600px">
													<select name="province" id="province" class="form-control province"  disabled><option value="" selected="true" disabled="disabled">--Select Region First--</option></select>
												</div>

												<div class="form-group" style="width:600px">
													<select name="city" id="city" class="form-control city"  disabled><option value="" selected="true" disabled="disabled">--Select Province First--</option></select>
												</div>

												<div class="form-group" style="width:600px">
													<select name="barangay" id="barangay" class="form-control barangay" disabled><option value="" selected="true" disabled="disabled">--Select City First--</option></select>
												</div>

												<div class="form-group">
													<label>Email</label>
													<input class="form-control" type="text" name="email" id="email" value="<?php IF(isset($field_email)): echo $field_email; ENDIF; ?>" PLACEHOLDER="Email Address" style="width:300px"/><span id="email-result" style="padding-left:10px"></span>
												</div>
												
												<div class="form-group">
													<label>Driver's License<span style="color:#FF0000">*</span></label>
													<div>
														<input class="form-control pull-left" type="text" name="dl" id="dl" value="<?php IF(isset($field_dl)): echo $field_dl; ENDIF; ?>" PLACEHOLDER="Driver's License" style="width:300px"/>
														<input class="form-control pull-left" type="date" name="dl_expire" id="dl_expire" value="<?php IF(isset($field_dl_expire)): echo $field_dl_expire; ENDIF; ?>" PLACEHOLDER="Expiration Date" style="margin-left:20px;width:180px"   />
														<div id="dl-result"></div>
														<div style="clear:both"></div>
													</div>
												</div>

												<div class="form-group">
													<label>NBI Clearance</label>
													<div>
														<input class="form-control pull-left" type="text" name="nbi" id="nbi" value="<?php IF(isset($field_nbi)): echo $field_nbi; ENDIF; ?>" PLACEHOLDER="NBI Clearance" style="width:300px"/>
														<input class="form-control pull-left" type="date" name="nbi_expire" id="nbi_expire" value="<?php IF(isset($field_nbi_expire)): echo $field_nbi_expire; ENDIF; ?>" PLACEHOLDER="Expiration Date" style="margin-left:20px;width:180px"/>
														<div id="nbi-result"></div>
														<div style="clear:both"></div>
													</div>
												</div>

												<div class="form-group">
													<label>Police Clearance</label>
													<div>
														<input class="form-control pull-left" type="text" name="police" id="police" value="<?php IF(isset($field_police)): echo $field_police; ENDIF; ?>" PLACEHOLDER="Police Clearance" style="width:300px"/>
														<input class="form-control pull-left" type="date" name="police_expire" id="police_expire" value="<?php IF(isset($field_police_expire)): echo $field_police_expire; ENDIF; ?>" PLACEHOLDER="Expiration Date" style="margin-left:20px;width:180px"/>
														<div id="police-result"></div>
														<div style="clear:both"></div>
													</div>
												</div>

												<div class="form-group">
													<label>Personal Contact Info<span style="color:#FF0000">*</span></label>
													<input class="form-control" type="text" name="contact" id="contact" value="<?php IF(isset($field_contact)): echo $field_contact; ENDIF; ?>" PLACEHOLDER="Contact Number" style="width:300px" onkeypress="return isNumberKey(event)"/>
												</div>

												<div class="form-group">
													<label>Contact Person in Case of  Emergency<span style="color:#FF0000">*</span></label>
													<div>
														<input class="form-control" type="text" name="cfname" id="cfname" value="<?php IF(isset($field_cfname)): echo $field_cfname; ENDIF; ?>" PLACEHOLDER="Contact First Name" style="float:left; margin-right:20px;width:215px"/>
														<input class="form-control" type="text" name="clname" id="clname" value="<?php IF(isset($field_clname)): echo $field_clname; ENDIF; ?>" PLACEHOLDER="Contact Last Name" style="float:left; margin-right:20px;width:215px"/>
														<select class="form-control" name="csuffix" id="csuffix" style="width:100px">
															<option value="">e.g. Jr.</option>
															<option value="Jr." <?php IF(isset($cfield_suffix)): IF($cfield_suffix == "Jr."): echo "selected='selected'"; ENDIF; ENDIF; ?>>Jr.</option>
															<option value="Sr." <?php IF(isset($cfield_suffix)): IF($cfield_suffix == "Sr."): echo "selected='selected'"; ENDIF; ENDIF; ?>>Sr.</option>
															<option value="III" <?php IF(isset($cfield_suffix)): IF($cfield_suffix == "III"): echo "selected='selected'"; ENDIF; ENDIF; ?>>III</option>
														</select>
													</div>
												</div>

												<div class="form-group">
													<label>Contact Number<span style="color:#FF0000">*</span></label>
													<input class="form-control" type="text" name="ccontact" id="ccontact" value="<?php IF(isset($field_ccontact)): echo $field_ccontact; ENDIF; ?>" PLACEHOLDER="Contact Person's Number" style="width:300px" onkeypress="return isNumberKey(event)" />
												</div>

												<div class="form-group">
													<input type="checkbox" name="contactaddsame" id="contactaddsame" onclick="showMe('sameadd')" value="1">
													<label>Same Address as Member</label>
												</div>

												<div id="sameadd" style="display:block">
													<div class="form-group">
														<label>Contact Person's Complete Address<span style="color:#FF0000">*</span></label>
														<input style="width:600px" class="form-control" type="text" name="caddress" id="caddress" value="<?php IF(isset($field_address)): echo $field_address; ENDIF; ?>" PLACEHOLDER="Contact Address" />
													</div>

													<div class="form-group" style="width:600px">
														<select name="ccountry" id="ccountry" class="form-control ccountry">
															<option value="" selected="true" disabled="disabled">--Select Country--</option>
															<?php
																$sql = mysqli_query($link, "SELECT countries_name, countries_iso_code_2 FROM ".DB_PREFIX."countries WHERE enabled = 1");
																while ($country = mysqli_fetch_array($sql)){ ?>
															<option value="<?php echo $country['countries_iso_code_2']; ?>"><?php echo $country['countries_name']; ?></option>
															<?php 
																} ?>
														</select>
													</div>

													<div class="form-group" style="width:600px">
														<select name="cregion" id="cregion" class="form-control cregion" disabled><option value="" selected="true" disabled="disabled">--Select Contry First--</option></select>
													</div>

													<div class="form-group" style="width:600px">												
														<select name="cprovince" id="cprovince" class="form-control cprovince"  disabled><option value="" selected="true" disabled="disabled">--Select Region First--</option></select>
													</div>

													<div class="form-group" style="width:600px">
														<select name="ccity" id="ccity" class="form-control ccity"  disabled><option value="" selected="true" disabled="disabled">--Select Province First--</option></select>
													</div>

													<div class="form-group" style="width:600px">
														<select name="cbarangay" id="cbarangay" class="form-control cbarangay" disabled><option value="" selected="true" disabled="disabled">--Select City First--</option></select>
													</div>
												</div>

												<div  class="form-group">
													<button class="btn btn-default" type="submit" name="doRegister" id="doRegister" value="doRegister">Submit</button>
												</div>
												<?php unset($_SESSION['post']); ?>
											</form>
									</div>
								</section>
							</div>
						</div>
			</div>
		</div>
			
		<!-- jQuery -->
		<script src="js/jquery.min.js"></script>

		<!-- Bootstrap Core JavaScript -->
		<script src="js/bootstrap.min.js"></script>

		<!-- Metis Menu Plugin JavaScript -->
		<script src="js/metisMenu.min.js"></script>

		<!-- Custom Theme JavaScript -->
		<script src="js/startmin.js"></script>

		<!-- DataTables JavaScript -->
		<script src="js/dataTables/jquery.dataTables.min.js"></script>
		<script src="js/dataTables/dataTables.bootstrap.min.js"></script>

		<!-- Page-Level Demo Scripts - Tables - Use for reference -->
		<script src="js/dashboard.js"></script>

	</body>
</html>