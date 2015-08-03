<?php

function load_nav($changePage) {
	$objResponse = new xajaxResponse();
	$_SESSION['curPage'] = $changePage;
	$text .= gen_nav();
	$objResponse->assign("top_wrapper", "innerHTML", $text);
	return $objResponse;
}

function gen_nav() {
	$r = "";
	$g = "";
	switch ($_SESSION['curPage']) {
		case "1":
			$r = "active";
			break;
		case "2":
			$g = "active";
			break;
	}

	$text = '';
	$text .= '<nav class="navbar navbar-inverse navbar-fixed-top">';
		$text .= '<div class="container-fluid">';
			$text .= '<div class="navbar-header">';
				$text .= '<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">';
        			$text .= '<span class="sr-only">Toggle navigation</span>';
        			$text .= '<span class="icon-bar"></span>';
        			$text .= '<span class="icon-bar"></span>';
        			$text .= '<span class="icon-bar"></span>';
        		$text .= '</button>';
        		$text .= '<span class="navbar-brand"><img style="max-height: 35px; margin-top: -7px" alt="Brand" src="res/logo.png" /></span>';
			$text .= '</div>';

			$text .= '<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">';
				$text .= '<ul class="nav navbar-nav">';
					$text .= '<li class="'.$r.'"><a class="linkButton" onclick="xajax_load_residents()">Beboere</a></li>';
					$text .= '<li class="'.$g.'"><a class="linkButton" onclick="xajax_load_guests()">Gæster</a></li>';
				$text .= '</ul>';
				$text .= '<ul class="nav navbar-nav navbar-right">';
					$text .= '<li><a class="linkButton" onclick="xajax_logout()">Log af</a></li>';
				$text .= '</ul>';
			$text .= '</div>';
		$text .= '</div>';
	$text .= '</nav>';
	return $text;
}

?>