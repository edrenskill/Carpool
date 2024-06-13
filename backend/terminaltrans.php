<?php

	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	if(!isset($_SESSION['ADM'])): header('location: login'); ENDIF;
	
	foreach($_GET as $key => $value) { $data[$key] = filter($value); }
	
	$terminal_ID = $data['terminal'];
		$loadwallet = mysqli_fetch_array(mysqli_query($link, "SELECT ending_balance, terminal_ID, transaction_code, debit, credit, transaction_date, transaction_time FROM ".DB_PREFIX."terminalload_wallet WHERE terminal_ID = '".$terminal_ID."' AND `primary` = 1")); 
		$prevloadwallet = mysqli_fetch_array(mysqli_query($link, "SELECT ending_balance, terminal_ID, transaction_code, debit, credit, transaction_date, transaction_time FROM ".DB_PREFIX."terminalload_wallet WHERE terminal_ID = '".$terminal_ID."' AND `primary` = 0 ORDER BY ID DESC LIMIT 0,1")); 
		$tcode = $loadwallet['transaction_code'];
		$transcodes = mysqli_fetch_array(mysqli_query($link, "SELECT description FROM ".DB_PREFIX."transactioncode WHERE transaction_code = '".$tcode."'"));
		$tdate = date_create($loadwallet['transaction_date']." ".$loadwallet['transaction_time']);
		$loadwalletdate = date_create($loadwallet['transaction_date']." ".$loadwallet['transaction_time']); 
		$prevloadwalletdate = date_create($prevloadwallet['transaction_date']." ".$prevloadwallet['transaction_time']);	
		
		$alltrans = mysqli_query($link, "SELECT * FROM ".DB_PREFIX."terminaltrans WHERE terminal_ID = '".$terminal_ID."' AND `transaction_code` != 'SF' ORDER BY ID DESC"); 
		$loadtrans = mysqli_query($link, "SELECT * FROM ".DB_PREFIX."terminaltrans WHERE terminal_ID = '".$terminal_ID."' AND `transaction_code` = 'LDC' ORDER BY ID DESC"); 
		
		$trip_history = mysqli_query($link, "SELECT * FROM ".DB_PREFIX."vehicle_trip_history WHERE terminal_ID = '".$terminal_ID."' ORDER BY ID DESC"); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <?php include_once('includes/header.php'); ?>
</head>
<body>

