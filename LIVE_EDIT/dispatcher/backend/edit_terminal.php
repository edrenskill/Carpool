<?php
include '../settings/connect.php';
if (session_id() == '') {
    page_protect();
} // START SESSIONS

$terminal_ID = $_SESSION ['terminal'];
$member_ID = $_SESSION['memberID'];
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

                            <h1 class="page-header">Manage Terminal</h1>

                        </div><!-- /.col-lg-12 -->
                    </div><!-- /.row -->

                    <div class="panel panel-default">
                        <div class="panel-heading">

                            <?php
                            IF (!isset($terminal_ID)):
                                echo "Select Terminal ID";
                            ELSE:
								$personal_info = mysqli_fetch_assoc(mysqli_query($link, "SELECT email, fname, mname, lname, suffix, user_ID  FROM " . DB_PREFIX . "users WHERE user_ID = '" . $member_ID . "'"));                               
								$terminal_query = mysqli_query($link, "SELECT terminal_ID, route_origin,member_dailydues, regular_service_fee,initial_service_fee, incentive_percentage, route_destination, terminal_name, operational FROM " . DB_PREFIX . "terminal WHERE terminal_ID = '" . $terminal_ID . "'");
                                ?>

                                <?php
                                WHILE ($terminal = mysqli_fetch_array($terminal_query)) {
                                    ?>
                                    <span class="pull-left"><h4>Manage Unit</h4></span>
                                    <span class="pull-right"><h4>Terminal ID: <?= $terminal['terminal_ID']; ?></h4></span>
                                    <div style="clear:both;"></div>

                                    <div class="container" id="genpro" style="display:block;">								
                                        <div style="clear:both"></div>		
                                        <div class="12u(xsmall)" style="color:#0000FF;" id="tname-displaymess">
                                            <span class="tnamemessage" id="tnamemessage"></span>
                                        </div>
                                        <div class="12u(xsmall) pull-left" style="width:50%;" id="tname-display">
                                            <div class="12u(xsmall) pull-left" style=" width:180px;">Terminal Name:</div>
                                            <div class="6u(xsmall) pull-left">
                                                <span class="tname"><strong><?= $terminal['terminal_name']; ?></strong></span>
                                            </div>
                                            <div class="pull-right">
                                                <a href="javascript: null(void)" data-toggle="modal" data-target="#tname-edit"><i class="fa fa-edit"></i> edit</a>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="tname-edit" tabindex="-1" role="dialog" aria-labelledby="Editttnamemod" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" id="Editttnamemod">Edit Terminal Name</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group has-success">
                                                            <input class="form-control nttname" type="text" tname="tname" id="tname" value="<?= $terminal['terminal_name']; ?>" PLACEHOLDER="Terminal tname"/>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary" id="tnamesubmit" data-dismiss="modal">Save changes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
										
										<div style="clear:both"></div>		
                                        <div class="12u(xsmall)" style="color:#0000FF;" id="did-displaymess">
                                            <span class="didmessage"></span>
                                        </div>
                                        <div class="12u(xsmall) pull-left" style="width:50%;" id="did-display">
                                            <div class="12u(xsmall) pull-left" style=" width:180px;">Dispatcher ID:</div>
                                            <div class="6u(xsmall) pull-left" id="did-result">
                                                <span class="did"><strong><?= $personal_info['user_ID']; ?></strong></span>
                                            </div>
                                            <div class="pull-right" style="margin: 0 -115px;">
                                                <a href="javascript: null(void)" data-toggle="modal" data-target="#did-edit"><i class="fa fa-edit"></i> Change Dispatcher ID</a>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="did-edit" tabindex="-1" role="dialog" aria-labelledby="Editdidmod" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" id="Editdidmod">Edit Dispatcher</h4>
                                                    </div>
                                                    <div class="modal-body">
													
													</div>
													<div class="modal-body">
                                                        <div class="form-group has-success" id="dispatcher-results"></div>
														<div class="form-group has-success" id="dispatcher-field">
                                                            <input class="form-control ndid" type="text" tname="did" id="did" value="<?= $personal_info['user_ID']; ?>" PLACEHOLDER="Dispatcher ID" onkeypress="return isNumberKey(event)"/>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary" id="didsubmit" data-dismiss="modal">Save changes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
										
									   <div style="clear:both"></div>		
                                        <div class="12u(xsmall) pull-left" style="width:50%;" id="dname-display">
                                            <div class="12u(xsmall) pull-left" style=" width:180px;">Dispatcher Name:</div>
                                            <div class="6u(xsmall) pull-left">
												<strong>
													<span class="fname"><?= $personal_info['fname']; ?></span>
														<span class="lname"><?= $personal_info['lname']; ?></span>
														<?php
														IF ($personal_info['suffix'] != ""): echo ", " . $personal_info['suffix'];
														ENDIF;
														?>
												</strong>
											 </div>
										</div>


                                        <div style="clear:both"></div>
                                        <div class="12u(xsmall)" style="color:#0000FF;" id="origin-displaymess">
                                            <span class="originmessage" id="originmessage"></span>
                                        </div>
                                        <div class="12u(xsmall) pull-left" style="width:50%;" id="origin-display">
                                            <div class="12u(xsmall) pull-left" style=" width:180px;">Route Origin:</div>
                                            <div class="6u(xsmall) pull-left">
                                                <span class="origin"><strong><?= $terminal['route_origin']; ?></strong></span>
                                            </div>
                                            <div class="pull-right">
                                                <a href="javascript: null(void)" data-toggle="modal" data-target="#origin-edit"><i class="fa fa-edit"></i> edit</a>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="origin-edit" tabindex="-1" role="dialog" aria-labelledby="Editoriginmod" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" id="Editoriginmod">Edit Route Origin</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group has-success">
                                                            <input class="form-control norigin" type="text" name="origin" id="origin" value="<?= $terminal['route_origin']; ?>" PLACEHOLDER="Route Origin" />
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary" id="originsubmit" data-dismiss="modal">Save changes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div style="clear:both"></div>
                                        <div class="12u(xsmall)" style="color:#0000FF;" id="destination-displaymess">
                                            <span class="destinationmessage" id="destinationmessage"></span>
                                        </div>
                                        <div class="12u(xsmall) pull-left" style="width:50%;" id="destination-display">
                                            <div class="12u(xsmall) pull-left" style=" width:180px;">Route Destination:</div>
                                            <div class="6u(xsmall) pull-left">
                                                <span class="destination"><strong><?= $terminal['route_destination']; ?></strong></span>
                                            </div>
                                            <div class="pull-right">
                                                <a href="javascript: null(void)" data-toggle="modal" data-target="#destination-edit"><i class="fa fa-edit"></i> edit</a>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="destination-edit" tabindex="-1" role="dialog" aria-labelledby="Editdestinationmod" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" id="Editdestinationmod">Edit Route Destination</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group has-success">
                                                            <input class="form-control ndestination" type="text" name="destination" id="destination" value="<?= $terminal['route_destination']; ?>" PLACEHOLDER="Route Destination"  />
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary" id="destinationsubmit" data-dismiss="modal">Save changes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div style="clear:both"></div>
                                        <div class="12u(xsmall)" style="color:#0000FF;" id="fare-displaymess">
                                            <span class="faremessage" id="faremessage"></span>
                                        </div>
                                        <div class="12u(xsmall) pull-left" style="width:50%;" id="fare-display">
                                            <div class="12u(xsmall) pull-left" style=" width:180px;">Fare Rate:</div>
                                            <div class="6u(xsmall) pull-left">
                                                <span class="fare"><strong><?= $terminal['member_dailydues']; ?></strong></span>
                                            </div>
                                            <div class="pull-right">
                                                <a href="javascript: null(void)" data-toggle="modal" data-target="#fare-edit"><i class="fa fa-edit"></i> edit</a>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="fare-edit" tabindex="-1" role="dialog" aria-labelledby="Editfaremod" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" id="Editfaremod">Edit Fare Rate</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group has-success">
                                                            <input class="form-control nfare" type="text" name="fare" id="fare" value="<?= $terminal['member_dailydues']; ?>" PLACEHOLDER="Fare Rate" onkeypress="return isNumberKey(event)" />
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary" id="faresubmit" data-dismiss="modal">Save changes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div style="clear:both"></div>
                                        <div class="12u(xsmall)" style="color:#0000FF;" id="charge-displaymess">
                                            <span class="chargemessage" id="chargemessage"></span>
                                        </div>
                                        <div class="12u(xsmall) pull-left" style="width:50%;" id="charge-display">
                                            <div class="12u(xsmall) pull-left" style=" width:180px;">Regular Terminal Charge:</div>
                                            <div class="6u(xsmall) pull-left">
                                                <span class="charge"><strong><?= $terminal['regular_service_fee']; ?></strong></span>
                                            </div>
                                            <div class="pull-right">
                                                <a href="javascript: null(void)" data-toggle="modal" data-target="#charge-edit"><i class="fa fa-edit"></i> edit</a>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="charge-edit" tabindex="-1" role="dialog" aria-labelledby="Editchargemod" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" id="Editchargemod">Edit Terminal Charge</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group has-success">
                                                            <input class="form-control nfare" type="text" name="charge" id="charge" value="<?= $terminal['regular_service_fee']; ?>" PLACEHOLDER=" Terminal Charge" onkeypress="return isNumberKey(event)" />
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary" id="chargesubmit" data-dismiss="modal">Save changes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div style="clear:both"></div>
                                        <div class="12u(xsmall)" style="color:#0000FF;" id="initial-displaymess">
                                            <span class="initialmessage" id="initialmessage"></span>
                                        </div>
                                        <div class="12u(xsmall) pull-left" style="width:50%;" id="initial-display">
                                            <div class="12u(xsmall) pull-left" style=" width:180px;">Initial Terminal Charge:</div>
                                            <div class="6u(xsmall) pull-left">
                                                <span class="initial"><strong><?= $terminal['initial_service_fee']; ?></strong></span>
                                            </div>
                                            <div class="pull-right">
                                                <a href="javascript: null(void)" data-toggle="modal" data-target="#initial-edit"><i class="fa fa-edit"></i> edit</a>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="initial-edit" tabindex="-1" role="dialog" aria-labelledby="Editinitialmod" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" id="Editinitialmod">Edit Initial Charge</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group has-success">
                                                            <input class="form-control nfare" type="text" name="initial" id="initial" value="<?= $terminal['initial_service_fee']; ?>" PLACEHOLDER=" Initial Charge" onkeypress="return isNumberKey(event)" />
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary" id="initialsubmit" data-dismiss="modal">Save changes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div style="clear:both"></div>
                                        <div class="12u(xsmall)" style="color:#0000FF;" id="incentive-displaymess">
                                            <span class="incentivemessage" id="incentivemessage"></span>
                                        </div>
                                        <div class="12u(xsmall) pull-left" style="width:50%;" id="incentive-display">
                                            <div class="12u(xsmall) pull-left" style=" width:180px;">Incentive Percentage:</div>
                                            <div class="6u(xsmall) pull-left">
                                                <span class="incentive"><strong><?= $terminal['incentive_percentage']; ?></strong></span>
                                            </div>
                                            <div class="pull-right">
                                                <a href="javascript: null(void)" data-toggle="modal" data-target="#incentive-edit"><i class="fa fa-edit"></i> edit</a>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="incentive-edit" tabindex="-1" role="dialog" aria-labelledby="Editincentivemod" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" id="Editincentivemod">Edit Incentive Percentage</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group has-success">
                                                            <input class="form-control nfare" type="text" name="incentive" id="incentive" value="<?= $terminal['incentive_percentage']; ?>" PLACEHOLDER="Incentive Percentage" />
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary" id="incentivesubmit" data-dismiss="modal">Save changes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div style="clear:both"></div>
                                        <div class="12u(xsmall)" style="color:#0000FF;" id="operational-displaymess">
                                            <span class="operationalmessage" id="operationalmessage"></span>
                                        </div>
                                        <div class="12u(xsmall) pull-left" style="width:50%;" id="operational-display">
                                            <div class="12u(xsmall) pull-left" style=" width:180px;">status:</div>
                                            <div class="6u(xsmall) pull-left">
                                                <span class="operationaldata" ><strong><?= ($terminal['operational'] ? 'Operational' : 'Pending'); ?></strong></span>
                                            </div>
                                            <div class="pull-right">
                                                <a href="javascript: null(void)" data-toggle="modal" data-target="#operational-edit"><i class="fa fa-edit"></i> edit</a>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="operational-edit" tabindex="-1" role="dialog" aria-labelledby="Editoperationalmod" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" id="Editoperationalmod">Edit operation status</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group has-success">

                                                            <select class="form-control" id="operational" name="operational">
                                                                <option value="1" <?php
                                                                if ($terminal['operational'] == 1): echo "Selected";
                                                                ENDIF;
                                                                ?>>Operational</option>
                                                                <option value="0" <?php
                                                                if ($terminal['operational'] == 0): echo "Selected";
                                                                ENDIF;
                                                                ?>>Pending</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary" id="operationalsubmit" data-dismiss="modal">Save changes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            ENDIF;
                            ?>
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
			
			$(document).ready(function() {
				$("#tnamesubmit").click(function() {
					var tname = $("#tname").val();

					$.post("trigger/updateterminalform", {
							tname1: tname,
							changetname: 1
						},
						function(data) {
							//document.getElementById("tnamemessage").textContent="Terminal tname has been updated";
							$('#tnamemessage').html(data);
							$('#tnamemessage').delay().fadeIn();
							$('#tnamemessage').delay(3000).fadeOut();
						}
					);
					$(".tname").text($("#tname").val());

				});
				$("#didsubmit").click(function() {
					var did = $("#did").val();

					$.post("trigger/updateterminalform", {
							did1: did,
							changedid: 1
						},
						function(data) {
							//document.getElementById("tnamemessage").textContent="Terminal tname has been updated";
							$('#didmessage').html(data);
							$('#didmessage').delay().fadeIn();
							$('#didmessage').delay(3000).fadeOut();
						}
					);
					$(".did").text($("#did").val());

				});

				$("#originsubmit").click(function() {
					var origin = $("#origin").val();

					$.post("trigger/updateterminalform", {
							origin1: origin,
							changeorigin: 1
						},
						function(data) {
							$('#originmessage').html(data);
							$('#originmessage').delay().fadeIn();
							$('#originmessage').delay(3000).fadeOut();
						}
					);
					$(".origin").text($("#origin").val());

				});

				$("#destinationsubmit").click(function() {
					var destination = $("#destination").val();

					$.post("trigger/updateterminalform", {
							destination1: destination,
							changedestination: 1
						},
						function(data) {
							$('#destinationmessage').html(data);
							$('#destinationmessage').delay().fadeIn();
							$('#destinationmessage').delay(3000).fadeOut();
						}
					);
					$(".destination").text($("#destination").val());

				});

				$("#faresubmit").click(function() {
					var fare = $("#fare").val();

					$.post("trigger/updateterminalform", {
							fare1: fare,
							changefare: 1
						},
						function(data) {
							$('#faremessage').html(data);
							$('#faremessage').delay().fadeIn();
							$('#faremessage').delay(3000).fadeOut();
						}
					);
					$(".fare").text($("#fare").val());

				});
				
				$("#chargesubmit").click(function() {
					var charge = $("#charge").val();

					$.post("trigger/updateterminalform", {
							charge1: charge,
							changecharge: 1
						},
						function(data) {
							$('#chargemessage').html(data);
							$('#chargemessage').delay().fadeIn();
							$('#chargemessage').delay(3000).fadeOut();
						}
					);
					$(".charge").text($("#charge").val());

				});
				
				$("#initialsubmit").click(function() {
					var initial = $("#initial").val();

					$.post("trigger/updateterminalform", { 
							initial1: initial,
							changeinitial: 1
						},
						function(data) {
							$('#initialmessage').html(data);
							$('#initialmessage').delay().fadeIn();
							$('#initialmessage').delay(3000).fadeOut();
						}
					);
					$(".initial").text($("#initial").val());

				});

				$("#incentivesubmit").click(function() {
					var incentive = $("#incentive").val();

					$.post("trigger/updateterminalform", {
							incentive1: incentive,
							changeincentive: 1
						},
						function(data) {
							$('#incentivemessage').html(data);
							$('#incentivemessage').delay().fadeIn();
							$('#incentivemessage').delay(3000).fadeOut();
						}
					);
					$(".incentive").text($("#incentive").val());

				});

				$("#operationalsubmit").click(function() {
					var operational = $("#operational").val();

					$.post("trigger/updateterminalform", {
							operational1: operational,
							changeoperational: 1
						},
						function(data) {
							$('#operationalmessage').html(data);
							$('#operationalmessage').delay().fadeIn();
							$('#operationalmessage').delay(3000).fadeOut();
							$(".operationaldata").html(data);
						}
					);


				});

				$("#did").blur(function(e) {
					//removes spaces from id
					$(this).val($(this).val().replace(/\s/g, ''));
					var did = $(this).val();

					if (did.length < 4) {
						$("#dispatcher-results").html('At least 10 characters');
						return;
					} else if (did.length >= 4) {
						$("#dispatcher-results").html('<img src="../profile/images/loading.gif" />');
						$.post('trigger/exec', {
							'did': did
						}, function(data) {
							$("#dispatcher-results").html(data);
						});

					}
				});
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