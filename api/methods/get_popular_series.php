<?php


	function get_popular_series ()
	{

		// curl initiation
		$curl = curl_init();
		$url = "http://api.themoviedb.org/3/tv/popular?api_key=4163044cd4323f71ac228a10c1a487d6";

		curl_setopt($curl,CURLOPT_URL,$url);
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);

		$result = json_decode(curl_exec($curl));
		curl_close($curl);

		//recuperation of only the first 10 elements of the array without the others informations
		$return = array_slice($result->results,0,10);

	    //confirmation            
		return $return;

	}
