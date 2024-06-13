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
						
							<span><h2><a href='gen_id_bridge?trip_history=<?= $vehicle_ID; ?>'>View Trip History <i class="fa fa-history"></i></a></h2></span>

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

								<div style="clear:both"></div>		
								<div class="12u(xsmall)" style="color:#0000FF;" id="dname-displaymess">
									<span class="dnamemessage" id="dnamemessage"></span>
								</div>
								<div class="12u(xsmall)" style="float:left;width:50%;" id="dname-display">
									<div class="12u(xsmall)" style="float:left; width:180px;">Driver's Name</div>
									<div class="6u(xsmall)" style="float:left;">
											<span class="d1fname"><strong><?= $driver1_ID ?></strong></span>
									</div>
									<div style="float:right;">
										<a href="javascript: null(void)" data-toggle="modal" data-target="#dname-edit"><i class="fa fa-edit"></i> edit</a>
									</div>
								</div>
								<div class="modal fade" id="dname-edit" tabindex="-1" role="dialog" aria-labelledby="Editdnamemod" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title" id="Editdnamemod">Edit Driver's Name</h4>
											</div>
											<div class="modal-body">
												<div class="form-group has-success">
													<input class="form-control ndname" type="text" name="dname" id="dname" value="<?= $driver1_ID ?>" PLACEHOLDER="Driver ID1" onkeypress="return isNumberKey(event)"/>
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
												<button type="button" class="btn btn-primary" id="dnamesubmit" data-dismiss="modal">Save changes</button>
											</div>
										</div>
									</div>
								</div>
			
	
								<div style="clear:both"></div>		
								<div class="12u(xsmall)" style="color:#0000FF;" id="d2name-displaymess">
									<span class="d2namemessage" id="d2namemessage"></span>
								</div>
								<div class="12u(xsmall)" style="float:left;width:50%;" id="d2name-display">
									<div class="12u(xsmall)" style="float:left; width:180px;">2nd Driver ID</div>
									<div class="6u(xsmall)" style="float:left;">
											<span class="d2fname"><strong><?= $driver2_ID ?></strong></span>
									</div>
									<div style="float:right;">
										<a href="javascript: null(void)" data-toggle="modal" data-target="#d2name-edit"><i class="fa fa-edit"></i> edit</a>
									</div>
								</div>
								<div class="modal fade" id="d2name-edit" tabindex="-1" role="dialog" aria-labelledby="Editd2namemod" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title" id="Editd2namemod">Edit Driver ID</h4>
											</div>
											<div class="modal-body">
												<div class="form-group has-success">
													<input class="form-control nd2name" type="text" name="d2name" id="d2name" value="<?= $driver2_ID ?>" PLACEHOLDER="Driver's ID2" onkeypress="return isNumberKey(event)"/>
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
												<button type="button" class="btn btn-primary" id="d2namesubmit" data-dismiss="modal">Save changes</button>
											</div>
										</div>
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
									<div style="float:right;">
										<a href="javascript: null(void)" data-toggle="modal" data-target="#plate-edit"><i class="fa fa-edit"></i> edit</a>
									</div>
								</div>
								<div class="modal fade" id="plate-edit" tabindex="-1" role="dialog" aria-labelledby="Editplatemod" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title" id="Editplatemod">Edit Plate Number</h4>
											</div>
											<div class="modal-body">
												<div class="form-group has-success">
													<input class="form-control nplate" type="text" name="plate" id="plate" value="<?= $unit_query['plate_number']; ?>" PLACEHOLDER="Plate Number" maxlength="7"/>
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
												<button type="button" class="btn btn-primary" id="platesubmit" data-dismiss="modal">Save changes</button>
											</div>
										</div>
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
									<div style="float:right;">
										<a href="javascript: null(void)" data-toggle="modal" data-target="#make-edit"><i class="fa fa-edit"></i> edit</a>
									</div>
								</div>
								<div class="modal fade" id="make-edit" tabindex="-1" role="dialog" aria-labelledby="Editmakemod" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title" id="Editmakemod">Edit Make/Brand</h4>
											</div>
											<div class="modal-body">
												<div class="form-group has-success">
													<input class="form-control nmake" type="text" name="make" id="make" value="<?= $unit_query['make']; ?>" PLACEHOLDER="Make/Model" />
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
												<button type="button" class="btn btn-primary" id="makesubmit" data-dismiss="modal">Save changes</button>
											</div>
										</div>
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
									<div style="float:right;">
										<a href="javascript: null(void)" data-toggle="modal" data-target="#model-edit"><i class="fa fa-edit"></i> edit</a>
									</div>
								</div>
								<div class="modal fade" id="model-edit" tabindex="-1" role="dialog" aria-labelledby="Editmodelmod" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title" id="Editmodelmod">Edit Year Model</h4>
											</div>
											<div class="modal-body">
												<div class="form-group has-success">
													<input class="form-control nmodel" type="text" name="model" id="model" value="<?= $unit_query['model']; ?>" PLACEHOLDER="Year Model" maxlength="4" onkeypress="return isNumberKey(event)"/>
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
												<button type="button" class="btn btn-primary" id="modelsubmit" data-dismiss="modal">Save changes</button>
											</div>
										</div>
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
									<div style="float:right;">
										<a href="javascript: null(void)" data-toggle="modal" data-target="#chassis-edit"><i class="fa fa-edit"></i> edit</a>
									</div>
								</div>
								<div class="modal fade" id="chassis-edit" tabindex="-1" role="dialog" aria-labelledby="Editchassismod" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title" id="Editchassismod">Edit Chassis No.</h4>
											</div>
											<div class="modal-body">
												<div class="form-group has-success">
													<input class="form-control nchassis" type="text" name="chassis" id="chassis" value="<?= $unit_query['chassis']; ?>" PLACEHOLDER="Chassis No." />
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
												<button type="button" class="btn btn-primary" id="chassissubmit" data-dismiss="modal">Save changes</button>
											</div>
										</div>
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
									<div style="float:right;">
										<a href="javascript: null(void)" data-toggle="modal" data-target="#engine-edit"><i class="fa fa-edit"></i> edit</a>
									</div>
								</div>
								<div class="modal fade" id="engine-edit" tabindex="-1" role="dialog" aria-labelledby="Editenginemod" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title" id="Editenginemod">Edit Engine No.</h4>
											</div>
											<div class="modal-body">
												<div class="form-group has-success">
													<input class="form-control nengine" type="text" name="engine" id="engine" value="<?= $unit_query['engine']; ?>" PLACEHOLDER="Engine No." />
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
												<button type="button" class="btn btn-primary" id="enginesubmit" data-dismiss="modal">Save changes</button>
											</div>
										</div>
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
									<div style="float:right;">
										<a href="javascript: null(void)" data-toggle="modal" data-target="#capacity-edit"><i class="fa fa-edit"></i> edit</a>
									</div>
								</div>
								<div class="modal fade" id="capacity-edit" tabindex="-1" role="dialog" aria-labelledby="Editcapacitymod" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title" id="Editcapacitymod">Edit Capacity</h4>
											</div>
											<div class="modal-body">
												<div class="form-group has-success">
													<input class="form-control ncapacity" type="text" name="capacity" id="capacity" value="<?= $unit_query['capacity']; ?>" PLACEHOLDER="Capacity" maxlength="2" onkeypress="return isNumberKey(event)"/>
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
												<button type="button" class="btn btn-primary" id="capacitysubmit" data-dismiss="modal">Save changes</button>
											</div>
										</div>
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
				$("#dnamesubmit").click(function(){
					var dname = $("#dname").val();

						$.post("trigger/updateunitform",{ dname1: dname, changedname: 1},
							function(data) {
								$('#dnamemessage').html(data);
								$('#dnamemessage').delay().fadeIn();
								$('#dnamemessage').delay(3000).fadeOut();
							}
						);
						$(".dname").text($("#dname").val());
		
				});		
				
				$("#d2namesubmit").click(function(){
					var d2name = $("#d2name").val();

						$.post("trigger/updateunitform",{ d2name1: d2name, changed2name: 1},
							function(data) {
								document.getElementById("d2namemessage").textContent = "Driver ID has been updated";
								$('#d2namemessage').delay().fadeIn();
								$('#d2namemessage').delay(3000).fadeOut();
							}
						);
						$(".d2name").text($("#d2name").val());
		
				});
		

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
