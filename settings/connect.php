<?php

// CONNECT TO DB
define("DB_HOST", "localhost");    // HOST NAME
define("DB_USER", "carpoolphil_carpoolphil1");    // DATABASE USER
define("DB_PASS", "akomismo0910239");    // DATABASE PASSWORD
define("DB_NAME", "carpoolphil_commuters101");    // DATABASE NAME
define("DB_PREFIX", "commuter_"); // DATABASE PREFIX

$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

date_default_timezone_set("Asia/Manila");
////////////////////////////// BEGIN FUNCTIONS SECTION ////////////////////////////

// get extension
function getExtension($str)
{
    $i = strrpos($str, ".");
    if (!$i) {
        return "";
    }
    $l = strlen($str) - $i;
    $ext = substr($str, $i + 1, $l);
    return $ext;
}

// crop image
if (!function_exists("create_square_image")) {
    function create_square_image($original_file, $destination_file = null, $square_size = 96)
    {
        if (isset($destination_file) && $destination_file != null) {
            if (!is_writable($destination_file)) {
                echo '<p style="color:#FF0000">Oops, the destination path is not writable. Make that file or its parent folder writable.</p>';
            }
        }

        // get width and height of original image
        $imagedata = getimagesize($original_file);
        $original_width = $imagedata[0];
        $original_height = $imagedata[1];

        if ($original_width > $original_height) {
            $new_height = $square_size;
            $new_width = $new_height * ($original_width / $original_height);
        } elseif ($original_height > $original_width) {
            $new_width = $square_size;
            $new_height = $new_width * ($original_height / $original_width);
        } else {
            $new_width = $square_size;
            $new_height = $square_size;
        }

        $new_width = round($new_width);
        $new_height = round($new_height);

        // load the image
        $original_image = null;
        $file_extension = strtolower(pathinfo($original_file, PATHINFO_EXTENSION));

        switch ($file_extension) {
            case 'jpg':
            case 'jpeg':
                $original_image = imagecreatefromjpeg($original_file);
                break;
            case 'gif':
                $original_image = imagecreatefromgif($original_file);
                break;
            case 'png':
                $original_image = imagecreatefrompng($original_file);
                break;
        }

        if ($original_image) {
            $smaller_image = imagecreatetruecolor($new_width, $new_height);
            $square_image = imagecreatetruecolor($square_size, $square_size);

            imagecopyresampled($smaller_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);

            if ($new_width > $new_height) {
                $difference = $new_width - $new_height;
                $half_difference = round($difference / 2);
                imagecopyresampled($square_image, $smaller_image, -$half_difference + 1, 0, 0, 0, $square_size + $difference, $square_size, $new_width, $new_height);
            } elseif ($new_height > $new_width) {
                $difference = $new_height - $new_width;
                $half_difference = round($difference / 2);
                imagecopyresampled($square_image, $smaller_image, 0, -$half_difference + 1, 0, 0, $square_size, $square_size + $difference, $new_width, $new_height);
            } else {
                imagecopyresampled($square_image, $smaller_image, 0, 0, 0, 0, $square_size, $square_size, $new_width, $new_height);
            }

            // if no destination file was given then display a png
            if (!$destination_file) {
                header('Content-Type: image/png');
                imagepng($square_image, null, 9);
            }

            // save the smaller image FILE if destination file given
            $destination_extension = strtolower(pathinfo($destination_file, PATHINFO_EXTENSION));

            switch ($destination_extension) {
                case 'jpg':
                case 'jpeg':
                    imagejpeg($square_image, $destination_file, 100);
                    break;
                case 'gif':
                    imagegif($square_image, $destination_file);
                    break;
                case 'png':
                    imagepng($square_image, $destination_file, 9);
                    break;
            }

            imagedestroy($original_image);
            imagedestroy($smaller_image);
            imagedestroy($square_image);
        }
    }
}

