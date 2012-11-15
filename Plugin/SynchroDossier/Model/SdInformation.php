<?php

App::uses('SynchroDossierAppModel', 'SynchroDossier.Model');

class SdInformation extends SynchroDossierAppModel {
	public $useTable = 'sd_information';

	public $validate = array(
		'quota_mb' => array(
			'comparison' => array(
				'rule' => array('comparison', '>', 0),
				'message' => 'Quota must be highter than 0',
				'required' => true
			),
		),
	);

	public function getUsedQuota() {
		$data = $this->find('first');

		return $data['SdInformation']['current_quota_mb'];
	}
}
