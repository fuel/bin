<?php

namespace Fuel\Migrations;

class Add_source_to_snippets
{
	public function up()
	{
		\DBUtil::add_fields('snippets', array(
			'source' => array('constraint' => 10, 'type' => 'varchar', 'default' => 'web'),
		));
	}

	public function down()
	{
		\DBUtil::drop_fields('snippets', array(
			'source'

		));
	}
}