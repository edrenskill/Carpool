<?php
	$fieldsset = 'username, fname, mname, lname, suffix, photo, signature, regdate, city, user_ID, gen_card, userlevel, account_status, status_ID';

	// Active Member Query
	$Activemembers_query = mysqli_query($link, "SELECT {$fieldsset} FROM ".DB_PREFIX."users WHERE (`userlevel` = 7 OR `userlevel` = 8 OR `userlevel` = 10) AND `user_ID` NOT LIKE '%TEMP%' AND approval = 1 AND account_status=0");

	//Pending
	$noIDmembers_query = mysqli_query($link, "SELECT {$fieldsset} FROM ".DB_PREFIX."users WHERE (userlevel=7 OR userlevel=8 OR userlevel=10) AND approval = 0 AND {$batchID} `gen_card`='0' AND `user_ID` LIKE '%TEMP%'");
	
	// Suspended Users
	$checksuspend_query = mysqli_query($link, "SELECT A.date_from, A.date_to, A.remarks, B.username, B.fname, B.mname, B.lname, B.suffix, B.photo, B.signature, B.regdate, B.user_ID, B.gen_card, B.account_status, B.status_ID FROM commuter_account_status A, commuter_users B WHERE B.account_status=1 AND A.user_ID=B.user_ID  AND A.status_ID=B.status_ID AND B.userlevel NOT IN (1,2,3,4,5,6,9)");
	
	// Banned Users
	$banned_query = mysqli_query($link, "SELECT A.date_from, A.date_to, A.remarks, B.username, B.fname, B.mname, B.lname, B.suffix, B.photo, B.signature, B.regdate, B.user_ID, B.gen_card, B.account_status, B.status_ID FROM commuter_account_status A, commuter_users B WHERE B.account_status=2 AND A.user_ID=B.user_ID  AND A.status_ID=B.status_ID AND B.userlevel NOT IN (1,2,3,4,5,6,9)");
	
	// Commuter Level
	$commuter_query = mysqli_query($link, "SELECT {$fieldsset} FROM ".DB_PREFIX."users WHERE `userlevel` = 1 ");
	
	// Dispatcher Level
	$dispatcher_query = mysqli_query($link, "SELECT {$fieldsset} FROM ".DB_PREFIX."users WHERE `userlevel` = 2 ");
	
	//$allmemberscounter = mysqli_num_rows($allmembers_query);
	$Activememberscounter = mysqli_num_rows($Activemembers_query);
	$noIDmemberscounter = mysqli_num_rows($noIDmembers_query);
	
	// Count suspended Users
	$checksuspendcounter = mysqli_num_rows($checksuspend_query);

	// Count Banned Users
	$bannedcounter = mysqli_num_rows($banned_query);
	
	// Count commuter Users
	$commutercounter = mysqli_num_rows($commuter_query);
	
	// Count Dispatcher Users
	$dispatchercounter = mysqli_num_rows($dispatcher_query);

	// 0 with TEMP = New Member w/o ID Number,
	// 0 without TEMP = New Member with ID No w/o Card, ---- For Generation of ID
	// 1 =  Generated and Printed
	

?>

					<div class="panel panel-default">
                        <div class="panel-heading">
							<span class="pull-right"><a href="application"><i class="fa fa-user-plus"></i> New Applicant</a></span>
							<div style="clear:both"></div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#allmembers" data-toggle="tab">Active Members <span style="color:red"><?= $Activememberscounter; ?></span></a></li>
                                <li><a href="#forID" data-toggle="tab">Pending Applications  <span style="color:red"><?= $noIDmemberscounter; ?></span></a></li>
                                <li><a href="#SuspendedDriver" data-toggle="tab">Suspend Accounts <span style="color:red"><?= $checksuspendcounter; ?></span></a></li>
								<li><a href="#BannedAccounts" data-toggle="tab">Banned Accounts <span style="color:red"><?= $bannedcounter; ?></span></a></li>
								<li><a href="#CommuterAccounts" data-toggle="tab">Commuter Accounts <span style="color:red"><?= $commutercounter; ?></span></a></li>
								<li><a href="#DispatcherAccounts" data-toggle="tab">Dispatcher Accounts <span style="color:red"><?= $dispatchercounter; ?></span></a></li>
						    </ul>

                            <!-- Tab panes -->
							
