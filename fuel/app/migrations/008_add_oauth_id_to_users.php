<?php

namespace Fuel\Migrations;

class Add_oauth_id_to_users
{
	public function up()
	{
		\DBUtil::add_fields('users', array(
			'oauth_id' => array('constraint' => 255, 'type' => 'int'),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('users', array(
			'oauth_id'

		));
	}
}