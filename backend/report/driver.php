<?php
include '../../settings/connect.php';
if(session_id() == '') { page_protect(); } // START SESSIONS
if(!isset($_SESSION['ADM'])): header('location: login'); ENDIF;

FOREACH($_GET as $key => $value) { $content[$key] = filter($value); }
$owner_ID = $content['driverID'];
$_SESSION['ID'] = $owner_ID;
$application_type = $content['type'];
IF($application_type == 7): $type = "Driver"; ELSE: $type = "Operator/Driver"; ENDIF;
$address = mysqli_fetch_assoc(mysqli_query($link, "SELECT A.fname, A.mname, A.lname, A.suffix, A.photo, A.dob, A.marital, A.regdate, A.mobile, A.tel, A.email, A.address, B.name AS Bname, C.name AS Cname, D.name AS Dname, E.drivers_license, E.DL_expiry, E.NBI, E.NBI_expiry, E.police_clearance, E.police_expiry FROM ".DB_PREFIX."users A, ".DB_PREFIX."barangays B, ".DB_PREFIX."city_municipality C, ".DB_PREFIX."provinces D, ".DB_PREFIX."driver_credentials E WHERE A.user_ID='{$owner_ID}' AND B.brgy_code=A.barangay AND C.cm_code=A.city AND D.p_code=A.province AND E.driver_ID=A.user_ID")); 
$check_suffix = $address['suffix'];
IF($check_suffix != ""): $suffix = ", ".$check_suffix; ELSE: $suffix = ""; ENDIF; 
$firstname = strtoupper($address['fname']);
$middlename = strtoupper($address['mname']);
$lastname = strtoupper($address['lname']);
$photo = $address['photo'];
$_SESSION['photo'] = $photo; 
$dob = $address['dob'];
$marital = $address['marital'];
$tel = $address['tel'];
$mobile = $address['mobile'];
$email = $address['email'];
$DL = $address['drivers_license'];
$DL_expiry = $address['DL_expiry'];
$NBI = $address['NBI'];
$NBI_expiry = $address['NBI_expiry'];
$police = $address['police_clearance'];
$police_expiry = $address['police_expiry'];
$street =  ucwords($address['address']);
$brgy = ucwords($address['Bname']);
$city = ucwords($address['Cname']);
$province = ucwords($address['Dname']);
$regdate = date("M. d, Y", strtotime($address['regdate']));
$year = date("Y");

	$adob = date("m-d-Y", strtotime($dob));
	$adob = explode("-", $adob);
	//get age from date or birthdate
	$age = (date("md", date("U", mktime(0, 0, 0, $adob[0], $adob[1], $adob[2]))) > date("md") ? ((date("Y") - $adob[2]) - 1) : (date("Y") - $adob[2]));

	IF($marital == 0): $status = "Single"; ELSEIF($marital == 1): $status = "Married"; ELSEIF($marital == 2): $status = "Esparated"; ELSEIF($marital == 3): $status = "Widdow"; ENDIF;

/////////// RESIZE PHOTO IF UPLOADED ///////////
IF($photo != ""):

	$im = imagecreatefrompng('../../myaccount/members/'.$owner_ID.'/'.$photo);
	$size = min((imagesx($im)-20), imagesy($im));
	$im2 = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => $size, 'height' => $size]);
	if ($im2 !== FALSE) {
		imagepng($im2, '../../myaccount/members/'.$owner_ID.'/t'.$photo);
		imagedestroy($im2);
	}
	imagedestroy($im);
ENDIF;

require('fpdf.php');

class PDF extends FPDF
{
// Page header
function Header()
{
    // Logo
     $this->Image('../../myaccount/images/CEC-Logo.png',65,5,80);
    // Arial bold 15
    $this->SetFont('Arial','B',25);
    // Move to the Left
    $this->Cell(5);
    // Title
    $this->Cell(0,75,'Driver\'s Application Form',0,0,'L');
	
	IF($_SESSION['photo'] != ""):
		 $this->Image('../../myaccount/members/'.$_SESSION['ID'].'/t'.$_SESSION['photo'],160,35,40);
		 unlink('../../myaccount/members/'.$_SESSION['ID'].'/t'.$_SESSION['photo']);
	ELSE:
		// Move picture to the Right
		$this->Cell(-45);
		// photo box
		$this->SetFont('Arial','',25);
		$this->Cell(35,35,'2x2',1,0,'C'); 
		$this->SetFont('Arial','',10);
		$this->Cell(-35,50,'PICTURE',0,0,'C');
	ENDIF;
    // Line break
    $this->Ln(40);
}

// Page footer
function Footer()
{
	// Position at 1.5 cm from bottom
	$this->SetY(-30);
	// Arial italic 8
	$this->SetFont('Arial','I',12);
	// Page number
	$this->Cell(0,10,'CARPOOL EXPRESS       to serve the public on a safe, convinient, efficient and reliable transportation.',0,0,'C');
	$this->Cell(0,12,'__________________________________________________________________________________',0,0,'R');
	$this->SetFont('Arial','',10);
	$this->Cell(-10,22,'851 unit 9 Crownland Bldg.                               carpoolexpressco@gmail.com                               Tel. # 697-7326',0,0,'R');
	$this->Cell(-70,32,'EDSA cor. Mother Ignacia                                 info@carpoolphil.net',0,0,'R');	
	$this->Cell(13,42,'Quezon City                                                       website: www.carpoolphil.net',0,0,'R');	
}
}

