<?php

use Model\Auth;

abstract class Controller_Base extends Controller_Hybrid
{
	protected $auth_required = false;

	protected $auth_force_check = array();

	protected $auth_skip_check = array();

	protected $template_for_action = array();

	protected $template_defaults = array(
		'title' => 'FuelPHP Bin',
	);

	protected $body_class;

	public function before()
	{
		if (isset($this->template_for_action[$this->request->action]))
		{
			$this->template = $this->template_for_action[$this->request->action];
		}

		parent::before();

		$is_authenticated = Auth::check();
		$is_ajax = Input::is_ajax();

		if ( ! $is_authenticated and $this->needs_auth_check())
		{
			if ($is_ajax)
			{
				return $this->response(array('status' => 'Not Authorized'), 401);
			}

			Session::set('post_login', Uri::current());
			Response::redirect('/');
		}

		if ($is_ajax)
		{
			return;
		}

		$this->template->set($this->template_defaults);
		View::set_global('user', Auth::get_user());
		$this->template->footer = View::forge('partial/footer');
		$account_view = $is_authenticated ? 'partial/account' : 'partial/login';
		$this->template->header = View::forge('partial/header', array(
			'login' => View::forge($account_view),
			'author' => 'FuelPHP',
			'body_class' => '',
		));

		if ($this->body_class)
		{
			$this->template->header->body_class = $this->body_class;
		}
	}

	public function set_title($title)
	{
		$this->template->header->set('title', $title.' | FuelPHP Bin');

		return $this;
	}

	public function set_body_class($class)
	{
		$this->template->header->set('body_class', $class);

		return $this;
	}

	public function needs_auth_check()
	{
		$action = $this->request->action;

		if (in_array($action, $this->auth_force_check))
		{
			return true;
		}

		if (in_array($action, $this->auth_skip_check))
		{
			return false;
		}

		return $this->auth_required;
	}
}
