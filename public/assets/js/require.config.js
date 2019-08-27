var require = {
	baseUrl: '/assets/js/',
	paths: {
		ace: "lib/ace"
	},
	shim: {
		'underscore': {
			exports: '_'
		},
		'jquery': {
			exports: '$'
		},
		'hogan': {
			exports: 'Hogan'
		},
		'backbone': {
			deps: ['underscore', 'jquery'],
			exports: 'Backbone'
		},
	}//,
	//urlArgs: "bust=" +  (new Date()).getTime()
};