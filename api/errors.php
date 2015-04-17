<?php
/*
	Work In Progress
	Gérer les erreurs en plus du gestionnaire internet
 */

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

	//configuration du gestionnaire d'erreurs
	//$old_error_handler = set_error_handler("API_error_handler");



