<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	if(!isset($_SESSION['ADM'])): header('location: login'); ENDIF;
	
	$suspend_ID = $_SESSION['suspendID'];

		$userID = mysqli_fetch_array(mysqli_query($link, "SELECT fname, mname, lname, suffix, user_ID FROM ".DB_PREFIX."users WHERE user_ID='{$suspend_ID}'"));
		$fullname = $userID['fname']." ".$userID['lname'].(($userID['suffix'])? ", ".$userID['suffix'] : "");
		
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
                            <h3 class="page-header">User Name: <strong style="color:#0000FF"><?= strtoupper($fullname);?></strong> <span style="float:right">User ID: <strong  style="color:#0000FF"><?= $suspend_ID ?></strong></span></h3>
					   </div><!-- /.col-lg-12 -->
					   
						<div style="float:left"><a href="dashboard"><h3><i class="fa fa-toggle-left fa-fw"></i>Back</a></h3></div>
						<div style="float:right"><a href="report/suspended_report" target="_blank"><img src="../myaccount/images/pdficon.png"></a></div>
						
						<!-- /.panel-heading -->
						<div class="panel panel-default">
							<div class="panel-body">
								<!-- Tab panes -->
								<div class="tab-content">
									<div class="tab-pane fade in active" id="suspend">
										<div class="panel-body">
											<div class="dataTable_wrapper">
												<table class="table table-striped table-bordered table-hover" id="dataTables-suspend">
													<thead>
														<tr>														
															<th>Date from</th>
															<th>Date To</th>
															<th style="WORD-BREAK:BREAK-ALL">Remarks</th>
														</tr>
													</thead>
													<tbody>
													<?php

														$checksuspend_query = mysqli_query($link, "SELECT A.status, A.date_from, A.date_to, A.remarks, B.username, B.fname, B.mname, B.lname, B.suffix, B.photo, B.signature, B.regdate, B.city, B.user_ID, B.gen_card FROM ".DB_PREFIX."driver_status A, ".DB_PREFIX."users B WHERE  B.user_ID=A.driver_ID AND A.status!=2");
														WHILE($suspend = mysqli_fetch_array($checksuspend_query)){
															$date_from = $suspend['date_from'];
															$date_to = $suspend['date_to'];
															$remarks = $suspend['remarks'];	
													?>
														<tr class="gradeA">
															<td><?= $date_from; ?></td>															
															<td align="right"> <?= $date_to; ?></td>
															<td align="right"> <?= $remarks; ?></td>
														</tr>
													<?php
														}
													?>
													</tbody>
												</table>											
											</div>
										</div><!-- /.panel-body -->
									</div>
								</div>
							</div><!-- /.panel-body -->
						</div><!-- /.panel --> 
					</div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div><!-- /#page-wrapper -->
		</div>
     </div>
        <!-- /#wrapper -->

        <!-- jQuery -->
        <script src="js/jquery.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="js/bootstrap.min.js"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="js/metisMenu.min.js"></script>
		<!-- DataTables JavaScript -->
		<script src="js/dataTables/jquery.dataTables.min.js"></script>
		<script src="js/dataTables/dataTables.bootstrap.min.js"></script>

		<!-- Page-Level Demo Scripts - Tables - Use for reference -->
		<script src="js/dashboard.js"></script>
        <!-- Custom Theme JavaScript -->
        <script src="js/startmin.js"></script>
		
		<!-- DataTables JavaScript -->
		<script src="js/dataTables/jquery.dataTables.min.js"></script>
		<script src="js/dataTables/dataTables.bootstrap.min.js"></script>
		
		<script>
			$(document).ready(function() {
				//TABLE
                $('#dataTables-payout').DataTable({ responsive: true });
				
				//TOOLTIP
				$('[data-toggle="tooltip"]').tooltip(); 
            });
		</script>
    </body>
</html>
