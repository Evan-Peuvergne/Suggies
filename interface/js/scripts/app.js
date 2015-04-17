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
	'eps_length': 'Durée'
};



$(document).ready(function ()
{

	// $.ajaxSetup({
 //    	cache: false
	// });

	// Init dom references
	dom.body = $('body');
	dom.loader = $('.main-loader');

	// Launch research
	modules.search = new Search();
	modules.search.init(function (search)
	{
		modules.search = search;
		dom.loader.addClass('hidden');
		modules.search.show();
	});


});