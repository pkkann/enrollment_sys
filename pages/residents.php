<?php

function load_residents() {
	$objResponse = new xajaxResponse();
	$text .= gen_residents();
	$objResponse->call('xajax_load_nav', "1");
	$objResponse->assign("middle_wrapper", "innerHTML", $text);
	return $objResponse;
}

function gen_residents() {
	$text = "";
	$text .= '<div class="container-fluid">';
		$text .= '<div class="panel panel-default">';
			$text .= '<div class="panel-heading">';
				$text .= '<div class="input-group">';
					$text .= '<span class="input-group-btn">';
		        		$text .= '<button class="btn btn-primary" type="button" onclick="xajax_show_new_resident()"><i class="fa fa-plus-circle"></i> Ny</button>';
		      		$text .= '</span>';
		      		$text .= '<input type="text" id="inputSearch" class="form-control" oninput="xajax_search_resident(document.getElementById(\'inputSearch\').value)" placeholder="F.eks. navn, adresse[blok-nr], fødselsdag[dd/mm/yyyy]">';
					$text .= '<span class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>';
				$text .= '</div>';
			$text .= '</div>';
			
			$text .= '<table id="resident_table" class="table table-striped table-hover">';
				$text .= gen_resident_search('');
			$text .= '</table>';

		$text .= '</div>';
	$text .= '</div>';
	
	return $text;
}

function search_resident($string) {
	$objResponse = new xajaxResponse();

	$text = gen_resident_search($string);

	$objResponse->assign("resident_table", "innerHTML", $text);
	return $objResponse;
}

function gen_resident_search($string) {
	global $dba;
	$sql = "SELECT * FROM residents_select WHERE deleted = 0 AND name LIKE '%".$string."%'";
	$stmt = $dba->query($sql);
	if($stmt->rowCount() < 1) {
		//$sql = "SELECT * FROM residents_select WHERE deleted = 0 AND address LIKE '%".$string."%'";
		$sql = "SELECT * FROM residents_select u WHERE deleted = 0 AND LEVENSHTEIN_RATIO(u.address, '".$string."') > 30";
		$stmt = $dba->query($sql);
		if($stmt->rowCount() < 1) {
			$sql = "SELECT * FROM residents_select WHERE deleted = 0 AND birthday LIKE '%".$string."%'";
			$stmt = $dba->query($sql);
		}
	}

	$text .= '<thead>';
		$text .= '<tr>';
			$text .= '<th width="70%">Navn</th>';
			$text .= '<th width="10%">Høne</th>';
			$text .= '<th width="10%">Reserve</th>';
			$text .= '<th width="10%">1-1</th>';
		$text .= '</tr>';
	$text .= '</thead>';
	$text .= '<tbody>';
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		if($_SESSION['shift']['id'] != 0) {
			$sql1 = "SELECT * FROM enrollments_residents_select WHERE residentid = " . $row['id'] . " AND shiftid = " . $_SESSION['shift']['id'];
			$stmt1 = $dba->query($sql1);
			if($stmt1->rowCount() > 0) {
				$text .= '<tr class="linkButton success" onclick="xajax_show_resident_details('.$row['id'].')">';
			} else {
				$text .= '<tr class="linkButton" onclick="xajax_show_resident_details('.$row['id'].')">';
			}
		} else {
			$text .= '<tr class="linkButton" onclick="xajax_show_resident_details('.$row['id'].')">';
		}
				$text .= '<td>'.$row['name'].'</td>';
				$text .= '<td>'.$row['hoene'].'</td>';
				$text .= '<td>'.$row['reserve'].'</td>';
				$text .= '<td>'.$row['oneone'].'</td>';
			$text .= '</tr>';
		}
	$text .= '</tbody>';

	return $text;
}

