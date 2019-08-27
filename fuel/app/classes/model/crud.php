<?php

class Model_Crud extends Fuel\Core\Model_Crud
{
	public function to_json()
	{
		return json_encode($this->to_array());
	}
}