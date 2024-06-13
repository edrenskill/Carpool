<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	if(!isset($_SESSION['Dspr'])): header('location: login'); ENDIF;
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
							<h4 class="page-header"><?= isset($_SESSION['terminal_ID'])? $_SESSION['terminal_name'] : "All Terminal";?></h4>
					    </div><!-- /.col-lg-12 -->

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
															<th>CE Card No.</th>
															<th>Card Type</th>
															<th>Remittance Status</th>
														</tr>
													</thead>
													<tbody>
													<?php
													
														$terminal_ID = $_SESSION['terminal_ID'];
													
														$query_cards = mysqli_query($link, "SELECT A.card_number, A.card_type, A.disposed, B.remitted FROM ".DB_PREFIX."idcards A, ".DB_PREFIX."card_sale B WHERE terminal_ID='{$terminal_ID}' AND disposed=1 AND B.card_number=A.card_number");
														
														WHILE($id_card = mysqli_fetch_array($query_cards)){
															$card_type = $id_card['card_type'];
															$card = $id_card['card_number'];
															$remitted = ($id_card['remitted'] == 0)? "<span class='text-danger'>For Remittance</span>" : "<span class='text-success'>Remitted</span>";
															
									
														?>
														<tr class="gradeA">
															<td><a href="gen_id_bridge?terminal=<?= $card; ?>" data-toggle="tooltip" title="View Terminal Transactions"><?= $card; ?></a></td>
															<td><span style="float:left;color:#0000FF;"></span><?= ($card_type == 1)? "Regular" : "Discounted"; ?></td>
															<td><?=$remitted?></td>
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
