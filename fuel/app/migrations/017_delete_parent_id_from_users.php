<?php

namespace Fuel\Migrations;

class Delete_parent_id_from_users
{
	public function up()
	{
		\DBUtil::drop_fields('users', array(
			'parent_id'

		));
	}

	public function down()
	{
		\DBUtil::add_fields('users', array(
			'parent_id' => array('constraint' => 11, 'type' => 'int'),

		));
	}
}