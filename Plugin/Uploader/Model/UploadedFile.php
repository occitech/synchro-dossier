<?php
App::uses('UploaderAppModel', 'Uploader.Model');
App::uses('Security', 'Utility');

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

	public $validate = array(
		'filename' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Ce champs ne peut être laissé vide'
			),
			'isUniqueName' => array(
				'rule' => 'isUniqueName',
				'message' => 'Ce nom est déjà utilisé par un autre dossier'
			)
		)
	);

    public function isUniqueName($check) {
    	$parentId = $this->data['UploadedFile']['parent_id'];
    	$result = $this->_findByFilenameParent_id($check['filename'], $parentId);
    	if (!empty($result)) {
    		if (isset($this->data['UploadedFile']['id'])
    			&& $result['UploadedFile']['id'] == $this->data['UploadedFile']['id']) {
    			return true;
    		}
    		return false;
    	}
		return true;
    }

///////////////////////////
/// Methods for folders ///
///////////////////////////

/**
 * Ajoute un dossier dans la db
 */
	public function addFolder($data, $parentId, $userId) {
		$parent = $this->findById($parentId);
		if (!$parent['UploadedFile']['is_folder']) {
			return false;
		}

		$this->create();
		$data['UploadedFile']['parent_id'] = $parentId;
		$data['UploadedFile']['is_folder'] = 1;
		$data['UploadedFile']['user_id'] = $userId;
		return $this->save($data);
	}

	protected function _getFoldersPath($folderId) {
		$parentFolder = $this->findById($folderId);
		if (empty($parentFolder) || !$parentFolder['UploadedFile']['is_folder']) {
			return false;
		}
		$subFolders = $this->children($folderId, false, null, null, null, 1, 1);
		$allFoldersPath = array(
			$folderId => array(
				'real_path' => $parentFolder['UploadedFile']['filename'] . DS,
				'remote_path' => null,
				'adapter' => null
			)
		);
		foreach ($subFolders as $v) {
			$uploadedFile = $v['UploadedFile'];
			$fileStorage = $v['FileStorage'];
			if ($uploadedFile['is_folder']) {
				$remotePath = null;
				$adapter = null;
			} else {
				$remotePath = $fileStorage[sizeof($fileStorage) - 1]['path'];
				$adapter = $fileStorage[sizeof($fileStorage) - 1]['adapter'];
			}
			$allFoldersPath[$uploadedFile['id']] = array(
				'real_path' => $allFoldersPath[$uploadedFile['parent_id']]['real_path'] . $uploadedFile['filename'],
				'remote_path' => $remotePath,
				'adapter' => $adapter
			);
			if ($uploadedFile['is_folder']) {
				$allFoldersPath[$uploadedFile['id']]['real_path'] .= DS;
			}
		}
		return $allFoldersPath;
	}

	public function createZip($folderId) {
		$zipfile = tempnam(null, 'Uploader');
		StorageManager::config('Zip', array(
				'adapterOptions' => array($zipfile),
				'adapterClass' => '\Gaufrette\Adapter\Zip',
				'class' => '\Gaufrette\Filesystem'
		));
		$files = $this->_getFoldersPath($folderId);
		foreach ($files as $f) {
			if ($f['remote_path'] != null) {
				$content = StorageManager::adapter($f['adapter'])->read($f['remote_path']);
				StorageManager::adapter('Zip')->write($f['real_path'], $content);	
			}
		}
		$content = file_get_contents($zipfile);
		unlink($zipfile);
		return $content;
	}


	public function rename($fileId, $data) {
		$fileInfos = $this->findById($fileId);

		if (!$fileInfos['UploadedFile']['is_folder']) {
			return false;
		}

		$data['UploadedFile'] = array_merge($fileInfos['UploadedFile'], $data['UploadedFile']);

		return $this->save($data);
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
		$hashFilename = Security::hash($fileInfos['name']);
		$path = $this->_getPathFile($userId, $this->id, $version, $hashFilename);
		StorageManager::adapter(Configure::read('FileStorage.adapter'))->write($path, $content);
		return $path;	
	}

	protected function _isANewVersion($filename, $parentId) {
		$result = $this->_findByFilenameParent_id($filename, $parentId);
		return !empty($result);
	}

	protected function _isNewVersionValidFile($lastFilename, $newFilename) {
		$lastFilename = explode('.', $lastFilename);
		$lastExt = $lastFilename[sizeof($lastFilename) - 1];
		$newFilename = explode('.', $newFilename);
		$newExt = $newFilename[sizeof($newFilename) - 1];
		return $newExt === $lastExt;
	}

	protected function _uploadNewFileVersion($data, $userId, $parentId, $originalFilename) {
		$fileInfos = $data['FileStorage']['file'];
		$isValid = true;
		
		if (!is_null($originalFilename)) {
			$isValid = $this->_isNewVersionValidFile($data['FileStorage']['file']['name'], $originalFilename);
			$fileInfos['name'] = $originalFilename;
		}

		if ($isValid) {
			
			$originalFileInfos = $this->_findByFilenameParent_id($fileInfos['name'], $parentId);
			$this->id = $originalFileInfos['UploadedFile']['id'];
			$userId = $originalFileInfos['UploadedFile']['user_id'];
			$version = $originalFileInfos['UploadedFile']['current_version'] + 1;

			$path = $this->_sendFileOnRemote($userId, $version, $fileInfos);

			$this->saveField('current_version', $version);

			return $this->_saveFileStorage($path, $userId);
		}
		return false;
	}

	protected function _uploadNewFile($data, $userId, $parentId) {
		$fileInfos = $data['FileStorage']['file'];
		$version = 1;

		if ($this->_saveUploadedFile($fileInfos, $userId, $parentId)) {
			$path = $this->_sendFileOnRemote($userId, $version, $fileInfos);
			return $this->_saveFileStorage($path, $userId);
		}
		return false;
	}

	public function upload($data, $userId, $parentId, $originalFilename = null) {
		$fileInfos = $data['FileStorage']['file'];
		$result = false;

		if ($fileInfos['error'] != UPLOAD_ERR_OK) {
			return false;
		}

		if (is_null($originalFilename) && !$this->_isANewVersion($fileInfos['name'], $parentId)) {
			$result = $this->_uploadNewFile($data, $userId, $parentId);
		} else {
			$result = $this->_uploadNewFileVersion($data, $userId, $parentId, $originalFilename);
		}
		return $result;
	}

	protected function _receiveFileFromRemote($remotePath, $adapter) {
		$content = StorageManager::adapter($adapter)->read($remotePath);
		return $content;
	}

	public function download($fileStorageId) {
		$fileStorage = $this->FileStorage->findById($fileStorageId);
		$fileInfos = $this->findById($fileStorage['FileStorage']['foreign_key']); // Fixme : wtf ?
		$content = $this->_receiveFileFromRemote($fileStorage['FileStorage']['path'], $fileStorage['FileStorage']['adapter']);
		return array($content, $fileInfos['UploadedFile']['filename']);
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
