/* VARIABLES */

var dom = {
	body: null,
	loader: null,
	search: null,
	graph: null,
	aside: null
};

var modules = {
	search: null,
	graph: null,
	aside: null
};

var metrics = {
	size: null,
	area: null
};

var filters = {
	'genre': 'Genre',
	'origin': 'Pays',
	'duration': 'Durée'
};

function parse(val) {
    var result = "Not found",
        tmp = [];
    location.search
    //.replace ( "?", "" )
    // this is better, there might be a question mark inside
    .substr(1)
        .split("&")
        .forEach(function (item) {
        tmp = item.split("=");
        if (tmp[0] === val) result = decodeURIComponent(tmp[1]);
    });
    return result;
}


$(document).ready(function ()
{

	// Init dom references
	dom.body = $('body');
	dom.loader = $('.main-loader');

	// Launch research
	if(parse("request_token") != 'Not found'){
		console.log('coucou');
		var token = parse("request_token");
		console.log(window.location);
		var str = window.location.pathname;
		var pattern = /\/(\d+)$/;
		var matches = pattern.exec(str);
		modules.search = new Search();
		modules.search.init(function (search)
		{
			modules.search = search;
		});
		modules.graph = new Graph(matches[1]);
		modules.graph.init(function (graph)
		{
			dom.loader.addClass('hidden');
			graph.open();
			graph.aside.addToWatchlist(token);
		});
	}
	else if(!window.history.state)
	{
		modules.search = new Search();
		modules.search.init(function (search)
		{
			modules.search = search;
			dom.loader.addClass('hidden');
			modules.search.show();
		});
	}else{
		modules.search = new Search();
		modules.search.init(function (search)
		{
			modules.search = search;
		});
		modules.graph = new Graph(window.history.state.id);
		modules.graph.init(function (graph)
		{
			dom.loader.addClass('hidden');
			graph.open();
		});
	}

});