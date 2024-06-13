<?php
include '../../settings/connect.php';
if(session_id() == '') { page_protect(); } // START SESSIONS
if(!isset($_SESSION['ADM'])): header('location: login'); ENDIF;
require('fpdf.php');



IF(isset($_SESSION['frompayout']) || isset($_SESSION['fromdrivertripreport']) || isset($_SESSION['fromremitted'])):
	// SET DATE ANG TERMINAL CONFIGURATION
	$terminal_settings = mysqli_fetch_array(mysqli_query($link, "SELECT cut_off FROM ".DB_PREFIX."terminal_settings"));
	$cutoff_time = $terminal_settings['cut_off'];
	$date = date('Y-m-d',strtotime("-1 days"));
	$stat = $_SESSION['stat'];
	
	IF(!isset($_SESSION['selecteddate'])):
		$cutoff_start = $date." ".$cutoff_time;
		$cutoff_end = date('Y-m-d')." ".$cutoff_time;
		$start = $date;
		$end = date('Y-m-d');
	ELSE:
		$cutoff_start = $_SESSION['cutstart']." ".$cutoff_time;
		$cutoff_end = $_SESSION['cutend']." ".$cutoff_time;
		$start = $_SESSION['cutstart'];
		$end = $_SESSION['cutend'];
	ENDIF;

	IF($_SESSION['terminal_ID'] != ""):
		IF($_SESSION['terminal_ID'] == "all"):
			$terminal_ID = "all";
			$conditionset = "";
		ELSE:
			$terminal_ID = $_SESSION['terminal_ID'];
			$conditionset = "A.terminal_ID='{$terminal_ID}' AND ";
		ENDIF;
	ELSE:
		$conditionset = "";
	ENDIF;
ELSEIF(isset($_SESSION['frompidcardinventory'])):
	$date = date("F j, Y, g:i a");
