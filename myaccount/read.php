<?php
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	foreach($_GET as $key => $value) { $data[$key] = filter($value); }
	
	$reportID = $data['reportID'];
	$subject = $data['subject'];
	mysqli_query($link, "UPDATE ".DB_PREFIX."reporting SET report_status=1 WHERE report_ID='{$reportID}' AND reply=1");
	$repstatus = mysqli_fetch_assoc(mysqli_query($link, "SELECT report_status FROM ".DB_PREFIX."reporting WHERE report_ID='{$reportID}' AND reply=0"));
	$all_message = mysqli_query($link, "SELECT user_ID, report_ID, reason, date_time, report_status, reply FROM ".DB_PREFIX."reporting WHERE report_ID='{$reportID}' ORDER BY ID ASC");
?>

<!DOCTYPE HTML>
<!-- STOP & GO Commuter Plus -->
<html>
	<head>
		<?php include_once("includes/header.php"); ?>
	</head>
	<body>
		<div id="wrapper">

			<?php include_once("includes/nav.php"); ?>
			<div id="page-wrapper">
				<div class="row">
                    <div class="col-lg-12">
                        <h4 class="page-header">Report ID: <span class="text-primary"><?=$reportID;?></span> Subject: <span class="text-primary"><?=$subject;?></span>
						<span class="pull-right"><a href="messaging"><i class="fa  fa-inbox"></i>Inbox</a></span></h4>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-10">
						<div class="panel-body">
							<ul class="chat">
							
								<?php
									WHILE($message = mysqli_fetch_array($all_message)){
										
										
										$reply = $message['reply'];
										$datetime = $message['date_time'];
										$content = $message['reason'];
										IF($reply==0 || $reply==2):
											$userID = $_SESSION['act_ID'];
										ELSE:
											$userID = $message['user_ID'];
										ENDIF;
										
										$sender = mysqli_fetch_array(mysqli_query($link, "SELECT fname, lname, user_ID, photo FROM ".DB_PREFIX."users WHERE user_ID='{$userID}'"));
										$fullname = strtoupper($sender['fname']." ".$sender['lname']);
										IF($sender['photo'] == ""){ 
											$avatar = "../myaccount/images/avatar2.jpg"; 
										}ELSE{ 
											$avatar = "../myaccount/members/".$userID."/".$sender['photo'];
										}

										IF($reply==0  || $reply==2):
											$headpos = '<li class="left clearfix"><span class="chat-img pull-left">';
											$contentpos =  '<strong class="primary-font"><a href="gen_id_bridge?memberID='.$userID.'">'.$fullname.'</a></strong>
															<small class="pull-right text-muted">
																<i class="fa fa-clock-o fa-fw"></i> <strong>'.date('F j, Y l g:ia', strtotime($datetime)).'</strong>
															</small>';
										ELSE:
											$headpos = '<li class="right clearfix"><span class="chat-img pull-right">';
											$contentpos =  '<small class="text-muted">
																<i class="fa fa-clock-o fa-fw"></i> <strong>'.date('F j, Y l g:ia', strtotime($datetime)).'</strong>
															</small>
															<strong class="pull-right primary-font">'.$fullname.'</strong>';
										ENDIF;

										IF($repstatus['report_status']==0): 
											$status = "<span class='text-danger'>Submitted</span>";
										ELSEIF($repstatus['report_status']==1): 
											$status = "<span class='text-warning'>Evaluating</span>";
										ELSE: 
											$status = "<span class='text-success'>Resolved</span>";
										ENDIF;
								?>
									<?=$headpos;?>
										<a href="gen_id_bridge?memberID=<?=$userID;?>"><img src="<?=$avatar;?>" alt="User Avatar" class="img-circle" height="42" width="42"/></a>
									</span>

									<div class="chat-body clearfix">
										<div class="header">
											<?=$contentpos;?>
										</div>
										<p><?=$content;?></p>
									</div>
								</li>
								<?php } ?>
								<span id="ReplyMessage" name ="ReplyMessage"></span>
							</ul>
						</div>
						<div class="panel-group" id="ReplyPanel">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h5 class="panel-title">
                                        <?=$status;?><span class="pull-right"><a data-toggle="collapse" data-parent="#ReplyPanel" href="#collapseReplypanel"><i class="fa fa-mail-reply"></i> Reply</a></span>
                                    </h5>
                                </div>
                                <div id="collapseReplypanel" class="panel-collapse collapse">
                                    <div class="panel-body">
										<form name="submit_reply" id="submit_reply">
											<div class="modal-body">
												<fieldset>
													<div class="form-group">
														<input name="adminavatar" id="adminavatar" type="hidden" value="<?=$avatar;?>"/>
														<input name="rID" id="rID" type="hidden" value="<?=$reportID;?>"/>
														<textarea class="form-control" name="content" id="content" rows="5" placeholder="Your Reply"></textarea>
													</div>
												</fieldset>
											</div>
											<div class="modal-footer">
												<button type="submit" class="btn btn-primary" value="Submit" id="submitreport" name="submitreport"><i class="fa fa-send"></i> Send</button>
											</div>
										</form>
									</div>
                                </div>
                            </div>
						</div>
                    </div>
				</div>
			</div>
			<?php include_once("includes/footer.php"); ?>
			<!-- Footer -->
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
		
		<script type="text/javascript">
			$(document).ready(function() {
                $('#reported').DataTable({
                    responsive: true
                });
            });

		
			$(document).ready(function() {

				//Balance Checking - dispatcher
				$('#submit_reply').submit(function (e) {

					// Prevent form submission which refreshes page
					e.preventDefault();

					// Serialize data
					var formData = $(this).serialize();

					var rID = $("#rID").val();
					var avatar = $("#adminavatar").val();
					var report_content = $("#content").val();
					
					if(report_content == ''){
						$('#ReplyMessage').html("<div class='alert alert-danger'>Please enter reply content</div>");
					}
					else{
						$.post('trigger/reportconcern', { 'rID': rID, 'avatar':avatar, 'reportcontent':report_content, 'replyreport': 1}, function(data) {
							$('#ReplyMessage').html(data);
							$('#content').val('');
							$('#subject').focus();
						});
					}
				});
            });
		</script>
	</body>
</html>