<?php 
	include '../settings/connect.php';
	if(session_id() == '') { page_protect(); } // START SESSIONS
	IF(!isset($_SESSION['ADM']) && isset($_SESSION['user_id'])){ 
		header ('location: login');
	}ELSEIF(isset($_SESSION['ADM']) && !isset($_SESSION['user_id'])){ 
		unset($_SESSION['ADM']);
		header ('location: ../index');
	}ELSEIF(!isset($_SESSION['ADM']) && !isset($_SESSION['user_id'])){ 
		header ('location: ../index');
	}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include ('includes/header.php'); ?>
    </head>
    <body>

        <div id="wrapper">

            <?php include ('includes/navigation.php'); ?>

            <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <h1 class="page-header">New Bank Confirmation</h1>
                        </div><!-- /.col-lg-12 -->
						
						<div class="col-lg-4" style="width:275">
							<div class="panel panel-primary">
								<div class="panel-heading">
									New Bank Has Been Added
								</div>
								<div class="panel-body">
								<?php
									IF (!isset($_SESSION['Bname'])):
										echo "Cannot add bank details - <span style='color:#FF0000'>Error<span>!<br/>Please Click <a href='add_bank'><i class='fa fa-bank'></i> Add Bank Name</a>";
									ELSE: ?>
										Bank Name :<span style="color:#FF0000"><?= $_SESSION['Bname']; ?></span><br/>
										Bank Name Abbreviation :<span style="color:#FF0000"><?= $_SESSION['BankAbbrs']; ?></span><br/>
										<br/>Please Click <a href='add_bank'><i class='fa fa-bank'></i> Add Another Bank Name</a>
									<?php
									ENDIF;
									?>
								</div>
								<div class="panel-footer">
									<?php
										IF (isset($_SESSION['Bname'])):
											unset($_SESSION['Bname']); unset($_SESSION['BankAbbrs']);
											echo '<a href="add_bank"><i class="fa fa-bank"></i> Add Another Bank Name</a>';
										ENDIF;
									?>
								</div>
							</div>
						</div>					
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div><!-- /#page-wrapper -->

        </div>
        <!-- /#wrapper -->

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
