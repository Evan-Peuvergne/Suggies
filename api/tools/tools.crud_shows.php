<?php

require_once DIR_API.'/tools/tools.tmdb.php';

$time = microtime(TRUE);

/*
 * construit le fichier cache d'une série
 *
 * get id from show title
 * get details from show
 * get similar ponderate
 * encode
 * save
 * 
 * repeat
 * 

 */
/**
 * 					TODO
 */
class CacheShow {
	public $showname;
	private $data;

	public function __construct($showname){
		$this->showname = $showname;
	}

	//set id from name
	public function set_id(){
		$this->showid = tmdb_get_id($this->showname);
	}

	//set full data about the show
	public function set_data(){
		$this->data = tmdb_get_data($this->showname);
		$this->showname = $this->data->name;
		$this->showid = $this->data->id;
	
	}

	//set meta array for show
	//need $this->data
	public function set_meta(){
		$this->meta = Array();
		$this->meta['poster'] = "http://image.tmdb.org/t/p/w500".$this->data->poster_path;
		$this->meta["vote_avg"] = $this->data->vote_average;
		$this->meta["popularity"] = $this->data->popularity;
		$this->meta["genre"] = $this->data->genres[0]->name;
	}

	//set keywords array for the show
	//need $this->data
	public function set_keywords(){
		$this->keywords = tmdb_get_keywords($this->showid);
	}

	public function set_sections(){
		$this->similar["sections"]=Array();

		$this->similar["sections"]["genre"]=Array();
		$this->similar["sections"]["origin"]=Array();
		$this->similar["sections"]["eps_length"]=Array();

		foreach ($this->similar["shows"] as $show){
			if (!array_key_exists($show["genre"], $this->similar["sections"]["genre"])){
				$this->similar["sections"]["genre"][$show["genre"]] = 0;
			}			
			$this->similar["sections"]["genre"][$show["genre"]]++;

			$origin = $show["origin"];
			if ( !is_string($origin) ){
				if ( $origin === null){
					$origin = "NC";
				} else if ( (array) $origin === $origin){
					if ($origin[0] != null){
						$origin = $origin[0];
					} else {
						$origin = "null";
					}
				} 
			} 
			if (!array_key_exists($show["origin"], $this->similar["sections"]["origin"])){
				$this->similar["sections"]["origin"][$show["origin"]] = 0;
			}
			$this->similar["sections"]["origin"][$show["origin"]]++;

			$show['eps_length'] = get_eps_length($show['id']);
			if (!array_key_exists($show["eps_length"], $this->similar["sections"]["eps_length"])){
				$this->similar["sections"]["eps_length"][$show["eps_length"]] = 0;
			}
			$this->similar["sections"]["eps_length"][$show["eps_length"]]++;
		}		
	}

	//set similars shows
	//need $this->data
	public function set_similars(){
		$similars = tmdb_get_similar($this->showid);
		//print "id : ". $this->showid." similars : ";
		//print json_encode($similars).PHP_EOL;
		$this->similar = Array();
		$this->similar["shows"] = Array();
		foreach($similars as $similar) {
			if (!isset($similar->origin_country[0])){
				$similar->origin_country[0] = "nc";
			}
			$this->similar["shows"][] = Array(
					"name" 		 => $similar->name,
					"id" 	 	 => $similar->id,
					"poster"	 => "http://image.tmdb.org/t/p/w500".$similar->poster_path,
					"note" 	     => $similar->vote_average,
					"popularity" => $similar->popularity,
					"origin" 	 => $similar->origin_country[0],
					"keywords"   => tmdb_compare_keywords( $this->keywords, tmdb_get_keywords($similar->id)),
					"genre" 	 => $this->set_get_genre($similar->name)
			);
		}

	}

	public function set_similarity(){
		//base la similarité sur le nombre de points communs;
		//si genre commun, +20 ?
		
		$max = 0;
		foreach ($this->similar["shows"] as $show){
			if ($show["keywords"]["score"] > $max) {
				$max = count($show["keywords"]["common"]);
			}		
		}
		$max = $max + 5;
		$coef = 100 / $max;
		$index = 0;
		foreach ($this->similar["shows"] as $show){
			$score = $show["keywords"]["score"];
			if ( $this->similar["shows"][$index]["genre"] == $this->get_genre() ){
				$score = $score + 5;
			}
			$this->similar["shows"][$index]["similarity"] = Array();			
			$similarity = $score * $coef;
			$this->similar["shows"][$index]["similarity"] =  $similarity;					
		$index++;
		}				
	}

	public function set_graph_cache_file(){
		//$this->set_name_and_id();
		$this->set_data();
		$this->set_meta();
		$this->set_keywords();
		//print json_encode($this->keywords).PHP_EOL;
		$this->set_similars();
		$this->set_sections();
		$this->set_similarity();

		$this->cache_file = Array(
			"name"     => $this->showname,
			"id"       => $this->showid,
			"meta"     => $this->meta,
			"keywords" => $this->keywords,
			"similar"  => $this->similar
		);
	}

	public function set_detailled_cache_file(){
		$this->set_data();
	}

	public function set_get_genre($showname){
		$ShowDetails = new CacheShow($showname);	
		$Cache = new Cache(ROOT.'/cache/details', 60*24*7); //1 week long
		$ShowDetails->get_detailled_cache_file($Cache);
		return $ShowDetails->get_genre();
	}

