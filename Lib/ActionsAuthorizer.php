<?php
App::uses('AclExtras', 'Acl.Lib');
class ActionsAuthorizer {

	public $AclAco;

	public function __construct() {
		$this->AclAco = ClassRegistry::init('Acl.AclAco');
	}

	public function allowRolesForAction($action, $roles) {
		return $this->AclAco->addAco($action, $roles);
	}

	public function removeRolesForAction($action, $roles) {
		return $this->AclAco->removeAco($action);
	}

}
