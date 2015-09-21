<?php

function show_alert($type = "", $title = "", $message = "") {
	$objResponse = new xajaxResponse();
	$color_type = "";
	$color_icon = "";
	switch ($type) {
		case 'success':
			$color_type = "success";
			$color_icon = "fa fa-check-circle";
			break;
		case 'info':
			$color_type = "info";
			$color_icon = "fa fa-info-circle";
			break;
		case 'warning':
			$color_type = "warning";
			$color_icon = "fa fa-exclamation-triangle";
			break;
		case 'danger':
			$color_type = "danger";
			$color_icon = "fa fa-exclamation-triangle";
			break;
		default:
			$color_type = "success";
			$color_icon = "fa fa-check-circle";
			break;
	}
	
	$command = "$.notify({icon: '".$color_icon."',";
	if(!empty($title)) {
		$command .= "title: '<strong>".$title."</strong>'";
	}
	if(!empty($title) && !empty($message)) {
		$command .= ",";
	}
	if(!empty($message)) {
		$command .= "message: '".$message."'";
	}

	$command .= "},{type: '".$color_type."',placement: {from: \"bottom\",align: \"right\"},animate: {enter: 'animated fadeIn',exit: 'animated zoomOutRight'},offset:70,spacing:0,delay:500});";

	$objResponse->script($command);
	return $objResponse;
}

?>