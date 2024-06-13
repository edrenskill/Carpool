<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	if(!isset($_SESSION['ADM'])): header('location: login'); ENDIF;

	//foreach($_GET as $key => $value) { $data[$key] = filter($value); }
	
	$member_ID = $_SESSION['newmember'];
	
	$count_contact = mysqli_num_rows(mysqli_query($link, "SELECT ID FROM ".DB_PREFIX."contacts WHERE UID='{$member_ID}'"));
	IF($count_contact == 0):
		$_SESSION['memberID'] = $_SESSION['newmember'];
		header("location: user_profile");
	ENDIF;
	
	$check_photo = mysqli_query($link, "SELECT CONCAT(fname, ' ', lname) AS fullname, photo, signature FROM ".DB_PREFIX."users WHERE `user_ID` = '{$member_ID}'");
	$Photo_exist = mysqli_fetch_array($check_photo); //total records
	
?>

<html lang="en">
<head>
   <?php include_once('includes/header.php'); ?>

   <script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="../myaccount/js/jquery.form.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				var options = {
						target: '#output',   // target element(s) to be updated with server response 
						beforeSubmit: beforeSubmit,  // pre-submit callback 
						success: afterSuccess,  // post-submit callback 
						resetForm: true        // reset the form after successful submit 
					}; 

				 $('#MyUploadForm').submit(function() { 
						$(this).ajaxSubmit(options);  			
						// always return false to prevent standard browser submit and page navigation 
						return false; 
					}); 
			}); 

			function afterSuccess()
			{
				$('#submit-btn').show(); //hide submit button
				$('#loading-img').hide(); //hide submit button

			}

			//function to check file size before uploading.
			function beforeSubmit(){
				//check whether browser fully supports all File API
			   if (window.File && window.FileReader && window.FileList && window.Blob)
				{
					
					if( !$('#imageInput').val()) //check empty input filed
					{
						$("#output").html("Please choose valid photo!");
						return false
					}
					
					var fsize = $('#imageInput')[0].files[0].size; //get file size
					var ftype = $('#imageInput')[0].files[0].type; // get file type
					

					//allow only valid image file types 
					switch(ftype)
					{
						case 'image/png': case 'image/gif': case 'image/jpeg': case 'image/pjpeg':
							break;
						default:
							$("#output").html("<b>"+ftype+"</b> Unsupported file type!");
							return false
					}
					
					//Allowed file size is less than 1 MB (1048576)
					if(fsize>1048576) 
					{
						$("#output").html("<b>"+bytesToSize(fsize) +"</b> Too big Image file! <br />Please reduce the size of your photo using an image editor.");
						return false
					}
							
					$('#submit-btn').hide(); //hide submit button
					$('#loading-img').show(); //hide submit button
					$("#output").html("");  
				}
				else
				{
					//Output error to older browsers that do not support HTML5 File API
					$("#output").html("Please upgrade your browser, because your current browser lacks some new features we need!");
					return false;
				}
			}
			
			$(document).ready(function() {
				var options = {
						target: '#signature_output',   // target element(s) to be updated with server response 
						beforeSubmit: beforeSigSubmit,  // pre-submit callback 
						success: afterSigSuccess,  // post-submit callback 
						resetForm: true        // reset the form after successful submit 
					}; 

				 $('#MySignatureUploadForm').submit(function() { 
						$(this).ajaxSubmit(options);  			
						// always return false to prevent standard browser submit and page navigation 
						return false; 
					}); 
			}); 

			function afterSigSuccess()
			{
				$('#submit-sig-btn').show(); //hide submit button
				$('#loading-sig-img').hide(); //hide submit button

			}

			//function to check file size before uploading.
			function beforeSigSubmit(){
				//check whether browser fully supports all File API
			   if (window.File && window.FileReader && window.FileList && window.Blob)
				{
					
					if( !$('#SignatureInput').val()) //check empty input filed
					{
						$("#signature_output").html("Please choose valid photo!");
						return false
					}
					
					var fsize = $('#SignatureInput')[0].files[0].size; //get file size
					var ftype = $('#SignatureInput')[0].files[0].type; // get file type
					

					//allow only valid image file types 
					switch(ftype)
					{
						case 'image/png':
							break;
						default:
							$("#signature_output").html("<b>"+ftype+"</b> Unsupported file type!");
							return false
					}
					
					//Allowed file size is less than 1 MB (1048576)
					if(fsize>1048576) 
					{
						$("#signature_output").html("<b>"+bytesToSize(fsize) +"</b> Too big Image file! <br />Please reduce the size of your photo using an image editor.");
						return false
					}
							
					$('#submit-sig-btn').hide(); //hide submit button
					$('#loading-sig-img').show(); //hide submit button
					$("#signature_output").html("");  
				}
				else
				{
					//Output error to older browsers that do not support HTML5 File API
					$("#signature_output").html("Please upgrade your browser, because your current browser lacks some new features we need!");
					return false;
				}
			}

			//function to format bites
			function bytesToSize(bytes) {
			   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
			   if (bytes == 0) return '0 Bytes';
			   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
			   return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
			}

		</script>

</head>
<body>


	<div id="wrapper">

		<?php
			include_once('includes/navigation.php'); 
		?>		
			
		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">Member's ID Generation</h1>
				</div><!-- /.col-lg-12 -->
			</div><!-- /.row -->
			
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<?php IF (stripos($member_ID, "TEMP") !== false) { ?>
							<?= "Assign new ID Number to: <span style='color:#0000FF;font-weight:bold;'>".strtoupper($Photo_exist['fullname'])."</span>"; ?>
							<?php }ELSEIF ($Photo_exist['photo'] == "" || $Photo_exist['signature'] == ""){ ?>
							<?= "Upload photo or scanned signature for: <span style='color:#0000FF;font-weight:bold;'>".strtoupper($Photo_exist['fullname'])."</span>"; ?>
							<?php }ELSE{ ?>
							<?= "<a href='gen_id_bridge?memberID=".$member_ID."'><span style='color:#0000FF;font-weight:bold;'>".strtoupper($Photo_exist['fullname'])."</span></a>'s ID has been generated"; ?>
							<?php } ?>
						</div>
					
						<div class="panel-body">
							<div style="margin:0 auto">
								<?php
									IF (stripos($member_ID, "TEMP") !== false || $Photo_exist['photo'] == "" || $Photo_exist['signature'] == "") {
										include("components/memberdata.php");
									}ELSE{
										include("components/qr/qr.php");
										include("components/id_gen/id.php");
									}
								?>
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
				$("#assignnew").click(function(){
					var nID = $("#newmemberID").val();
					var oID = '<?= $member_ID; ?>';
					if(nID == ''|| nID == 0 ){
						$('#NewNumberChecked').html("<p style='color:red'>Please enter valid Member ID.</p>");
					}
					else{
						$.post('trigger/exec', { 'nID': nID, 'oID': oID, assignval: 1}, function(data) {
							var checkdata = data;
							
							if(checkdata == 1){
								$('#NewNumberChecked').html("<p style='color:red'>Card Number doesn't exist from our system!</p>");
							}
							else if(checkdata == 2){
								$('#NewNumberChecked').html("<p style='color:red'>Card Number is already assigned to other member.</p>");
							}
							else if(checkdata == 3){
								$('#NewNumberChecked').html("<p style='color:red'>Member Doesn't Exist!</p>");
							}
							else{
								$('#IDAssigned').html(data);
								$('#AssignResult').show('slow');
								$('#enterNewID').hide();
							}
						});
					}
				});
            });
        </script>
</body>
</html>
