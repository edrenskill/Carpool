<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	if(!isset($_SESSION['ADM'])): header('location: login'); ENDIF;
	IF($_SESSION['terminal_ID']=="" || $_SESSION['terminal_ID']=="0" ): header("location: select_terminal"); ENDIF;
	
	$_SESSION['fromidcard'] = 1;
	IF(isset($_SESSION['fromremitted'])):
		unset($_SESSION['fromremitted']);
	ELSEIF(isset($_SESSION['frompidcardinventory'])):
		unset($_SESSION['frompidcardinventory']);
	ELSEIF(isset($_SESSION['fromdrivertripreport'])):
		unset($_SESSION['fromdrivertripreport']);
	ELSEIF(isset($_SESSION['frompayout'])):
		unset($_SESSION['frompayout']);
	ENDIF;
	
?>

<html lang="en">
	<head>
	   <?php include_once('includes/header.php'); ?>

	   <script type="text/javascript" src="js/jquery.min.js"></script>
	</head>
	<body>


		<div id="wrapper">

			<?php
				include_once('includes/navigation.php'); 
			?>
			<div id="page-wrapper">
				<div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">ID Card System Registration</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Designate ID Cards for terminal <span style='color:#0000FF;'><?=$_SESSION['terminal_name'];?></span> ID: <span style='color:#0000FF;'><?=$_SESSION['terminal_ID']?></span>
								<span style="float:right;"><a href="select_terminal"><i class="fa fa-road"></i>Select different terminal</a></span>
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="dataTable_wrapper">
										
									<div class="col-lg-4" style="width:275">
										<div class="panel panel-primary">
											<div class="panel-heading">
												ID Card Registration Panel
											</div>
											<div class="panel-body">
												<div class="panel-body" id="enterNewID">
													<fieldset>
														<form name="cardform" id="cardform">
															<div id="NewNumberChecked" name="NewNumberChecked"></div>
															<div class="form-group">
																
																<label>Select Card Type</label>
																<div class="radio">
																	<label>
																		<input type="radio" name="CardType" id="CardType" value="1" <?= ($_SESSION['CARDTYPE'] == 1)? "checked" : ""; ?>>Regular Card
																	</label>
																</div>
																<div class="radio">
																	<label>
																		<input type="radio" name="CardType" id="CardType" value="2" <?= ($_SESSION['CARDTYPE'] == 2)? "checked" : ""; ?>>Discounted 
																		<span style="font-size:9px" class="text-success">(Student/Senrior/PWD)</span>
																	</label>
																</div>
										
																<input type="hidden" id="TID" name="TID" value="<?= $_SESSION['terminal_ID']; ?>"/>
																<input class="form-control" placeholder="New ID Card" id="newmemberID" name="newmemberID" autofocus onkeypress="return isNumberKey(event)" />
															</div>
															<button type="submit" class="btn btn-lg btn-success btn-block" id="registernew" name="registernew"/>Register Card No.</button>			
															<?php unset($_SESSION['CARDTYPE']);?>
														</form>
													</fieldset>
												</div>
											</div>
											<div class="panel-footer">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div><!-- /#page-wrapper -->
		</div>
	</div>

<!-- jQuery -->
<!-- <script src="js/jquery.min.js"></script> -->

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
				//Assign
				$('#cardform').submit(function (e) {

					// Prevent form submission which refreshes page
					e.preventDefault();

					// Serialize data
					var formData = $(this).serialize();

					var nID = $("#newmemberID").val();
					var tID = $("#TID").val();
					var IDcount = $("#newmemberID").val().length;
					var ncardtype = document.getElementsByName('CardType');
					var nCard = 0;

					for (var i = 0, length = ncardtype.length; i < length; i++) {
						if (ncardtype[i].checked) {
							// do whatever you want with the checked radio
							var nCard = ncardtype[i].value;
						}
					}
					
					if(nCard == 0){ 
						$('#NewNumberChecked').html("<div class='alert alert-warning'>Please Select Card Type.</div>");
					}

					else if(nID == ''|| nID == 0 || IDcount < 10 || IDcount > 10){
						$('#NewNumberChecked').html("<div class='alert alert-warning'>Please enter valid Card Number or Card number must be at least 10 but not more than 10 digits long.</div>");
						$('#newmemberID').val('');
						$("#newmemberID").focus();
					}
					else{
						$.post('trigger/exec', { 'nID': nID, 'tID': tID, 'cType':nCard, registercard: 1}, function(data) {
							var checkdata = data;

							if(checkdata == 1){
								$('#NewNumberChecked').html("<p style='color:#FF0000'>Card Number already exist and ready to assign!</p>");
								$('#newmemberID').val('');
								$("#newmemberID").focus();
							}
							else if(checkdata == 2){
								$('#NewNumberChecked').html("<p style='color:#FF0000'>Card Number already exist and already assigned to other member.</p>");
								$('#newmemberID').val('');
								$("#newmemberID").focus();
							}
							else{
								$('#NewNumberChecked').html(data);
								$('#newmemberID').val('');
								$("#newmemberID").focus();
							}
						});
					}
				});
            });
        </script>
</body>
</html>
