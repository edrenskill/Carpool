<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	if(!isset($_SESSION['ADM'])): header('location: login'); ENDIF;

	foreach($_POST as $key => $value) { $data[$key] = filter($value); }	
	
	$user =$data['userID'];
	$Fname =$data['Fname']." ".$data['lname'];
	$email =$data['email'];	

	// SEARCH USER ID
	IF(isset($_POST) && array_key_exists('submitUID',$_POST)):
		$userID = $data['userID'];
		
		IF(!isset($userID)):
			$err = "Please enter Valid User ID";
		ELSE:
			$query = "SELECT COUNT(user_ID) AS `users` FROM `".DB_PREFIX."users` WHERE user_ID='{$userID}'";
			$row = mysqli_fetch_array(mysqli_query($link,$query));
			$totalv = $row['users'];
								
				IF($totalv):
					$_SESSION['IDusers'] = $userID;
					$query = mysqli_query($link, "SELECT driver_ID, status FROM `".DB_PREFIX."driver_status` WHERE driver_ID='{$userID}' AND status=1 OR status=2");
					$countquery = mysqli_num_rows($query);
					IF($countquery):
						
						$getstatus = mysqli_fetch_array($query);
						
						$driverstatus = $getstatus['status'];
						IF($driverstatus == 1):
							$_SESSION['dstatus'] = 1;
							header ("location: notify");
						ELSEIF($driverstatus == 2):
							$_SESSION['dstatus'] = 2;
							header ("location: notify");						
						ENDIF;
					ELSE:
						HEADER('Location: delete_unit');
						EXIT();
					ENDIF;
				ELSE:
					$err = "No record on file";
				ENDIF;
		ENDIF;
	ENDIF;
	
	//SEARCH EMAIL
	IF(isset($_POST) && array_key_exists('submitEmail',$_POST)):

		// VALIDATE EMAIL
		$email =  trim($data["email"]); //trim email
		$email = filter_var($email, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH); //sanitize email
		IF($email == ""): 
			$erremail = "Enter Valid Email Address"; 
		ELSE:
			$results = mysqli_query($link, "SELECT user_ID FROM ".DB_PREFIX."users WHERE email='{$email}'");
			$email_exist = mysqli_num_rows($results);
			IF(!filter_var($email, FILTER_VALIDATE_EMAIL)): 
				$erremail = "Email is not valid";
			ELSEIF(!$email_exist): 
				$erremail = "Email does not exist!";
			ELSE:
				$user_ID = mysqli_fetch_array($results);
				$_SESSION['IDusers'] = $user_ID['user_ID'];
				HEADER('Location: delete_unit');
				EXIT();
			ENDIF;
		ENDIF;
	ENDIF;
	
	IF(isset($_POST) && array_key_exists('submitFname',$_POST)):
		$fullname = $data['fullname'];
		
		IF($data['fullname'] == ""): 
			$errname = "Enter complete name";
		ELSE:
			$searchname = mysqli_query($link, "SELECT user_ID FROM `".DB_PREFIX."users` WHERE CONCAT(`fname`,' ',`lname`) LIKE '%{$fullname}%'");
			$name_exist = mysqli_num_rows($searchname);
			IF($name_exist):
				$user_ID = mysqli_fetch_array($searchname);
				$_SESSION['IDusers'] = $user_ID['user_ID'];
				HEADER('Location: delete_unit');
				EXIT();
			ELSE:
				$errname = "No record on file!";
			ENDIF;
		ENDIF;
	ENDIF;
?>

<html lang="en">
<head>
   <?php include_once('includes/header.php'); ?>

	<script src="js/jquery.min.js"></script>
		
</head>
<body>

	<div id="wrapper">

		<?php include_once('includes/navigation.php'); ?>
		
		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">Select User ID</h1>
				</div><!-- /.col-lg-12 -->
			</div><!-- /.row -->

			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="col-lg-4" style="width:275">
								<div class="panel panel-primary">
									<div class="panel-heading">
										Enter User ID
									</div>
									<div class="panel-body">
										<form action="rewrite_id" method="post" enctype="multipart/form-data" id="UserIDForm">
											<div class="panel-body" id="enterIDusers">
												<fieldset>
													<div class="form-group">
														<input class="form-control" type="text" name="userID" id="userID" value="" PLACEHOLDER="User ID">
													</div>

													<button class="btn btn-lg btn-primary btn-block" type="submit"  id="submitUID" name="submitUID" value="submitUID" /><i class="fa fa-search"></i> Search</button>
												</fieldset>
											</div>
										</form>
									</div>
									<div class="panel-footer">
										<div id="IDoutput" align="center">
											<?php IF(isset($err)): echo $err; ENDIF; ?>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-4" style="width:275">
								<div class="panel panel-primary">
									<div class="panel-heading">
										Enter Email
									</div>
									<div class="panel-body">
										<form action="rewrite_id" method="post" enctype="multipart/form-data" id="UserrmailForm">
											<div class="panel-body" id="enterIDusers">
												<fieldset>
													<div class="form-group">
														<input class="form-control" type="text" name="email" id="email" value="" PLACEHOLDER="Enter Email">
													</div>

													<button class="btn btn-lg btn-primary btn-block" type="submit"  id="submitEmail" name="submitEmail" value="submitUID" /><i class="fa fa-search"></i> Search</button>
												</fieldset>
											</div>
										</form>
									</div>
									<div class="panel-footer">
										<div id="emailoutput" align="center">
											<?php IF(isset($erremail)): echo $erremail; ENDIF; ?>
										</div>
									</div>
								</div>								
						</div>
						<div class="col-lg-4" style="width:275">
								<div class="panel panel-primary">
									<div class="panel-heading">
										Enter Fullname
									</div>
									<div class="panel-body">
										<form action="rewrite_id" method="post" enctype="multipart/form-data" id="UserFnameForm">
											<div class="panel-body" id="enterIDusers">
												<fieldset>
													<div class="form-group">
														<input class="form-control" type="text" name="fullname" id="fullname" value="" PLACEHOLDER="Enter Full Name">
													</div>

													<button class="btn btn-lg btn-primary btn-block" type="submit"  id="submitFname" name="submitFname" value="submitUID" /><i class="fa fa-search"></i> Search</button>
												</fieldset>
											</div>
										</form>
									</div>
									<div class="panel-footer">
										<div id="nameoutput" align="center">
											<?php IF(isset($errname)): echo $errname; ENDIF; ?>
										</div>
									</div>
								</div>
							
						</div>
					</div>
				</div>
			</div><!-- /#page-wrapper -->
		</div>
	</div>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="js/metisMenu.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="js/startmin.js"></script>
<script src="js/dataTables/dataTables.bootstrap.min.js"></script>

</body>
</html>