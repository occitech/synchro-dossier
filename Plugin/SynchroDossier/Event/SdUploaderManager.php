<?php

App::uses('CakeEventListener', 'Event');
App::uses('SdUser', 'SdUsers.Model');
App::uses('CakeEmail', 'Network/Email');

class SdUploaderManager implements CakeEventListener {

	public function implementedEvents() {
		return array(
			'Model.beforeSave' => 'addUsernameHardcodedInTable'
		);
	}

	public function addUsernameHardcodedInTable($event) {
		$modelsToCatch = array('UploadedFile', 'FileStorage');
		$model = $event->subject();
		if (in_array($model->name, $modelsToCatch)) {
			if (!isset($event->subject()->data[$model->alias]['id'])) {
				$UserModel = ClassRegistry::init('User');
				$user = array();
				if (!empty($event->subject()->data[$model->alias]['user_id'])) {
					$user = $UserModel->findById($event->subject()->data[$model->alias]['user_id']);
				}
				if (empty($user)) {
					$event->subject()->data[$model->alias]['uploader_name'] = '';
				} else {
					$event->subject()->data[$model->alias]['uploader_name'] = $user['User']['name'];
				}
			}
		}
	}
}
