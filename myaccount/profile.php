<?php
include '../settings/connect.php';
if (session_id() == '') { page_protect(); } // START SESSIONS

	$member_ID = $_SESSION['act_ID'];

	$personal_info = mysqli_fetch_assoc(mysqli_query($link, "SELECT userlevel, username, email, fname, mname, lname, suffix, gender, dob, marital, occupation, religion, zip_code, address, country, region, province, city, barangay, tel, sss, mobile, fax, regdate, user_ID, TIN, photo, account_status FROM " . DB_PREFIX . "users WHERE user_ID = '" . $member_ID . "'"));
	$contact_person_info = mysqli_fetch_assoc(mysqli_query($link, "SELECT contact_person, mobile, address1, country, region, province, city_municipality, barangay FROM " . DB_PREFIX . "contacts WHERE UID = '" . $member_ID . "'"));
	$accoun_status = $personal_info['account_status'];
	$marital = $personal_info['marital'];
	
	IF($personal_info['userlevel'] == 7 || $personal_info['userlevel'] == 10):

	$credentials = mysqli_fetch_assoc(mysqli_query($link, "SELECT NBI, NBI_expiry, drivers_license, DL_expiry, police_clearance, police_expiry FROM ".DB_PREFIX."driver_credentials WHERE driver_ID='{$member_ID}'"));

		$bank_account_info = mysqli_fetch_assoc(mysqli_query($link, "SELECT bank_ID, account_no, status FROM " . DB_PREFIX . "bank_accounts WHERE user_ID = '" . $member_ID . "'"));
		$bank_ID = $bank_account_info['bank_ID'];
		$BankAccountNo = $bank_account_info['account_no'];
		$bank_name = mysqli_fetch_assoc(mysqli_query($link, "SELECT name, Abbreviation FROM ".DB_PREFIX."banks WHERE ID={$bank_ID}"));
		IF(isset($bank_name['name'])):
			$Bname = $bank_name['name']." - (".$bank_name['Abbreviation'].")";
		ENDIF;
	ENDIF;

	$country = mysqli_fetch_array(mysqli_query($link, "SELECT countries_name FROM " . DB_PREFIX . "countries WHERE c_code = '" . $personal_info['country'] . "' AND enabled = 1"));
	$region = mysqli_fetch_array(mysqli_query($link, "SELECT r_code, name FROM " . DB_PREFIX . "regions WHERE r_code = '" . $personal_info['region'] . "'"));
	$province = mysqli_fetch_array(mysqli_query($link, "SELECT name FROM " . DB_PREFIX . "provinces WHERE p_code = '" . $personal_info['province'] . "'"));
	$city = mysqli_fetch_array(mysqli_query($link, "SELECT name FROM " . DB_PREFIX . "city_municipality WHERE cm_code = '" . $personal_info['city'] . "'"));
	$barangay = mysqli_fetch_array(mysqli_query($link, "SELECT name FROM " . DB_PREFIX . "barangays WHERE brgy_code = '" . $personal_info['barangay'] . "'"));

	$ccountry = mysqli_fetch_array(mysqli_query($link, "SELECT countries_name FROM " . DB_PREFIX . "countries WHERE c_code = '" . $contact_person_info['country'] . "' AND enabled = 1"));
	$cregion = mysqli_fetch_array(mysqli_query($link, "SELECT r_code, name FROM " . DB_PREFIX . "regions WHERE r_code = '" . $contact_person_info['region'] . "'"));
	$cprovince = mysqli_fetch_array(mysqli_query($link, "SELECT name FROM " . DB_PREFIX . "provinces WHERE p_code = '" . $contact_person_info['province'] . "'"));
	$ccity = mysqli_fetch_array(mysqli_query($link, "SELECT name FROM " . DB_PREFIX . "city_municipality WHERE cm_code = '" . $contact_person_info['city_municipality'] . "'"));
	$cbarangay = mysqli_fetch_array(mysqli_query($link, "SELECT name FROM " . DB_PREFIX . "barangays WHERE brgy_code = '" . $contact_person_info['barangay'] . "'"));

	//Default status

	IF(strpos($member_ID, 'TEMP') !== false):
		$dstatus = "<span class='text-warning'>For Activation</span>";
	ELSE:
		IF($accoun_status == 0):
			$dstatus = "<span class='text-success'>Active</span>";
		ELSEIF($accoun_status == 1):
			$dstatus = "<span class='text-danger'>Suspended</span>";
		ELSEIF($accoun_status == 2):
			$dstatus = "<span class='text-muted'>Banned</span>";
		ENDIF;
	ENDIF;
	
		IF($marital == 0):
			$civilS = "";	
		ELSEIF($marital == 1):
			$civilS = "<strong>Single</strong>";
		ELSEIF($marital == 2):
			$civilS = "<strong>Married</strong>";
		ELSEIF($marital == 3):
			$civilS = "<strong>widow</strong>";
		ELSEIF($marital == 4):
			$civilS = "<strong>Seperated</strong>";
		ELSEIF($marital == 5):
			$civilS = "<strong>Divorced</strong>";
	ENDIF;


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include_once("includes/header.php"); ?>
    </head>
    <body>

        <div id="wrapper">

            <?php include_once("includes/nav.php"); ?>

            <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <h4 class="page-header">My Profile</h4>
                        </div>
                        <!-- /.col-lg-12 -->
                    </div>

					<div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5>Status.: <strong><?= $dstatus; ?></strong> <span style="float:right;">ID No.: <span style='color:#0000ff'><strong><?= $member_ID; ?></strong></span></span></h5>
                            </div>
							
                            <!-- .panel-heading -->
                            <div class="panel-body">
                                <div class="panel-group" id="profile_details">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#profile_details" href="#personal_info">Personal Information</a>
                                            </h4>
                                        </div>
                                        <div id="personal_info" class="panel-collapse collapse in">
                                            <div class="panel-body">
												<span style="display:none" class="alert alert-info" id="namemessage"></span>
												<div class="12u(xsmall)" style="float:left;width:50%;" id="name-display">
													<div class="12u(xsmall)" style="float:left; width:90px;">Name</div>
													<div class="6u(xsmall)" style="float:left; ">
														<strong>
															<span class="fname"><?= $personal_info['fname']; ?></span>
															<span class="lname"><?= $personal_info['lname']; ?></span>
															<?php
															IF ($personal_info['suffix'] != ""): echo ", " . $personal_info['suffix'];
															ENDIF;
															?>
														</strong>
													</div>
													<div style="float:right;">
														<a href="javascript: null(void)" data-toggle="modal" data-target="#name-edit"><i class="fa fa-edit"></i> edit</a>
													</div>
												</div>
												<div class="modal fade" id="name-edit" tabindex="-1" role="dialog" aria-labelledby="Editnamemod" aria-hidden="true">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
																<h4 class="modal-title" id="Editnamemod">Edit Name</h4>
															</div>
															<div class="modal-body">
																<h4>First Name</h4>
																<div class="form-group has-success">
																	<input class="form-control nfname" type="text" name="fname" id="fname" value="<?= $personal_info['fname']; ?>" PLACEHOLDER="First Name" />
																</div>
																<h4>Middle Name</h4>
																<div class="form-group has-success">
																	<input class="form-control nmname" type="text" name="mname" id="mname" value="<?= $personal_info['mname']; ?>" PLACEHOLDER="First Name" />
																</div>															
																<h4>Last Name</h4>
																<div class="form-group has-success">
																	<input class="form-control nlname" type="text" name="lname" id="lname" value="<?= $personal_info['lname']; ?>" PLACEHOLDER="First Name" />
																</div>
															</div>
															<div class="modal-footer">
																<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																<button type="button" class="btn btn-primary" id="namesubmit" data-dismiss="modal">Save changes</button>
															</div>
														</div><!-- /.modal-content -->
													</div>
												</div>

												<div style="clear:both"></div>
												<div class="12u(xsmall)" style="float:left; width:90px;">User Name</div>
												<div class="6u(xsmall)" style="float:left;">
													<strong><?= $personal_info['username']; ?></strong>
												</div>

												<div style="clear:both"></div>
												
												<span style="display:none" class="alert alert-info" id="dobmessage"></span>
												<div class="12u(xsmall)" style="float:left;width:50%;" id="dob-display">
													<div class="12u(xsmall)" style="float:left; width:90px;">Birthday</div>
													<div class="6u(xsmall)" style="float:left;">
														<span class="dob">
															<strong><?= date('F j, Y', strtotime($personal_info['dob']));?></strong>
														</span>
													</div>
													<div style="float:right;">
														<a href="javascript: null(void)" data-toggle="modal" data-target="#dob-edit"><i class="fa fa-edit"></i> edit</a>
													</div>
												</div>
												<div class="modal fade" id="dob-edit" tabindex="-1" role="dialog" aria-labelledby="Editdobmod" aria-hidden="true">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
																<h4 class="modal-title" id="Editdobmod">Edit Birthday</h4>
															</div>
															<div class="modal-body">
																<h4>Birthday</h4>
																<div class="form-group has-success">
																	<input type="date" class="form-control ndob" type="text" name="dob" id="dob" value="<?= $personal_info['dob']; ?>" PLACEHOLDER="mm/dd/YYYY" style="width:250px" />
																</div>
															</div>
															<div class="modal-footer">
																<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																<button type="button" class="btn btn-primary" id="dobsubmit" data-dismiss="modal">Save changes</button>
															</div>
														</div>
													</div>
												</div>

												<div style="clear:both"></div>		
												<!--	<div class="12u(xsmall)" style="float:left; width:150px;">Marital Status</div><div class="6u(xsmall)" style="float:left; width:350px;"><strong><?= $personal_info['marital']; ?></strong></div><div style="clear:both"></div> -->
												<span style="display:none" class="alert alert-info" id="occumessage"></span>
												<div class="12u(xsmall)" style="float:left;width:50%;" id="occu-display">
													<div class="12u(xsmall)" style="float:left; width:90px;">Occupation</div>
													<div class="6u(xsmall)" style="float:left;">
														<span class="occu"><strong><?= $personal_info['occupation']; ?></strong></span>
													</div>
													<div style="float:right;">
														<a href="javascript: null(void)" data-toggle="modal" data-target="#occu-edit"><i class="fa fa-edit"></i> edit</a>
													</div>
												</div>
												<div class="modal fade" id="occu-edit" tabindex="-1" role="dialog" aria-labelledby="Editoccumod" aria-hidden="true">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
																<h4 class="modal-title" id="Editoccumod">Edit Occupation</h4>
															</div>
															<div class="modal-body">
																<h4>Occupation</h4>
																<div class="form-group has-success">
																	<input class="form-control noccu" type="text" name="occu" id="occu" value="<?= $personal_info['occupation']; ?>" PLACEHOLDER="Occupation" />
																</div>
															</div>
															<div class="modal-footer">
																<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																<button type="button" class="btn btn-primary" id="occusubmit" data-dismiss="modal">Save changes</button>
															</div>
														</div>
													</div>
												</div>

												<div style="clear:both"></div>
												<span style="display:none" class="alert alert-info" id="relimessage"></span>
												<div class="12u(xsmall)" style="float:left;width:50%;" id="reli-display">
													<div class="12u(xsmall)" style="float:left; width:90px;">Religion</div>
													<div class="6u(xsmall)" style="float:left;">
														<span class="reli"><strong><?= $personal_info['religion']; ?></strong></span>
													</div>
													<div style="float:right;">
														<a href="javascript: null(void)" data-toggle="modal" data-target="#reli-edit"><i class="fa fa-edit"></i> edit</a>
													</div>
													<div style="clear:both"></div>
												</div>

												<div class="modal fade" id="reli-edit" tabindex="-1" role="dialog" aria-labelledby="Editreliumod" aria-hidden="true">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
																<h4 class="modal-title" id="Editrelimod">Edit Religion</h4>
															</div>
															<div class="modal-body">
																<h4>Religion</h4>
																<div class="form-group has-success">
																	<input class="form-control nreli" type="text" name="reli" id="reli" value="<?= $personal_info['religion']; ?>" PLACEHOLDER="Religion" />
																</div>
															</div>
															<div class="modal-footer">
																<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																<button type="button" class="btn btn-primary" id="relisubmit" data-dismiss="modal">Save changes</button>
															</div>
														</div>
													</div>
												</div>
												
											
											  <div style="clear:both"></div>
												<div class="12u(xsmall)" style="color:#0000FF;" id="civil-displaymess">
													<span class="civilmessage" id="civilmessage"></span>
												</div>
												<div class="12u(xsmall)" style="float:left;width:50%;" id="civil-display">
													<div class="12u(xsmall)" style="float:left; width:90px;">Civil Status</div>
													<div class="6u(xsmall)" style="float:left;">
														<span class="civil"><strong><?= $civilS?></strong></span>
													</div>
													<div style="float:right;">
														<a href="javascript: null(void)" data-toggle="modal" data-target="#civil-edit"><i class="fa fa-edit"></i> edit</a>
													</div>
												</div>
												<div class="modal fade" id="civil-edit" tabindex="-1" role="dialog" aria-labelledby="Editcivilmod" aria-hidden="true">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
																<h4 class="modal-title" id="Editcivilmod">Edit Civil Status</h4>
															</div>
															<div class="modal-body">
																<h4>Civil Status</h4>
																<div class="form-group">
																	<label  class="radio-inline">
																		<input type="radio" name="civil" class="civil" id="civil" value="<?php
																		IF (isset($field_civil) && $field_civil == '1'): echo 'checked="checked"';
																		ENDIF;
																		?>" checked />Single<br>
																	</label>
																	<label  class="radio-inline">
																		<input type="radio" name="civil" class="civil" id="civil" value=""<?php
																		IF (isset($field_civil) && $field_civil == '2'): echo 'checked="checked"';
																		ENDIF;
																		?>""/>Married<br>
																	</label>
																	<label  class="radio-inline">
																		<input type="radio" name="civil" class="civil" id="civil" value=""<?php
																		IF (isset($field_civil) && $field_civil == '3'): echo 'checked="checked"';
																		ENDIF;
																		?>""/>Widow<br>
																	</label>
																	<label  class="radio-inline">
																		<input type="radio" name="civil" class="civil" id="civil" value=""<?php
																		IF (isset($field_civil) && $field_civil == '4'): echo 'checked="checked"';
																		ENDIF;
																		?>""/>Seperated<br>
																	</label>
																	<label  class="radio-inline">
																		<input type="radio" name="civil" class="civil" id="civil" value=""<?php
																		IF (isset($field_civil) && $field_civil == '5'): echo 'checked="checked"';
																		ENDIF;
																		?>""/>Divorced<br>
																	</label>
																</div>
															</div>
															<div class="modal-footer">
																<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																<button type="button" class="btn btn-primary" id="civilsubmit" data-dismiss="modal">Save changes</button>
															</div>
														</div>
													</div>
												</div>
												
												<div style="clear:both"></div>
												<span style="display:none" class="alert alert-info" id="tinmessage"></span>
												<div class="12u(xsmall)" style="float:left;width:50%;" id="tin-display">
													<div class="12u(xsmall)" style="float:left; width:90px;">TIN</div>
													<div class="6u(xsmall)" style="float:left;">
														<span class="tin"><strong><?= $personal_info['TIN']; ?></strong></span>
													</div>
													<div style="float:right;">
														<a href="javascript: null(void)" data-toggle="modal" data-target="#tin-edit"><i class="fa fa-edit"></i> edit</a>
													</div>
												</div>
												<div class="modal fade" id="tin-edit" tabindex="-1" role="dialog" aria-labelledby="Edittinmod" aria-hidden="true">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
																<h4 class="modal-title" id="Edittinmod">Edit Tin</h4>
															</div>
															<div class="modal-body">
																<h4>Tin</h4>
																<div class="form-group has-success">
																	<input class="form-control ntin" type="text" name="tin" id="tin" value="<?= $personal_info['TIN']; ?>" PLACEHOLDER="TIN Number" />
																</div>
															</div>
															<div class="modal-footer">
																<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																<button type="button" class="btn btn-primary" id="tinsubmit" data-dismiss="modal">Save changes</button>
															</div>
														</div>
													</div>
												</div>
												
												<div style="clear:both"></div>
												<span style="display:none" class="alert alert-info" id="sssmessage"></span>
												<div class="12u(xsmall)" style="float:left;width:50%;" id="sss-display">
													<div class="12u(xsmall)" style="float:left; width:90px;">SSS</div>
													<div class="6u(xsmall)" style="float:left;">
														<span class="sss"><strong><?= $personal_info['sss']; ?></strong></span>
													</div>
													<div style="float:right;">
														<a href="javascript: null(void)" data-toggle="modal" data-target="#sss-edit"><i class="fa fa-edit"></i> edit</a>
													</div>
												</div>
												<div class="modal fade" id="sss-edit" tabindex="-1" role="dialog" aria-labelledby="Editsssmod" aria-hidden="true">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
																<h4 class="modal-title" id="Editsssmod">Edit SSS</h4>
															</div>
															<div class="modal-body">
																<h4>SSS</h4>
																<div class="form-group has-success">
																	<input class="form-control nsss" type="text" name="sss" id="sss" value="<?= $personal_info['sss']; ?>" PLACEHOLDER="SSS Number" />
																</div>
															</div>
															<div class="modal-footer">
																<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																<button type="button" class="btn btn-primary" id="ssssubmit" data-dismiss="modal">Save changes</button>
															</div>
														</div>
													</div>
												</div>
												
                                            </div>
                                        </div>
                                    </div>
									
									
									
