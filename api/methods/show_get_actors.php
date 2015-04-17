<?php

		require_once DIR_API.'/tools/tools.tmdb.php';

	function show_get_actors($id){
		return tmdb_get_actors($id);
	}