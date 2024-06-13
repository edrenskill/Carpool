<?php
	include '../settings/connect.php';
	session_start();
?>

<!DOCTYPE HTML>
<!-- STOP & GO Commuter Plus -->
<html>
	<head>
		<?php include_once("includes/header.php"); ?>
	</head>
	<body>
		<div id="wrapper">

		<?php 
		IF(isset($_SESSION['user_id'])): 
			include_once("includes/nav.php");
		ELSE: ?>

		<!-- Navigation -->
		<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<!-- Top Navigation: Left Menu -->
			<ul class="nav navbar-nav navbar-left navbar-top-links">
				<li><h3><a href="../"><i class="fa fa-home fa-fw"></i> Carpool Express Co.</a></h3></li>
			</ul>
		</nav>
		
		<?php ENDIF; ?>
		<div class="container">
			<div class="row">
				<div class="col-md-4 col-md-offset-4">
					<div class="login-panel panel panel-default" style="width:350px">
						<div class="panel-body" >
							
								<div class="form-group">
									<div style="margin:0 auto;"><img src="images/warning.png" /></div>
								</div>
								
							
							<?php 
								$stat = $_SESSION['account_status'];
								
								IF($stat == 1):
									$status = "<span class='text-warning'><strong>Suspended</strong></span>";
								ELSEIF($stat == 2):
									$status = "<span class='text-danger'><strong>Banned</strong></span>";
								ENDIF;
								IF(isset($_SESSION['act_ID'])): ?>
								<div name="activatefield" id="activatefield">
									<p style="color:#1d52ff">This account has been <?=$status?>!</p>
								</div>	
							<?php ENDIF; ?>
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

        <!-- Custom Theme JavaScript -->
        <script src="js/startmin.js"></script>

	</body>
</html>