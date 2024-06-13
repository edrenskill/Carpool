<?php
	IF($_SESSION['terminal_ID'] != "" || $_SESSION['terminal_ID'] != 0):
		$terminalID = $_SESSION['terminal_ID'];
		$terminal_query = mysqli_fetch_assoc(mysqli_query($link, "SELECT terminal_ID, route_origin, route_destination, terminal_name, operational FROM ".DB_PREFIX."terminal WHERE terminal_ID ='".$terminalID."'"));
		$userid = mysqli_query($link, "SELECT fname, lname, user_ID FROM ".DB_PREFIX."users WHERE terminal_ID = '".$terminalID."'");
	ENDIF;
	
	?>


<!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="navbar-header">
            <a class="navbar-brand" href="../"><?php echo TITLES; ?></a>
        </div>

        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

        <!-- Top Navigation: Left Menu -->
        <!--<ul class="nav navbar-nav navbar-left navbar-top-links">
            <li><a href="../index"><i class="fa fa-home fa-fw"></i> Home Index</a></li>
        </ul>-->

        <!-- Top Navigation: Right Menu -->
        <ul class="nav navbar-right navbar-top-links">
            <!--
			
			<li class="dropdown navbar-inverse">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-bell fa-fw"></i> <b class="caret"></b>
                </a>
                <ul class="dropdown-menu dropdown-alerts">
                    <li>
                        <a href="#">
                            <div>
                                <i class="fa fa-comment fa-fw"></i> New Messsage
                                <span class="pull-right text-muted small">4 minutes ago</span>
                            </div>
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a class="text-center" href="#">
                            <strong>See All Alerts</strong>
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </li>
                </ul>
            </li>
			
			-->

            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">

                    <i class="fa fa-user fa-fw"></i> <?php echo $_SESSION['full_name']; ?> <b class="caret"></b>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li><a href="../myaccount/profile"><i class="fa fa-user fa-fw"></i> User Profile</a>
                    </li>
                    <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                    </li>
                    <li class="divider"></li>
                    <li><a href="logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                    </li>
                </ul>
            </li>
        </ul>

        <!-- Sidebar -->
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">

                <ul class="nav" id="side-menu">
                  <!--  <li class="sidebar-search">
                        <div class="input-group custom-search-form">
                            <input type="text" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                        </div>
                    </li> -->
                    <li>
                        <a href="dashboard" class="active"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-bus fa-fw"></i> Vehicle Transactions<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                               <a href="boarding"> <i class="fa fa-users fa-fw"></i>Passenger Boarding</a>
                            </li>
                            <li>
                                <a href="unit_login"><i class="fa fa-plus fa-fw"></i>Unit Login</a>
                             </li>
                        </ul>
                    </li>
					<li>
                        <a href="#"><i class="fa fa-credit-card fa-fw"></i> CE Card Management<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
							<li>
                               <a href="cards"> <i class="fa fa-money fa-fw"></i> Sale Cards</a>
                            </li>
                            <li>
                               <a href="ce_card_inventory_all"> <i class="fa fa-credit-card-alt fa-fw"></i> All Cards</a>
                            </li>
							<li>
                               <a href="ce_card_inventory_on_hand"> <i class="fa fa-sign-in fa-fw"></i>Available Cards</a>
                            </li>
                            <li>
                                <a href="ce_card_inventory_sold"><i class="fa fa-sign-out fa-fw"></i>Disposed Cards</a>
                             </li>
                        </ul>
                    </li>
					
					<li>
                        <a href="#"><i class="fa fa-stack-exchange fa-fw"></i> Transactions<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
							<li>
                               <a href="card_sale"> <i class="fa fa-credit-card-alt fa-fw"></i> Cards</a>
                            </li>
                            <li>
                               <a href="reloading"> <i class="fa fa-money fa-fw"></i> Reloading</a>
                            </li>
                        </ul>
                    </li>
					   
					<li>
						<a href="#"><i class="fa fa-road fa-fw"></i> Terminal Details<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
							<li class="sidebar-search">
								<div class="list-group">
									<?php IF($_SESSION['terminal_ID'] != "" || $_SESSION['terminal_ID'] != 0): ?>
								
									<i class="fa fa-tag fa-fw"></i> <strong>Terminal ID:</strong><br/>
									<span class="text-muted small"><em><?= $terminal_query['terminal_ID']; ?></em></span>
									<hr/>
									<i class="fa fa-bookmark-o fa-fw"></i> <strong>Terminal Name:</strong><br/>
									<span class=" text-muted small"><em><?= $terminal_query['terminal_name']; ?></em></span>
									<hr/>
									<i class="fa fa-users fa-fw"></i> <strong>Dispatchers:</strong><span class="pull-right"><i class="fa fa-credit-card fa-fw"></i> <strong>ID</strong></strong></span><br/>
									<?php WHILE($dispatchers = mysqli_fetch_array($userid)){ $fullname = $dispatchers['fname']." ".$dispatchers['lname']; $DID = $dispatchers['user_ID']; ?>
									<span class="pull-left text-muted small"><em><?=$fullname;?>       :</em></span>
									<span class="pull-right text-muted small"><em><?=$DID;?></em></span><br/>
									<?php } 
									
									ELSE: ?>
										<div class="alert alert-danger">No assigned terminal</div>
									<?php 
									
									ENDIF;
									
									?>
								</div>
							</li>
						</ul>
					</li>
                </ul>
            </div>
        </div>
    </nav>