<?php
	include '../settings/connect.php';
	session_start();
	IF(isset($_SESSION['user_id'])): header("location: ../myaccount/"); ENDIF;

	date_default_timezone_set("Asia/Manila");

	FOREACH ($_POST as $key => $value) {
		$data[$key] = filter($value);
	}
	$err = array();
	IF (isset($_POST) && array_key_exists('doRegister', $_POST)):

		// VALIDATE USERNAME
		$username = strtolower(trim($data["username"])); //trim and lowercase username
		$username = filter_var($username, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH); //sanitize username

		IF ($username == ""): $err[] = "Valid username";
		ELSE:
			$results = mysqli_query($link, "SELECT id FROM " . DB_PREFIX . "users WHERE username='$username'");
			$username_exist = mysqli_num_rows($results); //total records
			IF ($username_exist): $err[] = "Username already exist";
			ENDIF; // already exist
		ENDIF;

		IF ($data['pass1'] == "" || strlen($data['pass1']) < 8): $err[] = "Password cannot be blank or at least 8 characters";
		ELSEIF ($data['pass2'] == "" || $data['pass2'] != $data['pass1']): $err[] = "Password do not match";
		ENDIF;

		IF ($data['fname'] == "" || $data['mname'] == "" || $data['lname'] == ""): $err[] = "Complete Name (First, Middle and Last name)";
		ENDIF;
		IF ($data['address'] == ""): $err[] = "Address";
		ENDIF;
		IF ($data['country'] == ""): $err[] = "Country";
		ENDIF;
		IF ($data['region'] == ""): $err[] = "Region";
		ENDIF;
		IF ($data['province'] == ""): $err[] = "Province";
		ENDIF;
		IF ($data['city'] == ""): $err[] = "City";
		ENDIF;
		IF ($data['barangay'] == ""): $err[] = "Barangay";
		ENDIF;

		// VALIDATE EMAIL
		$email = trim($_POST["email"]); //trim email
		$email = filter_var($email, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH); //sanitize email
		IF ($email == ""): $err[] = "Email Address";
		ELSE:
			$results = mysqli_query($link, "SELECT id FROM " . DB_PREFIX . "users WHERE email='$email'");
			$email_exist = mysqli_num_rows($results);
			IF (!filter_var($email, FILTER_VALIDATE_EMAIL)): "Email is not valid";
			ELSEIF ($email_exist): $err[] = "Email already exist";
			ENDIF;
		ENDIF;

		IF ($data['contact'] == "" || $data['contact'] == 0): $err[] = "Contact Number";
		ENDIF;
		
		IF(isset($data['discounted1']) || $data['discounted1'] == 1){
			IF($data['school-name'] == ""): $err[] = "School Name"; ENDIF;
			IF($data['school-id'] == ""): $err[] = "School ID No."; ENDIF;
			IF($data['schoo-expiry'] == ""): $err[] = "School ID Expiry Date"; ENDIF;
		//	IF($data['id_pic'] == ""): $err[] = "School ID Picture"; ENDIF;
		}ELSEIF(isset($data['discounted2']) || $data['discounted2'] == 1){
			IF($data['Senior-IDno'] == ""): $err[] = "Senior ID Number"; ENDIF;
		}
			

		IF (!empty($err)):
			$_SESSION['post']['username'] = $data['username'];
			$_SESSION['post']['pass1'] = $data['pass1'];
			$_SESSION['post']['pass2'] = $data['pass2'];
			$_SESSION['post']['fname'] = $data['fname'];
			$_SESSION['post']['mname'] = $data['mname'];
			$_SESSION['post']['lname'] = $data['lname'];
			$_SESSION['post']['suffix'] = $data['suffix'];
			$_SESSION['post']['address'] = $data['address'];
			$_SESSION['post']['country'] = $data['country'];
			$_SESSION['post']['region'] = $data['region'];
			$_SESSION['post']['province'] = $data['province'];
			$_SESSION['post']['city'] = $data['city'];
			$_SESSION['post']['barangay'] = $data['barangay'];
			$_SESSION['post']['email'] = $data['email'];
			$_SESSION['post']['contact'] = $data['contact'];
			
			IF(isset($data['discounted1']) || $data['discounted1'] == 1){
				$_SESSION['post']['school-name'] = $data['school-name'];
				$_SESSION['post']['school-id'] = $data['school-id'];
				$_SESSION['post']['schoo-expiry'] = $data['schoo-expiry'];
			//	$_SESSION['post']['id_pic'] = $data['id_pic'];
				$_SESSION['studentselect'] = 1;
			}ELSEIF(isset($data['discounted2']) || $data['discounted2'] == 1){
				$_SESSION['post']['Senior-IDno'] = $data['Senior-IDno'];
				$_SESSION['seniorselect'] = 1;
			}

			HEADER('Location: ' . $_SESSION['REAL_REFERRER']);
		ELSEIF (empty($err)):

			$username = $data['username'];
			$password = $data['pass1'];
			$fname = $data['fname'];
			$mname = $data['mname'];
			$lname = $data['lname'];
			$suffix = $data['suffix'];
			$address = $data['address'];
			$country = $data['country'];
			$region = $data['region'];
			$province = $data['province'];
			$city = $data['city'];
			$barangay = $data['barangay'];
			$email = $data['email'];
			$contact = $data['contact'];
			
			IF(isset($data['discounted1']) || $data['discounted1'] == 1){
				$schoolname = $data['school-name'];
				$schoolID = $data['school-id'];
				$shoolExpiry = $data['schoo-expiry'];
			//	$IDPic = $data['id_pic'];
			}ELSEIF(isset($data['discounted2']) || $data['discounted2'] == 1){
				$Senior = $data['Senior-IDno'];
			}

			$userip = $_SERVER['REMOTE_ADDR']; // User IP Address
			//$sha1pass = PwdHash($password); // stores sha1 of password
			$sha1pass = password_hash($password, PASSWORD_DEFAULT);

			//SET TO MANUAL ID UMBER
			$gen_account = "TEMP-" . mysqli_real_escape_string($link, GenID());

			$duplicates = mysqli_query($link, "SELECT user_ID FROM " . DB_PREFIX . "users WHERE user_ID='$gen_account'");
			WHILE (mysqli_fetch_array($duplicates)) {
				$gen_account = "TEMP-" . mysqli_real_escape_string($link, GenID());
			}
			
			IF(isset($data['discounted1']) || $data['discounted1'] == 1){
				
				$sql_insert = "INSERT into `" . DB_PREFIX . "discount_account` (`user_ID`,`school_name`,`ID_NO`,`expiry`,`status`)
				VALUES ('$gen_account','$school_name','$schoolID','$schoolExpiry','1')";
				mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
				
			}ELSEIF(isset($data['discounted2']) || $data['discounted2'] == 1){
				$sql_insert = "INSERT into `" . DB_PREFIX . "discount_account` (`user_ID`,`ID_NO`,`status`)
				VALUES ('$gen_account','$Senior','1')";
				mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
			}

			$sql_insert = "INSERT into `" . DB_PREFIX . "users` (`username`,`pword`,`userlevel`,`email`,`fname`,`mname`,`lname`,`suffix`,`address`,`country`,`region`,`province`,`city`,`barangay`,`mobile`,`regdate`,`ipadd`,`user_ID`)
				VALUES ('$username','$sha1pass','1','$email','$fname','$mname','$lname','$suffix','$address','$country','$region','$province','$city','$barangay','$contact',now(),'$userip','$gen_account')";

			mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
			$user_id = mysqli_insert_id($link);
			$md5_id = md5($user_id);

			// Update user md5
			mysqli_query($link, "UPDATE `" . DB_PREFIX . "users` SET md5_id='$md5_id' WHERE id='$user_id'");

			$_SESSION['uname'] = $username;
			$_SESSION['pass'] = $password;
			$_SESSION['sha1pass'] = $sha1pass;
			$_SESSION['fname'] = $fname;
			$_SESSION['SPONSOR_ID'] = $sponsorID;
			$_SESSION['act_ID'] = $gen_account;
			HEADER('Location: confirmed');
		ENDIF;
	ENDIF;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
		<?php include_once("includes/header.php"); ?>
		<script type="text/javascript">
			// Show Hide Fields
			function showStudent (box) {
				var chboxs = document.getElementsByName("discounted1");
				var vis = "none";
				for(var i=0;i<chboxs.length;i++) { 
					if(chboxs[i].checked){
					 vis = "block";
						break;
					}
				}
				document.getElementById(box).style.display = vis;
				$("#student-form").load("includes/add_forms/student");
				$("#senior-pwd-form").empty();
			}		
			/////////////////////////////////////////////////////////		
			
			function showSenior (box) {
				var chboxs = document.getElementsByName("discounted2");
				var vis = "none";
				for(var i=0;i<chboxs.length;i++) { 
					if(chboxs[i].checked){
					 vis = "block";
						break;
					}
				}
				document.getElementById(box).style.display = vis;
				$("#senior-pwd-form").load("includes/add_forms/senior-pwd");
				$("#student-form").empty();
			}
			
			////////////////////////////////////////////////////////////
		</script>
    </head>
    <body>
		
		<div id="wrapper">
		
		<?php include_once("includes/nav.php"); ?>
		
		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">Signup</h1>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-8">
					<div class="panel panel-default">
						<div class="panel-heading"><i class="fa fa-warning" style="color:#F9E814"></i> Note: <strong>Field with asterisk (<i class="fa fa-asterisk" style="color:#FF0000;font-size:9px"></i>) are required.</strong></div>
							
						<div class="panel-body">
							<div class="row">
								<?php
								// Display error message
								IF (!empty($err)) : ?>
									<div class="form-group">
										<div class="alert alert-danger alert-dismissable">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
											Please check the following fields: <br/><?php FOREACH ($err as $e) { echo "$e <br />"; } echo "</div></p>"; ?>
										</div>
										<?php  
											$field_username = @$_SESSION['post']['username'];
											$field_pass1 = @$_SESSION['post']['pass1'];
											$field_pass2 = @$_SESSION['post']['pass2'];
											$field_fname = @$_SESSION['post']['fname'];
											$field_mname = @$_SESSION['post']['mname'];
											$field_lname = @$_SESSION['post']['lname'];
											$field_suffix = @$_SESSION['post']['suffix'];
											$field_address = @$_SESSION['post']['address'];
											$field_country = @$_SESSION['post']['country'];
											$field_region = @$_SESSION['post']['region'];
											$field_province = @$_SESSION['post']['province'];
											$field_city = @$_SESSION['post']['city'];
											$field_barangay = @$_SESSION['post']['barangay'];
											$field_email = @$_SESSION['post']['email'];
											$field_contact = @$_SESSION['post']['contact'];
										?>
									</div>
								<?php ENDIF; ?>
								<div class="col-lg-12">
									<form name="enroll" id="enroll" method="post" action="register" onsubmit="return validateForm()">	
										
									
										<div class="form-group">
											<label>Username<i class="fa fa-asterisk" style="color:#FF0000;font-size:9px"></i></label>
											<input class="form-control" type="text" name="username" id="username" value="<?php IF (isset($field_username)): echo $field_username; ENDIF; ?>" PLACEHOLDER="Username" style="width:300px" /><span id="user-result"  style="padding-left:10px;"></span>
										</div>
										
										<div class="form-group">
											<label>Password<i class="fa fa-asterisk" style="color:#FF0000;font-size:9px"></i></label>
											<input class="form-control" type="password" name="pass1" id="pass1" value="" PLACEHOLDER="Password" style="width:300px" /><span id="pass-result"  style="padding-left:10px;"></span>
										</div>
										
										<div class="form-group">
											<label>Verify Password<i class="fa fa-asterisk" style="color:#FF0000;font-size:9px"></i></label>
											<input class="form-control" type="password" name="pass2" id="pass2" value="" PLACEHOLDER="Verify Password" style="width:300px" /><span id="pass2-result"  style="padding-left:10px;"></span>
										</div>
										
										<div class="form-group">
											<label>Name<i class="fa fa-asterisk" style="color:#FF0000;font-size:9px"></i></label>
											<div>
												<div style="float:left; margin-right:10px"><input class="form-control" type="text" name="fname" id="fname" value="<?php IF (isset($field_fname)): echo $field_fname; ENDIF; ?>" PLACEHOLDER="First Name" style="width:180px" /></div>
												<div style="float:left; margin-right:10px"><input class="form-control" type="text" name="mname" id="mname" value="<?php IF (isset($field_mname)): echo $field_mname; ENDIF; ?>" PLACEHOLDER="Middle Name" style="width:180px" /></div>
												<div style="float:left; margin-right:10px"><input class="form-control" type="text" name="lname" id="lname" value="<?php IF (isset($field_lname)): echo $field_lname; ENDIF; ?>" PLACEHOLDER="Last Name" style="width:180px" /></div>
												<div style="float:left">
													<select class="form-control" name="suffix" id="suffix" style="width:80px">
														<option value="">e.g. Jr.</option>
														<option value="Jr." <?php IF (isset($field_suffix)): IF ($field_suffix == "Jr."): echo "selected='selected'"; ENDIF; ENDIF; ?>>Jr.</option>
														<option value="Sr." <?php IF (isset($field_suffix)): IF ($field_suffix == "Sr."): echo "selected='selected'"; ENDIF; ENDIF; ?>>Sr.</option>
														<option value="III" <?php IF (isset($field_suffix)): IF ($field_suffix == "III"): echo "selected='selected'"; ENDIF; ENDIF; ?>>III</option>
													</select>
												</div>
											</div>
											<div style="clear:both"></div>
										</div>
										<br/>
										
										<div class="form-group">
											<label>Complete Address<i class="fa fa-asterisk" style="color:#FF0000;font-size:9px"></i></label>
											<input class="form-control" type="text" name="address" id="address" value="<?php IF (isset($field_address)): echo $field_address; ENDIF; ?>" PLACEHOLDER="Address" />
										</div>
										<br />
										<div class="form-group" >
											<select style="width:300px" name="country" id="country" class="form-control country select-wrapper">
												<option value="" selected="true" disabled="disabled">--Select Country--</option>
												<?php
													$sql = mysqli_query($link, "SELECT countries_name, countries_iso_code_2 FROM " . DB_PREFIX . "countries WHERE enabled = 1");
													while ($country = mysqli_fetch_array($sql)) {
												?>
												<option value="<?php echo $country['countries_iso_code_2']; ?>"><?php IF (isset($country)): echo $country['countries_name']; ENDIF; ?></option>
												<?php } ?>
											</select>
										</div>
									
										<div class="form-group" >
											<select style="width:300px" name="region" id="region" class="form-control region" disabled><option value="" selected="true" disabled="disabled">--Select Country First--</option></select>
										</div>
										
										<div class="form-group" >
											<select style="width:300px" name="province" id="province" class="form-control province"  disabled><option value="" selected="true" disabled="disabled">--Select Region First--</option></select>
										</div>
										
										<div class="form-group" >
											<select style="width:300px" name="city" id="city" class="form-control city"  disabled><option value="" selected="true" disabled="disabled">--Select Province First--</option></select>
										</div>
										
										<div class="form-group" >
											<select style="width:300px" name="barangay" id="barangay" class="form-control barangay" disabled><option value="" selected="true" disabled="disabled">--Select City First--</option></select>
										</div>
									
										<div class="form-group">
											<label>Email<i class="fa fa-asterisk" style="color:#FF0000;font-size:9px"></i></label>
											<input class="form-control" type="text" name="email" id="email" value="<?php IF (isset($field_email)): echo $field_email; ENDIF; ?>" PLACEHOLDER="Email Address" style="width:300px" autocomplete="off" /><span id="email-result" style="padding-left:10px"></span>
											<div id="msg"></div>
										</div>
										
										<div class="form-group">
											<label>Mobile No.<i class="fa fa-asterisk" style="color:#FF0000;font-size:9px"></i></label>
											<input class="form-control" type="text" name="contact" id="contact" value="<?php IF (isset($field_contact)): echo $field_contact; ENDIF; ?>" PLACEHOLDER="Contact Number" style="width:300px" onkeypress="return isNumberKey(event)" />
										</div>
										
										<hr/>

										<div class="form-group">
											<label><span style="color:#0000FF">Discounted Card, check the box to activate the card</span></label>
											<div class="checkbox">
												<label class="checkbox-inline">
													<input type="checkbox" name="discounted1" id="discounted1" <?= (isset($_SESSION['studentselect']))? "checked" : ""; ?> class="discount_selection" value="1" onclick="if(this.checked) {document.enroll.discounted2.checked=false; $('#senior-pwd').hide();$('#student').show('slow');} showStudent('student')" /><span id="student-label">Student</span>
												</label>
												<label class="checkbox-inline">
													<input type="checkbox" name="discounted2" id="discounted2" <?= (isset($_SESSION['seniorselect']))? "checked" : ""; ?> class="discount_selection" value="2" onclick="if(this.checked) {document.enroll.discounted1.checked=false; $('#student').hide();$('#senior-pwd').show('slow');} showSenior('senior-pwd')" /><span id="senior-label">Senior/PWD</span>
												</label>
											</div>
										</div>
										
										<div class="form-group" id="student" name="student" style="display:none">
											<div class="form-group" id="student-form" name="student-form">
											</div>
										</div>
										
										<div class="form-group" id="senior-pwd" name="senior-pwd" style="display:none">
											<div class="form-group" id="senior-pwd-form" name="senior-pwd-form">
											</div>
										</div>
										
										<hr/>

										<div class="form-group">
											<label>
												By clicking "<span style="color:#0000FF"> Continue</span>" I have read and agree to the<a href="terms_and_condition"target="_blank">Terms, Condition </a>and Privacy Policy of Carpoolphil.net
											</label>
										</div>
										
										<div class="form-group">
											<button class="btn btn-primary btn-lg" type="submit" name="doRegister" id="doRegister" value="doRegister">Continue</button>
										</div>

									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- jQuery -->
		<script src="../myaccount/js/jquery.min.js"></script>

		<!-- Bootstrap Core JavaScript -->
		<script src="../myaccount/js/bootstrap.min.js"></script>

		<!-- Metis Menu Plugin JavaScript -->
		<script src="../myaccount/js/metisMenu.min.js"></script>

		<!-- Custom Theme JavaScript -->
		<script src="../myaccount/js/startmin.js"></script>

		<script type="text/javascript">
			// NUMBERS ONLY
            function isNumberKey(evt)
            {
                var charCode = (evt.which) ? evt.which : event.keyCode
                if (charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;
            }

///////////////////////////////////////////////////////////	
			<?php 
			IF(isset($_SESSION['studentselect'])){ 
			
			$schoolname = $_SESSION['post']['school-name'];
			$schoolID = $_SESSION['post']['school-id'];
			$expiry = $_SESSION['post']['schoo-expiry'];
			$IDpic = $_SESSION['post']['id_pic'];
			
			?>
				
				if ($("#discounted1").is(':checked')){
					$("#student-form").load("includes/add_forms/student");
					$('#student').show();
					document.getElementById("school-name").value = "<?=$schoolname;?>";
				}

			<?php 
			unset($_SESSION['studentselect']);
			} ?>
			
	/////////////////////////////////////////////////////////		
			<?php IF(isset($_SESSION['seniorselect'])){
				$senior_ID = $_SESSION['post']['Senior-IDno']; ?>
			
				if ($("#discounted2").is(':checked')){
					$("#senior-pwd-form").load("includes/add_forms/senior-pwd");
					$('#senior-pwd').show();
					
				}
				
			<?php 
			unset($_SESSION['seniorselect']);
			}	?>

////////////////////////////////////////////////////////////			
			
            $(document).ready(function () {
                $('#message').delay(3000).fadeOut();

                $(".country").change(function () {
                    var id = $(this).val();
                    var dataString = 'id=' + id;
                    $.ajax({type: "POST", url: "../myaccount/includes/location/region.php", data: dataString, cache: false, success: function (html) {
                            $(".region").html(html);
                        }});
                    $('#region option').each(function (index, option) {
                        $(option).remove();
                    });
                    var option = document.createElement("option");
                    option.text = "--Please Wait--";
                    option.value = "";
                    var select = document.getElementById("region");
                    select.appendChild(option);
                    $('#province option').each(function (index, option) {
                        $(option).remove();
                    });
                    var option = document.createElement("option");
                    option.text = "--Select Region First--";
                    option.value = "";
                    var select = document.getElementById("province");
                    select.appendChild(option);
                    $('#province').prop('disabled', true);
                    $('#city option').each(function (index, option) {
                        $(option).remove();
                    });
                    var option = document.createElement("option");
                    option.text = "--Select Province First--";
                    option.value = "";
                    var select = document.getElementById("city");
                    select.appendChild(option);
                    $('#city').prop('disabled', true);
                    $('#barangay option').each(function (index, option) {
                        $(option).remove();
                    });
                    var option = document.createElement("option");
                    option.text = "--Select City First--";
                    option.value = "";
                    var select = document.getElementById("barangay");
                    select.appendChild(option);
                    $('#barangay').prop('disabled', true);
                });

                $(".region").change(function () {
                    var id = $(this).val();
                    var dataString = 'id=' + id;
                    $.ajax({type: "POST", url: "../myaccount/includes/location/province.php", data: dataString, cache: false, success: function (html) {
                            $(".province").html(html);
                        }});
                    $('#province option').each(function (index, option) {
                        $(option).remove();
                    });
                    var option = document.createElement("option");
                    option.text = "--Please Wait--";
                    option.value = "";
                    var select = document.getElementById("province");
                    select.appendChild(option);
                    $('#city option').each(function (index, option) {
                        $(option).remove();
                    });
                    var option = document.createElement("option");
                    option.text = "--Select Province First--";
                    option.value = "";
                    var select = document.getElementById("city");
                    select.appendChild(option);
                    $('#city').prop('disabled', true);
                    $('#barangay option').each(function (index, option) {
                        $(option).remove();
                    });
                    var option = document.createElement("option");
                    option.text = "--Select City First--";
                    option.value = "";
                    var select = document.getElementById("barangay");
                    select.appendChild(option);
                    $('#barangay').prop('disabled', true);
                });

                $(".province").change(function () {
                    var id = $(this).val();
                    var dataString = 'id=' + id;
                    $.ajax({type: "POST", url: "../myaccount/includes/location/city.php", data: dataString, cache: false, success: function (html) {
                            $(".city").html(html);
                        }});
                    $('#city option').each(function (index, option) {
                        $(option).remove();
                    });
                    var option = document.createElement("option");
                    option.text = "--Please Wait--";
                    option.value = "";
                    var select = document.getElementById("city");
                    select.appendChild(option);
                    $('#barangay option').each(function (index, option) {
                        $(option).remove();
                    });
                    var option = document.createElement("option");
                    option.text = "--Select City First--";
                    option.value = "";
                    var select = document.getElementById("barangay");
                    select.appendChild(option);
                    $('#barangay').prop('disabled', true);
                });

                $(".city").change(function () {
                    var id = $(this).val();
                    var dataString = 'id=' + id;
                    $.ajax({type: "POST", url: "../myaccount/includes/location/barangay.php", data: dataString, cache: false, success: function (html) {
                            $(".barangay").html(html);
                        }});
                    $('#barangay option').each(function (index, option) {
                        $(option).remove();
                    });
                    var option = document.createElement("option");
                    option.text = "--Please Wait--";
                    option.value = "";
                    var select = document.getElementById("barangay");
                    select.appendChild(option);
                });
            });

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
                var address = document.forms["enroll"]["address"].value;
                var country = document.forms["enroll"]["country"].value;
                var region = document.forms["enroll"]["region"].value;
                var province = document.forms["enroll"]["province"].value;
                var city = document.forms["enroll"]["city"].value;
                var barangay = document.forms["enroll"]["barangay"].value;
                var email = document.forms["enroll"]["email"].value;
                var contact = document.forms["enroll"]["contact"].value;
			

                // VALIDATE
                if (username == null || username == "") {
                    alert("Enter valid username");
                    return false;
                }

                if (password1 == null || password1 == "" || pass1count > 8) {
                    alert("Please enter password or password must be atleast 8 charater");
                    return false;
                }
                if (password2 != password1) {
                    alert("Password did not match");
                    return false;
                }

                if (fname == null || fname == "") {
                    alert("First name must be filled out");
                    return false;
                }
                if (mname == null || mname == "") {
                    alert("Middle name must be filled out");
                    return false;
                }
                if (lname == null || lname == "") {
                    alert("Last name must be filled out");
                    return false;
                }


                if (address == null || address == "") {
                    alert("Address must be filled out");
                    return false;
                }
                if (country == null || country == "") {
                    alert("Country must be selected");
                    return false;
                }
                if (region == null || region == "") {
                    alert("Region must be selected");
                    return false;
                }
                if (province == null || province == "") {
                    alert("Province must be selected");
                    return false;
                }
                if (city == null || city == "") {
                    alert("City must be selected");
                    return false;
                }
                if (barangay == null || barangay == "") {
                    alert("Barangay must be selected");
                    return false;
                }
                if (email == null || email == "") {
                    alert("Enter valid email");
                    return false;
                }
                if (contact == null || contact == "") {
                    alert("Enter contact number");
                    return false;
                }

				
				if (document.forms["enroll"]["discounted1"].checked == true) { 
					// Get Value
					var schoolname = document.forms["enroll"]["school-name"].value;
					var schoolid = document.forms["enroll"]["school-id"].value;
					var expiry = document.forms["enroll"]["school-expiry"].value;
					var idpic = document.forms["enroll"]["id_pic"].value;			
					
					// Validate
					if (schoolname==null || schoolname=="") { alert("Enter name of school"); return false; }
					if (schoolid==null || schoolid=="") { alert("Enter school ID number"); return false; }
					if (expiry==null || expiry=="") { alert("CEnter expiry date"); return false; }
					if (idpic==null || idpic=="") { alert("Please uplod picture of your school ID"); return false; }
				}
				
				if (document.forms["enroll"]["discounted2"].checked == true) {
					// Get Value
					var SeniorIDno = document.forms["enroll"]["Senior-IDno"].value;	
					
					// Validate
					if (SeniorIDno==null || SeniorIDno=="") { alert("Enter senior citizen ID No."); return false; }
				}

            }

            $(document).ready(function () {
                // IF USERNAME ENTERED
                $("#username").blur(function (e) {
                    //removes spaces from username
                    $(this).val($(this).val().replace(/\s/g, ''));
                    var username = $(this).val();

                    if (username.length < 4) {
                        $("#user-result").html('');
                        return;
                    }

                    if (username.length >= 4) {
                        $("#user-result").html('<img src="../myaccount/images/loading.gif" />');
                        $.post('../myaccount/includes/validation/validate', {'username': username}, function (data) {
                            $("#user-result").html(data);
                        });
                    }
                });

                // PASS VALIDATION
                $("#pass1").blur(function (e) {
                    //removes spaces
                    $(this).val($(this).val().replace(/\s/g, ''));
                    var pass = $(this).val();

                    if (pass.length < 1) {
                        $("#pass-result").html('');
                        return;
                    }

                    if (pass.length >= 1) {
                        $("#pass-result").html('<img src="../myaccount/images/loading.gif" />');
                        $.post('../myaccount/includes/validation/validate', {'pass': pass}, function (data) {
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

                    if (pass2.length < 1) {
                        $("#pass2-result").html('');
                        return;
                    }

                    if (pass2.length >= 1) {
                        $("#pass2-result").html('<img src="../myaccount/images/loading.gif" />');
                        $.post('../myaccount/includes/validation/validate', {'pass1': pass1, 'pass2': pass2}, function (data) {
                            $("#pass2-result").html(data);
                        });
                    }
                });

                // IF EMAIL ENTERED
                $("#email").blur(function (e) {
                    //removes spaces from email
                    $(this).val($(this).val().replace(/\s/g, ''));
                    var email = $(this).val();

                    if (email.length < 1) {
                        $("#email-result").html('');
                        return;
                    }

                    if (email.length >= 1) {
                        $("#email-result").html('<img src="../myaccount/images/loading.gif" />');
                        $.post('../myaccount/includes/validation/validate', {'email': email}, function (data) {
                            $("#email-result").html(data);
                        });
                    }
                });
            }); // END USERNAME/EMAIL
        </script>
    </body>
</html>
