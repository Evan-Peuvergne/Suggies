<?php


	/* HEADERS */

	header('Content-Type: application/json');
	header("Access-Control-Allow-Origin: *");
	
	/* SHOW ERRORS */

	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	/* PATH */
	
	define ('ROOT', 	dirname(__FILE__));
	define ('DIR_API',  dirname(__FILE__));

