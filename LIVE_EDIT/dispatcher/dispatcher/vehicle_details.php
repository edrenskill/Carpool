<?php
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS

	$vehicle_ID = $_SESSION['vehicleID'];

	$unit_query = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM ".DB_PREFIX."vehicles WHERE `unit_ID`='".$vehicle_ID."'"));

	$owner_ID = $unit_query['owner_ID'];
	$driver1_ID = $unit_query['driver_ID1'];
	$driver2_ID = $unit_query['driver_ID2'];

	$owner_info = mysqli_fetch_array(mysqli_query($link, "SELECT fname,lname,suffix FROM ".DB_PREFIX."users WHERE user_ID = '".$owner_ID."'"));
	$driver1_info = mysqli_fetch_array(mysqli_query($link, "SELECT fname,lname,suffix FROM ".DB_PREFIX."users WHERE user_ID = '".$driver1_ID."'"));
	$driver2_info = mysqli_fetch_array(mysqli_query($link, "SELECT fname,lname,suffix FROM ".DB_PREFIX."users WHERE user_ID = '".$driver2_ID."'"));
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

                            <h1 class="page-header">Vehicle Details</h1>
							
                        </div><!-- /.col-lg-12 -->
                    </div><!-- /.row -->
					
					<div class="panel panel-default">
                        <div class="panel-heading">

							<span style="float:left;"><h4>Unit Profile</h4></span>
							<span style="float:right;"><h4>Unit ID: <?= $vehicle_ID; ?></h4></span>
							<div style="clear:both;"></div>
							
							<div class="container" id="genpro" style="display:block;">
								<div class="12u(xsmall)" style="float:left;width:50%;" id="name-display">
									<div class="12u(xsmall)" style="float:left; width:180px;">Vehicle Owner</div>
									<div class="6u(xsmall)" style="float:left; ">
										<strong>
											<span class="fname"><?= $owner_info['fname']; ?></span>
											<span class="lname"><?= $owner_info['lname']; ?></span>
											<?php IF($owner_info['suffix'] != ""): echo ", ".$owner_info['suffix']; ENDIF; ?>
										</strong>
									</div>
								</div>
								
								<div style="clear:both;"></div>
								<div class="12u(xsmall)" style="float:left;width:50%;" id="d1name-display">
									<div class="12u(xsmall)" style="float:left; width:180px;">Driver's Name</div>
									<div class="6u(xsmall)" style="float:left; ">
										<strong>
											<span class="d1fname"><?= $driver1_info['fname']; ?></span>
											<span class="d1lname"><?= $driver1_info['lname']; ?></span>
											<?php IF($driver1_info['suffix'] != ""): echo ", ".$driver1_info['suffix']; ENDIF; ?>
										</strong>
									</div>
								</div>
								
								<div style="clear:both;"></div>
								<div class="12u(xsmall)" style="float:left;width:50%;" id="d2name-display">
									<div class="12u(xsmall)" style="float:left; width:180px;">2nd Driver's Name</div>
									<div class="6u(xsmall)" style="float:left; ">
										<strong>
											<span class="d2fname"><?= $driver2_info['fname']; ?></span>
											<span class="d2lname"><?= $driver2_info['lname']; ?></span>
											<?php IF($driver2_info['suffix'] != ""): echo ", ".$driver2_info['suffix']; ENDIF; ?>
										</strong>
									</div>
								</div>
								
								<div style="clear:both"></div>		
								<div class="12u(xsmall)" style="color:#0000FF;" id="plate-displaymess">
									<span class="platemessage" id="platemessage"></span>
								</div>
								<div class="12u(xsmall)" style="float:left;width:50%;" id="plate-display">
									<div class="12u(xsmall)" style="float:left; width:180px;">Plate No.:</div>
									<div class="6u(xsmall)" style="float:left;">
										<span class="plate"><strong><?= $unit_query['plate_number']; ?></strong></span>
									</div>
								</div>
								
								<div style="clear:both"></div>
								<div class="12u(xsmall)" style="color:#0000FF;" id="make-displaymess">
									<span class="makemessage" id="makemessage"></span>
								</div>
								<div class="12u(xsmall)" style="float:left;width:50%;" id="make-display">
									<div class="12u(xsmall)" style="float:left; width:180px;">Make/Brand:</div>
									<div class="6u(xsmall)" style="float:left;">
										<span class="make"><strong><?= $unit_query['make']; ?></strong></span>
									</div>
								</div>
								
								<div style="clear:both"></div>
								<div class="12u(xsmall)" style="color:#0000FF;" id="model-displaymess">
									<span class="modelmessage" id="modelmessage"></span>
								</div>
								<div class="12u(xsmall)" style="float:left;width:50%;" id="model-display">
									<div class="12u(xsmall)" style="float:left; width:180px;">Year Model:</div>
									<div class="6u(xsmall)" style="float:left;">
										<span class="model"><strong><?= $unit_query['model']; ?></strong></span>
									</div>
								</div>
								
								<div style="clear:both"></div>
								<div class="12u(xsmall)" style="color:#0000FF;" id="chassis-displaymess">
									<span class="chassismessage" id="chassismessage"></span>
								</div>
								<div class="12u(xsmall)" style="float:left;width:50%;" id="chassis-display">
									<div class="12u(xsmall)" style="float:left; width:180px;">Chassis No.:</div>
									<div class="6u(xsmall)" style="float:left;">
										<span class="chassis"><strong><?= $unit_query['chassis']; ?></strong></span>
									</div>
								</div>
								<div style="clear:both"></div>
								<div class="12u(xsmall)" style="color:#0000FF;" id="engine-displaymess">
									<span class="enginemessage" id="enginemessage"></span>
								</div>
								<div class="12u(xsmall)" style="float:left;width:50%;" id="engine-display">
									<div class="12u(xsmall)" style="float:left; width:180px;">Engine No.:</div>
									<div class="6u(xsmall)" style="float:left;">
										<span class="engine"><strong><?= $unit_query['engine']; ?></strong></span>
									</div>
								</div>
								
								<div style="clear:both"></div>
								<div class="12u(xsmall)" style="color:#0000FF;" id="capacity-displaymess">
									<span class="capacitymessage" id="capacitymessage"></span>
								</div>
								<div class="12u(xsmall)" style="float:left;width:50%;" id="capacity-display">
									<div class="12u(xsmall)" style="float:left; width:180px;">Capacity:</div>
									<div class="6u(xsmall)" style="float:left;">
										<span class="capacity"><strong><?= $unit_query['capacity']; ?></strong></span>
									</div>
								</div>
							</div>
                        </div><!-- /.col-lg-12 -->
                    </div><!-- /.row -->
					
					
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
		
		<script language="javascript" type="text/javascript">

			$(document).ready(function(){
				$("#platesubmit").click(function(){
					var plate = $("#plate").val();

						$.post("trigger/updateunitform",{ plate1: plate, changeplate: 1},
							function(data) {
								//document.getElementById("platemessage").textContent="Occupation has been updated";
								$('#platemessage').html(data);
								$('#platemessage').delay().fadeIn();
								$('#platemessage').delay(3000).fadeOut();
							}
						);
						$(".plate").text($("#plate").val());
		
				});
				
				$("#makesubmit").click(function(){
					var make = $("#make").val();

						$.post("trigger/updateunitform",{ make1: make, changemake: 1},
							function(data) {
								$('#makemessage').html(data);
								$('#makemessage').delay().fadeIn();
								$('#makemessage').delay(3000).fadeOut();
							}
						);
						$(".make").text($("#make").val());
		
				});
				
				$("#modelsubmit").click(function(){
					var model = $("#model").val();

						$.post("trigger/updateunitform",{ model1: model, changemodel: 1},
							function(data) {
								$('#modelmessage').html(data);
								$('#modelmessage').delay().fadeIn();
								$('#modelmessage').delay(3000).fadeOut();
							}
						);
						$(".model").text($("#model").val());
		
				});
				
				$("#chassissubmit").click(function(){
					var chassis = $("#chassis").val();

						$.post("trigger/updateunitform",{ chassis1: chassis, changechassis: 1},
							function(data) {
								$('#chassismessage').html(data);
								$('#chassismessage').delay().fadeIn();
								$('#chassismessage').delay(3000).fadeOut();
							}
						);
						$(".chassis").text($("#chassis").val());
		
				});
				
				$("#enginesubmit").click(function(){
					var engine = $("#engine").val();

						$.post("trigger/updateunitform",{ engine1: engine, changeengine: 1},
							function(data) {
								$('#enginemessage').html(data);
								$('#enginemessage').delay().fadeIn();
								$('#enginemessage').delay(3000).fadeOut();
							}
						);
						$(".engine").text($("#engine").val());
		
				});
				
				$("#capacitysubmit").click(function(){
					var capacity = $("#capacity").val();

						$.post("trigger/updateunitform",{ capacity1: capacity, changecapacity: 1},
							function(data) {
								$('#capacitymessage').html(data);
								$('#capacitymessage').delay().fadeIn();
								$('#capacitymessage').delay(3000).fadeOut();
							}
						);
						$(".capacity").text($("#capacity").val());
		
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
		</script>

    </body>
</html>
