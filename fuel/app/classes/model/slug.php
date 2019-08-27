<?php

namespace Model;

use \DB;

abstract class Slug
{
	protected static $range = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_';

	public static $last = null;

	public static function generate()
	{
		$last_slug = static::last_slug();

		if ($last_slug === null)
		{
			return static::$range[0];
		}

		$max_position = strlen(static::$range) - 1;
		$last_length = strlen($last_slug);
		$position = $last_length - 1;

		while ($position > -1)
		{
			$char = $last_slug[$position];
			$char_position = strpos(static::$range, $char);

			if ($char_position === $max_position)
			{
				$last_slug[$position] = static::$range[0];
				$position--;
			}
			else
			{
				$last_slug[$position] = static::$range[$char_position+1];

				return $last_slug;
			}
		}

		return str_repeat(static::$range[0], $last_length+1);
	}

	public static function last_slug()
	{
		return DB::select('slug')
			->from('snippets')
			->order_by('id', 'desc')
			->limit(1)
			->execute()
			->get('slug');
	}
}