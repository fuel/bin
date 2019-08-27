({
    baseUrl: '.',
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
		}
	},
    include: ['bin', 'ace/theme/monokai', 'ace/mode/php',
    'ace/mode/text', 'ace/mode/javascript', 'ace/mode/python', 'ace/mode/ruby'],
    out: "bin-built.js"
})