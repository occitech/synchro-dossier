<?php
App::uses('UploaderAppModel', 'Uploader.Model');
App::uses('Security', 'Utility');

class UploadedFile extends UploaderAppModel {

	public $actsAs = array(
		'Tree',
		'Acl' => array('type' => 'controlled'),
		'Search.Searchable'
	);

	public $belongsTo = array(
		'User' => array(
			'className' => 'SdUsers.SdUser',
		),
		'ParentUploadedFile' => array(
			'className' => 'UploadedFile',
			'foreignKey' => 'parent_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public $hasOne = array(
		'Aco' => array(
			'className' => 'Aco',
			'foreignKey' => 'foreign_key',
			'conditions' => array('Aco.model' => 'UploadedFile')
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
		),
		'Comment' => array(
			'className' => 'Comments.Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array('Comment.model' => 'UploadedFile'),
			'order' => 'created DESC'
		)
	);

	public $findMethods = array('rootDirectories' =>  true);

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

	public $filterArgs = array(
		'filename_extension' => array('type' => 'query', 'method' => 'filenameExtensionCondition'),
		'parent_id' => array('type' => 'int'),
		'is_folder' => array('type' => 'value'),
		'size' => array('type' => 'expression', 'method' => 'makeSizeCondition', 'field' => 'UploadedFile.size BETWEEN ? AND ?'),
		'created' => array('type' => 'expression', 'encode' => true, 'method' => 'makeCreatedCondition', 'field' => 'UploadedFile.created BETWEEN ? AND ?'),
		'username' => array('type' => 'like', 'field' => array('User.username')),
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

	public function parentNode() {
		$parentId = null;
		if (isset($this->data['UploadedFile']['parent_id'])) {
			$parentId = array('UploadedFile' => array(
				'id' => $this->data['UploadedFile']['parent_id']
			));
		}
		return $parentId;
	}

////////////////////////////////
/// Method for search plugin ///
////////////////////////////////

	public function makeSizeCondition($data, $field = null) {
		$min = empty($data['size_min']) ? 0 : $data['size_min'] * 1024;
		$max = empty($data['size_max']) ? PHP_INT_MAX : $data['size_max'] * 1024;

		return array($min, $max);
	}

	public function makeCreatedCondition($data, $field = null) {
		$min = empty($data['created_min']) ? '0000-00-00' : $data['created_min'] . ' 00:00:00';
		$max = empty($data['created_max'])? '3000-01-01' : $data['created_max'] . ' 23:59:59';

		return array($min, $max);
	}

	public function filenameExtensionCondition($data = array()) {
		$filename = ($data['filename'] != '') ? '%' . $data['filename'] . '%' : '';
		$extension = ($data['extension'] != '') ? '%.' . $data['extension'] : '';
		$cond = array(
			'LOWER(' . $this->alias . '.filename) COLLATE utf8_unicode_ci LIKE' => $filename . $extension,
		);
		return $cond;
	}

	public function parseCriteria($data) {
		if (!empty($data['size_min']) || !empty($data['size_max'])) {
			$data['size'] = true;
		}
		if (!empty($data['created_min']) || !empty($data['created_max'])) {
			$data['created'] = true;
		}
		$data['filename'] = strtolower($data['filename']);
		$data['extension'] = strtolower($data['extension']);
		$data['filename_extension'] = true;
		return parent::parseCriteria($data);
	}

///////////////////////////
/// Methods for folders ///
///////////////////////////

	public function isRootFolder($folderId) {
		$result = $this->hasAny(array(
			$this->alias . '.id' => $folderId,
			$this->alias . '.parent_id' => null
		));

		return $result;
	}

	public function getRootFolderId($uploadedFileId) {
		$path = $this->getPath($uploadedFileId);

		return $path[0]['UploadedFile']['id'];
	}

	public function addFolder($data, $parentId, $userId) {
		if (!is_null($parentId)) {
			$parent = $this->findById($parentId);
			if (!empty($parent) && !$parent['UploadedFile']['is_folder']) {
				return false;
			}
		}

		$this->create();
		$data['UploadedFile']['parent_id'] = $parentId;
		$data['UploadedFile']['is_folder'] = 1;
		$data['UploadedFile']['user_id'] = $userId;
		return $this->save($data);
	}

	/**
	 * @todo To move in SynchroDossier plugin ? (cf action createSharing)
	 */
	public function addSharing($data, $user) {
		$rootAco = $this->Aco->findByAlias('uploadedFileAco');

		$result = $this->addFolder($data, null, $user['id']);

		if ($result) {
			$this->Aco->saveField('parent_id', $rootAco['Aco']['id']);
		}

		$data['UploadedFile']['id'] = $this->id;

		$this->getEventManager()->dispatch(new CakeEvent(
				'Model.UploadedFile.AfterSharingCreation',
				$this,
				array('data' => $data, 'user' => $user)
		));

		return $result;
	}

	public function getThreadedAllFolders() {
		$this->contain('Aco');
		$folder = $this->find('threaded', array(
			'conditions' => array('UploadedFile.is_folder' => 1)
		));

		return $folder;
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

	public function rename($data) {
		$fileInfos = $this->findById($data[$this->alias]['id']);

		if (!$fileInfos['UploadedFile']['is_folder']) {
			return false;
		}

		$data['UploadedFile'] = array_merge($fileInfos['UploadedFile'], $data['UploadedFile']);

		return $this->save($data);
	}

/////////////////////////
/// Methods for files ///
/////////////////////////

	protected function _findRootDirectories($state, $query, $results = array()) {
		if ($state == 'before') {
			$query['conditions'][$this->alias . '.parent_id'] = null;
			return $query;
		}
		return $results;
	}

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
		$data['UploadedFile']['current_version'] = 1;
		$data['UploadedFile']['mime_type'] = $fileInfos['type'];
		$data['UploadedFile']['size'] = $fileInfos['size'];
		return $this->save($data);
	}

	protected function _saveFileStorage($path, $userId, $fileInfos) {
		$data['FileStorage']['foreign_key'] = $this->id;
		$data['FileStorage']['model'] = get_class($this);
		$data['FileStorage']['path'] = $path;
		$data['FileStorage']['user_id'] = $userId;
		$data['FileStorage']['adapter'] = Configure::read('FileStorage.adapter');
		$data['FileStorage']['mime_type'] = $fileInfos['type'];
		$data['FileStorage']['filesize'] = $fileInfos['size'];
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
		$fileInfos = $data['file'];
		$isValid = true;
		
		if (!is_null($originalFilename)) {
			$isValid = $this->_isNewVersionValidFile($data['file']['name'], $originalFilename);
			if (!$isValid) {
				$this->FileStorage->invalidate('file', __('Le fichier doit avoir la même extension que le fichier d\'origine'));
			}
			$fileInfos['name'] = $originalFilename;
		}

		if ($isValid) {
			$originalFileInfos = $this->_findByFilenameParent_id($fileInfos['name'], $parentId);
			$this->id = $originalFileInfos['UploadedFile']['id'];
			$userId = $originalFileInfos['UploadedFile']['user_id'];
			$version = $originalFileInfos['UploadedFile']['current_version'] + 1;

			$path = $this->_sendFileOnRemote($userId, $version, $fileInfos);

			$this->saveField('current_version', $version);

			return $this->_saveFileStorage($path, $userId, $fileInfos);
		}
		return false;
	}

	protected function _uploadNewFile($data, $userId, $parentId) {
		$fileInfos = $data['file'];
		$version = 1;

		if ($this->_saveUploadedFile($fileInfos, $userId, $parentId)) {
			$path = $this->_sendFileOnRemote($userId, $version, $fileInfos);
			return $this->_saveFileStorage($path, $userId, $fileInfos);
		}
		return false;
	}

	protected function _hasUploadErrors ($errorCode) {
		$hasError = true;
		switch ($errorCode) {
			case UPLOAD_ERR_OK:
				$hasError = false;
				break;
			case UPLOAD_ERR_INI_SIZE:
				$this->FileStorage->invalidate('file', __('La taille du fichier dépasse la limite autorisée'));
				break;
			case UPLOAD_ERR_NO_FILE:
				$this->FileStorage->invalidate('file', __('Aucun fichier n\'a été uploadé'));
				break;
			default:
				$this->FileStorage->invalidate('file', __('Il y a eu une erreur pendant l\'upload de votre fichier'));
				break;
		}
		return $hasError;
	}

	public function upload($data, $user, $parentId, $originalFilename = null) {
		$fileInfos = $data['file'];
		$result = false;

		if ($this->_hasUploadErrors($fileInfos['error'])) {
			return false;
		}

		$event = new CakeEvent('Model.UploadedFile.beforeUpload', $this, array('data' => $data, 'user' => $user));
		$this->getEventManager()->dispatch($event);

		if ($event->result['hasError']) {
			$this->getEventManager()->dispatch(new CakeEvent(
				'Model.UploadedFile.afterUploadFailed',
				$this,
				array('data' => $data, 'user' => $user, 'beforeUploadResult' => $event->result)
			));
			$this->FileStorage->invalidate('file', $event->result['message']);
		} else {
			if (is_null($originalFilename) && !$this->_isANewVersion($fileInfos['name'], $parentId)) {
				$result = $this->_uploadNewFile($data, $user['id'], $parentId);
			} else {
				$result = $this->_uploadNewFileVersion($data, $user['id'], $parentId, $originalFilename);
			}
		}

		if ($result) {
			$data['file']['id'] = $this->id;
			$this->getEventManager()->dispatch(new CakeEvent(
				'Model.UploadedFile.afterUploadSuccess',
				$this,
				array('data' => $data, 'user' => $user
			)));
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
		return array($content, $fileInfos['UploadedFile']['filename'], $fileInfos['UploadedFile']['mime_type']);
	}

	public function downloadLatestVersion($uploadedFileId) {
		$fileStorage = $this->FileStorage->find('first', array(
			'conditions' => array('FileStorage.foreign_key' => $uploadedFileId),
			'order' => 'FileStorage.created DESC'
		));

		return $this->download($fileStorage['FileStorage']['id']);
	}
}
