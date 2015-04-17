<?php

	function API_error_handler($errno, $errstr, $errfile, $errline){
		if (!(error_reporting() & $errno)) {
			//this error code is weird or non existent
			return;
		}

		switch($errno){
		case E_USER_ERROR:
			echo "ERREUR : [$errno] $errstr <br />\n";
			echo "Erreur fatale ligne $errline dans le fichier $errfile";
			echo ", PHP ". PHP_VERSION . " (" . PHP_OS . ")<br />\n";
			echo "Arrêt du script. <br />\n";
			exit(1);
			break;
		case E_USER_WARNING:
			echo "ALERTE : [$errno] $errstr <br />\n";
			break;

		case E_USER_NOTICE:
			echo " Avertissement [$errno] $errstr";
			break;

		default:
			echo "Type d'erreur inconnu : [$errno] $errstr <br />\n";
		}

		//empêche l'éxecution du gestionnaire itnerne de PHP
		return true;
	}

	//Fonction pour tester la gestion d'erreur
	// function scale_by_log($vect, $scale){
	// 	if (!is_numeric($scale) || $scale <= 0) {
	// 		trigger_error("log(x) for x <= 0 is undefined, you used : scale = $scale", E_USER_ERROR);
	// 	}

	// 	if (!is_array($vect)) {
	// 		trigger_error("Type d'entrée incorrect, tableau de valeurs attendu", E_USER_WARNING);
	// 		return null;
	// 	}

	// 	$temp = array();
	// 	foreach($vect as $pos => $value) {
	// 		if (!is_numeric($value)) {
	// 			trigger_error("La valeur à la proposition $pos n'est pas un nombre, utilisation 0 (zero)", E_USER_NOTICE);
	// 			$value = 0;
	// 		}
	// 		$temp[$pos] = log($scale) * $value;
	// 	}
	// 	return $temp;
	// }

	//configuration du gestionnaire d'erreurs
	//$old_error_handler = set_error_handler("API_error_handler");



