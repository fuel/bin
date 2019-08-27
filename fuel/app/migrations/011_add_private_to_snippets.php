<?php

namespace Fuel\Migrations;

class Add_private_to_snippets
{
	public function up()
	{
		\DBUtil::add_fields('snippets', array(
			'private' => array('type' => 'boolean'),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('snippets', array(
			'private'

		));
	}
}