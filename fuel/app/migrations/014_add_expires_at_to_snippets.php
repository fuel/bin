<?php

namespace Fuel\Migrations;

class Add_expires_at_to_snippets
{
	public function up()
	{
		\DBUtil::add_fields('snippets', array(
			'expires_at' => array('constraint' => 20, 'type' => 'int'),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('snippets', array(
			'expires_at'

		));
	}
}