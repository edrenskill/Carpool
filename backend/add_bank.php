<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	IF(!isset($_SESSION['ADM']) && isset($_SESSION['user_id'])){ 
		header ('location: login');
	}ELSEIF(isset($_SESSION['ADM']) && !isset($_SESSION['user_id'])){ 
		unset($_SESSION['ADM']);
		header ('location: ../index');
	}ELSEIF(!isset($_SESSION['ADM']) && !isset($_SESSION['user_id'])){ 
		header ('location: ../index');
	}
	
	date_default_timezone_set("Asia/Manila");	
	
	FOREACH($_POST as $key => $value) { $data[$key] = filter($value); }
	$err = array();
	
	
	IF(isset($_POST) && array_key_exists('doAddBank',$_POST)):
		
		$Bname = strtoupper($data['bankname']);
		$BAbbr = strtoupper($data['BankAbbr']);
		
	
		IF(!isset($Bname) || $Bname == ""): 
			$err[] = "Bank Name";
		ELSE:
		
			//check ID in db
			$results = mysqli_query($link, "SELECT name FROM ".DB_PREFIX."banks WHERE name='{$Bname}'");

			//return total count
			$record_exist = mysqli_num_rows($results); //total records
			//if value is more than 0, bank name is not available
			if($record_exist) {
				$err[] = "Bank Name is already Exist!";
			}
			
		ENDIF;
		
		IF(!isset($BAbbr) || $BAbbr == ""): 
			$err[] = "Bank Name";
		ELSE:
		
			//check ID in db
			$results = mysqli_query($link, "SELECT Abbreviation FROM ".DB_PREFIX."banks WHERE Abbreviation='{$BAbbr}'");

			//return total count
			$record_exist = mysqli_num_rows($results); //total records
			//if value is more than 0, bank Abbreviation is not available
			if($record_exist) {
				$err[] = "Bank with same Abbreviation is already Exist!";
			}
			
		ENDIF;

		IF(!empty($err)):
			$_SESSION['post']['bankname'] = $Bname;
			$_SESSION['post']['BankAbbr'] = $BAbbr;

		ELSEIF(empty($err)):
			//Terminal
			$bankaccount = "INSERT into ".DB_PREFIX."banks (`name`,`Abbreviation`) 
			VALUES ('$Bname','$BAbbr')";

			mysqli_query($link, $bankaccount) or die("Insertion Failed:" . mysqli_error($link));

			$_SESSION['Bname'] = $Bname;
			$_SESSION['BankAbbrs'] = $BAbbr;

			
			HEADER('Location: bank_confirmation');
		ENDIF;
	ENDIF;

?>

<!DOCTYPE HTML>
<html>
	<head>
		<?php include ('includes/header.php'); ?>
		<script src="js/jquery.min.js"></script>

		<script type="text/javascript">
			$(document).ready( function() {
				$('#message').delay(5000).fadeOut();
					
				// IF bank name ENTERED
				$("#bankname").blur(function (e) {
					//removes spaces from bank name
					var BankName = $(this).val();
					
					if(BankName.length < 4){$("#Bname-result").html('At least 10 Characters');return;}

					if(BankName.length >= 4){
						$("#Bname-result").html('<img src="../profile/images/loading.gif" />');
						$.post('trigger/exec', {'BankName':BankName}, function(data) {
							$("#Bname-result").html(data);
						});
					}
				});
				
				// IF bank name ENTERED
				$("#BankAbbr").blur(function (e) {
					//removes spaces from bank name
					$(this).val($(this).val().replace(/\s/g, ''));
					var BankAbbr = $(this).val();
					
					if(BankAbbr.length < 2){$("#Abbr-result").html('At least 2 Characters');return;}

					if(BankAbbr.length >= 2){
						$("#Abbr-result").html('<img src="../profile/images/loading.gif" />');
						$.post('trigger/exec', {'BankAbbr':BankAbbr}, function(data) {
							$("#Abbr-result").html(data);
						});
					}
				});
				
			});

			// FORM VALIDATION
			function validateForm()
			{
				var Bname = document.forms["AddBank"]["bankname"].value;
				var Baccount = document.forms["AddBank"]["BankAbbr"].value;

				if (Bname==null || Bname=="") { alert("Enter Bank Name"); return false; }
				if (Baccount==null || Baccount=="") { alert("Enter Bank Abbreviation"); return false; }

			}

		</script>
	</head>
	<body>
		<!-- Wrapper -->
			<div id="wrapper">
				<?php include ('includes/navigation.php'); ?>

				<!-- Main -->
					<div id="page-wrapper">
					
					<div class="row">
						<div class="col-lg-12">
							<h1 class="page-header"><i class="fa fa-road fa-fw"></i> Add Bank</h1>
							<?php // Display error message
							IF(!empty($err)) : 
								echo "<p><div class=\"msg\" id=\"message\"><span style=\"color:#ff0000\">Please check the following fields:</span><br />"; FOREACH ($err as $e) { echo "$e <br />"; } echo "</div></p>"; 
								$field_bankname = @$_SESSION['post']['bankname'];
								$field_BAbbr = $_SESSION['post']['BankAbbr'];
							ENDIF;
							?>
						</div><!-- /.col-lg-12 -->
					</div><!-- /.row -->

						<!-- SIGN UP -->
							<section id="add_BankAccount">
								<div class="container">
									<span style="color:#003399">Note: <b>Field with asterisk (<span style="color:#FF0000">*</span>) are required.</b></span><br /><br />
										<form role="form" name="AddBank" id="AddBank" method="post" action="add_bank" onsubmit="return validateForm()">
											
											<div class="form-group" style="width:600px">
												<label>Bank Name<span style="color:#FF0000">*</span><span id="Bname-result"  style="padding-left:10px;"></span></label>
												<input class="form-control" type="text" name="bankname" id="bankname" value="<?php IF(isset($field_bankname)): echo $field_bankname; ENDIF; ?>" style="width:300px" PLACEHOLDER="Bank Name"/>
											</div>
											
											<div class="form-group" style="width:600px">
												<label>Bank Name Abbreviation<span style="color:#FF0000">*</span><span id="Abbr-result"  style="padding-left:10px;"></span></label>
												<input class="form-control" type="text" name="BankAbbr" id="BankAbbr" value="<?php IF(isset($field_BAbbr)): echo $field_BAbbr; ENDIF; ?>" style="width:300px" PLACEHOLDER="Bank Name"/>
											</div>
										

											<div  class="form-group">
												<button class="btn btn-default" type="submit" name="doAddBank" id="doAddBank" value="doAddBank">Submit</button>
											</div>
											<?php unset($_SESSION['post']); ?>
										</form>
								</div>
							</section>
					</div>

			</div>
			
			
		<!-- jQuery -->
		<script src="js/jquery.min.js"></script>

		<!-- Bootstrap Core JavaScript -->
		<script src="js/bootstrap.min.js"></script>

		<!-- Metis Menu Plugin JavaScript -->
		<script src="js/metisMenu.min.js"></script>

		<!-- Custom Theme JavaScript -->
		<script src="js/startmin.js"></script>
	</body>
	

</html>