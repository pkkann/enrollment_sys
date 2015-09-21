<?php

function load_nav($changePage = 0) {
	$objResponse = new xajaxResponse();
	if($changePage != 0) {
		$_SESSION['curPage'] = $changePage;
	}
	$text .= gen_nav();
	$objResponse->assign("top_nav", "innerHTML", $text);
	return $objResponse;
}

function gen_nav() {
	$r = "";
	$g = "";
	$b = "";
	$s = "";
	switch ($_SESSION['curPage']) {
		case "1":
			$r = "active";
			break;
		case "2":
			$g = "active";
			break;
		case "3":
			$b = "active";
			break;
		case "4":
			$s = "active";
			break;
	}

	$text = '';
	
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
				$text .= '<li class="'.$r.'"><a class="linkButton" onclick="xajax_load_residents()"><i class="fa fa-male"></i><i class="fa fa-female"></i> Beboere</a></li>';
				$text .= '<li class="'.$g.'"><a class="linkButton" onclick="xajax_load_guests()"><i class="fa fa-user-secret"></i> Gæster</a></li>';
				if($_SESSION['user']['admin']) {
					$text .= '<li class="'.$b.'"><a class="linkButton" onclick="xajax_load_users()"><i class="fa fa-users"></i> Brugere</a></li>';
					$text .= '<li class="'.$s.'"><a class="linkButton" onclick="xajax_load_shifts()"><i class="fa fa-list"></i> Vagter</a></li>';
				}
			$text .= '</ul>';
			$text .= '<ul class="nav navbar-nav navbar-right">';
				if($_SESSION['shift']['id'] == 0) {
					$text .= '<li><a class="linkButton" onclick="xajax_show_new_shift()"><i class="fa fa-square-o"></i> Ny vagt</a></li>';
				} else {
					$text .= '<li><a class="linkButton" onclick="xajax_show_close_shift()"><i class="fa fa-check-square-o"></i> Afslut vagt</a></li>';
				}
				
				$text .= '<li class="linkButton '.$p.'"><a class="linkButton" onclick="xajax_load_profile()"><i class="fa fa-user"></i> '.$_SESSION['user']['name'].'</a></li>';
				$text .= '<li><a class="linkButton" onclick="xajax_logout()"><i class="fa fa-sign-out"></i> Log af</a></li>';
			$text .= '</ul>';
		$text .= '</div>';
	$text .= '</div>';
	
	return $text;
}

?>