<?php 

 $totalc = mysqli_num_rows(mysqli_query($link,"SELECT ID FROM `".DB_PREFIX."users` WHERE userlevel = 1"));
 $totalo = mysqli_num_rows(mysqli_query($link,"SELECT ID FROM `".DB_PREFIX."users` WHERE userlevel = 8"));
 $totalod = mysqli_num_rows(mysqli_query($link,"SELECT ID FROM `".DB_PREFIX."users` WHERE userlevel = 10"));
 $totald = mysqli_num_rows(mysqli_query($link,"SELECT ID FROM `".DB_PREFIX."users` WHERE userlevel = 7"));

 $query = "SELECT COUNT(*) as `terminal` FROM `".DB_PREFIX."terminal` WHERE operational = 1";
 $row = mysqli_fetch_array(mysqli_query($link,$query));
 $totalt = $row['terminal'];

 $query = "SELECT COUNT(*) as `vehicle` FROM `".DB_PREFIX."vehicles` WHERE unit_status != 2";
 $row = mysqli_fetch_array(mysqli_query($link,$query));
 $totalv = $row['vehicle'];
?> 
<!-- Page Content -->

        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header">
					<span class="pull-left">Dashboard</span>
					<span class="pull-right">
						<form action="member_search" method="post" enctype="multipart/form-data" id="UserForm">
							<div class="form-group input-group" style="width:300px">
								<span class="input-group-addon"><li class="fa fa-user"></li></span><input type="text" class="form-control" name="userID" id="userID" placeholder="User Search">
								<span class="input-group-btn"><input class="btn btn-default" type="submit" name="submitUID" id="submitUID" value="Search"></span>
							</div>
						</form>
					</span>
					<div style="clear:both"></div>
				</h3>
            </div><!-- /.col-lg-12 -->
		</div><!-- /.row -->
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-users fa-5x"></i>
                            </div>
							<div class="col-xs-9 text-right">
                                <span class="small">Commuters <strong><?= $totalc; ?></strong><br/>
								Operators <strong><?= $totalo; ?></strong><br/>
								Operators / Drivers <strong><?= $totalod; ?></strong><br/>
								Drivers <strong><?= $totald; ?></strong></span>
							</div>
						</div>
					</div>
					<a href="javascript: null(void)">
						<div class="panel-footer" id="view_details_member" style="display:<?= ($_SESSION['commutertable'] == 'block') ? 'none' : 'block'; ?>" onclick='
								$("#members").show("slow");
								$("#view_details_member").hide();
								$("#hide_details_member").show();

								$("#terminals").hide();
								$("#vehicles").hide();
								$("#loading").hide();

								$("#view_details_terminal").show();
								$("#hide_details_terminal").hide();
								$("#view_details_vehicle").show();
								$("#hide_details_vehicle").hide();
								$("#view_details_loading").show();
								$("#hide_details_loading").hide();
							'>
							<span class="pull-left">View Details</span>
							<span class="pull-right"><i class="fa fa-arrow-circle-down"></i></span>

							<div class="clearfix"></div>
						</div>

						<div class="panel-footer" style="display:<?= ($_SESSION['commutertable'] == 'block') ? 'block' : 'none'; ?>" id="hide_details_member" onclick='
								$("#members").hide();
								$("#view_details_member").show();
								$("#hide_details_member").hide();
							'>
							<span class="pull-left">Hide Details</span>
							<span class="pull-right"><i class="fa fa-arrow-circle-up"></i></span>

							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>

            <div class="col-lg-3 col-md-6">
                <div class="panel panel-green">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-tasks fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?= $totalt; ?></div>
                                <div>Terminals</div>
                            </div>
                        </div>
                    </div>
                    <a href="javascript: null(void)">
						<div class="panel-footer" id="view_details_terminal" style="display:<?= ($_SESSION['terminaltable'] == 'block') ? 'none' : 'block'; ?>" onclick='
								$("#terminals").show("slow");
								$("#view_details_terminal").hide();
								$("#hide_details_terminal").show();

								$("#members").hide();
								$("#vehicles").hide();
								$("#loading").hide();

								$("#view_details_member").show();
								$("#hide_details_member").hide();
								$("#view_details_vehicle").show();
								$("#hide_details_vehicle").hide();
								$("#view_details_loading").show();
								$("#hide_details_loading").hide();
							'>
							<span class="pull-left">View Details</span>
							<span class="pull-right"><i class="fa fa-arrow-circle-down"></i></span>

							<div class="clearfix"></div>
						</div>

						<div class="panel-footer" style="display:<?= ($_SESSION['terminaltable'] == 'block') ? 'block' : 'none'; ?>" id="hide_details_terminal" onclick='
								$("#terminals").hide();
								$("#view_details_terminal").show();
								$("#hide_details_terminal").hide();
							'>
							<span class="pull-left">Hide Details</span>
							<span class="pull-right"><i class="fa fa-arrow-circle-up"></i></span>

							<div class="clearfix"></div>
						</div>
                    </a>
                </div>
            </div>

			<div class="col-lg-3 col-md-6">
                <div class="panel panel-yellow">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-bus fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?= $totalv; ?></div>
                                <div>Vehicles</div>
                            </div>
                        </div>
                    </div>
                    <a href="javascript: null(void)">
						<div class="panel-footer" id="view_details_vehicle" style="display:<?= ($_SESSION['vehicletable'] == 'block') ? 'none' : 'block'; ?>" onclick='
								$("#vehicles").show("slow");
								$("#view_details_vehicle").hide();
								$("#hide_details_vehicle").show();

								$("#members").hide();
								$("#terminals").hide();
								$("#loading").hide();

								$("#view_details_member").show();
								$("#hide_details_member").hide();
								$("#view_details_terminal").show();
								$("#hide_details_terminal").hide();
								$("#view_details_loading").show();
								$("#hide_details_loading").hide();
							'>
							<span class="pull-left">View Vehicle List</span>
							<span class="pull-right"><i class="fa fa-arrow-circle-down"></i></span>

							<div class="clearfix"></div>
						</div>

						<div class="panel-footer" style="display:<?= ($_SESSION['vehicletable'] == 'block') ? 'block' : 'none'; ?>" id="hide_details_vehicle" onclick='
								$("#vehicles").hide();
								$("#view_details_vehicle").show();
								$("#hide_details_vehicle").hide();
							'>
							<span class="pull-left">Hide Vehicle List</span>
							<span class="pull-right"><i class="fa fa-arrow-circle-up"></i></span>

							<div class="clearfix"></div>
						</div>
                    </a>
                </div>
            </div>

			<div class="col-lg-3 col-md-6">
                <div class="panel panel-red">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-dashboard fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><i class="fa fa-fw" aria-hidden="true">&#xf158</i></div>
                                <div>Terminal Loading</div>
                            </div>
                        </div>
                    </div>
                    <a href="javascript: null(void)">
						<div class="panel-footer" id="view_details_loading" style="display:<?= ($_SESSION['loadingtable'] == 'block') ? 'none' : 'block'; ?>" onclick='
								$("#loading").show("slow");
								$("#view_details_loading").hide();
								$("#hide_details_loading").show();

								$("#members").hide();
								$("#terminals").hide();
								$("#vehicles").hide();

								$("#view_details_member").show();
								$("#hide_details_member").hide();
								$("#view_details_terminal").show();
								$("#hide_details_terminal").hide();
								$("#view_details_vehicle").show();
								$("#hide_details_vehicle").hide();
							'>
							<span class="pull-left">Open Reloading Panel</span>
							<span class="pull-right"><i class="fa fa-arrow-circle-down"></i></span>

							<div class="clearfix"></div>
						</div>

						<div class="panel-footer" style="display:<?= ($_SESSION['loadingtable'] == 'block') ? 'block' : 'none'; ?>" id="hide_details_loading" onclick='
								$("#loading").hide();
								$("#view_details_loading").show();
								$("#hide_details_loading").hide();
							'>
							<span class="pull-left">Close Reloading Panel</span>
							<span class="pull-right"><i class="fa fa-arrow-circle-up"></i></span>

							<div class="clearfix"></div>
						</div>
                    </a>
                </div>
            </div>
        </div><!-- /.row -->
