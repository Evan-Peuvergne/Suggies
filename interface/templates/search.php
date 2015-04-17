<?php

	
	/* REQUEST DATA */

	
	// Config curl
	$curl = curl_init();
	$url = "http://api.themoviedb.org/3/tv/popular?api_key=4163044cd4323f71ac228a10c1a487d6";
	curl_setopt($curl,CURLOPT_URL,$url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
	// Filter results
	$shows = array_slice(json_decode(curl_exec($curl))->results, 0, 10);
	// Close curl	
	curl_close($curl);

?>


<div class="module module-search">
	<div class="container">

		<form>
			<input class="loading" type="text" placeholder="Votre série">
			<div class="loader"></div>
		</form>
		
		<ul>

			<?php foreach($shows as $show): ?>

				<li data-id="<?php echo $show->id; ?>" data-name="<?php echo $show->name; ?>" data-poster="<?php echo $show->poster_path; ?>">
					<img src="http://image.tmdb.org/t/p/w500<?php echo $show->poster_path; ?>" alt="">
				</li>

			<?php endforeach; ?>

		</ul>

	</div>
</div>