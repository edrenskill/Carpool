<?php
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	
	$member_ID = $_SESSION['memberID'];
	
	$personal_info = mysqli_fetch_assoc(mysqli_query($link, "SELECT username, email, fname, mname, lname, suffix, gender, dob, marital, occupation, religion, zip_code, address, country, region, province, city, barangay, tel, mobile, fax, regdate, user_ID, TIN, photo FROM ".DB_PREFIX."users WHERE user_ID = '".$member_ID."'"));
	
	$country = mysqli_fetch_array(mysqli_query($link, "SELECT countries_name FROM ".DB_PREFIX."countries WHERE c_code = '".$personal_info['country']."' AND enabled = 1"));
	$region = mysqli_fetch_array(mysqli_query($link, "SELECT r_code, name FROM ".DB_PREFIX."regions WHERE r_code = '".$personal_info['region']."'"));
	$province = mysqli_fetch_array(mysqli_query($link, "SELECT name FROM ".DB_PREFIX."provinces WHERE p_code = '".$personal_info['province']."'"));
	$city = mysqli_fetch_array(mysqli_query($link, "SELECT name FROM ".DB_PREFIX."city_municipality WHERE cm_code = '".$personal_info['city']."'"));
	$barangay = mysqli_fetch_array(mysqli_query($link, "SELECT name FROM ".DB_PREFIX."barangays WHERE brgy_code = '".$personal_info['barangay']."'"));

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include_once('includes/header.php'); ?>
	</head>
    <body>

        <div id="wrapper">

            <?php include ('includes/navigation.php'); ?>

            <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">

                            <h1 class="page-header">Member Profile</h1>
							
                        </div><!-- /.col-lg-12 -->
                    </div><!-- /.row -->
					<div class="row">
						
						<div class="col-lg-4">
							<div class="panel panel-primary">
								<div class="panel-heading">
									Account No.: <?= $member_ID; ?>
								</div>
								<div class="panel-body">
									<a href="javascript: null(void)" onclick='$("#genprolink1").hide();$("#genprolink2").show("slow");$("#genpro").show("slow");$("#addprolink1").show("slow");$("#addprolink2").hide();$("#addpro").hide();$("#conprolink1").show("slow");$("#conprolink2").hide();$("#conpro").hide();'><div class="12u(xsmall)" id="genprolink1" style="display:none; border-top:solid 1px #20b990; border-bottom:solid 1px #20b990; padding-left: 10px">▼   PERSONAL INFO</div></a>										
									<a href="javascript: null(void)" onclick='$("#genprolink1").show("slow");$("#genprolink2").hide();$("#genpro").hide();'><div class="12u(xsmall)" id="genprolink2" style="border-top:solid 1px #20b990; border-bottom:solid 1px #20b990; padding-left: 10px">▲   PERSONAL INFO</div></a>

									<div class="container" id="genpro" style="display:block;">
										<div class="12u(xsmall)" style="float:left;width:50%;" id="name-display">
											<div class="12u(xsmall)" style="float:left; width:90px;">Name</div>
											<div class="6u(xsmall)" style="float:left; ">
												<strong>
													<span class="fname"><?= $personal_info['fname']; ?></span>
													<span class="lname"><?= $personal_info['lname']; ?></span>
													<?php IF($personal_info['suffix'] != ""): echo ", ".$personal_info['suffix']; ENDIF; ?>
												</strong>
											</div>
										</div>
										
										<div style="clear:both"></div>
										<div class="12u(xsmall)" style="float:left;width:50%;" id="dob-display">
											<div class="12u(xsmall)" style="float:left; width:90px;">Birthday</div>
											<div class="6u(xsmall)" style="float:left;">
												<span class="dob">
													<strong><?= date('F j, Y', strtotime($personal_info['dob'])); ; ?></strong>
												</span>
											</div>
										</div>
										<div style="clear:both"></div>		
										<!--	<div class="12u(xsmall)" style="float:left; width:150px;">Marital Status</div><div class="6u(xsmall)" style="float:left; width:350px;"><strong><?= $personal_info['marital']; ?></strong></div><div style="clear:both"></div> -->
										<div class="12u(xsmall)" style="float:left;width:50%;" id="occu-display">
											<div class="12u(xsmall)" style="float:left; width:90px;">Occupation</div>
											<div class="6u(xsmall)" style="float:left;">
												<span class="occu"><strong><?= $personal_info['occupation']; ?></strong></span>
											</div>
										</div>

										<div style="clear:both"></div>
										<div class="12u(xsmall)" style="float:left;width:50%;" id="reli-display">
											<div class="12u(xsmall)" style="float:left; width:90px;">Religion</div>
											<div class="6u(xsmall)" style="float:left;">
												<span class="reli"><strong><?= $personal_info['religion']; ?></strong></span>
											</div>
										</div>
										
										<div style="clear:both"></div>
										<div class="12u(xsmall)" style="float:left;width:50%;" id="tin-display">
											<div class="12u(xsmall)" style="float:left; width:90px;">TIN</div>
											<div class="6u(xsmall)" style="float:left;">
												<span class="tin"><strong><?= $personal_info['TIN']; ?></strong></span>
											</div>
										</div>
									</div>
											
									<!-- ADDRESS -->
									<a href="javascript: null(void)" onclick='$("#addprolink1").hide();$("#addprolink2").show("slow");$("#addpro").show("slow");$("#genprolink1").show("slow");$("#genprolink2").hide();$("#genpro").hide();$("#conprolink1").show("slow");$("#conprolink2").hide();$("#conpro").hide();'><div class="12u(xsmall)" id="addprolink1" style="border-top:solid 1px #20b990; border-bottom:solid 1px #20b990; padding-left: 10px">▼   ADDRESS</div></a>
									<a href="javascript: null(void)" onclick='$("#addprolink1").show("slow");$("#addprolink2").hide();$("#addpro").hide();'><div class="12u(xsmall)" id="addprolink2" style="display:none; border-top:solid 1px #20b990; border-bottom:solid 1px #20b990; padding-left: 10px">▲   ADDRESS</div></a>

									<div class="container" id="addpro" style="display:none;">
										<div class="12u(xsmall)" style="float:left;width:50%;" id="addr-display">
											<div class="12u(xsmall)" style="float:left; width:90px;">Address</div>
											<div class="6u(xsmall)" style="float:left;">
												<span class="addr"><strong><?= $personal_info['address']; ?></strong></span>
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
										</div>

										<div style="clear:both"></div>
										<div class="12u(xsmall)" style="float:left;width:50%;" id="zip-display">
											<div class="12u(xsmall)" style="float:left; width:90px;">ZIP Code</div>
											<div class="6u(xsmall)" style="float:left;">
												<span class="zip"><strong><?= $personal_info['zip_code']; ?></strong></span>
											</div>
										</div>
									</div>

									
									<!-- CONTACT -->
									<a href="javascript: null(void)" onclick='$("#conprolink1").hide();$("#conprolink2").show("slow");$("#conpro").show("slow");$("#genprolink1").show("slow");$("#genprolink2").hide();$("#genpro").hide();$("#addprolink1").show("slow");$("#addprolink2").hide();$("#addpro").hide();'><div class="12u(xsmall)" id="conprolink1" style="border-top:solid 1px #20b990; border-bottom:solid 1px #20b990; padding-left: 10px">▼   CONTACT INFO</div></a>
									<a href="javascript: null(void)" onclick='$("#conprolink1").show("slow");$("#conprolink2").hide();$("#conpro").hide();'><div class="12u(xsmall)" id="conprolink2" style="display:none; border-top:solid 1px #20b990; border-bottom:solid 1px #20b990; padding-left: 10px">▲   CONTACT INFO</div></a>

									<div class="container" id="conpro" style="display:none;">
										<div class="12u(xsmall)" style="float:left;width:50%;" id="email-display">
											<div style="float:left;">
												<div class="12u(xsmall)" style="float:left; width:90px;">Email</div><div class="6u(xsmall)" style="float:left;"><span class="email"><strong><?= $personal_info['email']; ?></strong></span></div><div style="clear:both;"></div>
												<div class="12u(xsmall)" style="float:left; width:90px;">Telephone</div><div class="6u(xsmall)" style="float:left;"><span class="tele"><strong><?= $personal_info['tel']; ?></strong></span></div><div style="clear:both;"></div>
												<div class="12u(xsmall)" style="float:left; width:90px;">Mobile</div><div class="6u(xsmall)" style="float:left;"><span class="mob"><strong><?= $personal_info['mobile']; ?></strong></span></div><div style="clear:both;"></div>
												<div class="12u(xsmall)" style="float:left; width:90px;">Fax</div><div class="6u(xsmall)" style="float:left;"><span class="fax"><strong><?= $personal_info['fax']; ?></strong></span></div><div style="clear:both;"></div>
											</div>
										</div>
									</div>
								</div>
								<div class="panel-footer">

								</div>
							</div>
						</div>
						<!-- /.col-lg-4 -->
						
						
						<div class="col-lg-4">
							<div class="panel panel-default">
								<div class="panel-heading">
									ID Details
								</div>
								<div class="panel-body">
									<img src="../profile/members/<?= $member_ID; ?>/ID/front-<?= $member_ID; ?>.jpg" width="212" height="337"/>
								</div>
								<div class="panel-footer">
								</div>
							</div>
						</div>
						<!-- /.col-lg-4 -->
					</div>
					
                </div><!-- /.container-fluid -->
            </div><!-- /#page-wrapper -->

        </div><!-- /#wrapper -->

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
