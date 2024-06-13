<?php

	$count_new_report = mysqli_num_rows(mysqli_query($link, "SELECT ID FROM ".DB_PREFIX."reporting WHERE report_status=0 AND reply!=1"));
	$new_messages = mysqli_query($link, "SELECT A.lname, B.subject, B.report_ID, B.date_time FROM ".DB_PREFIX."users A, ".DB_PREFIX."reporting B WHERE A.user_ID=B.user_ID AND B.report_status=0 AND (B.reply=0 OR B.reply=2) ORDER BY B.ID DESC");
	
	$records = mysqli_num_rows(mysqli_query($link, "SELECT COUNT(A.ID) AS qty FROM ".DB_PREFIX."idcards A, ".DB_PREFIX."card_sale B WHERE A.disposed=1 AND B.card_number=A.card_number AND B.remitted='1' AND remit_confirmed ='0' GROUP BY B.r_code"));
	$cardsremit = $records;
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
       <!-- <ul class="nav navbar-nav navbar-left navbar-top-links">
            <li><a href="../index"><i class="fa fa-home fa-fw"></i> Home Index</a></li>
        </ul> -->

        <!-- Top Navigation: Right Menu -->
        <ul class="nav navbar-right navbar-top-links">
            <li class="dropdown navbar-inverse">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-envelope fa-fw"></i><?php IF($count_new_report!=0): ?><span class='badge badge-warning' id='lblCartCount'><strong><?=$count_new_report;?></strong></span><?php ENDIF; ?> <b class="caret"></b>
                </a>
                <ul class="dropdown-menu dropdown-alerts">
					<li>
                        <div>
                            <i class="fa fa-user fa-fw"></i><strong>From</strong>
                            <span class="pull-right"><strong>Time</strong></span>
                        </div>
                    </li>
					<li class="divider"></li>

                    <li>
					<?php
						IF($count_new_report!=0): 
							WHILE ($message = mysqli_fetch_array($new_messages)){ 
								$datetime = $message['date_time']; 
								$reportID = $message['report_ID'];
								$report_subject = mysqli_fetch_assoc(mysqli_query($link, "SELECT subject FROM ".DB_PREFIX."reporting WHERE report_ID='{$reportID}' AND reply=0"));
					?>
								<a href="read?reportID=<?=$reportID;?>&subject=<?=$report_subject['subject'];?>">
                            <div>
                                <i class="fa fa-user fa-fw"></i> <?=strtoupper($message['lname']);?>
                                <span class="pull-right text-muted small"><?=get_time_difference_php($datetime);?></span>
                            </div>
                        </a>
					<?php } ELSE: ?>
                            <div>
                                <i class="fa fa-warning fa-fw"></i> No New Message
                            </div>
					<?php ENDIF; ?>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a class="text-center" href="notifications">
                            <strong>See All Alerts</strong>
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">

                    <i class="fa fa-user fa-fw"></i> <?php echo $_SESSION['full_name']; ?> <b class="caret"></b>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li><a href="../myaccount/account_details"><i class="fa fa-user fa-fw"></i> User Profile</a>
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
                    <li class="sidebar-search">
                        <div class="input-group custom-search-form">
                            <input type="text" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                        </div>
                    </li>
                    <li>
                        <a href="dashboard" class="active"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                    </li>
					
					
                    <li>
                        <a href="#"><i class="fa fa-users fa-fw"></i> Members Administration<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="application">New Applicantion</a>
                            </li>
                            <li>
                                <a href="photo_upload">Photo Upload</span></a>
                            </li>
                            <li>
                                <a href="add_bank_account">Add Bank Account</span></a>
                            </li>							
                        </ul>
                    </li>
					
					 <li>
                        <a href="#"><i class="fa fa-credit-card fa-fw"></i> Card Administration<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
							<li>
                                <a href="id_card_registration">Register Card</span></a>
                            </li>
							<li>
                                <a href="id_card_inventory">Card Inventory</span></a>
                            </li>
                        </ul>
                    </li>		
					
					<li>
                        <a href="#"><i class="fa fa-money fa-fw"></i> Driver's Payout <span class="fa arrow"></span></a>
                        <ul class="nav nav-third-level">
                            <li>
                                <a href="payout">For Remittance</a>
                            </li>
                            <li>
                                <a href="remitted_payout">Remitted</a>
                            </li>
                        </ul>
                    </li>
					
					<li>
                        <a href="#"><i class="fa fa-inbox fa-fw"></i> Accounts Receivable <?php IF($cardsremit!=0): ?><span class='badge badge-warning' id='lblCartCount'><strong><?=$cardsremit;?></strong></span><?php ENDIF; ?> <span class="fa arrow"></span></a>
                        <ul class="nav nav-third-level">
                            <li>
                                <a href="card_sale_accounts_receivable">Card Sale  <?php IF($cardsremit!=0): ?><span class='badge badge-warning' id='lblCartCount'><strong><?=$cardsremit;?></strong></span><?php ENDIF; ?> </a>
                            </li>
                            <li>
                                <a href="reloading_accounts_receivable">Reloading</a>
                            </li>
                        </ul>
                    </li>
					
					<li>
                        <a href="add_bank"><i class="fa fa-bank fa-fw"></i> Add Banks</a>
                    </li>
            </div>
        </div>
    </nav>