function show_resident_details($id) {
	global $dba;
	$sql = "SELECT * FROM residents_select WHERE id = " . $id;
	$stmt = $dba->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	$objResponse = new xajaxResponse();

	$text .= '<div class="modal-dialog">';
    	$text .= '<div class="modal-content">';
      		$text .= '<div class="modal-header">';
        		$text .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        		$text .= '<h4 class="modal-title">Beboer detaljer</h4>';
      		$text .= '</div>';
      		$text .= '<div class="modal-body">';
        		$text .= '<div class="panel panel-default">';
        			$text .= '<table class="table">';
        				$text .= '<tr>';
        					$text .= '<td><strong>Navn:</strong></td>';
        					$text .= '<td>'.$row['name'].'</td>';
        				$text .= '</tr>';
        				$text .= '<tr>';
        					$text .= '<td><strong>Adresse:</strong></td>';
        					$text .= '<td>'.$row['address'].'</td>';
        				$text .= '</tr>';
        				$text .= '<tr>';
        					$text .= '<td><strong>Høne:</strong></td>';
        					$text .= '<td>'.$row['hoene'].'</td>';
        				$text .= '</tr>';
        				$text .= '<tr>';
        					$text .= '<td><strong>Reserve:</strong></td>';
        					$text .= '<td>'.$row['reserve'].'</td>';
        				$text .= '</tr>';
        				$text .= '<tr>';
        					$text .= '<td><strong>1-1:</strong></td>';
        					$text .= '<td>'.$row['oneone'].'</td>';
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
      			$text .= '<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Luk</button>';
      			if($_SESSION['shift']['id'] != 0) {
      				$sql1 = "SELECT * FROM enrollments_residents_select WHERE residentid = " . $id . " AND shiftid = " . $_SESSION['shift']['id'];
      				$stmt1 = $dba->query($sql1);
      				if($stmt1->rowCount() > 0) {
      					$text .= '<button disabled type="button" title="Man kan desværre ikke udskrive folk endnu" class="btn btn-success">Indskrevet</button>';
      				} else {
      					$text .= '<button type="button" class="btn btn-primary" onclick="xajax_show_enroll_resident('.$id.')">Indskriv</button>';
      				}
	      		}
	      		$text .= '<button type="button" class="btn btn-warning" onclick="xajax_show_edit_resident('.$row['id'].')">Rediger</button>';
      			$text .= '<button type="button" class="btn btn-danger" onclick="xajax_delete_resident('.$row['id'].')">Slet</button>';
      		$text .= '</div>';
    	$text .= '</div><!-- /.modal-content -->';
	$text .= '</div><!-- /.modal-dialog -->';

	$objResponse->assign("modal", "innerHTML", $text);
	$objResponse->script("$('#modal').modal('show');");

	return $objResponse;
}

