<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	if(!isset($_SESSION['Dspr'])): header('location: login'); ENDIF;
	$terminal_ID = $_SESSION['terminal_ID'];
?>
<html lang="en">
<head>
   <?php include_once('includes/header.php'); ?>
</head>
<body>


	<div id="wrapper">

		<?php
			include_once('includes/navigation.php'); 
		?>

		 <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">

                            <h1 class="page-header">Cards Sale</h1>

                        </div><!-- /.col-lg-12 -->
                    </div><!-- /.row -->

					<div class="row">
						<div class="col-lg-4">
							<div class="panel panel-primary">
								<div class="panel-heading">
								</div>
								<div class="panel-body">
									<div class="panel-body" id="tapcard">
										<fieldset>
										<div id="tapcard1" name="tapcard1">
											<span class='text-center'><small>Tap  ID Card or enter Card number Manually</small></span>
											<form id="TapCardForm" autocomplete="off">
												<div class="form-group">
													<h4><span class="TapCardCheck" id="TapCardCheck" name="TapCardCheck"></span></h4>
												</div>
												<div class="form-group">
													<input class="form-control" placeholder="Tap Card" id="TapCardDisposed" name="TapCardDisposed" autofocus onkeypress="return isNumberKey(event)">
												</div>
												<button type="submit" class="btn btn-lg btn-success btn-block" id="TapCardSubmit" name="TapCardSubmit"/>Next</button>
											</form>
										</div>
										<div id="tapcard2" name="tapcard2" style="display:none">	
											<form id="PayCardForm" autocomplete="off">
												<div class="form-group">
													<h4><span class="PayCardCheck" id="PayCardCheck" name="PayCardCheck"></span></h4>
													<span class="SaleCardCheck" id="SaleCardCheck" name="SaleCardCheck"></span>
												</div>
												<div class="form-group">
													<input class="form-control" type="hidden" id="PayCardDisposed" name="PayCardDisposed">
													<input class="form-control" type="hidden" id="amount" name="amount">
												</div>
												<button type="submit" class="btn btn-lg btn-success btn-block" id="PayCardSubmit" name="PayCardSubmit"/>Sale</button>
												<button type="button" class="btn btn-lg btn-warning btn-block" onclick="$('#PayCardSubmit').show();$('#tapcard2').hide();$('#tapcard1').show('slow');$('#TapCardCheck').html('');$('#PayCardCheck').html('');$('#TapCardDisposed').val('');$('#TapCardDisposed').focus();"/>Back</button>
											</form>
										</div>
										</fieldset>
									</div>
								</div>
							</div>
						</div>
					</div>

                </div><!-- /.container-fluid -->
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

        <!-- Page-Level Demo Scripts - Tables - Use for reference -->
        <script>
			// NUMBERS ONLY
			function isNumberKey(evt)
			{
				var charCode = (evt.which) ? evt.which : event.keyCode
				if (charCode > 31 && (charCode < 48 || charCode > 57))
				return false;
				return true;
			}
		
            $(document).ready(function() {

				//Read Card Data
				$('#TapCardForm').submit(function (e) {

					// Prevent form submission which refreshes page
					e.preventDefault();

					// Serialize data
					var formData = $(this).serialize();

					var dID = $("#TapCardDisposed").val();
					var IDcount = $("#TapCardDisposed").val().length;
					if(dID == ''|| dID == 0 || IDcount < 10){
						$('#TapCardCheck').html("<span class='text-warning text-center'>Card ID is too short!</span>");
						$('#TapCardDisposed').val('');$('#TapCardDisposed').focus();
					}else{	
						$.post('trigger/cardpos', { 'dID': dID, 'TapCardSubmit': 1}, function(data) {
							$('#TapCardCheck').html(data);
						});
					}
				});
				
				$('#PayCardForm').submit(function (e) {

					// Prevent form submission which refreshes page
					e.preventDefault();

					// Serialize data
					var formData = $(this).serialize();

					var cardID = $("#PayCardDisposed").val();
					var amount = $("#amount").val();
					$.post('trigger/cardpos', { 'cardID' : cardID, 'amount' : amount, 'PayCardSubmit': 1}, function(data) {
						$('#PayCardCheck').html(data);
					});
				});
            });
        </script>
</body>
</html>