ENDIF;
/// SET CONFIG DATA ///


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
	
	IF(isset($_SESSION['frompayout']) || isset($_SESSION['fromremitted'])):
		$w = array(55, 20, 30, 20, 30, 35);
	ELSEIF(isset($_SESSION['fromdrivertripreport'])):
		$w = array(45, 40, 35, 35, 35);
	ELSEIF(isset($_SESSION['frompidcardinventory'])):
		$w = array(65, 35, 30, 25, 40);
	ENDIF;
	

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
		
		
		IF(isset($_SESSION['frompayout'])|| isset($_SESSION['fromremitted'])):
		
			$this->Cell($w[2],6,$row[2],'LR',0,'L',$fill);
			$this->Cell($w[3],6,number_format($row[3]),'LR',0,'C',$fill);
			$this->Cell($w[4],6,"Php ".number_format($row[4], 2),'LR',0,'R',$fill);
			$this->Cell($w[5],6,$row[5],'LR',0,'L',$fill);
		
		ELSEIF(isset($_SESSION['fromdrivertripreport'])):
		
			$this->Cell($w[2],6,$row[2],'LR',0,'C',$fill);
			$this->Cell($w[3],6,number_format($row[3], 2),'LR',0,'R',$fill);
			$this->Cell($w[4],6,"Php ".number_format($row[4], 2),'LR',0,'R',$fill);
		
		ELSEIF(isset($_SESSION['frompidcardinventory'])):
			
			$this->Cell($w[2],6,$row[2],'LR',0,'R',$fill);
			$this->Cell($w[3],6,$row[3],'LR',0,'R',$fill);
			$this->Cell($w[4],6,$row[4],'LR',0,'R',$fill);

		ENDIF;
	
		
		
		
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
	
	IF(isset($_SESSION['frompayout']) || isset($_SESSION['fromremitted'])):
		// Diver Report
		$this->Cell(100,10,'Drivers Trip Report',0,0,'C');
	ELSEIF(isset($_SESSION['fromdrivertripreport'])):
		// Diver Report
		$this->Cell(100,10,'Individual Trip Report',0,0,'C');
    ELSE:
		// Card Report
		 $this->Cell(100,10,'Card Inventory Report',0,0,'C');
	ENDIF;
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
IF(isset($_SESSION['frompayout']) || isset($_SESSION['fromremitted'])):
	// FETCH REPORT DATA
	$file = "reportfile/group/group-report-".$start."-".$end.".txt";
	$f = fopen($file, 'w'); // Open in write mode

	$records = mysqli_query($link, "SELECT COUNT(A.ID) AS totaltrip, A.driver_ID, A.terminal_ID, A.trip_ID, B.trip_ID, SUM(B.total_fare) AS totalfare, SUM(B.service_fee) AS totalfee, B.bank_ref_no FROM ".DB_PREFIX."vehicle_trip_history A, ".DB_PREFIX."driver_account B WHERE {$conditionset} B.trip_ID=A.trip_ID AND A.time_date BETWEEN '{$cutoff_start}' AND '{$cutoff_end}' AND remitted='{$stat}' GROUP BY A.driver_ID");
		$totalpayable = 0;
		$totalTrips = 0;
		
	while($drivers = mysqli_fetch_array($records)) {

		$user_ID = $drivers['driver_ID'];
		$total_incentive = $drivers['totalfare'];
		$total_service_fee = $drivers['totalfee'];
		$overall_total = 0;
		$bank_ref = $drivers['bank_ref_no'];

		$trips = $drivers['totaltrip'];

		$driver = mysqli_fetch_array(mysqli_query($link, "SELECT fname, mname, lname, suffix, user_ID FROM ".DB_PREFIX."users WHERE user_ID='{$user_ID}' AND banned=0"));
		$fullname = $driver['fname']." ".$driver['lname'].(($driver['suffix'])? ", ".$driver['suffix'] : "");

		$bank = mysqli_fetch_array(mysqli_query($link, "SELECT a.bank_ID, a.account_no, b.name, b.Abbreviation FROM ".DB_PREFIX."bank_accounts a, ".DB_PREFIX."banks b WHERE a.user_ID = '{$user_ID}' AND a.status=1 AND a.bank_ID=b.ID"));

		$overall_total = $total_incentive - $total_service_fee;

		$DName = strtoupper($fullname);
		$Bank = $bank['Abbreviation'];
		$Account = $bank['account_no'];
		
		$Trips = $trips;
		$Amount = $overall_total;
		
		$totalpayable += $Amount;
		$totalTrips += $Trips;

		$accounts = "$DName;$Bank;$Account;$Trips;$Amount;$bank_ref\n";

		fwrite($f, $accounts);
	}

	fclose($f);


	//OPEN GENERATED REPORT FILE
	$pdf = new PDF();
	// Column headings
	$header = array('Driver Name', 'Bank', 'Account No.', '# of Trips', 'Amount', 'Reference No.');
	// Data loading
	$data = $pdf->LoadData('reportfile/group/group-report-'.$start.'-'.$end.'.txt');
	$pdf->SetFont('Arial','',11);
	$pdf->AddPage();
	$pdf->Cell(0,10,'Period covered: From '.$start.' to '.$end,0,1);
	$pdf->ReportTable($header,$data);
	$pdf->Cell(0,0,'',0,1,'L');
	$pdf->Cell(0,10,'Total Number of trips: '.$totalTrips,0,1,'L');
	$pdf->Cell(0,10,'Total amount payable: Php '.number_format($totalpayable, 2),0,1,'L');
	$pdf->Output();
	
