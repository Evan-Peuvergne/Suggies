

	/**
	*
	*	SHOW
	*	Create and manage a show on a graph
	*
	**/


	var Show = function (onChoose)
	{


		/* VARIABLES */


		// Reference

		var that = this;


		// Dom

		this.dom = {
			elem: null,
			label: null
		};


		// Caracteristics

		this.caracteristics = {
			position: {
				distance: null,
				radius: null
			},
			section: null,
			visible: true
		};


		// Datas

		this.data = null;


		// Callbacks

		this.onChoose = onChoose;




		/* INIT */


		this.init = function (datas)
		{
			// Fill properties
			this.data = datas;

			// Calculate shows relevance
			this.caracteristics.position.distance = metrics.area - ((this.data.similarity)/100)*metrics.area;

			// Create element
			this.dom.elem = $('<li><span>' + this.data.name + '</span></li>');
			this.dom.label = this.dom.elem.find('span');

			// Attach click event
			this.dom.elem.click(function (e)
			{
				e.preventDefault();
				that.onChoose(that);
			});
		}




		/* SHOW */


		this.show = function (location, i)
		{
			// Update filter
			this.dom.elem.addClass('show-filter-' + that.caracteristics.section);

			// Update position
			this.dom.elem.css({
				top: this.caracteristics.position.distance,
				transformOrigin: '50% ' + ((metrics.size/2) - this.caracteristics.position.distance) + 'px',
				transform: 'rotate(' + that.caracteristics.position.radius + 'deg)',
				animationDelay: (2+0.1*i) +'s'
			});

			// Replace label
			this.dom.label.css('transform-origin', '-1.5rem 50%');
			this.dom.label.css('transform', 'rotate(' + (- that.caracteristics.position.radius) + 'deg)');

			// Append
			location.append(this.dom.elem);
		};




		


		/* MOVE */


		this.move = function ()
		{
			if(this.caracteristics.visible)
			{
				// Update filter
				this.dom.elem.removeClass();
				this.dom.elem.addClass('show-filter-' + that.caracteristics.section).addClass('visible');

				// Update position
				this.dom.elem.css({
					top: this.caracteristics.position.distance,
					transformOrigin: '50% ' + ((metrics.size/2) - this.caracteristics.position.distance) + 'px',
					transform: 'rotate(' + that.caracteristics.position.radius + 'deg)'
				});	

				// Replace label
				this.dom.label.css('transform-origin', '-1.5rem 50%');
				this.dom.label.css('transform', 'rotate(' + (- that.caracteristics.position.radius) + 'deg)');
			}else{
				console.log('hello');
				this.dom.elem.removeClass('visible').addClass('hidden');
			}
		}




	}