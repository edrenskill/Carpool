<?php
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	$user_ID = $_SESSION['act_ID'];
	$all_messages = mysqli_query($link, "SELECT subject, report_ID, reason, date_time, report_status FROM ".DB_PREFIX."reporting WHERE user_ID='{$user_ID}' AND reply=0 ORDER BY ID DESC");
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
                    <h1 class="page-header"></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
		
			<div class="row">
				<div class="col-lg-4">
					<div class="panel panel-default">
						<div class="panel-body" >
							
							<div class="form-group">
								<p class="text-primary text-center">Please tell us about your concern.</p>
								<p class="text-center text-warning"><small>(Drivers, System, etc.)</small></p>
							</div>

							<div name="activatefield" id="activatefield">
								<form name="submit_report" id="submit_report">
									<fieldset>
										<div class="form-group">
											<label>All fields are required</label>
											<input class="form-control" name="uID" id="uID" type="hidden" value="<?=$_SESSION['act_ID'];?>"/>
											<input class="form-control" name="subject" type="text" id="subject" value="" Placeholder="Subject" /> 
										</div>
										<div class="form-group">
                                            <textarea class="form-control" name="content" id="content" rows="5" placeholder="Your Complaint/Report"></textarea>
                                        </div>
										<div class="form-group">
											<input type="submit" class="btn btn-lg btn-success btn-block" value="Submit" id="submitreport" name="submitreport" />
										</div>
									</fieldset>
								</form>
							</div>
						</div>
						<div class="panel-footer">
							<h4><span class="ReportMessage" id="ReportMessage" name="ReportMessage"></span></h4>
						</div>
					</div>
				</div>
				
				<div class="col-lg-8">
					<div class="panel panel-default">
						<div class="panel-heading">Recent Reports</div>
						<div class="panel-body" >
							<div class="table-responsive">
                                <table class="table table-striped" id="reported">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Report ID</th>
                                            <th>Date</th>
											<th>No. of Replies</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
										$count = 0;
										WHILE ($messages = mysqli_fetch_array($all_messages)){
											$count += 1;
											$datetime = $messages['date_time'];
											$subject = $messages['subject'];
											$reportID = $messages['report_ID'];
											$count_reply = mysqli_num_rows(mysqli_query($link, "SELECT ID FROM ".DB_PREFIX."reporting WHERE report_ID='{$reportID}' AND reply=1"));
											$new_reply = mysqli_num_rows(mysqli_query($link, "SELECT ID FROM ".DB_PREFIX."reporting WHERE report_ID='{$reportID}' AND report_status=0 AND reply=1"));
									?>
                                        <tr>
                                            <td><?=$count;?></td>
                                            <td><a href="read?reportID=<?=$messages['report_ID'];?>&subject=<?=$subject?>"><?=$messages['report_ID'];?></a></td>
                                            <td><?=date('F j, Y l g:ia', strtotime($datetime)); ?></td>
                                            <td><?=$count_reply;?> <?= ($new_reply!=0)? "<span class='badge badge-warning' id='InboxCount'><strong>".$new_reply."</strong></span>" : ""; ?></td>
											<td><?php IF($messages['report_status']==0): echo "<span class='text-danger'>Submitted</span>"; ELSEIF($messages['report_status']==1): echo "<span class='text-warning'>Evaluating</span>"; ELSE: echo "<span class='text-success'>Resolved</span>"; ENDIF;?></td>
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
				$('#submit_report').submit(function (e) {

					// Prevent form submission which refreshes page
					e.preventDefault();

					// Serialize data
					var formData = $(this).serialize();

					var UID = $("#uID").val();
					var subject = $("#subject").val();
					var report_content = $("#content").val();
					if(subject == ''){
						$('#ReportMessage').html("<div class='alert alert-danger'>Please enter subject!</div>");
					}
					else if(report_content == ''){
						$('#ReportMessage').html("<div class='alert alert-danger'>Please enter content!</div>");
					}
					else{
						$.post('trigger/reportconcern', { 'UID': UID, 'subject': subject, 'reportcontent':report_content, 'reportdriver': 1}, function(data) {
							$('#ReportMessage').html(data);
							$('#subject').val('');
							$('#content').val('');
							$('#subject').focus();
						});
					}
				});
            });
		</script>
	</body>
</html>