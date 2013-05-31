<?php
App::uses('AclExtras', 'Acl.Lib');
class AddAccessToForgotPasswordActions extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = '';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(),
		'down' => array(),
	);

	public $actions = array(
		'SdUsers/SdUsers/forgot',
		'SdUsers/SdUsers/reset',
	);

	public $roles = array(
		'public'
	);

	public $RolesActionMethod = array(
		'up'   => '_allowRolesForAction',
		'down' => '_removeRolesForAction'
	);

	public function __construct($options = array()) {
		parent::__construct($options);
		$this->AclAco = ClassRegistry::init('Acl.AclAco');
	}

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		$success = true;
		$addAco = $this->RolesActionMethod[$direction];

		foreach ($this->actions as $action) {
			try {
				 $this->{$addAco}($action, $this->roles);
			} catch (Exception $e) {
				$success = false;
			}
		}

		return $success;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function after($direction) {
		return true;
	}

	protected function _allowRolesForAction($action, $roles) {
		return $this->AclAco->addAco($action, $roles);
	}

	protected function _removeRolesForAction($action, $roles) {
		return $this->AclAco->removeAco($action);
	}
}
