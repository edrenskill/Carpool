<?php
include '../../../settings/connect.php';
if(session_id() == '') { session_start(); } // START SESSIONS

foreach($_POST as $key => $value) { $data[$key] = filter($value); }
	
IF(isset($_POST) && isset($data['idnumber'])):
	$my_id = $data['idnumber'];
ELSE:
	$my_id = $_SESSION['newmember'];
ENDIF;
	
$picsize = 400; 
$filepath1 = '../../../myaccount/members/'.$my_id.'/';
$filepath = '../../../myaccount/members/'.$my_id.'/signature/';
$userpath='../../../myaccount/members/'.$my_id.'/signature/';
	
############ Configuration ##############

$destination_folder1	= $filepath1; //upload directory ends with / (slash)
$destination_folder		= $filepath; //upload directory ends with / (slash)

if( ! file_exists($destination_folder1)) {
	$mask=umask(0);
	mkdir($destination_folder1, 0777);
	umask($mask);
}

if( ! file_exists($destination_folder)) {
	$mask=umask(0);
	mkdir($destination_folder, 0777);
	umask($mask);
}

$deletefiles = glob("../../../myaccount/members/".$my_id."/signature/*"); // get all file names
foreach($deletefiles as $file){ // iterate files
  if(is_file($file))
    unlink($file); // delete file
}
##########################################

//continue only if $_POST is set and it is a Ajax request
if(isset($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){

	// check $_FILES['ImageFile'] not empty
	if(!isset($_FILES['image_file']) || !is_uploaded_file($_FILES['image_file']['tmp_name'])){
		die('Image file is Missing!'); // output error when above checks fail.
	}

	function getExtension($str) {
	  $i = strrpos($str,".");
	  if (!$i) { return ""; }
	  $l = strlen($str) - $i;
	  $ext = substr($str,$i+1,$l);
	  return $ext;
	}

	$valid_formats = "png";

	if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
		$image_name = $_FILES['image_file']['name']; //file name
		$image_size = $_FILES['image_file']['size']; //file size

		if(strlen($image_name)){
			$ext = getExtension($image_name);

			if($ext == $valid_formats){

				if($image_size<(1048*1048)){
					
					$actual_image_name =$my_id."_".str_replace(" ", "_", $image_name);
			
					$image_temp = $_FILES['image_file']['tmp_name']; //file temp

					if(move_uploaded_file($image_temp, $filepath.$actual_image_name)){
						$image=$actual_image_name;
						/*--------resize image-----------*/
						$image_size = 100; // the imageheight
						$filedir = '../../../myaccount/members/'.$my_id.'/signature/';
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
							$destimg=ImageCreateTrueColor($new_width,$new_height)or die('Problem In Creating image');

							switch($ext){
								case "PNG":
								case "png":
									$srcimg = imageCreateFromPng($prod_img)or die('Problem In opening Source Image');
									imagealphablending($destimg, false);
									$colorTransparent = imagecolorallocatealpha($destimg, 0, 0, 0, 0x7fff0000);
									imagefill($destimg, 0, 0, $colorTransparent);
									imagesavealpha($destimg, true);

									break;
								default:
									$srcimg=ImageCreateFromPNG($prod_img)or die('Problem In opening Source Image');
							}
							
						if(function_exists('imagecopyresampled')){
							imagecopyresampled($destimg,$srcimg,0,0,0,0,$new_width,$new_height,imagesX($srcimg),imagesY($srcimg))or die('Problem In resizing');
						}else{
							Imagecopyresized($destimg,$srcimg,0,0,0,0,$new_width,$new_height,imagesX($srcimg),imagesY($srcimg))or die('Problem In resizing');
						}

						// Saving an image
						switch(strtolower($ext)){
							case "png":
							imagepng($destimg,$prod_img_thumb) or die('Problem In saving');
							break;
						default:
							// if image format is unknown, and you whant save it as jpeg, maybe you should change file extension
							imagepng($destimg,$prod_img_thumb,90) or die('Problem In saving');
						}

					}
					
					mysqli_query($link, "UPDATE `".DB_PREFIX."users` SET `signature`='".$prefix.$userfile_name."' WHERE `user_ID`='".$my_id."'") or die("Please upload valid format!");
					
					//unlink($prod_img);
					echo '<div align="center">';
					echo '<img src=" '.$prod_img_thumb.' " alt="Signature" width="200">';
					echo '</div>';
				}else{
					echo "Fail upload folder with read access.";
				}
			}else{
				echo "Image file size max 3 MB";
			}
		}else{
			echo "Invalid file format..";
		}
	}else{
		echo "Please select image..!";
	}

	exit;
	}

	
	
	
	
}