<?php
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS	
	
	date_default_timezone_set("Asia/Manila");	
	
	FOREACH($_POST as $key => $value) { $data[$key] = filter($value); }
	$err = array();
	
	
	IF(isset($_POST) && array_key_exists('dosuspend',$_POST)):
		
		$datesuspend = $data['datesuspend']." ".date("h:i:s");
		$datetosuspend = $data['datetosuspend']." ".date("h:i:s");
		$userID = $data['userID'];
		$remarks =$data['comment'];
	
		IF(false === strtotime($datesuspend) || false === strtotime($datetosuspend)): 
			$err[] = "Please enter Valid date";
		  ENDIF;
		IF($remarks == ""): $err[] = "remarks"; ENDIF;


		IF(!empty($err)):
			$_SESSION['post']['datefrom'] = $datesuspend;
			$_SESSION['post']['dateto'] = $datetosuspend;
			$_SESSION['post']['remarks'] = $remarks;
			
			//header ("location: suspend");

		ELSEIF(empty($err)):
			//Terminal
			
			//SET TO MANUAL ID UMBER
			$status_ID = mysqli_real_escape_string($link, GenKey());

			$duplicates = mysqli_query($link, "SELECT status_ID FROM " . DB_PREFIX . "account_status WHERE status_ID='{$status_ID}'");
			WHILE (mysqli_fetch_array($duplicates)) {
				$status_ID = mysqli_real_escape_string($link, GenKey());
			}

			$suspenddate = "INSERT into ".DB_PREFIX."account_status (`date_from`,`date_to`,`user_ID`,`status_ID`,`remarks`) 
			VALUES ('$datesuspend','$datetosuspend','$userID','$status_ID','$remarks')";

			mysqli_query($link, $suspenddate) or die("Insertion Failed:" . mysqli_error($link));
			
			mysqli_query($link, "UPDATE ".DB_PREFIX."users SET account_status=1, status_ID='{$status_ID}' WHERE user_ID='{$userID}'");
			
			$successmessage = "Account has been suspended";
			
		ENDIF;
	ENDIF;
	
//	IF(!isset($_SESSION['IDusers'])):
	//	header("location: member_search");

