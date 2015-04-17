<?php
	require_once DIR_API.'/tools/tools.crud_shows.php';

	function show_get_similar($id){
		return get_similar_shows($id);
	}