<!---------------------------------------------------------------------------------------------------------------------->									
									
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#profile_details" href="#address_info">Address Details</a>
                                            </h4>
                                        </div>
                                        <div id="address_info" class="panel-collapse collapse">
                                            <div class="panel-body">
											<span style="display:none" class="alert alert-info" id="addrmessage"></span>

												<div class="12u(xsmall)" style="float:left;width:50%;" id="addr-display">
													<div class="12u(xsmall)" style="float:left; width:90px;">Address</div>
													<div class="6u(xsmall)" style="float:left;">
														<span class="addr"><strong><?= $personal_info['address']; ?></strong></span>
													</div>
													<div style="float:right;">
														<a href="javascript: null(void)" data-toggle="modal" data-target="#addr-edit"><i class="fa fa-edit"></i> edit</a>
													</div>
													<div style="clear:both"></div>
												</div>
												<div class="modal fade" id="addr-edit" tabindex="-1" role="dialog" aria-labelledby="Editaddmod" aria-hidden="true">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
																<h4 class="modal-title" id="Editaddmod">Edit Address</h4>
															</div>
															<div class="modal-body">
																<h4>Address</h4>
																<div class="form-group has-success">
																	<input class="form-control naddr" type="text" name="addr" id="addr" value="<?= $personal_info['address']; ?>" PLACEHOLDER="Address"  />
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																	<button type="button" class="btn btn-primary" id="addrsubmit" data-dismiss="modal">Save changes</button>
																</div>
															</div>
														</div>
													</div>
												</div>

												<div style="clear:both"></div>									
												<div class="12u(xsmall)" style="float:left;width:50%;" id="dropaddr-display">
													<div style="float:left;">
														<div class="12u(xsmall)" style="float:left; width:90px;">Barangay</div><div class="6u(xsmall)" style="float:left;"><span class="nbarangay" id="nbarangay"><strong><?= $barangay['name']; ?></strong></span></div><div style="clear:both"></div>
														<div class="12u(xsmall)" style="float:left; width:90px;">City</div><div class="6u(xsmall)" style="float:left;"><span class="ncity" id="ncity"><strong><?= $city['name']; ?></strong></span></div><div style="clear:both"></div>
														<div class="12u(xsmall)" style="float:left; width:90px;">Province</div><div class="6u(xsmall)" style="float:left;"><span class="nprovince" id="nprovince"><strong><?= $province['name']; ?></strong></span></div><div style="clear:both"></div>
														<div class="12u(xsmall)" style="float:left; width:90px;">Region</div><div class="6u(xsmall)" style="float:left;"><span class="nregion" id="nregion"><strong><?= $region['name']; ?></strong></span></div><div style="clear:both"></div>
														<div class="12u(xsmall)" style="float:left; width:90px;">Country</div><div class="6u(xsmall)" style="float:left;"><span class="ncountry" id="ncountry"><strong><?= $country['countries_name']; ?></strong></span></div>
													</div>
													<div style="float:right;">
														<a href="javascript: null(void)" data-toggle="modal" data-target="#dropaddr-edit"><i class="fa fa-edit"></i> edit</a>
													</div>
													<div style="clear:both"></div>
												</div>
												<div class="modal fade" id="dropaddr-edit" tabindex="-1" role="dialog" aria-labelledby="Editdropaddmod" aria-hidden="true">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
																<h4 class="modal-title" id="Editdropaddmod">Edit Address</h4>
															</div>
															<div class="modal-body">
																<h4>Address</h4>
																<div class="form-group has-success">
																	<select name="country" id="country" class="form-control country select-wrapper">
																		<option value="" selected="true" disabled="disabled">--Select Country--</option>
																		<?php IF (isset($country['countries_name'])): ?>
																			<option value="" selected="true" ><?= $country['countries_name']; ?></option>
																			<?php ELSE: ?>
																			<option value="" selected="true" disabled="disabled">--Select Country--</option>
																		<?php
																		ENDIF;
																		$sql = mysqli_query($link, "SELECT countries_name, c_code FROM " . DB_PREFIX . "countries WHERE enabled = 1");
																		while ($ncountry = mysqli_fetch_array($sql)) {
																			?>
																			<option value="<?= $ncountry['c_code']; ?>"><?php
																				IF (isset($ncountry)): echo $ncountry['countries_name'];
																				ENDIF;
																				?></option>
																		<?php }
																		?>
																	</select>
																</div>

																<h4>Region</h4>
																<div class="form-group has-success">
																	<?php IF (isset($region['name'])): ?>
																		<select name="region" id="region" class="form-control region" ><option value="<?= $region['r_code']; ?>" selected="true" ><?= $region['name']; ?></option></select>
																		<?php ELSE: ?>
																		<select name="region" id="region" class="form-control region" disabled><option value="" selected="true" disabled="disabled">--Select Contry First--</option></select>
																	<?php ENDIF; ?>
																</div>

																<h4>Province</h4>
																<div class="form-group has-success">
																	<?php IF (isset($province['name'])): ?>
																		<select name="province" id="province" class="form-control province" ><option value="" selected="true" ><?= $province['name']; ?></option></select>
																		<?php ELSE: ?>
																		<select name="province" id="province" class="form-control province"  disabled><option value="" selected="true" disabled="disabled">--Select Region First--</option></select>
																	<?php ENDIF; ?>
																</div>

																<h4>City</h4>
																<div class="form-group has-success">
																	<?php IF (isset($city['name'])): ?>
																		<select name="city" id="city" class="form-control city" ><option value="" selected="true" ><?= $city['name']; ?></option></select>
																		<?php ELSE: ?>
																		<select name="city" id="city" class="form-control city"  disabled><option value="" selected="true" disabled="disabled">--Select Province First--</option></select>
																	<?php ENDIF; ?>
																</div>

																<h4>Barangay</h4>
																<div class="form-group has-success">
																	<?php IF (isset($barangay['name'])): ?>
																		<select name="barangay" id="barangay" class="form-control barangay" ><option value="" selected="true" ><?= $barangay['name']; ?></option></select>
																		<?php ELSE: ?>
																		<select name="barangay" id="barangay" class="form-control barangay" disabled><option value="" selected="true" disabled="disabled">--Select City First--</option></select>
																	<?php ENDIF; ?>
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																	<button type="button" class="btn btn-primary" id="dropaddrsubmit" data-dismiss="modal">Save changes</button>
																</div>
															</div>
														</div>
													</div>
												</div>

												<div style="clear:both"></div>
												<div class="12u(xsmall)" style="float:left;width:50%;" id="zip-display">
													<div class="12u(xsmall)" style="float:left; width:90px;">ZIP Code</div>
													<div class="6u(xsmall)" style="float:left;">
														<span class="zip"><strong><?= $personal_info['zip_code']; ?></strong></span>
													</div>
													<div style="float:right;">
														<a href="javascript: null(void)" data-toggle="modal" data-target="#zip-edit"><i class="fa fa-edit"></i> edit</a>
													</div>
													<div style="clear:both"></div>
												</div>
												<div class="modal fade" id="zip-edit" tabindex="-1" role="dialog" aria-labelledby="Editzipmod" aria-hidden="true">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
																<h4 class="modal-title" id="Editzipmod">Edit Address</h4>
															</div>
															<div class="modal-body">
																<h4>ZIP Code</h4>
																<div class="form-group has-success">
																	<input class="form-control nzip" type="text" name="zip" id="zip" value="<?= $personal_info['zip_code']; ?>" PLACEHOLDER="ZIP Code"  />
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																	<button type="button" class="btn btn-primary" id="zipsubmit" data-dismiss="modal">Save changes</button>
																</div>
															</div>
														</div>
													</div>
												</div>
                                            </div>
                                        </div>
                                    </div>
									
									
									
