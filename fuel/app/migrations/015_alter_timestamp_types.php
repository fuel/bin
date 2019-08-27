<?php

namespace Fuel\Migrations;

class Alter_timestamp_types
{
	public function up()
	{
		\DBUtil::modify_fields('snippets', array(
			'created_at' => array('constraint' => 20, 'type' => 'int', 'null' => true),
			'modified_at' => array('constraint' => 20, 'type' => 'int', 'null' => true),
		));

		\DBUtil::modify_fields('groups', array(
			'created_at' => array('constraint' => 20, 'type' => 'int', 'null' => true),
			'modified_at' => array('constraint' => 20, 'type' => 'int', 'null' => true),
		));

		\DBUtil::modify_fields('users', array(
			'created_at' => array('constraint' => 20, 'type' => 'int', 'null' => true),
			'modified_at' => array('constraint' => 20, 'type' => 'int', 'null' => true),
		));
	}

	public function down()
	{
		\DBUtil::modify_fields('snippets', array(
			'created_at' => array('type' => 'timestamp', 'default' => '0000-00-00 00:00:00'),
			'modified_at' => array('type' => 'timestamp', 'default' => '0000-00-00 00:00:00'),
		));

		\DBUtil::modify_fields('groups', array(
			'created_at' => array('type' => 'timestamp', 'default' => '0000-00-00 00:00:00'),
			'modified_at' => array('type' => 'timestamp', 'default' => '0000-00-00 00:00:00'),
		));

		\DBUtil::modify_fields('users', array(
			'created_at' => array('type' => 'timestamp', 'default' => '0000-00-00 00:00:00'),
			'modified_at' => array('type' => 'timestamp', 'default' => '0000-00-00 00:00:00'),
		));
	}
}