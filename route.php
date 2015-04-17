<?php

class Route{ 

	private $_uri = array();

	/**
	 * adds a route
	 * @param [str] $uri [description]
	 */
	public function add($uri){
		$this->_uri[] = trim($uri, '/');
	}

	public function submit(){
		// 'uri' comes from .htaccess rule
		// if is set $_GET['uri'] add it, else add root
		$uriGetParam = isset($_GET['uri']) ? $_GET['uri'] : '/';			
		
		foreach ($this->_uri as $key => $value){
			if ( preg_match("#^$value$#", $uriGetParam)) {
				echo "match !";
			} else {

			}
		}
	}
}