<!---------------------------------------------------------------------------------------------------------------------->										
									
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#profile_details" href="#personal_contact_info">Personal Contact Details</a>
                                            </h4>
                                        </div>
                                        <div id="personal_contact_info" class="panel-collapse collapse">
                                            <div class="panel-body">
											<span style="display:none" class="alert alert-info" id="contactmessage"></span>
												<div class="12u(xsmall)" style="float:left;width:50%;" id="email-display">
													<div style="float:left;">
														<div class="12u(xsmall)" style="float:left; width:90px;">Email</div><div class="6u(xsmall)" style="float:left;"><span class="email"><strong><?= $personal_info['email']; ?></strong></span></div><div style="clear:both;"></div>
														<div class="12u(xsmall)" style="float:left; width:90px;">Telephone</div><div class="6u(xsmall)" style="float:left;"><span class="tele"><strong><?= $personal_info['tel']; ?></strong></span></div><div style="clear:both;"></div>
														<div class="12u(xsmall)" style="float:left; width:90px;">Mobile</div><div class="6u(xsmall)" style="float:left;"><span class="mob"><strong><?= $personal_info['mobile']; ?></strong></span></div><div style="clear:both;"></div>
														<div class="12u(xsmall)" style="float:left; width:90px;">Fax</div><div class="6u(xsmall)" style="float:left;"><span class="fax"><strong><?= $personal_info['fax']; ?></strong></span></div><div style="clear:both;"></div>
													</div>

													<div style="float:right;">
														<a href="javascript: null(void)" data-toggle="modal" data-target="#contact-edit"><i class="fa fa-edit"></i> edit</a>
													</div>
													<div style="clear:both"></div>
												</div>

												<div class="modal fade" id="contact-edit" tabindex="-1" role="dialog" aria-labelledby="Editcontactmod" aria-hidden="true">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
																<h4 class="modal-title" id="Editcontactmod">Edit Contact Information</h4>
															</div>
															<div class="modal-body">
																<h4>Email</h4>
																<div class="form-group has-success">
																	<input class="form-control nemail" type="text" name="email" id="email" value="<?= $personal_info['email']; ?>" PLACEHOLDER="Email" style="width:145px" />
																</div>
																<h4>Telephone</h4>
																<div class="form-group has-success">
																	<input class="form-control ntele" type="text" name="tele" id="tele" value="<?= $personal_info['tel']; ?>" PLACEHOLDER="Telephone" style="width:145px" onkeypress="return isNumberKey(event)" />
																</div>
																<h4>Mobile</h4>
																<div class="form-group has-success">
																	<input class="form-control nmob" type="text" name="mob" id="mob" value="<?= $personal_info['mobile']; ?>" PLACEHOLDER="Mobile" style="width:145px" onkeypress="return isNumberKey(event)" />
																</div>
																<h4>Fax</h4>
																<div class="form-group has-success">
																	<input class="form-control nfax" type="text" name="fax" id="fax" value="<?= $personal_info['fax']; ?>" PLACEHOLDER="Fax" style="width:145px" />
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																	<button type="button" class="btn btn-primary" id="contactsubmit" data-dismiss="modal">Save changes</button>
																</div>
															</div>
														</div>
													</div>
												</div>
                                            </div>
                                        </div>
                                    </div>
									
									