<!---------------- ALL ACCOUNTS INCLUDING COMMUTERS, DRIVERS AND OPERATORS ------------------------------------------------>

                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="allmembers">
									<div class="panel-body">
										<div class="dataTable_wrapper">
											<table class="table table-striped table-bordered table-hover" id="dataTables-allmembers">
												<thead>
													<tr>
														<th>Join Date</th>
														<th>Member Name</th>
														<th>Membership Type</th>
														<th>Account Status</th>
														<th>Meber's ID Number</th>
														<th>ID Card Status</th>
													</tr>
												</thead>
												<tbody>
												<?php 
													WHILE($member = mysqli_fetch_array($Activemembers_query)){
													$location = mysqli_fetch_array(mysqli_query($link, "SELECT name FROM ".DB_PREFIX."city_municipality WHERE cm_code = {$member['city']}"));
													
													$fullname = $member['fname']." ".$member['lname'].(($member['suffix'])? ", ".$member['suffix'] : "");
													$Uid = $member['user_ID'];
												?>
													<tr class="gradeA">
														<td><?= $member['regdate']; ?></td>
														<td><a href="gen_id_bridge?memberID=<?= $member['user_ID']; ?>"><?= strtoupper($fullname); ?></a></td>
														<td>
															<?php
															$usertype = $member['userlevel'];
															IF($usertype == 7): echo "Driver";
															ELSEIF($usertype == 8): echo "Owner";
															ELSEIF($usertype == 10): echo "Owner/Driver"; 
															ENDIF;
															?>
														</td>
														<td>
															<?php
															$member_ID = $member['user_ID'];
															$account_status = $member['account_status'];
															
															IF(strpos($member_ID, 'TEMP') !== false):
																$status = "<span style='color:#ffa500'>For Activation</span>";
															ELSEIF(strpos($member_ID, 'TEMP') == false):
																IF($account_status == 0): 
																	$status = "<span style='color:#0000ff'>Active</span>";
																ELSEIF($account_status == 1):
																	$status = "<span style='color:#FF0000'>Suspended</span>";
																ELSEIF($account_status == 2):
																	$status = "<span style='color:#FF0000'>Banned</span>";
																ENDIF;
																$status = "<span style='color:#0000ff'>Active</span>";
															ENDIF;
															echo $status;
															?>
														</td>
														<td class="center"><?= $member['user_ID']; ?></td>
														<td class="center"><?= 
														(($member['gen_card'] ==1)? "<a href='gen_id_bridge?member=".$Uid."'><span style='color:#0000FF'>Printed</span></a>" : 
														(($member['photo'] == "" && $member['signature'] == "")? "<a href='gen_id_bridge?member=".$Uid."'><span style='color:#FF0000'>Upload Photo / Signature</span></a>" : 
														(($member['photo'] == "")? "<a href='gen_id_bridge?member=".$Uid."'><span style='color:#FF0000'>Upload Photo</span></a>" : 
														(($member['signature'] == "")? "<a href='gen_id_bridge?member=".$Uid."'><span style='color:#FF0000'>Upload Signature</span></a>" :
														((stripos($member['user_ID'], "TEMP") !== false)? "<a href='gen_id_bridge?member=".$Uid."'><span style='color:#FF0000'>Assign Permanent ID No.</span></a>" : "<a href='gen_id_bridge?member=".$Uid."'>Pending for Printing</a><span style='float:right'><button type='button' class='btn btn-primary btn-xs' id='id".$Uid."' onClick='
														
														
														$.post(`./trigger/exec`,{ printid: ".$Uid."},function(data) {});
														
														'>printed</button></span> 
														"))))); ?>
														</td>
													</tr>
												<?php } ?>
												</tbody>
											</table>											
										</div>
									</div><!-- /.panel-body -->
                                </div>
									
									
									
<!---------------- DRIVER / OPRATORS PENDING APPLICATIONS ------------------------------------------------>

 
								<div class="tab-pane fade" id="forID">
                                    <div class="panel-body">
										<div class="dataTable_wrapper">
											<table class="table table-striped table-bordered table-hover" id="dataTables-forID">
													<thead>
													<tr>
														<th>Join Date</th>
														<th>Member Name</th>
														<th>Membership Type</th>
														<th>Meber's ID Number</th>
														<th>Status</th>
														<th>Pending Requirements</th>
														<th>Printable Application</th>
													</tr>
												</thead>
												<tbody>
												<?php 
													WHILE($member = mysqli_fetch_array($noIDmembers_query)){
													$location = mysqli_fetch_array(mysqli_query($link, "SELECT name FROM ".DB_PREFIX."city_municipality WHERE cm_code = {$member['city']}"));
													$fullname = $member['fname']." ".$member['lname'].(($member['suffix'])? ", ".$member['suffix'] : "");
												?>
													<tr class="gradeA">
														<td><?= $member['regdate']; ?></td>
														<td><a href="gen_id_bridge?memberID=<?= $member['user_ID']; ?>"><?= strtoupper($fullname); ?></a></td>
														<td>
															<?php
															$usertype = $member['userlevel'];
															IF($usertype == 7): echo "Driver";
															ELSEIF($usertype == 8): echo "Owner";
															ELSEIF($usertype == 10): echo "Owner/Driver"; 
															ENDIF;
															?>
														</td>
														<td class="center"><?= $member['user_ID']; ?></td>
														<td>
															<?php
															$member_ID = $member['user_ID'];
															$account_status = $member['account_status'];
															
															IF(strpos($member_ID, 'TEMP') !== false):
																$status = "<span style='color:#ffa500'>For Activation</span>";
															ELSEIF(strpos($member_ID, 'TEMP') == false):
																IF($account_status == 0): 
																	$status = "<span style='color:#0000ff'>Active</span>";
																ELSEIF($account_status == 1):
																	$status = "<span style='color:#FF0000'>Suspended</span>";
																ELSEIF($account_status == 2):
																	$status = "<span style='color:#FF0000'>Banned</span>";
																ENDIF;
																$status = "<span style='color:#0000ff'>Active</span>";
															ENDIF;
															echo $status;
															?>
														</td>
														<td>
															<?php
																IF($usertype == 7 || $usertype == 10):
																	$credentials = mysqli_fetch_assoc(mysqli_query($link, "SELECT  NBI, police_clearance FROM ".DB_PREFIX."driver_credentials WHERE driver_ID='{$member_ID}'"));
																	IF($credentials['NBI']==""):
																		echo "<h5><small>NBI Clearance - </small><small class='text-danger'>Required</small></h5>";
																	ELSE:
																		echo "<h5><small>NBI Clearance - </small><small class='text-success'>Complied</small></h5>";
																	ENDIF;
																	IF($credentials['police_clearance']==""):
																		echo "<h5><small>Police Clearance - </small><small class='text-danger'>Required</small></h5>";
																	ELSE:
																		echo "<h5><small>Police Clearance - </small><small class='text-success'>Complied</small></h5>";
																	ENDIF;
																ENDIF;
															?>
														</td>
														<td>
															<?php
																IF($usertype == 7): 
																	$nlink = "driver?driver"; 
																ELSEIF($usertype == 8 || $usertype == 10): 
																	IF($usertype == 8):
																		$nlink = "operator_application?owner";
																	ELSE:
																		$nlink = "operator_application?owner"; ?>
																		<span class="pull-right"><a href="report/driver?driverID=<?=$member_ID;?>&type=<?=$usertype?>" target="_blank"><img src="../myaccount/images/pdficon.png"></a></span>
																	<?php
																	ENDIF;
																ENDIF;
															?>
															<span class="pull-right"><a href="report/<?=$nlink;?>ID=<?=$member_ID;?>&type=<?=$usertype?>" target="_blank"><img src="../myaccount/images/pdficon.png"></a></span>
														</td>
													</tr>
												<?php } ?>
												</tbody>
											</table>
										</div>
									</div><!-- /.panel-body -->
                                </div>

								
<!---------------- DRIVER / OPRATORS SUSPENDED ACCOUNTS ------------------------------------------------>
								
								<div class="tab-pane fade" id="SuspendedDriver">
                                    <div class="panel-body">
										<div class="dataTable_wrapper">
											<table class="table table-striped table-bordered table-hover" id="dataTables-SuspendedDriver">
													<thead>
													<tr>
														<th>Member Name</th>
														<th>Date From</th>
														<th>Date To</th>
														<th>Remarks</th>
													</tr>
												</thead>
												<tbody>
												<?php 
													WHILE($member = mysqli_fetch_array($checksuspend_query)){
														
													$fullname = $member['fname']." ".$member['lname'].(($member['suffix'])? ", ".$member['suffix'] : "");
												?>
													<tr class="gradeA">
														<td><a href="gen_id_bridge?memberID=<?= $member['user_ID']; ?>"><?= strtoupper($fullname); ?></a></td>
														<td><?= $member['date_from']; ?></td>
														<td><?= $member['date_to']; ?></td>
														<td><?php
															IF($member['remarks'] != ""):
																	
															$countremarks = strlen($member['remarks']);
																IF($countremarks > 15):
																		
																	echo substr($member['remarks'], 0, 10); ?><a href="javascript: null(void)" data-toggle="modal" data-target="#<?=$member['user_ID'];?>"> - Read More...</a>
																		
																	<div class="modal fade" id="<?=$member['user_ID'];?>" tabindex="-1" role="dialog" aria-labelledby="Remarkmod<?=$member['user_ID'];?>" aria-hidden="true">
																		<div class="modal-dialog">
																			<div class="modal-content">
																				<div class="modal-header">
																					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
																						<h4 class="modal-title" id="Remarkmod<?=$member['user_ID'];?>">Change Account Status Remarks</h4>
																				</div>
																				<div class="modal-body">
																				<?=$member['remarks']?>
																				</div>
																			</div>
																		</div>
																	</div>
															<?php
																ELSE:
																	echo $member['remarks'];
																ENDIF;
															ENDIF;?>
														</td>
													</tr>	
												<?php } ?>
												</tbody>
											</table>	
										</div>
									</div><!-- /.panel-body -->
                                </div>
								
<!---------------- DRIVER / OPRATORS BANNED ACCOUNTS ------------------------------------------------>								
								
								<div class="tab-pane fade" id="BannedAccounts">
                                    <div class="panel-body">
										<div class="dataTable_wrapper">
											<table class="table table-striped table-bordered table-hover" id="dataTables-BannedAccounts">
													<thead>
													<tr>
														<th>Member Name</th>
														<th>Date</th>
														<th>Remarks</th>														
													</tr>
												</thead>
												<tbody>
												<?php 
													WHILE($member = mysqli_fetch_array($banned_query)){
													$fullname = $member['fname']." ".$member['lname'].(($member['suffix'])? ", ".$member['suffix'] : "");
												?>
													<tr class="gradeA">
														<td><a href="gen_id_bridge?member=<?= $member['user_ID']; ?>"><?= strtoupper($fullname); ?></a></td>
														<td><?= $member['date_from']; ?></td>
														<td><?php
															IF($member['remarks'] != ""):
																	
															$countremarks = strlen($member['remarks']);
																IF($countremarks > 15):
																		
																	echo substr($member['remarks'], 0, 10); ?><a href="javascript: null(void)" data-toggle="modal" data-target="#<?=$member['user_ID'];?>"> - Read More...</a>
																		
																	<div class="modal fade" id="<?=$member['user_ID'];?>" tabindex="-1" role="dialog" aria-labelledby="Remarkmod<?=$member['user_ID'];?>" aria-hidden="true">
																		<div class="modal-dialog">
																			<div class="modal-content">
																				<div class="modal-header">
																					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
																						<h4 class="modal-title" id="Remarkmod<?=$member['user_ID'];?>">Change Account Status Remarks</h4>
																				</div>
																				<div class="modal-body">
																				<?=$member['remarks']?>
																				</div>
																			</div>
																		</div>
																	</div>
															<?php
																ELSE:
																	echo $member['remarks'];
																ENDIF;
															ENDIF;?>
														</td>
													</tr>
												<?php } ?>
												</tbody>
											</table>
										</div>
									</div>
                                </div><!-- /.panel-body -->
								
<!---------------- COMMUTER ACCOUNTS ------------------------------------------------>								
								
								<div class="tab-pane fade" id="CommuterAccounts">
                                    <div class="panel-body">
										<div class="dataTable_wrapper">
											<table class="table table-striped table-bordered table-hover" id="dataTables-CommuterAccounts">
													<thead>
													<tr>
														<th>Date Joined</th>
														<th>Member Name</th>
														<th>ID Number</th>
														<th>Account Status</th>														
													</tr>
												</thead>
												<tbody>
												<?php 
													WHILE($member = mysqli_fetch_array($commuter_query)){
													$fullname = $member['fname']." ".$member['lname'].(($member['suffix'])? ", ".$member['suffix'] : "");
												?>
													<tr class="gradeA">
														<td><?= $member['regdate']; ?></td>
														<td><a href="gen_id_bridge?member=<?= $member['user_ID']; ?>"><?= strtoupper($fullname); ?></a></td>
														<td><?=$member['user_ID']?></td>
														<td>
															<?php
															$member_ID = $member['user_ID'];
															$account_status = $member['account_status'];
															
															IF(strpos($member_ID, 'TEMP') !== false):
																$status = "<span style='color:#ffa500'>For Activation</span>";
															ELSEIF(strpos($member_ID, 'TEMP') == false):
																IF($account_status == 0): 
																	$status = "<span style='color:#0000ff'>Active</span>";
																ELSEIF($account_status == 1):
																	$status = "<span style='color:#FF0000'>Suspended</span>";
																ELSEIF($account_status == 2):
																	$status = "<span style='color:#FF0000'>Banned</span>";
																ENDIF;
															ENDIF;
															echo $status;
															?>
														</td>
													</tr>
												<?php } ?>
												</tbody>
											</table>
										</div>
									</div>
                                </div><!-- /.panel-body -->
								
<!---------------- DISPATCHER ACCOUNTS ------------------------------------------------>								
								
								<div class="tab-pane fade" id="DispatcherAccounts">
                                    <div class="panel-body">
										<div class="dataTable_wrapper">
											<table class="table table-striped table-bordered table-hover" id="dataTables-DispatcherAccounts">
													<thead>
													<tr>
														<th>Date Joined</th>
														<th>Dispatcher Name</th>
														<th>ID Number</th>
														<th>Account Status</th>														
													</tr>
												</thead>
												<tbody>
												<?php 
													WHILE($dispatcher = mysqli_fetch_array($dispatcher_query)){
													$fullname = $dispatcher['fname']." ".$dispatcher['lname'].(($dispatcher['suffix'])? ", ".$dispatcher['suffix'] : "");
												?>
													<tr class="gradeA">
														<td><?= $dispatcher['regdate']; ?></td>
														<td><a href="gen_id_bridge?member=<?= $dispatcher['user_ID']; ?>"><?= strtoupper($fullname); ?></a></td>
														<td><?=$dispatcher['user_ID']?></td>
														<td>
															<?php
															$member_ID = $dispatcher['user_ID'];
															$account_status = $dispatcher['account_status'];
															
															IF(strpos($member_ID, 'TEMP') !== false):
																$status = "<span style='color:#ffa500'>For Activation</span>";
															ELSEIF(strpos($member_ID, 'TEMP') == false):
																IF($account_status == 0): 
																	$status = "<span style='color:#0000ff'>Active</span>";
																ELSEIF($account_status == 1):
																	$status = "<span style='color:#FF0000'>Suspended</span>";
																ELSEIF($account_status == 2):
																	$status = "<span style='color:#FF0000'>Banned</span>";
																ENDIF;
															ENDIF;
															echo $status;
															?>
														</td>
													</tr>
												<?php } ?>
												</tbody>
											</table>
										</div>
									</div>
                                </div><!-- /.panel-body -->
								
                            </div>
                        </div><!-- /.panel-body -->
                    </div><!-- /.panel --> 
