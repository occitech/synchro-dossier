<?php

App::uses('SynchroDossierAppModel', 'SynchroDossier.Model');

class SdInformation extends SynchroDossierAppModel {

	public $virtualFields = array(
    	'remaining_quota' => 'SdInformation.quota_mb - SdInformation.current_quota_mb'
	);

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

	public function remainingQuota() {
		$quotas = $this->find('first');

		return $quotas['SdInformation']['remaining_quota'];
	}
}
