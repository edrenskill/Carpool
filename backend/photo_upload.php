<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	if(!isset($_SESSION['ADM'])): header('location: login'); ENDIF;
	
	foreach($_GET as $key => $value) { $data[$key] = filter($value); }
	
	$member_ID = $data['member'];
	$_SESSION['newmember'] = $member_ID;
	$personal_info = mysqli_fetch_assoc(mysqli_query($link, "SELECT  photo FROM ".DB_PREFIX."users WHERE user_ID = '".$member_ID."'"));
	
?>

<html lang="en">
<head>
   <?php include_once('includes/header.php'); ?>

   <script type="text/javascript" src="../myaccount/js/jquery.min.js"></script>
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
						$("#output").html("<div class=\"alert alert-danger\">Please choose valid photo!</div>");
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
							$("#output").html("<div class=\"alert alert-danger\"><b>"+ftype+"</b> Unsupported file type!</div>");
							return false
					}
					
					//Allowed file size is less than 1 MB (1048576)
					if(fsize>1048576) 
					{
						$("#output").html("<div class=\"alert alert-danger\"><b>"+bytesToSize(fsize) +"</b> Too big Image file! <br />Please reduce the size of your photo using an image editor.</div>");
						return false
					}
							
					$('#submit-btn').hide(); //hide submit button
					$('#loading-img').show(); //hide submit button
					$("#output").html("");  
				}
				else
				{
					//Output error to older browsers that do not support HTML5 File API
					$("#output").html("<div class=\"alert alert-danger\">Please upgrade your browser, because your current browser lacks some new features we need!</div>");
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

		<?php include_once('includes/navigation.php'); ?>
		
		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">Member's Photo Upload</h1>
				</div><!-- /.col-lg-12 -->
			</div><!-- /.row -->
			
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						
						
						<div class="panel-body">
							<div style="margin:0 auto">
								<div class="col-lg-4" style="width:275">
									<div class="panel panel-green">
										<form action="components/upload/member_photo_upload.php" method="post" enctype="multipart/form-data" id="MyUploadForm">
											<div class="panel-heading">
												Select Photo
											</div>
											<div class="panel-body">
												<div class="form-group">
													<label>Enter Member ID Number</label>
													<input class="form-control" type="text" name="idnumber" id="idnumber" value="" PLACEHOLDER="ID Number">
													<p class="help-block">Example: 0012345678</p>
												</div>
												<div class="form-group">
													<label>Select Photo</label>
													<input name="image_file" id="imageInput" type="file" />
												</div>
												<div class="form-group">
													<input class="btn btn-default" type="submit"  id="submit-btn" name="submit-btn" value="Upload" />
													<img src="../myaccount/images/ajax-loader.gif" id="loading-img" style="display:none;" alt="Please Wait"/>
												</div>
											</div>
										</form>
										<div class="panel-footer">
											<div id="output">
												<span class="image avatar">
													<img src="../myaccount/images/avatar1.jpg" alt="" width="200"/>
												</span>
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

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="js/metisMenu.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="js/startmin.js"></script>

</body>
</html>
