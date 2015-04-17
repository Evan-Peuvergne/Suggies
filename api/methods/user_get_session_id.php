<?php

	require_once '../tools/tools.tmdb.php';
	
	if($_POST['token']){
		return json_encode(tmdb_get_session_id($_POST['token']));
			
	}

	function user_get_session_id($approved_token){
		return tmdb_get_session_id($approved_token);
	}