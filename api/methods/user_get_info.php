<?php

	require_once DIR_API.'/tools/tools.tmdb.php';

	function user_get_info($user_si){
		return tmdb_user_get_info($user_si);
	}
