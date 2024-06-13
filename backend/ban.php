<?php
	include '../settings/connect.php';
	
	
	if(session_id() == '') { page_protect(); } // START SESSIONS
	IF(!isset($_SESSION['ADM']) && isset($_SESSION['user_id'])){ 
		header ('location: login');
	}ELSEIF(isset($_SESSION['ADM']) && !isset($_SESSION['user_id'])){ 
		unset($_SESSION['ADM']);
		header ('location: ../index');
	}ELSEIF(!isset($_SESSION['ADM']) && !isset($_SESSION['user_id'])){ 
		header ('location: ../index');
	}
	
	
	date_default_timezone_set("Asia/Manila");	
	
	FOREACH($_POST as $key => $value) { $data[$key] = filter($value); }
	$err = array();
	
	$remarks =$data['comment'];
	
	IF(isset($_POST) && array_key_exists('doban',$_POST)):
		
		IF(!isset($data['Tstatus']) || $_POST['Tstatus'] == 0):
		
			$err[] = "Please Click the checkbox to validate Banning of Member.";
			
		IF($remarks == ""): 
			$err[] = "remarks"; 
			$_SESSION['post']['remarks'] = $remarks;
		ENDIF;	
		
		ELSEIF(isset($data['Tstatus']) && $_POST['Tstatus'] == '2' && empty($err)):

			//SET TO MANUAL ID UMBER
			$status_ID = mysqli_real_escape_string($link, GenKey());

			$duplicates = mysqli_query($link, "SELECT status_ID FROM " . DB_PREFIX . "account_status WHERE status_ID='{$status_ID}'");
			WHILE (mysqli_fetch_array($duplicates)) {
				$status_ID = mysqli_real_escape_string($link, GenKey());
			}
			
			$userID = $data['userID'];
			$date = date("Y-m-d h:i:s");
			//Member
			$ban = "INSERT into ".DB_PREFIX."account_status (`user_ID`,`date_from`,`status_ID`,`remarks`) 
			VALUES ('$userID','$date','$status_ID','$remarks')";

			mysqli_query($link, $ban) or die("Insertion Failed:" . mysqli_error($link));
			
			// Set status to banned
			mysqli_query($link, "UPDATE ".DB_PREFIX."users SET account_status=2, status_ID='{$status_ID}' WHERE user_ID='{$userID}'");
			
			$successmessage = "Account has been banned";
			
		ENDIF;
	ENDIF;
	
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

							<div class="list-group">
                                <a href="#" class="list-group-item">
                                    Name
                                    <span class="pull-right text-muted small"><em>Account No.</em></span>
                                </a>
                                <a href="#" class="list-group-item">
                                    <i class="fa fa-user fa-fw"></i> <?=$user['fname']." ".$user['lname'];IF ($user['suffix'] != ""): echo ", " . $user['suffix'];ENDIF;?>
                                    <span class="pull-right text-success small"><em><?= $user['user_ID']; ?></em></span>
                                </a>
                            </div>
							<?php  }  ?>
							<div style="clear:both;"></div>	
						<?php // Display error message
							IF(!empty($err)) : 
								echo "<p><div ><span style=\"color:#ff0000\">"; FOREACH ($err as $e) { echo "$e <br />"; } echo "</span></div></p>"; 
								$field_remarks = $_SESSION['post']['remarks'];
							ENDIF;
							
							IF(isset($successmessage)): ?>
							
							<div name="banfield" id="banfield">
								<div>
									<h1 style="color:#1d52ff"><?= $successmessage; ?></h1>
									<a href="member_search">Search New</a>
								</div>
							</div>
							<?php
							
								IF(isset($_SESSION['IDusers'])): unset($_SESSION['IDusers']); ENDIF;
								IF(isset($_SESSION['email'])): unset($_SESSION['email']); ENDIF;
								IF(isset($_SESSION['Fname'])): unset($_SESSION['Fname']); ENDIF;

							ELSE: ?>
							
							<div name="banfield" id="banfield">
								<div>
									<form method="post" name="ban" action="ban" id="ban">
										<fieldset>	
											<div class="form-group"><label>status</label>
                                                <div class="checkbox">
													<input type="hidden" name="userID" id="userID" value="<?=$member_ID;?>"/>

                                                    <label>
                                                        <input name="Tstatus" id="Tstatus" type="checkbox" value="2"> Ban Member
                                                    </label>
                                                </div>
                                            </div>
											<textarea rows="4" cols="42" name="comment" id="comment" class="form-control" placeholder="Enter Remarks here..."><?= ($field_remarks)? $field_remarks : ''; ?></textarea>
											<div class="form-group">
												<button type="submit" class="btn btn-lg btn-success btn-block" value="doban" id="doban" name="doban" >Submit</button>
											</div>
										</fieldset>
									</form>
									</div>
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
