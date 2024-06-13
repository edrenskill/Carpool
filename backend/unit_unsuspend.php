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
	
	
	FOREACH($_POST as $key => $value) { $data[$key] = filter($value); }
	
	$unit = $_SESSION['vehicleID'];
	$status = $_SESSION['unit_status'];
	IF(isset($_POST) && array_key_exists('lift',$_POST)):
	
		$unit_ID = $_POST['unit'];
	
		//Terminal
		$unsuspend = "UPDATE ".DB_PREFIX."vehicles SET unit_status=0 WHERE unit_ID='{$unit_ID}'";

		mysqli_query($link, $unsuspend) or die("UPDATE Failed:" . mysqli_error($link));
			
		$successmessage = "Account is now active";

	ENDIF;

?>            
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include ('includes/header.php'); ?>
    </head>
    <body>

        <div id="wrapper">

            <?php include ('includes/navigation.php'); ?>

            <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <h3 class="page-header">Unlift Suspension</h3>
                        </div><!-- /.col-lg-12 -->
						
						<div class="col-lg-4" style="width:275">
							<div class="panel panel-primary">
								<div class="panel-heading">
								
									<?php 
										IF(isset($successmessage)): 
											echo $successmessage; 
											unset($_SESSION['vehicleID']); 
											$unit = 0;
											$status = 0;
										ELSE:
											IF(isset($unit)):
												IF($status == 1):	echo "Lift suspension for Unit ID No. ".$unit.".";
												ELSEIF($status == 2): echo "This account is already banned.";
												ENDIF;
											ENDIF;
										ENDIF;

										IF(isset($unit) && $status == 1):
									?>
									
									<form method="post" action="unit_unsuspend" id="unit_unsuspend" name="unit_unsuspend">
										<input type="hidden" name="unit" id="unit" value="<?=$unit;?>"/>
										<button type="submit" class="btn btn-lg btn-success btn-block" value="lift" id="lift" name="lift" >Lift Suspension</button>
									</form>
									<?php ENDIF; ?>
								</div>						
						</div>					
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div><!-- /#page-wrapper -->

        </div>
        <!-- /#wrapper -->

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
