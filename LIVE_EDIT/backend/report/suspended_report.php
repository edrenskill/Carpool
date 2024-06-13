<?php
// Header<?php
include '../../settings/connect.php';
if(session_id() == '') { page_protect(); } // START SESSIONS
if(!isset($_SESSION['ADM'])): header('location: login'); ENDIF;
require('fpdf.php');
$date = date("F j, Y, g:i a");

class PDF extends FPDF
{

// Load data
function LoadData($file)
{
	// Read file lines
	$lines = file($file);
	$data = array();
	foreach($lines as $line)
		$data[] = explode(';',trim($line));
	return $data;
}

// Colored table
function ReportTable($header, $data)
{
	// Colors, line width and bold font
	$this->SetFillColor(128,128,128);
	$this->SetTextColor(255);
	$this->SetDrawColor(0,0,0);
	$this->SetLineWidth(.3);
	$this->SetFont('','B');
	// Header
	$w = array(40, 40, 85);


	for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],7,$header[$i],1,0,'C',true);
	$this->Ln();
	// Color and font restoration
	$this->SetFillColor(211,211,211);
	$this->SetTextColor(0);
	$this->SetFont('');
	// Data
	$fill = false;
	foreach($data as $row)
	{
		$this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
		$this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
		$this->Cell($w[2],6,$row[2],'LR',0,'L',$fill);	
		$this->Ln();
		$fill = !$fill;
	}
	// Closing line
	$this->Cell(array_sum($w),0,'','T');
}

function Header()
{
    // Logo
    $this->Image('../../myaccount/images/CEC-Logo.png',10,6,30);
    // Arial bold 15
    $this->SetFont('Arial','B',12);
    // Move to the right
    $this->Cell(50);
	$this->Cell(100,10,'Member Suspended Report',0,0,'C');
	// Line break
    $this->Ln(20);
}

// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}
}

/////////////////////////////// GENERATE REPORT CONTENT //////////////////////////
	// FETCH REPORT DATA
	$file = "reportfile/member_report/member_status_report-".$date.".txt";
	$f = fopen($file, 'w'); // Open in write mode
	$suspend_ID = $_SESSION['suspendID'];

	$userID = mysqli_fetch_array(mysqli_query($link, "SELECT fname, mname, lname, suffix, user_ID FROM ".DB_PREFIX."users WHERE user_ID='{$suspend_ID}'"));
	$fullname = $userID['fname']." ".$userID['lname'].(($userID['suffix'])? ", ".$userID['suffix'] : "");

	$checksuspend_query = mysqli_query($link, "SELECT A.status, A.date_from, A.date_to, A.remarks, B.username, B.fname, B.mname, B.lname, B.suffix, B.photo, B.signature, B.regdate, B.city, B.user_ID, B.gen_card FROM ".DB_PREFIX."driver_status A, ".DB_PREFIX."users B WHERE B.user_ID=A.driver_ID AND A.status!=2");
	WHILE($member = mysqli_fetch_array($checksuspend_query)){												
	$datefrom = $member['date_from'];
	$dateto = $member['date_to'];
	$remarks = $member['remarks'];

		$accounts = "$datefrom;$dateto;$remarks\n";

		fwrite($f, $accounts);	
		
	}

	fclose($f);

	//OPEN GENERATED REPORT FILE
	$pdf = new PDF();
	// Column headings
	$header = array('Date From', 'Date To', 'Remarks');
	// Data loading
	$data = $pdf->LoadData('reportfile/member_report/member_status_report-'.$date.'.txt');
	$pdf->SetFont('Arial','',11);
	$pdf->AddPage();
	$pdf->Cell(0,10,'Report Date Generated: '.$date,0,1);
	$pdf->Cell(0,10,'Member Name: '.$fullname,0,1);
	$pdf->Cell(0,10,'Member Name: '.$suspend_ID,0,1);
	//$pdf->Cell(0,10,'Member Name: '.$fullname,0,1);
	$pdf->ReportTable($header,$data);
	$pdf->Output();
?>