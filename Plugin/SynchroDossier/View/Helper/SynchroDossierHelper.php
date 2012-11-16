<?php

App::uses('AppHelper', 'View/Helper');

class SynchroDossierHelper extends AppHelper {

	public function getQuotaData() {
		return $this->_View->getVar('quota');
	}

	public function displayQuota($element = 'SynchroDossier.displayQuota') {
		$quota = $this->getQuotaData();
		$element = $this->_View->element($element, array(
			'quota' => round($quota['quota_mb'] / 1024, 2),
			'current_quota' => round($quota['current_quota_mb'] / 1024, 2),
			'percent' => $quota['current_quota_mb'] / $quota['quota_mb'] * 100
		));

		return $element;
	}
}