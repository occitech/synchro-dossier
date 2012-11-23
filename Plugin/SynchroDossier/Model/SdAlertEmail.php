<?php

App::uses('SynchroDossierAppModel', 'SynchroDossier.Model');

class SdAlertEmail extends SynchroDossierAppModel {

	public $belongsTo = array(
		'User' => array(
			'className' => 'SdUsers.SdUser',
			'foreignKey' => 'user_id',
		),
		'UploadedFile' => array(
			'className' => 'Uploader.UploadedFile',
			'foreignKey' => 'uploaded_file_id',
		)
	);

	public $cakeEmail = null;

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->cakeEmail = new CakeEmail();	
	}

	/**
	 * @return array with two key
	 *	array(
	 *		'to' => array('email@mail.com', ...),
	 *		'files' => array()
	 *	);
	 * or false
	 */
	public function getUserToAlert($userId) {
		$result = array();
		$SdFileEmailModel = ClassRegistry::init('SynchroDossier.SdFileEmail');
		
		$result['files'] = $SdFileEmailModel->findAllByUser_id($userId);
		if (count($result['files']) > 0) {
			$rootFolderId = $this->UploadedFile->getRootFolderId(
				$result['files'][0]['UploadedFile']['id']
			);
			$usersToAlert = $this->findAllByUploaded_file_id($rootFolderId);

			$result['to'] = array();

			foreach ($usersToAlert as $user) {
				$result['to'][$user['User']['email']] = $user['User']['username'];
			}

			$SdFileEmailModel->deleteAll(array('SdFileEmail.user_id' => $userId));

			return $result;
		}

		return false;
	}
}