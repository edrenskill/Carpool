<?php
include '../settings/connect.php';
if (session_id() == '') { page_protect(); } // START SESSIONS

$_SESSION['history'] = 1;
	IF(isset($_SESSION['frommyaccount'])):
		unset($_SESSION['frommyaccount']);
	ENDIF;

// Get Details
$myname = mysqli_fetch_array(mysqli_query($link, "SELECT CONCAT(fname, ' ',lname) AS fullname, photo, user_ID FROM " . DB_PREFIX . "users WHERE ID = " . $_SESSION['user_id'] . ""));
IF ($myname['photo'] == "") {
    $avatar = "avatar.jpg";
} ELSE {
    $avatar = "members/" . $_SESSION['user_id'] . "/" . $myname['photo'];
}

$act_ID = $_SESSION['act_ID'];

// Get Account
IF (Registered()):
    $myhistory = mysqli_query($link, "SELECT ending_balance, terminal_ID, transaction_code, debit, credit, transaction_date, transaction_time FROM " . DB_PREFIX . "account WHERE user_ID = '" . $act_ID . "'");
ENDIF;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include_once("includes/header.php"); ?>
    </head>
    <body>

<div id="wrapper">

<?php include_once("includes/nav.php"); ?>

 <!-- Page Content -->
	<div id="page-wrapper">
		<div class="container-fluid">
			<div class="row">
			  <div class="col-lg-12">
				<h1 class="page-header">Transaction History</h1>
			  </div> <!-- /.col-lg-12 -->
			</div>
				<div class="col-lg-12" >
					<h3 class="page-header">Log History - Period of: <strong style="color:#0000FF"><?= date("F d, Y", strtotime($cutoff_start)); ?> </strong>To: <strong  style="color:#0000FF"><?= date("F d, Y", strtotime($cutoff_end)); ?></strong></h3>
					<h4 class="page-header"><strong style="color:#0000FF"><?= isset($_SESSION['terminal_ID']) ? $_SESSION['terminal_name'] : "All Terminal"; ?></strong></h4>
				</div><!-- /.col-lg-12 -->
				<div style="float:left"><a href="<?=($stat==1)?'account_details' : 'account_details'; ?>"><h3><i class="fa fa-toggle-left fa-fw"></i>Back</a></h3></div>
				<div class="col-lg-12" >
					<div class="panel-heading">
						<div class="page-header" style="float:left">
							Select Date Range<?php
							IF (isset($_SESSION['error'])): echo $_SESSION['error'];ENDIF;
								unset($_SESSION['error']);
													
								$datemin = date('Y-m-d',strtotime("-12 month"));
								$datemax = date('Y-m-d',strtotime("+1 day"));
							?>
							<form role="form" name="cutoff" id="cutoff" method="post" action="setcutoffdate2">
								<div class="form-group input-group" style="width:300px">
									<span class="input-group-addon"><li class="fa fa-calendar-minus-o"> From: </li></span><input type="date" class="form-control" name="selectdate1" id="selectdate1" min="<?=$datemin;?>" max="<?=$datemax;?>"><span class="input-group-addon"><li class="fa fa-calendar-plus-o"> To: </li></span><input type="date" class="form-control" name="selectdate2" id="selectdate2" min="<?=$datemin;?>" max="<?=$datemax;?>">
									<span class="input-group-btn"><button class="btn btn-default" type="submit" name="setcutdate"><i class="fa fa-arrow-right"></i></button></span>
								</div>
							</form>
						</div>
					</div>
				</div>

		<div class="row">
		   <div class="col-lg-12">
				<div class="panel panel-default">
					    <div class="panel-heading">Transactions</div>
								<!-- /.panel-heading -->
					<div class="panel-body">
						<?php IF (Registered()) : ?>
						<div class="table-responsive">
						
							<table class="table">
								<thead>
									<tr>
										<th>Detail</th>
										<th>Date</th>
										<th>Amount</th>
									</tr>
								</thead>
								<tbody>
								
								<?php
								WHILE($mybalance = mysqli_fetch_array($myhistory)){
									$tcode = $mybalance['transaction_code'];
									$transcodes = mysqli_fetch_array(mysqli_query($link, "SELECT description FROM " . DB_PREFIX . "transactioncode WHERE transaction_code = '" . $tcode . "'"));
									$tid = $mybalance['terminal_ID'];
									$prevdate = date_create($prevbalance['transaction_date'] . " " . $prevbalance['transaction_time']);
									$tdate = date_create($mybalance['transaction_date'] . " " . $mybalance['transaction_time']);
								?>
								
									<tr>
										<td><?=$transcodes['description']?></td>
										<td><?= date_format($tdate, 'F j, Y l g:ia'); ?></td>
										<?php IF ($mybalance['credit'] != 0) { ?>
										<td>PHP<?=number_format($mybalance['credit'], 2, ".", ",");?></td>
										<?php } ELSEIF ($mybalance['debit'] != 0) { ?>
										<td>PHP<?=number_format($mybalance['debit'], 2, ".", ",");?></td>
										<?php } ?>
									</tr>
								<?php
									}
								?>
								</tbody>
							</table>
							<h5>NOTE:
							<small>We only show up to 1 year of transation history. You can request to the office for the full history report.</small>
							</h5>
						</div>
						<?php 
						ENDIF; // close member level  
						?>
					</div>
				</div>
			</div>
		</div>
		<?php include_once("includes/footer.php"); ?>
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