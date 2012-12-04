<?php
App::uses('SynchroDossierAppController', 'SynchroDossier.Controller');

class SdInformationsController extends SynchroDossierAppController {

	public function admin_quota() {
		if ($this->request->is('put')) {
			if ($this->SdInformation->save($this->request->data)) {
				$this->Session->setFlash(__('Quota mis à jour'), 'default', array('class' => 'alert alert-success'));
			}
		} else {
			$this->request->data = $this->SdInformation->find('first');
		}
		$this->set('usedQuota', $this->SdInformation->getUsedQuota());
	}
}