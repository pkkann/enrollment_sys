<?php

function load_users() {
	$objResponse = new xajaxResponse();
	$text .= gen_users();
	$objResponse->call('xajax_load_nav', "3");
	$objResponse->assign("middle_wrapper", "innerHTML", $text);
	return $objResponse;
}

function gen_users() {
	$text = "";
	$text .= '<div class="container-fluid">';
		$text .= '<div class="panel panel-default">';
			$text .= '<div class="panel-heading">';
				$text .= '<div class="input-group">';
					$text .= '<span class="input-group-btn">';
		        		$text .= '<button class="btn btn-primary" type="button" onclick="xajax_show_new_user()"><i class="fa fa-plus-circle"></i> Ny</button>';
		      		$text .= '</span>';
		      		$text .= '<input type="text" id="inputSearch" class="form-control" oninput="xajax_search_user(document.getElementById(\'inputSearch\').value)" placeholder="F.eks. navn, brugernavn">';
					$text .= '<span class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>';
				$text .= '</div>';
			$text .= '</div>';
			
			$text .= '<table id="user_table" class="table table-striped table-hover">';
				$text .= gen_user_search('');
			$text .= '</table>';

		$text .= '</div>';
	$text .= '</div>';
	
	return $text;
}

function search_user($string) {
	$objResponse = new xajaxResponse();

	$text = gen_user_search($string);

	$objResponse->assign("user_table", "innerHTML", $text);
	return $objResponse;
}

function gen_user_search($string) {
	global $dba;
	
	$sql = "SELECT * FROM sysuser_select WHERE deleted = 0 AND name LIKE '%".$string."%'";
	$stmt = $dba->query($sql);
	if($stmt->rowCount() < 1) {
		$sql = "SELECT * FROM sysuser_select WHERE deleted = 0 AND user LIKE '%".$string."%'";
		$stmt = $dba->query($sql);
	}

	$text .= '<thead>';
		$text .= '<tr>';
			$text .= '<th width="70%">Navn</th>';
			$text .= '<th width="20%">Brugernavn</th>';
			$text .= '<th width="10%">Administrator</th>';
		$text .= '</tr>';
	$text .= '</thead>';
	$text .= '<tbody>';
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$text .= '<tr class="linkButton" onclick="xajax_show_user_details('.$row['id'].')">';
				$text .= '<td>'.$row['name'].'</td>';
				$text .= '<td>'.$row['username'].'</td>';
				$text .= '<td>'.$row['admin'].'</td>';
			$text .= '</tr>';
		}
	$text .= '</tbody>';

	return $text;
}

function show_user_details($id) {
	global $dba;
	$sql = "SELECT * FROM sysuser_select WHERE id = " . $id;
	$stmt = $dba->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	$objResponse = new xajaxResponse();

	$text .= '<div class="modal-dialog">';
    	$text .= '<div class="modal-content">';
      		$text .= '<div class="modal-header">';
        		$text .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        		if($id != $_SESSION['user']['id']) {
        			$text .= '<h4 class="modal-title">Bruger detaljer</h4>';
        		} else {
        			$text .= '<h4 class="modal-title">Bruger detaljer <b>- dig selv</b></h4>';
        		}
        		
      		$text .= '</div>';
      		$text .= '<div class="modal-body">';
        		$text .= '<div class="panel panel-default">';
        			$text .= '<table class="table">';
        				$text .= '<tr>';
        					$text .= '<td><strong>Navn:</strong></td>';
        					$text .= '<td>'.$row['name'].'</td>';
        				$text .= '</tr>';
        				$text .= '<tr>';
        					$text .= '<td><strong>Brugernavn:</strong></td>';
        					$text .= '<td>'.$row['username'].'</td>';
        				$text .= '</tr>';
        				$text .= '<tr>';
        					$text .= '<td><strong>Administrator:</strong></td>';
        					$text .= '<td>'.$row['admin'].'</td>';
        				$text .= '</tr>';
        				$text .= '<tr>';
        					$text .= '<td><strong>Oprettet af:</strong></td>';
        					$text .= '<td>'.$row['creator'].'</td>';
        				$text .= '</tr>';
        				$text .= '<tr>';
        					$text .= '<td><strong>Oprettet:</strong></td>';
        					$text .= '<td>'.$row['createdate'].'</td>';
        				$text .= '</tr>';
        			$text .= '</table>';
        		$text .= '</div>';
      		$text .= '</div>';
      		$text .= '<div class="modal-footer">';
      			$text .= '<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Luk</button>';
      			if($id != $_SESSION['user']['id']) {
      				$text .= '<button type="button" class="btn btn-warning" onclick="xajax_show_edit_user('.$row['id'].')">Rediger</button>';
      				$text .= '<button type="button" class="btn btn-danger" onclick="xajax_delete_user('.$row['id'].')">Slet</button>';
      			} else {
      				$text .= '<button disabled type="button" class="btn btn-warning">Rediger</button>';
      				$text .= '<button disabled type="button" class="btn btn-danger">Slet</button>';
      			}
	      		
      		$text .= '</div>';
    	$text .= '</div><!-- /.modal-content -->';
	$text .= '</div><!-- /.modal-dialog -->';

	$objResponse->assign("modal", "innerHTML", $text);
	$objResponse->script("$('#modal').modal('show');");

	return $objResponse;
}

