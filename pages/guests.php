<?php

function load_guests() {
	$objResponse = new xajaxResponse();
	$text .= gen_guests();
	$objResponse->call('xajax_load_nav', "2");
	$objResponse->assign("middle_wrapper", "innerHTML", $text);
	return $objResponse;
}

function gen_guests() {
	$text = "";
	$text .= '<div class="container-fluid">';
		$text .= '<div class="panel panel-default">';
			$text .= '<div class="panel-heading">';
				$text .= '<div class="input-group">';
					$text .= '<span class="input-group-btn">';
		        		$text .= '<button class="btn btn-primary" type="button" onclick="xajax_show_new_guest()"><i class="fa fa-plus-circle"></i> Ny</button>';
		      		$text .= '</span>';
		      		$text .= '<input type="text" id="inputSearch" class="form-control" oninput="xajax_search_guest(document.getElementById(\'inputSearch\').value)" placeholder="F.eks. navn, fødselsdag[dd/mm/yyyy]">';
					$text .= '<span class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>';
				$text .= '</div>';
			$text .= '</div>';
			
			$text .= '<table id="guest_table" class="table table-striped table-hover">';
				$text .= gen_guest_search('');
			$text .= '</table>';

		$text .= '</div>';
	$text .= '</div>';

	return $text;
}

function search_guest($string) {
	$objResponse = new xajaxResponse();

	$text = gen_guest_search($string);

	$objResponse->assign("guest_table", "innerHTML", $text);
	return $objResponse;
}

function gen_guest_search($string) {
	global $dba;
	$sql = "SELECT * FROM guests_select WHERE name LIKE '%".$string."%'";
	$stmt = $dba->query($sql);
	if($stmt->rowCount() < 1) {
		$sql = "SELECT * FROM guests_select WHERE birthday LIKE '%".$string."%'";
		$stmt = $dba->query($sql);
	}

	$text .= '<thead>';
		$text .= '<tr>';
			$text .= '<th width="70%">Navn</th>';
		$text .= '</tr>';
	$text .= '</thead>';
	$text .= '<tbody>';
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		if($_SESSION['shift']['id'] != 0) {
			/*
			$sql1 = "SELECT * FROM enrollments_guests_select WHERE guestid = " . $row['id'] . " AND shiftid = " . $_SESSION['shift']['id'];
			$stmt1 = $dba->query($sql1);
			if($stmt1->rowCount() > 0) {
				$text .= '<tr class="linkButton success" onclick="xajax_show_guest_details('.$row['id'].')">';
			} else {
				$text .= '<tr class="linkButton" onclick="xajax_show_guest_details('.$row['id'].')">';
			}
			*/
		} else {
			$text .= '<tr class="linkButton" onclick="xajax_show_guest_details('.$row['id'].')">';
		}
				$text .= '<td>'.$row['name'].'</td>';
			$text .= '</tr>';
		}
	$text .= '</tbody>';

	return $text;
}

function show_guest_details($id) {
	global $dba;
	$sql = "SELECT * FROM guests_select WHERE id = " . $id;
	$stmt = $dba->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	$objResponse = new xajaxResponse();

	$text .= '<div class="modal-dialog">';
    	$text .= '<div class="modal-content">';
      		$text .= '<div class="modal-header">';
        		$text .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        		$text .= '<h4 class="modal-title">Gæst detaljer</h4>';
      		$text .= '</div>';
      		$text .= '<div class="modal-body">';
        		$text .= '<div class="panel panel-default">';
        			$text .= '<table class="table">';
        				$text .= '<tr>';
        					$text .= '<td><strong>Navn:</strong></td>';
        					$text .= '<td>'.$row['name'].'</td>';
        				$text .= '</tr>';
        				$text .= '<tr>';
        					$text .= '<td><strong>Fødselsdag:</strong></td>';
        					$text .= '<td>'.$row['birthday'].'</td>';
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
      			$text .= '<button type="button" class="btn btn-danger pull-left" onclick="xajax_delete_guest('.$row['id'].')">Slet</button>';
      			$text .= '<button type="button" class="btn btn-warning pull-left" onclick="xajax_show_edit_guest('.$row['id'].')">Rediger</button>';
      			if($_SESSION['shift']['id'] != 0) {
      				/*
      				$sql1 = "SELECT * FROM enrollments_residents_select WHERE residentid = " . $id . " AND shiftid = " . $_SESSION['shift']['id'];
      				$stmt1 = $dba->query($sql1);
      				if($stmt1->rowCount() > 0) {
      					$text .= '<button disabled type="button" title="Man kan desværre ikke udskrive folk endnu" class="btn btn-success pull-left">Indskrevet</button>';
      				} else {
      					$text .= '<button type="button" class="btn btn-primary pull-left" onclick="xajax_show_enroll_resident('.$id.')">Indskriv</button>';
      				}
      				*/
	      		}
        		$text .= '<button type="button" class="btn btn-default" data-dismiss="modal">Luk</button>';
      		$text .= '</div>';
    	$text .= '</div><!-- /.modal-content -->';
	$text .= '</div><!-- /.modal-dialog -->';

	$objResponse->assign("modal", "innerHTML", $text);
	$objResponse->script("$('#modal').modal('show');");

	return $objResponse;
}

