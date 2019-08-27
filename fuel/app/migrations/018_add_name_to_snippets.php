<?php

namespace Fuel\Migrations;

class Add_name_to_snippets
{
	public function up()
	{
		\DBUtil::add_fields('snippets', array(
			'name' => array('constraint' => 255, 'type' => 'varchar', 'null' => true),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('snippets', array(
			'name'

		));
	}
}