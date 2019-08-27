<?php

namespace Fuel\Migrations;

class Add_api_id_to_users
{
	public function up()
	{
		\DBUtil::add_fields('users', array(
			'api_id' => array('constraint' => 10, 'type' => 'varchar'),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('users', array(
			'api_id'

		));
	}
}