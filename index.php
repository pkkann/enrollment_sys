<?php
	require_once "sys.php";
	require_once "nav.php";
	require_once "footer.php";
	require_once "main.php";
	require_once "login.php";
	require_once "alert.php";
	require_once "shift.php";

	require_once "pages/residents.php";
	require_once "pages/guests.php";
	require_once "pages/users.php";
	require_once "pages/profile.php";
	require_once "pages/shifts.php";
	session_start();
	
	//Init sys
	initEnvironment();
	initXajax();
	initDB();
	registerXajaxFunctions();
	$xajax->processRequest();
?>
<!DOCTYPE html>
<html lang="dk">
	<head>
		<!-- Meta data -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE-edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<!-- Site attributes -->
		<title>Hønens Indskrivnings System</title>
		
		<!-- jQuery -->
		<script type="text/javascript" src="libraries/js/jquery/jquery-1.11.3.js"></script>
		
		<!-- Bootstrap -->
		<link rel="stylesheet" href="css/bootstrap/bootstrap.css">
		<script type="text/javascript" src="libraries/js/bootstrap/bootstrap.js"></script>

		<!-- Animate -->
		<link rel="stylesheet" type="text/css" href="css/animate/animate.css">

		<!-- Bootstrap-notify -->
		<script type="text/javascript" src="libraries/js/bootstrap-notify/bootstrap-notify.js"></script>

		<!-- Sweet-alert -->
		<link rel="stylesheet" type="text/css" href="css/sweet-alert/sweet-alert.css">
		<script type="text/javascript" src="libraries/js/sweet-alert/sweet-alert.js"></script>

		<!-- Autotab -->
		<script type="text/javascript" src="libraries/js/autotab/jquery.autotab.js"></script>

		<!-- Select -->
		<script type="text/javascript" src="libraries/js/select/bootstrap-select.js"></script>
		<link rel="stylesheet" type="text/css" href="css/select/bootstrap-select.css">
		<script type="text/javascript" src="libraries/js/select/i18n/defaults-da_DK.js"></script>

		<!-- ChartJS -->
		<script type="text/javascript" src="libraries/js/chart/Chart.js"></script>

		<!-- Fontawesome -->
		<link rel="stylesheet" type="text/css" href="css/fontawesome/font-awesome.css">
		
		<!-- Custom styles -->
		<link rel="stylesheet" type="text/css" href="css/theme.css">
		<link rel="stylesheet" type="text/css" href="css/styles.css">	
		
		<!-- Xajax javascript call -->
		<?php $xajax->printJavascript(); ?>
	</head>
	<body>
		<div id="modal" class="modal fade"></div>
		<div id="site_wrapper">
			<?php
			if(isset($_SESSION['user'])) {
				reload_shift();
				echo gen_main();
			} else {
				echo gen_login();
			}
			?>
		</div>
		<script>
			function isNumberKey(evt){
			    var charCode = (evt.which) ? evt.which : event.keyCode
			    if (charCode > 31 && (charCode < 48 || charCode > 57))
			        return false;
			    return true;
			}
			function setupInputRestricts() {
				$('.numbersOnly').autotab('number');
			}
			function refresh_session() {
				var time = 300000;
				setTimeout(function() {
					$.ajax({
			           url: 'refresh_session.php',
			           cache: false,
			           complete: function () {refresh_session();}
			        });
				},time);
			}
			refresh_session();
		</script>
	</body>
</html>