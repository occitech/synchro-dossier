<?php

App::uses('ClassRegistry', 'Utility');

class AddRoles extends CakeMigration {

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

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function after($direction) {
		if ($direction == 'up') {
			$this->_afterUp();
		} else {
			$this->_afterDown();
		}
		return true;
	}

	protected function _afterUp() {
		$roleModel = ClassRegistry::init('Users.Role');

		// Change Admin by Occitech
		$roleModel->updateAll(
			array('Role.title' => "'Occitech'"),
			array('Role.alias' => 'admin')
		);

		// Add 3 new roles 
		$roles = array(
			array('title' => 'SuperAdmin', 'alias' => 'sdSuperAdmin'),
			array('title' => 'Admin', 'alias' => 'sdAdmin'),
			array('title' => 'Utilisateurs', 'alias' => 'sdUtilisateurs')
		);
		$roleModel->saveMany($roles, array('deep' => true));
	}

	protected function _afterDown() {
		$roleModel = ClassRegistry::init('Users.Role');

		// Remove 3 roles
		$roleModel->deleteAll(array('alias' => 'sdSuperAdmin'), false, true);
		$roleModel->deleteAll(array('alias' => 'sdAdmin'), false, true);
		$roleModel->deleteAll(array('alias' => 'sdUtilisateurs'), false, true);

		// Change Occitech by Admin
		$roleModel->updateAll(
			array('Role.title' => "'Admin'"),
			array('Role.alias' => 'admin')
		);
	}
}
