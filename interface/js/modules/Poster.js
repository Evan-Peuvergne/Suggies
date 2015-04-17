

	/**
	*
	*	POSTER
	*	Manage shows' posters
	*
	***/


	var Poster = function (id, name, url, onChoose)
	{


		/* VARIABLES */


		// Reference

		var that = this;


		// DOM

		this.dom = {
			elem: null,
			image: null
		};


		// OnChoose callback

		this.onChoose = onChoose;


		// PROPERTIES

		this.datas = {
			id: id,
			name: name,
			url: url
		};




		/* INIT */


		// Init with html creation

		this.create = function (callback)
		{
			// Create html
			this.dom.elem = $('<li><img src="http://image.tmdb.org/t/p/w500' + that.datas.url + '"/></li');
			this.dom.image = this.dom.elem.find('img');

			// Attch events
			this.dom.elem.click(function (e)
			{
				e.preventDefault();
				that.onChoose(that);
			});

			// Callback
			this.dom.image.load(function (e)
			{
				callback();
			});
		}


		// Init by building with existant html

		this.build = function (html)
		{
			// Update dom properties
			this.dom.elem = html;
			this.dom.image = this.dom.elem.find('img');

			// Attch events
			this.dom.elem.click(function (e)
			{
				e.preventDefault();
				that.onChoose(that);
			});
		}



		/* CHOOSE */

		this.choose = function ()
		{
			// Set choice on loading state
			this.dom.elem.addClass('loading');
		}


	}