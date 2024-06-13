<?php
include '../../settings/connect.php';
if(session_id() == '') { page_protect(); } // START SESSIONS
if(!isset($_SESSION['ADM'])): header('location: login'); ENDIF;

FOREACH($_GET as $key => $value) { $content[$key] = filter($value); }
$owner_ID = $content['ownerID'];
$address = mysqli_fetch_assoc(mysqli_query($link, "SELECT A.fname, A.mname, A.lname, A.suffix, A.address, B.name AS Bname, C.name AS Cname, D.name AS Dname FROM ".DB_PREFIX."users A, ".DB_PREFIX."barangays B, ".DB_PREFIX."city_municipality C, ".DB_PREFIX."provinces D WHERE A.user_ID='{$owner_ID}' AND B.brgy_code=A.barangay AND C.cm_code=A.city AND D.p_code=A.province")); 
$check_suffix = $address['suffix'];
IF($check_suffix != ""): $suffix = ", ".$check_suffix; ELSE: $suffix = ""; ENDIF; 
$fullname = strtoupper($address['fname']." ".substr($address['mname'],0, 1).". ".$address['lname'].$suffix);
$street =  ucwords($address['address']);
$brgy = ucwords($address['Bname']);
$city = ucwords($address['Cname']);
$province = ucwords($address['Dname']);
$year = date("Y");

require('fpdf.php');

class PDF extends FPDF
{

// INDENT FUNCTION
function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false, $indent=0)
{
    //Output text with automatic or explicit line breaks
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;

    $wFirst = $w-$indent;
    $wOther = $w;

    $wmaxFirst=($wFirst-2*$this->cMargin)*1000/$this->FontSize;
    $wmaxOther=($wOther-2*$this->cMargin)*1000/$this->FontSize;

    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 && $s[$nb-1]=="\n")
        $nb--;
    $b=0;
    if($border)
    {
        if($border==1)
        {
            $border='LTRB';
            $b='LRT';
            $b2='LR';
        }
        else
        {
            $b2='';
            if(is_int(strpos($border,'L')))
                $b2.='L';
            if(is_int(strpos($border,'R')))
                $b2.='R';
            $b=is_int(strpos($border,'T')) ? $b2.'T' : $b2;
        }
    }
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $ns=0;
    $nl=1;
        $first=true;
    while($i<$nb)
    {
        //Get next character
        $c=$s[$i];
        if($c=="\n")
        {
            //Explicit line break
            if($this->ws>0)
            {
                $this->ws=0;
                $this->_out('0 Tw');
            }
            $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $ns=0;
            $nl++;
            if($border && $nl==2)
                $b=$b2;
            continue;
        }
        if($c==' ')
        {
            $sep=$i;
            $ls=$l;
            $ns++;
        }
        $l+=$cw[$c];

        if ($first)
        {
            $wmax = $wmaxFirst;
            $w = $wFirst;
        }
        else
        {
            $wmax = $wmaxOther;
            $w = $wOther;
        }

        if($l>$wmax)
        {
            //Automatic line break
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
                if($this->ws>0)
                {
                    $this->ws=0;
                    $this->_out('0 Tw');
                }
                $SaveX = $this->x; 
                if ($first && $indent>0)
                {
                    $this->SetX($this->x + $indent);
                    $first=false;
                }
                $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
                    $this->SetX($SaveX);
            }
            else
            {
                if($align=='J')
                {
                    $this->ws=($ns>1) ? ($wmax-$ls)/1000*$this->FontSize/($ns-1) : 0;
                    $this->_out(sprintf('%.3f Tw',$this->ws*$this->k));
                }
                $SaveX = $this->x; 
                if ($first && $indent>0)
                {
                    $this->SetX($this->x + $indent);
                    $first=false;
                }
                $this->Cell($w,$h,substr($s,$j,$sep-$j),$b,2,$align,$fill);
                    $this->SetX($SaveX);
                $i=$sep+1;
            }
            $sep=-1;
            $j=$i;
            $l=0;
            $ns=0;
            $nl++;
            if($border && $nl==2)
                $b=$b2;
        }
        else
            $i++;
    }
    //Last chunk
    if($this->ws>0)
    {
        $this->ws=0;
        $this->_out('0 Tw');
    }
    if($border && is_int(strpos($border,'B')))
        $b.='B';
    $this->Cell($w,$h,substr($s,$j,$i),$b,2,$align,$fill);
    $this->x=$this->lMargin;
    }

