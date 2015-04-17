<?php

	require_once DIR_API.'/tools/tools.tmdb.php';

	function show_get_videos($id){
		return tmdb_get_videos($id);
	}
/*Mettre la key renvoyée par la fonction à la suite de l'url suivante : "http://youtube.com/embed/" */


