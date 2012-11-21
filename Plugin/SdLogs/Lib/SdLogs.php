<?php

App::uses('CakeEventListener', 'Event');
App::uses('SdUser', 'SdUsers.Model');
App::uses('CakeEmail', 'Network/Email');

class SdLogs implements CakeEventListener {

	const CHANGE_RIGHT = 1;

	public function implementedEvents() {
		return array(
			'Controller.FilesController.afterChangeRight' => 'afterChangeRight',
		);
	}

	protected function _saveLogs($model, $foreignKey, $type, $data) {
		$LogModel = ClassRegistry::init('SdLogs.SdLog');

		$logData = array(
			'model' => $model,
			'foreign_key' => $foreignKey,
			'type' => $type,
			'data' => json_encode($data)
		);

		$LogModel->save($logData);
	}

	public function afterChangeRight($event) {
		$UserModel = ClassRegistry::init('SdUsers.SdUser');

		$user = $UserModel->findById($event->data['user']['id']);

		$data = array(
			'user_id' => $user['User']['id'],
			'name' => $user['Profile']['name'],
			'firstname' => $user['Profile']['firstname'],
			'email' => $user['User']['email']
		);

		$this->_saveLogs($event->data['model'], $event->data['foreign_key'], self::CHANGE_RIGHT, $data);
	}
}