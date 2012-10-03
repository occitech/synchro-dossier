<?php
App::uses('UploaderAppModel', 'Uploader.Model');

class UploadedFile extends UploaderAppModel {

	public $actsAs = array('Tree');

	public $belongsTo = array(
		// 'User' => array(
		// 	'className' => 'User',
		// 	'foreignKey' => 'user_id',
		// 	'conditions' => '',
		// 	'fields' => '',
		// 	'order' => ''
		// ),
		'ParentUploadedFile' => array(
			'className' => 'UploadedFile',
			'foreignKey' => 'parent_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public $hasMany = array(
		'ChildUploadedFile' => array(
			'className' => 'UploadedFile',
			'foreignKey' => 'parent_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'FileStorage' => array(
			'className' => 'FileStorage.FileStorage',
			'foreignKey' => 'foreign_key'
		)
	);

	public $hasAndBelongsToMany = array(
		'Users.User'
	);

///////////////////////////
/// Methods for folders ///
///////////////////////////

/**
 * Ajoute un dossier dans la db
 */
	public function addFolder($data, $parent_id, $user_id) {
		$this->create();
		$data['UploadedFile']['parent_id'] = $parent_id;
		$data['UploadedFile']['is_folder'] = 1;
		$data['UploadedFile']['user_id'] = $user_id;
		return $this->save($data);
	}

/**
 * A refaire. L'objectif était juste de montrer qu'il est possible de recréer l'arborescence d'un
 * dossier
 */
	public function getFoldersPath($folder_id) {
		$parentFolder = $this->findById($folder_id);
		$subFolders = $this->children($folder_id, false, null, null, null, 1, 1);
		$allFoldersPath = array(
			$folder_id => array(
				'real_path' => $parentFolder['UploadedFile']['filename'] . DS,
				'remote_path' => null
			)
		);
		foreach ($subFolders as $v) {
			$uploadedFile = $v['UploadedFile'];
			$fileStorage = $v['FileStorage'];
			if ($uploadedFile['is_folder']) {
				$remotePath = null;
			} else {
				$remotePath = $fileStorage[sizeof($fileStorage) - 1]['path'];
			}
			$allFoldersPath[$uploadedFile['id']] = array(
				'real_path' => $allFoldersPath[$uploadedFile['parent_id']]['real_path'] . $uploadedFile['filename'],
				'remote_path' => $remotePath
			);
			if ($uploadedFile['is_folder']) {
				$allFoldersPath[$uploadedFile['id']]['real_path'] .= DS;
			}
		}
		return $allFoldersPath;
	}


	public function createZip($folder_id) {
		// fixme ;)
	}

/////////////////////////
/// Methods for files ///
/////////////////////////

	protected function _findByFilenameParent_id($filename, $parent_id) {
		return $this->find('first', array(
			'conditions' => array(
				'UploadedFile.filename' => $filename,
				'UploadedFile.parent_id' => $parent_id
			)
		));
	}

	protected function _saveUploadedFile($fileInfos, $user_id, $parent_id) {
		$this->create();
		$data['UploadedFile']['filename'] = $fileInfos['name'];
		$data['UploadedFile']['size'] = $fileInfos['size'];
		$data['UploadedFile']['user_id'] = $user_id;
		$data['UploadedFile']['parent_id'] = $parent_id;
		$data['UploadedFile']['current_version'] = 1; // Fixme : directly in MySQL
		return $this->save($data);
	}

	protected function _saveFileStorage($path, $user_id) {
		$data['FileStorage']['foreign_key'] = $this->id;
		$data['FileStorage']['model'] = get_class($this);
		$data['FileStorage']['path'] = $path;
		$data['FileStorage']['user_id'] = $user_id;
		$data['FileStorage']['adapter'] = Configure::read('FileStorage.adapter');
		return $this->FileStorage->save($data);
	}

	protected function _getPathFile($user_id, $file_id, $version, $filename) {
		$path = Configure::read('FileStorage.filePattern');
		$path = str_replace('{user_id}', $user_id, $path);
		$path = str_replace('{file_id}', $file_id, $path);
		$path = str_replace('{version}', $version, $path);
		$path = str_replace('{filename}', $filename, $path);
		return $path;
	}

	protected function _sendFileOnRemote($user_id, $version, $fileInfos) {
		$content = file_get_contents($fileInfos['tmp_name']);
		$path = $this->_getPathFile($user_id, $this->id, $version, $fileInfos['name']);
		StorageManager::adapter(Configure::read('FileStorage.adapter'))->write($path, $content);
		return $path;	
	}

	public function upload($data, $user_id, $parent_id) {
		$fileInfos = $data['FileStorage']['file'];
		$version = 1;

		$data = $this->_findByFilenameParent_id($fileInfos['name'], $parent_id);
		if (empty($data)) {
			if (!$this->_saveUploadedFile($fileInfos, $user_id, $parent_id)) {
				throw new Exception(__('Impossible d\'enregistrer les infos dans la base de données UploadedFile'));
			}
		} else {
			$this->id = $data['UploadedFile']['id'];
			$user_id = $data['UploadedFile']['user_id'];
			$version = $data['UploadedFile']['current_version'] + 1;
			// Fixme faire l'update après l'upload sur le service distant
			$this->saveField('current_version', $version);
		}

		$path = $this->_sendFileOnRemote($user_id, $version, $fileInfos);
		if (!$this->_saveFileStorage($path, $user_id)) {
			throw new Exception(__('Impossible d\'enregistrer les infos dans la base de données FileStorage'));
		}
	}

////////////////////////////////////////
/// Methods for user right on a file ///
////////////////////////////////////////

	/**
	 * @todo Move on User model ?
	 */
	public function userHasRight($user_id, $file_id, $what) {
		$right = $this->UploadedFilesUser->find('first', array(
			'conditions' => array(
				'user_id' => $user_id,
				'uploaded_file_id' => $file_id
			)
		));

		if (empty($right)) {
			return false;
		}

	}

}
