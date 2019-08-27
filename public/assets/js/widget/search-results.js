define(['jquery', 'underscore', 'backbone', 'hogan', 'text!../../templates/search-results.mustache'],
function($, _, Backbone, Hogan, html) {
	var templates = Hogan.compile(html);
	var Results = Backbone.View.extend({
		el: '#search-results',
		initialize: function () {
			this.originalHtml = this.$el.html();
			this.frozen = false;
			$(window).on('keydown', _.bind(this.windowKeyDown, this));
		},
		events: {
			'click': 'click',
			'mouseenter .search-result': 'hoverHighlight'
		},
		hoverHighlight: function (e) {
			this.items.removeClass('active');
			$(e.target).addClass('active');
		},
		windowKeyDown: function (e) {
			console.log(e.which);
			if (e.which === 38) {
				this.highlightPrev();
				e.preventDefault();
			} else if (e.which === 40) {
				this.highlightNext();
				e.preventDefault();
			} else if (e.which === 13) {
				var link = this.$('.active').click();
				if (link.length === 1)
					window.location = link.attr('href');
			}
		},
		highlightNext: function () {
			var active = this.$('.search-result.active');
			this.items.removeClass('active');
			if (active.length === 0 || active.next().length === 0) {
				this.items.first().addClass('active');
			} else {
				var next = active.next();
				next.addClass('active');
			}
		},
		highlightPrev: function () {
			var active = this.$('.search-result.active');
			this.items.removeClass('active');
			if (active.length === 0 || active.prev().length === 0) {
				this.items.last().addClass('active');
			} else {
				var prev = active.prev();
				prev.addClass('active');
			}
		},
		click: function(e) {
			if (this.frozen && e) {
				e.preventDefault();
			}
		},
		setResults: function (results) {
			var result = templates.render({
				results: results,
				has_results: results.length !== 0
			});
			this.$el.html(result);
			this.items = this.$('.search-result');
			this.unfreeze();
		},
		freeze: function () {
			if (this.frozen) {
				return;
			}
			this.frozen = true;
			this.$el.stop().animate({
				opacity: .4
			}, 350);
			return this;
		},
		unfreeze: function () {
			if ( ! this.frozen) {
				return;
			}
			this.frozen = false;
			this.$el.stop().animate({
				opacity: 1
			}, 350);
			return this;
		},
		reset: function() {
			this.unfreeze();
			this.$el.html(this.originalHtml);
			this.items = this.$('.search-result');
		}
	});

	return Results;
});