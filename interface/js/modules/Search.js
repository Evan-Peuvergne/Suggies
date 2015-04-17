	

	/**
	*
	*	SEARCH
	* 	Create a search module
	*
	**/


	var Search = function ()
	{


		/* VARIABLES */


		// REFERENCE

		var that = this;


		// DOM

		this.dom = {
			elem: null,
			input: null,
			posters: null,
			loader: null
		};


		//  PROPERTIES

		this.posters = null;
		this.trendies = null;


		// STATE

		this.posterSelected = false;




		/* INIT */

		this.init = function (success, error)
		{
			// Create requets
			var request = $.ajax({
				type: 'GET',
				url: 'interface/templates/search.php',
				dataType: 'html'
			});

			// Success
			request.done(function (data)
			{
				// Init module properties
				initModuleProperties(that, data, success, error);
			});

		}

		function initModuleProperties (module, data, success, error)
		{
			// Update properties
			module.dom.elem = $(data);
			module.dom.input = module.dom.elem.find('input');
			module.dom.posters = module.dom.elem.find('ul');
			module.dom.loader = module.dom.elem.find('.loader');

			// Create posters list
			module.posters = new Array();
			module.dom.posters.find('li').each(function (i, elem)
			{
				elem = $(elem);
				module.posters[module.posters.length] = new Poster(elem.attr('data-id'), elem.attr('data-name'), elem.attr('data-poster'), function (poster)
				{
					if(!module.posterSelected){
						choosePoster(that, poster);
					}
				});
				module.posters[module.posters.length - 1].build(elem);
			});
			module.trendies = module.posters;

			// Init events
			initModuleEvents(module, success, error);
		}

		function initModuleEvents (module, success, error)
		{
			// Search submitted
			module.dom.elem.find('form').submit(function (e)
			{
				e.preventDefault();
				var search = that.dom.input.val();
				that.search(search);
			});

			// Call callback
			success(that);
		}




		/* SEARCH */


		this.search = function (search)
		{
			// Manage errors
			if(search == ""){ handleErrors("Votre recherche est vide"); }

			// Make the research
			else
			{
				search = search.toLowerCase(search);
				requestSearch(this, search);
			}

		}

		function handleErrors (error)
		{
			console.log(error);
		}

		function requestSearch (module, search)
		{
			//Start loader
			module.dom.loader.css({
				left: 0,
				right: 'auto',
				width: '20%',
				transitionDuration: '1s'
			});

			// Prepare request
			var request = $.ajax({
				type: 'POST',
				url: 'api/search',
				data: {
					string: search
				}
			});

			// Success
			request.done(function (data)
			{
				module.posters = new Array();
				createPosterForResult(module, data.response, 0);
			});
		}

		function createPosterForResult (module, results, i)
		{
			// Update loader caracteristics
			module.dom.loader.css('transition-duration', '0.3s');

			// Create poster
			var result = results[i];
			if(result.poster_path)
			{
				module.posters[module.posters.length] = new Poster(result.id, result.name, result.poster_path, function (poster)
				{
					if(!module.posterSelected){
						choosePoster(that, poster);
					}
				});
				module.posters[module.posters.length - 1].create(function ()
				{
					if(i<results.length -1){ module.dom.loader.css('width', ((i/results.length*50)+30) + '%'); createPosterForResult(module, results, i+1); }else{ printPosters(module); }
				});
			}
			else
			{
				if(i<results.length -1){ createPosterForResult(module, results, i+1); }else{ printPosters(module); }
			}
		}

		function printPosters (module)
		{
			// Finish loader
			module.dom.loader.css({
				transitionDuration: '3s',
				width: '100%'
			});

			// Remove animation for old posters
			var old = module.dom.posters.find('li').addClass('remove');

			setTimeout(function ()
			{
				// Remove old posters
				old.remove();

				// Add new posters
				for(i=0; i<module.posters.length; i++)
				{
					module.dom.posters.append(module.posters[i].dom.elem);
				}

				// Reset loader
				setTimeout(function ()
				{
					module.dom.loader.css({
						left: 'auto',
						right: 0,
						transitionDuration: '1s',
						width: '0%'
					});
				}, 3000);
			}
			, 1000);
		}

		function choosePoster (module, poster)
		{
			// Set poster to choose state
			poster.choose();
			module.posterSelected = true;

			// Create graph object
			modules.graph = new Graph(poster.datas.id);
			modules.graph.init(function (graph)
			{
				module.hide();
				graph.open();
			}, 
			function (graph)
			{
				console.log("la c'est vraiment fini");
			})
		}







		/* REINIT */

		this.reinit = function ()
		{
			// Remove loading
			this.posterSelected = false;
			this.dom.posters.find('li.loading').removeClass('loading');
		}






		/* SHOW */

		this.show = function (animation, append)
		{
			// Default parameters
			if(!animation){ animation = true; }
			if(!(append)){ append = true; }

			// Show html
			this.dom.elem.removeClass('hidden').addClass('opened');
			if(append){ $('body').append(this.dom.elem) };
		}



		/* HIDE */

		this.hide = function (animation, callback)
		{
			// Default parameters
			if(!animation){ animation = true; }

			// Close
			this.dom.elem.removeClass('opened').addClass('hidden'); 
		}





	};