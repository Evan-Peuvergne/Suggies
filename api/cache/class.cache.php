<?php
class Cache {

	public $dirname; 
	public $duration; // durée de vie EN MINUTES

	public function __construct($dirname, $duration){
		$this->dirname = $dirname;      
		$this->duration = $duration;    
	}

	public function write($filename, $content){  //write a new cache file
		return file_put_contents($this->dirname.'/'.$filename, $content);
	}

	public function read($filename){   //reads a file, return false if no file or file outdated
		$file = $this->dirname.'/'.$filename;
		if (!file_exists($file)) {
			return false;
		}
		$lifetime = (time() - filemtime($file)) / 60 ;
		if ($lifetime > 60){
			return false;
		}
		return file_get_contents($file);
	}

	public function delete($filename){		//delete a given file
		$file = $this->$dirname.'/'.$filename;
		if (file_exists($file)){
			unlink($file);			
		}
	}

	public function clear(){			// CLEAR EVERYTHINGGGG
		$files = glob($this->dirname, '.*');
		foreach( $files as $file ){
			unlink($file);
		}
	}

	public function inc($file){			// save content of a script in a file
		$filename = basename($file);
		if ($content = $this->read(filename)){
			return false;
		}
		ob_start();
		require $file;
		$content = ob_set_clear();
		$this->write($filename, $content);
		return true;
	}
}