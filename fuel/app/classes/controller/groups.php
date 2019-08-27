<?php

use Model\Auth;
use Model\Group;

class Controller_Groups extends Controller_Base
{
	protected $auth_required = true;
	public $body_class = 'account';
	public $template = 'page/layout';

	public function get_index()
	{
		$this->set_title('Your Groups');
		$this->template->information = View::forge('account/groups', array(
			'groups' => Auth::get_user()->get_groups(false),
		));
	}

	public function post_index()
	{
		if ( ! $id = Input::post('id') or ! $group = Group::find_by_pk($id))
		{
			return $this->response(array(
				'error' => 'Invalid ID provided',
			), 500);
		}

		if ($group->user_id !== Auth::get_user()->id)
		{
			return $this->response(array(
				'error' => 'This is not the group you are looking for',
			), 401);
		}

		$group->name = Input::post('new_name');

		if ($group->save())
		{
			return array(
				'status' => 'OK',
			);
		}

		return $this->response(array(
			'error' => 'Updating the group name failed.',
		), 500);
	}

	public function get_view($slug = null)
	{
		if ( ! $slug or ! $group = Group::find_one_by('slug', $slug))
		{
			throw new HttpNotFoundException('Could not locate group: '.$slug);
		}

		$this->set_title('Group: '.$group->name);

		$this->template->information = View::forge('account/group', array(
			'group' => $group,
			'snippets' => $group->get_snippets(array(
				'limit' => 20,
				'offset' => 0
			)),
		));
	}

	public function post_delete()
	{
		$id = Input::post('id');

		if ( ! $id or ! $group = Group::find_by_pk($id))
		{
			return $this->response(array(
				'error' => 'Invalid ID provided',
			), 500);
		}

		if ($group->user_id !== Auth::get_user()->id)
		{
			return $this->response(array(
				'error' => 'This is not the group you are looking for',
			), 401);
		}

		$group->delete();

		return array('status' => 'deleted');
	}

	public function post_delete_snippets()
	{
		$id = Input::post('id');

		if ( ! $id)
		{
			return $this->response(array(
				'error' => 'Invalid ID provided',
			), 500);
		}

		DB::delete('snippets')
			->where('user_id', Auth::get_user()->id)
			->where('group_id', $id)
			->execute();

		return array(
			'status' => 'Snippets are deleted',
		);
	}
}