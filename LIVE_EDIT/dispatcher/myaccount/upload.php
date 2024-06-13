<?php 
	require_once '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	date_default_timezone_set('Asia/Manila'); // Timezone
	$currentdate = date('Y-m-d H:i:s');	

	$member_ID = $_SESSION['act_ID'];

	$personal_info = mysqli_fetch_assoc(mysqli_query($link, "SELECT  photo FROM ".DB_PREFIX."users WHERE user_ID = '{$member_ID}'"));
	
?>

<!DOCTYPE HTML>
<!-- STOP & GO Commuter Plus -->
<html>
	<head>
		<?php include_once("includes/header.php"); ?>
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery.form.min.js"></script>
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

		<!-- Navigation -->
		<?php include_once("includes/nav.php");?>
		
		 <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <h4 class="page-header">Profile Photo</h4>
                        </div>
                        <!-- /.col-lg-12 -->
                    </div>
	
	
					<div class="container">
						<div class="row">
							<div class="col-lg-4 col-md-offset-4">
								<div class="panel panel-default">
									<div class="panel-heading"> Upload picture </div>
									<div class="panel-body">
										<form action="process_pic_upload.php" method="post" enctype="multipart/form-data" id="MyUploadForm">
											<input name="image_file" id="imageInput" type="file" />
											<br/>
											<input class="btn btn-success" type="submit"  id="submit-btn" value="Upload" />
											<img src="images/loading.gif" id="loading-img" style="display:none;" alt="Please Wait"/>
										</form>
									</div>
									<div class="panel-footer" id="output"></div>
								</div>
							</div>
						</div>
						<?php include_once("includes/footer.php"); ?>
					<!-- Footer -->
					</div>
				</div>
			</div>
		</div>
    <!-- jQuery -->
        <!-- Bootstrap Core JavaScript -->
        <script src="js/bootstrap.min.js"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="js/metisMenu.min.js"></script>

        <!-- Custom Theme JavaScript -->
        <script src="js/startmin.js"></script>
	</body>
</html>