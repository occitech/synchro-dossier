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
		$linkModel = ClassRegistry::init('Menus.Link');
		
		// Change Admin by Occitech
		$roleModel->updateAll(
			array('Role.title' => "'Occitech'"),
			array('Role.alias' => 'admin')
		);

		// Add 3 new roles 
		$roles = array(
			array('id' => '4', 'title' => 'SuperAdmin', 'alias' => 'sdSuperAdmin'),
			array('id' => '5', 'title' => 'Admin', 'alias' => 'sdAdmin'),
			array('id' => '6', 'title' => 'Utilisateurs', 'alias' => 'sdUtilisateurs')
		);
		$roleModel->saveMany($roles, array('deep' => true));

		// Add link to add user in menu
		$linkModel->deleteAll(array('menu_id' => 3));
		$links = array(
			array(
				'menu_id' => 3,
				'title' => 'Accueil',
				'class' => 'accueil',
				'link' => '/',
				'visibility_roles' => '["1","4","5","6"]'
			),
			array(
				'menu_id' => 3,
				'title' => 'Ajouter un utilisateur',
				'class' => 'add-user',
				'link' => 'admin:true/plugin:users/controller:users/action:add',
				'visibility_roles' => '["1","4"]'
			)
		);
		$linkModel->saveMany($links, array('deep' => true));
	}

	protected function _afterDown() {
		$roleModel = ClassRegistry::init('Users.Role');

		// Remove 3 roles
		$roleModel->deleteAll(array('alias' => 'sdSuperAdmin'), true, true);
		$roleModel->deleteAll(array('alias' => 'sdAdmin'), true, true);
		$roleModel->deleteAll(array('alias' => 'sdUtilisateurs'), true, true);

		// Change Occitech by Admin
		$roleModel->updateAll(
			array('Role.title' => "'Admin'"),
			array('Role.alias' => 'admin')
		);
	}
}
