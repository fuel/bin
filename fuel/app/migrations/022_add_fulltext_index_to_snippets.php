<?php

namespace Fuel\Migrations;

class Add_fulltext_index_to_snippets
{
	public function up()
	{
		\DBUtil::create_index('snippets', array('name', 'code'), 'snippet_search', 'fulltext');
	}

	public function down()
	{
		\DBUtil::drop_index('snippets', 'snippet_search');
	}
}