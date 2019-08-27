<?php

namespace Fuel\Migrations;

class Delete_email_from_users
{
	public function up()
	{
		\DBUtil::drop_fields('users', array(
			'email'

		));
	}

	public function down()
	{
		\DBUtil::add_fields('users', array(
			'email' => array('constraint' => 255, 'type' => 'varchar'),
		));
	}
}