function show_new_resident() {
	$objResponse = new xajaxResponse();

	$text .= '<div class="modal-dialog">';
    	$text .= '<div class="modal-content">';
      		$text .= '<div class="modal-header">';
        		$text .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        		$text .= '<h4 class="modal-title">Ny beboer</h4>';
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
					    	$text .= '<label class="col-lg-2 control-label">Adresse</label>';
					    	$text .= '<div class="col-lg-2">';
					        	$text .= '<input id="inputAddrBlok" placeholder="Blok" class="form-control numbersOnly" maxlength="2" type="text">';
					      	$text .= '</div>';
					      	$text .= '<div class="col-lg-2">';
					        	$text .= '<input id="inputAddrNr" placeholder="Nr" class="form-control numbersOnly" maxlength="3" type="text">';
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
					    $text .= '<div class="form-group">';
					      	$text .= '<label for="select" class="col-lg-2 control-label">Høne</label>';
					      	$text .= '<div class="col-lg-3">';
					        	$text .= '<select id="selectHoene" class="form-control">';
					          		$text .= '<option value="0">Nej</option>';
					          		$text .= '<option value="1">Ja</option>';
					        	$text .= '</select>';
					      	$text .= '</div>';
					    $text .= '</div>';
					    $text .= '<div class="form-group">';
					      	$text .= '<label for="select" class="col-lg-2 control-label">Reserve</label>';
					      	$text .= '<div class="col-lg-3">';
					        	$text .= '<select id="selectReserve" class="form-control">';
					          		$text .= '<option value="0">Nej</option>';
					          		$text .= '<option value="1">Ja</option>';
					        	$text .= '</select>';
					      	$text .= '</div>';
					    $text .= '</div>';
					    $text .= '<div class="form-group">';
					      	$text .= '<label for="select" class="col-lg-2 control-label">1-1</label>';
					      	$text .= '<div class="col-lg-3">';
					        	$text .= '<select id="selectOneone" class="form-control">';
					          		$text .= '<option value="0">Nej</option>';
					          		$text .= '<option value="1">Ja</option>';
					        	$text .= '</select>';
					      	$text .= '</div>';
					    $text .= '</div>';
        			$text .= '</fieldset>';
        		$text .= '</form>';
      		$text .= '</div>';
      		$text .= '<div class="modal-footer">';
      			$text .= '<button type="button" onclick="xajax_create_resident(
      												document.getElementById(\'inputName\').value,
      												document.getElementById(\'inputAddrBlok\').value,
      												document.getElementById(\'inputAddrNr\').value,
      												document.getElementById(\'selectHoene\').value,
      												document.getElementById(\'selectReserve\').value,
      												document.getElementById(\'selectOneone\').value,
      												document.getElementById(\'inputBirthDate\').value,
      												document.getElementById(\'inputBirthMonth\').value,
      												document.getElementById(\'inputBirthYear\').value
      												)" class="btn btn-success">Opret</button>';
				if($_SESSION['shift']['id'] != 0) {
					$text .= '<button type="button" onclick="xajax_create_resident(
      												document.getElementById(\'inputName\').value,
      												document.getElementById(\'inputAddrBlok\').value,
      												document.getElementById(\'inputAddrNr\').value,
      												document.getElementById(\'selectHoene\').value,
      												document.getElementById(\'selectReserve\').value,
      												document.getElementById(\'selectOneone\').value,
      												document.getElementById(\'inputBirthDate\').value,
      												document.getElementById(\'inputBirthMonth\').value,
      												document.getElementById(\'inputBirthYear\').value,
      												true
      												)" class="btn btn-success">Opret og indskriv</button>';
				}
        		$text .= '<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Annuller</button>';
      		$text .= '</div>';
    	$text .= '</div><!-- /.modal-content -->';
	$text .= '</div><!-- /.modal-dialog -->';

	$objResponse->assign("modal", "innerHTML", $text);
	$objResponse->script("setupInputRestricts();");
	$objResponse->script("$('#modal').modal('show');");
	return $objResponse;
}

function show_edit_resident($id) {
	$objResponse = new xajaxResponse();
	global $dba;
	$sql = "SELECT * FROM residents_edit WHERE id = " . $id;
	$stmt = $dba->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	$hoene = "";
	if($row['hoene'] == 1) {
		$hoene = "selected";
	}
	$reserve = "";
	if($row['reserve'] == 1) {
		$reserve = "selected";
	}
	$oneone = "";
	if($row['oneone'] == 1) {
		$oneone = "selected";
	}

	$text .= '<div class="modal-dialog">';
    	$text .= '<div class="modal-content">';
      		$text .= '<div class="modal-header">';
        		$text .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        		$text .= '<h4 class="modal-title">Rediger beboer</h4>';
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
					    	$text .= '<label class="col-lg-2 control-label">Adresse</label>';
					    	$text .= '<div class="col-lg-2">';
					        	$text .= '<input id="inputAddrBlok" value="'.$row['blok'].'" placeholder="Blok" class="form-control numbersOnly" maxlength="2" type="text">';
					      	$text .= '</div>';
					      	$text .= '<div class="col-lg-2">';
					        	$text .= '<input id="inputAddrNr" value="'.$row['nr'].'" placeholder="Nr" class="form-control numbersOnly" maxlength="3" type="text">';
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
					    $text .= '<div class="form-group">';
					      	$text .= '<label for="select" class="col-lg-2 control-label">Høne</label>';
					      	$text .= '<div class="col-lg-3">';
					        	$text .= '<select id="selectHoene" class="form-control">';
					          		$text .= '<option value="0">Nej</option>';
					          		$text .= '<option '.$hoene.' value="1">Ja</option>';
					        	$text .= '</select>';
					      	$text .= '</div>';
					    $text .= '</div>';
					    $text .= '<div class="form-group">';
					      	$text .= '<label for="select" class="col-lg-2 control-label">Reserve</label>';
					      	$text .= '<div class="col-lg-3">';
					        	$text .= '<select id="selectReserve" class="form-control">';
					          		$text .= '<option value="0">Nej</option>';
					          		$text .= '<option '.$reserve.' value="1">Ja</option>';
					        	$text .= '</select>';
					      	$text .= '</div>';
					    $text .= '</div>';
					    $text .= '<div class="form-group">';
					      	$text .= '<label for="select" class="col-lg-2 control-label">1-1</label>';
					      	$text .= '<div class="col-lg-3">';
					        	$text .= '<select id="selectOneone" class="form-control">';
					          		$text .= '<option value="0">Nej</option>';
					          		$text .= '<option '.$oneone.' value="1">Ja</option>';
					        	$text .= '</select>';
					      	$text .= '</div>';
					    $text .= '</div>';
        			$text .= '</fieldset>';
        		$text .= '</form>';
      		$text .= '</div>';
      		$text .= '<div class="modal-footer">';
      			$text .= '<button type="button" onclick="xajax_save_resident(
      												'.$id.',
      												document.getElementById(\'inputName\').value,
      												document.getElementById(\'inputAddrBlok\').value,
      												document.getElementById(\'inputAddrNr\').value,
      												document.getElementById(\'selectHoene\').value,
      												document.getElementById(\'selectReserve\').value,
      												document.getElementById(\'selectOneone\').value,
      												document.getElementById(\'inputBirthDate\').value,
      												document.getElementById(\'inputBirthMonth\').value,
      												document.getElementById(\'inputBirthYear\').value
      												)" class="btn btn-success">Gem</button>';
        		$text .= '<button type="button" class="btn btn-default pull-left" onclick="xajax_show_resident_details('.$id.')"><i class="fa fa-chevron-left"></i></button>';
      		$text .= '</div>';
    	$text .= '</div><!-- /.modal-content -->';
	$text .= '</div><!-- /.modal-dialog -->';

	$objResponse->assign("modal", "innerHTML", $text);
	$objResponse->script("setupInputRestricts();");
	$objResponse->script("$('#modal').modal('show');");
	return $objResponse;	
}

function show_enroll_resident($id, $justcreated = false) {
	$objResponse = new xajaxResponse();

	global $dba;
	$sql = "CALL enrollments_residents_insert(".$id.", ".$_SESSION['user']['id'].", ".$_SESSION['shift']['id'].")";
	$stmt = $dba->query($sql);

	if(!$stmt) {
		$objResponse->script('swal("Hov!", "Der skete sku en fejl.. Beboeren blev ikke indskrevet :( Kontakt en administrator", "error")');
	} else {
		//$objResponse->call('xajax_show_resident_details', $id);
		
		if($justcreated) {
			//$objResponse->script('swal("Yay!", "Beboeren blev oprettet og indskrevet", "success")');
			$objResponse->call('xajax_show_alert', 'success', 'Yay!', 'Beboeren blev oprettet og indskrevet');
		} else {
			//$objResponse->script('swal("Yay!", "Beboeren blev indskrevet", "success")');
			$objResponse->call('xajax_show_alert', 'success', 'Yay!', 'Beboeren blev indskrevet');
		}
		
		
		$objResponse->script('$(\'#modal\').modal(\'hide\');');
		$objResponse->call('xajax_do_reload_shift');
	}

	return $objResponse;
}

function create_resident($name = "", $blok = "", $nr = "", $hoene = 0, $reserve = 0, $oneone = 0, $birthDate = "", $birthMonth = "", $birthYear = "", $enroll = false) {
	$objResponse = new xajaxResponse();

	if(empty($name) || empty($blok) || empty($nr) || empty($birthDate) || empty($birthMonth) || empty($birthYear)) {
		$objResponse->script('swal("Ups!", "Du har vidst ikke udfyldt det hele...", "error")');
	} else {
		if(checkdate($birthMonth, $birthDate, $birthYear)) {
			$birthday = "";
			$birthday .= ltrim((string)$birthYear, '0') . '-' . ltrim((string)$birthMonth, '0') . '-' . ltrim((string)$birthDate, '0');
			$creator = $_SESSION['user']['id'];

			global $dba;
			$sql = "INSERT INTO residents (name, addr_blok, addr_nr, birthday, hoene, reserve, oneone, creator, createdate) VALUES('".$name."', ".ltrim($blok, '0').", ".ltrim($nr, '0').", '".$birthday."', ".$hoene.", ".$reserve.", ".$oneone.", ".$creator.", NOW())";
			$stmt = $dba->query($sql);

			if($stmt) {
				$objResponse->script('$(\'#modal\').modal(\'hide\');');
				if(!$enroll) {
					$objResponse->call('xajax_show_alert', 'success', 'Yay!', 'Beboeren blev oprettet');
				}
				$objResponse->call('xajax_load_residents');
				if($enroll) {
					$sql = "SELECT max(id) as id FROM residents";
					$stmt = $dba->query($sql);
					$row = $stmt->fetch(PDO::FETCH_ASSOC);
					$objResponse->call('xajax_show_enroll_resident', $row['id'], true);
				}
			} else {
				$objResponse->script('swal("Hov!", "Der skete en fejl, og beboeren blev ikke oprettet :(", "error")');
			}
		} else {
			$objResponse->script('swal("Ups!", "Fødselsdagen er ikke gyldig", "error")');
		}
		
	}

	return $objResponse;
}

function delete_resident($id, $ask = 1) {
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
				xajax_delete_resident('.$id.', 0);
			});');
	} else {
		$sql = "UPDATE residents SET deleted = 1 WHERE id = " . $id;
		global $dba;
		$stmt = $dba->query($sql);
		if(!$stmt) {
			$objResponse->script('swal("Hov!", "Der skete en fejl :( beboeren blev ikke slettet. Kontakt en administrator", "error")');
		} else {
			$objResponse->script('$(\'#modal\').modal(\'hide\');');
			$objResponse->call('xajax_show_alert', 'success', 'Yay!', 'Beboeren blev slettet');
		}
		
		$objResponse->call('xajax_load_residents');
	}

	return $objResponse;
}

function save_resident($id = "", $name = "", $blok = "", $nr = "", $hoene = 0, $reserve = 0, $oneone = 0, $birthDate = "", $birthMonth = "", $birthYear = "") {
	$objResponse = new xajaxResponse();

	if(empty($name) || empty($blok) || empty($nr) || empty($birthDate) || empty($birthMonth) || empty($birthYear)) {
		$objResponse->script('swal("Ups!", "Du har vidst ikke udfyldt det hele...", "error")');
	} else {
		if(checkdate($birthMonth, $birthDate, $birthYear)) {
			$birthday = "";
			$birthday .= (string)$birthYear . '-' . (string)$birthMonth . '-' . (string)$birthDate;

			global $dba;
			$sql = "UPDATE residents SET name='".$name."', addr_blok=".$blok.", addr_nr=".$nr.", hoene=".$hoene.", reserve=".$reserve.", oneone=".$oneone.", birthday='".$birthday."' WHERE id=" . $id;
			$stmt = $dba->query($sql);

			if($stmt) {
				$objResponse->script('$(\'#modal\').modal(\'hide\');');
				$objResponse->call('xajax_show_alert', 'success', 'Yay!', 'Beboeren blev gemt');
				$objResponse->call('xajax_load_residents');
			} else {
				$objResponse->script('swal("Hov!", "Der skete en fejl. Beboeren blev ikke gemt :(", "error")');
			}
		} else {
			$objResponse->script('swal("Ups!", "Fødselsdagen er ikke gyldig", "error")');
		}
	}

	return $objResponse;
}

?>