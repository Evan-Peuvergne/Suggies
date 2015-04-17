
	
	/**
	*
	*	GRAPH
	*	Create a graph module
	*
	***/


	var Graph = function (id)
	{


		/* VARIABLES */


		// Reference

		var that = this;


		// DOM

		this.dom = {
			elem: null,
			graph: null,
			shows: null,
			labels: null,
			filters: null,
			aside: null,
			search: null
		};


		// Datas

		this.id = id;
		this.show = null;
		this.suggestions = null;
		this.shows = new Array();
		this.aside = null;
		this.sections = null;


		// States

		this.isFiltering = false;



		

		/* INIT */


		this.init = function (onGraphLoaded, onSuggestionsLoaded)
		{
			// Get show details
			var request = $.ajax({
				type: 'GET',
				url: 'api/shows/' + that.id
			});

			// Success
			request.done(function (data)
			{
				if(data.response)
				{
					that.show = data.response;
					loadTemplate(that, onGraphLoaded, onSuggestionsLoaded);
				}
				else{
					console.log('Attention ça part en couilles');
					that.init(onGraphLoaded, onSuggestionsLoaded);
				}
			});
		}

		function loadTemplate (module, onGraphLoaded, onSuggestionsLoaded)
		{
			// Load template
			var request = $.ajax({
				type: 'GET',
				url: 'interface/templates/graph.php'
			});

			// Success
			request.done(function (data)
			{
				// Update dom variables
				that.dom.elem = $(data);
				that.dom.graph = that.dom.elem.find('.graph');
				that.dom.show = that.dom.elem.find('.graph-background-1');
				that.dom.shows = that.dom.graph.find('.graph-shows');
				that.dom.labels = that.dom.graph.find('.graph-labels');
				that.dom.filters = that.dom.graph.find('.graph-filters');
				that.dom.aside = that.dom.elem.find('.infos');
				that.dom.search = that.dom.elem.find('.btn-search');

				// Update current show
				that.dom.show.css('background', 'url(http://image.tmdb.org/t/p/w500' + that.show.poster_path + ')');
				
				// Search link event
				that.dom.search.click(function (e)
				{
					that.remove(function ()
					{
						modules.search.reinit();
						modules.search.show(true, false);
					});
				});

				// Fill aside
				fillAside(that, onGraphLoaded, onSuggestionsLoaded);
			}); 
		}

		function fillAside (module, onGraphLoaded, onSuggestionsLoaded)
		{
			// Get aside
			module.aside = new Aside(module.show);
			module.aside.init(function (html)
			{
				module.dom.aside.append(html);
			});

			// Start loading
			module.dom.graph.addClass('loading');

			// Graph
			onGraphLoaded(module);

			// Add filters
			getSuggestions(module, onSuggestionsLoaded);
		}

		function getSuggestions (module, onSuggestionsLoaded)
		{
			// Load suggestions
			var request = $.ajax({
				type: 'GET',
				url: 'api/shows/' + module.id + '/similar'
			});

			// Success
			request.done(function (data)
			{
				// Add suggestions
				addSuggestions(module, data.response.similar, onSuggestionsLoaded);
			});
		}

		function addSuggestions (module, suggestions, onSuggestionsLoaded)
		{
			// Update properties
			module.suggestions = suggestions;

			// Create shows
			for(var i=0; i<module.suggestions.shows.length; i++)
			{
				module.shows[i] = new Show(function (suggestion)
				{
					// Select suggestion
					module.selectSuggestion(suggestion);
				});
				module.shows[i].init(module.suggestions.shows[i]);
			}

			// Filter default
			module.filter(Object.keys(module.suggestions.sections)[0], module.suggestions.sections[Object.keys(module.suggestions.sections)[0]], function ()
			{
				// Add every shows
				for(var i=0; i<module.shows.length; i++)
				{
					module.shows[i].show(module.dom.shows, i);
				}
			});

			// Add filters
			addFilters(module, onSuggestionsLoaded);
		}

		function addFilters (module, onSuggestionsLoaded)
		{
			// Create elements
			for(var i=0; i<Object.keys(module.suggestions.sections).length; i++)
			{
				var filter = Object.keys(module.suggestions.sections)[i];
				module.dom.filters.append($('<li data-filter="' + filter + '">'+ filters[filter] + '</li>'));

			}

			// Set first filter active
			module.dom.filters.find('li:first-child').addClass('active');

			// Attach filter selection event
			module.dom.filters.find('li').click(function (e)
			{
				e.preventDefault();
				if(!module.isFiltering && !$(this).hasClass('active')){
					// Change buttons states
					module.dom.filters.find('li.active').removeClass('active');
					var button = $(this);
					var filter = button.attr('data-filter');
					button.addClass('loading');

					// Remove labels
					that.dom.labels.find('li').addClass('hide');

					// Filter
					module.filter(filter, module.suggestions.sections[filter], function ()
					{
						for(var i=0; i<module.shows.length; i++)
						{
							module.shows[i].move();
						}
						module.isFiltering = false;
						button.addClass('active').removeClass('loading');
					});
				}
			});

			// Loading ended
			module.dom.graph.addClass('loading-ended').removeClass('loading');
		}





		/* FILTER */


		this.filter = function (name, filter, callback)
		{
			// Set filtering state
			this.isFiltering = true;

			// Counts
			var size = 360/this.shows.length;
			var i = 0;
			var lastOffset = 0;
			var counts = new Array();
			for(section in filter)
			{
				var obj = {
					section: section,
					index: i,
					iterations: 0,
					offset: lastOffset
				};
				lastOffset = lastOffset + filter[section]*size;
				counts[section] = obj;
				i++;
			}

			// Update each show caracteristics
			for(var i=0; i<this.shows.length; i++)
			{
				var t = counts[this.shows[i].data[name]];
				if(t)
				{
					this.shows[i].caracteristics.position.radius = t.offset + t.iterations*size + size/2;
					counts[this.shows[i].data[name]].iterations++;
					this.shows[i].caracteristics.section = t.index + 1;
					this.shows[i].caracteristics.visible = true;
				}else{
					this.shows[i].caracteristics.visible = false;
				}
			}

			// Show sections labels
			for(section in counts)
			{
				var position = counts[section].offset + (filter[section]*size)/2;
				var elem = $('<li><span>' + section + '</span></li>');
				elem.css({
					transformOrigin: '50% ' + (metrics.size/2 + 20) + 'px',
					transform: 'rotate(' + position + 'deg)'
				});
				elem.addClass('filter-label-' + (counts[section].index + 1));
				this.dom.labels.append(elem);
			}

			// Callback
			this.isFiltering = false;
			callback();
		}






		/* SELECT SUGGESTION */

		this.selectSuggestion = function (show)
		{
			// Store old aside
			var old = this.aside;

			// Load data
			var request = $.ajax({
				type: 'GET',
				url: 'api/shows/' + show.data.id
			});

			// Success
			request.done(function (data)
			{
				// Create aside
				that.aside = new Aside(data.response);
				that.aside.init(function (html)
				{
					// // Remove old
					old.hide(function ()
					{
						that.dom.aside.append(html);
						that.aside.open();
					});
				});
			});
		}





		/* OPEN */

		this.open = function (animation)
		{
			// Default parameters
			if(!animation){ var animation = true; }

			// Show elem
			this.dom.elem.addClass('opened');
			$('body').append(this.dom.elem);

			// Set graph format
			this.dom.graph.width(this.dom.graph.height());

			// Get metrics
			metrics.size = this.dom.graph.height();
			metrics.area = (metrics.size - this.dom.show.height())/2 - 50;
		};





		/* REMOVE */

		this.remove = function (callback)
		{
			// Animation
			this.dom.elem.removeClass('opened').addClass('remove');

			// Callback
			callback();
		}



	}
