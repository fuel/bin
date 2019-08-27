<?php

namespace Fuel\Migrations;

class Create_users
{
	public function up()
	{
		\DBUtil::create_table('users', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'name' => array('constraint' => 255, 'type' => 'varchar'),
			'email' => array('constraint' => 255, 'type' => 'varchar'),
			'password' => array('constraint' => 255, 'type' => 'varchar', 'null' => true),
			'username' => array('constraint' => 255, 'type' => 'varchar'),
			'api_token' => array('constraint' => 255, 'type' => 'varchar'),
			'created_at' => array('type' => 'timestamp', 'default' => '0000-00-00 00:00:00'),
			'modified_at' => array('type' => 'timestamp', 'default' => '0000-00-00 00:00:00'),
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('users');
	}
}