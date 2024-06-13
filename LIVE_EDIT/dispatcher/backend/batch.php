<?php
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	if(!isset($_SESSION['ADM'])): header('location: login'); ENDIF;

	foreach($_POST as $key => $value) { $data[$key] = filter($value); }	
	
	$_SESSION['member_batch'] = "";
	$err = "";
	$err2 = "";
	
	IF(isset($_POST) && array_key_exists('submitbatch',$_POST)):

		IF(!isset($data['newbatchname']) || $data['newbatchname'] == ""):
			$err = "Please enter new batch name or select from existing batch";
		ELSE:
				$newbatchname = $data['newbatchname'];
				$sql_insert = "INSERT into `".DB_PREFIX."member_batch` (`batch_name`)	VALUES ('$newbatchname')";

				mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
				$batch_id = mysqli_insert_id($link);

				$_SESSION['member_batch'] = $batch_id;
				HEADER('Location: signup');
				EXIT();
		ENDIF;
	ENDIF;
	
	IF(isset($_POST) && array_key_exists('submitoldbatch',$_POST)):

		IF(!isset($data['oldbatchname']) || $data['oldbatchname'] == ""):
			$err2 = "Please select from existing batch or enter new batch name";
		ELSE:
				$batchselect = mysqli_fetch_array(mysqli_query($link, "SELECT  `ID`, `batch_name` FROM `".DB_PREFIX."member_batch` WHERE `ID` = ".$data['oldbatchname'].""));	

				$_SESSION['member_batch'] = $batchselect['ID'];
				HEADER('Location: signup');
				EXIT();
		ENDIF;
	ENDIF;

$batch_select = mysqli_query($link, "SELECT  ID, batch_name FROM ".DB_PREFIX."member_batch");
?>

<html lang="en">
<head>
   <?php include_once('includes/header.php'); ?>

	<script type="text/javascript" src="../myaccount/js/jquery.min.js"></script>
	<script type="text/javascript" src="../myaccount/js/jquery.form.min.js"></script>
		
</head>
<body>

	<div id="wrapper">

		<?php include_once('includes/navigation.php'); ?>
		
		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">Member's Batch Registration</h1>
				</div><!-- /.col-lg-12 -->
			</div><!-- /.row -->

			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="col-lg-4" style="width:275">
								<div class="panel panel-primary">
									<div class="panel-heading">
										Enter New Batch Name
									</div>
									<div class="panel-body">
										<form action="batch" method="post" enctype="multipart/form-data" id="NewBatchForm">
											<div class="panel-body" id="enterNewID">
												<fieldset>
													<div class="form-group">
														<input class="form-control" type="text" name="newbatchname" id="newbatchname" value="" PLACEHOLDER="New Batch Name">
													</div>
													<button class="btn btn-lg btn-primary btn-block" type="submit"  id="submitbatch" name="submitbatch" value="submitbatch" />Continue</button>
												</fieldset>
											</div>
										</form>
									</div>
									<div class="panel-footer">
										<div id="output" align="center">
											<?php IF(isset($err)): echo $err; ENDIF; ?>
										</div>
									</div>
								</div>
							</div>
							
							<div class="col-lg-4" style="width:80">
								<h4>-OR-</h4>
							</div>

							<div class="col-lg-4" style="width:275">
								<div class="panel panel-green">
									<div class="panel-heading">
										Select Existing Batch
									</div>
									<div class="panel-body">
										<form action="batch" method="post" enctype="multipart/form-data" id="OldBatchForm">
											<div class="panel-body" id="enterNewID">
												<fieldset>
													<div class="form-group">
														<select class="form-control" name="oldbatchname" id="oldbatchname">
															<option value="">Select Existing Batch</option>
																			
															<?php WHILE($batchselect = mysqli_fetch_array($batch_select)){ ?>														
															<option value="<?= $batchselect['ID']; ?>"><?= strtoupper($batchselect['batch_name']); ?></option>
															<?php } ?>
														</select>
													</div>
													<button class="btn btn-lg btn-success btn-block" type="submit"  id="submitoldbatch" name="submitoldbatch" value="submitoldbatch" />Continue</button>
												</fieldset>
											</div>
										</form>
									</div>
									<div class="panel-footer">
										<div id="output" align="center">
											<?php IF(isset($err2)): echo $err2; ENDIF; ?>
										</div>
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

</body>
</html>
