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

		debug($maxSizeKb);
		debug($event->data['data']['FileStorage']['file']['size']);
		$messages = array(
			Configure::read('sd.Utilisateur.roleId') => __('Désolé, l\'envoie de fichier n\'est actuellement pas disponible.'),
			Configure::read('sd.Admin.roleId') => __('Le quota est atteint, vous devez contacter Mr {Nom} {Prenom}, ou supprimer des fichiers.'),
			Configure::read('sd.SuperAdmin.roleId') => __('Le quota est atteint, vous devez commander plus de quota ou supprimer des fichiers.')
		);

		$event->result['hasError'] = false;
		if ($maxSizeKb < $event->data['data']['FileStorage']['file']['size']) {
			$event->result['hasError'] = true;
			$event->result['message'] = $messages[$event->data['user']['role_id']];
		}
	}
}