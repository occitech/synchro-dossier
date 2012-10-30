<?php

class SynchroDossierActivation {

	private $__roles = array(
		array('id' => '4', 'title' => 'SuperAdmin', 'alias' => 'sdSuperAdmin'),
		array('id' => '5', 'title' => 'Admin', 'alias' => 'sdAdmin'),
		array('id' => '6', 'title' => 'Utilisateur', 'alias' => 'sdUtilisateur')
	);

	private $__usersCRUDAcos = array(
		'SdUsers/SdUsers/admin_index',
		'SdUsers/SdUsers/admin_edit',
		'SdUsers/SdUsers/admin_add',
		'SdUsers/SdUsers/admin_delete',
		'Users/Users/admin_logout'
	);

	private $__filesAcos = array(
		'Uploader/Files/browse',
		'Uploader/Files/upload',
		'Uploader/Files/download',
		'Uploader/Files/rename',
		'Uploader/Files/createFolder',
		'Uploader/Files/downloadZipFolder'
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
			'visibility_roles' => '["1","4"]'
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

		$success =
				$this->__renameAdminRole('Occitech') &&
				$this->__addSynchroRoles() &&
				$this->__removeAllMainMenuLink() &&
				$this->__addNewMainMenuLink() &&
				$this->__addUsersCRUDArcos($controller) &&
				$this->__addFilesAcos($controller);

		return $success;
	}

	public function onDeactivation(&$controller) {
		$success =
			$this->__removeAllAcos($controller) &&
			$this->__removeSynchroRoles() &&
			$this->__renameAdminRole('Admin');
	}

	public function beforeActivation(&$controller) {
		return true;
	}

	public function beforeDeactivation(&$controller) {
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

	private function __addUsersCRUDArcos (&$controller) {
		foreach ($this->__usersCRUDAcos as $aco) {
			$controller->Croogo->removeAco($aco);
			$controller->Croogo->addAco($aco, array('sdSuperAdmin', 'sdAdmin'));
		}
		return true;
	}

	private function __addFilesAcos(&$controller) {
		foreach ($this->__filesAcos as $aco) {
			$controller->Croogo->addAco($aco, $this->__sdRoles);
		}
		return true;		
	}

	private function __removeAllAcos(&$controller) {
		$allAcos = array_merge($this->__usersCRUDAcos, $this->__filesAcos);
		foreach ($allAcos as $aco) {
			$controller->Croogo->removeAco($aco);
		}		
	}
}
