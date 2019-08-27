<?php

namespace Model;

use \Arr;
use \Crypt;
use \Session;
use \DB;
use \Input;

abstract class Auth
{
	protected static $crypt_key = '203r8w&*^3ck10';

	protected static $user;

	public static function check()
	{
		return Session::get('auth.logged_in');
	}

	public static function api_check()
	{
		static $check = null;

		if ($check === null)
		{
			$check = static::api_login();
		}

		return $check;
	}

	public static function get_user()
	{
		if ( ! static::$user and static::check())
		{
			$user_id = Session::get('auth.user_id');
			static::$user = User::find_by_pk($user_id);
		}

		if ( ! static::$user)
		{
			static::api_login();
		}

		return static::$user;
	}

	public static function login(User $user)
	{
		static::$user = $user;
		Session::set('auth.user_id', $user->id);
		Session::set('auth.logged_in', true);
	}

	public static function register(array $user)
	{
		$user['oauth_id'] = $user['uid'];
		unset($user['uid']);

		$user = new User($user);

		if ($user->save())
		{
			return $user;
		}
	}

	public static function find_user($provider, $uid)
	{
		$result = DB::select()
			->from('users')
			->where('provider', $provider)
			->where('oauth_id', $uid)
			->limit(1)
			->as_object('Model\User')
			->execute();

		if (count($result))
		{
			return $result[0];
		}
	}

	public static function api_login()
	{
		$headers = Input::server();
		$headers = array_change_key_case($headers, CASE_UPPER);
		$id = Arr::get($headers, 'HTTP_X_FUELPHP-BIN-ID');
		$token = Arr::get($headers, 'HTTP_X_FUELPHP-BIN-TOKEN');

		if ( ! $id or ! $token)
		{
			return false;
		}

		if ( ! $users = User::find_by_api_id($id))
		{
			return false;
		}

		foreach ($users as $user)
		{
			if ($user->verify_token($token))
			{
				static::$user = $user;

				return true;
			}
		}

		return false;
	}

	public static function logout()
	{
		Session::delete('auth.logged_in');
		Session::delete('auth.user_id');
		static::$user = null;
	}
}