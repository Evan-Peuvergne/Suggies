<?php
	require_once DIR_API.'/tools/tools.crud_shows.php';

	function return_JSON($show_id){
		return get_show_details($show_id);
	}