// Page footer
function Footer()
{
	// Position at 1.5 cm from bottom
	$this->SetY(-15);
	// Arial italic 8
	$this->SetFont('Arial','I',8);
	// Page number
	$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}

//TABLE FUNCTION FOR VEHICLE DATA
function LoadData($file)
{
    // Read file lines
    $lines = file($file);
    $data = array();
    foreach($lines as $line)
        $data[] = explode(';',trim($line));
    return $data;
}

function VehicleDataTable($header, $data)
{
    // Column widths
    $w = array(30, 45, 45, 25, 40);
    // Header
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],6,$header[$i],0,0,'C');
    $this->Ln();
    // Data
    foreach($data as $row)
    {
        $this->Cell($w[0],6,strtoupper($row[0]),'',0,'C');
        $this->Cell($w[1],6,strtoupper($row[1]),'',0,'C');
        $this->Cell($w[2],6,strtoupper($row[2]),'',0,'C');
        $this->Cell($w[3],6,strtoupper($row[3]),'',0,'C');
		$this->Cell($w[4],6,strtoupper($row[4]),'',0,'C');
        $this->Ln();
    }
    // Closing line
    $this->Cell(array_sum($w),0,'','F');
} 
}


//CREATE LINE 1

		$route = mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(A.owner_ID) AS units, B.route_origin, B.route_destination FROM ".DB_PREFIX."vehicles A, ".DB_PREFIX."terminal B WHERE A.owner_ID='{$owner_ID}' AND B.terminal_ID=A.terminal_ID"));
		$unitcounter = $route['units'];
		$wordcount = Ucwords(NumberToWord($unitcounter));
		IF($unitcounter <= 1): $plu = "unit"; ELSE: $plu = "units"; ENDIF;
		$origin = $route['route_origin'];
		$destination = $route['route_destination'];

		$textfile1 = "That, applicant is a Filipino citizen, of legal age and with postal address at $street, $brgy, $city, $province.";
		$textfile2 = "That, pursuant to Department Order No. 2015-011, and LTFRB Memorandum Circular 2015-017 \"IMPLEMENTING GUIDELINES ON THE ACCEPTANCE OF APPLICATIONS FOR A CERTIFICATE OF PUBLIC CONVENIENCE TO OPERATE A TRANSPORTATION NETWORK VEHICLE SERVICE\", the applicant hereby apply for the issuance of a New Certificate of Public Convenience to operate Transportation Network Vehicle Service with application for Provisional Authority for the transportation of passengers and freight within the route of $origin - $destination with the use of $wordcount ($unitcounter) $plu to wit:";
		$textfile3 = "That, the applicant is financially capable to operate and maintain the service being applied for:";
		$textfile4 = "That, the applicant will comply with all the requirements of this Honorable Board and will abide with the existing LTFRB Rules and Regulations, the Public Service ACT and other pertinent laws/policies; and attached herewith the photocopy of requirements for application for Transport Network Vehicles Service, to wit:";
		$textfile5 = "That, with the consideration and approval of the application, public necessity and convenience will be promoted in a suitable manner.";
		$textfile6 = "it is most respectfully prayed unto this Honorable Board that this Application for a Certificate of Public Convenience to Operate a";
		$textfile7 = "TRANSPORTATION NETWORK VEHICLES SERVICE on a POINT TO POINT SERVICE CLASSIFICATION using VAN Unit";
		$textfile8 = "be accepted and considered. Thereafter, a Notice of Hearing be issued by this Honorable Board.";
		
		$page2text1 = "I, $fullname of legal age, Filipino with business address at $street, $brgy, $city, $province after having been duly sworn to in accordance with law hereby depose and state: ";
		$page2text2 = "I have not commenced any other action or proceeding involving the same issues in any other LTFRB Office;";
		$page2text3 = "To the best of my knowledge, no such other application, petition or pleading is pending therein;";
		$page2text4 = "If I learn of the same or similar application, petition or pleading has been filed or is pending I, shall report of such fact within five (5) days therefrom to this Honorable Board.";

// FETCH VEHICLE DATA
	$file = "reportfile/operator_applications/".$owner_ID.".txt";
	$f = fopen($file, 'w'); // Open in write mode

	$records = mysqli_query($link, "SELECT plate_number, make, model, engine, chassis FROM ".DB_PREFIX."vehicles WHERE owner_ID='{$owner_ID}'");
	while($unit = mysqli_fetch_array($records)) {

		$make = $unit['make'];
		$motor = $unit['engine'];
		$chassis = $unit['chassis'];
		$model = $unit['model'];
		$plate = $unit['plate_number'];

		$units = "$make;$motor;$chassis;$model;$plate\n";

		fwrite($f, $units);
	}

	fclose($f);

