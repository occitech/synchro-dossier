<?php

App::uses('SynchroDossierAppModel', 'SynchroDossier.Model');

class SdInformation extends SynchroDossierAppModel {
	public $useTable = 'sd_information';

	public function getUsedQuota() {
		$data = $this->find('first');

		return $data['SdInformation']['current_quota_mb'];
	}
}
