<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS

	
$_SESSION['drivertransac'] = 1;
	IF(isset($_SESSION['frommyaccount'])):
		unset($_SESSION['frommyaccount']);
	ENDIF;
	
	//CUT OFF SETTINGS				
$terminal_settings = mysqli_fetch_array(mysqli_query($link, "SELECT cut_off FROM " . DB_PREFIX . "terminal_settings"));
$cutoff_time = $terminal_settings['cut_off'];
$date = date('Y-m-d', strtotime("-1 days"));

	$driverID = $_SESSION['driverID'];

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
        $conditionset = "terminal_ID='{$terminal_ID}' AND ";
    ENDIF;
ELSE:
    $conditionset = "";
ENDIF;

$driver = mysqli_fetch_array(mysqli_query($link, "SELECT fname, mname, lname, suffix, user_ID FROM " . DB_PREFIX . "users WHERE user_ID='{$driverID}' AND account_status=0"));
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
							<div class="col-lg-12">
								<div class="container-fluid">
									<div class="row">
									<span style="float:right">Cutoff: <strong  style="color:#0000FF"><?= $daily_cutoff; ?></strong></span>
										<div class="col-lg-12" >
											<h3 class="page-header">Driver's Transaction - Period of: <strong style="color:#0000FF"><?= date("F d, Y", strtotime($cutoff_start)); ?> </strong>To: <strong  style="color:#0000FF"><?= date("F d, Y", strtotime($cutoff_end)); ?></strong></h3>
											<h4 class="page-header"><strong style="color:#0000FF"><?= isset($_SESSION['terminal_ID']) ? $_SESSION['terminal_name'] : "All Terminal"; ?></strong></h4>
										</div><!-- /.col-lg-12 -->
										<div class="col-lg-12">
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
													<form action="select_terminal" method="post" enctype="multipart/form-data" id="driverForm">
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
										<div class="col-lg-10"><a href="account_details"><h3><i class="fa fa-toggle-left fa-fw pull-left"></i>Back</a></h3></div>			
										<div class="col-lg-10">
											<h3 class="page-header"><strong>Driver ID:<span style="color:#0000FF;"><?= $driverID; ?></span></strong></h3>
										</div><!-- /.col-lg-12 -->
															
										<div class="panel-body col-lg-12" >
											<!-- Tab panes -->
											<div class="tab-content">
												<div class="col-lg-10" >
													<div class="panel-body">
														<div class="dataTable_wrapper">
															<table class="table table-bordered table-striped" id="dataTables-DTD">
																<thead>
																	<tr>
																		<th>Trip ID</th>
																		<th>Date</th>
																		<th>No. of Passenger(s)</th>
																	</tr>
																</thead>
																<tbody>
																	<?php
																		$records = mysqli_query($link, "SELECT trip_ID, time_date, passenger FROM ".DB_PREFIX."vehicle_trip_history  WHERE {$conditionset} driver_ID='{$driverID}' AND `time_date` BETWEEN '{$cutoff_start}' AND '{$cutoff_end}'");
																
																			WHILE ($drivers = mysqli_fetch_array($records)) {																		
																				$date = $drivers['time_date'];
																				$trip_ID = $drivers['trip_ID'];
																				$passenger = $drivers['passenger'];
																	?>
																	<tr class="gradeA">
																		<td><?= strtoupper($trip_ID); ?></a></td>
																		<td><?= $date; ?></td>
																		<td><?= $passenger; ?></td>
														
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
							