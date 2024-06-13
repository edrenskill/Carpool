<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	if(!isset($_SESSION['ADM'])): header('location: login'); ENDIF;
	
	IF(isset($_SESSION['IDusers'])): unset($_SESSION['IDusers']); ENDIF; // Clear Session Variable

	foreach($_POST as $key => $value) { $data[$key] = filter($value); }	

	// SEARCH USER ID
	IF(isset($_POST) && array_key_exists('submitUID',$_POST)):
		
		$userdata =$data['userID'];

		IF(!isset($userdata) || $userdata == ""):
			$err = "Cannot Search Blank";
		ELSE:
			$search_result = array();
			$search_query = 0;
			
			$query = mysqli_query($link,"SELECT user_ID FROM `".DB_PREFIX."users` WHERE `user_ID` LIKE '%{$userdata}%' OR `email` LIKE '%{$userdata}%' OR `fname` LIKE '%{$userdata}%' OR `lname` LIKE '%{$userdata}%' AND userlevel !='5'");
			$total_count = mysqli_num_rows($query);
			IF($total_count !=0 ):	
				
				$search_query = 1;	
				WHILE($row = mysqli_fetch_array($query)){
					$search_result[] = $row['user_ID'];
				}
				$totalresult = $row['users'];
			ELSE:
				$err = "No record on file";
			ENDIF;
			
		ENDIF;
	ENDIF;

?>

<html lang="en">
<head>
   <?php include_once('includes/header.php'); ?>

	<script src="js/jquery.min.js"></script>
		
