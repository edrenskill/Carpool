<?php
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	// $sql = mysqli_query($link, "UPDATE ".DB_PREFIX."driver_status SET status=0 WHERE date_to<=now() AND status=1");
	IF(!isset($_SESSION['ADM']) && isset($_SESSION['user_id'])){ 
		header ('location: login');
	}ELSEIF(isset($_SESSION['ADM']) && !isset($_SESSION['user_id'])){ 
		unset($_SESSION['ADM']);
		header ('location: ../index');
	}ELSEIF(!isset($_SESSION['ADM']) && !isset($_SESSION['user_id'])){ 
		header ('location: ../index');
	}
	
	//IF(!isset($_SESSION['temp_amount'])): $_SESSION['temp_amount'] = 0; ENDIF;
	$_SESSION['temp_amount'] = '';
	IF(!isset($_SESSION['commutertable'])): $_SESSION['commutertable'] = 'none'; ENDIF;
	IF(!isset($_SESSION['terminaltable'])): $_SESSION['terminaltable'] = 'none'; ENDIF;
	IF(!isset($_SESSION['vehicletable'])): $_SESSION['vehicletable'] = 'none'; ENDIF;
	IF(!isset($_SESSION['loadingtable'])): $_SESSION['loadingtable'] = 'none'; ENDIF;
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
	<?php
		include_once('includes/dashboard_icon.php');
		
		echo "<div style='display:".$_SESSION['commutertable']."' id='members'>";
		include('components/members.php');
		echo "</div>";
		
		echo "<div style='display:".$_SESSION['terminaltable']."' id='terminals'>";
		include('components/terminals.php');
		echo "</div>";
		
		echo "<div style='display:".$_SESSION['vehicletable']."' id='vehicles'>";
		include('components/vehicles.php');
		echo "</div>";
		
		echo "<div style='display:".$_SESSION['loadingtable']."' id='loading'>";
		include('components/loading.php');
		echo "</div>";
	?>
	
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
<script src="js/dashboard.js"></script>
</body>
</html>
