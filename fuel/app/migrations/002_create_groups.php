<?php

namespace Fuel\Migrations;

class Create_groups
{
	public function up()
	{
		\DBUtil::create_table('groups', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'user_id' => array('constraint' => 11, 'type' => 'int'),
			'name' => array('constraint' => 30, 'type' => 'varchar'),
			'position' => array('constraint' => 11, 'type' => 'int', 'default' => 0),
			'created_at' => array('type' => 'timestamp', 'default' => '0000-00-00 00:00:00'),
			'modified_at' => array('type' => 'timestamp', 'default' => '0000-00-00 00:00:00'),
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('groups');
	}
}