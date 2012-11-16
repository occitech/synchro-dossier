<?php

App::uses('AppHelper', 'View/Helper');

class SynchroDossierHelper extends AppHelper {

	public function getQuotaData() {
		return $this->_View->getVar('quota');
	}

	public function displayQuota($element = 'SynchroDossier.displayQuota') {
		$quota = $this->getQuotaData();

		$vars = array(
			'toPrint' => false
		);

		if (!empty($quota)) {
			$vars = array(
				'toPrint' => true,
				'quota' => round($quota['quota_mb'] / 1024, 2),
				'currentQuota' => round($quota['current_quota_mb'] / 1024, 2),
				'usedPercent' => $quota['current_quota_mb'] / $quota['quota_mb'] * 100
			);
		}
		$element = $this->_View->element($element, $vars);

		return $element;
	}
}