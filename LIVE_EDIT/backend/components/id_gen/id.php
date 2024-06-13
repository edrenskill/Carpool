<?php

// link to the font file not the server
$fontname = dirname(__FILE__) . '/font/arial.ttf';
// controls the spacing between text
$i=30;
//JPG image quality 0-100
$quality = 100;
$_SESSION['members_ID'] = $member_ID;
$Photoset = 0;

//User Avatar
$member = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM ".DB_PREFIX."users WHERE user_ID = ".$member_ID."")) or (die("Error, Please complete your data in order to generate your ID."));
$IDpic = $member['photo'];
$Sigpic = $member['signature'];
IF($member['userlevel'] == 1):
	$position = "MEMBER";
ELSEIF($member['userlevel'] == 2):
	$position = "DISPATCHER";
ELSEIF($member['userlevel'] == 3):
	$position = "COLLECTOR";
ELSEIF($member['userlevel'] == 4):
	$position = "WEB ADMIN";
ELSEIF($member['userlevel'] == 5):
	$position = "SYS ADMIN";
ELSEIF($member['userlevel'] == 6):
	$position = "OFFICER";
ELSEIF($member['userlevel'] == 7):
	$position = "DRIVER";
ELSEIF($member['userlevel'] == 8):
	$position = "VEHICLE OWNER";
ELSEIF($member['userlevel'] == 9):
	$position = "LOADING";
ELSEIF($member['userlevel'] == 10):
	$position = "VEHICLE OWNER/DRIVER";
ENDIF;

IF($IDpic == ""):
	$Photoset = 0;
