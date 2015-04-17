<?php

	echo '<pre>';
	print_r($_GET);
	echo '</pre>';

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Suggies</title>
	<!-- <link rel="stylesheet" href="interface/assets/styles/css/app.css"> -->
	<style>

		html, body{ width:100%; height:100%; overflow:hidden;} 
		body{ background-color:#fbfcfc; font-family:"Lato"; color:#272727; font-size:12px; font-weight:400; }
		.main-loader{ width:100%; height: 100%; display: -webkit-box; display: -ms-flexbox; display: -webkit-flex; display:flex; display:flex; -webkit-box-direction:column; -webkit-flex-direction: column; -ms-flex-direction: column; flex-direction:column; -webkit-justify-content:center; -ms-justify-content:center; justify-content:center; -webkit-align-items:center; -ms-align-items:center; align-items:center; }
		.main-loader h1{ font-size:3rem; font-family:"Futura STD"; font-weight:700; text-transform: uppercase; margin:0; margin-bottom:1rem; }
		.main-loader h2{ font-size:1.2rem; font-weight:400; text-transform: uppercase; margin:0; margin-bottom:3rem; }
		.main-loader span{ color:#80cdc7; margin-bottom:1rem; text-transform: uppercase; }
		/*.main-loader svg circle { fill:#80cdc7; }*/

	</style>

</head>
<body>

	

	<div class="main-loader">
		<h1><img width="300" src="interface/assets/medias/images/logo4.svg" alt=""></h1>
		<h2>Lorem ipsum dolor est verti.</h2>
		<span>Chargement ...</span>
		<img src="interface/assets/medias/images/loader.svg" alt="">
	</div>
	
</body>
</html>

<script src="interface/js/vendors/jquery.js"></script>
<script>
	
	$(document).ready(function ()
	{

		
		/* ASSETS */

		
		// CSS

		var css = [
			"interface/assets/styles/css/app.css",
		];


		// SCRIPTS

		var js = [
			"interface/js/vendors/mustache.js",
			"interface/js/modules/Search.js",
			"interface/js/modules/Poster.js",
			"interface/js/modules/Show.js",
			"interface/js/modules/Aside.js",
			"interface/js/modules/Graph.js",
			"interface/js/scripts/app.js"
		];



		/* LOAD CSS and Scripts */

		// Load CSS
		loadCSS(css, 0, function ()
		{
			
			loadJS(js, 0);

		});


	});


	/* LOAD CSS */

	function loadCSS (css, i, callback)
	{
		$.ajax({
			method: 'GET',
			url: css[i],
			success: function (data)
			{
				$('head').append('<style>' + data + '</style>');
				if(i >= css.length - 1){ callback(); }else{ loadCSS(css, i+1, callback); }
			}
		});

	}



	/* LOAD JS */

	function loadJS (js, i)
	{

		$.getScript(js[i]).done(function (data)
		{
			if(i < js.length - 1){ loadJS(js, i+1); }

		});

	}

</script>