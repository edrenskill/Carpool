<?php
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS	
	
	IF(!isset($_SESSION['IDusers'])):
		header("location: member_search");

	ENDIF;
	
	
	
$member_ID = $_SESSION['IDusers'];
$personal_info = mysqli_query($link, "SELECT email, fname, mname, lname, suffix,  user_ID FROM ".DB_PREFIX."users WHERE user_ID = '{$member_ID}'");


?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include_once('includes/header.php'); ?>
	</head>
    <body>

        <div id="wrapper">

            <?php include ('includes/navigation.php'); ?>

            <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4">

                            <h1 class="page-header">Details</h1>
							
                        </div><!-- /.col-lg-12 -->
                    </div><!-- /.row -->
					
					<div class="panel panel-default" style="width:350px">
                        <div class="panel-heading">
							<span style="float:left;"><h4>User Profile</h4></span>
							<div style="clear:both;"></div>
	
				           <?php
                                 WHILE ($user = mysqli_fetch_array($personal_info)) {
									 
							?>
							<div class="form-group">								
								<h4><span class="NewCardCheck" id="NewCardCheck" name="NewCardCheck"></span></h4>
							</div>
						     <div class="12u(xsmall)" style="float:left;width:50%;" id="name-display">
                                    <div class="12u(xsmall)" style="float:left; width:90px;">Name</div>
                                    <div class="6u(xsmall)" style="float:left; ">
                                        <strong>
                                            <span class="fname"><?= $user['fname']; ?></span>
                                            <span class="lname"><?= $user['lname']; ?></span>
                                            <?php
                                            IF ($user['suffix'] != ""): echo ", " . $user['suffix'];
                                            ENDIF;
											
                                            ?>
                                        </strong>
										<div style="clear:both;"></div>
                                    </div>								
                                </div>
								 <?php  }  ?>
								<div style="clear:both;"></div>
								<?php IF(isset($member_ID)): ?>
					<div name="activatefield" id="activatefield">
						<div name="activatefield" id="activatefield">
							<p style="color:#1d52ff">Please enter new card number to change your old id number.</p>
								<form name="activate" id="activate">
									<fieldset>
										<div class="form-group">
											<div id="6u 12u(xsmall)">Old ID Number<span style="color:#FF0000">*</span></div>
											<div id="6u 12u(xsmall)">
												<input class="form-control" name="oldId" type="text" id="oldId" readonly value="<?= $member_ID; ?>" />
											</div>
										</div>
										<div class="form-group">
											<div id="6u 12u(xsmall)">10 digit CEC New Card Number<span style="color:#FF0000">*</span></div>
											<div id="6u 12u(xsmall)">
												<input class="form-control" name="newCardID" type="text" id="newCardID" value="" Placeholder="Enter Card ID No." onkeypress="return isNumberKey(event)" autofocus /> 
											</div>
										</div>
										<div class="form-group">
											<input type="submit" class="btn btn-lg btn-success btn-block" value="Submit" id="Submit" name="Submit" />
										</div>
									</fieldset>
								</form>
							</div>
						</div>
							<?php  ENDIF  ?>
							</div>
                        </div><!-- /.col-lg-12 -->
                    </div>
					<div name="activated" id="activated" style="display:none">
						<span id="account_validate" name="account_validate"></span>
					<?php
						IF(isset($_SESSION['email'])): unset($_SESSION['email']); ENDIF;
						IF(isset($_SESSION['Fname'])): unset($_SESSION['Fname']); ENDIF;
					?>
					</div>
					
					<!-- /.row -->
                </div><!-- /.container-fluid -->
            </div><!-- /#page-wrapper -->

        </div><!-- /#wrapper -->

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

					var NCID = $("#newCardID").val();
					var OID = $("#oldID").val();
					if(NCID == '' || NCID == 0 ){
						$('#NewCardCheck').html("<div class='alert alert-danger'>Please Enter Card Number.</div>");
					}
					else{
						$.post('trigger/readcard', { 'NCID': NCID, 'OID': OID, checknewcard: 1}, function(data) {
							$('#NewCardCheck').html(data);
						});
					}
				});
            });
		</script>
		


    </body>
</html>
