<?php
	$terminal_query = mysqli_query($link, "SELECT * FROM ".DB_PREFIX."vehicles");
?>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Vehicle List
								<span class="pull-right"><a href="add_vehicle"><i class="fa fa-plus"></i> Add New Vehicle</a></span>	
				
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="dataTable_wrapper">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-vehicles">
                                        <thead>
                                            <tr>
												<th>Unit ID</th>
                                                <th>Plate Number</th>
                                                <th>Vehicle Owner</th>
                                                <th>Driver</th>
                                                <th>Application Status</th>
												<th>Unit Status</th>
												<th>Edit</th>
												<th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php 
											WHILE($terminal = mysqli_fetch_array($terminal_query)){
												$ownerID = $terminal['owner_ID'];
												$driverID = "'".$terminal['driver_ID1']."', '".$terminal['driver_ID2']."'";
												$app_stat = $terminal['application_status'];
												$unit_stat = $terminal['unit_status'];
												$unit_ID = $terminal['unit_ID']; 
												
											IF($app_stat == 2):
												IF($unit_stat == 0):
													$unitstatus = "<span class='text-warning' style='color:#0000ff'>active</span>";
												ELSEIF($unit_stat == 1):
													$unitstatus = "<span class='text-warning' style='color:#ffa500'>Suspended</span>";
												ELSEIF($unit_stat == 2):
													$unitstatus = "<span class='text-danger' style='color:#FF0000'>Banned</span>";
												ENDIF;
											ELSEIF($app_stat == 0 || $app_stat == 1 || $app_stat == 3 || $app_stat == 4):
													$unitstatus = "<span class='text-danger' style='color:#0000ff'>Pending</span>";
											ELSEIF($app_stat == 3 || $app_stat == 5):
													$unitstatus = "<span class='text-danger' style='color:#FF0000'>Denied</span>";
											ENDIF;
											
												IF($app_stat == 0):
													$nunitstatus = "<span class='text-info'>New Application</span>";
												ELSEIF($app_stat == 1):
													$nunitstatus = "<span class='text-primary'>Processing</span>";
												ELSEIF($app_stat == 2):
													$nunitstatus = "<span class='text-success'>Granted</span>";
												ELSEIF($app_stat == 3):
													$nunitstatus = "<span class='text-danger'>Denied</span>";
												ELSEIF($app_stat == 4):
													$nunitstatus = "<span class='text-danger'>Denied/Appealed</span>";
												ELSEIF($app_stat == 5):
													$nunitstatus = "<span class='text-danger'>Denied/Final</span>";
												ENDIF;
												
												
												
											$vowner = mysqli_fetch_array(mysqli_query($link, "SELECT CONCAT (fname,' ',lname) AS `fullname` FROM ".DB_PREFIX."users WHERE `user_ID`='".$ownerID."' "));
										?>
                                            <tr class="gradeA">
												<td class="center"><?= $unit_ID;?></td>
                                                <td><?= $terminal['plate_number']; ?></td>
                                                <td><a href="gen_id_bridge?memberID=<?= $ownerID; ?>"><?= strtoupper($vowner['fullname']); ?><a/></td>
                                                <td>
													<?php 
														$getdriver = mysqli_query($link, "SELECT fname, lname, user_ID, account_status FROM ".DB_PREFIX."users WHERE user_ID IN ($driverID)");								
														WHILE ($driver = mysqli_fetch_array($getdriver)){
															$currentID = $driver['user_ID'];
															$Dstatus = $driver['account_status'];
															$fullname = strtoupper($driver['fname'][0].". ".$driver['lname']);

															IF(strpos($currentID, 'TEMP') !== false):
																$status = "<span class='text-warning'>For Activation</span>";
															ELSE:
																IF($Dstatus == 0):
																	$status = "<span class='text-success'>Active</span>";
																ELSEIF($Dstatus == 1):
																	$status = "<span class='text-danger'>Suspended</span>";
																ELSEIF($Dstatus == 2):
																	$status = "<span class='text-muted'>Banned</span>";
																ENDIF;
															ENDIF;
													?>
														<small><a href="gen_id_bridge?memberID=<?= $currentID; ?>"><?=$fullname?></a> - <span class="pull-right"><em><?=$status?></em></span></small><br/>
														<?php } ?>
												</td>
                                                <td class="center"><?=$nunitstatus;?></br>
														<?php IF($app_stat == 0): ?>
																<button id="Processing\" class="btn btn-default" data-toggle="tooltip" title="Processing" style="padding: 4px 4px 4px 4px;"><a href="gen_id_bridge?processing=<?=$unit_ID?>"><i class="fa  fa-gears"></i></a></button>
																<button id="grandted" class="btn btn-default" data-toggle="tooltip" title="grandted" style="padding: 4px 4px 4px 4px;"><a href="gen_id_bridge?grandted=<?=$unit_ID?>"><i class="fa fa-check-square"></i></a></button>
																<button id="Denied" class="btn btn-default" data-toggle="tooltip" title="Denied" style="padding: 4px 4px 4px 4px;"><a href="gen_id_bridge?denied=<?=$unit_ID?>"><i class="fa fa-close"></i></a></button>
														<?php ELSEIF($app_stat == 1): ?>
																<button id="grandted" class="btn btn-default" data-toggle="tooltip" title="grandted" style="padding: 4px 4px 4px 4px;"><a href="gen_id_bridge?grandted=<?=$unit_ID?>"><i class="fa fa-check-square"></i></a></button>
																<button id="Denied" class="btn btn-default" data-toggle="tooltip" title="Denied" style="padding: 4px 4px 4px 4px;"><a href="gen_id_bridge?denied=<?=$unit_ID?>"><i class="fa fa-close"></i></a></button>
														<?php ELSEIF($app_stat == 2): ?>
														<?php ELSEIF($app_stat == 3): ?>
															<button id="Processing" class="btn btn-default disabled" style="padding: 4px 4px 4px 4px;"><i class="fa fa-gears"></i></button>
															<button id="grandted" class="btn btn-default disabled" style="padding: 4px 4px 4px 4px;"><i class="fa fa-check-square"></i></button>
															<button id="Appealed/Denied" class="btn btn-default" data-toggle="tooltip" title="Denied/Appealed" style="padding: 4px 4px 4px 4px;"><a href="gen_id_bridge?deniedappealed=<?=$unit_ID?>"><i class="fa fa-close"></i></a></button>
															<?php ELSEIF($app_stat == 4): ?>
															<button id="grandted" class="btn btn-default" data-toggle="tooltip" title="grandted" style="padding: 4px 4px 4px 4px;"><a href="gen_id_bridge?grandted=<?=$unit_ID?>"><i class="fa fa-check-square"></i></a></button>
															<button id="finalDenied" class="btn btn-default" data-toggle="tooltip" title="Denied/Final Appealed" style="padding: 4px 4px 4px 4px;"><a href="gen_id_bridge?deniedfinalappealed=<?=$unit_ID?>"><i class="fa fa-close"></i></a></button>
															<?php ELSEIF($app_stat == 5): ?>
															<button id="Processing" class="btn btn-default disabled" style="padding: 4px 4px 4px 4px;"><i class="fa fa-gears"></i></button>
															<button id="grandted" class="btn btn-default disabled" style="padding: 4px 4px 4px 4px;"><i class="fa fa-check-square"></i></button>
															
														<?php ENDIF; ?></td>
												<td class="center"><?=$unitstatus;?></td>
												<td class="center"><a href="gen_id_bridge?unitID=<?= $terminal['unit_ID']; ?>"><i class="fa fa-edit"></i></a></td>
												<td>
													<?php IF($app_stat == 2): ?>
															<?php IF($unit_stat == 0 || $unit_stat == 1): ?>
																	<?php IF($unit_stat == 0): ?>
																		<button id="suspend" class="btn btn-default" data-toggle="tooltip" title="Suspend ID" style="padding: 4px 4px 4px 4px;"><a href="gen_id_bridge?suspendUNITID=<?=$unit_ID;?>"><i class="fa fa-exclamation-triangle"></i></a></button>
																	<?php ELSEIF($unit_stat == 1): ?>
																		<button id="unsuspend" class="btn btn-default" data-toggle="tooltip" title="Unsuspend ID" style="padding: 4px 4px 4px 4px;"><a href="gen_id_bridge?unsuspendUNITID=<?=$unit_ID?>&unit_status=<?=$unit_stat?>"><i class="fa fa-exclamation-triangle"></i></a></button>
																	<?php ENDIF; ?>
																	<button id="ban" class="btn btn-default" data-toggle="tooltip" title="Ban ID" style="padding: 4px 4px 4px 4px;"><a href="gen_id_bridge?banUNITID=<?=$unit_ID;?>"><i class="fa fa-ban"></i></a></button>

															<?php ELSEIF($unit_stat == 2): ?>
																<button id="suspend" class="btn btn-default disabled" style="padding: 4px 4px 4px 4px;"><i class="fa fa-exclamation-triangle"></i></button>
																<button id="ban" class="btn btn-default disabled" style="padding: 4px 4px 4px 4px;"><i class="fa fa-ban"></i></button>
															<?php ENDIF; ?>
													<?php ENDIF; ?>
												</td>
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
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
				
