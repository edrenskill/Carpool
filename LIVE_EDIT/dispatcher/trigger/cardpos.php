<?php
require_once '../../settings/connect.php';
if(session_id() == '') { page_protect(); } // START SESSIONS

foreach($_POST as $key => $value) { $data[$key] = filter($value); }
$terminal_ID = $_SESSION['terminal_ID'];

date_default_timezone_set("Asia/Manila");
$date = date("Y-m-d h:i:s a");

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
	mysqli_query($link, "INSERT INTO ".DB_PREFIX."card_sale (`dispatcher_ID`,`date_sale`,`card_number`,`amount`) VALUES ('$dispatcher','$date','$cardID','$amount')");;	  
	die("<h1>Paid</h1><script>$('#PayCardSubmit').hide();</script>");
ENDIF;

?>
