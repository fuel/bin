<?php

namespace Fuel\Migrations;

class Add_last_api_call_to_users
{
	public function up()
	{
		\DBUtil::add_fields('users', array(
			'last_api_call' => array('constraint' => 20, 'type' => 'int'),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('users', array(
			'last_api_call'

		));
	}
}