<?php
	
	require_once 'config.php';
	require 'render.php';

	$routes = Array(
		'/shows\/\d+$/'	 		    => 'show_get_details',
		'/shows\/\d+\/similar\/?$/' => 'show_get_similar',
		'/shows\/\d+\/actors\/?$/'	=> 'show_get_actors',
		'/shows\/\d+\/videos\/?$/'	=> 'show_get_videos',
		'/shows\/popular\/?$/'      => 'shows_get_popular',
		'/shows\/worst\/?$/'		=> 'shows_get_worst',
		'/search\/?(.*)$/'			=> 'shows_search',
		'/user\/newtoken\/?$/'		=> 'user_get_new_token',
		'/user\/sessionid\/?$/'		=> 'user_get_session_id',
		'/user\/userinfo\/?$/'		=> 'user_get_info',
		'/user\/session\/?$/'		=> 'user_get_session',
		'/user\/watchlist\/?$/'		=> 'post_watch_list',		
	);


	//get uri
	$uri = $_SERVER['REQUEST_URI'];

	//extract correct part
	preg_match("/api\/(.*)/", $uri, $matches);
	$uri = $matches[1];
	
	//detect route
	foreach ($routes as $key => $value){

		if ( preg_match($key, $uri) ){
			include "methods/".$value.".php";
			preg_match("/\d+/", $uri, $id);
			preg_match("/search\/?(.*)$/", $uri, $search);
					
			if ( count($id) > 0 ){
				render(200, call_user_func($value, intval($id[0])));				
			} else if (count ($search) > 0){
				render(200, call_user_func($value, $search[1]));
			} else {
				render(200, call_user_func($value));				
			}
		}
	}
	render(404, array('error' => "this route does not exist"));
		

