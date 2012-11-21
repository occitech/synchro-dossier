<?php

App::uses('Component', 'Controller');

class SynchroDossierComponent extends Component {

	public $canViewQuota = null;

	public $helperName = 'SynchroDossier.SynchroDossier';

	public function initialize(Controller $controller) {
		$this->setCanViewQuota($controller);
	}

	public function beforeRender(Controller $controller) {
		if (isset($controller->request->params['prefix']) && $controller->request->params['prefix'] != 'admin') {
			$controller->layout = 'SynchroDossier.default';
		}
		$controller->helpers[] = $this->helperName;
		$this->__setQuotaInformation($controller);
	}

	private function __setQuotaInformation(Controller $controller) {
		if ($this->canViewQuota) {
			$SdInformationModel = ClassRegistry::init('SynchroDossier.SdInformation');
			$quota = $SdInformationModel->find('first');
			$quota = $quota['SdInformation'];
		} else {
			$quota = array();
		}
		$controller->set(compact('quota'));
	}

	public function setCanViewQuota(Controller $controller) {
		if (is_null($this->canViewQuota)) {
			$roleId = $controller->Auth->user('role_id');
			$supervisersRole = array(
				Configure::read('sd.Occitech.roleId'),
				Configure::read('sd.SuperAdmin.roleId'),
				Configure::read('sd.Admin.roleId')
			);
			$this->canViewQuota = in_array($roleId, $supervisersRole);
		}
		return $this->canViewQuota;
	}
}