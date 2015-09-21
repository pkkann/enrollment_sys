<?php

function load_profile() {
	$objResponse = new xajaxResponse();
	$objResponse->call("xajax_show_profile");
	return $objResponse;
}

function show_profile() {
	$objResponse = new xajaxResponse();

	global $dba;
	$sql = "SELECT * FROM sysuser_select WHERE id = " . $_SESSION['user']['id'];
	$stmt = $dba->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	$admin = "";
	if($row['admin'] == "Ja") {
		$admin = "selected";
	}

	$text .= '<div class="modal-dialog">';
    	$text .= '<div class="modal-content">';
      		$text .= '<div class="modal-header">';
        		$text .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        		$text .= '<h4 class="modal-title"><i class="fa fa-user"></i> '.$_SESSION['user']['name'].'</h4>';
      		$text .= '</div>';
      		$text .= '<div class="modal-body">';
        		$text .= '<form class="form-horizontal">';
        			$text .= '<fieldset>';

        				$text .= '<div class="form-group">';
					    	$text .= '<label class="col-lg-2 control-label">Navn</label>';
					    	$text .= '<div class="col-lg-10">';
					        	$text .= '<input id="inputName" value="'.$row['name'].'" class="form-control" maxlength="45" type="text">';
					      	$text .= '</div>';
					    $text .= '</div>';

					    $text .= '<div class="form-group">';
					    	$text .= '<label class="col-lg-2 control-label">Brugernavn</label>';
					    	$text .= '<div class="col-lg-10">';
					        	$text .= '<input id="inputUsername" value="'.$row['username'].'" class="form-control" maxlength="45" type="text">';
					      	$text .= '</div>';
					    $text .= '</div>';

        			$text .= '</fieldset>';
        		$text .= '</form>';
      		$text .= '</div>';
      		$text .= '<div class="modal-footer">';
      			$text .= '<button type="button" class="btn btn-warning" onclick="xajax_show_setPwd_profile('.$_SESSION['user']['id'].')">Set adgangskode</button>';
      			$text .= '<button type="button" class="btn btn-success" onclick="xajax_save_profile(
      																'.$_SESSION['user']['id'].',
      																document.getElementById(\'inputName\').value,
      																document.getElementById(\'inputUsername\').value
      																)">Gem</button>';
        		$text .= '<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Annuller</button>';
      		$text .= '</div>';
    	$text .= '</div><!-- /.modal-content -->';
	$text .= '</div><!-- /.modal-dialog -->';

	$objResponse->assign("modal", "innerHTML", $text);
	$objResponse->script("setupInputRestricts();");
	$objResponse->script("$('#modal').modal('show');");
	return $objResponse;
}

function show_setPwd_profile($id) {
	$objResponse = new xajaxResponse();

	$text .= '<div class="modal-dialog">';
    	$text .= '<div class="modal-content">';
      		$text .= '<div class="modal-header">';
        		$text .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        		$text .= '<h4 class="modal-title">Set adgangskode</h4>';
      		$text .= '</div>';
      		$text .= '<div class="modal-body">';
        		$text .= '<form class="form-horizontal">';
        			$text .= '<fieldset>';

        				$text .= '<div class="form-group">';
					    	$text .= '<label class="col-lg-2 control-label">Adgangskode</label>';
					    	$text .= '<div class="col-lg-10">';
					        	$text .= '<input id="inputPassword1" class="form-control" maxlength="45" type="password">';
					      	$text .= '</div>';
					    $text .= '</div>';

					    $text .= '<div class="form-group">';
					    	$text .= '<label class="col-lg-2 control-label">Gentag</label>';
					    	$text .= '<div class="col-lg-10">';
					        	$text .= '<input id="inputPassword2" class="form-control" maxlength="45" type="password">';
					      	$text .= '</div>';
					    $text .= '</div>';

        			$text .= '</fieldset>';
        		$text .= '</form>';
      		$text .= '</div>';
      		$text .= '<div class="modal-footer">';
      			$text .= '<button type="button" class="btn btn-success" onclick="xajax_save_pwd('.$_SESSION['user']['id'].', document.getElementById(\'inputPassword1\').value, document.getElementById(\'inputPassword2\').value)">Set</button>';
        		$text .= '<button type="button" class="btn btn-default pull-left" onclick="xajax_show_profile()"><i class="fa fa-chevron-left"></i></button>';
      		$text .= '</div>';
    	$text .= '</div><!-- /.modal-content -->';
	$text .= '</div><!-- /.modal-dialog -->';

	$objResponse->assign("modal", "innerHTML", $text);
	$objResponse->script("setupInputRestricts();");
	$objResponse->script("$('#modal').modal('show');");
	return $objResponse;
}

function save_profile($id = "", $name = "", $username = "") {
	$objResponse = new xajaxResponse();

	if(empty($name) || empty($username)) {
		$objResponse->script('swal("Ups!", "Du har vidst ikke udfyldt det hele...", "error")');
	} else {
		global $dba;
		$save = true;
		$sql = "SELECT user FROM sysuser WHERE id = " . $id;
		$stmt = $dba->query($sql);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if($row['user'] != $username) {
			$sql = "SELECT * FROM sysuser_select WHERE deleted = 0 AND username = '" . $username . "'";
			$stmt = $dba->query($sql);
			if($stmt->rowCount() > 0) {
				$save = false;
				$objResponse->script('swal("Ups!", "Brugernavnet er ikke ledigt", "error")');
			}
		}

		if($save) {
			$sql = "UPDATE sysuser SET name = '".$name."', user = '".$username."' WHERE id = " . $id;
			$stmt = $dba->query($sql);
			if($stmt) {
				$objResponse->script('$(\'#modal\').modal(\'hide\');');
				$objResponse->call('xajax_relogin');
				$objResponse->call('xajax_show_alert', 'success', 'Yay!', 'Din profil blev gemt');
			} else {
				$objResponse->script('swal("Hov!", "Der skete en fejl, og din profil blev ikke gemt :(", "error")');
			}
		}
	}

	return $objResponse;
}

function save_pwd($id, $password1, $password2) {
	$objResponse = new xajaxResponse();

	if(empty($password1) || empty($password2)) {
		$objResponse->script('swal("Ups!", "Du har vidst ikke udfyldt det hele...", "error")');
	} else {
		if($password1 == $password2) {
			global $dba;
			$sql = "UPDATE sysuser SET pass = '".$password1."' WHERE id = " . $id;
			$stmt = $dba->query($sql);
			if($stmt) {
				$objResponse->script('$(\'#modal\').modal(\'hide\');');
				$objResponse->call('xajax_show_alert', 'success', 'Yay!', 'Din adgangskode blev ændret');
			} else {
				$objResponse->script('swal("Hov!", "Der skete en fejl, og din profil blev ikke gemt :(", "error")');
			}
		} else {
			$objResponse->script('swal("Ups!", "Adgangskoderne er ikke ens", "error")');
		}
	}

	return $objResponse;
}

?>