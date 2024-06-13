<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	if(!isset($_SESSION['ADM'])): header('location: login'); ENDIF;

	//CUT OFF SETTINGS				
	$terminal_settings = mysqli_fetch_array(mysqli_query($link, "SELECT cut_off FROM ".DB_PREFIX."terminal_settings"));
	$cutoff_time = $terminal_settings['cut_off'];
	$date = date('Y-m-d',strtotime("-1 days"));
	
	$_SESSION['fromdrivertripreport'] = 1;
	IF(isset($_SESSION['fromremitted'])):
		unset($_SESSION['fromremitted']);
	ELSEIF(isset($_SESSION['fromidcard'])):
		unset($_SESSION['fromidcard']);
	ELSEIF(isset($_SESSION['USERStransac'])):
		unset($_SESSION['USERStransac']);
	ELSEIF(isset($_SESSION['frompidcardinventory'])):
		unset($_SESSION['frompidcardinventory']);
	ELSEIF(isset($_SESSION['frompayout'])):
		unset($_SESSION['frompayout']);
	ENDIF;

	$driver_ID = $_SESSION['driverID'];
	$stat = $_SESSION['stat'];

	IF(!isset($_SESSION['selecteddate'])):
		$cutoff_start = $date." ".$cutoff_time;
		$cutoff_end = date('Y-m-d')." ".$cutoff_time;
	ELSE:
		$cutoff_start = $_SESSION['cutstart']." ".$cutoff_time;
		$cutoff_end = $_SESSION['cutend']." ".$cutoff_time;
	ENDIF;

	$daily_cutoff = date('g:i A', strtotime($cutoff_time));
	
	IF($_SESSION['terminal_ID'] != ""):
		IF($_SESSION['terminal_ID'] == "all"):
			$terminal_ID = "all";
			$conditionset = "";
		ELSE:
			$terminal_ID = $_SESSION['terminal_ID'];
			$conditionset = "A.terminal_ID='{$terminal_ID}' AND ";
		ENDIF;
	ELSE:
		$conditionset = "";
	ENDIF;
	
		$driver = mysqli_fetch_array(mysqli_query($link, "SELECT fname, mname, lname, suffix, user_ID FROM ".DB_PREFIX."users WHERE user_ID='{$driver_ID}' AND account_status=0"));
		$fullname = $driver['fname']." ".$driver['lname'].(($driver['suffix'])? ", ".$driver['suffix'] : "");
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
                            <h4 class="page-header"><?=$stat;?>  Terminal Name: <strong style="color:#0000FF"><?= isset($_SESSION['terminal_ID'])? $_SESSION['terminal_name'] : "All Terminal";?></strong> <span style="float:right">Cutoff: <strong  style="color:#0000FF"><?= $daily_cutoff; ?></strong></span></h4>
					   </div><!-- /.col-lg-12 -->
					   <div class="col-lg-12">
                            <h4 class="page-header">Driver Name: <strong style="color:#0000FF"><?= strtoupper($fullname);?></strong> <span class="pull-right">Driver ID: <strong  style="color:#0000FF"><?= $driver_ID; ?></strong></span></h4>
					   </div><!-- /.col-lg-12 -->
					   
						<div style="float:left"><a href="<?=($stat==1)? 'remitted_payout' : 'payout'; ?>"><h3><i class="fa fa-toggle-left fa-fw"></i>Back</a></h3></div>
						<div style="float:right"><a href="report/report" target="_blank"><img src="../myaccount/images/pdficon.png"></a></div>
						
						<!-- /.panel-heading -->
						<div class="panel panel-default">
							<div class="panel-body">
								<!-- Tab panes -->
								<div class="tab-content">
									<div class="tab-pane fade in active" id="payout">
										<div class="panel-body">
											<div class="dataTable_wrapper">
												<table class="table table-striped table-bordered table-hover" id="dataTables-payout">
													<thead>
														<tr>
															<th>Date</th>
															<th>Trip ID</th>
															<th>No. of Passenger(s)</th>
															<th>Total Fare</th>
															<th>Service Fee</th>
														</tr>
													</thead>
													<tbody>
													<?php
														$overall_total = 0;
														$overall_fare = 0;
														$overall_service_fee = 0;

														$records = mysqli_query($link, "SELECT A.trip_ID, A.time_date, B.total_fare, B.service_fee, A.passenger FROM ".DB_PREFIX."vehicle_trip_history A, ".DB_PREFIX."driver_account B WHERE {$conditionset} B.remitted='{$stat}' AND A.driver_ID='{$driver_ID}' AND A.trip_ID=B.trip_ID AND `time_date` BETWEEN '{$cutoff_start}' AND '{$cutoff_end}'");
														WHILE($drivers = mysqli_fetch_array($records)){
															$trip_ID = $drivers['trip_ID'];
															$date = $drivers['time_date'];
															$fare = $drivers['total_fare'];
															$service_fee = $drivers['service_fee'];
															$passenger = $drivers['passenger'];
															$overall_fare += $fare;
															$overall_service_fee += $service_fee;
													?>
														<tr class="gradeA">
															<td><?=$date;?></td>
															<td><?=strtoupper($trip_ID);?></a></td>
															<td align="right"> <?= $passenger; ?></td>
															<td align="right"><span style="float:left;color:#0000FF;">₱</span> <?=number_format($fare, 2);?></td>
															<td align="right"><span style="float:left;color:#0000FF;">₱</span> <?=number_format($service_fee, 2);?></td>
														</tr>
													<?php
														}
														
														$overall_total = $overall_fare - $overall_service_fee;
													?>
													</tbody>
												</table>											
											</div>
										</div><!-- /.panel-body -->
									</div>
								</div>
							</div><!-- /.panel-body -->
						</div><!-- /.panel --> 
						<div class="col-lg-12">
                            <h3 class="page-header">Total Incentives: <strong style="color:#0000FF"><span style="color:#0000FF;">₱ <?=number_format($overall_total, 2);?></span></strong></h3>
					   </div><!-- /.col-lg-12 -->
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
		<?php
		
		//	IF(isset($_SESSION['cutstart'])): unset($_SESSION['cutstart']); ENDIF;
		//	IF(isset($_SESSION['cutend'])): unset($_SESSION['cutend']); ENDIF;
		?>
    </body>
</html>
