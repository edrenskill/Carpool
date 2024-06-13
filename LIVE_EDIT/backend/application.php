<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	if(!isset($_SESSION['ADM'])): header('location: login'); ENDIF;

	foreach($_POST as $key => $value) { $data[$key] = filter($value); }	
	
	$_SESSION['application_type'] = "";
	$_SESSION['step'] = 1;

	IF(isset($_POST) && array_key_exists('membership_type',$_POST)):

	$err2 = "";

		IF(!isset($data['membertype']) || $data['membertype'] == ""):
			$err2 = "Please select type of membership";
		ELSE:
				$_SESSION['application_type'] = $data['membertype'];
				HEADER('Location: signup2');
				EXIT();
		ENDIF;
	ENDIF;
	$batch_select = mysqli_query($link, "SELECT  ID, batch_name FROM ".DB_PREFIX."member_batch");
?>

<html lang="en">
<head>
   <?php include_once('includes/header.php'); ?>
	<script type="text/javascript">
		function validateForm()
		{
			var membertype = document.forms['apptype'].elements['membertype'];
			// VALIDATE
			len=membertype.length-1;
			chkvaluem='';
			for(i=0; i<=len; i++){ if(membertype[i].checked)chkvaluem=membertype[i].value; }
			if(chkvaluem==''){ 
				$("#warning").show("slow");
				$("#output").html("Membership type must be selected."); 
				return false; 
			}
		}
	</script>
	<script src="js/jquery.min.js"></script>
</head>
<body>

	<div id="wrapper">

		<?php include_once('includes/navigation.php'); ?>
		
		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h3 class="page-header">Application Type</h3>
				</div><!-- /.col-lg-12 -->
			</div><!-- /.row -->

			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="col-lg-4" style="width:275">
								<div class="panel panel-default">
									
									<div id="warning" class="panel-heading" style="display:<?=($err2)? 'block' : 'none'; ?>">
										<div id="output" align="center" class="alert alert-warning">
											<?php IF(isset($err2) || $err2 != ''): echo $err2; ENDIF; ?>
										</div>
									</div>

									<div class="panel-body">
										<form action="application" method="post" enctype="multipart/form-data" id="apptype" name="apptype" onsubmit="return validateForm()">
											<div class="panel-body" id="enterNewID">
												<fieldset>
													<div class="form-group">
														<label>Membership Type<span style="color:#FF0000">*</span></label>
														<div class="radio">
															<label>
																<input class="radiobox" type="radio" name="membertype" id="membertype1" value="1" <?php IF(isset($field_member) && $field_member == '1'): echo 'checked="checked"'; ENDIF; ?> />Commuter
															</label>
														</div>
														<div class="radio">
															<label>
																<input class="radiobox" type="radio" name="membertype" id="membertype2" value="7" <?php IF(isset($field_member) && $field_member == '7'): echo 'checked="checked"'; ENDIF; ?> />Driver
															</label>
														</div>
														<div class="radio">
															<label>
																<input class="radiobox" type="radio" name="membertype" id="membertype3" value="8" <?php IF(isset($field_member) && $field_member == '8'): echo 'checked="checked"'; ENDIF; ?> />Vehicle Owner
															</label>
														</div>
														<div class="radio">
															<label>
																<input class="radiobox" type="radio" name="membertype" id="membertype3" value="10" <?php IF(isset($field_member) && $field_member == '10'): echo 'checked="checked"'; ENDIF; ?> />Vehicle Owner / Driver
															</label>
														</div>
														<div class="radio">
															<label>
																<input class="radiobox" type="radio" name="membertype" id="membertype3" value="2" <?php IF(isset($field_member) && $field_member == '2'): echo 'checked="checked"'; ENDIF; ?> />Dispatcher
															</label>
														</div>
													</div>
													<button class="btn btn-lg btn-success btn-block" type="submit"  id="membership_type" name="membership_type" value="membership_type" />Next</button>
												</fieldset>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div><!-- /#page-wrapper -->
		</div>
	</div>

	<!-- Bootstrap Core JavaScript -->
	<script src="js/bootstrap.min.js"></script>

	<!-- Metis Menu Plugin JavaScript -->
	<script src="js/metisMenu.min.js"></script>

	<!-- Custom Theme JavaScript -->
	<script src="js/startmin.js"></script>

</body>
</html>
