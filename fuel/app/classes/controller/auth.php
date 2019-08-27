<?php

use Model\Auth;

class Controller_Auth extends Controller
{
	public function action_index()
	{
		if (Auth::check())
		{
			Response::redirect('account');
		}

		Response::redirect('/');
	}

	public function action_opauth()
	{
		$referer = Input::server('http_referer', '');

		if (strpos($referer, 'fuelphp.'))
		{
			Session::set('auth.return_to', $referer);
		}

		new Opauth(Config::load('opauth'));
	}

	public function action_callback()
	{
		$response = unserialize(base64_decode($_GET['opauth']));

		if ( ! $uid = Arr::get($response, 'auth.uid'))
		{
			Response::redirect('/');
		}

		$name = Arr::get($response, 'auth.info.name');
		$nickname = Arr::get($response, 'auth.info.nickname');
		$provider = strtolower(Arr::get($response, 'auth.provider'));

		if ($user = Auth::find_user($provider, $uid))
		{
			$user->update(array(
				'name' => $name,
				'nickname' => $nickname,
			));
		}
		else
		{
			$user = Auth::register(compact('name', 'nickname', 'provider', 'uid'));
		}

		Auth::login($user);

		if ($next = Session::get('auth.return_to'))
		{
			Session::delete('auth.return_to');
			Response::redirect($next);
		}

		Response::redirect('/');
	}

	public function action_logout()
	{
		Auth::logout();
		Response::redirect('/');
	}


}
