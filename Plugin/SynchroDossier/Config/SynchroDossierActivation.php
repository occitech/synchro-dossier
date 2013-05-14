<?php

class SynchroDossierActivation {

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
			'link' => 'admin:true/plugin:sd_users/controller:sd_users/action:add',
			'visibility_roles' => '["1","4"]'
		),
		array(
			'menu_id' => 3,
			'title' => 'Ajouter un partage',
			'class' => 'add-partage',
			'link' => 'plugin:uploader/controller:files/action:createSharing',
			'visibility_roles' => '["1","4","5"]'
		),
		array(
			'menu_id' => 3,
			'title' => 'Logout',
			'class' => 'add-partage',
			'link' => 'plugin:users/controller:users/action:logout',
			'visibility_roles' => '["1","4","5","6"]'
		)
	);

	private $__sdRoles = array('sdSuperAdmin', 'sdUtilisateur', 'sdAdmin');

	public function onActivation(&$controller) {
		$this->Role = ClassRegistry::init('Users.Role');
		$this->Link = ClassRegistry::init('Menus.Link');

		App::uses('CroogoPlugin', 'Extensions.Lib');
		$CroogoPlugin = new CroogoPlugin();
		$CroogoPlugin->migrate('SynchroDossier');

		$success =
				$this->__renameAdminRole('Occitech') &&
				$this->__addSynchroRoles() &&
				$this->__removeAllMainMenuLink() &&
				$this->__addNewMainMenuLink() &&
				$this->__addSuperAdminAllRightOnUploadedFile($controller) &&
				$this->__addAllUsersAcos($controller);

		return $success;
	}

	public function onDeactivation(&$controller) {
		App::uses('CroogoPlugin', 'Extensions.Lib');
		$CroogoPlugin = new CroogoPlugin();
		$CroogoPlugin->unmigrate('SynchroDossier');

		$success =
			$this->__removeSynchroRoles() &&
			$this->__renameAdminRole('Admin');
	}

	public function beforeActivation(&$controller) {
		return true;
	}

	public function beforeDeactivation(&$controller) {
		return true;
	}

	private function __addSuperAdminAllRightOnUploadedFile(&$controller) {
		$controller->Acl->allow(
			array('model' => 'Role', 'foreign_key' => Configure::read('sd.SuperAdmin.roleId')),
			Configure::read('sd.uploadedFileRootAco.alias')
		);
		return true;
	}

	private function __renameAdminRole($name) {
		return $this->Role->updateAll(
			array('Role.title' => "'$name'"),
			array('Role.alias' => 'admin')
		);
	}

	private function __addSynchroRoles() {
		return $this->Role->saveMany($this->__roles, array('deep' => true));
	}

	private function __removeSynchroRoles() {
		return $this->Role->deleteAll(array('alias' => Hash::extract($this->__roles, '{n}.alias'), true, true));
	}

	private function __removeAllMainMenuLink() {
		return $this->Link->deleteAll(array('menu_id' => 3));
	}

	private function __addNewMainMenuLink() {
		return $this->Link->saveMany($this->__links, array('deep' => true));
	}
}
