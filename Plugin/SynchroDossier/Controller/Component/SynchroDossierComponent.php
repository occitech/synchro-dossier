<?php

App::uses('Component', 'Controller');

class SynchroDossierComponent extends Component {

	public $hasRightToViewQuota = null;

	public $helperName = 'SynchroDossier.SynchroDossier';

	public function initialize(Controller $controller) {
		$this->hasRightToViewQuota();
	}

	public function beforeRender(Controller $controller) {
		$controller->helpers[] = $this->helperName;
		$this->__setQuotaInformation($controller);
	}

	private function __setQuotaInformation(Controller $controller) {
		$SdInformationModel = ClassRegistry::init('SynchroDossier.SdInformation');
		
		if ($this->hasRightToViewQuota) {
			$quota = $SdInformationModel->find('first');
			$quota = $quota['SdInformation'];
		} else {
			$quota = array();
		}
		$controller->set(compact('quota'));
	}

	public function hasRightToViewQuota(Controller $controller) {
		if (is_null($this->hasRightToViewQuota)) {
			$roleId = $controller->Auth->user('role_id');
			$this->hasRightToViewQuota = 
				$roleId == Configure::read('sd.Occitech.roleId') ||
				$roleId == Configure::read('sd.SuperAdmin.roleId') ||
				$roleId == Configure::read('sd.Admin.roleId');
		}
		return $this->hasRightToViewQuota;
	}
}