function show_new_guest() {
	$objResponse = new xajaxResponse();

	$text .= '<div class="modal-dialog">';
    	$text .= '<div class="modal-content">';
      		$text .= '<div class="modal-header">';
        		$text .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        		$text .= '<h4 class="modal-title">Ny gæst</h4>';
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
					    	$text .= '<label class="col-lg-2 control-label">Fødselsdag</label>';
					    	$text .= '<div class="col-lg-2">';
					        	$text .= '<input id="inputBirthDate" class="form-control numbersOnly" maxlength="2" placeholder="dd" type="text">';
					      	$text .= '</div>';
					      	$text .= '<div class="col-lg-2">';
					        	$text .= '<input id="inputBirthMonth" class="form-control numbersOnly" maxlength="2" placeholder="mm" type="text">';
					      	$text .= '</div>';
					      	$text .= '<div class="col-lg-2">';
					        	$text .= '<input id="inputBirthYear" class="form-control numbersOnly" maxlength="4" placeholder="yyyy" type="text">';
					      	$text .= '</div>';
					    $text .= '</div>';
        			$text .= '</fieldset>';
        		$text .= '</form>';
      		$text .= '</div>';
      		$text .= '<div class="modal-footer">';
      			$text .= '<button type="button" onclick="xajax_create_guest(
      												document.getElementById(\'inputName\').value,
      												document.getElementById(\'inputBirthDate\').value,
      												document.getElementById(\'inputBirthMonth\').value,
      												document.getElementById(\'inputBirthYear\').value
      												)" class="btn btn-success pull-left">Opret</button>';
				if($_SESSION['shift']['id'] != 0) {
					/*
					$text .= '<button type="button" onclick="xajax_create_guest(
      												document.getElementById(\'inputName\').value,
      												document.getElementById(\'inputBirthDate\').value,
      												document.getElementById(\'inputBirthMonth\').value,
      												document.getElementById(\'inputBirthYear\').value,
      												true
      												)" class="btn btn-success pull-left">Opret og indskriv</button>';
      												*/
				}
        		$text .= '<button type="button" class="btn btn-default" data-dismiss="modal">Annuller</button>';
      		$text .= '</div>';
    	$text .= '</div><!-- /.modal-content -->';
	$text .= '</div><!-- /.modal-dialog -->';

	$objResponse->assign("modal", "innerHTML", $text);
	$objResponse->script("setupInputRestricts();");
	$objResponse->script("$('#modal').modal('show');");
	return $objResponse;
}

function show_edit_guest($id) {
	$objResponse = new xajaxResponse();
	global $dba;
	$sql = "SELECT * FROM guests_edit WHERE id = " . $id;
	$stmt = $dba->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	$text .= '<div class="modal-dialog">';
    	$text .= '<div class="modal-content">';
      		$text .= '<div class="modal-header">';
        		$text .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        		$text .= '<h4 class="modal-title">Rediger gæst</h4>';
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
					    	$text .= '<label class="col-lg-2 control-label">Fødselsdag</label>';
					    	$text .= '<div class="col-lg-2">';
					        	$text .= '<input id="inputBirthDate" value="'.$row['birthdate'].'" class="form-control numbersOnly" maxlength="2" placeholder="dd" type="text">';
					      	$text .= '</div>';
					      	$text .= '<div class="col-lg-2">';
					        	$text .= '<input id="inputBirthMonth" value="'.$row['birthmonth'].'" class="form-control numbersOnly" maxlength="2" placeholder="mm" type="text">';
					      	$text .= '</div>';
					      	$text .= '<div class="col-lg-2">';
					        	$text .= '<input id="inputBirthYear" value="'.$row['birthyear'].'" class="form-control numbersOnly" maxlength="4" placeholder="yyyy" type="text">';
					      	$text .= '</div>';
					    $text .= '</div>';
        			$text .= '</fieldset>';
        		$text .= '</form>';
      		$text .= '</div>';
      		$text .= '<div class="modal-footer">';
      			$text .= '<button type="button" onclick="xajax_save_guest(
      												'.$id.',
      												document.getElementById(\'inputName\').value,
      												document.getElementById(\'inputBirthDate\').value,
      												document.getElementById(\'inputBirthMonth\').value,
      												document.getElementById(\'inputBirthYear\').value
      												)" class="btn btn-success pull-left">Gem</button>';
        		$text .= '<button type="button" class="btn btn-default" data-dismiss="modal">Annuller</button>';
      		$text .= '</div>';
    	$text .= '</div><!-- /.modal-content -->';
	$text .= '</div><!-- /.modal-dialog -->';

	$objResponse->assign("modal", "innerHTML", $text);
	$objResponse->script("setupInputRestricts();");
	$objResponse->script("$('#modal').modal('show');");
	return $objResponse;	
}

function create_guest($name = "", $birthDate = "", $birthMonth = "", $birthYear = "", $enroll = false) {
	$objResponse = new xajaxResponse();

	if(empty($name) || empty($birthDate) || empty($birthMonth) || empty($birthYear)) {
		$objResponse->script('swal("Ups!", "Du har vidst ikke udfyldt det hele...", "error")');
	} else {
		$birthday = "";
		$birthday .= ltrim((string)$birthYear, '0') . '-' . ltrim((string)$birthMonth, '0') . '-' . ltrim((string)$birthDate, '0');
		$creator = $_SESSION['user']['id'];

		global $dba;
		$sql = "INSERT INTO guests (name, birthday, creator, createdate) VALUES('".$name."', '".$birthday."', ".$creator.", NOW())";
		$stmt = $dba->query($sql);

		if($stmt) {
			$objResponse->script('$(\'#modal\').modal(\'hide\');');
			$objResponse->script('swal("Yay!", "Gæsten blev oprettet", "success")');
			$objResponse->call('xajax_load_guests');
			if($enroll) {
				$sql = "SELECT max(id) as id FROM guests";
				$stmt = $dba->query($sql);
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				$objResponse->call('xajax_show_enroll_guest', $row['id'], true);
			}
		} else {
			$objResponse->script('swal("Hov!", "Der skete en fejl, og gæsten blev ikke oprettet :(", "error")');
		}

		
	}

	return $objResponse;
}

function delete_guest($id, $ask = 1) {
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
				closeOnConfirm: false
			},
			function(){
				xajax_delete_guest('.$id.', 0);
			});');
	} else {
		$sql = "DELETE FROM guests WHERE id = " . $id;
		global $dba;
		$stmt = $dba->query($sql);
		if(!$stmt) {
			$objResponse->script('swal("Hov!", "Der skete en fejl :( gæsten blev ikke slettet. Kontakt en administrator", "error")');
		} else {
			$objResponse->script('$(\'#modal\').modal(\'hide\');');
			$objResponse->script('swal("Yay!", "Gæsten blev Slettet", "success")');
		}
		
		$objResponse->call('xajax_load_guests');
	}

	return $objResponse;
}

function save_guest($id = "", $name = "", $birthDate = "", $birthMonth = "", $birthYear = "") {
	$objResponse = new xajaxResponse();

	$birthday = "";
	$birthday .= (string)$birthYear . '-' . (string)$birthMonth . '-' . (string)$birthDate;

	global $dba;
	$sql = "UPDATE guests SET name='".$name."', birthday='".$birthday."' WHERE id=" . $id;
	$stmt = $dba->query($sql);

	if($stmt) {
		$objResponse->script('$(\'#modal\').modal(\'hide\');');
		$objResponse->script('swal("Yay!", "Gæsten blev gemt", "success")');
		$objResponse->call('xajax_load_guests');
	} else {
		$objResponse->script('swal("Hov!", "Der skete en fejl. Gæsten blev ikke gemt :(", "error")');
	}

	

	return $objResponse;
}

?>