<!---------------------------------------------------------------------------------------------------------------------->										
									
									
									<div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#profile_details" href="#contact_person_info">Contact Person's Details</a>
                                            </h4>
                                        </div>
                                        <div id="contact_person_info" class="panel-collapse collapse">
                                            <div class="panel-body">
												<div class="12u(xsmall)" style="float:left;width:51%;" id="cname-display">
													<div class="12u(xsmall)" style="float:left; width:90px;">Name</div>
													<div class="6u(xsmall)" style="float:left; ">
														<strong>
															<span class="cname"><?= $contact_person_info['contact_person']; ?></span>                
														</strong>
													</div>
													<div style="float:right;">
														<a href="javascript: null(void)" data-toggle="modal" style= "padding: 0 12px 0 0;" data-target="#cname-edit"><i class="fa fa-edit"></i> edit</a>
													</div>
												</div>
												<div class="modal fade" id="cname-edit" tabindex="-1" role="dialog" aria-labelledby="Editcnamemod" aria-hidden="true">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
																<h4 class="modal-title" id="Editcnamemod">Edit Name</h4>
															</div>
															<div class="modal-body">
																<h4>Name</h4>
																<div class="form-group has-success">
																	<input class="form-control cname" type="text" name="cname" id="cname" value="<?= $contact_person_info['contact_person']; ?>" PLACEHOLDER="Name" />
																</div>
															</div>
															<div class="modal-footer">
																<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																<button type="button" class="btn btn-primary" id="cnamesubmit" data-dismiss="modal">Save changes</button>
															</div>
														</div><!-- /.modal-content -->
													</div>
												</div>
												
												<div style="clear:both"></div>
												<span style="display:none" class="alert alert-info" id="cmobilemessage"></span>
												<div class="12u(xsmall)" style="float:left;width:51%;" id="cmobile-display">
													<div style="float:left;">
														<div class="12u(xsmall)" style="float:left; width:90px;">Mobile</div><div class="6u(xsmall)" style="float:left;"><span class="cmobile"><strong><?= $contact_person_info['mobile']; ?></strong></span></div><div style="clear:both;"></div>
													</div>

													<div style="float:right;">
														<a href="javascript: null(void)" data-toggle="modal" style= "padding: 0 12px 0 0;" data-target="#cmobile-edit"><i class="fa fa-edit"></i> edit</a>
													</div>
													<div style="clear:both"></div>
												</div>

												<div class="modal fade" id="cmobile-edit" tabindex="-1" role="dialog" aria-labelledby="Editcmobilemod" aria-hidden="true">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
																<h4 class="modal-title" id="Editcmobilemod">Edit Mobile</h4>
															</div>
															<div class="modal-body">
																<h4>Mobile</h4>
																<div class="form-group has-success">
																	<input class="form-cmobile nmob" type="text" name="cmobile" id="cmobile" value="<?= $contact_person_info['mobile']; ?>" PLACEHOLDER="Mobile" style="width:145px" onkeypress="return isNumberKey(event)" />
																</div>
																<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																<button type="button" class="btn btn-primary" id="cmobilesubmit" data-dismiss="modal">Save changes</button>
																
															</div>
														</div>
													</div>
												</div>
											
												<div style="clear:both"></div>
												<span style="display:none" class="alert alert-info" id="caddrmessage"></span>
												<div class="12u(xsmall)" style="float:left;width:50%;" id="caddr-display">
													<div class="12u(xsmall)" style="float:left; width:90px;">Address</div>
													<div class="6u(xsmall)" style="float:left;">
														<span class="caddr"><strong><?= $contact_person_info['address1']; ?></strong></span>
													</div>
													<div style="float:right;">
														<a href="javascript: null(void)" data-toggle="modal" data-target="#caddr-edit"><i class="fa fa-edit"></i> edit</a>
													</div>
													<div style="clear:both"></div>
												</div>
												<div class="modal fade" id="caddr-edit" tabindex="-1" role="dialog" aria-labelledby="Editcaddmod" aria-hidden="true">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
																<h4 class="modal-title" id="Editcaddmod">Edit Address</h4>
															</div>
															<div class="modal-body">
																<h4>Address</h4>
																<div class="form-group has-success">
																	<input class="form-control ncaddr" type="text" name="caddr" id="caddr" value="<?= $contact_person_info['address1']; ?>" PLACEHOLDER="Address"  />
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																	<button type="button" class="btn btn-primary" id="caddrsubmit" data-dismiss="modal">Save changes</button>
																</div>
															</div>
														</div>
													</div>
												</div>

												<div style="clear:both"></div>									
												<div class="12u(xsmall)" style="float:left;width:50%;" id="dropcaddr-display">
													<div style="float:left;">
														<div class="12u(xsmall)" style="float:left; width:90px;">Barangay</div><div class="6u(xsmall)" style="float:left;"><span class="ncbarangay" id="ncbarangay"><strong><?= $cbarangay['name']; ?></strong></span></div><div style="clear:both"></div>
														<div class="12u(xsmall)" style="float:left; width:90px;">City</div><div class="6u(xsmall)" style="float:left;"><span class="nccity" id="nccity"><strong><?= $ccity['name']; ?></strong></span></div><div style="clear:both"></div>
														<div class="12u(xsmall)" style="float:left; width:90px;">Province</div><div class="6u(xsmall)" style="float:left;"><span class="ncprovince" id="ncprovince"><strong><?= $cprovince['name']; ?></strong></span></div><div style="clear:both"></div>
														<div class="12u(xsmall)" style="float:left; width:90px;">Region</div><div class="6u(xsmall)" style="float:left;"><span class="ncregion" id="ncregion"><strong><?= $cregion['name']; ?></strong></span></div><div style="clear:both"></div>
														<div class="12u(xsmall)" style="float:left; width:90px;">Country</div><div class="6u(xsmall)" style="float:left;"><span class="nccountry" id="nccountry"><strong><?= $ccountry['countries_name']; ?></strong></span></div>
													</div>
													<div style="float:right;">
														<a href="javascript: null(void)" data-toggle="modal" data-target="#dropcaddr-edit"><i class="fa fa-edit"></i> edit</a>
													</div>
													<div style="clear:both"></div>
												</div>
												<div class="modal fade" id="dropcaddr-edit" tabindex="-1" role="dialog" aria-labelledby="Editdropcaddmod" aria-hidden="true">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
																<h4 class="modal-title" id="Editdropcaddmod">Edit Address</h4>
															</div>
															<div class="modal-body">
																<h4>Address</h4>
																
																<div class="form-group has-success">
																	<select name="ccountry" id="ccountry" class="form-control ccountry select-wrapper">
																		<option value="" selected="true" disabled="disabled">--Select Country--</option>
																		<?php IF (isset($ccountry['countries_name'])): ?>
																			<option value="" selected="true" ><?= $country['countries_name']; ?></option>
																			<?php ELSE: ?>
																			<option value="" selected="true" disabled="disabled">--Select Country--</option>
																		<?php
																		ENDIF;
																		$sql = mysqli_query($link, "SELECT countries_name, c_code FROM " . DB_PREFIX . "countries WHERE enabled = 1");
																		while ($nccountry = mysqli_fetch_array($sql)) {
																			?>
																			<option value="<?= $nccountry['c_code']; ?>"><?php
																				IF (isset($nccountry)): echo $nccountry['countries_name'];
																				ENDIF;
																				?></option>
																		<?php }
																		?>
																	</select>
																</div>

																<h4>Region</h4>
																<div class="form-group has-success">
																	<?php IF (isset($cregion['name'])): ?>
																		<select name="cregion" id="cregion" class="form-control cregion" ><option value="<?= $cregion['r_code']; ?>" selected="true" ><?= $cregion['name']; ?></option></select>
																		<?php ELSE: ?>
																		<select name="cregion" id="cregion" class="form-control cregion" disabled><option value="" selected="true" disabled="disabled">--Select Contry First--</option></select>
																	<?php ENDIF; ?>
																</div>

																<h4>Province</h4>
																<div class="form-group has-success">
																	<?php IF (isset($cprovince['name'])): ?>
																		<select name="cprovince" id="cprovince" class="form-control cprovince" ><option value="" selected="true" ><?= $cprovince['name']; ?></option></select>
																		<?php ELSE: ?>
																		<select name="cprovince" id="cprovince" class="form-control cprovince"  disabled><option value="" selected="true" disabled="disabled">--Select Region First--</option></select>
																	<?php ENDIF; ?>
																</div>

																<h4>City</h4>
																<div class="form-group has-success">
																	<?php IF (isset($ccity['name'])): ?>
																		<select name="ccity" id="ccity" class="form-control ccity" ><option value="" selected="true" ><?= $ccity['name']; ?></option></select>
																		<?php ELSE: ?>
																		<select name="ccity" id="ccity" class="form-control ccity"  disabled><option value="" selected="true" disabled="disabled">--Select Province First--</option></select>
																	<?php ENDIF; ?>
																</div>

																<h4>Barangay</h4>
																<div class="form-group has-success">
																	<?php IF (isset($cbarangay['name'])): ?>
																		<select name="cbarangay" id="cbarangay" class="form-control cbarangay" ><option value="" selected="true" ><?= $cbarangay['name']; ?></option></select>
																		<?php ELSE: ?>
																		<select name="cbarangay" id="cbarangay" class="form-control cbarangay" disabled><option value="" selected="true" disabled="disabled">--Select City First--</option></select>
																	<?php ENDIF; ?>
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																	<button type="button" class="btn btn-primary" id="dropcaddrsubmit" data-dismiss="modal">Save changes</button>
																</div>
															</div>
														</div>
													</div>
												</div>
                                            </div>
                                        </div>
                                    </div>
									
