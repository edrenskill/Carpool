<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	
?>

<html lang="en">
<head>
   <?php include_once('includes/header.php'); ?>

	<script type="text/javascript" src="../profile/assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="../profile/assets/js/jquery.form.min.js"></script>

</head>
<body>

	<div id="wrapper">

		<?php include_once('includes/navigation.php'); ?>
		
		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">Member Management</h1>
					<h3><a href="member_search"><i class="fa fa-arrow-left"></i>Back to Search Panel</a></h3>
				</div><!-- /.col-lg-12 -->
			</div><!-- /.row -->

			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="col-lg-4" style="width:275">
								<div class="panel panel-green">
									<div class="panel-heading">
										Select Option
									</div>
									<div class="panel-body">
										<form action="delete_unit" method="post" enctype="multipart/form-data" id="ManageForm">
											<div class="panel-body" id="selectoptionID">
												<fieldset>
													<div class="form-group">
													<h4><Strong style="float: left; width: 100%;"><span><i class="fa fa-credit-card"></i><a  href="new_userID">Change ID</a></span></Strong></h4>
													<h4><Strong style="float: left; width: 100%;"><span><i class="fa fa-exclamation-triangle" ></i><a href="suspend">Suspend ID</a></span></Strong></h4>
													<h4><Strong style="float: left; width: 100%;"><span><i class="fa fa-ban"></i><a href="ban">Ban ID</a></span></Strong></h4>
													</div>
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
