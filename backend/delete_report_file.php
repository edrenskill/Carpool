<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	if(!isset($_SESSION['ADM'])): header('location: login'); ENDIF
	
	IF(isset($_POST['remve_txt']));
	
		$file_Path = $_POST['txtToRemove'];
		
		// check if the file exist
		IF(file_exists($txt_Path));
		
			unlink($txt_Path);
			echo 'File Deleted';
		ENDIF
	ENDIF
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
					<h1 class="page-header">Delete Member Report</h1>
				</div><!-- /.col-lg-12 -->
			</div><!-- /.row -->

			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="col-lg-6">
								<div class="panel panel-primary">
									<div class="panel-heading">
										Member Report
									</div>
									<div class="panel-body">
										<form action="delete_report_file" method="post" enctype="multipart/form-data" id="delete">
											<div class="panel-body" id="filedeleted">
										<form action="delete_report_file">									
											<p><label><input type="checkbox" id="checkAll" value="<?= basename($pdf, ""); ?>"/> Check all</label></p>
													<?php $directory = "report/reportfile/member_report/";

													$pdf = glob(("$directory") . "*.txt", GLOB_BRACE);
													 
													//print each file name
													foreach($pdf as $pdf)
													{ ?>
													
													
													<input  type="checkbox" name="" value="" />     <span><?= basename($pdf, "");?></span>       <i class="fa fa-trash-o"></i></br>
												
												<?php } ?>
												<button style="float: right" type="submit" name="remve_txt" class="btn btn-danger" value="delete file">Delete</button>
												</form>
											</div>
										</form>
									</div>
									<div class="panel-footer">

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div><!-- /#page-wrapper -->
		</div>
	</div>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="css/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="css/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/startmin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="css/morris.css" rel="stylesheet">
	<link href="js/dashboard.js" rel="stylesheet">
			<!-- DataTables JavaScript -->
		<script src="js/dataTables/jquery.dataTables.min.js"></script>
		<script src="js/dataTables/dataTables.bootstrap.min.js"></script>
		

<script>
$("#checkAll").change(function () {
    $("input:checkbox").prop('checked', $(this).prop("checked"));
});
</script>
</body>
</html>