<!---------------------------------------------------------------------------------------------------------------------->										
									<?php IF($personal_info['userlevel'] == 7 || $personal_info['userlevel'] == 10): ?>
									
									<div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#profile_details" href="#bank_info">Bank Account Information</a>
                                            </h4>
                                        </div>
                                        <div id="bank_info" class="panel-collapse collapse">
                                            <div class="panel-body">
												<span style="display:none" class="alert alert-info" id="bankmessage"></span>
												<div class="12u(xsmall)" style="float:left;width:50%;" id="bank-display">
													<div style="float:left;">
														<div class="12u(xsmall)" style="float:left; width:90px;">Bank Name</div><div class="6u(xsmall)" style="float:left;"><span class="bank"><strong><?php IF(isset($Bname)): echo $Bname; ELSE: echo "-N/A-"; ENDIF; ?></strong></span></div><div style="clear:both;"></div>
														<div class="12u(xsmall)" style="float:left; width:90px;">Account No</div><div class="6u(xsmall)" style="float:left;"><span class="account"><strong><?php IF($BankAccountNo): echo $BankAccountNo; ELSE: echo "-N/A-"; ENDIF; ?></strong></span></div><div style="clear:both;"></div>
													</div>

													<div style="float:right;">
														<a href="javascript: null(void)" data-toggle="modal" data-target="#bank-edit"><i class="fa fa-edit"></i> edit</a>
													</div>
													<div style="clear:both"></div>
												</div>

												<div class="modal fade" id="bank-edit" tabindex="-1" role="dialog" aria-labelledby="Editbankmod" aria-hidden="true">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
																<h4 class="modal-title" id="Editbankmod">Edit Bank Account Information</h4>
															</div>
															<div class="modal-body">
																<h4>Bank Name</h4>
																<div class="form-group has-success">
																
																	<select class="form-control" name="bank" id="bank">
																		<option value='<?php IF(isset($Bank_ID)): echo $bank_ID; ELSE: echo ""; ENDIF; ?>'><?php IF(isset($Bname)): echo $Bname; ELSE: echo "-Select Bank-"; ENDIF; ?></option>
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
																<h4>Account No</h4>
																<div class="form-group has-success">
																	<input class="form-control naccount" type="text" name="account" id="account" value="<?= $bank_account_info['account_no']; ?>" PLACEHOLDER="Account No" style="width:145px" onkeypress="return isNumberKey(event)" />
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																	<button type="button" class="btn btn-primary" id="banksubmit" data-dismiss="modal">Save changes</button>
																</div>
															</div>
														</div>
													</div>
												</div>
                                            </div>
                                        </div>
                                    </div>
									<div style="clear:both"></div>
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title">
													<a data-toggle="collapse" data-parent="#profile_details" href="#credentials">Credentials</a>
												</h4>
											</div>
											<div id="credentials" class="panel-collapse collapse">
												<div style="float:left;">
													<div class="12u(xsmall)" style="float:left; width:106px;">NBI</div><div class="6u(xsmall)" style="float:left; margin: 0 0 0 25px;"><span class="nbi"><strong><?= $credentials['NBI']; ?></strong></span></div><div style="clear:both;"></div>
													<div class="12u(xsmall)" style="float:left; width:106px;">NBI Expiration</div><div class="6u(xsmall)" style="float:left; margin: 0 0 0 25px;"><span class="NBI_expiry"><strong><?= $credentials['NBI_expiry']; ?></strong></span></div><div style="clear:both;"></div>
													<div class="12u(xsmall)" style="float:left; width:106px;">Driver License</div><div class="6u(xsmall)" style="float:left; margin: 0 0 0 25px;"><span class="drivers_license"><strong><?= $credentials['drivers_license']; ?></strong></span></div><div style="clear:both;"></div>
													<div class="12u(xsmall)" style="float:left; width:106px;">Expiration</div><div class="6u(xsmall)" style="float:left; margin: 0 0 0 25px;"><span class="DL_expiry"><strong><?= $credentials['DL_expiry']; ?></strong></span></div><div style="clear:both;"></div>
													<div class="12u(xsmall)" style="float:left; width:106px;">Police Clearance</div><div class="6u(xsmall)" style="float:left; margin: 0 0 0 25px;"><span class="police_clearance"><strong><?= $credentials['police_clearance']; ?></strong></span></div><div style="clear:both;"></div>
													<div class="12u(xsmall)" style="float:left; width:106px;">Expiration</div><div class="6u(xsmall)" style="float:left; margin: 0 0 0 25px;"><span class="police_expiry"><strong><?= $credentials['police_expiry']; ?></strong></span></div><div style="clear:both;"></div>
												</div>
											</div>
										</div>	
									<?php ENDIF; ?>
