<?php

App::uses('ClassRegistry', 'Utility');

class AddRoles extends CakeMigration {

	public $description = '';

	public $migration = array(
		'up' => array(),
		'down' => array(),
	);

	private $__roles = array(
		array('id' => '4', 'title' => 'SuperAdmin', 'alias' => 'sdSuperAdmin'),
		array('id' => '5', 'title' => 'Admin', 'alias' => 'sdAdmin'),
		array('id' => '6', 'title' => 'Utilisateur', 'alias' => 'sdUtilisateur')
	);

	private $__links = array(
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

	private function __renameAdminRole($name) {
		return $this->Role->updateAll(
			array('Role.title' => "'$name'"),
			array('Role.alias' => 'admin')
		);
	}

	private function __addSynchroRoles() {
		return $this->Role->saveMany($this->__roles, array('deep' => true));
	}

	private function __removeAllMainMenuLink() {
		return $this->Link->deleteAll(array('menu_id' => 3));
	}

	private function __addNewMainMenuLink() {
		return $this->Link->saveMany($this->__links, array('deep' => true));
	}

	private function __removeSynchroRoles() {
		return $this->Role->deleteAll(array('alias' => Hash::extract($this->__roles, '{n}.alias'), true, true));
	}

	public function before($direction) {
		return true;
	}

	public function after($direction) {
		$this->Role = ClassRegistry::init('Users.Role');
		$this->Link = ClassRegistry::init('Menus.Link');
		$success = true;

		if ($direction == 'up') {
			$success = 
				$this->__renameAdminRole('Occitech') &&
				$this->__addSynchroRoles() &&
				$this->__removeAllMainMenuLink() &&
				$this->__addNewMainMenuLink();
		} else {
			$success =
				$this->__removeSynchroRoles() &&
				$this->__renameAdminRole('Admin');
		}
		return $success;
	}
}