// Instanciation of inherited class
$pdf = new PDF('P','mm','LEGAL');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->Cell(0,5,'',0,1);

$pdf->SetFont('Arial','',12);
$pdf->Cell(0,40,'Type of Application: '.$type,0,1);

// Name Details
$pdf->Cell(0,0,'Full Name: __________________________________________________________________________',0,1);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(50,0,$firstname,0,1,'R');
$pdf->Cell(170,0,$middlename,0,1,'C');
$pdf->Cell(300,0,$lastname,0,1,'C');
$pdf->Cell(0,0,$suffix,0,1,'R');
$pdf->SetFont('Arial','',12);

$pdf->SetFont('Arial','',7);
$pdf->Cell(70,7,'(First Name)',0,1,'C');
$pdf->Cell(170,-7,'(Middle Name)',0,1,'C');
$pdf->Cell(300,7,'(Last Name)',0,1,'C');
$pdf->Cell(0,-7,'(Suffix)',0,1,'R');

// Address details
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,30,'Complete Address: ___________________________________________________________________',0,1);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(160,-30,$street,0,1,'C');
$pdf->Cell(300,30,$brgy,0,1,'C');
$pdf->SetFont('Arial','',12);

$pdf->SetFont('Arial','',7);
$pdf->Cell(100,-23,'(House / Street)',0,1,'C');
$pdf->Cell(300,23,'(Barangay)',0,1,'C');

$pdf->SetFont('Arial','B',12);
$pdf->Cell(100,0,$city,0,1,'C');
$pdf->Cell(250,0,$province,0,1,'C');
$pdf->SetFont('Arial','',12);

$pdf->Cell(0,0,'________________________________________________________________________________',0,1,'R');
$pdf->SetFont('Arial','',7);
$pdf->Cell(100,7,'(City / Municipality)',0,1,'C');
$pdf->Cell(250,-7,'(Province)',0,1,'C');

// Personal Details
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,30,'Date of Birth: _________________            Age: ___________          Civil Status: __________________',0,1);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(90,-30,date("M. d, Y", strtotime($dob)),0,1,'C');
$pdf->Cell(200,30,$age,0,1,'C');
$pdf->Cell(340,-30,$status,0,1,'C');

// Contact Details
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,60,'Residence/Office Contact No: ___________________                   Mobile No: ____________________',0,1);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(85,-60,$tel,0,1,'R');
$pdf->Cell(180,60,$mobile,0,1,'R');

$pdf->SetFont('Arial','',12);
$pdf->Cell(0,-30,'Email Address: ____________________________________',0,1);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(72,30,$email,0,1,'R');

// Cridentials
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,0,'Driver\'s License No.: ___________________',0,1);
$pdf->Cell(190,0,'Expiration Date: ___________________',0,1,'R');
$pdf->SetFont('Arial','B',12);
$pdf->Cell(70,0,$DL,0,1,'R');
$pdf->Cell(180,0,$DL_expiry,0,1,'R');

$pdf->SetFont('Arial','',12);
$pdf->Cell(0,30,'NBI Clearance: ___________________',0,1);
$pdf->Cell(190,-30,'Expiration Date: ___________________',0,1,'R');
$pdf->SetFont('Arial','B',12);
$pdf->Cell(70,30,$NBI,0,1,'R');
$pdf->Cell(180,-30,$NBI_expiry,0,1,'R');

$pdf->SetFont('Arial','',12);
$pdf->Cell(0,60,'Police Clearance: ___________________',0,1);
$pdf->Cell(190,-60,'Expiration Date: ___________________',0,1,'R');
$pdf->SetFont('Arial','B',12);
$pdf->Cell(70,60,$police,0,1,'R');
$pdf->Cell(180,-60,$police_expiry,0,1,'R');

// Date of application
$pdf->SetFont('Arial','',12);
$pdf->Cell(100,90,'Application Date: ___________________',0,1,'R');
$pdf->SetFont('Arial','B',12);
$pdf->Cell(90,-90,$regdate,0,1,'R');

// Signature
$pdf->SetFont('Arial','',12);
$pdf->Cell(190,100,'_____________________',0,1,'R');
$pdf->SetFont('Arial','B',10);
$pdf->Cell(190,-90,'SIGNATURE OF APPLICANT',0,1,'R');
$pdf->SetFont('Arial','',7);
$pdf->Cell(183,95,'(Signature over printed name)',0,1,'R');

$pdf->SetFont('Arial','',8);
$pdf->Cell(30,-80,'Note.',0,1,'R');
$pdf->Cell(80,90,'1. Please attach the following requirements:',0,1,'R');
$pdf->Cell(105,-80,'a. Photo copy of NBI Clearance.',0,1,'C');
$pdf->Cell(108,90,'b. Photo copy of Police Clearance.',0,1,'C');
$pdf->Cell(105,-80,'c. Photo copy of Driver\' License.',0,1,'C');
$pdf->Cell(114,90,'2. This application is NULL and VOID without the above requirements.',0,1,'R');



$pdf->Output();
unset($_SESSION['ID'], $_SESSION['photo']);
?>