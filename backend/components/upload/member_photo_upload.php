<?php
include '../../../settings/connect.php';
if(session_id() == '') { session_start(); } // START SESSIONS

foreach($_POST as $key => $value) { $data[$key] = filter($value); }
	
IF(isset($_POST) && isset($data['idnumber'])):
	$my_id = $data['idnumber'];
ELSE:
	$my_id = $_SESSION['newmember'];
ENDIF;

IF(isset($my_id)){
	
	$searchID = mysqli_query($link, "SELECT user_ID FROM ".DB_PREFIX."users WHERE user_ID='{$my_id}' AND userlevel !=1");
	$countID = mysqli_num_rows($searchID);
	IF($countID){


		$jpeg_quality = 90; //jpeg quality
		$picsize = 400; 
		$filepath = '../../../myaccount/members/'.$my_id.'/';
		$userpath='../../../myaccount/members/'.$my_id.'/';
			
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
			if(!isset($_FILES['image_file']) || !is_uploaded_file($_FILES['image_file']['tmp_name'])){
				die('Image file is Missing!'); // output error when above checks fail.
			}

			$valid_formats = array("pjpeg", "jpeg", "jpg", "png", "gif");

			if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
				$image_name = $_FILES['image_file']['name']; //file name
				$image_size = $_FILES['image_file']['size']; //file size

				if(strlen($image_name)){
					$ext = getExtension($image_name);
					
					if(in_array($ext,$valid_formats)){

						if($image_size<(1048*1048)){
							
							$deletefiles = glob("../../../myaccount/members/".$my_id."/*"); // get all file names
							foreach($deletefiles as $file){ // iterate files
							  if(is_file($file))
								unlink($file); // delete file
							}
							
							$actual_image_name =$my_id."_".str_replace(" ", "_", $image_name);
					
							$image_temp = $_FILES['image_file']['tmp_name']; //file temp

							if(move_uploaded_file($image_temp, $filepath.$actual_image_name)){
								$image=$actual_image_name;
								/*--------resize image-----------*/
								$image_size = 400; // the imageheight
								$filedir = '../../../myaccount/members/'.$my_id.'/';
								$thumbdir = $userpath; // the directory for the resized image
								$prefix = $my_id.'_'; // the prefix to be added to the original name
								$maxfile = '1048576';
								$mode = '0777';
								$userfile_name =str_replace(" ", "_", $_FILES['image_file']['name']);
								$userfile_tmp = str_replace(" ", "_", $_FILES['image_file']['tmp_name']);
								$userfile_size =$_FILES['image_file']['size'];
								$userfile_type = $_FILES['image_file']['type'];

								if (isset($_FILES['image_file']['name'])){
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
							
								mysqli_query($link, "UPDATE `".DB_PREFIX."users` SET `photo`='".$prefix.$userfile_name."' WHERE `user_ID`='".$my_id."'") or die("<div class=\"alert alert-danger\">Please upload valid format!</div>");
								
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
	}ELSE{
		echo "<div class=\"alert alert-danger\">ID not found!</div>";
	}
}ELSE{
	echo "<div class=\"alert alert-danger\">ID Number is invalid!</div>";
}