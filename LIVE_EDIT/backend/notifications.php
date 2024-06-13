<?php
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	if(!isset($_SESSION['ADM'])): header('location: login'); ENDIF;
	$all_messages = mysqli_query($link, "SELECT subject, report_ID, reason, date_time, report_status FROM ".DB_PREFIX."reporting WHERE reply=0 ORDER BY ID DESC");
?>

<!DOCTYPE HTML>
<!-- STOP & GO Commuter Plus -->
<html>
	<head>
		<?php include_once("includes/header.php"); ?>
	</head>
	<body>
		<div id="wrapper">

		<?php include_once("includes/navigation.php"); ?>
		<div id="page-wrapper">
			<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Reporting</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
		
			<div class="row">
				<div class="col-lg-12">
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
											$count_reply = mysqli_num_rows(mysqli_query($link, "SELECT ID FROM ".DB_PREFIX."reporting WHERE report_ID='{$reportID}' AND reply=2"));
											$new_reply = mysqli_num_rows(mysqli_query($link, "SELECT ID FROM ".DB_PREFIX."reporting WHERE report_ID='{$reportID}' AND report_status=0 AND reply=2"));
									?>
                                        <tr>
                                            <td><?=$count;?></td>
                                            <td><a href="read?reportID=<?=$messages['report_ID'];?>&subject=<?=$subject?>"><?=$reportID;?></a></td>
                                            <td><?=date('F j, Y l g:ia', strtotime($datetime)); ?></td>
                                            <td><?=$count_reply;?> <?= ($new_reply!=0)? "<span class='badge badge-warning' id='InboxCount'><strong>".$new_reply."</strong></span>" : ""; ?></td>
											<td><?php IF($messages['report_status']==0): echo "<span class='text-danger'>New</span>"; ELSEIF($messages['report_status']==1): echo "<span class='text-warning'>Evaluating</span>"; ELSE: echo "<span class='text-success'>Resolved</span>"; ENDIF;?></td>
                                        </tr>
										<?php } ?>
                                    </tbody>
                                </table>
                            </div>
						</div>
					</div>
				</div>
			</div>
			<?php include_once("../myaccount/includes/footer.php"); ?>
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
		</script>
	</body>
</html>