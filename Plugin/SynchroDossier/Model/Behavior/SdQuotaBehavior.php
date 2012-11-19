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
			$event->result['message'][Configure::read('sd.Utilisateur.roleId')] = __('Désolé, l\'envoie de fichier n\'est actuellement pas disponible.');
			$event->result['message'][Configure::read('sd.Admin.roleId')] = __('Le quota est atteint, vous devez contacter Mr {Nom} {Prenom}, ou supprimer des fichiers.');
			$event->result['message'][Configure::read('sd.SuperAdmin.roleId')] = __('Le quota est atteint, vous devez commander plus de quota ou supprimer des fichiers.');
		}
	}
}