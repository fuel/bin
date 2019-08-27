<?php

use Model\Snippet;
use Model\Group;
use Model\Auth;

class Controller_Snippet extends Controller_Base
{
	public $template = 'page/snippet';

	protected $template_defaults = array(
		'editable' => false,
		'code' => null,
	);

	public function before()
	{
		$this->template_defaults['form_action'] =  Uri::create('snippet/create');
		parent::before();


		$this->template->set(array(
			'code' => '',
			'name' => null,
			'group' => null,
			'mode' => 'php',
			'private' => 0,
			'is_forkable' => false,
			'is_fork' => false,
			'read_only' => false,
			'parent' => null,
			'url' => null,
			'modes' => Config::get('modes'),
		));

		$this->template->groups = Auth::check() ?
			Auth::get_user()->get_group_names() :
			array();
	}

	public function action_index()
	{
		Response::redirect('snippet/create', 302);
	}

	public function get_create()
	{
		$this->set_title('Create');
	}

	public function post_create()
	{
		$group = Group::find_or_create(Input::post('group'));

		$snippet = array(
			'code' => Input::post('code'),
			'private' => (bool) Input::post('private', false),
			'group_id' => $group ? $group->id : null,
			'parent_id' => Input::post('parent_id'),
			'mode' => Input::post('mode', 'text'),
			'name' => Input::post('name'),
		);

		if (Auth::check())
		{
			$snippet['user_id'] = Auth::get_user()->id;
		}

		Session::set_flash('message', array(
			'message' => 'The snippet was saved!',
			'label' => '',
			'type' => 'success',
		));

		if ($snippet = Snippet::create($snippet))
		{
			Response::redirect($snippet->get_url());
		}
	}

	public function post_edit($slug, $security)
	{
		if ($slug === null or ! $snippet = Snippet::find_by_slug($slug, $security))
		{
			return $this->action_404($slug);
		}

		// TODO
	}

	public function get_edit($slug = null, $security = null)
	{
		$this->get_view($slug, $security);
		$this->template->read_only = false;
		$this->set_title('Edit Snippet');
	}

	public function get_fork($slug = null, $security = null)
	{
		$this->get_view($slug, $security);
		$this->template->read_only = false;
		$this->template->is_fork = true;
		$this->set_title('Fork Snippet');
	}

	public function get_view($slug = null, $security = null)
	{
		if ($slug === null or ! $snippet = Snippet::find_by_slug($slug, $security))
		{
			throw new HttpNotFoundException('Could not locate snippet with slug: '.$slug);
		}

		$this->template->read_only = true;
		$this->template->snippet = $snippet;
		$this->template->parent = $snippet->get_parent();
		$this->template->set($snippet->to_array());
		$this->template->is_forkable = $snippet->is_forkable();
		$this->template->set_safe('code', $snippet->code);
		$this->template->url = $snippet->get_url('short');
		$this->set_title('View Snippet');
	}

	public function get_raw($slug = null, $security = null)
	{
		if ( ! $slug or ! $snippet = Snippet::find_by_slug($slug, $security))
		{
			return $this->action_404($slug);
		}

		return $snippet->code;
	}
}
