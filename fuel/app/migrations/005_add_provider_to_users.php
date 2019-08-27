<?php

namespace Fuel\Migrations;

class Add_provider_to_users
{
	public function up()
	{
		\DBUtil::add_fields('users', array(
			'provider' => array('constraint' => 20, 'type' => 'varchar'),
		));
	}

	public function down()
	{
		\DBUtil::drop_fields('users', array(
			'provider'
		));
	}
}