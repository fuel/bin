<?php

namespace Fuel\Migrations;

class Add_parent_id_to_snippets
{
	public function up()
	{
		\DBUtil::add_fields('snippets', array(
			'parent_id' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('snippets', array(
			'parent_id'

		));
	}
}