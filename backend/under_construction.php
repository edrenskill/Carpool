<?php
include '../settings/connect.php';
if (session_id() == '') {
    page_protect();
} // START SESSIONS
?>

<!DOCTYPE html>

<head>
    <?php include_once('includes/header.php'); ?>
</head>
<body>

    <div id="wrapper">

        <?php include ('includes/navigation.php'); ?>

        <!-- Page Content -->
        <div id="page-wrapper" style="witdh:100%;">
			<div style="margin: 156px 0 0 322px;"><img src="../images/under_construction.jpg"></div>
        </div>
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