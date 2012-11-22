<?php
App::uses('LogsAppModel', 'Logs.Model');

class SdLog extends SdLogsAppModel {

	public function getLastest($type, $model = null, $foreignKey = null) {
		$result = $this->find('first',array(
			'conditions' => array(
				'type' => $type,
				'model' => $model,
				'foreign_key' => $foreignKey
			),
			'order' => 'created DESC'
		));

		return $result;
	}
}
