<?php
	$user_ID = $_SESSION['act_ID'];
	IF(WM()):
		$count_new_report = mysqli_num_rows(mysqli_query($link, "SELECT ID FROM ".DB_PREFIX."reporting WHERE report_status=0 AND (reply=0 OR reply =2)"));
		$new_messages = mysqli_query($link, "SELECT A.lname, B.subject, B.report_ID, B.date_time FROM ".DB_PREFIX."users A, ".DB_PREFIX."reporting B WHERE A.user_ID=B.user_ID AND B.report_status=0 AND (B.reply=0 OR B.reply=2) ORDER BY B.ID DESC");
	ELSE:
		$get_RID = mysqli_query($link, "SELECT report_ID FROM ".DB_PREFIX."reporting WHERE user_ID='{$user_ID}' AND reply=0");
		$reply = 0;
		WHILE($reports = mysqli_fetch_array($get_RID)){
			
			$reportID = $reports['report_ID'];
			
			$count_new_report = mysqli_num_rows(mysqli_query($link, "SELECT ID FROM ".DB_PREFIX."reporting WHERE report_ID='{$reportID}' AND report_status=0 AND reply=1"));
			$new_messages = mysqli_query($link, "SELECT A.lname, B.report_ID, B.subject, B.date_time FROM ".DB_PREFIX."users A, ".DB_PREFIX."reporting B WHERE A.user_ID=B.user_ID AND B.report_status=0 AND B.reply=1 ORDER BY B.ID DESC");
			$reply += $count_new_report;
		}
	ENDIF;
?>	
			<!-- Navigation -->
            <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                <div class="navbar-header">
                    <a class="navbar-brand" href="https://carpoolphil.net"><i class="fa fa-home fa-fw"></i> <?= TITLES; ?></a>
                </div>

                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <ul class="nav navbar-right navbar-top-links">
				
					<li class="dropdown navbar-inverse">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#">
							<i class="fa fa-envelope fa-fw"></i><?php IF($reply!=0): ?><span class='badge badge-warning' id='lblCartCount'><strong><?=$reply;?></strong></span><?php ENDIF; ?> <b class="caret"></b>
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
								IF($reply!=0): 
									WHILE ($message = mysqli_fetch_array($new_messages)){ 
										$datetime = $message['date_time'];
										$reportID = $message['report_ID'];
										$report_subject = mysqli_fetch_assoc(mysqli_query($link, "SELECT subject FROM ".DB_PREFIX."reporting WHERE report_ID='{$reportID}' AND reply=0"));
							?>
								<a href="<?=(WM())? '../backend/' : '';?>read?reportID=<?=$reportID;?>&subject=<?=$report_subject['subject'];?>">
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
								<a class="text-center" href="<?=(WM())? "../backend/notifications" : "messaging";?>">
									<strong>See All Alerts</strong>
									<i class="fa fa-angle-right"></i>
								</a>
							</li>
						</ul>
					</li>
					
					
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-user fa-fw"></i> <?= (isset($_SESSION['full_name']))? $_SESSION['full_name'] : "Guest"; ?> <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="profile"><i class="fa fa-user fa-fw"></i> User Profile</a>
                            </li>
                            <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                            </li>
                            <li class="divider"></li>
                            <li><a href="logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <!-- /.navbar-top-links -->

                <div class="navbar-default sidebar" role="navigation">
                    <div class="sidebar-nav navbar-collapse">
                        <ul class="nav" id="side-menu">
                            <li class="sidebar-profile">
                               
							   
							   
							   
							 <header>
								<div style="margin:0 auto; padding:10px 40px 0px 40px" id="avatar">
							   <?php if(!isset($_SESSION['user_id'])){ ?>
									<span class="image avatar"><img src="images/avatar1.jpg" alt="" /></span>
									<h1 id="logo">Guest</h1>

								<?php }else{ $myname = mysqli_fetch_array(mysqli_query($link, "SELECT fname, lname, photo FROM ".DB_PREFIX."users WHERE ID = ".$_SESSION['user_id']."")); ?>
									<span class="image avatar">
										<?php IF($myname['photo'] == ""){ $avatar = "images/avatar2.jpg"; }ELSE{ $avatar = "members/".$_SESSION['act_ID']."/".$myname['photo']; } ?>
										<a href="upload" alt="Click to update photo"><img src="<?= $avatar; ?>" alt="" /></a>
									</span>
								<?php }?>
								</div>
							 </header>  
							   
							   
							   
							   
							   
							   
							   
							   
							   
                            </li>
							<?php IF(isset($_SESSION['user_id'])): ?>
                            <li>
                                <a href="account_details"><i class="fa fa-suitcase fa-fw"></i> Account</a>
                            </li>
							<?php IF(Registered()): ?>
							<li>
                                <a href="messaging"><i class="fa fa-envelope fa-fw"></i> Report/Messaging</a>
                            </li>
							<?php ENDIF; ENDIF; IF(WM()): ?>
                            <li>
                                <a href="../backend/"><i class="fa fa-cogs fa-fw"></i> Administrator</a>
                            </li>
							<?php ELSEIF(Dispatcher()): ?>
							<li>
								<a href="../dispatcher/" class="active"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
							</li>
							<?php ENDIF; ?>
							<!--
							
							<li>
                                <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Charts<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="flot.html">Flot Charts</a>
                                    </li>
                                    <li>
                                        <a href="morris.html">Morris.js Charts</a>
                                    </li>
                                </ul>
                               
                            </li>
                            <li>
                                <a href="tables.html"><i class="fa fa-table fa-fw"></i> Tables</a>
                            </li>
                            <li>
                                <a href="forms.html"><i class="fa fa-edit fa-fw"></i> Forms</a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-wrench fa-fw"></i> UI Elements<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="panels-wells.html">Panels and Wells</a>
                                    </li>
                                    <li>
                                        <a href="buttons.html">Buttons</a>
                                    </li>
                                    <li>
                                        <a href="notifications.html">Notifications</a>
                                    </li>
                                    <li>
                                        <a href="typography.html">Typography</a>
                                    </li>
                                    <li>
                                        <a href="icons.html"> Icons</a>
                                    </li>
                                    <li>
                                        <a href="grid.html">Grid</a>
                                    </li>
                                </ul>
                            
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-sitemap fa-fw"></i> Multi-Level Dropdown<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="#">Second Level Item</a>
                                    </li>
                                    <li>
                                        <a href="#">Second Level Item</a>
                                    </li>
                                    <li>
                                        <a href="#">Third Level <span class="fa arrow"></span></a>
                                        <ul class="nav nav-third-level">
                                            <li>
                                                <a href="#">Third Level Item</a>
                                            </li>
                                            <li>
                                                <a href="#">Third Level Item</a>
                                            </li>
                                            <li>
                                                <a href="#">Third Level Item</a>
                                            </li>
                                            <li>
                                                <a href="#">Third Level Item</a>
                                            </li>
                                        </ul>
                                  
                                    </li>
                                </ul>
                       
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-files-o fa-fw"></i> Sample Pages<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="blank.html">Blank Page</a>
                                    </li>
                                    <li>
                                        <a href="login.html">Login Page</a>
                                    </li>
                                </ul>
                          
                            </li>
							-->
                        </ul>
                    </div>
               
                </div>
              
            </nav>