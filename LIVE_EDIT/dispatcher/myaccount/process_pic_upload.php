<?php
include '../settings/connect.php';
if(session_id() == '') { page_protect(); } // START SESSIONS


	$picsize = 250; 
	$my_id = $_SESSION['act_ID'];
	$filepath = 'members/'.$my_id.'/';


############ Configuration ##############
$thumb_square_size 	= $picsize; //Thumbnails will be cropped to 200x200 pixels
$thumb_prefix			= $my_id; //Normal thumb Prefix
$destination_folder		= $filepath; //upload directory ends with / (slash)

if( ! file_exists($destination_folder)) {
	$mask=umask(0);
	mkdir($destination_folder, 0777);
	umask($mask);
}

$deletefiles = glob("members/".$my_id."/*"); // get all file names
foreach($deletefiles as $file){ // iterate files
  if(is_file($file))
    unlink($file); // delete file
}

$jpeg_quality 			= 90; //jpeg quality
##########################################

//continue only if $_POST is set and it is a Ajax request
if(isset($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){

	// check $_FILES['ImageFile'] not empty
	if(!isset($_FILES['image_file']) || !is_uploaded_file($_FILES['image_file']['tmp_name'])){
		die('Image file is Missing!'); // output error when above checks fail.
	}
	
	//uploaded file info we need to proceed
	$image_name = $_FILES['image_file']['name']; //file name
	$image_size = $_FILES['image_file']['size']; //file size
	$image_temp = $_FILES['image_file']['tmp_name']; //file temp

	$image_size_info 	= getimagesize($image_temp); //get image size
	
	if($image_size_info){
		$image_width 		= $image_size_info[0]; //image width
		$image_height 		= $image_size_info[1]; //image height
		$image_type 		= $image_size_info['mime']; //image type
	}else{
		die("Make sure image file is valid!");
	}

	//switch statement below checks allowed image type 
	//as well as creates new image from given file 
	switch($image_type){
		case 'image/png':
			$image_res =  imagecreatefrompng($image_temp); break;
			
            imagealphablending($new_canvas, false);
            $colorTransparent = imagecolorallocatealpha($new_canvas, 0, 0, 0, 0x7fff0000);
            imagefill($new_canvas, 0, 0, $colorTransparent);
            imagesavealpha($new_canvas, true);
			
		case 'image/gif':
			$image_res =  imagecreatefromgif($image_temp); break;			
		case 'image/jpeg': case 'image/pjpeg':
			$image_res = imagecreatefromjpeg($image_temp); break;
		default:
			$image_res = false;
	}

	if($image_res){
		//Get file extension and name to construct new file name 
		$image_info = pathinfo($image_name);
		$image_extension = strtolower($image_info["extension"]); //image extension
		$image_name_only = strtolower($image_info["filename"]);//file name only, no extension
		
		//create a random name for new image (Eg: fileName_293749.jpg) ;
		$new_file_name = $image_name_only.'_'.uniqid().'.'.$image_extension;
		
		//folder path to save resized images and thumbnails
		$image_save = $filepath.$thumb_prefix.$new_file_name;
		$db_image_save = $thumb_prefix.$new_file_name;
		// Update Avatar
		mysqli_query($link, "UPDATE `".DB_PREFIX."users` SET `photo`='".$db_image_save."' WHERE `user_ID`='".$my_id."'") or die("Please upload valid format!");
		
		//call crop_image_square() function to create square thumbnails
		if(!crop_image_square($image_res, $image_save, $image_type, $thumb_square_size, $image_width, $image_height, $jpeg_quality))
		{
			die('Error Creating thumbnail');
		}
		
		
		/* We have succesfully resized and created thumbnail image
		We can now output image to user's browser or store information in the database*/
		echo '<div align="center">';
		echo '<img src=" '.$image_save.' " alt="Photo">';
		echo '</div>';
		imagedestroy($image_res); //freeup memory
	}
}

##### This function corps image to create exact square, no matter what its original size! ######
function crop_image_square($source, $destination, $image_type, $square_size, $image_width, $image_height, $quality){
	if($image_width <= 0 || $image_height <= 0){return false;} //return false if nothing to resize
	
	if( $image_width > $image_height )
	{
		$y_offset = 0;
		$x_offset = ($image_width - $image_height) / 2;
		$s_size 	= $image_width - ($x_offset * 2);
	}else{
		$x_offset = 0;
		$y_offset = ($image_height - $image_width) / 2;
		$s_size = $image_height - ($y_offset * 2);
	}
	$new_canvas	= imagecreatetruecolor( $square_size, $square_size); //Create a new true color image
	
	//Copy and resize part of an image with resampling
	if(imagecopyresampled($new_canvas, $source, 0, 0, $x_offset, $y_offset, $square_size, $square_size, $s_size, $s_size)){
		save_image($new_canvas, $destination, $image_type, $quality);
	}

	return true;
}

##### Saves image resource to file ##### 
function save_image($source, $destination, $image_type, $quality){
	switch(strtolower($image_type)){//determine mime type
		case 'image/png': 
			imagepng($source, $destination); return true; //save png file
			break;
		case 'image/gif': 
			imagegif($source, $destination); return true; //save gif file
			break;          
		case 'image/jpeg': case 'image/pjpeg': 
			imagejpeg($source, $destination, $quality); return true; //save jpeg file
			break;
		default: return false;
	}
}