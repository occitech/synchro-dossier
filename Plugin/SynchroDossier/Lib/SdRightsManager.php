<?php

App::uses('CakeEventListener', 'Event');
App::uses('SdUser', 'SdUsers.Model');
App::uses('CakeEmail', 'Network/Email');

class SdRightsManager implements CakeEventListener {

	public $cakeEmail = null;

	public function __construct() {
		$this->cakeEmail = new CakeEmail();
	}

	public function implementedEvents() {
		return array(
			'Controller.FilesController.afterChangeRight' => 'setAdminAllRights'
		);
	}

	public function setAdminAllRights($event) {
		if ($event->data['method'] == '_allowRight') {
			$SdUserModel = ClassRegistry::init('SdUsers.SdUser');
			$user = $SdUserModel->findById($event->data['user']['id']);

			if ($user['User']['role_id'] == Configure::read('sd.Admin.roleId')) {
				$PermissionModel = ClassRegistry::init('Permission');
				$PermissionModel->id = $event->data['foreign_key'];
				$data = array(
					'_create' => 1,
					'_delete' => 1,
					'_change_right' => 1
				);
				$PermissionModel->save($data);
			}
		}
	}
}