<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	if(!isset($_SESSION['ADM'])): header('location: login'); ENDIF;

	foreach($_POST as $key => $value) { $data[$key] = filter($value); }	
	
	$_SESSION['terminal_ID'] = "";
	$err = "";

	IF(isset($_SESSION['fromremitted'])):
		$location = "remitted_payout";
		$transaction = "Remitted Accounts";
	ELSEIF(isset($_SESSION['frompayout'])):
		$location = "payout";
		$transaction = "Payout";
	ELSEIF(isset($_SESSION['fromidcard'])):
		$location = "id_card_registration";
		$transaction = "ID Card Registration/Designation";
		unset($_SESSION['CARDTYPE']);
	ELSEIF(isset($_SESSION['frompidcardinventory'])):
		$location = "id_card_inventory";
		$transaction = "ID Card Inventory";
	ELSEIF(isset($_SESSION['fromcardsaleaccount'])):
		$location = "card_sale_accounts_receivable";
		$transaction = "Card Sale Remittance";
	ENDIF;
	
	
	
	IF(isset($_POST) && array_key_exists('submitterminal',$_POST)):
	
		$terminal = $data['terminal_select'];

		IF(!isset($terminal) || $terminal == ""):
			$err = "Please select from terminal list";
		ELSE:
			IF($terminal == "all"):
				$_SESSION['terminal_ID'] = "all";
				$_SESSION['terminal_name'] = "All Terminals";
				HEADER('Location: '.$location);
				EXIT();
			ELSE:
				$selectedterminalID = $data['terminal_select'];
				$terminalselected = mysqli_fetch_array(mysqli_query($link, "SELECT  `terminal_ID`, `terminal_name` FROM `".DB_PREFIX."terminal` WHERE `terminal_ID` = '{$selectedterminalID}'"));	

				$_SESSION['terminal_ID'] = $terminalselected['terminal_ID'];
				$_SESSION['terminal_name'] = $terminalselected['terminal_name'];
				HEADER('Location: '.$location);
				EXIT();
			ENDIF;
		ENDIF;
	ENDIF;

$terminal_select_list = mysqli_query($link, "SELECT  terminal_ID, terminal_name FROM ".DB_PREFIX."terminal WHERE operational='1'");
?>

<html lang="en">
<head>
   <?php include_once('includes/header.php'); ?>

	<script type="text/javascript" src="../myaccount/js/jquery.min.js"></script>
	<script type="text/javascript" src="../myaccount/js/jquery.form.min.js"></script>
		
</head>
<body>

	<div id="wrapper">

		<?php include_once('includes/navigation.php'); ?>
		
		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">Terminal Selection for <?= $transaction; ?></h1>
				</div><!-- /.col-lg-12 -->
			</div><!-- /.row -->

			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="col-lg-4" style="width:275">
								<div class="panel panel-green">
									<div class="panel-heading">
										Select Existing Terminal
									</div>
									<div class="panel-body">
										<form action="select_terminal" method="post" enctype="multipart/form-data" id="TerminalForm">
											<div class="panel-body" id="SelectTerminalID">
												<fieldset>
													<div class="form-group">
														<select class="form-control" name="terminal_select" id="terminal_select">
															<option value="">Select Existing Terminal</option>
															<option value="all">All Terminals</option>
																			
															<?php WHILE($terminalselect = mysqli_fetch_array($terminal_select_list)){ ?>														
															<option value="<?= $terminalselect['terminal_ID']; ?>"><?= strtoupper($terminalselect['terminal_name']); ?></option>
															<?php } ?>
														</select>
													</div>
													<button class="btn btn-lg btn-success btn-block" type="submit"  id="submitterminal" name="submitterminal" value="submitterminal" />Continue</button>
												</fieldset>
											</div>
										</form>
									</div>
									<div class="panel-footer">
										<div id="output" align="center">
											<?php IF(isset($err)): echo $err; ENDIF; ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div><!-- /#page-wrapper -->
		</div>
	</div>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="js/metisMenu.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="js/startmin.js"></script>

</body>
</html>
