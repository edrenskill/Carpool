<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	if(!isset($_SESSION['ADM'])): header('location: login'); ENDIF;
	
	// REPORTING SESSION SET
	$_SESSION['frompidcardinventory'] = 1;
	IF(isset($_SESSION['fromremitted'])):
		unset($_SESSION['fromremitted']);
	ELSEIF(isset($_SESSION['fromidcard'])):
		unset($_SESSION['fromidcard']);
	ELSEIF(isset($_SESSION['fromdrivertripreport'])):
		unset($_SESSION['fromdrivertripreport']);
	ELSEIF(isset($_SESSION['frompayout'])):
		unset($_SESSION['frompayout']);
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
															<th>Teminal Name</th>
															<th>Teminal ID</th>
															<th>Disposed</th>
															<th>On Hand</th>
															<th>Total Number of Cards</th>
														</tr>
													</thead>
													<tbody>
													<?php
													
														$terminal = mysqli_query($link, "SELECT terminal_ID, terminal_name, operational FROM ".DB_PREFIX."terminal {$conditionset}");
														
														WHILE($terminalselect = mysqli_fetch_array($terminal)){
															$selectedID = $terminalselect['terminal_ID'];
															$terminal_name = $terminalselect['terminal_name'];
														
															// Collect Card Record
															$totalcards = mysqli_fetch_array(mysqli_query($link, "SELECT COUNT(ID) AS total_cards, status  FROM ".DB_PREFIX."idcards WHERE terminal_ID='{$selectedID}'"));
															$activecard = mysqli_fetch_array(mysqli_query($link, "SELECT COUNT(ID) AS active_card, status  FROM ".DB_PREFIX."idcards WHERE terminal_ID='{$selectedID}' AND status=1 AND disposed=1"));
															$pendingcard = mysqli_fetch_array(mysqli_query($link, "SELECT COUNT(ID) AS pending_card, status  FROM ".DB_PREFIX."idcards WHERE terminal_ID='{$selectedID}' AND status=0 AND disposed=0"));
													?>
														<tr class="gradeA">
															<td><a href="gen_id_bridge?terminal=<?= $selectedID; ?>" data-toggle="tooltip" title="View Terminal Transactions"><?= strtoupper($terminal_name); ?></a></td>
															<td><a href="terminaltrans?terminal=<?= $selectedID; ?>" data-toggle="tooltip" title="View Terminal Transactions"><?= $selectedID; ?></td>
															<td align="right"><span style="float:left;color:#0000FF;"></span><?= $activecard['active_card']; ?></td>
															<td align="right"><span style="float:left;color:#0000FF;"></span><?= $pendingcard['pending_card']; ?></td>
															<td align="right"><span style="float:left;color:#0000FF;"></span><?= $totalcards['total_cards']; ?></td>
														</tr>
													<?php } ?>
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
