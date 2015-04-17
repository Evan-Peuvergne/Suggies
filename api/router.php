<?php
	
	require_once 'config.php';
	require 'render.php';

	$routes = Array(
		'/shows\/\d+$/'	 		    => 'get_show_details',
		'/shows\/\d+\/similar\/?$/' => 'get_similar_shows',
		'/popular\/?$/'         	=> 'get_popular_series',
		'/worst\/?$/'				=> 'get_worst_series',
		'/search\/?$/'				=> 'get_search_tv',
		'/newtoken\/?$/'			=> 'get_new_token',
		'/sessionid\/?$/'			=> 'get_session_id',
		'/userinfo\/?$/'			=> 'get_user_info',
		'/videos\/?$/'				=> 'get_videos',
		'/session\/?$/'				=> 'get_session',
		'/actors\/?$/'				=> 'get_actors',
		'/watchlist\/?$/'			=> 'post_watch_list'		

	);


	//get uri
	$uri = $_SERVER['REQUEST_URI'];

	//extract correct part
	preg_match("/api\/(.*)/", $uri, $matches);
	$uri = $matches[1];

	
	//detect route
	foreach ($routes as $key => $value){

		// echo "<br> key : ".$key;
		// echo "<br> value : ".$value;

		if ( preg_match($key, $uri) ){
			//echo "<br>preg match uri : ".$uri."<br><br>";
			include "methods/".$value.".php";
			preg_match("/\d+/", $uri, $id);
					
			if ( count($id) > 0 ){
				render(200, call_user_func($value, intval($id[0])));				
			} else {
				render(200, call_user_func($value));				
			}
		}
	}
	render(404, array('error' => "this route does not exist"));
		

