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
									<div style="margin:0 auto;"><img src="images/thankyou.png" /></div>
									<h4><span class="CardCheck" id="CardCheck" name="CardCheck"></span></h4>
								</div>
								
							
							<?php IF(isset($_SESSION['user_id'])): ?>
								<div name="activatefield" id="activatefield">
									<p class="text-primary text-center">Your account is already activated!</p>
								</div>
							<?php ELSEIF(isset($_SESSION['act_ID'])): ?>
								<div name="activatefield" id="activatefield">
									<p style="color:#1d52ff">Please enter card number to activate your account.</p>
									<form name="activate" id="activate">
										<fieldset>
											<div class="form-group">
												<div>Temporary ID Number</div>
												<div class="form-group">
													<input class="form-control" name="tempID" type="text" id="tempID" readonly value="<?= $_SESSION['act_ID']; ?>" />
												</div>
											</div>
											<div class="form-group">
												<div id="6u 12u(xsmall)">10 digit CEC Card Number<span style="color:#FF0000">*</span></div>
												<div id="6u 12u(xsmall)">
													<input class="form-control" name="CardID" type="text" id="CardID" value="" Placeholder="Enter Card ID No." onkeypress="return isNumberKey(event)" autofocus /> 
												</div>
											</div>
											<div class="form-group">
												<input type="submit" class="btn btn-lg btn-success btn-block" value="Activate" id="Activate" name="Activate" />
											</div>
										</fieldset>
									</form>
								</div>
								
							<?php ELSE: ?>
							
								<div name="activatefield" id="activatefield">
								<p style="color:#1d52ff">Please complete the following fields to activate your account.</p>
									<form name="activate" id="activate">
										<fieldset>
											<div class="form-group">
												<div id="6u 12u(xsmall)">Temporary ID Number<span style="color:#FF0000">*</span></div>
												<div id="6u 12u(xsmall)">
													<input class="form-control" name="tempID" type="text" id="tempID" Placeholder="Your Temporary Account No." value="" autofocus />
												</div>
											</div>
											<div class="form-group">
												<div id="6u 12u(xsmall)">10 digit CEC Card Number<span style="color:#FF0000">*</span></div>
												<div id="6u 12u(xsmall)">
													<input class="form-control" name="CardID" type="text" id="CardID" value="" Placeholder="New CE Card ID No." onkeypress="return isNumberKey(event)" /> 
												</div>
											</div>
											<div class="form-group">
												<input type="submit" class="btn btn-lg btn-success btn-block" value="Activate" id="Activate" name="Activate" />
											</div>
										</fieldset>
									</form>
								</div>
								<p>Forgot you Temporay ID No? <a href="login" style="text_decoration:underline;color:#0000FF;">Login</a> to recover.</p>

								<p>Don't have account yet? <a href="../signup/register" style="text_decoration:underline;color:#0000FF;">Register</a></p>
							<?php ENDIF; ?>

								<div name="activated" id="activated" style="display:none">
									<span id="account_validate" name="account_validate"></span>
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

        <!-- Custom Theme JavaScript -->
        <script src="js/startmin.js"></script>
		
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
					if(TID == '' || TID == 0 ){
						$('#CardCheck').html("<div class='alert alert-danger'>Please enter Temporary ID number.</div>");
					}
					else if(CID == '' || CID == 0 ){
						$('#CardCheck').html("<div class='alert alert-danger'>Please enter CE card number.</div>");
					}
					else{
						$.post('trigger/activatecard', { 'CID': CID, 'TID': TID, checkcard: 1}, function(data) {
							$('#CardCheck').html(data);
						});
					}
				});
            });
		</script>
	</body>
</html>