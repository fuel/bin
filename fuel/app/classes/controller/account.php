<?php

use Model\User;
use Model\Auth;
use Model\Group;
use Model\Snippet;

class Controller_Account extends Controller_Base
{
	protected $auth_required = true;

	public $template = 'page/layout';

	public function before()
	{
		parent::before();
		if ( ! Input::is_ajax())
		{
			$this->set_body_class('account');
		}
	}

	public function get_index()
	{
		$this->set_title('Your Profile');
		$this->template->information = View::forge('account/information', array(
			'account' => Auth::get_user(),
		));
	}

	public function get_groups()
	{
		$this->set_title('Your Groups');
		$this->template->information = View::forge('account/groups', array(
			'groups' => Auth::get_user()->get_groups(false),
		));
	}

	public function post_groups()
	{
		if ( ! $id = Input::post('id') or ! $group = Group::find_by_pk($id))
		{
			return Response::forge(json_encode(array(
				'error' => 'Invalid ID provided',
			)), 500);
		}

		if ($group->user_id !== Auth::get_user()->id)
		{
			return Response::forge(json_encode(array(
				'error' => 'This is not the group you are looking for',
			)), 401);
		}

		$group->name = Input::post('new_name');
		if ($group->save())
		{
			return array(
				'status' => 'OK',
			);
		}

		return Response::forge(json_encode(array(
			'error' => 'Updating the group name failed.',
		)), 500);
	}

	public function post_delete_group()
	{
		$id = Input::post('id');

		if ( ! $id or ! $group = Group::find_by_pk($id))
		{
			return Response::forge(json_encode(array(
				'error' => 'Invalid ID provided',
			)), 500);
		}

		if ($group->user_id !== Auth::get_user()->id)
		{
			return Response::forge(json_encode(array(
				'error' => 'This is not the group you are looking for',
			)), 401);
		}

		$group->delete();
	}

	public function post_delete_group_snippets()
	{
		$id = Input::post('id');

		if ( ! $id)
		{
			return Response::forge(json_encode(array(
				'error' => 'Invalid ID provided',
			)), 500);
		}

		DB::delete('snippets')
			->where('user_id', Auth::get_user()->id)
			->where('group_id', $id)
			->execute();

		return array(
			'status' => 'Snippets are deleted',
		);
	}

	public function get_search()
	{
		$this->set_title('Search');
		$this->template->information = View::forge('account/search');
	}

	public function post_search()
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
