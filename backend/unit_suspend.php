<?php
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS	
	
	date_default_timezone_set("Asia/Manila");	
	
	FOREACH($_POST as $key => $value) { $data[$key] = filter($value); }
	$err = array();
	
	
	IF(isset($_POST) && array_key_exists('dosuspend',$_POST)):
		
		$datesuspend = $data['datesuspend']." ".date("h:i:s");
		$datetosuspend = $data['datetosuspend']." ".date("h:i:s");
		$unit_ID = $_SESSION['vehicleID'];
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
			
		$suspend = "UPDATE ".DB_PREFIX."vehicles SET unit_status=1, date_from='{$datesuspend}', date_to='{$datetosuspend}', remarks='{$remarks}' WHERE unit_ID='{$unit_ID}'";

		mysqli_query($link, $suspend) or die("UPDATE Failed:" . mysqli_error($link));
			
			$successmessage = "Account has been suspended";
			
		ENDIF;
	ENDIF;
	
	date_default_timezone_set("Asia/Manila");
	
	$unit = $_SESSION['vehicleID'];
	$personal_info = mysqli_query($link, "SELECT unit_ID FROM ".DB_PREFIX."vehicles WHERE unit_ID = '{$unit}'");
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
				           <?php
                                 WHILE ($user = mysqli_fetch_array($personal_info)) {
									 
							?>
							<div class="form-group">								
								<h4><span class="NewCardCheck" id="NewCardCheck" name="NewCardCheck"></span></h4>
							</div>
						     <div class="12u(xsmall)" style="float:left;width:50%;" id="suspend-display">
                                    <div class="12u(xsmall)" style="float:left; width:90px;">Unit ID</div>
                                    <div class="6u(xsmall)" style="float:left; ">
                                        <strong>
                                            <span class="suspend"><?= $user['unit_ID']; ?></span>
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
								</div>
							</div>
							<?php
							
								IF(isset($_SESSION['vehicleID'])): unset($_SESSION['vehicleID']); ENDIF;

							ELSE: ?>
							
							<div name="suspendfield" id="suspendfield">
									<p style="color:#1d52ff">Please enter date range of suspention.</p>
									<form method="post" name="unit_suspend" action="unit_suspend" id="unit_suspend">
										<fieldset>	
											<div class="form-group">
												<div id="6u 12u(xsmall)">
													<li class="fa fa-calendar-minus-o"> From: </li>
													<input type="hidden" name="unit" id="unit" value="<?=$unit;?>"/>
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
