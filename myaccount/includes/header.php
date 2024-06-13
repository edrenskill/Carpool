		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="Marlon E. Avillion, Edren Cobegro">

		<title>Account Details - <?php echo TITLES; ?></title>

		<!-- Bootstrap Core CSS -->
		<link href="../dispatcher/css/bootstrap.min.css" rel="stylesheet">

		<!-- MetisMenu CSS -->
		<link href="../dispatcher/css/metisMenu.min.css" rel="stylesheet">

		<!-- Timeline CSS -->
		<link href="../dispatcher/css/timeline.css" rel="stylesheet">

		<!-- Custom CSS -->
		<link href="../dispatcher/css/startmin.css" rel="stylesheet">

		<!-- Morris Charts CSS -->
		<link href="../dispatcher/css/morris.css" rel="stylesheet">

		<!-- Custom Fonts -->
		<link href="../dispatcher/css/font-awesome.min.css" rel="stylesheet" type="text/css">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
		
		<link rel="apple-touch-icon" sizes="57x57" href="images/favicon/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="images/favicon/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="images/favicon/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="images/favicon/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="images/favicon/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="images/favicon/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="images/favicon/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="images/favicon/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192"  href="images/favicon/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="images/favicon/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">
		<link rel="manifest" href="images/favicon/manifest.json">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="images/favicon/ms-icon-144x144.png">
		<meta name="theme-color" content="#ffffff">

		
		<style>
		
			#InboxCount {
				font-size: 12px;
				background: #ff0000;
				color: #fff;
				padding: 0 5px;
				vertical-align: middle;
				margin-left: 10px;
			}

			#lblCartCount {
				font-size: 12px;
				background: #ff0000;
				color: #fff;
				padding: 0 5px;
				vertical-align: top;
				margin-left: -10px;
			}
			.badge {
				padding-left: 9px;
				padding-right: 9px;
				-webkit-border-radius: 9px;
				-moz-border-radius: 9px;
				border-radius: 9px;
			}

			.label-warning[href],
			.badge-warning[href] {
				background-color: #c67605;
			}

			
			
		.image {
		border-radius: 5px;
		border: 0;
		display: inline-block;
		position: relative;
	}

		.image img {
			border-radius: 5px;
			display: block;
		}

		.image.left {
			float: left;
			margin: 0 2.5em 2em 0;
			top: 0.25em;
		}

		.image.right {
			float: right;
			margin: 0 0 2em 2.5em;
			top: 0.25em;
		}

		.image.fit {
			display: block;
			margin: 0 0 2.25em 0;
			width: 100%;
		}

			.image.fit img {
				display: block;
				width: 100%;
			}
		
		.image.avatar {
			border-radius: 100%;
			overflow: hidden;
		}

			.image.avatar img {
				border-radius: 100%;
				display: block;
				width: 100%;
			}
		
			#header {
				width: 17em;
				-moz-transform: translateX(17em);
				-webkit-transform: translateX(17em);
				-ms-transform: translateX(17em);
				transform: translateX(17em);
				right: 0;
			}

				#header > header {
					padding: 2em;
				}

					#header > header .avatar {
						margin: 0 auto 1.6875em auto;
						width: 6em;
					}

					#header > header h1 {
						font-size: 1.5em;
					}

					#header > header p {
						margin: 1em 0 0 0;
					}

				#header > footer {
					padding: 1.5em;
				}
		</style>