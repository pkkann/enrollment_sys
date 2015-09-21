<?php

function load_shifts() {
	$objResponse = new xajaxResponse();
	$text .= gen_shifts();
	$objResponse->call('xajax_load_nav', "4");
	$objResponse->assign("middle_wrapper", "innerHTML", $text);
	return $objResponse;
}

function gen_shifts() {
	global $dba;
	$sql = "SELECT * FROM shifts_select ORDER BY ended IS NULL DESC, ended DESC";
	$stmt = $dba->query($sql);
	$text = "";
	$text .= '<div class="container-fluid">';
		$text .= '<div class="panel panel-default">';
			
			$text .= '<table id="user_table" class="table table-striped table-hover">';
				$text .= '<thead>';
					$text .= '<tr>';
								$text .= '<th>Started</th>';
								$text .= '<th>Started af</th>';
								$text .= '<th>Afsluttet</th>';
								$text .= '<th>Afslutted af</th>';
							$text .= '</tr>';
				$text .= '</thead>';
				$text .= '<tbody>';
					if($stmt) {
						while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							$open = "";
							if(empty($row['endedby'])) {
								$open = "info";
							}
							$text .= '<tr class="linkButton '.$open.'" onclick="xajax_show_shift_details('.$row['id'].')">';
								$text .= '<td>'.$row['started'].'</td>';
								$text .= '<td>'.$row['startedby'].'</td>';
								$text .= '<td>'.$row['ended'].'</td>';
								$text .= '<td>'.$row['endedby'].'</td>';
							$text .= '</tr>';
						}
					} else {
						
					}
				$text .= '</tbody>';
				
			$text .= '</table>';

		$text .= '</div>';
	$text .= '</div>';
	
	return $text;
}

function show_shift_details($id) {
	global $dba;
	$sql = "SELECT * FROM shifts_select WHERE id = " . $id;
	$stmt = $dba->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	$objResponse = new xajaxResponse();

	$text .= '<div class="modal-dialog">';
    	$text .= '<div class="modal-content">';
      		$text .= '<div class="modal-header">';
        		$text .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        		$text .= '<h4 class="modal-title">Vagt detaljer</h4>';
      		$text .= '</div>';
      		$text .= '<div class="modal-body">';
        		$text .= '<div class="panel panel-default">';
        			$text .= '<table class="table">';
        				$text .= '<tr>';
        					$text .= '<td><strong>Started:</strong></td>';
        					$text .= '<td>'.$row['started'].'</td>';
        				$text .= '</tr>';
        				$text .= '<tr>';
        					$text .= '<td><strong>Started af:</strong></td>';
        					$text .= '<td>'.$row['startedby'].'</td>';
        				$text .= '</tr>';
        				$text .= '<tr>';
        					$text .= '<td><strong>Afslutted</strong></td>';
        					$text .= '<td>'.$row['ended'].'</td>';
        				$text .= '</tr>';
        				$text .= '<tr>';
        					$text .= '<td><strong>Afslutted af:</strong></td>';
        					$text .= '<td>'.$row['endedby'].'</td>';
        				$text .= '</tr>';
        				$text .= '<tr>';
        					$text .= '<td><strong>Igang:</strong></td>';
        					if(empty($row['endedby'])) {
        						$text .= '<td>Ja</td>';
        					} else {
        						$text .= '<td>Nej</td>';
        					}
        				$text .= '</tr>';
        				$text .= '<tr>';
        					$text .= '<td><strong>Indskrevede beboere:</strong></td>';
        					$text .= '<td></td>';
        				$text .= '</tr>';
        				$text .= '<tr>';
        					$text .= '<td><strong>Indskrevede gæster:</strong></td>';
        					$text .= '<td></td>';
        				$text .= '</tr>';
        			$text .= '</table>';
        		$text .= '</div>';
      		$text .= '</div>';
      		$text .= '<div class="modal-footer">';
      			$text .= '<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Luk</button>';
      		$text .= '</div>';
    	$text .= '</div><!-- /.modal-content -->';
	$text .= '</div><!-- /.modal-dialog -->';

	$objResponse->assign("modal", "innerHTML", $text);
	$objResponse->script("$('#modal').modal('show');");

	return $objResponse;
}

?>