ELSE:
	$Photoset = 1;
	//Check Path
	$filepath = $_SERVER['DOCUMENT_ROOT']."/backend/components/id_gen/commuterID/".$member_ID."/";

	$destination_folder		= $filepath; //upload directory ends with / (slash)

	if( ! file_exists($destination_folder)) {
		$mask=umask(0);
		mkdir($destination_folder, 0777);
		umask($mask);
	}


	// Copy pic to id directory
	copy($_SERVER['DOCUMENT_ROOT']."/myaccount/members/".$member_ID."/".$IDpic,$filepath.$member_ID."x.jpg");

	// Copy QR to id directory
	copy($_SERVER['DOCUMENT_ROOT']."/myaccount/members/".$member_ID."/QRCODE".$member_ID.".png",$filepath.$member_ID."y.png");
	
	// Copy QR to id directory
	copy($_SERVER['DOCUMENT_ROOT']."/myaccount/members/".$member_ID."/signature/".$Sigpic,$filepath.$member_ID."sig.png");

	//Photo ID
	$picim = "components/id_gen/commuterID/".$member_ID."/".$member_ID."x.jpg";
	$picqr = "components/id_gen/commuterID/".$member_ID."/".$member_ID."y.png";
	$picsig = "components/id_gen/commuterID/".$member_ID."/".$member_ID."sig.png";

	// Get Contact Information
			$address = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM ".DB_PREFIX."users WHERE user_ID=".$member_ID."")) or die("Error, Please complete your data in order to generate your ID.1".$member_ID);
			$contact = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM ".DB_PREFIX."contacts WHERE UID=".$member_ID."")) or die("Error, Contact Detail Error.".$member_ID);
			$brgy = mysqli_fetch_array(mysqli_query($link, "SELECT name FROM ".DB_PREFIX."barangays WHERE brgy_code = ".$address['barangay']."")) or die("Error, Please complete your data in order to generate your ID.2".$member_ID);
			$city = mysqli_fetch_array(mysqli_query($link, "SELECT name FROM ".DB_PREFIX."city_municipality WHERE cm_code = ".$address['city']."")) or die("Error, Please complete your data in order to generate your ID.3".$member_ID);
			$province = mysqli_fetch_array(mysqli_query($link, "SELECT name FROM ".DB_PREFIX."provinces WHERE p_code = ".$address['province']."")) or die("Error, Please complete your data in order to generate your ID.4".$member_ID);
			$region = mysqli_fetch_array(mysqli_query($link, "SELECT name FROM ".DB_PREFIX."regions WHERE r_code = ".$address['region']."")) or die("Error, Please complete your data in order to generate your ID.5".$member_ID);
			$country = mysqli_fetch_array(mysqli_query($link, "SELECT countries_name FROM ".DB_PREFIX."countries WHERE c_code = '".$address['country']."'")) or die("Error, Please complete your data in order to generate your ID.6".$member_ID);
			
			$fullname = $address['fname']." ".$address['lname'].(($address['suffix'])? ", ".$address['suffix'] : "");
			$contactperson = $contact['contact_person'];
			$contactnumber = $contact['mobile'];
			$fontsize = (strlen($fullname) >= 20)? ($fonthsize = 25) : ($fontsize = 35); 

	// Create Front
	function create_front_id($user_front){

		global $fontname;
		global $quality;

		$file = "components/id_gen/commuterID/".$_SESSION['members_ID']."/front-".$_SESSION['members_ID'].".jpg";
				
		// if the file already exists dont create it again just serve up the original	
		

			// define the base image that we lay our text on
			$im = imagecreatefromjpeg("components/id_gen/images/id_front.jpg");

			// setup the text colours
			$color['black'] = imagecolorallocate($im, 0, 0, 0);

			// loop through the array and write the text	
			foreach ($user_front as $value){
				// center the text in our image - returns the x value
				$x = center_text($value['name'], $value['font-size']);	
				imagettftext($im, $value['font-size'], 0, $x, $value['vpos'], $color[$value['color']], $fontname,$value['name']);
			}
			
			imagettftext($im, 25, 0, 410, 990, $color['black'], $fontname,$_SESSION['members_ID']);

			// ID Number
			//imagettftext($im, 35, 0, 200, 180, $color['black'], $fontname,$_SESSION['members_ID']);

			// create the image
			imagejpeg($im, $file, $quality);
				
			return $file;
	}



	// Create Back
	function create_back_id($user_back){

		global $fontname;
		global $quality;

		$file = "components/id_gen/commuterID/".$_SESSION['members_ID']."/back-".$_SESSION['members_ID'].".jpg";
				
		// if the file already exists don't create it again just serve up the original	

			// define the base image that we lay our text on
			$im = imagecreatefromjpeg("components/id_gen/images/id_back2.jpg");

			// setup the text colours
			$color['black'] = imagecolorallocate($im, 0, 0, 0);
			$color['white'] = imagecolorallocate($im, 255, 255, 255);
			//$color['green'] = imagecolorallocate($im, 55, 189, 102);

			// this defines the starting height for the text block
			$y = imagesy($im) - 200 - 548;

			// loop through the array and write the text	
			foreach ($user_back as $value){
				// center the text in our image - returns the x value
				$x = center_text($value['name'], $value['font-size']);	
				imagettftext($im, $value['font-size'], 0, $value['hpos'], $y+$value['vpos'], $color[$value['color']], $fontname,$value['name']);
			}

			// create the image
			imagejpeg($im, $file, $quality);

			return $file;
	}

	function center_text($string, $font_size){

				global $fontname;

				$image_width = 638;
				$dimensions = imagettfbbox($font_size, 0, $fontname, $string);
				
				return ceil(($image_width - $dimensions[4]) / 2);				
	}

		$user_front = array(
			array(
				'name'=> strtoupper($fullname), 
				'font-size'=>$fontsize,
				'color'=>'blue',
				'vpos'=>'720'),
		//		'hpos'=>'120'),
				
			array(
				'name'=> $position,
				'font-size'=>'35',
				'color'=>'black',
				'vpos'=>'770')
				//'hpos'=>'230')
		);
		
		$user_back = array(
				
			array(
				'name'=> strtoupper($contactperson),
				'font-size'=>'35',
				'color'=>'black',
				'vpos'=>'180', //350
				'hpos'=>'20'),
				
			array(
				'name'=> strtoupper($contact['address1'].", "),
				'font-size'=>'22',
				'color'=>'black',
				'vpos'=>'305', //480
				'hpos'=>'20'),
				
			array(
				'name'=> strtoupper($brgy['name'].", "),
				'font-size'=>'22',
				'color'=>'black',
				'vpos'=>'335', //520
				'hpos'=>'20'),
			
			array(
				'name'=> strtoupper($city['name'].", ".$province['name'].","),
				'font-size'=>'22',
				'color'=>'black',
				'vpos'=>'365', //560
				'hpos'=>'20'),

			array(
				'name'=> strtoupper($region['name']),
				'font-size'=>'22',
				'color'=>'black',
				'vpos'=>'395', //600
				'hpos'=>'20'),
			
			array(
				'name'=> strtoupper($contactnumber),
				'font-size'=>'35',
				'color'=>'black',
				'vpos'=>'540', //720
				'hpos'=>'20'),
			array(
				'name'=> strtoupper($member_ID), 
				'font-size'=>'25',
				'color'=>'white',
				'vpos'=>'730',
				'hpos'=>'410')

		);
	// run the script to create the image
	$frontfilename = create_front_id($user_front);
	$backfilename = create_back_id($user_back);

	// Put Photo
	$file = "components/id_gen/commuterID/".$member_ID."/front-".$member_ID.".jpg";
	$baseim = imagecreatefromjpeg($file);

	if (exif_imagetype($picim) != IMAGETYPE_JPEG) {
		$pic = imagecreatefrompng($picim);
	}ELSE{
		$pic = imagecreatefromjpeg($picim);
	}

	// Set the margins for the stamp and get the height/width of the stamp image
	$marge_right = 120;
	$marge_bottom = 350;
	$sx = imagesx($pic);
	$sy = imagesy($pic);
				
	// Merge the stamp onto our photo with an opacity of 50%
	imagecopymerge($baseim, $pic, imagesx($baseim) - $sx - $marge_right, imagesy($baseim) - $sy - $marge_bottom, 0, 0, 400, 400, 100);
				
	// Save the image to file and free memory
	imagejpeg($baseim, 'components/id_gen/commuterID/'.$member_ID.'/front-'.$member_ID.'.jpg');
	imagedestroy($baseim);
	unlink("components/id_gen/commuterID/".$member_ID."/".$member_ID."x.jpg");
	
	
	
	
	// Put SIGNATURE
	$file = "components/id_gen/commuterID/".$member_ID."/front-".$member_ID.".jpg";
	$basesig = imagecreatefromjpeg($file);
	$sig = imagecreatefrompng($picsig);

	// Set the margins for the stamp and get the height/width of the stamp image
	$sx = imagesx($sig);
	$sy = imagesy($sig);
	
	$marge_right = (imagesx($basesig) - $sx)/2;
	$marge_bottom = 800;
				
	// Merge the stamp onto our photo with an opacity of 50%
	//imagecopyresampled($dest, $src, $src2x, $src2y, 0, 0, $src2w, $src2h, $src2w, $src2h); 
	imagecopyresampled($basesig, $sig, $marge_right, $marge_bottom, 0, 0, $sx, $sy, $sx, $sy);
				
	// Save the image to file and free memory
	imagejpeg($basesig, 'components/id_gen/commuterID/'.$member_ID.'/front-'.$member_ID.'.jpg');
	imagedestroy($basesig);
	unlink("components/id_gen/commuterID/".$member_ID."/".$member_ID."sig.png");
	
	
	


	// Put QRCODE
	$file = "components/id_gen/commuterID/".$member_ID."/back-".$member_ID.".jpg";
	$baseqr = imagecreatefromjpeg($file);
	$qr = imagecreatefrompng($picqr);

	// Set the margins for the stamp and get the height/width of the stamp image
	$marge_right = 230;
	$marge_bottom = 700;
	$sx = imagesx($qr);
	$sy = imagesy($qr);
				
	// Merge the stamp onto our photo with an opacity of 50%
	imagecopymerge($baseqr, $qr, imagesx($baseqr) - $sx - $marge_right, imagesy($baseqr) - $sy - $marge_bottom, 0, 0, 200, 200, 100);
				
	// Save the image to file and free memory
	imagejpeg($baseqr, 'components/id_gen/commuterID/'.$member_ID.'/back-'.$member_ID.'.jpg');
	imagedestroy($baseqr);
	unlink("components/id_gen/commuterID/".$member_ID."/".$member_ID."y.png");


