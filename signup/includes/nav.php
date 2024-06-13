<!-- Navigation -->

            <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                <div class="navbar-default sidebar" role="navigation">
                    <div class="sidebar-nav navbar-collapse">
                        <ul class="nav" id="side-menu">
							<?php IF(isset($_SESSION['user_id'])): ?>
                            <li>
                                <a href="../myaccount/"><i class="fa fa-dashboard fa-fw"></i> My Dashboard</a>
                            </li>
							<?php ENDIF; ?>
							<li>
                                <a href="../myaccount/login"><i class="fa fa-sign-in fa-fw"></i> Login</a>
                            </li>
                        </ul>
                    </div>
                    <!-- /.sidebar-collapse -->
                </div>
                <!-- /.navbar-static-side -->
            </nav>
			<!-- Navigation -->
			
			<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<!-- Top Navigation: Left Menu -->
				<ul class="nav navbar-nav navbar-left navbar-top-links">
					<li><a href="../"><i class="fa fa-home fa-fw"></i> Home Index</a></li>
				</ul>
			</nav>