//	ENDIF;
	
	date_default_timezone_set("Asia/Manila");
	
	$member_ID = $_SESSION['IDusers'];
	$personal_info = mysqli_query($link, "SELECT email, fname, mname, lname, suffix,  user_ID FROM ".DB_PREFIX."users WHERE user_ID = '{$member_ID}'");
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include_once('includes/header.php'); ?>
	</head>
    <body>

        <div id="wrapper">

            <?php include ('includes/navigation.php'); ?>

            <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4">

                            <h1 class="page-header">Details</h1>
							
                        </div><!-- /.col-lg-12 -->
                    </div><!-- /.row -->
					
					<div class="panel panel-default" style="width:350px">
                        <div class="panel-heading">
							<span style="float:left;"><h4>User Profile</h4></span>
							<div style="clear:both;"></div>
	
				           <?php
                                 WHILE ($user = mysqli_fetch_array($personal_info)) {
									 
							?>
							<div class="form-group">								
								<h4><span class="NewCardCheck" id="NewCardCheck" name="NewCardCheck"></span></h4>
							</div>
						     <div class="12u(xsmall)" style="float:left;width:50%;" id="name-display">
                                    <div class="12u(xsmall)" style="float:left; width:90px;">Name</div>
                                    <div class="6u(xsmall)" style="float:left; ">
                                        <strong>
                                            <span class="fname"><?= $user['fname']; ?></span>
                                            <span class="lname"><?= $user['lname']; ?></span>
                                            <?php
                                            IF ($user['suffix'] != ""): echo ", " . $user['suffix'];
                                            ENDIF;
											
                                            ?>
                                        </strong>
										<div style="clear:both;"></div>
                                    </div>								
                                </div>
								
								 <?php  }  ?>
								<div style="clear:both;"></div>	
						<?php // Display error message
							IF(!empty($err)) : 
								echo "<p><div class=\"msg\" id=\"message\"><span style=\"color:#ff0000\">Please check the following fields:</span><br />"; FOREACH ($err as $e) { echo "$e <br />"; } echo "</div></p>"; 
								$field_datefrom = $_SESSION['post']['datefrom'];
								$field_dateto = $_SESSION['post']['dateto'];
								$field_remarks = $_SESSION['post']['remarks'];
							ENDIF;
							
							IF(isset($successmessage)): ?>
							
							<div name="suspendfield" id="suspendfielder">
								<div>
									<h3 class="text-danger"><?= $successmessage; ?></h3>
									<form action="member_search" method="post" enctype="multipart/form-data" id="UserForm">
										<div class="form-group input-group" style="width:300px">
											<span class="input-group-addon"><li class="fa fa-user"></li></span><input type="text" class="form-control" name="userID" id="userID" placeholder="User Search">
											<span class="input-group-btn"><input class="btn btn-default" type="submit" name="submitUID" id="submitUID" value="Search"></span>
										</div>
									</form>
								</div>
							</div>
							<?php
							
								IF(isset($_SESSION['IDusers'])): unset($_SESSION['IDusers']); ENDIF;
								IF(isset($_SESSION['email'])): unset($_SESSION['email']); ENDIF;
								IF(isset($_SESSION['Fname'])): unset($_SESSION['Fname']); ENDIF;

							ELSE: ?>
							
							<div name="suspendfield" id="suspendfield">
							
								<?php IF(!ISSET($_SESSION['IDusers'])): ?>
									<div name="suspendfield" id="suspendfielder">
										<div>
											<h4 class="text-warning">Please Select Member Account</h4>
											<form action="member_search" method="post" enctype="multipart/form-data" id="UserForm">
												<div class="form-group input-group" style="width:300px">
													<span class="input-group-addon"><li class="fa fa-user"></li></span><input type="text" class="form-control" name="userID" id="userID" placeholder="User Search">
													<span class="input-group-btn"><input class="btn btn-default" type="submit" name="submitUID" id="submitUID" value="Search"></span>
												</div>
											</form>
										</div>
									</div>
								<?php ELSE: ?>
									<p style="color:#1d52ff">Please enter date range of suspention.</p>
									<form method="post" name="suspend" action="suspend" id="suspend">
										<fieldset>	
											<div class="form-group">
												<div id="6u 12u(xsmall)">
													<li class="fa fa-calendar-minus-o"> From: </li>
													<input type="hidden" name="userID" id="userID" value="<?=$member_ID;?>"/>
													<input class="form-control" name="datesuspend" type="date" id="datesuspend" value="<?= ($field_datefrom)? $field_datefrom : ''; ?>" />
												</div>
											</div>
											<div class="form-group">
												<div id="6u 12u(xsmall)">
													<li class="fa fa-calendar-minus-o"> To: </li>
													<input class="form-control" name="datetosuspend" type="date" id="datetosuspend" value="<?= ($field_dateto)? $field_dateto : ''; ?>"/>
												</div>
											</div>
											<textarea rows="4" cols="42" name="comment" id="comment" class="form-control" placeholder="Enter text here..."><?= ($field_remarks)? $field_remarks : ''; ?></textarea>
											<div class="form-group">
												<button type="submit" class="btn btn-lg btn-danger btn-block" value="dosuspend" id="dosuspend" name="dosuspend" >Suspend Account</button>
											</div>
										</fieldset>
									</form>
								<?php ENDIF; ?>
								</div>
							</div>
							
							<?php  
							ENDIF; 
						
							?>
                        </div><!-- /.col-lg-12 -->
                    </div>
					
					<!-- /.row -->
                </div><!-- /.container-fluid -->
            </div><!-- /#page-wrapper -->

        </div><!-- /#wrapper -->

        <!-- jQuery -->
        <script src="js/jquery.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="js/bootstrap.min.js"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="js/metisMenu.min.js"></script>

        <!-- Custom Theme JavaScript -->
        <script src="js/startmin.js"></script>

    </body>
</html>
