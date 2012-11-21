<?php

App::uses('SynchroDossierAppModel', 'SynchroDossier.Model');

class SdInformation extends SynchroDossierAppModel {

	public $virtualFields = array();

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

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->virtualFields['remaining_quota'] =
			 $this->alias . '.quota_mb - ' . $this->alias . '.current_quota_mb';
	}

	public function getUsedQuota() {
		$data = $this->find('first');

		return $data['SdInformation']['current_quota_mb'];
	}

	public function remainingQuota() {
		return $this->field('remaining_quota');
	}
}
