<?php
	require_once DIR_API.'/tools/tools.crud_shows.php';

	function return_JSON($show_id){
		return get_similar_shows($show_id);
	}