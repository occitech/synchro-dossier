<?php
App::uses('SynchroDossierAppModel', 'SynchroDossier.Model');
/**
 * SdFileEmail Model
 *
 * @property UploadedFile $UploadedFile
 * @property User $User
 */
class SdFileEmail extends SynchroDossierAppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
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
