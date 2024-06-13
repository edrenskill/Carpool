<?php
require_once '../../settings/connect.php';
if(session_id() == '') { page_protect(); } // START SESSIONS
foreach($_POST as $key => $value) { $data[$key] = filter($value); }

// SET THE DATE
$date = date('Y-m-d H:i:s');

// Board Passenger
if(isset($data["reportdriver"]))
{	
	//trim and lowercase ID
	$UID = $data['UID'];
	$subject = strtoupper($data['subject']);
	$reportcontent = $data['reportcontent'];
	
	IF (!isset($subject) || $subject == ''):
		die ("<div class='alert alert-danger'>Please enter subject!</div>");
	ELSEIF (!isset($reportcontent) || $reportcontent == ''):
		die ("<div class='alert alert-danger'>Please enter content!</div>");
	ELSE:
	
		$gen_ID = mysqli_real_escape_string($link, GenID());
				
		$duplicates = mysqli_query($link, "SELECT report_ID FROM ".DB_PREFIX."reporting WHERE report_ID='{$gen_ID}'");
		WHILE(mysqli_fetch_array($duplicates)){
			$gen_ID = mysqli_real_escape_string($link, GenID());
			WHILE($gen_ID <= 2018500){
				$gen_ID = mysqli_real_escape_string($link, GenID());
			}
		}

		$UID = filter_var($UID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
		
		mysqli_query($link, "INSERT INTO ".DB_PREFIX."reporting (`user_ID`,`subject`,`reason`,`report_ID`,`date_time`) VALUES ('$UID','$subject','$reportcontent','$gen_ID','$date')") or die("Report Failed:" . mysqli_error($link));
			die("
				<div class='alert alert-success'>Your report has been submited.</div><br>
				<span class='text-center text-success'><h3>Your Report ID: ".$gen_ID."</h3></span>
			");
	ENDIF;
}


// Board Passenger
if(isset($data["replyreport"]))
{	
	//trim and lowercase ID
	$rID = $data['rID'];
	$avatar = $data['avatar'];
	$reportcontent = $data['reportcontent'];
	$userID = $_SESSION['act_ID'];
	
	IF (!isset($reportcontent) || $reportcontent == ''):
		die ("<div class='alert alert-danger'>Please enter reply content</div>");
	ELSE:

		$UID = filter_var($UID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
		
		mysqli_query($link, "INSERT INTO ".DB_PREFIX."reporting (`user_ID`,`reason`,`report_ID`,`date_time`,`reply`) VALUES ('$userID','$reportcontent','$rID','$date','2')") or die("Report Failed:" . mysqli_error($link));
			die("
				<li class='left clearfix'>
					<span class='chat-img pull-left'>
						<a href='gen_id_bridge?memberID=".$_SESSION['act_ID']."'><img src='".$avatar."' alt='User Avatar' class='img-circle' height='40' width='40'/></a>
					</span>
					<div class='chat-body clearfix'>
						<div class='header'>
							<small class='pull-right text-muted'>
								<i class='fa fa-clock-o fa-fw'></i> <strong>".$date."</strong>
							</small>
							<strong class='primary-font'>".$_SESSION['full_name']."</strong>
						</div>
						<p>".$reportcontent."</p>
					</div>
				</li>
			");
	ENDIF;
}
?>