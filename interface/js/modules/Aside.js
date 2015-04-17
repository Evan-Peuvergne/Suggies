

	/**
	*
	*	ASIDE
	*	Manage an aside object for graph
	*
	**/


	var Aside = function (data)
	{


		/* VARIABLES */


		// Reference

		var that = this;


		// DOM

		this.dom = {
			elem: null,
			header: null,
			buttons: {
				suggest: null,
				play: null,
			},
			rates: null,
			content: null
		};


		// Data

		this.data = data;





		
		/* INIT */

		this.init = function (callback)
		{
			// Load html
			var request = $.ajax({
				type: 'GET',
				url: 'interface/templates/aside.php'
			});

			// Success
			request.done(function (data)
			{
				// Fill aside
				var note = Math.round(that.data.vote_average);
				that.data.note = new Array();
				var i=0;
				while(note >= 2){ that.data.note.push('fill'); note = note - 2; i++; }
				for(var n=i; i<5; i++){ that.data.note.push('empty'); }
				data = Mustache.render(data, that.data);

				// Update DOM
				that.dom.elem = $(data);
				that.dom.header = that.dom.elem.find('.aside-header');
				that.dom.buttons.suggest = that.dom.header.find('.btn-suggest');
				that.dom.buttons.play = that.dom.header.find('a.btn-play');
				that.dom.rates = that.dom.header.find('ul.rates');
				that.dom.content = that.dom.elem.find('.aside-content');

				// Attache events
				attachEvents(that, callback);
			});
		}

		function attachEvents (module, callback)
		{
			// Suggest event
			that.dom.buttons.suggest.click(function (e)
			{
				e.preventDefault();
				var old = modules.graph;
				modules.graph = new Graph(module.data.id);
				modules.graph.init(function (graph)
				{
					old.remove(function ()
					{
						modules.graph.open(true, false);
					});
				});
			});

			// Play
			that.dom.buttons.play.click(function (e)
			{
				e.preventDefault();
				console.log('play');
			});

			// Callback
			callback(module.dom.elem);
		} 





		/* OPEN */


		this.open = function ()
		{
			this.dom.elem.parent().removeClass('remove').addClass('open');
		}



		/* HIDE */

		this.hide = function (callback)
		{
			// Animation
			this.dom.elem.parent().removeClass('open').addClass('remove');

			// Wait for removing html
			setTimeout(function ()
			{
				that.dom.elem.remove();
				callback();
			}, 1000);
		}




	};