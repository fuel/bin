<?php

namespace Model;

use \DB;
use \Model_Crud;
use \Inflector;

class Group extends Model_Crud
{
	protected static $_table_name = 'groups';
	protected static $_created_at = 'created_at';
	protected static $_updated_at = 'modified_at';
	protected static $_mysql_timestamp = false;

	public static function from_user(User $user)
	{
		static::find(function($query) use ($user) {
			$query->where('user_id', $user->id)
				->order_by('position', 'asc')
				->order_by('id', 'desc');
		}) ?: array();
	}

	public function prep_values($values)
	{
		$values['slug'] = Inflector::friendly_title($values['name']);

		return $values;
	}

	public function owned_by(User $user)
	{
		if ( ! isset($this->user_id))
		{
			return false;
		}

		return $this->user_id === $user->id;
	}

	public function get_snippets($options)
	{
		$options = $options + array(
			'limit' => null,
			'offset' => null,
			'order_field' => null,
			'order_direction' => null,
		);

		$id = $this->id;

		return Snippet::find(function($query) use ($id, $options) {
			$query->where('group_id', $id)
				->limit($options['limit'])
				->offset($options['offset']);

			if ($options['order_field'])
			{
				$query->order_by($options['order_field'], $options['order_direction']);
			}
		}) ?: array();
	}

	public static function find_or_create($group)
	{
		if (empty($group) or ! $user = Auth::get_user())
		{
			return;
		}

		$found = static::find(function($query) use ($group, $user) {
			$query->where('name', $group)
				->where('user_id', $user->id);
		});

		if ($found)
		{
			return reset($found);
		}

		$group = static::forge(array(
			'name' => $group,
			'user_id' => $user->id,
		));

		$group->save();

		return $group;
	}
}