	public function get_graph_cache_file($Cache){
		if ( !is_int($this->showname) ){
			$this->set_data();
		}
		if ( !$Cache->read(strval($this->showid)) ) {
			//echo "no cache";
			$this->set_graph_cache_file();
			$Cache->write(strval($this->showid), json_encode($this->cache_file));
		}
		$this->cache_file = json_decode($Cache->read(strval($this->showid)));
		return $this->cache_file;
	}

	public function get_detailled_cache_file($Cache){
		if ( !is_int($this->showname) ){
			$this->set_data();
		}
		if ( !$Cache->read(strval($this->showid)) ) {
			//echo "no cache";
			$this->set_detailled_cache_file();
			$Cache->write(strval($this->showid), json_encode($this->data));
		}
		$this->cache_file = json_decode($Cache->read(strval($this->showid)));
		//print $Cache->read(strval($this->showid)).PHP_EOL;
		return $this->cache_file;
	}

	public function get_id(){
		return $this->showid;
	}

	public function get_genre(){
		return $this->data->genres[0]->name;
	}

}


function get_similar_shows($show_id){
	$ShowGraph = new CacheShow($show_id);	
	$Cache = new Cache(ROOT.'/cache/graph', 60*24*7); //1 week long
	$ShowGraph->showid = $show_id;
	$ShowGraph = $ShowGraph->get_graph_cache_file($Cache);
	
	return $ShowGraph;
}

function get_show_details($show_id){

	$ShowDetails = new CacheShow($show_id);	
	$Cache = new Cache(ROOT.'/cache/details', 60*24*7); //1 week long
	$ShowDetails->showid = $show_id;
	$ShowDetails->get_detailled_cache_file($Cache);
	
	return $ShowDetails->cache_file;
}

function get_eps_length($show_id){
	
	$ShowDetails = new CacheShow($show_id);	
	$Cache = new Cache(ROOT.'/cache/details', 60*24*7); //1 week long
	$ShowDetails->showid = $show_id;
	$ShowDetails->get_detailled_cache_file($Cache);
	//var_dump($ShowLength = $ShowDetails->cache_file->episode_run_time);
	//die();
	if (!isset($ShowDetails->cache_file->episode_run_time[0])){
		$ShowDetails->cache_file->episode_run_time[0] = 30;
	}
	$ShowLength = $ShowDetails->cache_file->episode_run_time[0];
	return strval($ShowLength);
}



$foo = Array(
"Game of Thrones",
"The Big Bang Theory",
"Marvel's Daredevil",
"Law & Order: Special Victims Unit",
"Grey's Anatomy",
"The Walking Dead",
"The Flash",
"Family Guy",
"Doctor Who",
"CSI: Crime Scene Investigation",
"The Vampire Diaries",
"Pretty Little Liars",
"Once Upon a Time",
"Outlander",
"Supernatural",
"The Messengers",
"Breaking Bad",
"Arrow",
"12 Monkeys",
"Revenge",
"The Simpsons",
"Vikings",
"NCIS",
"Bones",
"Salem",
"Gotham",
"Castle",
"American Odyssey",
"American Dad!",
"The Lizzie Borden Chronicles",
"Poldark",
"Criminal Minds",
"Marvel's Agents of S.H.I.E.L.D.",
"Empire",
"The Good Wife",
"Mad Men",
"Keeping Up with the Kardashians",
"The Originals",
"A.D. The Bible Continues",
"Grimm",
 "The Mentalist",
"The Blacklist",
"Better Call Saul",
"The Last Man on Earth",
"Smallville",
"Person of Interest",
"iZombie",
"The 100",
"Hawaii Five-0",
"American Horror Story",
"Secrets and Lies",
"Silicon Valley",
"Sons of Anarchy",
"Cedric's Barber Battle",
"Bitten",
"Thunderbirds Are Go!",
"Elementary",
"The Comedians",
"House of Cards",
"Blue Bloods",
"House",
"The Following",
"How I Met Your Mother",
"Veep",
"Scorpion",
"Bates Motel",
"2 Broke Girls",
"Battle Creek",
"Two and a Half Men",
"Community",
"Jane the Virgin",
"Modern Family",
"My Little Pony: Friendship Is Magic",
"Dexter",
"Fringe",
"The Royals",
"Sherlock",
"Beverly Hills 90210",
"Nurse Jackie",
"Top Gear"
);
$fooo = 0;


// function faa($arr, $fifoo){
// 	echo $arr[$fifoo].PHP_EOL;
// 	$Show = new CacheShow($arr[$fifoo]);	
// 	$Cache = new Cache(ROOT.'/cache/tmpp', 60*24); //1 week long
	
// 	$Show->get_graph_cache_file($Cache);

// 	if ($fifoo < count($arr)){
// 		faa($arr, $fifoo+1);		
// 	}
// }
// faa($foo, 0);


// function go($showname){
// 	$ShowGraph = new CacheShow($showname);	
// 	$Cache = new Cache(ROOT.'/cache/graph', 1); //1 week long

// 	return $ShowGraph->get_graph_cache_file($Cache);
// }

//echo "<br>temps de chargement : ".round(microtime(TRUE) - $time,3)." secondes ";


		