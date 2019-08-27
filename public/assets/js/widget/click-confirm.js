define(['jquery', 'underscore', 'backbone', 'hogan', 'text!../../templates/click-confirm.mustache'],
function($, _, Backbone, Hogan, html) {
	var template = Hogan.compile(html);
	var ClickConfirm = Backbone.View.extend({
		tagName: 'div',
		className: 'overlay widget',
		initialize: function(options) {
			console.log(this);
			this.show = _.bind(this.show, this);
			if (this.options.button) {
				this.options.button.on('click', this.show);
			}
			this.render();
		},
		events: {
			'click': 'cancel',
			'click .modal-window': function(e){
				e.stopPropagation();
			},
			'click .button-cancel': 'cancel',
			'click .button-confirm': 'confirm'
		},
		show: function(e) {
			$(".widget").trigger('close-widget');
			if (e) {
				e.preventDefault();
				e.stopPropagation();
				e.stopImmediatePropagation();
			}
			this.$el.fadeIn(200);
			return false;
		},
		cancel: function(e) {
			if (e) e.preventDefault();
			this.$el.fadeOut(200);
		},
		confirm: function(e){
			console.log(this.options);
			if (this.options.onConfirm) {
				this.options.onConfirm();
			} else if (this.options.button) {
				this.options.button
					.off('click', this.show)
					.trigger('click');
			}

			this.$el.fadeOut(200);
		},
		render: function(){
			var options = _.clone(this.options);
			options = _.defaults(options, {
				label_confirm: 'confirm',
				label_cancel: 'cancel'
			});
			if (options.options) {
				option.has_options = true;
			}
			this.$el.html(template.render(options)).appendTo('body');
			return this;
		}
	});

	return ClickConfirm;
});