<?php

namespace Model;

use \DB;
use \Str;
use \Uri;
use \Config;

class Snippet extends \Model_Crud
{
	protected static $_table_name = 'snippets';
	protected static $_created_at = 'created_at';
	protected static $_updated_at = 'modified_at';
	protected static $_mysql_timestamp = false;

	public static function find_by_slug($slug, $token)
	{
		if ( ! $snippet = static::find_one_by('slug', $slug, 'like binary'))
		{
			return null;
		}

		if ($snippet->private and $snippet->token !== $token)
		{
			return null;
		}

		return $snippet;
	}

	public static function search($query, $group = null, $only_user = true, $limit = 20, $offset = 0)
	{
		$like = DB::escape('%'.$query.'%');
		$search = DB::escape($query);

		return static::find(function($q) use ($search, $query, $like, $only_user, $limit, $offset) {
			$user_id = Auth::get_user()->id;

			$q->select(static::$_table_name.'.*', array(
				DB::expr('MATCH (name, code) AGAINST ('.$search.')'), 'score'
			), array(
				DB::expr('CASE WHEN name like '.$like.' THEN 1 ELSE 0 end'), 'name_match'
			), array(
				DB::expr('CASE WHEN code like '.$like.' THEN 1 ELSE 0 end'), 'code_match'
			));

			$q->where_open()
				->where('name', 'like', '%'.$query.'%')
				->or_where('code', 'like', '%'.$query.'%')
				->where_close()
				->where_open();

			if ( ! $only_user)
			{
				$q->where('private', false);
			}

			$q->or_where('user_id', $user_id)
				->where_close();

			$q->limit($limit)->offset($offset);
			$q->order_by('name_match', 'DESC');
			$q->order_by('code_match', 'DESC');
			$q->order_by('score', 'DESC');

		}) ?: array();
	}

	public static function normalize_mode($mode)
	{
//		\Log::error($mode);
		$modes = Config::load('modes', true);
		$mode = strtolower(pathinfo($mode, PATHINFO_FILENAME));
		$mode = str_replace('/\W/', '', $mode);
//		\Log::error($mode);
//		\Log::error(print_r($modes, true));
		// Check for valid mode
		if (isset($modes[$mode]))
		{
			$mode;
		}

		// Try to guess a mode
		foreach ($modes as $mode_key => $mode_name)
		{
			if (strpos($mode_key, $mode) !== false or strpos($mode, $mode_key) !== false)
			{
				return $mode_key;
			}
		}

		// Default to text
		return 'text';
	}

	public static function create(array $values)
	{
		$snippet = static::forge($values);
		$snippet->save();

		return $snippet;
	}

	public function prep_values($values)
	{
		if ( ! isset($values['slug']))
		{
			$values['slug'] = Slug::generate();
		}

		if (isset($values['private']) and $values['private'])
		{
			$values['token'] = Str::random('alnum', 20);
		}

		if (isset($values['expires_in']))
		{
			$values['expires_at'] = time() + intval($values['expires_in']);
			unset($values['expires_in']);
		}

		if (strpos($values['mode'], '/') !== false)
		{
			$values['mode'] = static::normalize_mode($values['mode']);
		}

		return $values;
	}

	public function get_group_name()
	{
		if ($this->is_new() or ! $this->group_id)
		{
			return;
		}

		if ($group = Group::find_by_pk($this->group_id))
		{
			return $group->name;
		}
	}

	public function get_name()
	{
		if ($this->name)
		{
			return $this->name;
		}

		return $this->get_short_url();
	}

	public function get_parent()
	{
		$parent = static::find_by_pk($this->parent_id);

		if ($parent and $parent->is_available())
		{
			return $parent;
		}
	}

	public function get_url($action = 'view')
	{
		$slug = $this->slug;

		if (isset($this->private) and $this->private)
		{
			$slug .= '/'.$this->token;
		}

		if ($action === 'short')
		{
			return Uri::create('~'.$slug);
		}

		return Uri::create('snippet/'.$action.'/'.$slug);
	}

	public function get_short_url()
	{
		return $this->get_url('short');
	}

	public function get_fork_url()
	{
		return $this->get_url('fork');
	}

	public function is_forkable()
	{
		return $this->is_available();
	}

	public function is_available()
	{
		if ($this->private or $this->expires_at > time())
		{
			return false;
		}

		return true;
	}
}
