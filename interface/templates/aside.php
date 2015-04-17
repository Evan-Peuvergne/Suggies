<div class="aside">
	<div class="aside-header" style="background-image: url(http://image.tmdb.org/t/p/w500{{poster_path}})">
		<div class="filter"></div>
		<h1>{{name}}</h1>
		<div class="buttons">
			<a href="#" class="btn-suggest"></a>
			<a class="btn-play" href="#"></a>
		</div>
		<ul class="rates">
			{{#note}}
				<li class="{{.}}"></li>
			{{/note}}
		</ul>
	</div>
	<div class="aside-content">
		<section>
			<h2>Synopsis</h2>
			<p>{{overview}}</p>
		</section>
		<a href="#" class="btn btn-watchlist">
			<span>Watchlist</span>
		</a>
	</div>
</div>