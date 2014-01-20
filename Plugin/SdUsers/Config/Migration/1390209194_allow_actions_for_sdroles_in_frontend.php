<?php
App::uses('AclExtras', 'Acl.Lib');
class AllowActionsForSdrolesInFrontend extends CakeMigration {

	public $description = 'Fix permissions for users actions';

	public $actions = array(
		'SdUsers/SdUsers/add' => array(
			'sdSuperAdmin', 'sdAdmin'
		),
		'SdUsers/SdUsers/index' => array(
			'sdSuperAdmin', 'sdAdmin'
		),
		'SdUsers/SdUsers/edit' => array(
			'sdSuperAdmin', 'sdAdmin', 'sdUtilisateur'
		),
		'SdUsers/SdUsers/profile' => array(
			'sdSuperAdmin', 'sdAdmin', 'sdUtilisateur'
		),
		'SdUsers/SdUsers/reset' => array('public'),
		'SdUsers/SdUsers/forgot' => array('public'),
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

		foreach ($this->actions as $action => $roles) {
			try {
				 $this->{$addAco}($action, $roles);
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
