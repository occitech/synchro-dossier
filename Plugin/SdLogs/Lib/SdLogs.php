<?php

App::uses('CakeEventListener', 'Event');
App::uses('SdUser', 'SdUsers.Model');
App::uses('CakeEmail', 'Network/Email');

class SdLogs implements CakeEventListener {

	const CHANGE_RIGHT = 1;

	public function implementedEvents() {
		return array(
			'Model.UploadedFile.afterUploadSuccess' => 'afterUploadSuccess',
		);
	}

	public function afterUploadSuccess($event) {
		$LogModel = ClassRegistry::init('SdLogs.SdLog');
		$UserModel = ClassRegistry::init('SdUsers.SdUser');

		$user = $UserModel->findById($event->data['user']['id']);

		$data = array(
			'user_id' => $user['User']['id'],
			'name' => $user['Profile']['name'],
			'firstname' => $user['Profile']['firstname'],
			'email' => $user['User']['email']
		);

		$logData = array(
			'model' => $event->subject()->alias,
			'foreign_key' => $event->subject()->id,
			'type' => self::CHANGE_RIGHT,
			'data' => serialize($data)
		);

		$LogModel->save($logData);
	}

}