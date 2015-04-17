<?php

function get_session_id ($approved_token) {
	$curl = curl_init();

	$url="http://api.themoviedb.org/3/authentication/session/new?api_key=4163044cd4323f71ac228a10c1a487d6&request_token=".$approved_token;
	curl_setopt($curl,CURLOPT_URL,$url);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);

	$result = json_decode(curl_exec($curl));
	curl_close($curl);

	return $result;
}