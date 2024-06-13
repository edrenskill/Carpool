<?php
include '../settings/connect.php';
if (session_id() == '') { page_protect(); } // START SESSIONS

// Get Details
$myname = mysqli_fetch_array(mysqli_query($link, "SELECT CONCAT(fname, ' ',lname) AS fullname, photo, user_ID FROM " . DB_PREFIX . "users WHERE ID = " . $_SESSION['user_id'] . ""));
IF ($myname['photo'] == "") {
    $avatar = "avatar.jpg";
} ELSE {
    $avatar = "members/" . $_SESSION['user_id'] . "/" . $myname['photo'];
}

$act_ID = $_SESSION['act_ID'];

// Get Account
IF (Registered()):
    $mybalance = mysqli_fetch_array(mysqli_query($link, "SELECT ending_balance, terminal_ID, transaction_code, debit, credit, transaction_date, transaction_time FROM " . DB_PREFIX . "account WHERE user_ID = '" . $act_ID . "' AND `primary` = 1"));
    $prevbalance = mysqli_fetch_array(mysqli_query($link, "SELECT ending_balance, terminal_ID, transaction_code, debit, credit, transaction_date, transaction_time FROM " . DB_PREFIX . "account WHERE user_ID = '" . $act_ID . "' AND `primary` = 0 ORDER BY ID DESC LIMIT 0, 1"));
    $tcode = $mybalance['transaction_code'];
    $transcodes = mysqli_fetch_array(mysqli_query($link, "SELECT description FROM " . DB_PREFIX . "transactioncode WHERE transaction_code = '" . $tcode . "'"));
    $tid = $mybalance['terminal_ID'];
    $prevdate = date_create($prevbalance['transaction_date'] . " " . $prevbalance['transaction_time']);
    $tdate = date_create($mybalance['transaction_date'] . " " . $mybalance['transaction_time']);

    $prev_transaction = mysqli_fetch_array(mysqli_query($link, "SELECT route_origin, route_destination, terminal_name FROM " . DB_PREFIX . "terminal WHERE terminal_ID = '" . $tid . "'"));

ELSEIF (Dispatcher()):
    $terminal_ID = $_SESSION['terminal_ID'];
    $loadwallet = mysqli_fetch_array(mysqli_query($link, "SELECT ending_balance, terminal_ID, transaction_code, debit, credit, transaction_date, transaction_time FROM " . DB_PREFIX . "terminalload_wallet WHERE terminal_ID = '" . $terminal_ID . "' AND `primary` = 1"));
    $prevloadwallet = mysqli_fetch_array(mysqli_query($link, "SELECT ending_balance, terminal_ID, transaction_code, debit, credit, transaction_date, transaction_time FROM " . DB_PREFIX . "terminalload_wallet WHERE terminal_ID = '" . $terminal_ID . "' AND `primary` = 0 ORDER BY ID DESC LIMIT 0,1"));
    $tcode = $loadwallet['transaction_code'];
    $transcodes = mysqli_fetch_array(mysqli_query($link, "SELECT description FROM " . DB_PREFIX . "transactioncode WHERE transaction_code = '" . $tcode . "'"));
    $tdate = date_create($loadwallet['transaction_date'] . " " . $loadwallet['transaction_time']);
    $loadwalletdate = date_create($loadwallet['transaction_date'] . " " . $loadwallet['transaction_time']);
    $prevloadwalletdate = date_create($prevloadwallet['transaction_date'] . " " . $prevloadwallet['transaction_time']);
ENDIF;


	$_SESSION['frommyaccount'] = 1;
	IF(isset($_SESSION['drivertransac'])):
		unset($_SESSION['drivertransac']);
	ENDIF;


//CUT OFF SETTINGS				
$terminal_settings = mysqli_fetch_array(mysqli_query($link, "SELECT cut_off FROM " . DB_PREFIX . "terminal_settings"));
$cutoff_time = $terminal_settings['cut_off'];
$date = date('Y-m-d', strtotime("-1 days"));

$driver_ID = $_SESSION['act_ID'];

IF (!isset($_SESSION['selecteddate'])):
    $cutoff_start = $date . " " . $cutoff_time;
    $cutoff_end = date('Y-m-d') . " " . $cutoff_time;
ELSE:
    $cutoff_start = $_SESSION['cutstart'] . " " . $cutoff_time;
    $cutoff_end = $_SESSION['cutend'] . " " . $cutoff_time;
ENDIF;

$daily_cutoff = date('g:i A', strtotime($cutoff_time));

