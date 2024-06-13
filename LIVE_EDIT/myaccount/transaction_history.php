<?php 	
require_once '../settings/connect.php';
if(session_id() == '') { page_protect(); } // START SESSIONS 
$terminal_ID = $_SESSION['terminal_ID'];
$statement = "`".DB_PREFIX."terminaltrans` WHERE terminal_ID = '".$terminal_ID."' AND transaction_code !='SS' ORDER BY `trans_date` DESC"; 
$results = mysqli_query($link,"SELECT * FROM {$statement}");
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
                            <h1 class="page-header">Transaction History</h1>
                        </div>
                        <!-- /.col-lg-12 -->
                    </div>
					<div class="row">
						<div class="col-lg-12">
							<div class="panel panel-default">
								<div class="panel-heading">
									Terminal Detailed Transaction History
								</div>
								<!-- /.panel-heading -->
								<div class="panel-body">
									<div class="dataTable_wrapper">
										<table class="table table-striped table-bordered table-hover" id="dataTables-TransHistory">
											<thead>
												<tr>
													<th>Rec No.</th>
													<th>Date</th>
													<th>Transaction Type</th>
													<th>Amount</th>
												</tr>
											</thead>
											<tbody>
											<?php
											$recno = 0;
											while ($row = mysqli_fetch_array($results)) {
											list($trans_code,$description) = mysqli_fetch_row(mysqli_query($link, "SELECT transaction_code, description FROM ".DB_PREFIX."transactioncode WHERE transaction_code = '".$row['transaction_code']."'"));
											$recno +=1;
											?>
											
												<tr>
													<td><?=$recno;?></td>
													<td><?=date("F d, m h:i:s A",strtotime($row['trans_date']." ".$row['trans_time']));?></td>
													<?php IF($row['credit']!=0){ ?>
													<td><?=$description;?></td>
													<td><span style="color:#00ff00;">+Php <?=number_format($row['credit'], 2, ".", "," );?></span></td>
													<?php }ELSEIF($row['debit']!=0){ ?>
													<td><?=strtoupper($description)." ".strtoupper($row['user_ID']);?></td>
													<td nowrap><span style="color:#ff0000;">-Php <?=number_format($row['debit'], 2, ".", "," );?></span></td>
													<?php } ELSE { ?>
													<td></td>
													<td></td>
													<?php } ?>
												</tr>
											
											<?php } ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php include_once("includes/footer.php"); ?>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /#page-wrapper -->

        </div>
        <!-- /#wrapper -->

        <!-- jQuery -->
        <script src="js/jquery.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="js/bootstrap.min.js"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="js/metisMenu.min.js"></script>

        <script src="js/dataTables/jquery.dataTables.min.js"></script>
        <script src="js/dataTables/dataTables.bootstrap.min.js"></script>

        <!-- Custom Theme JavaScript -->
        <script src="js/startmin.js"></script>

        <script>
            $(document).ready(function() {
                $('#dataTables-TransHistory').DataTable({
                        responsive: true
                });
            });
        </script>

    </body>
</html>
