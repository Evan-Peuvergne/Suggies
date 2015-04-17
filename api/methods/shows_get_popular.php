<?php

	require_once DIR_API.'/tools/tools.tmdb.php';

	function shows_get_popular(){
		return tmdb_get_popular_series();
	}