// Instanciation of inherited class
$pdf = new PDF('P','mm','LEGAL');
$pdf->AliasNbPages();
$pdf->AddPage();
$InterLigne = 5;
$pdf->SetMargins(20,0,15);

// TITLE
$pdf->SetFont('Times','B',12);
$pdf->Cell(0,0,'',0,1,'C');
$pdf->Cell(0,10,'REPUBLIC OF THE PHILLIPINES',0,1,'C');
$pdf->Cell(0,0,'Department of Transportation',0,1,'C');
$pdf->Cell(0,10,'LAND TRANSPORTATION FRANCHISING AND REGULATORY BOARD',0,1,'C');
$pdf->Cell(0,0,'East Avenue, Quezon City',0,1,'C');

// CASE NO.
$pdf->Cell(0,5,'',0,1);
$pdf->Cell(0,10,'Application for a Certificate of',0,1);
$pdf->Cell(0,0,'Public Convenience to operate a',0,1);
$pdf->Cell(320,0,'Case No.__________',0,1,'C');

$pdf->SetFont('Times','BU',12);
$pdf->Cell(0,10,'Transportation Network Vehicle Service',0,1);
$pdf->Cell(0,0,'For Point To Point (P2P) Classification',0,1);
$pdf->Cell(0,10,'Using Van Vehicle',0,1);

$pdf->Cell(0,5,'',0,1);
$pdf->Cell(0,10,$fullname,0,1);
$pdf->SetFont('Times','',12);
$pdf->Cell(50,0,'Applicant',20,1,'C');
$pdf->Cell(0,10,'x---------------------------------------x',0,1);

$pdf->SetFont('Times','BU',14);
$pdf->Cell(0,10,'APPLICATION',0,1,'C');
$pdf->Cell(0,0,'WITH PRAYER FOR THE ISSUANCE OF PROVISIONAL AUTHORITY',0,1,'C');


$pdf->SetFont('Times','B',12);
$pdf->Cell(0,5,'',0,1);
$pdf->Cell(55,10,'COMES NOW,',0,1,'C');
$pdf->SetFont('Times','',12);
$pdf->Cell(200,-10,'Applicant and unto this Honorable Board, respectfully alleges;',0,1,'C');

$pdf->Cell(0,7,'',0,1);
$pdf->ln(5);
$pdf->MultiCell(0,$InterLigne,$textfile1,0,'J',0,12);

$pdf->ln(5);
$pdf->MultiCell(0,$InterLigne,$textfile2,0,'J',0,12);

// TABLE VEHICLE OUTPUT
// Column headings
$pdf->Cell(0,7,'',0,1);
$pdf->SetMargins(20,0,15);
$header = array('MAKE', 'MOTOR NO.', 'CHASSIS NO.', 'MODEL', 'Con.Sticker/PLATE');
// Data loading
$data = $pdf->LoadData("reportfile/operator_applications/".$owner_ID.".txt");
$pdf->SetFont('Times','B',12);
$pdf->VehicleDataTable($header,$data);

$pdf->SetMargins(20,0,15);
$pdf->SetFont('Times','',12);
$pdf->ln(5);
$pdf->MultiCell(0,$InterLigne,$textfile3,0,'J',0,12);

$pdf->ln(5);
$pdf->MultiCell(0,$InterLigne,$textfile4,0,'J',0,12);

$pdf->SetMargins(30,0,30);
$pdf->ln(5);
$pdf->MultiCell(0,$InterLigne,'a)	Live Birth issued by the Philippines Statistic Authority marked as',0,'J',0,12);
$pdf->SetFont('Times','B',12);
$pdf->ln(-5);
$pdf->MultiCell(0,$InterLigne,'"ANNEX A"',0,'R',0);

$pdf->ln(0);
$pdf->SetFont('Times','',12);
$pdf->MultiCell(0,$InterLigne,'b)	Business Name issued by DTI marked as',0,'J',0,12);
$pdf->SetFont('Times','B',12);
$pdf->ln(-5);
$pdf->MultiCell(0,$InterLigne,'"ANNEX B"',0,'R',0);

$pdf->ln(0);
$pdf->SetFont('Times','',12);
$pdf->MultiCell(0,$InterLigne,'c)	Location Map of Garage/Address of Operator and marked as',0,'J',0,12);
$pdf->SetFont('Times','B',12);
$pdf->ln(-5);
$pdf->MultiCell(0,$InterLigne,'"ANNEX C"',0,'R',0);

