<?php
require_once '../../settings/connect.php';
if(session_id() == '') { page_protect(); } // START SESSIONS

foreach($_POST as $key => $value) { $data[$key] = filter($value); }
$terminal_ID = $_SESSION['terminal_ID'];
$dispatcher_ID = $_SESSION['act_ID'];

date_default_timezone_set("Asia/Manila");
$date = date("Y-m-d");
$time = date("h:i:s a");

IF(isset($_POST["TapCardSubmit"])):
	//Fetching Values from URL
	$dID=$data['dID'];

	$card_sale = mysqli_fetch_assoc(mysqli_query($link, "SELECT initial_card_load FROM ".DB_PREFIX."terminal_settings"));
	$check_record = mysqli_query($link, "SELECT card_number, card_type, disposed FROM ".DB_PREFIX."idcards WHERE terminal_ID='{$terminal_ID}' AND card_number='{$dID}'");
	$count_record=mysqli_num_rows($check_record);
	IF($count_record):

		$card_status = mysqli_fetch_assoc($check_record);
		$c_status = $card_status['disposed'];
		$card_type = $card_status['card_type'];
		$type = ($card_type == 1)? "Regular Card" : "Discounted Card";
		$amount = $card_sale['initial_card_load'];

		IF($c_status == 0):
			echo "<script>$('#PayCardDisposed').val('".$dID."');$('#amount').val('".$amount."');$('#SaleCardCheck').html('<p>Card Type: <span class=\"lead text-success\">".$type."</span></p><p>Total Amount Due: <span class=\"lead text-success\">P".$amount."</span></p>');$('#tapcard2').show();$('#tapcard1').hide();</script>";
		ELSE:
			die("Card is already sold and not for disposal! <script>$('#TapCardDisposed').val('');$('#TapCardDisposed').focus();</script>");
		ENDIF;
	ELSE:
		die("Card ID is invalid, no record found! <script>$('#TapCardDisposed').val('');$('#TapCardDisposed').focus();</script>");
	ENDIF;
ENDIF;

IF(isset($_POST["PayCardSubmit"])):

	//Fetching Values from URL
	$cardID=$data['cardID'];
	$amount=$data['amount'];
	mysqli_query($link, "UPDATE ".DB_PREFIX."idcards SET disposed=1 WHERE terminal_ID='{$terminal_ID}' AND card_number='{$cardID}'") or die("Insertion Failed:" . mysqli_error($link));
	mysqli_query($link, "INSERT INTO ".DB_PREFIX."card_sale (`dispatcher_ID`,`date_sale`,`time_sale`,`card_number`,`amount`) VALUES ('$dispatcher_ID','$date','$time','$cardID','$amount')");;	  
	
	
	// Generate Reference No.
	$date = date('Y-m-d');
	$time = date('H:i:s');

	$batch = mysqli_real_escape_string($link, date("Y"));
	$gen_transcode = $batch.mysqli_real_escape_string($link, GenKey());
	$duplicates = mysqli_query($link, "SELECT transaction_no FROM ".DB_PREFIX."terminalload_wallet WHERE transaction_no='$gen_transcode'");
	WHILE(mysqli_fetch_array($duplicates)){
		$gen_transcode = $batch.mysqli_real_escape_string($link, GenKey());
	}
	$gen_transcode = STRTOUPPER($gen_transcode);		
	
	// Insert into terminaltrans
	//Terminal Transaction
	$transaction_sql_insert = "INSERT into `".DB_PREFIX."terminaltrans` (`ref_no`,`trans_date`,`trans_time`,`transaction_code`,`user_ID`,`cash_on_hand`,`terminal_ID`)
	VALUES ('$gen_transcode','$date','$time','CS','$cardID','100','$terminal_ID')";
	mysqli_query($link, $transaction_sql_insert) or die("Insertion Failed:" . mysql_error());

	
	die("<h1>Paid</h1><script>$('#PayCardSubmit').hide();</script>");
ENDIF;

?>