ELSEIF(isset($_SESSION['fromdrivertripreport'])):

	$driver_ID = $_SESSION['driverID'];
	
	// FETCH REPORT DATA
	$file = "reportfile/individual/individual-report-".$start."-".$end."-".$driver_ID.".txt";
	$f = fopen($file, 'w'); // Open in write mode
	
	$driver = mysqli_fetch_array(mysqli_query($link, "SELECT fname, mname, lname, suffix, user_ID FROM ".DB_PREFIX."users WHERE user_ID='{$driver_ID}' AND banned=0"));
	$fullname = $driver['fname']." ".$driver['lname'].(($driver['suffix'])? ", ".$driver['suffix'] : "");

	$overall_total = 0;
	$overall_fare = 0;
	$overall_service_fee = 0;

	$records = mysqli_query($link, "SELECT A.trip_ID, A.time_date, B.total_fare, B.service_fee, A.passenger FROM commuter_vehicle_trip_history A, commuter_driver_account B WHERE {$conditionset} A.driver_ID='{$driver_ID}' AND A.trip_ID=B.trip_ID AND A.time_date BETWEEN '{$cutoff_start}' AND '{$cutoff_end}' AND remitted='{$stat}'");
	WHILE($drivers = mysqli_fetch_array($records)){
		$trip_ID = strtoupper($drivers['trip_ID']);
		$date = $drivers['time_date'];
		$fare = $drivers['total_fare'];
		$service_fee = $drivers['service_fee'];
		$passenger = $drivers['passenger'];
		$overall_fare += $fare;
		$overall_service_fee += $service_fee;
		
		$accounts = "$date;$trip_ID;$passenger;$fare;$service_fee\n";

		fwrite($f, $accounts);
	}
	$overall_total = $overall_fare - $overall_service_fee;
	fclose($f);


	//OPEN GENERATED REPORT FILE
	$pdf = new PDF();
	// Column headings
	$header = array('Trip Date and Time', 'Trip ID', 'No. of Passengers', 'Total Fare', 'Service Fee');
	// Data loading
	$data = $pdf->LoadData('reportfile/individual/individual-report-'.$start.'-'.$end.'-'.$driver_ID.'.txt');
	$pdf->SetFont('Arial','',11);
	$pdf->AddPage();
	$pdf->Cell(0,10,'Driver Name: '.$fullname.'       Driver ID:  '.$driver_ID,0,1);
	$pdf->Cell(0,10,'Period covered: From '.$start.' to '.$end,0,1);
	$pdf->ReportTable($header,$data);
	$pdf->Cell(0,0,'',0,1,'L');
	$pdf->Cell(0,10,'Total Incentives: Php '.number_format($overall_total, 2),0,1,'L');
	$pdf->Output();
	
ELSEIF(isset($_SESSION['frompidcardinventory'])):

	// FETCH REPORT DATA
	$file = "reportfile/card_inventory/card-inventory-report-".$date.".txt";
	$f = fopen($file, 'w'); // Open in write mode

	$terminal = mysqli_query($link, "SELECT terminal_ID, terminal_name, operational FROM ".DB_PREFIX."terminal {$conditionset}");
														
	WHILE($terminalselect = mysqli_fetch_array($terminal)){
		$selectedID = $terminalselect['terminal_ID'];
		$terminal_name = strtoupper($terminalselect['terminal_name']);
														
		// Collect Card Record
		$totalcards = mysqli_fetch_array(mysqli_query($link, "SELECT COUNT(ID) AS total_cards, status  FROM ".DB_PREFIX."idcards WHERE terminal_ID='{$selectedID}'"));
		$activecard = mysqli_fetch_array(mysqli_query($link, "SELECT COUNT(ID) AS active_card, status  FROM ".DB_PREFIX."idcards WHERE terminal_ID='{$selectedID}' AND status=1"));
		$pendingcard = mysqli_fetch_array(mysqli_query($link, "SELECT COUNT(ID) AS pending_card, status  FROM ".DB_PREFIX."idcards WHERE terminal_ID='{$selectedID}' AND status=0"));
		
		$pending_card = $pendingcard['pending_card'];
		$active_card = $activecard['active_card'];
		$total_card =  $totalcards['total_cards'];

		$accounts = "$terminal_name;$selectedID;$active_card;$pending_card;$total_card\n";

		fwrite($f, $accounts);	
		
	}

	fclose($f);

	//OPEN GENERATED REPORT FILE
	$pdf = new PDF();
	// Column headings
	$header = array('Terminal Name', 'Terminal ID', 'Disposed Card', 'On Hand', 'Total Card Issued');
	// Data loading
	$data = $pdf->LoadData('reportfile/card_inventory/card-inventory-report-'.$date.'.txt');
	$pdf->SetFont('Arial','',11);
	$pdf->AddPage();
	$pdf->Cell(0,10,'Report Date Generated: '.$date,0,1);
	$pdf->ReportTable($header,$data);
	$pdf->Output();

ENDIF;
?>