$pdf->ln(0);
$pdf->SetFont('Times','',12);
$pdf->MultiCell(0,$InterLigne,'d)	Vehicle OR/CR and marked as',0,'J',0,12);
$pdf->SetFont('Times','B',12);
$pdf->ln(-5);
$pdf->MultiCell(0,$InterLigne,'"ANNEX D"',0,'R',0);

$pdf->ln(0);
$pdf->SetFont('Times','',12);
$pdf->MultiCell(0,$InterLigne,'e)	Lot Title in the name of Applicant marked as',0,'J',0,12);
$pdf->SetFont('Times','B',12);
$pdf->ln(-5);
$pdf->MultiCell(0,$InterLigne,'"ANNEX E"',0,'R',0);

$pdf->ln(0);
$pdf->SetFont('Times','',12);
$pdf->MultiCell(0,$InterLigne,'f)	Operator’s Data Sheet marked as',0,'J',0,12);
$pdf->SetFont('Times','B',12);
$pdf->ln(-5);
$pdf->MultiCell(0,$InterLigne,'"ANNEX F"',0,'R',0);

$pdf->SetMargins(20,0,15);
$pdf->SetFont('Times','',12);
$pdf->ln(5);
$pdf->MultiCell(0,$InterLigne,$textfile5,0,'J',0,12);

$pdf->SetFont('Times','BI',12);
$pdf->Cell(0,5,'',0,1);
$pdf->Cell(55,5,'WHEREFORE,',0,1,'C');

$pdf->SetFont('Times','',12);
$pdf->ln(-5);
$pdf->MultiCell(0,$InterLigne,$textfile6,0,'J',0,43);

$pdf->SetFont('Times','BU',12);
$pdf->ln(-5);
$pdf->MultiCell(0,$InterLigne,$textfile7,0,'J',0,90);

$pdf->SetFont('Times','',12);
$pdf->ln(-5);
$pdf->MultiCell(0,$InterLigne,$textfile8,0,'J',0,155);

$pdf->Cell(0,15,'Quezon City, _____________'.$year,0,1);

$pdf->SetMargins(120,0,15);
$pdf->ln(5);
$pdf->SetFont('Times','BU',12);
$txt = $fullname;
$pdf->MultiCell(0,$InterLigne,$txt,0,'C',0); 

$pdf->ln(0);
$pdf->SetFont('Times','B',12);
$txt = "Applicant";
$pdf->MultiCell(0,$InterLigne,$txt,0,'C',0); 

$pdf->SetMargins(20,0,15);
$pdf->AddPage();
$pdf->SetFont('Times','B',12);

// TITLE PAGE 2
$pdf->Cell(0,10,'',0,1);
$pdf->Cell(0,10,'VERIFICATION AND CERTIFICATION',0,1,'C');
$pdf->Cell(0,0,'OF NON - FORUM SHOPPING',0,1,'C');

$pdf->Cell(0,10,'',0,1);
$pdf->SetFont('Times','',12);
$pdf->ln(5);
$pdf->MultiCell(0,$InterLigne,$page2text1,0,'J',0,12);

$pdf->SetMargins(40,0,40);
$pdf->ln(5);
$pdf->MultiCell(0,$InterLigne,'That I am the applicant in the above title case;',0,'J',0,0);

$pdf->ln(5);
$pdf->MultiCell(0,$InterLigne,'That I have caused the preparation of this Application; and',0,'J',0,0);

$pdf->ln(5);
$pdf->MultiCell(0,$InterLigne,'That I read the contents thereof and acknowledge that the same are true and correct to the best of my knowledge and belief.',0,'J',0,0);

$pdf->SetMargins(20,0,15);
$pdf->ln(5);
$pdf->MultiCell(0,$InterLigne,$page2text2,0,'J',0,12);

$pdf->ln(5);
$pdf->MultiCell(0,$InterLigne,$page2text3,0,'J',0,12);

$pdf->ln(5);
$pdf->MultiCell(0,$InterLigne,$page2text4,0,'J',0,12);

$pdf->Cell(0,30,'',0,1);
$pdf->SetMargins(120,0,15);
$pdf->ln(5);
$pdf->SetFont('Times','BU',12);
$txt = $fullname;
$pdf->MultiCell(0,$InterLigne,$txt,0,'C',0); 

$pdf->ln(0);
$pdf->SetFont('Times','',12);
$txt = "Affiant";
$pdf->MultiCell(0,$InterLigne,$txt,0,'C',0); 
$pdf->SetMargins(20,0,15);

$pdf->Output();
?>
