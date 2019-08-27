<?php

namespace Fuel\Migrations;

class Add_slug_to_groups
{
	public function up()
	{
		\DBUtil::add_fields('groups', array(
			'slug' => array('constraint' => 100, 'type' => 'varchar'),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('groups', array(
			'slug'

		));
	}
}