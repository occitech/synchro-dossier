<?php

App::uses('Component', 'Controller');

class SynchroDossierComponent extends Component {

	public $autoHelper = true;

	public $helperName = 'SynchroDossier.SynchroDossier';

	public function beforeRender(Controller $controller) {
		if ($this->autoHelper) {
			$controller->helpers[] = $this->helperName;
			$this->__setQuotaInformation($controller);
		}
	}

	public function __setQuotaInformation(Controller $controller) {
		$SdInformationModel = ClassRegistry::init('SynchroDossier.SdInformation');
		
		$quota = $SdInformationModel->find('first');
		$quota = $quota['SdInformation'];
		$controller->set(compact('quota'));
	}
}