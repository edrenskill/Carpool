<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	if(!isset($_SESSION['ADM'])): header('location: login'); ENDIF;
	
	foreach($_POST as $key => $value) { $data[$key] = filter($value); }	

	// SEARCH USER ID
	IF(isset($_POST) && array_key_exists('submitDID',$_POST)):
		
		$userdata =$data['userID'];
		$terminal_ID = $data['terID'];
		
		IF(!isset($userdata) || $userdata == ""):
			$err = "Cannot Search Blank";
		ELSE:
			
			$query = mysqli_query($link,"SELECT user_ID, terminal_ID FROM `".DB_PREFIX."users` WHERE `user_ID`='{$userdata}' AND userlevel=2");
			$total_count = mysqli_num_rows($query);
			IF($total_count !=0 ):	

				$row = mysqli_fetch_array($query);
				$terminal = $row['terminal_ID'];
				IF(!empty($terminal) && $terminal == $terminal_ID):
					$err = "This user is already in this terminal";
				ELSEIF(!empty($terminal) && $terminal != $terminal_ID):
					$err = "This user is already in other terminal";
				ELSE:
					mysqli_query($link, "UPDATE ".DB_PREFIX."users SET terminal_ID='{$terminal_ID}' WHERE user_ID='{$userdata}'");	
					$message = "New Dispatcher has been added to terminal with ID no. ".$terminal_ID;
				ENDIF;
			ELSE:
				$err = "Dispatcher ID not found";
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
					<h1 class="page-header">Select Dispatcher ID</h1>
				</div><!-- /.col-lg-12 -->
			</div><!-- /.row -->

			<div class="row">
				<div class="col-lg-12">
					<div class="panel-body">
						<form action="add_dispatcher" method="post" enctype="multipart/form-data" id="dispatcherForm">
							<div class="form-group input-group" style="width:300px">
								<input type="hidden" name="terID" id="terID" value="<?=$terminal_ID;?>"/>
								<span class="input-group-addon"><li class="fa fa-user-plus"></li></span><input type="text" class="form-control" name="userID" id="userID" placeholder="Enter Dispatcher ID">
								<span class="input-group-btn"><input class="btn btn-default" type="submit" name="submitDID" id="submitDID" value="Search"></span>
							</div>
						</form>
					</div>	
				</div>
			</div><!-- /#page-wrapper -->
			<div class="row">
				<div class="col-lg-12">
					<div class="panel-body">
					
						<?php IF(isset($err)):?>
							<div class="panel-heading">
								<div class="alert alert-warning">
									<?= $err;?>
								</div>
							</div>
						<?php ENDIF; 
							IF(!empty($message)): ?>
							<!-- /.panel-heading -->
							<div class="panel-body">
								<h3><?=$message;?></h3>
							</div>
						<?php
							ENDIF;
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
<script src="js/jquery.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="js/bootstrap.min.js"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="js/metisMenu.min.js"></script>

        <script src="js/dataTables/jquery.dataTables.min.js"></script>
        <script src="js/dataTables/dataTables.bootstrap.min.js"></script>

        <!-- Custom Theme JavaScript -->
        <script src="js/startmin.js"></script>

        <script>
            $(document).ready(function() {
                $('#Search_Result_Table').DataTable({
                        responsive: true
                });
            });
        </script>

</body>
</html>