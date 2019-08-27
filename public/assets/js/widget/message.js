define(['jquery', 'backbone', 'underscore', 'hogan', 'text!../../templates/message.mustache'],
	function($, Backbone, _, Hogan, html) {
		var template = Hogan.compile(html);
		var Message = Backbone.View.extend({
			tagName: 'div',
			className: 'message',
			initialize: function(options) {
				this.show();
			},
			render: function() {
				if (this.options.type && ! this.options.label) {
					this.options.label = ucfirst(this.options.type) + ': ';
				}

				this.$el.html(template.render({
					message: this.options.message,
					label: this.options.label
				})).hide().appendTo('body');

				if (this.options.type) {
					this.$el.addClass('type-'+this.options.type);
				}

				return this;
			},
			events: {
				'click .icon-remove': 'close'
			},
			close: function() {
				this.$el.fadeOut(this.options.fadeOut || this.options.fadeIn || 300, _.bind(function(){
					this.remove();
				}, this));
			},
			show: function() {
				this.render();
				this.$el.delay(this.options.delay || 200)
					.fadeIn(this.options.fadeIn || 300);

				if (this.options.autoHide !== false) {
					this.$el.delay(this.options.pause || 2500);
					this.close();
				}
			}
		});

		return Message;
	});