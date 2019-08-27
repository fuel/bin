<?php

namespace Fuel\Migrations;

class Add_mode_to_snippets
{
	public function up()
	{
		\DBUtil::add_fields('snippets', array(
			'mode' => array('constraint' => 10, 'type' => 'varchar', 'default' => 'php'),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('snippets', array(
			'mode'

		));
	}
}