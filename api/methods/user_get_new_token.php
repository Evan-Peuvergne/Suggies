<?php

	require_once DIR_API.'/tools/tools.tmdb.php';

	function user_get_new_token(){
		return tmdb_get_new_token();
	}


