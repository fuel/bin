<?php

class Controller_404 extends Controller
{
	public function action_index()
	{
		$request = Request::forge('snippet/view/a')->execute();

		return Response::forge($request->response->body, 404);
	}
}