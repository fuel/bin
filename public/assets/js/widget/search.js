define(['jquery', 'underscore', 'backbone', './search-results'],
function($, _, Backbone, Results){
	var Search = Backbone.View.extend({
		el: '#search-form',
		initialize: function(){
			this.input = this.$('#search-input');
			this.to = false;
			this.group = false;
			this.results = new Results();
			this.icon = this.$('i.icon-search');
			this.request = false;
		},
		events: {
			'keyup #search-input': 'keypress',
			'submit': 'submit'
		},
		setGroup: function(group) {
			if ( ! group || group.length === 0) {
				group = false;
			}
			this.group = group;
			this.checkForSearch();
		},
		newSearch: function(value) {
			if (this.prevValue && this.prevValue === value) {
				return false;
			}
			this.prevValue = value;
			return true;
		},
		checkForSearch: function() {
			var value = this.input.val();
			if ( ! value || value.length < 3) {
				this.results.empty();
				this.stopSpinner();
			}
			this.startSpinner();
			this.results.freeze();
			if (this.request) {
				this.request.abort();
			}
			this.request = $.ajax({
				type: 'POST',
				url: '/account/search',
				data: {
					group: this.group || '',
					query: value
				}
			}).done(_.bind(function(response){
				this.results.setResults(response.results || []);
			}, this)).fail(_.bind(function(){
				console.log(arguments);
			}, this)).always(_.bind(function(){
				this.request = false;
				this.stopSpinner();
			}, this));
		},
		submit: function(e) {
			e.preventDefault();
			setTimeout(_.bind(this.checkForSearch, this), 100);
		},
		keypress: function(e) {
			if (e.which === 13) {
				return;
			}
			var value = this.input.val();
			if ( ! this.newSearch(value)) {
				return;
			}
			if (this.input.val().length > 2) {
				this.startSpinner();
				clearTimeout(this.to);
				this.to = setTimeout(_.bind(this.checkForSearch, this), 1000);
			}
			else
			{
				this.stopSpinner();
			}
		},
		startSpinner: function() {
			if (this.spinning === true) {
				return;
			}
			this.spinning = true;
			this.icon.removeClass('icon-search')
				.addClass('icon-spinner icon-spin');
		},
		stopSpinner: function () {
			if (this.spinning !== true) {
				return;
			}
			this.spinning = false;
			this.icon.addClass('icon-search')
				.removeClass('icon-spinner icon-spin');
		},
		empty: function(){
			this.stopSpinner();
			this.results.reset();
		}
	});

	return Search;
});