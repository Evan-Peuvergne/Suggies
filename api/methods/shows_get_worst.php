<?php
	require_once DIR_API.'/tools/tools.tmdb.php';

	
	function shows_get_worst(){
		return tmdb_get_worst_series();
	}