////////////////////////////// MESSAGING TIME LAPSE ////////////////////////////////
function get_time_difference_php($created_time)
{
    $str = strtotime($created_time);
    $today = strtotime(date('Y-m-d H:i:s'));

    // It returns the time difference in Seconds...
    $time_difference = $today - $str;

    // To Calculate the time difference in Years...
    $years = 60 * 60 * 24 * 365;

    // To Calculate the time difference in Months...
    $months = 60 * 60 * 24 * 30;

    // To Calculate the time difference in Days...
    $days = 60 * 60 * 24;

    // To Calculate the time difference in Hours...
    $hours = 60 * 60;

    // To Calculate the time difference in Minutes...
    $minutes = 60;

    if (intval($time_difference / $years) > 1) {
        return intval($time_difference / $years) . " years ago";
    } elseif (intval($time_difference / $years) > 0) {
        return intval($time_difference / $years) . " year ago";
    } elseif (intval($time_difference / $months) > 1) {
        return intval($time_difference / $months) . " months ago";
    } elseif (intval($time_difference / $months) > 0) {
        return intval($time_difference / $months) . " month ago";
    } elseif (intval($time_difference / $days) > 1) {
        return intval($time_difference / $days) . " days ago";
    } elseif (intval($time_difference / $days) > 0) {
        return intval($time_difference / $days) . " day ago";
    } elseif (intval($time_difference / $hours) > 1) {
        return intval($time_difference / $hours) . " hours ago";
    } elseif (intval($time_difference / $hours) > 0) {
        return intval($time_difference / $hours) . " hour ago";
    } elseif (intval($time_difference / $minutes) > 1) {
        return intval($time_difference / $minutes) . " minutes ago";
    } elseif (intval($time_difference / $minutes) > 0) {
        return intval($time_difference / $minutes) . " minute ago";
    } elseif (intval($time_difference) > 1) {
        return intval($time_difference) . " seconds ago";
    } else {
        return "few seconds ago";
    }
}

define("COOKIE_TIME_OUT", 10); // specify cookie timeout in days (default is 10 days)
define('SALT_LENGTH', 9); // salt for password

/* Specify user levels */
define("OWNER_DRIVER_LEVEL", 10);
define("LOADING_LEVEL", 9);
define("OPERATOR_LEVEL", 8);
define("DRIVER_LEVEL", 7);
define("OFFICER_LEVEL", 6);
define("ADMINISTRATOR_LEVEL", 5);
define("ADMIN_LEVEL", 4);
define("COLLECTOR_LEVEL", 3);
define("DISPATCHER_LEVEL", 2);
define("REGISTERED_LEVEL", 1);
define("GUEST_LEVEL", 0);

// Get web settings details
$websettings = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM " . DB_PREFIX . "settings"));
$currency = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM " . DB_PREFIX . "currency WHERE selected=1"));
$symbol = $currency['Symbol'];
if ($symbol == "") {
    $symbol = $currency['ISO_4217_code'];
}
define("BANNER", $websettings['banner']);
define("WEBSITE", $websettings['website']);
define("TITLES", $websettings['title']);
define("DESCRIPTION", $websettings['description']);
define("SLOGAN", $websettings['title_slogan']);
define("KEYWORDS", $websettings['keywords']);
define("CURRENCY", $symbol);

// DEFINE INCLUDE DIRECTORY
define("ROOT", $_SERVER["DOCUMENT_ROOT"]);
define("PAGE", ROOT . "/paging/");
define("SETTING", ROOT . "/settings/");

/*************** reCAPTCHA KEYS ****************/
$publickey = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"; // get your key at http://www.google.com/recaptcha/whyrecaptcha
$privatekey = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"; // get your key at http://www.google.com/recaptcha/whyrecaptcha



