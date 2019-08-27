<?php

namespace Fuel\Migrations;

class Rename_field_username_to_nickname_in_users
{
	public function up()
	{
		\DBUtil::modify_fields('users', array(
			'username' => array('name' => 'nickname', 'type' => 'varchar', 'constraint' => 255)
		));
	}

	public function down()
	{
	\DBUtil::modify_fields('users', array(
			'nickname' => array('name' => 'username', 'type' => 'varchar', 'constraint' => 255)
		));
	}
}