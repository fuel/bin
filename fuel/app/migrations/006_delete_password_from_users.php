<?php

namespace Fuel\Migrations;

class Delete_password_from_users
{
	public function up()
	{
		\DBUtil::drop_fields('users', array(
			'password'

		));
	}

	public function down()
	{
		\DBUtil::add_fields('users', array(
			'password' => array('constraint' => 255, 'type' => 'varchar', 'null' => true),

		));
	}
}