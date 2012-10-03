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
	public function addFolder($data, $parentId, $userId) {
		$this->create();
		$data['UploadedFile']['parent_id'] = $parentId;
		$data['UploadedFile']['is_folder'] = 1;
		$data['UploadedFile']['user_id'] = $userId;
		return $this->save($data);
	}

/**
 * A refaire. L'objectif était juste de montrer qu'il est possible de recréer l'arborescence d'un
 * dossier
 */
	public function getFoldersPath($folderId) {
		$parentFolder = $this->findById($folderId);
		$subFolders = $this->children($folderId, false, null, null, null, 1, 1);
		$allFoldersPath = array(
			$folderId => array(
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


	public function createZip($folderId) {
		// fixme ;)
	}

/////////////////////////
/// Methods for files ///
/////////////////////////

	protected function _findByFilenameParent_id($filename, $parentId) {
		return $this->find('first', array(
			'conditions' => array(
				'UploadedFile.filename' => $filename,
				'UploadedFile.parent_id' => $parentId
			)
		));
	}

	protected function _saveUploadedFile($fileInfos, $userId, $parentId) {
		$this->create();
		$data['UploadedFile']['filename'] = $fileInfos['name'];
		$data['UploadedFile']['size'] = $fileInfos['size'];
		$data['UploadedFile']['user_id'] = $userId;
		$data['UploadedFile']['parent_id'] = $parentId;
		$data['UploadedFile']['current_version'] = 1; // Fixme : directly in MySQL
		return $this->save($data);
	}

	protected function _saveFileStorage($path, $userId) {
		$data['FileStorage']['foreign_key'] = $this->id;
		$data['FileStorage']['model'] = get_class($this);
		$data['FileStorage']['path'] = $path;
		$data['FileStorage']['user_id'] = $userId;
		$data['FileStorage']['adapter'] = Configure::read('FileStorage.adapter');
		return $this->FileStorage->save($data);
	}

	protected function _getPathFile($userId, $fileId, $version, $filename) {
		$path = Configure::read('FileStorage.filePattern');
		$path = str_replace('{user_id}', $userId, $path);
		$path = str_replace('{file_id}', $fileId, $path);
		$path = str_replace('{version}', $version, $path);
		$path = str_replace('{filename}', $filename, $path);
		return $path;
	}

	protected function _sendFileOnRemote($userId, $version, $fileInfos) {
		$content = file_get_contents($fileInfos['tmp_name']);
		$path = $this->_getPathFile($userId, $this->id, $version, $fileInfos['name']);
		StorageManager::adapter(Configure::read('FileStorage.adapter'))->write($path, $content);
		return $path;	
	}

	public function upload($data, $userId, $parentId) {
		$fileInfos = $data['FileStorage']['file'];
		$version = 1;

		$data = $this->_findByFilenameParent_id($fileInfos['name'], $parentId);
		if (empty($data)) {
			if (!$this->_saveUploadedFile($fileInfos, $userId, $parentId)) {
				throw new Exception(__('Impossible d\'enregistrer les infos dans la base de données UploadedFile'));
			}
		} else {
			$this->id = $data['UploadedFile']['id'];
			$userId = $data['UploadedFile']['user_id'];
			$version = $data['UploadedFile']['current_version'] + 1;
			// Fixme faire l'update après l'upload sur le service distant
			$this->saveField('current_version', $version);
		}
		try {
			$path = $this->_sendFileOnRemote($userId, $version, $fileInfos);
		} catch (Exception $e) {
			echo 'An error <br>' . $e;
		}
		if (!$this->_saveFileStorage($path, $userId)) {
			throw new Exception(__('Impossible d\'enregistrer les infos dans la base de données FileStorage'));
		}
	}

////////////////////////////////////////
/// Methods for user right on a file ///
////////////////////////////////////////

	/**
	 * @todo Move on User model ?
	 */
	public function userHasRight($userId, $fileId, $what) {
		$right = $this->UploadedFilesUser->find('first', array(
			'conditions' => array(
				'user_id' => $userId,
				'uploaded_file_id' => $fileId
			)
		));

		if (empty($right)) {
			return false;
		}

	}

}
