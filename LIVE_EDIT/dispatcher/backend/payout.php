<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	if(!isset($_SESSION['ADM'])): header('location: login'); ENDIF;

	$_SESSION['frompayout'] = 1;
	$_SESSION['stat'] = 0;
	IF(isset($_SESSION['fromremitted'])):
		unset($_SESSION['fromremitted']);
	ELSEIF(isset($_SESSION['fromidcard'])):
		unset($_SESSION['fromidcard']);
	ELSEIF(isset($_SESSION['USERStransac'])):
		unset($_SESSION['USERStransac']);
	ELSEIF(isset($_SESSION['frompidcardinventory'])):
		unset($_SESSION['frompidcardinventory']);
	ELSEIF(isset($_SESSION['fromdrivertripreport'])):
		unset($_SESSION['fromdrivertripreport']);
	ENDIF;


	//CUT OFF SETTINGS				
	$terminal_settings = mysqli_fetch_array(mysqli_query($link, "SELECT cut_off FROM ".DB_PREFIX."terminal_settings"));
	$cutoff_time = $terminal_settings['cut_off'];
	$date = date('Y-m-d',strtotime("-1 days"));

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
                            <h4 class="page-header">Driver's Payout - Period of: <strong style="color:#0000FF"><?= date("F d, Y", strtotime($cutoff_start)); ?> </strong>To: <strong  style="color:#0000FF"><?= date("F d, Y", strtotime($cutoff_end)); ?></strong><span class="pull-right">Cutoff: <strong  style="color:#0000FF"><?= $daily_cutoff; ?></strong></span></h4>
							<h4 class="page-header"><?= isset($_SESSION['terminal_ID'])? $_SESSION['terminal_name'] : "All Terminal";?></h4>
							<?php IF(isset($_SESSION['updatedRecords'])): ?>
								<h4 class="page-header"><?=$_SESSION['updatedRecords'];?></h4>
							<?php ENDIF; ?>
					   </div><!-- /.col-lg-12 -->
						<div class="col-lg-12">
							
							<div class="panel-heading">
								<div class="pull-right"><a href="report/report" target="_blank"><img src="../myaccount/images/pdficon.png"></a></div>
								<div class="page-header pull-left">
									Select Cut off Date Range <?php IF(isset($_SESSION['error'])): echo $_SESSION['error']; ENDIF; unset($_SESSION['error']);?>
									
									<form role="form" name="cutoff" id="cutoff" method="post" action="setcutoffdate">
										<div class="form-group input-group" style="width:300px">
											<span class="input-group-addon"><li class="fa fa-calendar-minus-o"> From: </li></span><input type="date" class="form-control" name="selectdate1" id="selectdate1"><span class="input-group-addon"><li class="fa fa-calendar-plus-o"> To: </li></span><input type="date" class="form-control" name="selectdate2" id="selectdate2">
											<span class="input-group-btn">
												<button class="btn btn-default" type="submit" name="setcutdate"><i class="fa fa-arrow-right"></i></button>
											</span>
										</div>
									</form>
								</div>
								
								<div class="page-header" style="float:right">
									Select By Terminal
									<form action="select_terminal" method="post" enctype="multipart/form-data" id="TerminalForm">
										<div class="form-group input-group" style="width:300px">
											<?php $terminal_select_list = mysqli_query($link, "SELECT  terminal_ID, terminal_name FROM ".DB_PREFIX."terminal WHERE operational='1'"); ?>
											<select class="form-control" name="terminal_select" id="terminal_select">
												<option value="<?= ($_SESSION['terminal_ID'])? $_SESSION['terminal_ID'] : "all";?>"><?= ($_SESSION['terminal_ID'])? $_SESSION['terminal_name'] : "All Terminals";?></option>
										
												<option value="all">All Terminals</option>
												<?php WHILE($terminalselect = mysqli_fetch_array($terminal_select_list)){ ?>														
												<option value="<?= $terminalselect['terminal_ID']; ?>"><?= strtoupper($terminalselect['terminal_name']); ?></option>
												<?php } ?>
											</select>
											<span class="input-group-btn">
												<button class="btn btn-default" type="submit"  id="submitterminal" name="submitterminal" value="submitterminal" /><i class="fa fa-arrow-right"></i></button>
											</span>
										</div>
									</form>
								</div>
							</div>
						</div>

						<!-- /.panel-heading -->
						<div class="panel panel-default">
							<div class="panel-body">
								<!-- Tab panes -->
								<div class="tab-content">
									<div class="tab-pane fade in active" id="payout">
										<div class="panel-body">
											<div class="dataTable_wrapper">
												<form name="remit" id="remit" method="post" action="trigger/updateremit">
													<table class="table table-striped table-bordered table-hover" id="dataTables-payout">
														<thead>
															<tr>
																<th>Driver Name</th>
																<th>Bank Name</th>
																<th>Account No.</th>
																<th>No. of Trip(s)</th>
																<th>Total Incentive</th>
																<th>Remittance Code</th>
																<th></th>
															</tr>
														</thead>
														<tbody>
															
																<?php
																
																	$records = mysqli_query($link, "SELECT COUNT(A.ID) AS totaltrip, A.driver_ID, A.terminal_ID, A.trip_ID, B.trip_ID, SUM(B.total_fare) AS totalfare, SUM(B.service_fee) AS totalfee FROM ".DB_PREFIX."vehicle_trip_history A, ".DB_PREFIX."driver_account B WHERE {$conditionset} B.remitted=0 AND B.trip_ID=A.trip_ID AND A.time_date BETWEEN '{$cutoff_start}' AND '{$cutoff_end}' GROUP BY A.driver_ID");
																
																	WHILE($drivers = mysqli_fetch_array($records)){
																		$user_ID = $drivers['driver_ID'];
																		$total_incentive = $drivers['totalfare'];
																		$total_service_fee = $drivers['totalfee'];
																		$overall_total = 0;
																		
																		$trips = $drivers['totaltrip'];
																		
																		$driver = mysqli_fetch_array(mysqli_query($link, "SELECT fname, mname, lname, suffix, user_ID FROM ".DB_PREFIX."users WHERE user_ID='{$user_ID}' AND account_status=0"));
																		$fullname = $driver['fname']." ".$driver['lname'].(($driver['suffix'])? ", ".$driver['suffix'] : "");
																			
																		$bank = mysqli_fetch_array(mysqli_query($link, "SELECT a.bank_ID, a.account_no, b.name, b.Abbreviation FROM ".DB_PREFIX."bank_accounts a, ".DB_PREFIX."banks b WHERE a.user_ID = '{$user_ID}' AND a.status=1 AND a.bank_ID=b.ID"));

																		$overall_total = $total_incentive - $total_service_fee;
																		?>
																			<tr class="gradeA">
																		<td><a href="gen_id_bridge?memberID=<?= $user_ID; ?>" data-toggle="tooltip" title="View Profile"><?= strtoupper($fullname); ?></a></td>
																		<td><span data-toggle="tooltip" title="<?= ($bank['bank_ID'])? $bank['name'] : '-N/A-'; ?>"><?= ($bank['bank_ID'])? $bank['Abbreviation'] : "<span align='center'>-N/A-</span>"; ?></span></h6></td>
																		<td><?= ($bank['account_no'])? $bank['account_no'] : "-N/A-"; ?></td>
																		<td><?= $trips; ?></td>
																		<td align="right"><span style="float:left;color:#0000FF;">₱</span> <?= number_format($overall_total,2); ?></td>
																		<td>
																			<div class="form-group input-group">
																				
																				<input type="hidden" class="form-control" name="ID[]" id="ID[]" value="<?= $user_ID; ?>" style="width:90px">
																				<input type="text" class="form-control" name="Rcode[]" id="Rcode[]" value="" style="width:150px">
																				<span class="input-group-btn"></span>

																			</div>
																		</td>
																		<td align="center"><a href="gen_id_bridge?transaction_details=<?=$driver['user_ID'];?>&cos=<?=strtotime($cutoff_start);?>&cof=<?=strtotime($cutoff_end);?>"><span data-toggle="tooltip" title="View Details"><i class="fa fa-eye"></span></i></a>
																	</tr>
																<?php } ?>
																
														</tbody>
													</table>	
													<span class="pull-right"><button class="btn btn-default" type="submit" name="updateremit" id="updateremit"><i data-toggle="tooltip" title="Remitted" class="fa fa-arrow-right"></i>Update Remittance</button></span>
												</form>										
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
