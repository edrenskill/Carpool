<?php
	require_once '../settings/connect.php';
	if(session_id() == '') { session_start(); } // START SESSIONS
		
		// Begin Login
	
	foreach($_POST as $key => $value) { $data[$key] = filter($value); }
	
	// Begin log process
	if(isset($_POST) && array_key_exists('Login',$_POST)){
		// Filter POST Variables
	
		$user_email = $data['uname'];
		$pass = $data['pword'];
	
		// Can use email or username to login
		if (strpos($user_email,'@') === false) { $user_cond = "username='$user_email'"; }else{ $user_cond = "email='$user_email'"; }
	
		$result = mysqli_query($link, "SELECT `ID`,`pword`,CONCAT(fname,' ',lname) AS full_name,`approval`,`userlevel`,`user_ID`,`terminal_ID`, `account_status` FROM ".DB_PREFIX."users WHERE $user_cond ") or die (mysqli_error()); 
		$num = mysqli_num_rows($result);

		// Match row found with more than 1 results  - the user is authenticated. 
		if ( $num > 0 ) {
			list($id,$pwd,$full_name,$approved,$userlevel,$user_id, $terminal_ID, $status) = mysqli_fetch_row($result);	
			if(!$approved) {
				//$_SESSION['error_msg'] = "Account not activated. Please check your email for activation code or contact the administrator";
				$_SESSION['act_ID'] = $user_id;
				$_SESSION['full_name'] = $full_name;
				header("Location: account_activation");
				exit;
			}
			
			if($status!=0) {
				//$_SESSION['error_msg'] = "Account not activated. Please check your email for activation code or contact the administrator";
				$_SESSION['act_ID'] = $user_id;
				$_SESSION['full_name'] = $full_name;
				$_SESSION['account_status'] = $status;
				header("Location: notification");
				exit;
			}

			//check against salt
			
			if (password_verify($pass, $pwd)) {
				 if($_SESSION['error_msg'] == ""){
					// this sets session and logs user in
					session_regenerate_id (true); //prevent against session fixation attacks.

				   // this sets variables in the session
					$_SESSION['user_id']= $id;
					$_SESSION['full_name'] = $full_name;
					$_SESSION['user_level'] = $userlevel;
					$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
					$_SESSION['act_ID'] = $user_id;
					$_SESSION['terminal_ID'] = $terminal_ID;
					
					//update the timestamp and key for cookie
					$stamp = time();
					$ckey = GenKey();
					$logdate = date("Y-m-d h:m:s");
					$userip = $_SERVER['REMOTE_ADDR'];
					
					mysqli_query($link, "update ".DB_PREFIX."users set `ctime`='$stamp', `ckey` = '$ckey' where id='$id'") or die(mysqli_error());
					mysqli_query($link, "INSERT INTO ".DB_PREFIX."user_log_history (`user_ID`,`log_date_time`,`IP_add`) VALUES ('$user_id','$logdate','$userip')");
					//set a cookie
				   if(isset($_POST['remember'])){
						setcookie("user_id", $_SESSION['user_id'], time()+60*60*24*COOKIE_TIME_OUT, "/");
						setcookie("user_key", sha1($ckey), time()+60*60*24*COOKIE_TIME_OUT, "/");
						setcookie("user_name",$_SESSION['user_name'], time()+60*60*24*COOKIE_TIME_OUT, "/");
					}
					header("Location: verify_2ndlevel");
				}
			}else{ $_SESSION['error_msg'] = "Invalid Login. Please try again with correct user email and password."; header("Location: login"); }
		}else{ $_SESSION['error_msg'] = "Error - Invalid login. No such user exists"; header("Location: login"); }
	} // End log process
?>