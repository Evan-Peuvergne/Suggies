<?php

	require_once DIR_API.'/tools/tools.tmdb.php';

	function shows_search($search){
		return tmdb_get_search_tv($search);
	}