<div id="wrapper">

    <?php
		include_once('includes/navigation.php'); 
	?>		
		
	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Terminal Transaction Details</h1>
			</div><!-- /.col-lg-12 -->
		</div><!-- /.row -->
		
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
                    <div class="panel-heading">
                        Previous Transaction.
                    </div>
				
					<div class="panel-body">
                        <div class="dataTable_wrapper">
							<table class="table table-striped table-bordered table-hover" id="dataTables-terminaltrans">
								<tr>
									<th><h4>Detail</h4></th>
									<th><h4>Date</h4></th>
									<th><h4>Amount</h4></th>
								</tr>
								<?php IF(isset($prevloadwallet)): ?>
								<tr>
									<td>Previous Load Wallet</td>
									<td><?= date_format($prevloadwalletdate, 'g:ia l jS F Y'); ?></td>
									<td>₱<?= number_format($prevloadwallet['ending_balance'], 2, ".", "," ); ?></td>
								</tr>
								<?php ENDIF;
									IF($loadwallet['credit'] != 0):
										echo "<tr>";
										echo "<td>".$transcodes['description']."</td>";
										echo "<td>".date_format($tdate, 'g:ia l jS F Y')."</td>";
										echo "<td><span style=color:#0000ff;>+₱<strong>".number_format($loadwallet['credit'], 2, ".", "," )."</strong></span></td>";
										echo "</tr>";
									ELSEIF($loadwallet['debit'] != 0):
										echo "<tr>";
										echo "<td>".$transcodes['description']."</td>";
										echo "<td>".date_format($tdate, 'g:ia l jS F Y')."</td>";
										echo "<td><span style='color:#ff0000;'>-₱".number_format($loadwallet['debit'], 2, ".", "," )."</span></td>";
										echo "</tr>";
									ENDIF;

								IF(isset($loadwallet)): ?>
								<tr>
									<td><h4>Current Load Wallet</h4></td>
									<td><h4><?= date_format($loadwalletdate, 'g:ia l jS F Y'); ?></h4></td>
									<td><h4>₱<?= number_format($loadwallet['ending_balance'], 2, ".", "," ); ?></h4></td>
								</tr>
								<?php ENDIF; ?>
							</table>
							</form>
						</div>
					</div>
					
					
					
					<div class="panel panel-default">
                            <div class="panel-heading">
                                Terminal Transactions						
                            </div>
							<div class="row" style="float: right;">
								<div class="col-lg-12">
									<div class="panel-body">
										<form action="add_dispatcher" method="post"   id="dispatcherForm">
											<div class="form-group input-group" style="width:300px">
												<input type="hidden" name="terID" id="terID" value="<?=$terminal_ID;?>"/>
												<span class="input-group-addon"><li class="fa fa-user-plus"></li></span><input type="text" class="form-control" name="userID" id="userID" placeholder="Enter Dispatcher ID">
												<span class="input-group-btn"><input class="btn btn-default" type="submit" name="submitDID" id="submitDID" value="add"></span>
											</div>
										</form>
									</div>	
								</div>
							</div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#transaction" data-toggle="tab">All Transactions</a></li>
                                    <li><a href="#loading" data-toggle="tab">Loading</a></li>
                                    <li><a href="#trips" data-toggle="tab">Vehicle Trips</a></li>
									<li><a href="#details" data-toggle="tab">Terminal Details</a></li>
                                </ul>

                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div class="tab-pane fade in active" id="transaction">
										
										
										
										
										<div class="panel-body">
											<div class="dataTable_wrapper">
												<table class="table table-striped table-bordered table-hover" id="dataTables-terminaltransaction">
													<thead>
														<tr>
															<th>Transaction</th>
															<th>Date</th>
															<th>Reference Number</th>
															<th>User ID</th>
															<th>Amount</th>
														</tr>
													</thead>
													<tbody>
													<?php 
														WHILE($trans = mysqli_fetch_array($alltrans)){
														$tcode = $trans['transaction_code'];
														$transcodes = mysqli_fetch_array(mysqli_query($link, "SELECT description FROM ".DB_PREFIX."transactioncode WHERE transaction_code = '".$tcode."'"));
														$passenger = mysqli_fetch_array(mysqli_query($link, "SELECT description FROM ".DB_PREFIX."transactioncode WHERE transaction_code = '".$tcode."'"));
													?>
															<tr class="gradeA">
																<td><?= $transcodes['description']; ?></td>
																<td><?= $trans['trans_date']." ".$trans['trans_time']; ?></td>
																<td><?= $trans['ref_no']; ?></td>
																<td class="center"><?= $trans['user_ID']; ?></td>
																<td class="right"><?= $amount_ptrint = (($trans['service_fee'] > $trans['debit'] || $trans['service_fee'] > $trans['credit'])? ($trans['service_fee'] > $trans['cash_on_hand'])? $trans['service_fee'] : (($trans['debit'] > 0) ? $trans['debit'] : $trans['credit']) : ($trans['cash_on_hand'])); ?></td >
														</tr>
													<?php } ?>
													</tbody>
												</table>
											</div>
										</div>
										<!-- /.panel-body -->
                                    </div>
									
                                    <div class="tab-pane fade" id="loading">
                                        
										<div class="panel-body">
											<div class="dataTable_wrapper">
												<table class="table table-striped table-bordered table-hover" id="dataTables-terminaltransaction">
													<thead>
														<tr>
															<th>Transaction</th>
															<th>Date</th>
															<th>Reference Number</th>
															<th>User ID</th>
															<th>Amount</th>
														</tr>
													</thead>
													<tbody>
													<?php 
														WHILE($trans = mysqli_fetch_array($loadtrans)){
														$tcode = $trans['transaction_code'];
														$transcodes = mysqli_fetch_array(mysqli_query($link, "SELECT description FROM ".DB_PREFIX."transactioncode WHERE transaction_code = '".$tcode."'"));
														$passenger = mysqli_fetch_array(mysqli_query($link, "SELECT description FROM ".DB_PREFIX."transactioncode WHERE transaction_code = '".$tcode."'"));
													?>
															<tr class="gradeA">
																<td><?= $transcodes['description']; ?></td>
																<td><?= $trans['trans_date']." ".$trans['trans_time']; ?></td>
																<td><?= $trans['ref_no']; ?></td>
																<td class="center"><?= $trans['user_ID']; ?></td>
																<td class="right"><?= $amount_ptrint = ($trans['debit'] > 0 ? $trans['debit'] : $trans['credit']); ?></td>
														</tr>
													<?php } ?>
													</tbody>
												</table>
											</div>
										</div>
										<!-- /.panel-body -->

                                    </div>
									
                                    <div class="tab-pane fade" id="trips">
                                        
										<div class="panel-body">
											<div class="dataTable_wrapper">
												<table class="table table-striped table-bordered table-hover" id="dataTables-terminaltransaction">
													<thead>
														<tr>
															<th>Vehicle ID</th>
															<th>Plate Number</th>
															<th>Number of Passenger</th>
															<th>Date</th>
														</tr>
													</thead>
													<tbody>
													<?php 
														WHILE($trips = mysqli_fetch_array($trip_history)){
															$unit_ID = $trips['vehicle_ID'];
															$unit = mysqli_fetch_array(mysqli_query($link, "SELECT plate_number FROM ".DB_PREFIX."vehicles WHERE unit_ID = '".$unit_ID."'"));
													?>
															<tr class="gradeA">
																<td><?= $unit_ID; ?></td>
																<td><?= $unit['plate_number']; ?></td>
																<td><?= $trips['passenger']; ?></td>
																<td><?= $trips['time_date']; ?></td>
														</tr>
													<?php } ?>
													</tbody>
												</table>
											</div>
										</div>
										<!-- /.panel-body -->
                                    </div>
									
									
									 <div class="tab-pane fade" id="details">
                                        
										<div class="panel-body">
											<div class="dataTable_wrapper">
												<table class="table table-striped table-bordered table-hover">
												
													
													
													
													
													<?php $terminal_name = mysqli_fetch_assoc(mysqli_query($link, "SELECT terminal_name FROM ".DB_PREFIX."terminal WHERE terminal_ID='{$terminal_ID}'")); ?>
													<div class="panel-body">
														<div class="col-lg-6">
															<i class="fa fa-road fa-fw"></i> Terminal Name
															<span class="pull-right text-muted small"><em><?=$terminal_name['terminal_name'];?></em></span>
															<br/>
															
															<i class="fa fa-dashboard fa-fw"></i> Terminal ID
															<span class="pull-right text-muted small"><em><?= $terminal_ID;?></em></span>
															<br/>
														</div>
													</div>													

													<thead>
														<tr>
															<th>Name</th>
															<th>ID</th>
															<th>Last Login</th>
															<th>IP Address</th>
														</tr>
													</thead>
													<tbody>
													<?php 
														$getdispatchers = mysqli_query($link, "SELECT fname, lname, user_ID FROM ".DB_PREFIX."users WHERE terminal_ID='{$terminal_ID}'");
														WHILE($dispatcher = mysqli_fetch_array($getdispatchers)){
															$user_ID = $dispatcher['user_ID'];
															$fullname = $dispatcher['fname']." ".$dispatcher['lname'];
															$last_login = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM ".DB_PREFIX."user_log_history WHERE user_ID = '".$user_ID."' ORDER BY ID DESC LIMIT 1"));
													?>
															<tr class="gradeA">
																<td><?= strtoupper($fullname); ?></td>
																<td><?= $user_ID; ?></td>
																<td><?= $last_login['log_date_time']; ?></td>
																<td><?= $last_login['IP_add']; ?></td>
														</tr>
													<?php } ?>
													</tbody>
												</table>
											</div>
										</div>
										<!-- /.panel-body -->
                                    </div>
									
                                </div>
                            </div>
                            <!-- /.panel-body -->
                        </div>
                        <!-- /.panel -->
					
					
					
					
					
					
					
					
					
					
					 
				</div>
			</div>
		</div>
	</div><!-- /#page-wrapper -->

    

</div>

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

        <!-- Page-Level Demo Scripts - Tables - Use for reference -->
        <script>
            $(document).ready(function() {
				$('#dataTables-terminaltransaction').DataTable({
                        responsive: true
                });
            });
        </script>
</body>
</html>