ENDIF;


	IF($Photoset == 0):
?>
	<div class="col-lg-4" style="width:275">
		<div class="panel panel-primary">
			<div class="panel-heading">
				Upload Photo First
			</div>  
			<div class="panel-body">
				UPLOAD
			</div>
			<div class="panel-footer">
				SUBMIT
			</div>
		</div>
	</div>

<?php ELSE: ?>
<div class="col-lg-4" style="width:275">
    <div class="panel panel-primary">
        <div class="panel-heading">
            Front ID
        </div>
        <div class="panel-body">
            <img src="components/id_gen/commuterID/<?=$member_ID;?>/front-<?=$member_ID;?>.jpg" width="212" height="337"/>
        </div>
        <div class="panel-footer">
            <a href="components/id_gen/commuterID/<?=$member_ID;?>/front-<?=$member_ID;?>.jpg" download="front-<?=$member_ID;?>.jpg"><i class="fa fa-download"></i> Download</a>
        </div>
    </div>
</div>
<div class="col-lg-4" style="width:275">
    <div class="panel panel-primary">
        <div class="panel-heading">
            Back ID
        </div>
        <div class="panel-body">
            <img src="components/id_gen/commuterID/<?=$member_ID;?>/back-<?=$member_ID;?>.jpg" width="212" height="337"/>
        </div>
        <div class="panel-footer">
			<a href="components/id_gen/commuterID/<?=$member_ID;?>/back-<?=$member_ID;?>.jpg" download="back-<?=$member_ID;?>.jpg"><i class="fa fa-download"></i> Download</a>
        </div>
    </div>
</div>
<?php ENDIF; ?>