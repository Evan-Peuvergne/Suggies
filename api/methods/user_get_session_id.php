<?php

	require_once DIR_API.'/tools/tools.tmdb.php';
	
	function user_get_session_id($approved_token){
		return tmdb_get_session_id();
	}