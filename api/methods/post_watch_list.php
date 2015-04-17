<?php

function post_watch_list ($user_id, $tv_id, $sess_id) {

			
	$ch = curl_init();

	$url = "http://api.themoviedb.org/3/account/".$user_id."/watchlist?api_key=4163044cd4323f71ac228a10c1a487d6&session_id=".$sess_id;
			
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);

	curl_setopt($ch, CURLOPT_POST, TRUE);

	curl_setopt($ch, CURLOPT_POSTFIELDS, "{
	  \"media_type\": \"tv\",
	  \"media_id\": ".$tv_id.",
	  \"watchlist\": true
	}");

	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	  "Accept: application/json",
	  "Content-Type: application/json"
	));

	$response = curl_exec($ch);
	curl_close($ch);

	//var_dump($response);

}