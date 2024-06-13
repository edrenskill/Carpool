<?php 

//	foreach($_GET as $key => $value) { $data[$key] = filter($value); }
//	$member_ID = $data['member'];
	
    //set it to writable location, a place for temp generated PNG files
    $PNG_TEMP_DIR = '..'.DIRECTORY_SEPARATOR.'myaccount'.DIRECTORY_SEPARATOR.'members'.DIRECTORY_SEPARATOR.$member_ID.DIRECTORY_SEPARATOR;
	
	//html PNG location prefix
    $PNG_WEB_DIR = '../myaccount/members/'.$member_ID.'/';

    include "qrlib.php";    
    
    //ofcourse we need rights to create temp dir
    if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);

    $filename = $PNG_TEMP_DIR.$member_ID.'.png';
    
    //processing form input
    //remember to sanitize user input in real-life solution !!!
	$errorCorrectionLevel = 'H';
    $matrixPointSize = min(max((int)8, 1), 10);

        //it's very important!
        if (trim($member_ID) == '')
        die('data cannot be empty! <a href="?">back</a>'.$member_ID);
            
        // user data
        $filename = $PNG_TEMP_DIR.'QRCODE'.$member_ID.'.png';
        QRcode::png($member_ID, $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
        
        
  
        
    //display generated file
   // echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" /><hr/>';  
    

    