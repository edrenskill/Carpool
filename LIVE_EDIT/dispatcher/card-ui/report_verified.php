<?php 
	include '../settings/connect.php';
	session_start();
?>

<!DOCTYPE HTML>
<html>
	<head>
		<!-- Bootstrap Core CSS -->
		<link href="../dispatcher/css/bootstrap.min.css" rel="stylesheet">

		<!-- MetisMenu CSS -->
		<link href="../dispatcher/css/metisMenu.min.css" rel="stylesheet">

		<!-- Timeline CSS -->
		<link href="../dispatcher/css/timeline.css" rel="stylesheet">

		<!-- Custom CSS -->
		<link href="../dispatcher/css/startmin.css" rel="stylesheet">

		<!-- Morris Charts CSS -->
		<link href="../dispatcher/css/morris.css" rel="stylesheet">

		<!-- Custom Fonts -->
		<link href="../dispatcher/css/font-awesome.min.css" rel="stylesheet" type="text/css">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	
	<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<!-- Top Navigation: Left Menu -->
        <ul class="nav navbar-nav navbar-left navbar-top-links">
            <li><h3><a href="../"><i class="fa fa-home fa-fw"></i> Carpool Express Co.</a></h3></li>
        </ul>
	</nav>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default" style="width:350px">
                    <div class="panel-body" >
						<?php IF(isset($_SESSION['act_ID'])): ?>
							<div class="form-group">
								<div style="margin:0 auto;"><img src="images/thankyou.png" /></div>
								<h4><span class="CardCheck" id="CardCheck" name="CardCheck"></span></h4>
							</div>
							<div name="activatefield" id="activatefield">
							<h3><p style="color:#1d52ff">Your report has been sucessfully sumitted.</p></h3>
							<h4>For more information, please feel free to contact us.</h4>
								
							</div>
							<div name="activated" id="activated" style="display:none">
								<span id="account_validate" name="account_validate"></span>
							</div>
						<?php 
							unset($_SESSION['act_ID']);
							ELSE: ?>
							<p>Please <a href="login" style="text_decoration:underline;color:#0000FF;">Login</a> or <a href="signup" style="text_decoration:underline;color:#0000FF;">Register</a></p>
						<?php 
							ENDIF;
						?>
					</div>
				</div>
			</div>
        </div>
    </div>
    <!-- jQuery -->
		<script src="../dispatcher/js/jquery.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="../dispatcher/js/bootstrap.min.js"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="../dispatcher/js/metisMenu.min.js"></script>

        <!-- Custom Theme JavaScript -->
        <script src="../dispatcher/js/startmin.js"></script>
		
		<script type="text/javascript">
			// NUMBERS ONLY
            function isNumberKey(evt)
            {
                var charCode = (evt.which) ? evt.which : event.keyCode
                if (charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;
            }
			
			
			$(document).ready(function() {

				//Balance Checking - dispatcher
				$('#activate').submit(function (e) {

					// Prevent form submission which refreshes page
					e.preventDefault();

					// Serialize data
					var formData = $(this).serialize();

					var CID = $("#CardID").val();
					var TID = $("#tempID").val();
					if(CID == '' || CID == 0 ){
						$('#CardCheck').html("<div class='alert alert-danger'>Please Enter Card Number.</div>");
					}
					else{
						$.post('trigger/readcard', { 'CID': CID, 'TID': TID, checkcard: 1}, function(data) {
							$('#CardCheck').html(data);
						});
					}
				});
            });
		</script>
	</body>
</html>