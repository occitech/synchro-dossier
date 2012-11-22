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
}