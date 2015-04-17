<?php
require_once DIR_API.'/config.php';
require_once DIR_API.'/cache/class.cache.php';

/**
 * Is used for every request to tmdb API
 * Use APC cache to be sure the script do not reach API limitation 
 * cURL the API
 * @param  str $url API's url to call
 * @return json     API call's result
 */
function curl_tmdb($url){
    if (!function_exists('apc_exists')) {
        function apc_exists($key) { return (boolean)apc_fetch($key); }
    }
	if ( apc_exists('compteur') ){
		$compteur = apc_fetch('compteur');
		if (count($compteur)>29){							//if max requests is reached:
			$time_end = microtime(true);					//check time since first req
			$time = $time_end - $compteur[0];				// in cache
			$time = 10 - $time;
			array_shift($compteur);
			if ($time > 0 && $time < 10) sleep( $time );	//sleep if needed
			else if ($time < 0) {
				while ($time < 0 && count($compteur)>1){	//if possible, clear cache
					array_shift($compteur);
					$time_end = microtime(true);
					$time = $time_end - $compteur[0];
					$time = 10 - $time;
				}
			}
			else if ($time > 10) sleep(10);
		} else {
			$compteur[] = time();
		}
		apc_store('compteur', $compteur);
 	} else {												//if cache is not set
 		$compteur = Array(microtime(true));
 		$apcc = apc_store(Array('compteur' => $compteur));
 	}

	$curl = curl_init();
	$url = $url."&api_key=4163044cd4323f71ac228a10c1a487d6";
	curl_setopt_array($curl, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => $url,
		CURLOPT_USERAGENT => "Yo I'm SUGGIES"
		)
	);

	$resp = curl_exec($curl);
			
	if($resp === false)								//error handling is important
	{
	    echo 'error:' . curl_error($curl);
	    exit();
	}
	else if ( isset(json_decode($resp)->status_code) && json_decode($resp)->status_code!= 1 ) {
		echo 'error:' . json_decode($resp)->status_message;
		return false;
	}
	else {
		curl_close($curl);
		return $resp;
	}
	curl_close($curl);
}


/**
 * returns basic information about a show 
 * ( name, id, origin, popularity, note, two images, first air date )
 * http://docs.themoviedb.apiary.io/#reference/search/searchtv/get
 * @param  str $show 	 a show title
 * @return obj  		 content of API response   
 */
function tmdb_get_show($show){
	$url = "http://api.themoviedb.org/3/search/tv?query=".urlencode($show);
	$res = curl_tmdb($url);
	//get the exact match
	$res = json_decode($res);
	if (!empty($res->results)){
		$res = $res->results[0];
		return $res;		
	}
	trigger_error("pas de résultat pour ".$show, E_USER_NOTICE);
}

/**
 * returns a show id from a show title
 * @param  str $showname a show title
 * @return int           a show id
 */
function tmdb_get_id($showname){
	return tmdb_get_show($showname)->id;
}

/**
 * returns information about a show from a title or an id
 * (basic info + info about seasons, synopsys, production, casting, languages, dates,...)
 * http://docs.themoviedb.apiary.io/#reference/tv/tvid/get
 * @param  str || int $showname_or_id a title or an id
 * @return obj             		      information about a show
 */
function tmdb_get_data($showname_or_id){
	if (!is_int($showname_or_id)){
		$ress = tmdb_get_show($showname_or_id);
		$id = $ress->id;
	} else {
		$id = $showname_or_id;
	} 

	$url = "http://api.themoviedb.org/3/tv/".$id."?";
	$res = curl_tmdb($url);

	return json_decode($res);
}


/**
 * get keywords of a show from its id
 * http://docs.themoviedb.apiary.io/#reference/tv/tvidkeywords/get
 * @param  int $id a show id
 * @return array   array filled with keywords
 */
function tmdb_get_keywords($id){
	$url = "http://api.themoviedb.org/3/tv/".$id."/keywords?";
	$keywords = curl_tmdb($url);
	//get the exact match
	$keywords = json_decode($keywords)->results;
	
	$res = Array();
	foreach($keywords as $keyword){
		$res[] = $keyword->name;
	}
	
	if (count($res) < 1){
		trigger_error("pas de keywords pour ".$id, E_USER_NOTICE);
	}

	return $res;
}

/**
 * compares two shows keywords to find commons keywords and format
 * 'similar show' keywords
 * @param  arr $showkw         keywords of show 1
 * @param  arr $showkw_bis     keywords of show 2
 * @return arr                 formatted keywords of show 2
 */
function tmdb_compare_keywords($showkw, $showkw_bis){

	$res = Array();
	$res["common"] = Array();
	$res["all"] = Array();
	//
	foreach($showkw as $keyword){
		foreach($showkw_bis as $keyword_bis){
			if( $keyword == $keyword_bis ){
				$res["common"][] = $keyword_bis;
			}
		}
	}


	foreach ($showkw_bis as $keyword_bis) {
		$res["all"][] = $keyword_bis;
	}

	$res["score"] = count($res["common"]);

	return $res;
}

/**
 * get similar shows to a show1 from show1 id
 * @param  int $id id of a show (show1)
 * @return obj      list of similar shows and basic information
 */
function tmdb_get_similar($id){
	$url = "http://api.themoviedb.org/3/tv/".$id."/similar?";
	$res = curl_tmdb($url);
	$res = json_decode($res);

	if (count($res->results)<1){
		trigger_error("pas de similaires pour ".$id, E_USER_NOTICE);
	}
	return $res->results;
}

/**
 * get other ids saved in tmdb DB (imdb, tvrage, freebase, tvdb)
 * http://docs.themoviedb.apiary.io/#reference/tv/tvidexternalids/get
 * @param  int $id    id of a show 
 * @return obj        list of ids
 */
function tmdb_get_external_ids($id){
	$url = "http://api.themoviedb.org/3/tv/".$id."/external_ids?";
	$res = curl_tmdb($url);

	return json_decode($res);
}

/**
 * get movies by a certain genre
 * @param  str $genre 	name of genre
 * @return obj     		list of shows
 */
function tmdb_get_by_genre($genre){
	$url = "http://api.themoviedb.org/3/discover/movie?&with_genres".$genre;
	$res = curl_tmdb($url);

	return json_decode($res);
}