function show_new_user() {
	$objResponse = new xajaxResponse();

	$text .= '<div class="modal-dialog">';
    	$text .= '<div class="modal-content">';
      		$text .= '<div class="modal-header">';
        		$text .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        		$text .= '<h4 class="modal-title"><i class="fa fa-plus-circle"></i> Ny bruger</h4>';
      		$text .= '</div>';
      		$text .= '<div class="modal-body">';
        		$text .= '<form class="form-horizontal">';
        			$text .= '<fieldset>';

        				$text .= '<div class="form-group">';
					    	$text .= '<label class="col-lg-2 control-label">Navn</label>';
					    	$text .= '<div class="col-lg-10">';
					        	$text .= '<input id="inputName" class="form-control" maxlength="45" type="text">';
					      	$text .= '</div>';
					    $text .= '</div>';

					    $text .= '<div class="form-group">';
					    	$text .= '<label class="col-lg-2 control-label">Brugernavn</label>';
					    	$text .= '<div class="col-lg-10">';
					        	$text .= '<input id="inputUsername" class="form-control" maxlength="45" type="text">';
					      	$text .= '</div>';
					    $text .= '</div>';

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

					    $text .= '<div class="form-group">';
					      	$text .= '<label for="select" class="col-lg-2 control-label">Administrator</label>';
					      	$text .= '<div class="col-lg-3">';
					        	$text .= '<select id="selectAdmin" class="form-control">';
					          		$text .= '<option value="0">Nej</option>';
					          		$text .= '<option value="1">Ja</option>';
					        	$text .= '</select>';
					      	$text .= '</div>';
					    $text .= '</div>';

        			$text .= '</fieldset>';
        		$text .= '</form>';
      		$text .= '</div>';
      		$text .= '<div class="modal-footer">';
      			$text .= '<button type="button" onclick="xajax_create_user(
      												document.getElementById(\'inputName\').value,
      												document.getElementById(\'inputUsername\').value,
      												document.getElementById(\'inputPassword1\').value,
      												document.getElementById(\'inputPassword2\').value,
      												document.getElementById(\'selectAdmin\').value
      												)" class="btn btn-success">Opret</button>';
        		$text .= '<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Annuller</button>';
      		$text .= '</div>';
    	$text .= '</div><!-- /.modal-content -->';
	$text .= '</div><!-- /.modal-dialog -->';

	$objResponse->assign("modal", "innerHTML", $text);
	$objResponse->script("setupInputRestricts();");
	$objResponse->script("$('#modal').modal('show');");
	return $objResponse;
}

function show_edit_user($id) {
	$objResponse = new xajaxResponse();
	global $dba;
	$sql = "SELECT * FROM sysuser WHERE id = " . $id;
	$stmt = $dba->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	$admin = "";
	if($row['admin'] == 1) {
		$admin = "selected";
	}

	$text .= '<div class="modal-dialog">';
    	$text .= '<div class="modal-content">';
      		$text .= '<div class="modal-header">';
        		$text .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        		$text .= '<h4 class="modal-title"><i class="fa fa-pencil"></i> Rediger bruger</h4>';
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
					    	$text .= '<label class="col-lg-2 control-label">Username</label>';
					    	$text .= '<div class="col-lg-10">';
					        	$text .= '<input id="inputUsername" value="'.$row['user'].'" class="form-control" maxlength="45" type="text">';
					      	$text .= '</div>';
					    $text .= '</div>';

					    $text .= '<div class="form-group">';
					      	$text .= '<label for="select" class="col-lg-2 control-label">Administrator</label>';
					      	$text .= '<div class="col-lg-3">';
					        	$text .= '<select id="selectAdmin" class="form-control">';
					          		$text .= '<option value="0">Nej</option>';
					          		$text .= '<option '.$admin.' value="1">Ja</option>';
					        	$text .= '</select>';
					      	$text .= '</div>';
					    $text .= '</div>';

        			$text .= '</fieldset>';
        		$text .= '</form>';
      		$text .= '</div>';
      		$text .= '<div class="modal-footer">';
      			$text .= '<button type="button" class="btn btn-warning" onclick="xajax_show_setPwd_user('.$id.')">Set adgangskode</button>';
      			$text .= '<button type="button" onclick="xajax_save_user(
      												'.$id.',
      												document.getElementById(\'inputName\').value,
      												document.getElementById(\'inputUsername\').value,
      												document.getElementById(\'selectAdmin\').value
      												)" class="btn btn-success">Gem</button>';
        		$text .= '<button type="button" class="btn btn-default pull-left" onclick="xajax_show_user_details('.$id.')"><i class="fa fa-chevron-left"></i></button>';
      		$text .= '</div>';
    	$text .= '</div><!-- /.modal-content -->';
	$text .= '</div><!-- /.modal-dialog -->';

	$objResponse->assign("modal", "innerHTML", $text);
	$objResponse->script("setupInputRestricts();");
	$objResponse->script("$('#modal').modal('show');");
	return $objResponse;	
}

function show_setPwd_user($id) {
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
      			$text .= '<button type="button" class="btn btn-success" onclick="xajax_save_pwd('.$id.', document.getElementById(\'inputPassword1\').value, document.getElementById(\'inputPassword2\').value)">Set</button>';
        		$text .= '<button type="button" class="btn btn-default pull-left" onclick="xajax_show_edit_user('.$id.')"><i class="fa fa-chevron-left"></i></button>';
      		$text .= '</div>';
    	$text .= '</div><!-- /.modal-content -->';
	$text .= '</div><!-- /.modal-dialog -->';

	$objResponse->assign("modal", "innerHTML", $text);
	$objResponse->script("setupInputRestricts();");
	$objResponse->script("$('#modal').modal('show');");
	return $objResponse;
}

function create_user($name = "", $username = "", $password1 = "", $password2 = "", $admin = 0) {
	$objResponse = new xajaxResponse();

	if(empty($name) || empty($username) || empty($password1) || empty($password2)) {
		$objResponse->script('swal("Ups!", "Du har vidst ikke udfyldt det hele...", "error")');
	} else {
		if($password1 == $password2) {
			global $dba;
			$sql = "SELECT * FROM sysuser WHERE deleted = 0 AND user = '" . $username . "'";
			$stmt = $dba->query($sql);
			if($stmt->rowCount() > 0) {
				$objResponse->script('swal("Ups!", "Brugernavnet er ikke ledigt", "error")');
			} else {
				$creator = $_SESSION['user']['id'];

				$sql = "INSERT INTO sysuser (name, user, pass, admin, creator, createdate) VALUES('".$name."', '".$username."', '".$password1."', ".$admin.", ".$creator.", NOW())";
				$stmt = $dba->query($sql);
				if($stmt) {
					$objResponse->script('$(\'#modal\').modal(\'hide\');');
					$objResponse->call('xajax_load_users');
					$objResponse->call('xajax_show_alert', 'success', 'Yay!', 'Brugeren blev oprettet');
				} else {
					$objResponse->script('swal("Hov!", "Der skete en fejl, og brugeren blev ikke oprettet :(", "error")');
				}
			}
		} else {
			$objResponse->script('swal("Ups!", "Adgangskoderne er ikke ens", "error")');
		}
	}

	return $objResponse;
}

function delete_user($id, $ask = 1) {
	$objResponse = new xajaxResponse();

	if($ask == 1) {
		$objResponse->script('
			swal({
				title: "Er du sikker?",
				text: "Denne handling kan ikke fortrydes!",
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: "btn-danger",
				confirmButtonText: "Ja slet!",
				cancelButtonText: "Nej",
				closeOnConfirm: true
			},
			function(){
				xajax_delete_user('.$id.', 0);
			});');
	} else {
		$sql = "UPDATE sysuser SET deleted = 1 WHERE id = " . $id;
		global $dba;
		$stmt = $dba->query($sql);
		if(!$stmt) {
			$objResponse->script('swal("Hov!", "Der skete en fejl :( brugeren blev ikke slettet. Kontakt en administrator", "error")');
		} else {
			$objResponse->script('$(\'#modal\').modal(\'hide\');');
			$objResponse->call('xajax_show_alert', 'success', 'Yay!', 'Brugeren blev slettet');
		}
		
		$objResponse->call('xajax_load_users');
	}

	return $objResponse;
}

function save_user($id = "", $name = "", $username = "", $admin = 0) {
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
			$objResponse->script("console.log(\"".$sql."\")");
			if($stmt->rowCount() > 0) {
				$save = false;
				$objResponse->script('swal("Ups!", "Brugernavnet er ikke ledigt", "error")');
			}

		}

		if($save) {
			$sql = "UPDATE sysuser SET name = '".$name."', user = '".$username."', admin = " . $admin . " WHERE id = " . $id;
			$stmt = $dba->query($sql);
			if($stmt) {
				$objResponse->script('$(\'#modal\').modal(\'hide\');');
				$objResponse->call('xajax_load_users');
				$objResponse->call('xajax_show_alert', 'success', 'Yay!', 'Brugeren blev gemt');
			} else {
				$objResponse->script('swal("Hov!", "Der skete en fejl, og brugeren blev ikke gemt :(", "error")');
			}
		}
	}

	return $objResponse;
}

?>