<!---------------------------------------------------------------------------------------------------------------------->	

                                </div>
                            </div>
                            <!-- .panel-body -->
                        </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
					<?php include_once("includes/footer.php"); ?>
				<!-- /.row -->
			</div>
		</div>
		
		 <!-- jQuery -->
        <script src="js/jquery.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="js/bootstrap.min.js"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="js/metisMenu.min.js"></script>
		
		<!-- DataTables JavaScript -->
		<script src="js/dataTables/jquery.dataTables.min.js"></script>
		<script src="js/dataTables/dataTables.bootstrap.min.js"></script>

        <!-- Custom Theme JavaScript -->
        <script src="js/startmin.js"></script>
		
		
		<script language="javascript" type="text/javascript">
			$(document).ready(function() {

				$("#namesubmit").click(function() {
					var fname = $("#fname").val();
					var mname = $("#mname").val();
					var lname = $("#lname").val();
					//	var gender = $("input[type=radio]:checked").val();

					if (fname == '' || mname == '' || lname == '') {
						alert("Update Failed Some Name Fields are Blank....!!");
					} else {
						// Returns successful data submission message when the entered information is stored in database.
						$.post("trigger/updatenameform", { fname1: fname, mname1: mname, lname1: lname, changename: 1 },
							function(data) {
								document.getElementById("namemessage").textContent = "Name has been updated";
								$('#namemessage').delay().fadeIn();
								$('#namemessage').delay(3000).fadeOut();
								//$('#editnameform')[0].reset(); //To reset form fields
							}
						);
						$(".fname").text($("#fname").val());
						$(".lname").text($("#lname").val());
					}
				});

				$("#dobsubmit").click(function() {
					var dob = $("#dob").val();

					if (dob == null || dob == "") {
						alert("Date of Birth must be filled out");
						return false;
					} else {
						$.post("trigger/updatenameform", {
								dob1: dob,
								changedob: 1
							},
							function(data) {
								document.getElementById("dobmessage").textContent = "Birthday has been updated";
								$('#dobmessage').delay().fadeIn();
								$('#dobmessage').delay(3000).fadeOut();
							}
						);
						$(".dob").text($("#dob").val());
					}
				});

				$("#occusubmit").click(function() {
					var occu = $("#occu").val();

					$.post("trigger/updatenameform", {
							occu1: occu,
							changeoccu: 1
						},
						function(data) {
							document.getElementById("occumessage").textContent = "Occupation has been updated";
							$('#occumessage').delay().fadeIn();
							$('#occumessage').delay(3000).fadeOut();
						}
					);
					$(".occu").text($("#occu").val());

				});

				$("#relisubmit").click(function() {
					var reli = $("#reli").val();

					$.post("trigger/updatenameform", {
							reli1: reli,
							changereli: 1
						},
						function(data) {
							document.getElementById("relimessage").textContent = "Religion has been updated";
							$('#relimessage').delay().fadeIn();
							$('#relimessage').delay(3000).fadeOut();
						}
					);
					$(".reli").text($("#reli").val());

				});

				$("#tinsubmit").click(function() {
					var tin = $("#tin").val();

					$.post("trigger/updatenameform", {
							tin1: tin,
							changetin: 1
						},
						function(data) {
							document.getElementById("tinmessage").textContent = "TIN number has been updated";
							$('#tinmessage').delay().fadeIn();
							$('#tinmessage').delay(3000).fadeOut();
						}
					);
					$(".tin").text($("#tin").val());

				});
				
				$("#ssssubmit").click(function() {
					var sss = $("#sss").val();

					$.post("trigger/updatenameform", {
							sss1: sss,
							changesss: 1
						},
						function(data) {
							document.getElementById("sssmessage").textContent = "SSS number has been updated";
							$('#sssmessage').delay().fadeIn();
							$('#sssmessage').delay(3000).fadeOut();
						}
					);
					$(".sss").text($("#sss").val());

				});

				$("#contactsubmit").click(function() {
					var email = $("#email").val();
					var tele = $("#tele").val();
					var mob = $("#mob").val();
					var fax = $("#fax").val();

					$.post("trigger/updatenameform", {
							email1: email,
							tele1: tele,
							mob1: mob,
							fax1: fax,
							changecontact: 1
						},
						function(data) {
							document.getElementById("contactmessage").textContent = "Contact information has been updated";
							$('#contactmessage').delay().fadeIn();
							$('#contactmessage').delay(3000).fadeOut();
						}
					);
					$(".email").text($("#email").val());
					$(".tele").text($("#tele").val());
					$(".mob").text($("#mob").val());
					$(".fax").text($("#fax").val());
				});

				$("#addrsubmit").click(function() {
					var addr = $("#addr").val();

					$.post("trigger/updatenameform", {
							addr1: addr,
							changeaddr: 1
						},
						function(data) {
							document.getElementById("addrmessage").textContent = "Address has been updated";
							$('#addrmessage').delay().fadeIn();
							$('#addrmessage').delay(3000).fadeOut();
						}
					);
					$(".addr").text($("#addr").val());

				});

				$("#dropaddrsubmit").click(function() {
					var country = $("#country").val();
					var region = $("#region").val();
					var province = $("#province").val();
					var city = $("#city").val();
					var barangay = $("#barangay").val();
					//	var gender = $("input[type=radio]:checked").val();

					if (country == '' || region == '' || province == '' || city == '' || barangay == '') {
						alert("Update Failed Some Address Fields are Blank....!!");
					} else {
						// Returns successful data submission message when the entered information is stored in database.
						$.post("trigger/updatenameform", {
								country1: country,
								region1: region,
								province1: province,
								city1: city,
								barangay1: barangay,
								changedropaddr: 1
							},
							function(data) {
								document.getElementById("addrmessage").textContent = "Address has been updated";
								$('#addrmessage').delay().fadeIn();
								$('#addrmessage').delay(3000).fadeOut();
								$(".ncountry").html("<strong>" + $("#country option:selected").text() + "</strong>");
								$(".nregion").html("<strong>" + $("#region option:selected").text() + "</strong>");
								$(".nprovince").html("<strong>" + $("#province option:selected").text() + "</strong>");
								$(".ncity").html("<strong>" + $("#city option:selected").text() + "</strong>");
								$(".nbarangay").html("<strong>" + $("#barangay option:selected").text() + "</strong>");

							}
						);


					}
				});

				$("#zipsubmit").click(function() {
					var zip = $("#zip").val();

					$.post("trigger/updatenameform", {
							zip1: zip,
							changezip: 1
						},
						function(data) {
							document.getElementById("addrmessage").textContent = "ZIP Code has been updated";
							$('#addrmessage').delay().fadeIn();
							$('#addrmessage').delay(3000).fadeOut();
						}
					);
					$(".zip").text($("#zip").val());

				});

				$(".country").change(function() {
					var id = $(this).val();
					var dataString = 'id=' + id;
					$.ajax({
						type: "POST",
						url: "../myaccount/includes/location/region.php",
						data: dataString,
						cache: false,
						success: function(html) {
							$(".region").html(html);
						}
					});
					$('#region option').each(function(index, option) {
						$(option).remove();
					});
					var option = document.createElement("option");
					option.text = "--Please Wait--";
					option.value = "";
					var select = document.getElementById("region");
					select.appendChild(option);
					$('#province option').each(function(index, option) {
						$(option).remove();
					});
					var option = document.createElement("option");
					option.text = "--Select Region First--";
					option.value = "";
					var select = document.getElementById("province");
					select.appendChild(option);
					$('#province').prop('disabled', true);
					$('#city option').each(function(index, option) {
						$(option).remove();
					});
					var option = document.createElement("option");
					option.text = "--Select Province First--";
					option.value = "";
					var select = document.getElementById("city");
					select.appendChild(option);
					$('#city').prop('disabled', true);
					$('#barangay option').each(function(index, option) {
						$(option).remove();
					});
					var option = document.createElement("option");
					option.text = "--Select City First--";
					option.value = "";
					var select = document.getElementById("barangay");
					select.appendChild(option);
					$('#barangay').prop('disabled', true);
				});

				$(".region").change(function() {
					var id = $(this).val();
					var dataString = 'id=' + id;
					$.ajax({
						type: "POST",
						url: "../myaccount/includes/location/province.php",
						data: dataString,
						cache: false,
						success: function(html) {
							$(".province").html(html);
						}
					});
					$('#province option').each(function(index, option) {
						$(option).remove();
					});
					var option = document.createElement("option");
					option.text = "--Please Wait--";
					option.value = "";
					var select = document.getElementById("province");
					select.appendChild(option);
					$('#city option').each(function(index, option) {
						$(option).remove();
					});
					var option = document.createElement("option");
					option.text = "--Select Province First--";
					option.value = "";
					var select = document.getElementById("city");
					select.appendChild(option);
					$('#city').prop('disabled', true);
					$('#barangay option').each(function(index, option) {
						$(option).remove();
					});
					var option = document.createElement("option");
					option.text = "--Select City First--";
					option.value = "";
					var select = document.getElementById("barangay");
					select.appendChild(option);
					$('#barangay').prop('disabled', true);
				});

				$(".province").change(function() {
					var id = $(this).val();
					var dataString = 'id=' + id;
					$.ajax({
						type: "POST",
						url: "../myaccount/includes/location/city.php",
						data: dataString,
						cache: false,
						success: function(html) {
							$(".city").html(html);
						}
					});
					$('#city option').each(function(index, option) {
						$(option).remove();
					});
					var option = document.createElement("option");
					option.text = "--Please Wait--";
					option.value = "";
					var select = document.getElementById("city");
					select.appendChild(option);
					$('#barangay option').each(function(index, option) {
						$(option).remove();
					});
					var option = document.createElement("option");
					option.text = "--Select City First--";
					option.value = "";
					var select = document.getElementById("barangay");
					select.appendChild(option);
					$('#barangay').prop('disabled', true);
				});

				$(".city").change(function() {
					var id = $(this).val();
					var dataString = 'id=' + id;
					$.ajax({
						type: "POST",
						url: "../myaccount/includes/location/barangay.php",
						data: dataString,
						cache: false,
						success: function(html) {
							$(".barangay").html(html);
						}
					});
					$('#barangay option').each(function(index, option) {
						$(option).remove();
					});
					var option = document.createElement("option");
					option.text = "--Please Wait--";
					option.value = "";
					var select = document.getElementById("barangay");
					select.appendChild(option);
				});

				//contact person 
				$("#cnamesubmit").click(function() {
					var cname = $("#cname").val();
					if (cname == '') {
						alert("Update Failed Some Name Fields are Blank....!!");
					} else {
						$.post("trigger/updatenameform", {
								cname1: cname,
								changecname: 1
							},
							function(data) {
								document.getElementById("cnamemessage").textContent = "Name has been updated";
								$('#cnamemessage').delay().fadeIn();
								$('#cnamemessage').delay(3000).fadeOut();
							}
						);
						$(".cname").text($("#cname").val());
					}
				});

				$("#cmobilesubmit").click(function() {
					var cmobile = $("#cmobile").val();
					if (cmobile == '') {
						alert("Update Failed Some Name Fields are Blank....!!");
					} else {
						$.post("trigger/updatenameform", {
								cmobile1: cmobile,
								changecmobile: 1
							},
							function(data) {
								document.getElementById("cmobilemessage").textContent = "Mobile has been updated";
								$('#cmobilemessage').delay().fadeIn();
								$('#cmobilemessage').delay(3000).fadeOut();
							}
						);
						$(".cmobile").text($("#cmobile").val());
					}
				});

				$("#caddrsubmit").click(function() {
					var caddr = $("#caddr").val();
					if (caddr == '') {
						alert("Update Failed Some Address Fields are Blank....!!");
					} else {
						$.post("trigger/updatenameform", {
								caddr1: caddr,
								changecaddr: 1
							},
							function(data) {
								document.getElementById("caddrmessage").textContent = "Address has been updated";
								$('#caddrmessage').delay().fadeIn();
								$('#caddrmessage').delay(3000).fadeOut();
							}
						);
						$(".caddr").text($("#caddr").val());
					}
				});

				$("#dropcaddrsubmit").click(function() {
					var ccountry = $("#ccountry").val();
					var cregion = $("#cregion").val();
					var cprovince = $("#cprovince").val();
					var ccity = $("#ccity").val();
					var cbarangay = $("#cbarangay").val();
					//	var gender = $("input[type=radio]:checked").val();

					if (ccountry == '' || cregion == '' || cprovince == '' || ccity == '' || cbarangay == '') {
						alert("Update Failed Some Address Fields are Blank....!!");
					} else {
						// Returns successful data submission message when the entered information is stored in database.
						$.post("trigger/updatenameform", {
								ccountry1: ccountry,
								cregion1: cregion,
								cprovince1: cprovince,
								ccity1: ccity,
								cbarangay1: cbarangay,
								changedropcaddr: 1
							},
							function(data) {
								document.getElementById("caddrmessage").textContent = "Address has been updated";
								$('#caddrmessage').delay().fadeIn();
								$('#caddrmessage').delay(3000).fadeOut();

								$(".nccountry").html("<strong>" + $("#ccountry option:selected").text() + "</strong>");
								$(".ncregion").html("<strong>" + $("#cregion option:selected").text() + "</strong>");
								$(".ncprovince").html("<strong>" + $("#cprovince option:selected").text() + "</strong>");
								$(".nccity").html("<strong>" + $("#ccity option:selected").text() + "</strong>");
								$(".ncbarangay").html("<strong>" + $("#cbarangay option:selected").text() + "</strong>");
							}
						);
					}
				});


				$(".ccountry").change(function() {
					var id = $(this).val();
					var dataString = 'id=' + id;
					$.ajax({
						type: "POST",
						url: "../myaccount/includes/location/cregion.php",
						data: dataString,
						cache: false,
						success: function(html) {
							$(".cregion").html(html);
						}
					});
					$('#cregion option').each(function(index, option) {
						$(option).remove();
					});
					var option = document.createElement("option");
					option.text = "--Please Wait--";
					option.value = "";
					var select = document.getElementById("cregion");
					select.appendChild(option);
					$('#cprovince option').each(function(index, option) {
						$(option).remove();
					});
					var option = document.createElement("option");
					option.text = "--Select Region First--";
					option.value = "";
					var select = document.getElementById("cprovince");
					select.appendChild(option);
					$('#cprovince').prop('disabled', true);
					$('#ccity option').each(function(index, option) {
						$(option).remove();
					});
					var option = document.createElement("option");
					option.text = "--Select Province First--";
					option.value = "";
					var select = document.getElementById("ccity");
					select.appendChild(option);
					$('#ccity').prop('disabled', true);
					$('#cbarangay option').each(function(index, option) {
						$(option).remove();
					});
					var option = document.createElement("option");
					option.text = "--Select City First--";
					option.value = "";
					var select = document.getElementById("cbarangay");
					select.appendChild(option);
					$('#cbarangay').prop('disabled', true);
				});

				$(".cregion").change(function() {
					var id = $(this).val();
					var dataString = 'id=' + id;
					$.ajax({
						type: "POST",
						url: "../myaccount/includes/location/cprovince.php",
						data: dataString,
						cache: false,
						success: function(html) {
							$(".cprovince").html(html);
						}
					});
					$('#cprovince option').each(function(index, option) {
						$(option).remove();
					});
					var option = document.createElement("option");
					option.text = "--Please Wait--";
					option.value = "";
					var select = document.getElementById("cprovince");
					select.appendChild(option);
					$('#ccity option').each(function(index, option) {
						$(option).remove();
					});
					var option = document.createElement("option");
					option.text = "--Select Province First--";
					option.value = "";
					var select = document.getElementById("ccity");
					select.appendChild(option);
					$('#ccity').prop('disabled', true);
					$('#cbarangay option').each(function(index, option) {
						$(option).remove();
					});
					var option = document.createElement("option");
					option.text = "--Select City First--";
					option.value = "";
					var select = document.getElementById("cbarangay");
					select.appendChild(option);
					$('#cbarangay').prop('disabled', true);
				});

				$(".cprovince").change(function() {
					var id = $(this).val();
					var dataString = 'id=' + id;
					$.ajax({
						type: "POST",
						url: "../myaccount/includes/location/ccity.php",
						data: dataString,
						cache: false,
						success: function(html) {
							$(".ccity").html(html);
						}
					});
					$('#ccity option').each(function(index, option) {
						$(option).remove();
					});
					var option = document.createElement("option");
					option.text = "--Please Wait--";
					option.value = "";
					var select = document.getElementById("ccity");
					select.appendChild(option);
					$('#cbarangay option').each(function(index, option) {
						$(option).remove();
					});
					var option = document.createElement("option");
					option.text = "--Select City First--";
					option.value = "";
					var select = document.getElementById("cbarangay");
					select.appendChild(option);
					$('#cbarangay').prop('disabled', true);
				});

				$(".ccity").change(function() {
					var id = $(this).val();
					var dataString = 'id=' + id;
					$.ajax({
						type: "POST",
						url: "../myaccount/includes/location/cbarangay.php",
						data: dataString,
						cache: false,
						success: function(html) {
							$(".cbarangay").html(html);
						}
					});
					$('#cbarangay option').each(function(index, option) {
						$(option).remove();
					});
					var option = document.createElement("option");
					option.text = "--Please Wait--";
					option.value = "";
					var select = document.getElementById("cbarangay");
					select.appendChild(option);
				});


			});

			$("#banksubmit").click(function() {
				var bank = $("#bank").val();
				var account = $("#account").val();

				$.post("trigger/updatenameform", {
						bank1: bank,
						account1: account,
						changebank: 1
					},
					function(data) {
						document.getElementById("bankmessage").textContent = "Bank Account information has been updated";
						$('#bankmessage').delay().fadeIn();
						$('#bankmessage').delay(3000).fadeOut();
						$(".bank").html("<strong>" + data + "</strong>");
						$(".account").html("<strong>" + account + "</strong>");
					}
				);

			});


			// NUMBERS ONLY
			function isNumberKey(evt) {
				var charCode = (evt.which) ? evt.which : event.keyCode
				if (charCode > 31 && (charCode < 48 || charCode > 57))
					return false;
				return true;
			}
		</script>

    </body>
</html>