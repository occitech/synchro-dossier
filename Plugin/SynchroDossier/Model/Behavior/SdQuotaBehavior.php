<?php

App::uses('SdInformation', 'SynchroDossier.Model');
App::uses('ModelBehavior', 'Model');

class SdQuotaBehavior extends ModelBehavior {

    public function implementedEvents() {
        return array(
            'Model.UploadedFile.beforeUpload' => 'beforeUpload',
        );
    }

	public function beforeUpload($event) {
		$SdInformationModel = ClassRegistry::init('SynchroDossier.SdInformation');
		$maxSizeKb = $SdInformationModel->remainingQuota() * 1024;

		if ($maxSizeKb < $event->data['data']['FileStorage']['file']['size']) {
			$event->stopPropagation();
			$event->result['message'] = __('Il n\'y a plus d\'espace');
		}
	}
}