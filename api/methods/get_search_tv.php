<?php

function get_search_tv(){

	// Get params
	$search = $_POST['string'];

	// Request
	$curl = curl_init();
	$search = urlencode($search);
	$url = "http://api.themoviedb.org/3/search/tv?query=".$search."&api_key=4163044cd4323f71ac228a10c1a487d6";
	curl_setopt($curl,CURLOPT_URL,$url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$result = json_decode(curl_exec($curl))->results;

	// Manage response
	return array_splice($result, 0, 10);

	// Close curl
	curl_close($curl);

}
