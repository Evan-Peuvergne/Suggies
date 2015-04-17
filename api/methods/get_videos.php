<?php


	function get_videos ($tv_id)
	{

		// curl initiation
		$curl = curl_init();

		$url = "http://api.themoviedb.org/3/tv/".$tv_id."/videos?api_key=4163044cd4323f71ac228a10c1a487d6";
		curl_setopt($curl,CURLOPT_URL,$url);
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		
		$result = json_decode(curl_exec($curl));
		curl_close($curl);

		$return = $result->results["0"]->key;
	
	    //confirmation            
		return $return;

		
	}

/*Mettre la key renvoyée par la fonction à la suite de l'url suivante : "http://youtube.com/embed/" */


get_videos ();


