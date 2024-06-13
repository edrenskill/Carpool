<?php
	require_once '../settings/connect.php';
	if(session_id() == '') { session_start(); } // START SESSIONS
		
		// Begin Login
	
	foreach($_POST as $key => $value) { $data[$key] = filter($value); }
	
	// Begin log process
	if(isset($_POST) && array_key_exists('Login',$_POST)){
		// Filter POST Variables
	
		$user_email = $data['username'];
		$pass = $data['password'];
	
		// Can use email or username to login
		if (strpos($user_email,'@') === false) { $user_cond = "username='$user_email'"; }else{ $user_cond = "email='$user_email'"; }
	
		$result = mysqli_query($link, "SELECT `ID`,`pword`,CONCAT(fname,' ',lname) AS full_name,`approval`,`userlevel`,`user_ID`,`terminal_ID` FROM ".DB_PREFIX."users WHERE $user_cond AND `account_status` = '0' AND userlevel = 5 ") or die (mysqli_error()); 
		$num = mysqli_num_rows($result);

		// Match row found with more than 1 results  - the user is authenticated. 
		if ( $num > 0 ) {
			list($id,$pwd,$full_name,$approved,$userlevel,$user_id, $terminal_ID) = mysqli_fetch_row($result);	
			if(!$approved) {
				$_SESSION['error_msg'] = "Account not activated. Please check your email for activation code or contact the administrator";
				header("Location: login");
			}	

			//check against salt
			
			if (password_verify($pass, $pwd)) {
				 if($_SESSION['error_msg'] == ""){
					// this sets session and logs user in
					session_regenerate_id (true); //prevent against session fixation attacks.

				   // this sets variables in the session
					$_SESSION['ADM']= $id;
					
					header("Location: index.php");
				}
			}else{ $_SESSION['error_msg'] = "Invalid Login. Please try again with correct user email and password."; header("Location: login"); }
		}else{ $_SESSION['error_msg'] = "Error - Invalid login. No such user exists"; header("Location: login"); }
	} // End log process
?>