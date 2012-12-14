<?php

App::uses('CakeEventListener', 'Event');
App::uses('SdUser', 'SdUsers.Model');
App::uses('CakeEmail', 'Network/Email');

class SdUploaderManager implements CakeEventListener {

	public $cakeEmail = null;

	public function __construct() {
		$this->cakeEmail = new CakeEmail();
	}

	public function implementedEvents() {
		return array(
			'Model.beforeSave' => 'addUsernameHardcodedInTable'
		);
	}

	public function addUsernameHardcodedInTable($event) {
		$modelsToCatch = array('UploadedFile', 'FileStorage');
		$model = $event->subject();
		if (in_array($model->name, $modelsToCatch)) {
			$UserModel = ClassRegistry::init('User');
			$user = $UserModel->findById($event->subject()->data[$model->alias]['user_id']);
			$event->subject()->data[$model->alias]['uploader_name'] = $user['User']['name'];
		}
	}
}