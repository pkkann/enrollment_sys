<?php

function load_login() {
	$objResponse = new xajaxResponse();
	$text .= gen_login();
	$objResponse->assign("site_wrapper", "innerHTML", $text);
	return $objResponse;
}

function gen_login() {
	$text = '';
	$text .= '<div id="login_wrapper">';
		$text .= '<div id="login_internat" class="well login_internal animated fadeInDownBig">';
			$text .= '<h3>Login</h3>';
			$text .= '<form class="form-horizontal">';
				$text .= '<fieldset>';
					$text .= '<div class="form-group">';
						$text .= '<div class="col-lg-12">';
							$text .= '<input class="form-control" id="inputUser" placeholder="Username" type="text">';
						$text .= '</div>';
					$text .= '</div>';
					$text .= '<div class="form-group">';
						$text .= '<div class="col-lg-12">';
							$text .= '<input class="form-control" id="inputPass" placeholder="Password" type="password">';
						$text .= '</div>';
					$text .= '</div>';
					$text .= '<div class="form-group">';
						$text .= '<div class="col-lg-12">';
							$text .= '<button type="button" class="btn btn-primary" onclick="xajax_login(document.getElementById(\'inputUser\').value, document.getElementById(\'inputPass\').value)">Login</button>';
						$text .= '</div>';
					$text .= '</div>';
				$text .= '</fieldset>';
			$text .= '</form>';
		$text .= '</div>';
	$text .= '</div>';
	return $text;
}

function login($user, $pass) {
	$objResponse = new xajaxResponse();
	$success = false;
	//$user = mysql_real_escape_string($user);
	//$pass = mysql_real_escape_string($pass);

	global $dba;
	$sql = "SELECT * FROM sysuser WHERE user = '".$user."' AND pass = '".$pass."'";
	$stmt = $dba->query($sql);
	if($stmt->rowCount() > 0) {
		$success = true;
	}
	
	if($success) {
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$_SESSION['user']['id'] = $row['id'];
			$_SESSION['user']['name'] = $row['name'];
			$_SESSION['user']['username'] = $row['user'];
			$_SESSION['user']['admin'] = $row['admin'];
		}
		
		$objResponse->call('xajax_load_main');
		$objResponse->call('xajax_do_reload_shift');
	} else {
		$objResponse->call('xajax_show_alert', 'danger', 'Ups!', 'Forkert brugernavn eller adgangskode');
	}
	
	return $objResponse;
}

function relogin() {
	$objResponse = new xajaxResponse();

	global $dba;
	$sql = "SELECT * FROM sysuser_select WHERE id = " . $_SESSION['user']['id'];
	$stmt = $dba->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$_SESSION['user']['id'] = $row['id'];
	$_SESSION['user']['name'] = $row['name'];
	$_SESSION['user']['username'] = $row['user'];
	$_SESSION['user']['admin'] = $row['admin'];
	
	$objResponse->call('xajax_load_main');
	$objResponse->call('xajax_do_reload_shift');

	return $objResponse;
}

function logout() {
	$objResponse = new xajaxResponse();

	session_destroy();
	session_start();

	$objResponse->call('xajax_load_login');
	return $objResponse;
}

?>