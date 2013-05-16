<?php

App::uses('AppHelper', 'View/Helper');
App::uses('SdUser', 'SdUsers.Model');

class SdUsersHelper extends AppHelper {

	public $helpers = array('Session', 'Form');
	private $__adminRolesIds = array(
		SdUser::ROLE_OCCITECH_ID,
		SdUser::ROLE_SUPERADMIN_ID,
		SdUser::ROLE_ADMIN_ID,
	);

	private $__userRolesIds = array(SdUser::ROLE_UTILISATEUR_ID);
	private $__displayer;

	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);
		if ($this->isAdmin()) {
			$this->__displayer = new SdUsersDisplayEditable($this->Form);
		} else {
			$this->__displayer = new SdUsersDisplayNotEditable($this->Form);
		}
	}

	public function __call($method, $arguments) {
		if (method_exists($this->__displayer, $method)) {
			return call_user_func_array(array($this->__displayer, $method), $arguments);
		}
		parent::__call($method, $arguments);
	}

/**
 * Check if the user logged in is an admin or not
 * @param  array $userData   array return by a Model::find() or Auth::user().
 * @param  boolean $strict   true, check user is admin only, otherwise return true even if user is superadmin.
 * @return boolean
 */
	public function isAdmin($userData = array(), $strict = false) {
		$isAdmin = false;
		if (empty($userData)) {
			$userData = $this->Session->read('Auth.User');
		}

		if (!empty($userData)) {
			if (array_key_exists('User', $userData)) {
				$userData = $userData['User'];
			}
			if ($strict) {
				$isAdmin = $userData['role_id'] === SdUser::ROLE_ADMIN_ID;
			} else {
				$isAdmin = in_array($userData['role_id'], $this->__adminRolesIds);
			}
		}

		return $isAdmin;
	}
}

class SdUsersDisplayEditable {
	public function __construct($form) {
		$this->Form = $form;
	}
	public function displayHeader($user) {
		return '';
	}
	public function displayFooter($user) {
		return '';
	}
	public function displayLastName($user, $hidden=false) {
		$type = $hidden ? 'hidden' : 'text';
		return $this->_input(
			'Profile.name',
			$user['Profile']['name'],
			__d('sd_users', 'Lastname'),
			$type
		);
	}
	public function displayFirstName($user, $hidden=false) {
		$type = $hidden ? 'hidden' : 'text';
		return $this->_input(
			'Profile.firstname',
			$user['Profile']['firstname'],
			__d('sd_users', 'Firstname'),
			$type
		);
	}
	public function displaySociety($user, $hidden=false) {
		$type = $hidden ? 'hidden' : 'text';
		return $this->_input(
			'Profile.society',
			$user['Profile']['society'],
			__d('sd_users', 'Society'),
			$type
		);
	}
	public function displayMail($user, $hidden=false) {
		$type = $hidden ? 'hidden' : 'email';
		return $this->_input(
			'User.email',
			$user['User']['email'],
			__d('sd_users', 'Email'),
			$type
		);
	}

	public function displayLang($user, $options = array()) {
		$options = array_merge(array(
			'label' => $this->_langLabel(),
			'type'  => 'select',
			'selected' => $user['Profile']['language_id'],
		), $options);
		return $this->Form->input('Profile.language_id', $options); 

	}

	protected function _input($name, $value, $label, $type='text') {
		return $this->Form->input(
			$name, 
			array('label' => $label, 'type' => $type, 'value' => $value)
		);
	}
	protected function _langLabel() {
		return __d('sd_users', 'Language:');
	}
}

class SdUsersDisplayNotEditable extends SdUsersDisplayEditable {

	public function displayHeader($user) {
		return '<dl class="users-informations--list inline">';
	}
	public function displayFooter($user) {
		return '</dl>';
	}
	public function displayLastName($user) {
		$input = parent::displayLastName($user, true);
		return  $this->_dataRow(__d('sd_users', 'Name:'), $user['Profile']['name'] . $input);
	}
	public function displayFirstName($user) {
		$input = parent::displayFirstName($user, true);
		return  $this->_dataRow(__d('sd_users', 'Firstname:'), $user['Profile']['firstname'] . $input);
	}
	public function displaySociety($user) {
		$input = parent::displaySociety($user, true);
		return  $this->_dataRow(__d('sd_users', 'Society:'), $user['Profile']['society'] . $input);
	}
	public function displayMail($user) {
		$input = parent::displayMail($user, true);
		return  $this->_dataRow(__d('sd_users', 'Email:'), $user['User']['email'] . $input);
	}

	public function displayLang($user) {
		$input = parent::displayLang($user, array('label' => false, 'div' => false));
		return $this->_dataRow($this->_langLabel(), $input);
	}

	protected function _dataRow($title, $data) {
		return sprintf('<dt>%s</dt><dd>%s</dd>', $title, $data);
	}
}