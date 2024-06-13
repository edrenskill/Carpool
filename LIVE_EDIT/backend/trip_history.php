<?php
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	if(!isset($_SESSION['ADM'])): header('location: login'); ENDIF;
	
	$vehicle_ID = $_SESSION['vehicleID'];
		$prevtrip = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM ".DB_PREFIX."vehicle_trip_history WHERE vehicle_ID = '".$vehicle_ID."' ORDER BY ID DESC LIMIT 0,1")); 
		$unitID = $prevtrip['vehicle_ID'];
		$prevtrip_vehicle = mysqli_fetch_array(mysqli_query($link, "SELECT plate_number FROM ".DB_PREFIX."vehicles WHERE unit_ID='{$unitID}'"));
		$driver_ID = $prevtrip['driver_ID'];
		$driver_info = mysqli_fetch_array(mysqli_query($link, "SELECT fname, lname, suffix FROM ".DB_PREFIX."users WHERE user_ID = '".$driver_ID."'"));

		$route = $prevtrip['terminal_ID'];
		$routedetail = mysqli_fetch_array(mysqli_query($link, "SELECT route_origin, route_destination, terminal_name FROM ".DB_PREFIX."terminal WHERE terminal_ID = '".$route."'"));
		$prevtripdate = date_create($prevtrip['time_date']);
		
		$trip_history = mysqli_query($link, "SELECT * FROM ".DB_PREFIX."vehicle_trip_history WHERE vehicle_ID = '".$vehicle_ID."' ORDER BY ID DESC"); 
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
				<h1 class="page-header">Vehicle Trip History</h1>
			</div><!-- /.col-lg-12 -->
		</div><!-- /.row -->
		
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
                    <div class="panel-heading">
                        Recent Trip.
                    </div>
				
					<div class="panel-body">
                        <div class="dataTable_wrapper">
							<table class="table table-striped table-bordered table-hover" id="dataTables-vehicletrans">
								<tr>
									<th><h4>Plate No.</h4></th>
									<th><h4>Driver</h4></th>
									<th><h4>No. of Passenger(s)</h4></th>
									<th><h4>Origin</h4></th>
									<th><h4>Destination</h4></th>
									<th><h4>Time and Date</h4></th>
								</tr>

								<tr>
									<td><?= $prevtrip_vehicle['plate_number']; ?></td>
									<td>
										<strong>
											<span><?= $driver_info['fname']; ?></span>
											<span><?= $driver_info['lname']; ?></span>
											<?php IF($driver_info['suffix'] != ""): echo ", ".$driver_info['suffix']; ENDIF; ?>
										</strong>
									</td>
									<td><?= $prevtrip['passenger']; ?></td>
									<td><?= $routedetail['route_origin']; ?></td>
									<td><?= $routedetail['route_destination']; ?></td>
									<td><?= date_format($prevtripdate, 'g:ia l jS F Y'); ?></td>
								</tr>
							</table>
							</form>
						</div>
					</div>
					
					
					
					<div class="panel panel-default">
                        <div class="panel-heading">
                            All Trips
                        </div>
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-vehicletransaction">
                                    <thead>
                                        <tr>
											<th>Plate No.</th>
											<th>Driver</th>
											<th>No. of Passenger(s)</th>
											<th>Origin</th>
											<th>Destination</th>
											<th>Time and Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
										WHILE($alltrip = mysqli_fetch_array($trip_history)){
											$driver_ID = $alltrip['driver_ID'];
											$driver_info = mysqli_fetch_array(mysqli_query($link, "SELECT fname, lname, suffix FROM ".DB_PREFIX."users WHERE user_ID = '".$driver_ID."'"));
											
											$route = $alltrip['terminal_ID'];
											$allroutedetail = mysqli_fetch_array(mysqli_query($link, "SELECT route_origin, route_destination, terminal_name FROM ".DB_PREFIX."terminal WHERE terminal_ID = '".$route."'"));
											$alltripdate = date_create($alltrip['time_date']);
											$allplate = $alltrip['vehicle_ID'];
											
											$trip_vehicle = mysqli_fetch_array(mysqli_query($link, "SELECT plate_number FROM ".DB_PREFIX."vehicles WHERE unit_ID='{$allplate}'"));
											

									?>
                                           <tr class="gradeA">
											   <td><?= $trip_vehicle['plate_number']; ?></td>
                                               <td><a href="gen_id_bridge?memberID=<?= $driver_ID; ?>">
													<strong>
														<span><?= $driver_info['fname']; ?></span>
														<span><?= $driver_info['lname']; ?></span>
														<?php IF($driver_info['suffix'] != ""): echo ", ".$driver_info['suffix']; ENDIF; ?>
													</strong>
											   <a/></td>
											   <td><?= $alltrip['passenger']; ?></td>
                                               <td><?= $allroutedetail['route_origin']; ?></td>
                                               <td><?= $allroutedetail['route_destination']; ?></td>
                                               <td class="center"><?= date_format($alltripdate, 'g:ia l jS F Y'); ?></td>
                                           </tr>
                                       <?php } ?>
                                       </tbody>
                                   </table>
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
				$('#dataTables-vehicletransaction').DataTable({
                        responsive: true
                });
            });
        </script>
</body>
</html>
