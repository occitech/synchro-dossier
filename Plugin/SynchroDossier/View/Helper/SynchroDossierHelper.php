<?php

App::uses('AppHelper', 'View/Helper');

class SynchroDossierHelper extends AppHelper {

	public $helpers = array(
		'Html',
		'Uploader.UploaderAcl'
	);

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

	public function displayTreeFolders($folders) {
		$output = '';
		foreach ($folders as $folder) {
			if ($this->UploaderAcl->userCan($folder['Aco'], 'read')) {
				$subfolderOutput = '';
				if (isset($folder['children']) && count($folder['children']) > 0) {
					$subfolderOutput .= $this->displayTreeFolders($folder['children']);
					$subfolderOutput = $this->Html->tag('ul', $subfolderOutput, array('class' => 'hidden'));
				}
				$link = $this->Html->link(
					$folder['UploadedFile']['filename'],
					array('plugin' => 'uploader', 'controller' => 'files', 'action' => 'browse', $folder['UploadedFile']['id'])
				);
				$output .= $this->Html->tag('li', $link . $subfolderOutput);
			}
		}

		return $output;
	}
}