define(['jquery', 'backbone', 'hogan', 'text!../../templates/options.mustache'], function($, Backbone, hogan, html){
	var template = Hogan.compile(html);
	var Options = Backbone.View.extend({
		tagName: 'ul',
		className: 'options widget',
		showInput: true,
		initialize: function(options) {
			this._options = options.options || [];
			this._title = options.title;
			this._placeholder = options.placeholder;
			this._button = $(options.button);
			this.show = _.bind(this.show, this);
			this.hide = _.bind(this.hide, this);
			this.windowKeypress = _.bind(this.windowKeypress, this);
			this.remove = _.bind(this.remove, this);
			this._button.on('click', this.show);
			this._clickOutsideTimer = false;
			this._clickOutside = _.bind(function(){
				this._clickOutsideTimer = setTimeout(this.hide, 0);
			}, this);
			$(window).on('resize', this.hide);
			this.render();
			if (this.options.input) {
				this.setInitialInput();
			}
		},
		events: {
			'click': function(e) {
				e.stopPropagation();
			},
			'click .option': 'clickOption',
			'keydown input': 'inputKeypress',
			'click .option-clear': 'clearOption',
			'close-widget': 'hide'
		},
		setInitialInput: function(){
			var input = $(this.options.input);
			if (input.length > 0) {
				this.setOption(input.val());
			}
		},
		windowKeypress: function(e) {
			if (e.keyCode == 27) {
				this.hide();
			}
		},
		clearOption: function() {
			this.$('input').val('');
			this.setOption('', true);
		},
		setOption: function(option, hide) {
			this.$('.option').removeClass('active');
			if (this.options.input) {
				$(this.options.input).val(option);
			}
			if (this.options.onChange) {
				this.options.onChange(option, this._button, this);
			}
			if (option.length){
				this.$('.active').removeClass();
				this.$('[data-index="'+option+'"]').addClass('active');
			}
			if (hide) {
				this.hide();
			}
		},
		clickOption: function(e) {
			e.preventDefault();
			var option = $(e.target);
			this.$('input').val('');
			this.setOption(option.data('index'), true);
			option.addClass('active');
		},
		inputKeypress: function(e){
			if (e.keyCode == 13) {
				e.preventDefault();
				var value = $(e.target).val();
				this.setOption(value, true);
			}
		},
		show: function(e){
			if (e) e.preventDefault();
			$(".widget").trigger('close-widget');
			$(window).on('keydown', this.windowKeypress);
			if (e && e.stopPropagation) e.stopPropagation();
			var offset = this._button.offset();
			this.$el.removeClass('position-right position-left');
			var css = {
				left: '',
				right: ''
			};
			css.top = offset.top + this._button.height() + 12;
			var windowWidth = $(window).width();
			if (windowWidth/2 < offset.left) {
				this.$el.addClass('position-right');
				css.right = (windowWidth - offset.left) - this._button.width();
			} else {
				this.$el.addClass('position-left');
				css.left = offset.left + 7;
			}
			this.$el.css(css).stop().fadeIn(200);
			this.$('input').focus();
			$(window).on('click', this._clickOutside);
		},
		hide: function() {
			$(window).off('click', this._clickOutside);
			$(window).off('keydown', this.windowKeypress);
			this.$el.fadeOut(200);
			if (this.options.onBlur) {
				this.options.onBlur(this._button, this);
			}
		},
		remove: function(){
			this.stopListening();
			this._button.off('click', this.show);
			$(window).off('click', this.clickOutside);
			$(window).on('resize', this.hide);
			$(window).off('keydown', this.windowKeypress);
			this.$el.fadeOut(250, function(){
				$(this).remove();
			});
		},
		setOptions: function(options){
			this._options = options;
			return this.render();
		},
		addOptions: function(options) {
			this._options = _.union(this._options, options);
			return this.render();
		},
		normalizeOptions: function(options)
		{
			if (_.isArray(options))
			{
				return _.map(options, function(option){
					return {
						index: option,
						value: option
					};
				});
			}

			var normalized = [];
			_.each(options, function(v, i){
				normalized.push({
					index: i,
					value: v
				});
			});
			return normalized;
		},
		render: function() {
			var options = this.normalizeOptions(this._options);
			var content = template.render({
				title: this._title,
				placeholder: this._placeholder,
				options: options,
				has_options: ! _.isEmpty(options),
				show_input: this.options.showInput !== false,
				show_delete: this.options.showDelete !== false
			});
			this.$el.html(content).hide().appendTo('body');
			return this;
		}
	});

	return Options;
});