<?php

namespace Fuel\Migrations;

class Create_snippets
{
	public function up()
	{
		\DBUtil::create_table('snippets', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'user_id' => array('constraint' => 11, 'type' => 'int'),
			'group_id' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'code' => array('type' => 'text', 'null' => false),
			'created_at' => array('type' => 'timestamp', 'default' => '0000-00-00 00:00:00'),
			'modified_at' => array('type' => 'timestamp', 'default' => '0000-00-00 00:00:00'),
		), array('id'), false, 'MYISAM');
	}

	public function down()
	{
		\DBUtil::drop_table('snippets');
	}
}