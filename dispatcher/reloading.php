<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	if(!isset($_SESSION['Dspr'])): header('location: login'); ENDIF;

	$_SESSION['fromcardsale'] = 1;
	IF(isset($_SESSION['fromremitted'])):
	ENDIF;

	//CUT OFF SETTINGS				
	$date = date('Y-m-d',strtotime("-1 days"));

	IF(!isset($_SESSION['selecteddate'])):
		$dateset = "";
		$cutoff_start = date("Y-m-d", strtotime("-1 year"));
		$cutoff_end = date("Y-m-d");
	ELSE:
		$cutoff_start = $_SESSION['cutstart']." ".$cutoff_time;
		$cutoff_end = $_SESSION['cutend']." ".$cutoff_time;
		$dateset = "AND B.date_sale BETWEEN '{$cutoff_start}' AND '{$cutoff_end}'";
	ENDIF;
	$terminal_ID = $_SESSION['terminal_ID'];
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
                            <h4 class="page-header">Period of: <strong style="color:#0000FF"><?= date("F d, Y", strtotime($cutoff_start)); ?> </strong>To: <strong  style="color:#0000FF"><?= date("F d, Y", strtotime($cutoff_end)); ?></strong></h4>
						</div><!-- /.col-lg-12 -->
						<div class="col-lg-12">

							<div class="panel-heading">
								<div class="page-header pull-left">
									Select Cut off Date Range <?php IF(isset($_SESSION['error'])): echo $_SESSION['error']; ENDIF; unset($_SESSION['error']);?>
									<?php IF(isset($_SESSION['updatedRecords'])): echo $_SESSION['updatedRecords']; ENDIF; ?>
									<form role="form" name="cutoff" id="cutoff" method="post" action="setdate">
										<div class="form-group input-group" style="width:300px">
											<span class="input-group-addon"><li class="fa fa-calendar-minus-o"> From: </li></span><input type="date" class="form-control" name="selectdate1" id="selectdate1"><span class="input-group-addon"><li class="fa fa-calendar-plus-o"> To: </li></span><input type="date" class="form-control" name="selectdate2" id="selectdate2">
											<span class="input-group-btn">
												<button class="btn btn-default" type="submit" name="setcutdate"><i class="fa fa-arrow-right"></i></button>
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
									<div class="tab-pane fade in active" id="crds">
										<div class="panel-body">

											 <div class="panel-heading clearfix col-lg-12">
												<div class="panel-title pull-left">Reloading Remittance Status</div>
												<div class="pull-right">
													<ul class="nav nav-tabs">
														<li class="active"><a href="#tab-remit" data-toggle="tab" aria-expanded="false">For Remit</a></li>
														<li class=""><a href="#tab-forconfirm" data-toggle="tab" aria-expanded="true">For Confirmation</a></li>
														<li class=""><a href="#tab-confirm" data-toggle="tab" aria-expanded="false">Confirmed</a></li>
													</ul>
												</div>
											</div>
											<div class="panel-body">
												<div class="tab-content">
													<div class="tab-pane fade  active in" id="tab-remit">
														<div class="dataTable_wrapper">
															<form name="remit" id="remit" method="post" action="trigger/update_reloading_remit">
																<table class="table table-striped table-bordered table-hover" id="dataTables-cash">
																	<thead>
																		<tr>
																			<th>Date</th>
																			<th># of Transactions</th>
																			<th>Total Sale</th>
																			<th>Remit Code</th>
																			<th></th>
																		</tr>
																	</thead>
																	<tbody>

																			<?php
																				$records = mysqli_query($link, "SELECT COUNT(ID) AS qty, GROUP_CONCAT(ref_no SEPARATOR ', ') AS r_number, trans_date, SUM(cash_on_hand) AS total_amount FROM ".DB_PREFIX."terminaltrans WHERE transaction_code='LDC' AND terminal_ID='{$terminal_ID}' AND remitted='0' {$dateset} GROUP BY trans_date");

																				WHILE($cash = mysqli_fetch_array($records)){
																					$R_no = $cash['r_number'];
																					//$IDs = str_replace(",","', '",$R_no);
																					$date_sale = $cash['trans_date'];
																					$total_sale = $cash['total_amount'];
																					$overall_total = 0;

																					$qty = $cash['qty'];;

																					$overall_total += $total_sale;
																					$tag = md5($date_sale);
																				?>
																					<tr class="gradeA">
																						<td><?= $date_sale ?></td>
																						<td><?= $qty ?></td>
																						<td><span class="pull-left text-primary">₱</span> <span class="pull-right text-primary"><strong><?= number_format($total_sale,2); ?></strong></span></td>
																						<td>
																							<div class="form-group input-group">

																								<input type="hidden" name="ID[]" id="ID[]" value="<?= $R_no ?>">
																								<input type="text" class="form-control" name="Rcode[]" id="Rcode[]" value="" style="width:150px">
																								<input type="hidden" name="amount[]" id="amount[]" value="<?= $overall_total ?>">
																								<span class="input-group-btn"></span>

																							</div>
																						</td>
																						<td align="center">
																							<a href="" data-toggle="modal" data-target="#tag_<?=$tag?>">
																								<span data-toggle="tooltip" title="View Details"><i class="fa fa-eye"></span></i>
																							</a>
																							<div class="modal fade" id="tag_<?=$tag?>" tabindex="-1" role="dialog" aria-labelledby="mytag_<?=$tag?>" aria-hidden="true">
																								<div class="modal-dialog">
																									<div class="modal-content">
																										<div class="modal-header">
																											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
																											<h4 class="modal-title" id="mytag_<?=$tag?>">Reloading Reference No. List</h4>
																										</div>
																										<div class="modal-body">
																											<?php
																												echo $IDs;
																											?>
																										</div>
																										<div class="modal-footer">
																											<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																										</div>
																									</div>
																								</div>
																							</div>
																						</td>
																					</tr>
																			<?php } ?>

																	</tbody>
																</table>	
																<span class="pull-right"><button class="btn btn-default" type="submit" name="updateremit" id="updateremit"><i data-toggle="tooltip" title="Remitted" class="fa fa-arrow-right"></i>Update Remittance</button></span>
															</form>										
														</div>
													</div>
													<div class="tab-pane fade" id="tab-forconfirm">

														<div class="dataTable_wrapper">

																<table class="table table-striped table-bordered table-hover" id="dataTables-cards_for_confirm">
																	<thead>
																		<tr>
																			<th>Date</th>
																			<th># of Transactions</th>
																			<th>Total Sale</th>
																			<th>Remit Code</th>
																			<th></th>
																		</tr>
																	</thead>
																	<tbody>

																			<?php
																				$records = mysqli_query($link, "SELECT COUNT(ID) AS qty, GROUP_CONCAT(ref_no SEPARATOR ', ') AS r_number, trans_date, SUM(cash_on_hand) AS total_amount, remittance_code FROM ".DB_PREFIX."terminaltrans WHERE transaction_code='LDC' AND terminal_ID='{$terminal_ID}' AND remitted='1' AND remit_confirmed='0' {$dateset} GROUP BY trans_date");

																				WHILE($cash = mysqli_fetch_array($records)){
																					$ref = $cash['r_number'];
																					$R_no = $cash['remittance_code'];
																					$date_sale = $cash['trans_date'];
																					$total_sale = $cash['total_amount'];
																					$overall_total = 0;

																					$qty = $cash['qty'];;

																					$overall_total += $total_sale;
																					$tag = md5($date_sale);
																				?>
																					<tr class="gradeA">
																						<td><?= $date_sale ?></td>
																						<td><?= $qty ?></td>
																						<td><span class="pull-left text-primary">₱</span> <span class="pull-right text-primary"><strong><?= number_format($total_sale,2); ?></strong></span></td>
																						<td><?= $R_no ?></td>
																						<td align="center">
																							<a href="" data-toggle="modal" data-target="#tag_<?=$tag?>">
																								<span data-toggle="tooltip" title="View Details"><i class="fa fa-eye"></span></i>
																							</a>
																							<div class="modal fade" id="tag_<?=$tag?>" tabindex="-1" role="dialog" aria-labelledby="mytag_<?=$tag?>" aria-hidden="true">
																								<div class="modal-dialog">
																									<div class="modal-content">
																										<div class="modal-header">
																											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
																											<h4 class="modal-title" id="mytag_<?=$tag?>">Reloading Reference No. List</h4>
																										</div>
																										<div class="modal-body">
																											<?php
																												echo $ref;
																											?>
																										</div>
																										<div class="modal-footer">
																											<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																										</div>
																									</div>
																								</div>
																							</div>
																						</td>
																					</tr>
																			<?php } ?>

																	</tbody>
																</table>	
														</div>
													
													</div>
													<div class="tab-pane fade" id="tab-confirm">
													
														<div class="dataTable_wrapper">
												
																<table class="table table-striped table-bordered table-hover" id="dataTables-cards_confirmed">
																	<thead>
																		<tr>
																			<th>Date</th>
																			<th># of Transactions</th>
																			<th>Total Sale</th>
																			<th>Remit Code</th>
																			<th></th>
																		</tr>
																	</thead>
																	<tbody>
																		
																			<?php
																				$records = mysqli_query($link, "SELECT COUNT(ID) AS qty, GROUP_CONCAT(ref_no SEPARATOR ', ') AS r_number, trans_date, SUM(cash_on_hand) AS total_amount, remittance_code FROM ".DB_PREFIX."terminaltrans WHERE transaction_code='LDC' AND terminal_ID='{$terminal_ID}' AND remitted='1' AND remit_confirmed='1' {$dateset} GROUP BY trans_date");

																				WHILE($cash = mysqli_fetch_array($records)){
																					$ref = $cash['r_number'];
																					$R_no = $cash['remittance_code'];
																					$date_sale = $cash['trans_date'];
																					$total_sale = $cash['total_amount'];
																					$overall_total = 0;

																					$qty = $cash['qty'];;

																					$overall_total += $total_sale;
																					$tag = md5($date_sale);
																				?>
																					<tr class="gradeA">
																						<td><?= $date_sale ?></td>
																						<td><?= $qty ?></td>
																						<td><span class="pull-left text-primary">₱</span> <span class="pull-right text-primary"><strong><?= number_format($total_sale,2); ?></strong></span></td>
																						<td><?= $R_no ?></td>
																						<td align="center">
																							<a href="" data-toggle="modal" data-target="#tag_<?=$tag?>">
																								<span data-toggle="tooltip" title="View Details"><i class="fa fa-eye"></span></i>
																							</a>
																							<div class="modal fade" id="tag_<?=$tag?>" tabindex="-1" role="dialog" aria-labelledby="mytag_<?=$tag?>" aria-hidden="true">
																								<div class="modal-dialog">
																									<div class="modal-content">
																										<div class="modal-header">
																											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
																											<h4 class="modal-title" id="mytag_<?=$tag?>">Reloading Reference No. List</h4>
																										</div>
																										<div class="modal-body">
																											<?php
																												echo $ref;
																											?>
																										</div>
																										<div class="modal-footer">
																											<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																										</div>
																									</div>
																								</div>
																							</div>
																						</td>
																					</tr>
																			<?php } ?>

																	</tbody>
																</table>	
														</div>
													
													
													</div>
												</div>
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
                $('#dataTables-cash').DataTable({ responsive: true });
				$('#dataTables-cards_for_confirm').DataTable({ responsive: true });
				$('#dataTables-cards_confirmed').DataTable({ responsive: true });

				//TOOLTIP
				$('[data-toggle="tooltip"]').tooltip(); 
            });
		</script>
    </body>
</html>