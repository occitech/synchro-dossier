<?php

App::uses('SynchroDossierAppModel', 'SynchroDossier.Model');

class SdFileEmail extends SynchroDossierAppModel {

	public $belongsTo = array(
		'UploadedFile' => array(
			'className' => 'Uploader.UploadedFile',
			'foreignKey' => 'uploaded_file_id',
		),
		'User' => array(
			'className' => 'SdUsers.SdUser',
			'foreignKey' => 'user_id',
		)
	);
}
