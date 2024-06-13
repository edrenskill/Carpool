<?php 	
require_once '../settings/connect.php';
if(session_id() == '') { page_protect(); } // START SESSIONS 
$terminal_ID = $_SESSION['terminal_ID'];
$statement = "`".DB_PREFIX."terminaltrans` WHERE terminal_ID = '".$terminal_ID."' AND transaction_code !='SF' ORDER BY `trans_date` DESC"; 
$results = mysqli_query($link,"SELECT * FROM {$statement}");

$_SESSION['transactionhistory'] = 1;
	IF(isset($_SESSION['frommyaccount'])):
		unset($_SESSION['frommyaccount']);
	ENDIF;
	//CUT OFF SETTINGS				
$date = date('Y-m-d', strtotime("-1 days"));

IF (!isset($_SESSION['selecteddate'])):
    $cutoff_start = $date . " " . $cutoff_time;
    $cutoff_end = date('Y-m-d') . " " . $cutoff_time;
ELSE:
    $cutoff_start = $_SESSION['cutstart'] . " " . $cutoff_time;
    $cutoff_end = $_SESSION['cutend'] . " " . $cutoff_time;
ENDIF;
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
									<div class="col-lg-12" style="margin: 0 0 -74px 0;">
											<h3 class="page-header">Log History - Period of: <strong style="color:#0000FF"><?= date("F d, Y", strtotime($cutoff_start)); ?> </strong>To: <strong  style="color:#0000FF"><?= date("F d, Y", strtotime($cutoff_end)); ?></strong></h3>
											<h4 class="page-header"><strong style="color:#0000FF"><?= isset($_SESSION['terminal_ID']) ? $_SESSION['terminal_name'] : "All Terminal"; ?></strong></h4>
									</div><!-- /.col-lg-12 -->
										<div style="float:left"><a href="account_details"><h3><i class="fa fa-toggle-left fa-fw"></i>Back</a></h3></div>
										<div class="col-lg-12" style="margin: -42px 0 0 0;">
											<div class="panel-heading">
												<div class="page-header" style="float:left">
													Select Date Range<?php
													IF (isset($_SESSION['error'])): echo $_SESSION['error'];
													ENDIF;
													unset($_SESSION['error']);
													
													$datemin = date('Y-m-d',strtotime("-12 month"));
													$datemax = date('Y-m-d',strtotime("+1 day"));
													?>

													<form role="form" name="cutoff" id="cutoff" method="post" action="setcutoffdate2">
														<div class="form-group input-group" style="width:300px">
															<span class="input-group-addon"><li class="fa fa-calendar-minus-o"> From: </li></span><input type="date" class="form-control" name="selectdate1" id="selectdate1" min="<?=$datemin;?>" max="<?=$datemax;?>"><span class="input-group-addon"><li class="fa fa-calendar-plus-o"> To: </li></span><input type="date" class="form-control" name="selectdate2" id="selectdate2" min="<?=$datemin;?>" max="<?=$datemax;?>">
															<span class="input-group-btn"><button class="btn btn-default" type="submit" name="setcutdate"><i class="fa fa-arrow-right"></i></button></span>
														</div>
													</form>
												</div>
											</div>
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
													<td><span class="text-success">+Php <?=number_format($row['credit'], 2, ".", "," );?></span></td>
													<?php }ELSEIF($row['debit']!=0){ ?>
													<td><?=strtoupper($description)." ".strtoupper($row['user_ID']);?></td>
													<td nowrap><span class="text-danger">-Php <?=number_format($row['debit'], 2, ".", "," );?></span></td>
													<?php } ELSEIF($row['cash_on_hand']!=0 && $row['debit'] ==0) { ?>
													<td><?=strtoupper($description);?></td>
													<td nowrap><span class="text-success">+Php <?=number_format($row['cash_on_hand'], 2, ".", "," );?></span></td>
													<?php } ?>
												</tr>
											
											<?php } ?>
											</tbody>
										</table>
										<h5>NOTE:
											<small>We only show up to 1 year of transation history. You can request to the office for the full history report.</small>
										</h5>
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
