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
	
	
	IF(isset($_POST) && array_key_exists('dograndted',$_POST)):
		
		IF(!isset($data['Tstatus']) || $_POST['Tstatus'] == 0):
		
			$err[] = "Please Click the checkbox to Application Approve .";	
		
		ELSEIF(isset($data['Tstatus']) && $_POST['Tstatus'] == '2' && empty($err)):

			$date = date("Y-m-d h:i:s");
			$unit_ID = $_SESSION['vehicleID'];
			//Member
		//Terminal
		$grandted = "UPDATE ".DB_PREFIX."vehicles SET application_status=2 WHERE unit_ID='{$unit_ID}'";

		mysqli_query($link, $grandted) or die("UPDATE Failed:" . mysqli_error($link));
		
			$successmessage = "Application Granted";
			
		ENDIF;
	ENDIF;
	
	date_default_timezone_set("Asia/Manila");
	
	$vehicle = $_SESSION['vehicleID'];
	$unit = mysqli_query($link, "SELECT unit_ID FROM ".DB_PREFIX."vehicles WHERE unit_ID = '{$vehicle}'");
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
					
					<div class="panel panel-default" style="width:250px">
                        <div class="panel-heading">
							<div style="clear:both;"></div>
								
				           <?php
                                 WHILE ($user = mysqli_fetch_array($unit)) {
									 
							?>
							<div class="form-group">								
								<h4><span class="NewCardCheck" id="NewCardCheck" name="NewCardCheck"></span></h4>
							</div>
							  <div class="12u(xsmall)" style="float:left;width:50%;" id="unitID-display">
                                    <div class="12u(xsmall)" style="float:left; width:90px;">Unit ID</div>
                                    <div class="6u(xsmall)" style="float:left; ">
                                        <strong>
										
                                            <span class="unitID"><?= $user['unit_ID']; ?></span>
                                        </strong>
										<div style="clear:both;"></div>
                                    </div>								
                                </div>
								 <?php  }  ?>
								<div style="clear:both;"></div>	
						<?php // Display error message
							IF(!empty($err)) : 
								echo "<p><div ><span style=\"color:#ff0000\">"; FOREACH ($err as $e) { echo "$e <br />"; } echo "</span></div></p>"; 
							ENDIF;
							
							IF(isset($successmessage)): ?>
							
							<div name="deniedfield" id="deniedfield">
								<div>
									<h1 style="color:#008B00"><?= $successmessage; ?></h1>
								</div>
							</div>
							<?php
							
								IF(isset($_SESSION['vehicleID'])): unset($_SESSION['vehicleID']); ENDIF;
							ELSE: ?>
							
							<div name="deniedfield" id="deniedfield">
								<div>
									<form method="post" name="grandted" action="grandted" id="grandted">
										<fieldset>	
											<div class="form-group"><label>status</label>
                                                <div class="checkbox">
													<input type="hidden" name="vehicle" id="vehicle" value="<?=$vehicle;?>"/>

                                                    <label>
                                                        <input name="Tstatus" id="Tstatus" type="checkbox" value="2"> Approve Application
                                                    </label>
                                                </div>
                                            </div>
											<div class="form-group">
												<button type="submit" class="btn btn-lg btn-success btn-block" value="dograndted" id="dograndted" name="dograndted" >Submit</button>
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
