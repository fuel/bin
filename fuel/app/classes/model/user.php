<?php

namespace Model;

use \DB;
use \Crypt;
use \Str;

class User extends \Model_Crud
{
	protected static $_table_name = 'users';
	protected static $_created_at = 'created_at';
	protected static $_updated_at = 'modified_at';
	protected static $_mysql_timestamp = false;
	protected static $_token_salt = 'qw098r223W@983RUCPA';

	public static function find_profile($provider, $nickname)
	{
		return static::find_one_by(function($query) use ($provider, $nickname) {
			$query->where('provider', $provider)
				->where('nickname', $nickname)
				->limit(1);
		});
	}

	public static function find_api_user($nickname, $token)
	{
		if ( ! $users = static::find_by_nickname($nickname))
		{
			return null;
		}

		foreach ($users as $user)
		{
			if ($user->verify_token($token))
			{
				return $user;
			}
		}
	}

	public function update(array $values)
	{
		$this->set($values);
		$this->save();
	}

	public function prep_values($values)
	{
		if ($this->is_new())
		{
			$values['api_id'] = Str::random('alnum', 10);
			$composite = $values['provider'].'::'.$values['oauth_id'];
			$values['api_token'] = Crypt::encode($composite, static::$_token_salt);
		}

		return $values;
	}

	public function verify_token($token)
	{
		if ($this->is_new())
		{
			return false;
		}

		if ( ! $composite = Crypt::decode($token, static::$_token_salt))
		{
			return false;
		}

		list($provider, $uid) = explode('::', $composite);

		return ($this->provider === $provider and $this->oauth_id === $uid);
	}

	public function get_num_snippets()
	{
		return DB::select(array('COUNT("id")', 'num'))
			->from('snippets')
			->where('user_id', $this->id)
			->execute()
			->get('num');
	}

	public function get_num_groups()
	{
		return DB::select(array('COUNT("id")', 'num'))
			->from('groups')
			->where('user_id', $this->id)
			->execute()
			->get('num');
	}

	public function get_groups()
	{
		return Group::find_by_user_id($this->id) ?: array();
	}

	public function get_group_names()
	{
		return  array_map(function($group) {
			return $group->name;
		}, $this->get_groups());
	}
}