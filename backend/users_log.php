<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS

	$history = $_SESSION['IDusers'];
	
	$_SESSION['USERStransac'] = 1;
	IF(isset($_SESSION['fromremitted'])):
		unset($_SESSION['fromremitted']);
	ELSEIF(isset($_SESSION['frompayout'])):
		unset($_SESSION['frompayout']);
	ELSEIF(isset($_SESSION['fromidcard'])):
		unset($_SESSION['fromidcard']);
	ELSEIF(isset($_SESSION['frompidcardinventory'])):
		unset($_SESSION['frompidcardinventory']);
	ELSEIF(isset($_SESSION['fromdrivertripreport'])):
		unset($_SESSION['fromdrivertripreport']);
	ENDIF;
	
	//CUT OFF SETTINGS				
$terminal_settings = mysqli_fetch_array(mysqli_query($link, "SELECT cut_off FROM " . DB_PREFIX . "terminal_settings"));
$cutoff_time = $terminal_settings['cut_off'];
$date = date('Y-m-d', strtotime("-1 days"));


IF (!isset($_SESSION['selecteddate'])):
    $cutoff_start = $date . " " . $cutoff_time;
    $cutoff_end = date('Y-m-d') . " " . $cutoff_time;
ELSE:
    $cutoff_start = $_SESSION['cutstart'] . " " . $cutoff_time;
    $cutoff_end = $_SESSION['cutend'] . " " . $cutoff_time;
ENDIF;

$daily_cutoff = date('g:i A', strtotime($cutoff_time));

$USERS = mysqli_fetch_array(mysqli_query($link, "SELECT fname, mname, lname, suffix, user_ID FROM " . DB_PREFIX . "users WHERE user_ID='{$history}'"));
$fullname = $USERS['fname'] . " " . $USERS['lname'] . (($USERS['suffix']) ? ", " . $USERS['suffix'] : "");
		
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include_once('includes/header.php'); ?>
    </head>
    <body>

<div id="wrapper">

<?php include ('includes/navigation.php'); ?>

<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
						<!-- /.panel-heading -->
						<div class="panel-body">
							<div class="col-lg-12">
								<div class="container-fluid">
									<div class="row">
										<div class="col-lg-12" >
											<h3 class="page-header">Log History - Period of: <strong style="color:#0000FF"><?= date("F d, Y", strtotime($cutoff_start)); ?> </strong>To: <strong  style="color:#0000FF"><?= date("F d, Y", strtotime($cutoff_end)); ?></strong><span style="float:right">Cutoff: <strong  style="color:#0000FF"><?= $daily_cutoff; ?></strong></span></h3>
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

													<form role="form" name="cutoff" id="cutoff" method="post" action="setcutoffdate">
														<div class="form-group input-group" style="width:300px">
															<span class="input-group-addon"><li class="fa fa-calendar-minus-o"> From: </li></span><input type="date" class="form-control" name="selectdate1" id="selectdate1"><span class="input-group-addon"><li class="fa fa-calendar-plus-o"> To: </li></span><input type="date" class="form-control" name="selectdate2" id="selectdate2">
															<span class="input-group-btn"><button class="btn btn-default" type="submit" name="setcutdate"><i class="fa fa-arrow-right"></i></button></span>
														</div>
													</form>
												</div>
											</div>
										</div>
										<div class="col-lg-10"><a href="member_search"><h3><i class="fa fa-toggle-left fa-fw pull-left"></i>Back</a></h3></div>			
										<div class="col-lg-10">
											<h3 class="page-header"><strong>Name:<span style="color:#0000FF;"> <?= $fullname; ?></span></strong><strong class="pull-right">USERS ID:<span style="color:#0000FF;"> <?= $history; ?></span></strong></h3>
										</div><!-- /.col-lg-12 -->
															
										<div class="panel-body col-lg-12" >
											<!-- Tab panes -->
											<div class="tab-content">
												<div class="col-lg-10" >
													<div class="panel-body">
														<div class="dataTable_wrapper">
															<table class="table table-bordered table-striped" id="Search_Result_Table">
																<thead>
																	<tr>
																		<th>Date and Time</th>
																		<th>IP Address</th>
																	</tr>
																</thead>
																<tbody>
																	<?php
																		$log = mysqli_query($link, "SELECT log_date_time, IP_add FROM ".DB_PREFIX."user_log_history  WHERE user_ID='{$history}' AND `log_date_time` BETWEEN '{$cutoff_start}' AND '{$cutoff_end}'");
 
																			WHILE ($USERSs = mysqli_fetch_array($log)) {																		
																				$date = $USERSs['log_date_time'];
																				$IP = $USERSs['IP_add'];
																	?>
																	<tr class="gradeA">
																		<td><?= $date; ?></td>
																		<td><?= $IP; ?></td>
														
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
				<script>
			$(document).ready(function() {
				//TABLE
                $('#Search_Result_Table').DataTable({ responsive: true });
				
				//TOOLTIP
				$('[data-toggle="tooltip"]').tooltip(); 
            });
		</script>
    </body>
</html>
							