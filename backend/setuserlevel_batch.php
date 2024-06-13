<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	if(!isset($_SESSION['ADM'])): header('location: login'); ENDIF;
	
	foreach($_POST as $key => $value) { $data[$key] = filter($value); }
	
	IF(isset($_POST) && array_key_exists('setulevel',$_POST)):
	
		$checklevel = $data['selectuserlevel'];
		IF($checklevel == 1):
			$_SESSION['userleveldisplay'] = '';
			$_SESSION['userleveldisplay2'] = '';
			$_SESSION['membertype'] = "All Level";
		ELSEIF($checklevel == 2):
			$_SESSION['userleveldisplay'] = 'userlevel = 1 AND';
			$_SESSION['userleveldisplay2'] = 'B.userlevel = 1 AND';
			$_SESSION['membertype'] = "Commuters";
		ELSEIF($checklevel == 3):
			$_SESSION['userleveldisplay'] = 'userlevel = 7 AND';
			$_SESSION['userleveldisplay2'] = 'B.userlevel = 7 AND';
			$_SESSION['membertype'] = "Drivers";
		ELSEIF($checklevel == 4):
			$_SESSION['userleveldisplay'] = 'userlevel = 8 AND';
			$_SESSION['userleveldisplay2'] = 'B.userlevel = 8 AND';
			$_SESSION['membertype'] = "Vehicle Owner";
		ELSEIF($checklevel == 5):
			$_SESSION['userleveldisplay'] = 'userlevel = 10 AND';
			$_SESSION['userleveldisplay2'] = 'B.userlevel = 10 AND';
			$_SESSION['membertype'] = "Vehicle Owner / Diver";
		ELSEIF($checklevel == 6):
			$_SESSION['userleveldisplay'] = 'userlevel = 2 AND';
			$_SESSION['userleveldisplay2'] = 'B.userlevel = 2 AND';
			$_SESSION['membertype'] = "Dispatchers";
		ELSEIF($checklevel == 7):
			$_SESSION['userleveldisplay'] = 'userlevel = 4 AND';
			$_SESSION['userleveldisplay2'] = 'B.userlevel = 4 AND';
			$_SESSION['membertype'] = "Admins";
		ELSE:
			$_SESSION['userleveldisplay'] = '';
			$_SESSION['userleveldisplay2'] = '';
			$_SESSION['membertype'] = "All Level";
		ENDIF;
		
		header ("location: dashboard");
	ENDIF;
	
	
	
	
	//BATCH
	IF(isset($_POST) && array_key_exists('setbatch',$_POST)):
		$checkbatch = $data['batchname'];
		IF(!isset($checkbatch) || $checkbatch == 0 || $checkbatch == ""):
			$_SESSION['batchdisplay'] = '';
			$_SESSION['batchdisplay2'] = '';
			$_SESSION['batchlevel'] = 'All Batch';
		ELSE:
			$batchselect = mysqli_fetch_array(mysqli_query($link, "SELECT  ID, batch_name FROM ".DB_PREFIX."member_batch WHERE ID = {$checkbatch}"));
			$_SESSION['batchdisplay'] = 'batch_ID = '.$checkbatch.' AND';
			$_SESSION['batchdisplay2'] = 'B.batch_ID = '.$checkbatch.' AND';
			$_SESSION['batchlevel'] = strtoupper($batchselect['batch_name']);
		ENDIF;
		
		header ("location: dashboard");
	ENDIF;
?>