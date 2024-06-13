<?php
	include '../settings/connect.php';
	if(session_id() == '') { session_start(); } // START SESSIONS
?>

<!DOCTYPE html>
<html lang="en">
    <head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>CEC Card Reporting - <?php echo TITLES; ?></title>

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
    <body>
	
	<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<!-- Top Navigation: Left Menu -->
        <ul class="nav navbar-nav navbar-left navbar-top-links">
            <li><a href="../"><i class="fa fa-home fa-fw"></i> Home Index</a></li>
        </ul>
	</nav>
	
	<div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"></h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">Lost Card Reporting</div>
				<div class="col-md-6 col-md-offset-3">
					<?php // Display error message
						if(!empty($_SESSION['error_msg']))  { echo $_SESSION['error_msg']; unset($_SESSION['error_msg']); }
					?>
				</div>
                <div class="panel-body">
                    
					
					
					
					<div class="col-md-4 col-md-offset-4">
						<div class="panel panel-default" style="border-color:#FFF;-webkit-box-shadow: 0 1px 1px rgba(0,0,0,0)">
							<div class="panel-body">
								<div style="margin:0 auto;"><img src="images/security.png" align="middle" /></div>
							</div>
							<div class="panel-body">
								<form role="form" action="reportprocess" method="post" name="report" id="report">
									<fieldset>
										<span id="card-result"  style="padding-left:10px;"></span>
										<div class="form-group input-group" style="width:320px">
											<span class="input-group-addon" id="cardicon"><li class="fa fa-cog"></li></span><input class="form-control" placeholder="10 Digit CEC Card Number" name="cnumber" id="cnumber" type="text" onkeypress="return isNumberKey(event)" autofocus />
										</div>
										
										<div class="form-group input-group" style="width:320px">
											<span class="input-group-addon" id="cardicon"><li class="fa fa-user"></li></span><input class="form-control" placeholder="User name/E-mail" name="usrname" id="usrname" type="username" />
										</div>
										<div class="form-group input-group" style="width:320px">
											<span class="input-group-addon" id="cardicon"><li class="fa fa-key"></li></span><input class="form-control" placeholder="Password" name="pwrd" id="pwrd" type="password" value="" />
										</div>
										<!-- Change this to a button or input when using this as a form -->
										<input type="submit" class="btn btn-lg btn-success btn-block" value="Report" id="Report" name="Report" style="width:320px" />
									</fieldset>
								</form>
							</div>
						</div>
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

            $(document).ready(function () {
                // IF USERNAME ENTERED
                $("#cnumber").blur(function (e) {
                    //removes spaces from username
                    $(this).val($(this).val().replace(/\s/g, ''));
                    var cnumber = $(this).val();
					
					if (cnumber.length < 4) {
                        $("#card-result").html('');
                        return;
                    }

                    if (cnumber.length >= 4) {
                        $("#card-result").html('<img src="../profile/images/loading.gif" />');
                        $.post('validate/validation', {'cnumber': cnumber}, function (data) {
                           $("#card-result").html(data); 
                        });
                    }
                });
            }); // END USERNAME/EMAIL
        </script>
    </body>
</html>
