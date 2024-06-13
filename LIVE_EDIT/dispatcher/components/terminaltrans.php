<?php
		$Ter_ID = $_SESSION['terminal_ID'];
		$loadwallet = mysqli_fetch_array(mysqli_query($link, "SELECT ending_balance, terminal_ID, transaction_code, debit, credit, transaction_date, transaction_time FROM ".DB_PREFIX."terminalload_wallet WHERE terminal_ID = '".$Ter_ID."' AND `primary` = 1")); 
		$prevloadwallet = mysqli_fetch_array(mysqli_query($link, "SELECT ending_balance, terminal_ID, transaction_code, debit, credit, transaction_date, transaction_time FROM ".DB_PREFIX."terminalload_wallet WHERE terminal_ID = '".$Ter_ID."' AND `primary` = 0 ORDER BY ID DESC LIMIT 0,1")); 
		$tcode = $loadwallet['transaction_code'];
		$transcodes = mysqli_fetch_array(mysqli_query($link, "SELECT description FROM ".DB_PREFIX."transactioncode WHERE transaction_code = '".$tcode."'"));
		$tdate = date_create($loadwallet['transaction_date']." ".$loadwallet['transaction_time']);
		$loadwalletdate = date_create($loadwallet['transaction_date']." ".$loadwallet['transaction_time']); 
		$prevloadwalletdate = date_create($prevloadwallet['transaction_date']." ".$prevloadwallet['transaction_time']);	
		
		$alltrans = mysqli_query($link, "SELECT * FROM ".DB_PREFIX."terminaltrans WHERE terminal_ID = '".$Ter_ID."' AND `transaction_code` != 'SS' ORDER BY ID DESC"); 
		$passengertrans = mysqli_query($link, "SELECT * FROM ".DB_PREFIX."terminaltrans WHERE terminal_ID = '".$Ter_ID."' AND `transaction_code` = 'LDC' ORDER BY ID DESC"); 
		$trip_history = mysqli_query($link, "SELECT * FROM ".DB_PREFIX."vehicle_trip_history WHERE terminal_ID = '".$Ter_ID."' ORDER BY ID DESC"); 
?>
	
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
							<table class="table table-striped table-bordered table-hover">
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
						</div>
					</div>
					
					
					
					
					
					
					
					
					
					
					
					
					
					<div class="panel panel-default">
                        <div class="panel-heading">
                            Terminal Transactions
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#transacations" data-toggle="tab">All Transactions</a></li>
                                <li><a href="#passengers" data-toggle="tab">Passenger</a></li>
                                <li><a href="#trips" data-toggle="tab">Vehicle Trips</a></li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="transacations">
									<div class="panel-body">
										<div class="dataTable_wrapper">
											<table class="table table-striped table-bordered table-hover" id="dataTables-terminaltransacations">
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
													<td class="right"><?= $amount_ptrint = ($trans['debit'] > 0 ? $trans['debit'] : $trans['credit']); ?></td>
												</tr>
												<?php } ?>
												</tbody>
											</table>
										</div>
									</div><!-- /.panel-body -->
                                </div>
									
                                <div class="tab-pane fade" id="passengers">
                                    <div class="panel-body">
										<div class="dataTable_wrapper">
											<table class="table table-striped table-bordered table-hover" id="dataTables-passengertransacations">
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
													WHILE($trans = mysqli_fetch_array($passengertrans)){
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
									</div><!-- /.panel-body -->
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
                                </div>

                            </div>
                        </div><!-- /.panel-body -->
                    </div><!-- /.panel --> 
				</div>
			</div>
		</div>


    

