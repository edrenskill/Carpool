<?php
require_once '../../settings/connect.php';
if(session_id() == '') { page_protect(); } // START SESSIONS

//Change Occupation
$user_ID = $_SESSION['user_id'];
$terminal_ID = $_SESSION['terminal_ID'];

IF(isset($_POST) && array_key_exists('updateremit',$_POST)):

	$DID = $_POST['ID'];
	$Rcode = $_POST['Rcode'];
	$Records_counter = 0;
	$Depslips = $_FILE['depslip']; 
	
	// Generate Reference No.
	$date = date('Y-m-d');
	$time = date('H:i:s');

	foreach( $DID as $key => $ID ) {

		$RemittanceCode = strtoupper($Rcode[$key]);
		$depslip = $Depslips[$key];

		IF(isset($RemittanceCode) && $RemittanceCode!=''):
			$Records_counter += 1;
			
			
			
			
			
			
			
			
				$jpeg_quality = 90; //jpeg quality
				$picsize = 400;
				$filepath = '../../dispatcher/depslip/'.$RemittanceCode.'/';
				$userpath='../../dispatcher/depslip/'.$RemittanceCode.'/';
					
				############ Configuration ##############
				$destination_folder		= $filepath; //upload directory ends with / (slash)

				if( ! file_exists($destination_folder)) {
					$mask=umask(0);
					mkdir($destination_folder, 0777);
					umask($mask);
				}

				##########################################

				//continue only if $_POST is set and it is a Ajax request
				if(isset($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){

					// check $_FILES['ImageFile'] not empty
					if(!isset($depslip) || !is_uploaded_file($depslip['tmp_name'])){
						die('Image file is Missing!'); // output error when above checks fail.
					}

					$valid_formats = array("pjpeg", "jpeg", "jpg", "png", "gif");

					if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
						$image_name = $depslip['name']; //file name
						$image_size = $depslip['size']; //file size

						if(strlen($image_name)){
							$ext = getExtension($image_name);
							
							if(in_array($ext,$valid_formats)){

								if($image_size<(1048*1048)){
									
									$deletefiles = glob("../../dispatcher/depslip/".$RemittanceCode."/*"); // get all file names
									foreach($deletefiles as $file){ // iterate files
									  if(is_file($file))
										unlink($file); // delete file
									}
									
									$actual_image_name =$RemittanceCode."_".str_replace(" ", "_", $image_name);
							
									$image_temp = $depslip['tmp_name']; //file temp

									if(move_uploaded_file($image_temp, $filepath.$actual_image_name)){
										$image=$actual_image_name;
										/*--------resize image-----------*/
										$image_size = 400; // the imageheight
										$filedir = '../../dispatcher/depslip/'.$RemittanceCode.'/';
										$thumbdir = $userpath; // the directory for the resized image
										$prefix = $RemittanceCode.'_'; // the prefix to be added to the original name
										$maxfile = '1048576';
										$mode = '0777';
										$userfile_name =str_replace(" ", "_", $depslip['name']);
										$userfile_tmp = str_replace(" ", "_", $depslip['tmp_name']);
										$userfile_size =$depslip['size'];
										$userfile_type = $depslip['type'];

										if (isset($depslip['name'])){
											$prod_img = $filedir.$actual_image_name;
											$prod_img_thumb = $thumbdir.$prefix.$userfile_name;
											move_uploaded_file($userfile_tmp, $prod_img);
											chmod ($prod_img, octdec($mode));
											$sizes = getimagesize($prod_img);
											$aspect_ratio = $sizes[1]/$sizes[0];

											if ($sizes[1] <= $image_size){
												$new_width = $sizes[0];
												$new_height = $sizes[1];
											}else{
												$new_height = $image_size;
												$new_width = abs($new_height/$aspect_ratio);
											}
											$destimg=ImageCreateTrueColor($new_width,$new_height)or die('<div class="alert alert-danger">Problem In Creating image</div>');

											switch($ext){
												case "PNG":
												case "png":
													$srcimg = imageCreateFromPng($prod_img)or die('<div class="alert alert-danger">Problem In opening Source Image</div>');
													imagealphablending($destimg, false);
													$colorTransparent = imagecolorallocatealpha($destimg, 0, 0, 0, 0x7fff0000);
													imagefill($destimg, 0, 0, $colorTransparent);
													imagesavealpha($destimg, true);
													break;
													
												case "gif":
													$srcimg =  imagecreatefromgif($prod_img)or die('<div class="alert alert-danger">Problem In opening Source Image</div>'); break;	
													
												case "jpeg": case "pjpeg": case "jpg":
													$srcimg = imagecreatefromjpeg($prod_img)or die('<div class="alert alert-danger">Problem In opening Source Image</div>'); break;
							
												default:
													$srcimg=imageCreateFromPng($prod_img)or die('<div class="alert alert-danger">Problem In opening Source Image</div>');
											}
											
											if(function_exists('imagecopyresampled')){
												imagecopyresampled($destimg,$srcimg,0,0,0,0,$new_width,$new_height,imagesX($srcimg),imagesY($srcimg))or die('<div class="alert alert-danger">Problem In resizing</div>');
											}else{
												Imagecopyresized($destimg,$srcimg,0,0,0,0,$new_width,$new_height,imagesX($srcimg),imagesY($srcimg))or die('<div class="alert alert-danger">Problem In resizing</div>');
											}

											// Saving an image
											switch(strtolower($ext)){
												case "png":
													imagepng($destimg,$prod_img_thumb) or die('<div class="alert alert-danger">Problem In saving</div>');
													break;
													
												case 'gif':
													imagegif($destimg, $prod_img_thumb) or die('<div class="alert alert-danger">Problem In saving</div>'); //save gif file
													break;
													
												case 'jpeg': case 'pjpeg': case "jpg":
													imagejpeg($destimg, $prod_img_thumb, $jpeg_quality) or die('<div class="alert alert-danger">Problem In saving</div>'); //save jpeg file
													break;
													
												default:
													// if image format is unknown, and you whant save it as jpeg, maybe you should change file extension
													imagejpeg($destimg,$prod_img_thumb,$jpeg_quality) or die('<div class="alert alert-danger">Problem In saving</div>');
											}

										}
									
										//mysqli_query($link, "UPDATE `".DB_PREFIX."users` SET `photo`='".$prefix.$userfile_name."' WHERE `user_ID`='".$my_id."'") or die("<div class=\"alert alert-danger\">Please upload valid format!</div>");
										
										//unlink($prod_img);
										echo '<div align="center">';
										echo '<img src=" '.$prod_img_thumb.' " alt="Photo" width="200">';
										echo '</div>';
									}else{
										echo "<div class=\"alert alert-danger\">Fail upload folder with read access.</div>";
									}
								}else{
									echo "<div class=\"alert alert-danger\">Image file size max 3 MB</div>";
								}
							}else{
								echo "<div class=\"alert alert-danger\">Invalid file format..</div>";
							}
						}else{
							echo "<div class=\"alert alert-danger\">Please select image..!</div>";
						}

						exit;
					}

				}
			
			
			
			
			
			
			
			
			
			//mysqli_query($link, "UPDATE ".DB_PREFIX."card_sale SET r_code='{$RemittanceCode}', remitted='1', remit_date='{$date}', remit_time='{$time}'  WHERE card_number IN ({$ID})");
			//mysqli_query($link, "UPDATE ".DB_PREFIX."terminaltrans SET remitted='1', remittance_code='{$RemittanceCode}', remit_date='{$date}', remit_time='{$time}'  WHERE transaction_code='CS' AND terminal_ID='{$terminal_ID}' AND user_ID IN ({$ID})");

		ENDIF;
	}
	$_SESSION['updatedRecords'] = "A total of ".$Records_counter." Remittance has been updated";
	header ("location: ../card_sale");
	Exit;

ENDIF;
?>