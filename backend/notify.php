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
	
	$userID = $_SESSION['IDusers'];
	$status = $_SESSION['status'];
	IF(isset($_POST) && array_key_exists('lift',$_POST)):
	
		$user_ID = $_POST['userID'];
	
		//Terminal
		$unsuspend = "UPDATE ".DB_PREFIX."users SET account_status=0, status_ID=0 WHERE user_ID='{$user_ID}'";

		mysqli_query($link, $unsuspend) or die("Insertion Failed:" . mysqli_error($link));
			
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
                            <h3 class="page-header">Unlift Suspension <?=$status?></h3>
                        </div><!-- /.col-lg-12 -->
						
						<div class="col-lg-4" style="width:275">
							<div class="panel panel-primary">
								<div class="panel-heading">
								
									<?php 
										IF(isset($successmessage)): 
											echo $successmessage; 
											unset($_SESSION['IDusers']); 
											$userID = 0;
											$status = 0;
										ELSE:
											IF(isset($userID)):
												IF($status == 1):	echo "Lift suspension for account ID No. ".$userID.".";
												ELSEIF($status == 2): echo "This account is already banned.";
												ENDIF;
											ENDIF;
										ENDIF;

										IF(isset($userID) && $status == 1):
									?>
									
									<form method="post" action="notify" id="notifyuser" name="notifyuser">
										<input type="hidden" name="userID" id="userID" value="<?=$userID;?>"/>
										<button type="submit" class="btn btn-lg btn-success btn-block" value="lift" id="lift" name="lift" >Lift Suspension</button>
									</form>
									<?php ENDIF; ?>
								</div>						
								<div class="panel-footer">
									<form action="member_search" method="post" enctype="multipart/form-data" id="UserForm">
										<div class="form-group input-group" style="width:300px">
											<span class="input-group-addon"><li class="fa fa-user"></li></span><input type="text" class="form-control" name="userID" id="userID" placeholder="User Search">
											<span class="input-group-btn"><input class="btn btn-default" type="submit" name="submitUID" id="submitUID" value="Search"></span>
										</div>
									</form>
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
