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
	IF(isset($_POST) && array_key_exists('doAdd',$_POST)):
		
		$personalid = $data['userID'];
		$Bname = strtoupper($data['BankName']);
		$Baccount = strtoupper($data['account']);
		
	
		IF(!isset($personalid) || $personalid == ""): 
			$err[] = "User ID";
		ELSE:
		
			//check ID in db
			$results = mysqli_query($link, "SELECT ".DB_PREFIX."bank_accounts.user_ID FROM ".DB_PREFIX."bank_accounts JOIN ".DB_PREFIX."users ON ".DB_PREFIX."bank_accounts.user_ID=".DB_PREFIX."users.user_ID WHERE ".DB_PREFIX."bank_accounts.user_ID='{$personalid}' AND (".DB_PREFIX."users.userlevel=10 || ".DB_PREFIX."users.userlevel=7)");

			//return total count
			$record_exist = mysqli_num_rows($results); //total records
			//if value is more than 0, username is not available
			if($record_exist) {
				$err[] = "Driver has existing Bank Account!";
			}
			
		ENDIF;
		
		IF(!isset($Bname) || $Bname == ""): $err[] = "Bank Name"; ENDIF;
		IF($Baccount == ""): $err[] = "Account"; ENDIF;

		IF(!empty($err)):
			$_SESSION['post']['userID'] = $personalid;
			$_SESSION['post']['account'] = $Baccount;

		ELSEIF(empty($err)):
			//Terminal
			$bankaccount = "INSERT into ".DB_PREFIX."bank_accounts (`user_ID`,`bank_ID`,`account_no`,`status`) 
			VALUES ('$personalid','$Bname','$Baccount','1')";

			mysqli_query($link, $bankaccount) or die("Insertion Failed:" . mysqli_error($link));
			
			$_SESSION['personalid'] = $personalid;
			$_SESSION['Bname'] = $Bname;
			$_SESSION['Baccount'] = $Baccount;

			
			HEADER('Location: account_confirmation');
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
					
				// IF userID ENTERED
				$("#userID").blur(function (e) {
					//removes spaces from id
					$(this).val($(this).val().replace(/\s/g, ''));
					var userID = $(this).val();
					
					if(userID.length < 4){$("#user-result").html('At least 10 Characters');return;}

						if(userID.length >= 4){
							$("#user-result").html('<img src="../profile/images/loading.gif" />');
							$.post('trigger/exec', {'userID':userID}, function(data) {
								$("#user-result").html(data);
							});
						}
					});
				
			});

			// NUMBERS ONLY
			function isNumberKey(evt)
			{
				var charCode = (evt.which) ? evt.which : event.keyCode
				if (charCode > 31 && (charCode < 48 || charCode > 57))
				return false;
				return true;
			}

			// FORM VALIDATION
			function validateForm()
			{			
				var personalid = document.forms["addBankAccount"]["userID"].value;
				var Bname = document.forms["addBankAccount"]["BankName"].value;
				var Baccount = document.forms["addBankAccount"]["account"].value;

				
				if (personalid==null || personalid=="") { alert("Enter User ID"); return false; }
				if (Bname==null || Bname=="") { alert("Enter Bank Name"); return false; }
				if (Baccount==null || Baccount=="") { alert("Enter Bank account"); return false; }

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
							<h1 class="page-header"><i class="fa fa-road fa-fw"></i> Add Bank Account</h1>
							<?php // Display error message
							IF(!empty($err)) : 
								echo "<p><div class=\"msg\" id=\"message\"><span style=\"color:#ff0000\">Please check the following fields:</span><br />"; FOREACH ($err as $e) { echo "$e <br />"; } echo "</div></p>"; 
								$field_personalid = @$_SESSION['post']['userID'];
								$field_Bname = @$_SESSION['post']['BankName'];
								$field_Baccount = $_SESSION['post']['account'];
							ENDIF;
							?>
						</div><!-- /.col-lg-12 -->
					</div><!-- /.row -->
					
					
					
				
						<!-- SIGN UP -->
							<section id="add_BankAccount">
								<div class="container">
									<span style="color:#003399">Note: <b>Field with asterisk (<span style="color:#FF0000">*</span>) are required.</b></span><br /><br />
										<form role="form" name="addBankAccount" id="addBankAccount" method="post" action="add_bank_account" onsubmit="return validateForm()">
											
											<div class="form-group" style="width:600px">
												<label>User's ID<span style="color:#FF0000">*</span><span id="user-result"  style="padding-left:10px;"></span></label>
												<input class="form-control" type="text" name="userID" id="userID" value="<?php IF(isset($field_personalid)): echo $field_personalid; ENDIF; ?>" style="width:300px" PLACEHOLDER="User's ID" onkeypress="return isNumberKey(event)"/>
											</div>
											
											<div class="form-group" style="width:600px">
												<label>Bank Name<span style="color:#FF0000">*</span><span id="Bank-result"  style="padding-left:10px;"></span></label>
												
												<select class="form-control" name="BankName" id="BankName" style="width:300px">
													<option value="">-Select Bank-</option>
													<?php
														$Bank_Name = mysqli_query($link, "SELECT ID, name, Abbreviation FROM ".DB_PREFIX."banks");
														WHILE($name = mysqli_fetch_array($Bank_Name)){
															$BID = $name['ID'];
															$Bname = $name['name']." - (".$name['Abbreviation'].")";
													?>
													<option value="<?= $BID; ?>"><?= $Bname; ?></option>
														<?php } ?>
												</select>
												
											</div>
											
											<div class="form-group" style="width:600px">
												<label>Bank account No<span style="color:#FF0000">*</span><span id="account-result"  style="padding-left:10px;"></span><span id="driver2-result"  style="padding-left:10px;"></span></label>
												<div>
													<input class="form-control" type="text" name="account" id="account" value="<?php IF(isset($field_Baccount)): echo $field_Baccount; ENDIF; ?>" style="width:300px; margin:0 0 10px 0;" PLACEHOLDER="Bank account" onkeypress="return isNumberKey(event)"/>
												</div>
											</div>
											
											<!--<div class="form-group">
                                                <label>status</label>
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="Tstatus" id="Tstatus" type="checkbox" <?php IF(isset($field_status) && $field_status == 1): echo "checked"; ENDIF; ?> value="1">Status
                                                    </label>
                                                </div>
                                            </div> -->
										

											<div  class="form-group">
												<button class="btn btn-default" type="submit" name="doAdd" id="doAdd" value="doAdd">Submit</button>
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

<!-- Page-Level Demo Scripts - Tables - Use for reference -->
<script src="js/dashboard.js"></script>

	</body>
	

</html>