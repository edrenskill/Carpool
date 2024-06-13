<?php
	include '../settings/connect.php';
	session_start();
	IF(isset($_SESSION['user_id'])): header("location: ../myaccount/"); ENDIF;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
		<?php include_once("includes/header.php"); ?>
    </head>
    <body>
		
		<div id="wrapper">
		
		<?php include_once("includes/nav.php"); ?>
		
			<div id="page-wrapper">
				<div class="row">
					<div class="col-lg-12">
						<h1 class="page-header">Terms And Conditions</h1>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-8">
						<div class="panel panel-default">								
							<div class="panel-body">
								<p>PLEASE READ THE TERMS AND CONDITION CAREFULLY BEFORE REGISTRATION OR USING THIS WEBSITE. THE TERMS CONSTITUTE AN AGREEMENT BETWEEN CARPOOLPHIL.NET AND YOU, GOVERNING YOUR ACCESS TO AND USE OF THIS SITE. BY ACCESSING OR USING THE SITE, YOU AGREE TO BE BOUND BY THESE TERMS. WE MAY MODIFY THESE TERMS AT ANY TIME WITHOUT NOTICE TO YOU BY POSTING REVISED TERMS ON OUR SITES. CONTINUED USE OF OUR SITE CONSTITUTES YOUR BINDING ACCEPTANCE OF ANY MODIFICATIONS HERETO.
								THE SITE AND ITS CONTENT AND THE APPLICATIONS ARE PROVIDED ON AN "AS IS" AND "AS AVAILABLE" BASIS. CARPOOLPHIL.NET, ITS AFFILIATES, LICENSORS AND PARTNERS, TO THE FULLEST EXTENT PERMITTED BY LAW, DISCLAIM ANY AND ALL WARRANTIES, EXPRESS OR IMPLIED, STATUTORY OR OTHERWISE, INCLUDING BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF BUSINESSES, FITNESS FOR A PARTICULAR PURPOSE, NON-INFRINGEMENT OF THIRD PARTIES' RIGHTS AND YOUR ABILITY OR INABILITY TO USE THIS SITE, 
								ITS CONTENT AND THE APPLICATIONS.CARPOOLPHIL.NET AND ITS AFFILIATES, LICENSORS AND PARTNERS MAKE NO REPRESENTATIONS OR WARRANTIES ABOUT THE ACCURACY, COMPLETENESS, SECURITY OR TIMELINESS OF THE CONTENT, THE APPLICATIONS, RESULTS OBTAINED, INFORMATION OR SERVICES PROVIDED ON OR THROUGH THE USE OF THIS SITE. NO INFORMATION OBTAINED BY YOU FROM THE SITE SHALL CREATE ANY WARRANTY NOT EXPRESSLY STATED BY CARPOOLPHIL.NET IN THE TERMS. 
								CARPOOLPHIL.NET DOES NOT WARRANT OR REPRESENT THAT YOUR ACCESS TO OR USE OF THE  SITE WILL BE UNINTERRUPTED OR FREE OF ERRORS OR OMISSIONS,
								THAT DEFECTS WILL BE CORRECTED, OR THAT THE SITE IS FREE OF COMPUTER VIRUSES OR OTHER HARMFUL COMPONENTS. USE OF THE SITE, ITS CONTENT AND THE APPLICATIONS IS AT YOUR OWN RISK.<p>
								</br>
								<strong>ELIGIBILITY: </strong>
								<p>You must be over the age of legal majority to access. These Terms govern your access and use of all applications and services available, except to the extent such Services are subject to a separate agreement</p>
								</br>
								<strong>LIMITATION OF LIABILITY: </strong>
								</br>
								<p>TO THE MAXIMUM EXTENT PERMITTED BY LAW, ITS AFFILIATES, LICENSORS, OR PARTNERS BE LIABLE FOR ANY INCIDENTAL, INDIRECT, EXEMPLARY, PUNITIVE AND CONSEQUENTIAL DAMAGES OR DAMAGES RESULTING FROM LOST DATA INTERRUPTION RESULTING FROM THE USE OF OR INABILITY TO USE THE SITE, THE CONTENT, RESULTS OBTAINED, INFORMATION OR SERVICES AT ANY OTHER LEGAL THEORY. TO THE EXTENT PERMITTED BY LAW, THE REMEDIES STATED FOR YOU IN THESE TERMS OF USE ARE EXCLUSIVE AND ARE LIMITED TO THOSE EXPRESSLY PROVIDED FOR IN THESE TERMS OF USE.</p>
								</br>
								<strong>UNAUTHORIZED ACCESS </strong>
								<p><strong>Pursuant to SEC. 12 and SEC. 13 of RA 10173-Data Act of 2012</strong>, unauthorized access to the Site is a breach of these Terms and a violation of the law. You agree not to use any automated means, including, without limitation, agents, robots, scripts, or spiders, to access, monitor, or copy any part of our sites, copying of personal data, except those automated means that we have approved in advance and in writing.</p>
								</br>
								<strong>TERMINATION</strong>
								</br>
								<p>Carpoolphil.net may discontinue or suspend your Application at any time without notice, and Carpoolphil.net may block, terminate or suspend your Application and access to this Site at any time for any reason in its sole discretion. Upon such suspension or termination.</p>
							</div>
						</div>
					</div>
				</div>
				<?php include_once("includes/footer.php"); ?>
			</div>
		</div>

		<!-- jQuery -->
		<script src="../myaccount/js/jquery.min.js"></script>

		<!-- Bootstrap Core JavaScript -->
		<script src="../myaccount/js/bootstrap.min.js"></script>

		<!-- Metis Menu Plugin JavaScript -->
		<script src="../myaccount/js/metisMenu.min.js"></script>

		<!-- Custom Theme JavaScript -->
		<script src="../myaccount/js/startmin.js"></script>
    </body>
</html>
