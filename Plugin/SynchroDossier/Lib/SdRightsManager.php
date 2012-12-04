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
			'Controller.FilesController.afterChangeRight' => 'updateRights'
		);
	}

	public function updateRights($event) {
		if ($event->data['method'] == '_allowRight') {
			$SdUserModel = ClassRegistry::init('SdUsers.SdUser');
			$user = $SdUserModel->findById($event->data['user']['id']);

			if ($user['User']['role_id'] == Configure::read('sd.Admin.roleId')) {
				$PermissionModel = ClassRegistry::init('Permission');
				$PermissionModel->id = $event->data['foreign_key'];
				$PermissionModel->saveField('_create', 1);
				$PermissionModel->saveField('_delete', 1);
				$PermissionModel->saveField('_change_right', 1);
			}
		}
	}
}