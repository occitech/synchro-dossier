<?php
App::uses('AclExtras', 'Acl.Lib');
App::uses('PhpReader', 'Configure');

class AclLoader {

	public $Reader;
	public $AclExtras;
	private $__configFile;

	const ALLOW = 1;
	const DISALLOW = -1;

	public function __construct($configFileName, ConfigReaderInterface $Reader = null, AclExtras $AclExtras = null) {
		$this->Reader = is_null($Reader) ? new PhpReader() : $Reader;
		$this->AclExtras = is_null($AclExtras) ? new AclExtras() : $AclExtras;

		$this->__configFile = $configFileName;
	}

	public function addAuthorizations() {
		$this->__syncAco();
		return $this->__updatePermissions(self::ALLOW);
	}

	public function removeAuthorizations() {
		$this->__syncAco();
		return $this->__updatePermissions(self::DISALLOW);
	}

	private function __syncAco() {
		$this->AclExtras->startup();
		$this->AclExtras->aco_sync();
	}

	private function __updatePermissions($allowanceCode) {
		$success = true;
		$authorizations = (array) $this->Reader->read($this->__configFile);
		foreach ($authorizations as $action => $roles) {
			$success = $success && $this->__updateActionPermissionsFor($action, $roles, $allowanceCode);
		}
		return $success;
	}

	private function __updateActionPermissionsFor($action, $roles, $allow) {
		$rolesData = ClassRegistry::init('Users.Role')->find('list', array(
			'conditions' => array('Role.alias' => $roles),
			'fields' => array('Role.id', 'Role.alias'),
		));

		$Permission = ClassRegistry::init('Acl.AclPermission');
		$success = true;
		foreach (array_keys($rolesData) as $roleId) {
			$success = $success && $Permission->allow(array('model' => 'Role', 'foreign_key' => $roleId), $action, '*', $allow);
		}
		return $success;
	}

}