/////////////////////////// FUNCTION CONVERT NUMBER TO WORDS ////////////////////////////////
	
	function NumberToWord($number) {

		$hyphen      = '-';
		$conjunction = ' and ';
		$separator   = ', ';
		$negative    = 'negative ';
		$decimal     = ' point ';
		$dictionary  = array(
			0                   => 'zero',
			1                   => 'one',
			2                   => 'two',
			3                   => 'three',
			4                   => 'four',
			5                   => 'five',
			6                   => 'six',
			7                   => 'seven',
			8                   => 'eight',
			9                   => 'nine',
			10                  => 'ten',
			11                  => 'eleven',
			12                  => 'twelve',
			13                  => 'thirteen',
			14                  => 'fourteen',
			15                  => 'fifteen',
			16                  => 'sixteen',
			17                  => 'seventeen',
			18                  => 'eighteen',
			19                  => 'nineteen',
			20                  => 'twenty',
			30                  => 'thirty',
			40                  => 'fourty',
			50                  => 'fifty',
			60                  => 'sixty',
			70                  => 'seventy',
			80                  => 'eighty',
			90                  => 'ninety',
			100                 => 'hundred',
			1000                => 'thousand',
			1000000             => 'million',
			1000000000          => 'billion',
			1000000000000       => 'trillion',
			1000000000000000    => 'quadrillion',
			1000000000000000000 => 'quintillion'
		);

		if (!is_numeric($number)) {
			return false;
		}

		if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
			// overflow
			trigger_error(
				'NumberToWord only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
				E_USER_WARNING
			);
			return false;
		}

		if ($number < 0) {
			return $negative . NumberToWord(abs($number));
		}

		$string = $fraction = null;

		if (strpos($number, '.') !== false) {
			list($number, $fraction) = explode('.', $number);
		}

		switch (true) {
			case $number < 21:
				$string = $dictionary[$number];
				break;
			case $number < 100:
				$tens   = ((int) ($number / 10)) * 10;
				$units  = $number % 10;
				$string = $dictionary[$tens];
				if ($units) {
					$string .= $hyphen . $dictionary[$units];
				}
				break;
			case $number < 1000:
				$hundreds  = $number / 100;
				$remainder = $number % 100;
				$string = $dictionary[$hundreds] . ' ' . $dictionary[100];
				if ($remainder) {
					$string .= $conjunction . NumberToWord($remainder);
				}
				break;
			default:
				$baseUnit = pow(1000, floor(log($number, 1000)));
				$numBaseUnits = (int) ($number / $baseUnit);
				$remainder = $number % $baseUnit;
				$string = NumberToWord($numBaseUnits) . ' ' . $dictionary[$baseUnit];
				if ($remainder) {
					$string .= $remainder < 100 ? $conjunction : $separator;
					$string .= NumberToWord($remainder);
				}
				break;
		}

		if (null !== $fraction && is_numeric($fraction)) {
			$string .= $decimal;
			$words = array();
			foreach (str_split((string) $fraction) as $number) {
				$words[] = $dictionary[$number];
			}
			$string .= implode(' ', $words);
		}

		return $string;
	}
	
	

	//GET URL function
	function url(){
	  return sprintf(
	    "%s://%s%s",
	    (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https' : 'http',
	    $_SERVER['HTTP_HOST'],
	    $_SERVER['REQUEST_URI']
	  );
	}

	$path = $_SERVER['DOCUMENT_ROOT'];

	// Base URL Function
	function baseurl($url) {
	  $result = parse_url($url);
	  return $result['scheme']."://".$result['host'];
	}

	$urllink = url();
	define('SERVER_PATH', dirname(url()));

	// get url address function
	function getAddress() {
	    $protocol = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
	    return $protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}

	function getBaseUrl() 
	{
		// output: /myproject/index.php
		$currentPath = $_SERVER['PHP_SELF']; 
		
		// output: Array ( [dirname] => /myproject [basename] => index.php [extension] => php [filename] => index ) 
		$pathInfo = pathinfo($currentPath); 
		
		// output: localhost
		$hostName = $_SERVER['HTTP_HOST']; 
		
		// output: http://
		$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
		
		// return: http://localhost/myproject/
		return $protocol.$hostName."/";
	}
	

	/**** PAGE PROTECT CODE  ********************************
	This code protects pages to only logged in users. If users have not logged in then it will redirect to login page.
	If you want to add a new page and want to login protect, COPY this from this to END marker.
	Remember this code must be placed on very top of any html or php page.
	********************************************************/

	function page_protect() {
		session_start();
		global $link; 

		/* Secure against Session Hijacking by checking user agent */
		if (isset($_SESSION['HTTP_USER_AGENT'])) {
			if ($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT'])) {
				logout();
				exit;
			}
		}

		// Need to check authentication key - ckey and ctime stored in database before allowing sessions
		/* If session not set, check for cookies set by Remember me */
		if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_name']) ) {
			if(isset($_COOKIE['user_id']) && isset($_COOKIE['user_key'])) {

				/* Double check cookie expiry time against stored in database */
				$cookie_user_id  = filter($_COOKIE['user_id']);
				$rs_ctime = mysqli_query($link, "select `ckey`,`ctime` from `".DB_PREFIX."users` WHERE `id` ='$cookie_user_id'") or die(mysqli_error());
				list($ckey,$ctime) = mysqli_fetch_row($rs_ctime);
				// coookie expiry
				if( (time() - $ctime) > 60*60*24*COOKIE_TIME_OUT) {
					logout();
				}
				/* Security check with untrusted cookies - dont trust value stored in cookie. 		
				/* Also do authentication check of the `ckey` stored in cookie matches that stored in database during login*/

				if( !empty($ckey) && is_numeric($_COOKIE['user_id']) && isUserID($_COOKIE['user_name']) && $_COOKIE['user_key'] == sha1($ckey)  ) {
					session_regenerate_id(); //against session fixation attacks.
					$_SESSION['user_id'] = $_COOKIE['user_id'];
					$_SESSION['user_name'] = $_COOKIE['user_name'];
					/* query user level from database instead of storing in cookies */	
					list($user_level) = mysqli_fetch_row(mysqli_query($link, "select userlevel from ".DB_PREFIX."users where id='$_SESSION[user_id]'"));
					$_SESSION['user_level'] = $user_level;
					$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']); 
				}else{
					logout();
				}
			}else{
				header("Location: login");
				exit();
			}
		}
	}
	// End page protect function

	// Data filtering function
	function filter($data) {
		global $link;

		$data = trim(htmlentities(strip_tags($data)));

		if (get_magic_quotes_gpc()) 
			$data = stripslashes($data);
			$data = mysqli_real_escape_string($link, $data);
			return $data;
	}

	function EncodeURL($url) {
		$new = strtolower(ereg_replace(' ','_',$url));
		return($new);
	}

	function DecodeURL($url) {
		$new = ucwords(ereg_replace('_',' ',$url));
		return($new);
	}

	function ChopStr($str, $len){
		if (strlen($str) < $len)
	        return $str;
    		$str = substr($str,0,$len);
    	if ($spc_pos = strrpos($str," "))
            $str = substr($str,0,$spc_pos);
		    return $str . "...";
	}	

	// Email Validation function
	function isEmail($email){ return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? TRUE : FALSE; }

	// Username Validation Faunction
	function isUserID($username) { 	if (preg_match('/^[a-z\d_]{5,20}$/i', $username)) { return true; } else { return false;	}  }	

	// URL Validation Function
	function isURL($url) {
		if (preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $url)) {
			return true;
		}else{
			return false;
		}
	}

	//add http to URL
	function addhttp($url) {
		if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
			$url = "http://" . $url;
		}
		return $url;
	}

	// Password Checker function
	function checkPwd($x,$y) {
		if(empty($x) || empty($y) ) { return false; }
		if (strlen($x) < 4 || strlen($y) < 4) { return false; }
		if (strcmp($x,$y) != 0) {
			return false;
		} 
		return true;
	}

	// Password Generator function
	function familyName($fname, $year) {
    echo "$fname Refsnes. Born in $year <br>";
}
	
	function GenPwd($length = 7) {
		$password = "";
		$possible = "0123456789bcdfghjkmnpqrstvwxyz"; //no vowels  
		$i = 0;    
		while ($i < $length) {     
			$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);     
			if (!strstr($password, $char)) { 
				$password .= $char;
				$i++;
    		}
		}
		return $password;
	}
	
	// activation Generator function
	function GenCode($length = 8) {
		$code = "";
		$possible = "0123456789"; //no vowels  
		$i = 0;    
		while ($i < $length) {     
			$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);     
			if (!strstr($code, $char)) { 
				$code .= $char;
				$i++;
    		}
		}
		return $code;
	}
	
	// ID Generator function
	function GenID($length = 8) {
		$account = "";
		$possible = "0123456789"; //no vowels  
		$i = 0;    
		while ($i < $length) {     
			$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);     
			if (!strstr($account, $char)) { 
				$account .= $char;
				$i++;
    		}
		}
		return $account;
	}
	
	// PID Generator funtion
	function GenPID($length = 6) {
		$account = "";
		$possible = "0123456789"; //no vowels  
		$i = 0;    
		while ($i < $length) {     
			$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);     
			if (!strstr($account, $char)) { 
				$account .= $char;
				$i++;
    		}
		}
		return $account;
	}

	// Key Generator or use Password Generator as alternative function
	function GenKey($length = 7)
	{
		$password = "";
		$possible = "0123456789abcdefghijkmnopqrstuvwxyz";   
		$i = 0;     
		while ($i < $length)
		{ 
			$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
			if (!strstr($password, $char))
			{ 
				$password .= $char;
				$i++;
			}
		}
		return $password;
	}

	// Logout Function
	function logout()
	{
		global $link;
		session_start();
		$rdirect = baseurl(url()).$_SESSION['oldURL'];
		if(isset($_SESSION['user_id']) || isset($_COOKIE['user_id']))
		{
			mysqli_query($link, "update `".DB_PREFIX."users` SET `ckey`= '', `ctime`= ''  WHERE `id`='$_SESSION[user_id]' OR  `id` = '$_COOKIE[user_id]'") or die(mysqli_error());
		}			

		/************ Delete the sessions****************/
		unset($_SESSION['user_id']);
		unset($_SESSION['user_name']);
		unset($_SESSION['user_level']);
		unset($_SESSION['HTTP_USER_AGENT']);
		session_unset();
		session_destroy();
		$_SESSION = [];

		/* Delete the cookies*******************/
		setcookie("user_id", '', time()-60*60*24*COOKIE_TIME_OUT, "/");
		setcookie("user_name", '', time()-60*60*24*COOKIE_TIME_OUT, "/");
		setcookie("user_key", '', time()-60*60*24*COOKIE_TIME_OUT, "/");

		if(isset($rdirect)){ header("Location:  ".$rdirect.""); }
		else { header("Location: index"); }
	}
	
	// Password and salt generation (password encryption)
	function PwdHash($pwd, $salt = null)
	{
		if ($salt === null)
		{
			$salt = substr(md5(uniqid(rand(), true)), 0, SALT_LENGTH);
		}
		else
		{
			$salt = substr($salt, 0, SALT_LENGTH);
		}
		return $salt . sha1($pwd . $salt);
	}

	// Check User Levels
	function Owner_Driver()
	{ // Loading Station
		if($_SESSION['user_level'] == OWNER_DRIVER_LEVEL) { return 10; }else{ return 0 ; }
	}
	
	function Loader()
	{ // Loading Station
		if($_SESSION['user_level'] == LOADING_LEVEL) { return 9; }else{ return 0 ; }
	}
	
	function Operator()
	{ // Terminal member
		if($_SESSION['user_level'] == OPERATOR_LEVEL) { return 8; }else{ return 0 ; }
	}
	
	function Driver()
	{ // Terminal member
		if($_SESSION['user_level'] == DRIVER_LEVEL) { return 7; }else{ return 0 ; }
	}
	
	function Officer()
	{ // Officer
		if($_SESSION['user_level'] == OFFICER_LEVEL) { return 6; }else{ return 0 ; }
	}
	
	function WM()
	{ // webmaster
		if($_SESSION['user_level'] == ADMINISTRATOR_LEVEL) { return 5; }else{ return 0 ; }
	}
	
	function Admin()
	{ // Admin
		if($_SESSION['user_level'] == ADMIN_LEVEL) { return 4; }else{ return 0 ; }
	}
	
	function Collector()
	{ // moderator
		if($_SESSION['user_level'] == COLLECTOR_LEVEL) { return 3; }else{ return 0 ; }
	}
	
	function Dispatcher()
	{ // group leader
		if($_SESSION['user_level'] == DISPATCHER_LEVEL) { return 2; }else{ return 0 ; }
	}
	
	function Registered()
	{ // member
		if($_SESSION['user_level'] == REGISTERED_LEVEL) { return 1; }else{ return 0 ; }
	}
	
	
// END FUNCTION SECTION	
?>