IF ($_SESSION['terminal_ID'] != ""):
    IF ($_SESSION['terminal_ID'] == "all"):
        $terminal_ID = "all";
        $conditionset = "";
    ELSE:
        $terminal_ID = $_SESSION['terminal_ID'];
        $conditionset = "A.terminal_ID='{$terminal_ID}' AND ";
    ENDIF;
ELSE:
    $conditionset = "";
ENDIF;

$driver = mysqli_fetch_array(mysqli_query($link, "SELECT fname, mname, lname, suffix, user_ID FROM " . DB_PREFIX . "users WHERE user_ID='{$driver_ID}' AND account_status=0"));
$fullname = $driver['fname'] . " " . $driver['lname'] . (($driver['suffix']) ? ", " . $driver['suffix'] : "");

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include_once("includes/header.php"); ?>
    </head>
    <body>

<div id="wrapper">

<?php include_once("includes/nav.php"); ?>

 <!-- Page Content -->
	<div id="page-wrapper">
		<div class="container-fluid">
			<div class="row">
			  <div class="col-lg-12">
				<h1 class="page-header">Account Details</h1>
			  </div> <!-- /.col-lg-12 -->
			</div>

			<div class="row">
			    <div class="col-lg-12">
					<div class="panel panel-default">
					    <div class="panel-heading">Transactions</div>
								<!-- /.panel-heading -->
						<div class="panel-body">

						<?php IF (Registered()) : ?>
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th>Detail</th>
										<th>Date</th>
										<th>Amount</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><h4>Current Balance</h4></td>
										<td><h4><?= date_format($tdate, 'F j, Y l g:ia'); ?></h4></td>
										<td><h4>PHP<?= number_format($mybalance['ending_balance'], 2, ".", ","); ?></h4></td>
									</tr>
									<tr>
										<td>Previous Balance:</td>
										<td><?= date_format($prevdate, 'F j, Y l g:ia'); ?></td>
										<td>PHP<?= number_format($prevbalance['ending_balance'], 2, ".", ","); ?></td>
									</tr>
									<tr>
										<td colspan="3"><h4>Last Transaction: </h4></td>
									</tr>
									<?php IF (!$mybalance) { ?>
									</tr>
								</tbody>
							</table>
							<?php } ELSEIF ($mybalance['credit'] != 0) { ?>
										<td><?=$transcodes['description'];?></td>
										<td><?=date_format($tdate, 'F j, Y l g:ia');?></td>
										<td>PHP<?=number_format($mybalance['credit'], 2, ".", ",");?></td>
									</tr>
								</tbody>
							</table>
							<br />
							<?php } ELSEIF ($mybalance['debit'] != 0) { ?>
										<td><?=$transcodes['description'];?></td>
										<td><?=date_format($tdate, 'F j, Y l g:ia');?></td>
										<td>PHP<?=number_format($mybalance['debit'], 2, ".", ",");?></td>
									</tr>
									<tr>
										<td colspan='3'>Origin and Destination: <b><?=$prev_transaction['route_origin'] . "-" . $prev_transaction['route_destination'];?></b></td>
									</tr>
								</tbody>
							</table>
						<?php } ?>
						</div>
						<a href="history" class="btn btn-primary btn-sm">View full history</a>
						<?php 
						ENDIF; // close member level  
								
						IF (Dispatcher()) : ?>
						<div class="table-responsive">
							<div class="panel-body">
								<h4>Latest Transaction</h4>
								<table style="width: 100%">
									<thead>
										<tr>
											<th><h4>Detail</h4></th>
											<th><h4>Date</h4></th>
											<th><h4>Amount</h4></th>
										</tr>
									</thead>
									<?php 
									IF (isset($prevloadwallet)): ?>
									<tbody>
										<tr>
											<td>Previous Load Wallet</td>
											<td><?= date_format($prevloadwalletdate, 'F j, Y l g:ia'); ?></td>
											<td>PHP<?= number_format($prevloadwallet['ending_balance'], 2, ".", ","); ?></td>
										</tr>
									<?php
									ENDIF;
									IF ($loadwallet['credit'] != 0) { ?>
										<tr>
											<td><?=$transcodes['description'];?></td>
											<td><?=date_format($tdate, 'F j, Y l g:ia');?></td>
											<td><span style=color:#00ff00;>+Pph<?=number_format($loadwallet['credit'], 2, ".", ",");?></span></td>
										</tr>
									<?php } ELSEIF ($loadwallet['debit'] != 0) { ?>
										<tr>
											<td><?=$transcodes['description'];?></td>
											<td><?=date_format($tdate, 'F j, Y l g:ia');?></td>
											<td><span style='color:#ff0000;'>-Php<?=number_format($loadwallet['debit'], 2, ".", ",");?></span></td>
										</tr>
									<?php 
										}
									IF (isset($loadwallet)): ?>
										<tr>
											<td><h4>Current Load Wallet</h4></td>
											<td><h4><?= date_format($loadwalletdate, 'F j, Y l g:ia'); ?></h4></td>
											<td><h4>PHP<?= number_format($loadwallet['ending_balance'], 2, ".", ","); ?></h4></td>
										</tr>
									<?php ENDIF; ?>
									</tbody>
								</table>

								<form method="post" action="transaction_history" name="trans_date<?= $time; ?>" id="trans_date<?= $time; ?>">
									<?php IF (!isset($terminal_ID)): ?>
									<input type="button" disabled value="No Transaction" class="special" id="HistoryDate" name="HistoryDate" />
									<?php ELSE: ?>
									<input type="submit" value="View all Transactions" class="special" id="HistoryDate" name="HistoryDate" />
									<?php ENDIF; ?>
								</form>
							</div>	
						</div>	
						<?php ENDIF; // close dispatcher level 

						 // driver
						IF (Driver() || Owner_Driver()) : ?>	
						<div class="col-lg-12">
						<!--<div id="remit" class="col-lg-10">	-->								
							<div class="container-fluid">
								<div class="row">
								<span style="float:right">Cutoff: <strong  style="color:#0000FF"><?= $daily_cutoff; ?></strong></span>
									<div class="col-lg-12" >
										<h3 class="page-header">Driver's Payout - Period of: <strong style="color:#0000FF"><?= date("F d, Y", strtotime($cutoff_start)); ?> </strong>To: <strong  style="color:#0000FF"><?= date("F d, Y", strtotime($cutoff_end)); ?></strong></h3>
										<h4 class="page-header"><strong style="color:#0000FF"><?= isset($_SESSION['terminal_ID']) ? $_SESSION['terminal_name'] : "All Terminal"; ?></strong></h4>
									</div><!-- /.col-lg-12 -->
									<div class="col-lg-12" style="margin: -61px 0 0 0;">
										<div class="panel-heading">
											<div class="page-header" style="float:left">
												Select Date Range<?php
												IF (isset($_SESSION['error'])): echo $_SESSION['error'];
												ENDIF;
												unset($_SESSION['error']);
												?>

												<form role="form" name="cutoff" id="cutoff" method="post" action="setcutoffdate2">
													<div class="form-group input-group" style="width:300px">
														<span class="input-group-addon"><li class="fa fa-calendar-minus-o"> From: </li></span><input type="date" class="form-control" name="selectdate1" id="selectdate1"><span class="input-group-addon"><li class="fa fa-calendar-plus-o"> To: </li></span><input type="date" class="form-control" name="selectdate2" id="selectdate2">
														<span class="input-group-btn"><button class="btn btn-default" type="submit" name="setcutdate"><i class="fa fa-arrow-right"></i></button></span>
													</div>
												</form>
											</div>

											<div class="page-header" style="float:right">
												Select By Terminal
												<form action="select_terminal" method="post" enctype="multipart/form-data" id="TerminalForm">
													<div class="form-group input-group" style="width:300px">
														<?php $terminal_select_list = mysqli_query($link, "SELECT  terminal_ID, terminal_name FROM " . DB_PREFIX . "terminal WHERE operational='1'"); ?>
														<select class="form-control" name="terminal_select" id="terminal_select">
															<option value="<?= ($_SESSION['terminal_ID']) ? $_SESSION['terminal_ID'] : "all"; ?>"><?= ($_SESSION['terminal_ID']) ? $_SESSION['terminal_name'] : "All Terminals"; ?></option>
															
															<option value="all">All Terminals</option>
															<?php WHILE ($terminalselect = mysqli_fetch_array($terminal_select_list)) { ?>														
															<option value="<?= $terminalselect['terminal_ID']; ?>"><?= strtoupper($terminalselect['terminal_name']); ?></option>
															<?php } ?>
														</select>
														<span class="input-group-btn">
															<button class="btn btn-default" type="submit" href="#"  id="submitterminal" name="submitterminal" value="submitterminal" /><i class="fa fa-arrow-right"></i></button>
														</span>
													</div>
												</form>
											</div>
										</div>
									</div>
									<?php
									$driverID1 = $terminal['driver_ID1'];
									$driverID2 = $terminal['driver_ID2'];	
									$driver = mysqli_fetch_array(mysqli_query($link, "SELECT CONCAT (fname,' ',lname) AS fullname, user_ID, account_status FROM ".DB_PREFIX."users WHERE (user_ID='{$driverID1}' OR user_ID='{$driverID2}')"));								
									
									$DRID = $driver['user_ID'];
									$Dstatus = $driver['account_status'];
									
									IF(strpos($DRID, 'TEMP') == true):
										$status = "<span style='color:#ffa500'>For Activation</span>";
									ELSEIF(strpos($DRID, 'TEMP') == false):
										IF($Dstatus == 0): 
											$status = "<span style='color:#0000ff'>Active</span>";
										ELSEIF($Dstatus == 1):
											$status = "<span style='color:#FF0000'>Suspended</span>";
										ELSEIF($Dstatus == 2):
											$status = "<span style='color:#FF0000'>Banned</span>";
										ENDIF;
									ENDIF;
									
									?>

									<div class="col-lg-10" style="margin: -57px 0 0 0;">
										<h3 class="page-header"><strong>Driver ID:<span style="color:#0000FF;"><?= $driver_ID; ?></span></strong> <strong class="pull-right"> Status:<span><?= $status; ?></span></strong></h3>
									</div><!-- /.col-lg-12 -->


									<div class="panel-body col-lg-12" >
										<!-- Nav tabs -->
										<ul class="nav nav-tabs">
											<li class="active"><a href="#home" data-toggle="tab">Transaction Summary</a></li>
											<li><a href="#profile" data-toggle="tab">Unclaimed Balance</a></li>
											<li><a href="#messages" data-toggle="tab">Payment recieved</a></li>
										</ul>

										<!-- Tab panes -->
										<div class="tab-content">
											<div class="col-lg-12 tab-pane fade in active" id="home">
												<div class="panel-body">
													<div class="dataTable_wrapper">
														<table class="table table-bordered table-striped" id="dataTables-myaccount">
															<thead>
																<tr>
																	<th>No. of Trip(s)</th>
																	<th>Total Incentive</th>
																	<th>Remittance Code</th>
																	<th></th>
																</tr>
															</thead>
															<tbody>
																<?php
																$records = mysqli_query($link, "SELECT COUNT(A.ID) AS totaltrip, A.driver_ID, A.terminal_ID, A.trip_ID, B.trip_ID, B.bank_ref_no, SUM(B.total_fare) AS totalfare, SUM(B.service_fee) AS totalfee FROM ".DB_PREFIX."vehicle_trip_history A, ".DB_PREFIX."driver_account B WHERE {$conditionset} B.driver_ID='{$act_ID}' AND B.trip_ID=A.trip_ID AND A.time_date BETWEEN '{$cutoff_start}' AND '{$cutoff_end}' GROUP BY B.bank_ref_no");
																
																WHILE ($drivers = mysqli_fetch_array($records)) {
																		$total_incentive = $drivers['totalfare'];
																		$total_service_fee = $drivers['totalfee'];
																		$overall_total = 0;
																		$trips = $drivers['totaltrip'];
																		$ref_no = $drivers['bank_ref_no'];
																		$overall_total = $total_incentive - $total_service_fee;

																?>
																<tr class="gradeA">
																		<td align="right"><?= $trips; ?></td>
																		<td align="right"><span style="float:left;color:#0000FF;">₱</span> <?= number_format($overall_total,2); ?></td>
																		<td><?= $ref_no;?>
																		<td align="center"><a href="gen_id_bridge?transaction_details=<?=$ref_no;?>&cos=<?=strtotime($cutoff_start);?>&cof=<?=strtotime($cutoff_end);?>"><span data-toggle="tooltip" title="View Details"><i class="fa fa-eye"></span></i></a></td>

																</tr>
																<?php
																}
																?>
															</tbody>
														</table>											
													</div> 
												</div>
											</div> 
											<!-- end of Home -->
															
											<div class=" col-lg-12 tab-pane fade" id="profile">

												<div class="panel-body">
													<div class="dataTable_wrapper">
														<table class="table table-bordered table-striped" id="dataTables-myaccounts">
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
																	
																IF (!isset($_SESSION['selecteddate'])):
																	$records = mysqli_query($link, "SELECT A.trip_ID, A.time_date, B.total_fare, B.service_fee, A.passenger FROM ".DB_PREFIX."vehicle_trip_history A, ".DB_PREFIX."driver_account B WHERE {$conditionset} A.driver_ID='{$driver_ID}' AND A.trip_ID=B.trip_ID AND B.remitted=0");
																	ELSE:
																	$records = mysqli_query($link, "SELECT A.trip_ID, A.time_date, B.total_fare, B.service_fee, A.passenger FROM ".DB_PREFIX."vehicle_trip_history A,".DB_PREFIX."driver_account B WHERE {$conditionset} A.driver_ID='{$driver_ID}' AND A.trip_ID=B.trip_ID AND `time_date` BETWEEN '{$cutoff_start}' AND '{$cutoff_end}' AND B.remitted=0");
																ENDIF;
																	WHILE ($drivers = mysqli_fetch_array($records)) {
																		$trip_ID = $drivers['trip_ID'];
																		$date = $drivers['time_date'];
																		$fare = $drivers['total_fare'];
																		$service_fee = $drivers['service_fee'];
																		$passenger = $drivers['passenger'];
																		$overall_fare += $fare;
																		$overall_service_fee += $service_fee;
															?>
																<tr class="gradeA">
																	<td><?= $date; ?></td>
																	<td><?= strtoupper($trip_ID); ?></a></td>
																	<td align="right"> <?= $passenger; ?></td>
																	<td align="right"><span style="float:left;color:#0000FF;">₱</span> <?= number_format($fare, 2); ?></td>
																	<td align="right"><span style="float:left;color:#0000FF;">₱</span> <?= number_format($service_fee, 2); ?></td>
																</tr>
															<?php
																}
															$overall_total = $overall_fare - $overall_service_fee;
															?>
															</tbody>
														</table>											
													</div>
												</div>
												<div class="col-lg-12">
													<h3 class="page-header">Total Incentives: <strong style="color:#0000FF"><span style="color:#0000FF;">₱ <?= number_format($overall_total, 2); ?></span></strong></h3>
												</div>
											</div>
											
											<div class="col-lg-12 tab-pane fade" id="messages">
												<div class="panel-body">
													<div class="dataTable_wrapper">
														<table class="table table-bordered table-striped" id="dataTables-accounts">
															<thead>
																<tr>
																	<th>No. of Trip(s)</th>
																	<th>Total Incentive</th>
																	<th>Remittance Code</th>
																	<th></th>
																</tr>
															</thead>
															<tbody>
															<?php
																$overall_total = 0;
																$overall_fare = 0;
																$overall_service_fee = 0;

															IF (!isset($_SESSION['selecteddate'])):
																$records = mysqli_query($link, "SELECT COUNT(A.ID) AS totaltrip, A.driver_ID, A.terminal_ID, A.trip_ID, B.trip_ID, B.bank_ref_no, SUM(B.total_fare) AS totalfare, SUM(B.service_fee) AS totalfee FROM ".DB_PREFIX."vehicle_trip_history A, ".DB_PREFIX."driver_account B WHERE {$conditionset} B.driver_ID='{$act_ID}' AND B.trip_ID=A.trip_ID AND A.time_date BETWEEN '{$cutoff_start}' AND '{$cutoff_end}' AND B.remitted=1 GROUP BY B.bank_ref_no");
																ELSE:
																$records = mysqli_query($link, "SELECT COUNT(A.ID) AS totaltrip, A.driver_ID, A.terminal_ID, A.trip_ID, B.trip_ID, B.bank_ref_no, SUM(B.total_fare) AS totalfare, SUM(B.service_fee) AS totalfee FROM ".DB_PREFIX."vehicle_trip_history A, ".DB_PREFIX."driver_account B WHERE {$conditionset} B.driver_ID='{$act_ID}' AND B.trip_ID=A.trip_ID AND A.time_date BETWEEN '{$cutoff_start}' AND '{$cutoff_end}' AND B.remitted=1 GROUP BY B.bank_ref_no");
															ENDIF;
																WHILE ($drivers = mysqli_fetch_array($records)) {
																		$total_incentive = $drivers['totalfare'];
																		$total_service_fee = $drivers['totalfee'];
																		$overall_total = 0;
																		$trips = $drivers['totaltrip'];
																		$ref_no = $drivers['bank_ref_no'];
																		$overall_total = $total_incentive - $total_service_fee;
															?>
																<tr class="gradeA">
																		<td align="right"><?= $trips; ?></td>
																		<td align="right"><span style="float:left;color:#0000FF;">₱</span> <?= number_format($overall_total,2); ?></td>
																		<td><?= $ref_no;?>
																		<td align="center"><a href="gen_id_bridge?transaction_details=<?=$ref_no;?>&cos=<?=strtotime($cutoff_start);?>&cof=<?=strtotime($cutoff_end);?>"><span data-toggle="tooltip" title="View Details"><i class="fa fa-eye"></span></i></a></td>

																</tr>
															<?php
															}
															?>
															</tbody>
														</table>											
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php ENDIF; // close Driver level   
						IF (Operator()): ?>	
							<div class="col-lg-12">
							<!--<div id="remit" class="col-lg-10">	-->								
								<div class="container-fluid">
									<div class="row">
												
										<div class="col-lg-10">
											<h3 class="page-header"><strong>Operator ID:<span style="color:#0000FF;"><?= $driver_ID; ?> <i><?= strtoupper($vowner['fullname']); ?></i></span></strong></h3>
										</div><!-- /.col-lg-12 -->

										<div class="panel-body col-lg-12" >
														<!-- Nav tabs -->
											<ul class="nav nav-tabs">
												<li class="active"><a href="#home" data-toggle="tab">Transaction Summary</a>
												</li>
											</ul>
										<!-- Tab panes -->
											<div class="tab-content">
												<div class="col-lg-12 tab-pane fade in active" id="home">
													<div class="panel-body">
														<div class="dataTable_wrapper">
															<table class="table table-bordered table-striped" id="dataTables-myaccount">
																<thead>
																	<tr>
																		<th>Driver Name</th>
																		<th>Driver ID</th>
																		<th>Plate Number</th>
																		<th>Capacity</th>
																		<th>Status</th>
																		<th>Details</th>
																	</tr>
																</thead>
																<tbody>
																<?php
																	$terminal_query = mysqli_query($link, "SELECT * FROM ".DB_PREFIX."vehicles WHERE owner_ID='{$driver_ID}'");
																	
																	WHILE($terminal = mysqli_fetch_array($terminal_query)){
																		
																		$driverID = "'".$terminal['driver_ID1']."', '".$terminal['driver_ID2']."'";

																		$getdriver = mysqli_query($link, "SELECT CONCAT (fname,' ',lname) AS fullname, user_ID, account_status FROM ".DB_PREFIX."users WHERE user_ID IN ($driverID)");								
																		WHILE ($driver = mysqli_fetch_array($getdriver)){
																		$currentID = $driver['user_ID'];
																		$Dstatus = $driver['account_status'];

																		IF(strpos($currentID, 'TEMP') !== false):
																			$status = "<span class='text-warning'>For Activation</span>";
																		ELSE:
																			IF($Dstatus == 0):
																				$status = "<span class='text-success'>Active</span>";
																			ELSEIF($Dstatus == 1):
																				$status = "<span class='text-danger'>Suspended</span>";
																			ELSEIF($Dstatus == 2):
																				$status = "<span class='text-muted'>Banned</span>";
																			ENDIF;
																		ENDIF;
																?>
																	<tr class="gradeA">
																		<td><?= strtoupper($driver['fullname']); ?></td>
																		<td><?= $driver['user_ID']; ?></td>
																		<td><?= $terminal['plate_number']; ?></td>
																		<td class="center"><?= $terminal['capacity']; ?></td>
																		<td class="center"><?= $status;?></td>
																		<td class="center"><a href="gen_id_bridge?driver_ID=<?=$currentID;?>"><i class="fa fa-arrow-right pull-right"></i></a></td>																			
																	</tr>
																	<?php } }?>
	
																</tbody>
															</table>											
														</div>
													</div>
												</div> 
										<!-- end of Home -->																									
											</div>
										</div>
									</div>
								</div>
							</div>									
							<?php ENDIF ?>
						</div>
					</div>
				</div>
			</div>
			<?php include_once("includes/footer.php"); ?>
		</div>
	</div>
</div>
        
        

		
		
		 <!-- jQuery -->
        <script src="js/jquery.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="js/bootstrap.min.js"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="js/metisMenu.min.js"></script>
		
		<!-- DataTables JavaScript -->
		<script src="js/dataTables/jquery.dataTables.min.js"></script>
		<script src="js/dataTables/dataTables.bootstrap.min.js"></script>

        <!-- Custom Theme JavaScript -->
        <script src="js/startmin.js"></script>
		<script src="js/dashboard.js"></script>

    </body>
</html>