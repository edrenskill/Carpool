<?php 
require_once '../../settings/connect.php';
if(session_id() == '') { page_protect(); } // START SESSIONS
$terminal_ID = $_SESSION['terminal_ID'];
?>

										<span class="list-group-item">
											<i class="fa fa-bus fa-1x"></i> Plate No.
											<span class="pull-right medium"><i class="fa fa-user fa-1x"></i> Driver Name</span>
										</span>
										<?php 
											$available_units = mysqli_query($link, "SELECT * FROM ".DB_PREFIX."vehicle_trip_schedule WHERE terminal_ID='".$terminal_ID."' AND selected='0'");
											WHILE($available = mysqli_fetch_array($available_units)){ 
											
											$all_vehicle_ID = $available['vehicle_ID'];		

											$all_unit_query = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM ".DB_PREFIX."vehicles WHERE `unit_ID`='".$all_vehicle_ID."'"));
											$all_driver_ID = $available['driver_ID'];
											$all_plate_no = $all_unit_query['plate_number'];
											$unit_ID = $all_unit_query['unit_ID'];

											$all_driver_info = mysqli_fetch_array(mysqli_query($link, "SELECT fname,lname,suffix FROM ".DB_PREFIX."users WHERE user_ID = '".$all_driver_ID."'"));
										?>
										<span class="list-group-item">
											<a href="unit_login_bridge?unitID=<?= $unit_ID; ?>">
											<span class="text-muted medium" style="color:#003300;"><em><b><?= $all_plate_no; ?></b></em></span>
											<span class="pull-right medium" style="color:#003300;">
												<em>
													<b>
														<span class="fname"><?= $all_driver_info['fname']; ?></span>
														<span class="lname"><?= $all_driver_info['lname']; ?></span>
														<?php IF($all_driver_info['suffix'] != ""): echo ", ".$all_driver_info['suffix']; ENDIF; ?>
													</b>
												</em>
											</span>
											</a>
										</span>
											<?php } ?>