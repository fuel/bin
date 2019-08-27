<?php

namespace Fuel\Migrations;

class Add_parent_id_to_users
{
	public function up()
	{
		\DBUtil::add_fields('users', array(
			'parent_id' => array('constraint' => 11, 'type' => 'int'),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('users', array(
			'parent_id'

		));
	}
}