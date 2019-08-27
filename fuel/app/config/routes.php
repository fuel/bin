<?php
return array(
	'_root_'  => function(){
		Response::redirect('snippet/create');
	},  // The default route
	'_404_'   => '404/index',    // The main 404 route
	'~(.+)(/.*)?' => function($request) {
		$slug = trim(join($request->uri->segments(), '/'), '~');

		Response::redirect('snippet/view/'.$slug);
	},
	'auth/(github|facebook|twitter)(/.*)?' => 'auth/opauth',
);