</head>
<body>

	<div id="wrapper">

		<?php include_once('includes/navigation.php'); ?>
		
		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h3 class="page-header">Select User ID</h3>
				</div><!-- /.col-lg-12 -->
			</div><!-- /.row -->

			<div class="row">
				<div class="col-lg-12">
					<div class="panel-body">
						<form action="member_search" method="post" enctype="multipart/form-data" id="UserForm">
							<div class="form-group input-group" style="width:300px">
								<span class="input-group-addon"><li class="fa fa-user"></li></span><input type="text" class="form-control" name="userID" id="userID" placeholder="User Search">
								<span class="input-group-btn"><input class="btn btn-default" type="submit" name="submitUID" id="submitUID" value="Search"></span>
							</div>
						</form>
					</div>	
				</div>
			</div><!-- /#page-wrapper -->
			<div class="row">
				<div class="col-lg-12">
					<div class="panel-heading">
						<div class="panel-body">
						
							<?php IF(isset($err)):?>
								<div class="panel-heading">
									<div class="alert alert-warning">
										<?= $err;?>
									</div>
								</div>
							<?php ENDIF; 
								IF(isset($search_query)):
									IF(!empty($search_result)): ?>
								
									<div class="panel-heading">
										Search Result
									</div>
									
									<!-- /.panel-heading -->
									<div class="panel-heading">	
										<div class="panel-body">
											<div class="dataTable_wrapper">
												<table class="table table-striped table-bordered table-hover" id="Search_Result_Table">
													<thead>
														<tr>
															<th>Name</th>
															<th>Email</th>
															<th>ID No.</th>
															<th>Action</th>
															<th>Status</th>
															<th>Remarks</th>
														</tr>
													</thead>
													<tbody>

														<?php 
														
														FOREACH ($search_result AS $result){
															$searchname = mysqli_fetch_assoc(mysqli_query($link, "SELECT fname, lname, email, user_ID, account_status, status_ID FROM ".DB_PREFIX."users WHERE user_ID='{$result}'"));
															$fullname = strtoupper($searchname['fname']." ".$searchname['lname']);
															$email = $searchname['email'];
															$userID = $searchname['user_ID'];
															$Dstatus = $searchname['account_status'];
															$statusID = $searchname['status_ID'];
															$remarks = mysqli_fetch_assoc(mysqli_query($link, "SELECT remarks FROM ".DB_PREFIX."account_status WHERE user_ID='{$userID}' AND status_ID='{$statusID}'"));
															
															IF(strpos($userID, 'TEMP') !== false):
																$Dstatus == 3;
																$status = "<span style='color:#ffa500'>For Activation</span>";
															ELSE:
																IF($Dstatus == 0): 
																	$status = "<span style='color:#0000ff'>Active</span>";
																ELSEIF($Dstatus == 1):
																	$status = "<span style='color:#FF0000'>Suspended</span>";
																ELSEIF($Dstatus == 2):
																	$status = "<span style='color:#FF0000'>Banned</span>";
																ENDIF;	
															ENDIF;
												
														?>

														<tr>
															<td><a href="gen_id_bridge?memberID=<?= $userID; ?>" data-toggle="tooltip" title="View Profile"><?=$fullname;?></a></td>
															<td><?=$email;?></td>
															<td><?=$userID;?></td>
															<td>
																<?php IF($Dstatus == 0 || $Dstatus == 1 || $Dstatus == 3): ?>
																
																		<button id="cards" class="btn btn-default" data-toggle="tooltip" title="new ID"><a href="gen_id_bridge?newID=<?=$userID;?>"><i class="fa fa-credit-card"></i></a></button>
																		
																	<?php IF($Dstatus == 0 || $Dstatus == 1): ?>
																		<?php IF($Dstatus == 0): ?>
																			<button id="suspend" class="btn btn-default" data-toggle="tooltip" title="Suspend ID"><a href="gen_id_bridge?suspendID=<?=$userID;?>"><i class="fa fa-exclamation-triangle"></i></a></button>
																		<?php ELSEIF($Dstatus == 1): ?>
																			<button id="unsuspend" class="btn btn-default" data-toggle="tooltip" title="Unsuspend ID"><a href="gen_id_bridge?unsuspendID=<?=$userID?>&status=<?=$Dstatus?>"><i class="fa fa-exclamation-triangle"></i></a></button>
																		<?php ENDIF; ?>
																		<button id="ban" class="btn btn-default" data-toggle="tooltip" title="Ban ID"><a href="gen_id_bridge?banID=<?=$userID;?>"><i class="fa fa-ban"></i></a></button>
																		<button id="loghistory" class="btn btn-default" data-toggle="tooltip" title="Log History"><a  href="gen_id_bridge?history=<?=$userID;?>"><i class="fa fa-history"></i></a></button>
																	<?php ELSEIF($Dstatus == 3): ?>

																		<button id="suspend" class="btn btn-default disabled"><i class="fa fa-exclamation-triangle"></i></button>
																		<button id="ban" class="btn btn-default disabled"><i class="fa fa-ban"></i></button>
																		<button id="loghistory" class="btn btn-default disabled" data-toggle="tooltip" title="Log History"><i class="fa fa-history"></i></button>
																	<?php ENDIF; ?>

																<?php ELSEIF($Dstatus == 2): ?>
																	<button id="cards" class="btn btn-default disabled"><i class="fa fa-credit-card"></i></button>
																	<button id="suspend" class="btn btn-default disabled"><i class="fa fa-exclamation-triangle"></i></button>
																	<button id="ban" class="btn btn-default disabled"><i class="fa fa-ban"></i></button>
																	<a  href="gen_id_bridge?history=<?=$userID;?>"><button id="loghistory" class="btn btn-default" data-toggle="tooltip" title="Log History"><i class="fa fa-history"></i></button></a>

																<?php ENDIF; ?>
															</td>
															<td><?=$status?></td>
															<td>
																<?php
																	IF($remarks['remarks'] != ""):
																	
																	$countremarks = strlen($remarks['remarks']);
																		IF($countremarks > 15):
																		
																			echo substr($remarks['remarks'], 0, 10); ?><a href="javascript: null(void)" data-toggle="modal" data-target="#<?=$userID?>"> - Read More...</a>
																		
																			<div class="modal fade" id="<?=$userID?>" tabindex="-1" role="dialog" aria-labelledby="Remarkmod<?=$userID?>" aria-hidden="true">
																				<div class="modal-dialog">
																					<div class="modal-content">
																						<div class="modal-header">
																							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
																							<h4 class="modal-title" id="Remarkmod<?=$userID?>">Change Account Status Remarks</h4>
																						</div>
																						<div class="modal-body">
																							<?=$remarks['remarks']?>
																						</div>
																					</div>
																				</div>
																			</div>
																	<?php
																		ELSE:
																			echo $remarks['remarks'];
																		ENDIF;
																	ENDIF;
																?>
															</td>												
														</tr>
													
														<?php
														}
														?>
													</tbody>
												</table>
											</div>
										</div>
									</div>	
							<?php
									ENDIF;
								ENDIF;
							?>
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>
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
                $('#Search_Result_Table').DataTable({
                        responsive: true
                });	
				//TOOLTIP
				$('[data-toggle="tooltip"]').tooltip(); 
            });
        </script>
		

</body>
</html>