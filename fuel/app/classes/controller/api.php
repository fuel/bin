<?php

use Model\Auth;
use Model\Group;
use Model\Snippet;

class Controller_Api extends Controller_Rest
{
	public function action_create()
	{
		if ( ! Auth::api_check())
		{
			return array('status' => 'Not Authorized');
		}

		if ( ! $code = Input::post('code'))
		{
			return array('status' => 'No snippet send');
		}

		$private = Input::post('private', false);

		if ($private === '0')
		{
			$private = false;
		}

		$snippet = Snippet::create(array(
			'user_id' => Auth::get_user()->id,
			'code' => $code,
			'private' => (bool) $private,
			'mode' => Input::post('mode', 'php'),
			'source' => 'api',
		));

		return array(
			'status' => 'OK',
			'url' => $snippet->get_short_url(),
		);
	}

	public function post_snippet()
	{
		if ( ! $user = Auth::get_user())
		{
			return $this->response(array(
				'error' => 'Not Authorized',
			), 401);
		}

		$code = Input::param('post');
		$group = Input::param('group');
		$private = Input::param('private');

		if ($group)
		{
			$group = Group::find_or_create($group);
		}

		$snippet = Snippet::create(array(
			'user_id' => $user->id,
			'code' => $code,
			'group' => $group,
			'private' => $private,
		));

		return array(
			'url' => $snippet->get_short_url(),
		);
	}
}
