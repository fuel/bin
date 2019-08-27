<?php

namespace Fuel\Migrations;

class Add_slug_to_snippets
{
	public function up()
	{
		\DBUtil::add_fields('snippets', array(
			'slug' => array('constraint' => 255, 'type' => 'varchar'),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('snippets', array(
			'slug'

		));
	}
}