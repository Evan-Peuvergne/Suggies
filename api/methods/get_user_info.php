<?php

function get_user_info ($user_si) {
	$curl = curl_init();


	$url="http://api.themoviedb.org/3/account?api_key=4163044cd4323f71ac228a10c1a487d6&session_id=".$user_si;

	curl_setopt($curl,CURLOPT_URL,$url);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);

	$result = json_decode(curl_exec($curl));
	curl_close($curl);


	return $result;			

}
