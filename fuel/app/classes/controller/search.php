<?php

class Controller_Search extends Controller_base
{
	public $template = 'page/layout';
	protected $body_class = 'account';

	public function get_index()
	{
		$this->set_title('Search');
		$this->template->information = View::forge('account/search');
	}

	public function post_index()
	{
		$query = Input::param('query');

		if (empty($query))
		{
			return array('results' => array());
		}

		if ($group = Input::post('group') and ! $group = Auth::get_user()->find_group($group))
		{
			return Response::forge(json_encode(array(
				'status' => 'Invalid group',
			)), 500);
		}

		$modes = Config::load('modes', true);
		$result = Snippet::search($query, $group ? $group->id : null);

		return array(
			'results' => array_map(function($snippet) {
				return array(
					'name' => $snippet->name ?: $snippet->get_short_url(),
					'url' => $snippet->get_url(),
					'mode' => Config::get('modes.'.$snippet->mode, 'Plain Text'),
				);
			}, $result)
		);
	}
}
