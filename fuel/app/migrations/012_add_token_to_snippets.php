<?php

namespace Fuel\Migrations;

class Add_token_to_snippets
{
	public function up()
	{
		\DBUtil::add_fields('snippets', array(
			'token' => array('constraint' => 100, 'type' => 'varchar'),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('snippets', array(
			'token'

		));
	}
}