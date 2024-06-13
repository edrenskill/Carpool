<?php
require_once '../../settings/connect.php';
if(session_id() == '') { page_protect(); } // START SESSIONS
foreach($_POST as $key => $value) { $data[$key] = filter($value); }

// SET THE DATE
$date = date('Y-m-d H:i:s');

// Reply to post
if(isset($data["replyreport"]))
{	
	//trim and lowercase ID
	$rID = $data['rID'];
	$avatar = $data['avatar'];
	$reportcontent = $data['reportcontent'];
	
	IF (!isset($reportcontent) || $reportcontent == ''):
		die ("<div class='alert alert-danger'>Please enter reply content</div>");
	ELSE:

		$UID = filter_var($UID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
		$userID = $_SESSION['act_ID'];
		mysqli_query($link, "INSERT INTO ".DB_PREFIX."reporting (`user_ID`,`reason`,`report_ID`,`date_time`,`reply`) VALUES ('$userID','$reportcontent','$rID','$date','1')") or die("Report Failed:" . mysqli_error($link));
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

// Change status
if(isset($data["changestat"]))
{	
	//trim and lowercase ID
	$rID = $data['rID'];
	
	mysqli_query($link, "UPDATE ".DB_PREFIX."reporting SET report_status='2' WHERE report_status='1' AND report_ID='{$rID}' AND reply='0'") or die("Update Failed:" . mysqli_error($link));
		die("<span class='text-success'>Resolved</span>");
}
?>