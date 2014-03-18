<?php
App::uses('UploaderAppModel', 'Uploader.Model');
App::uses('Multibyte', 'I18n');
App::uses('Security', 'Utility');

class UploadedFile extends UploaderAppModel {

	public $validationDomain = 'uploader';

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
			'foreignKey' => 'foreign_key',
			'dependent' => true
		),
		'Comment' => array(
			'className' => 'Comments.Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array('Comment.model' => 'UploadedFile'),
			'order' => 'created DESC'
		)
	);

	public $hasAndBelongsToMany = array(
		'FileTag' => array(
			'className' => 'Uploader.FileTag',
			'foreignKey' => 'uploaded_file_id',
			'associationForeignKey' => 'taxonomy_id',
			'joinTable' => 'taxonomies_uploaded_files',
		),
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
		'tags' => array('type' => 'subquery', 'method' => 'fileTagCondition', 'field' => 'UploadedFile.id'),
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
		if (empty($this->data[$this->alias]['parent_id'])) {
			$parentId = $this->field('parent_id', array($this->primaryKey => $this->data[$this->alias][$this->primaryKey]));
		} else {
			$parentId = $this->data[$this->alias]['parent_id'];
		}

		$acoData = null;
		if (!empty($parentId)) {
			$acoData =	array($this->alias => array(
				$this->primaryKey => $parentId
			));
		}

		return $acoData;
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
			$data['size_min'] = $data['size_min'] * 1024;
			$data['size_max'] = $data['size_max'] * 1024;
			$data['size'] = true;
		}
		if (!empty($data['created_min']) || !empty($data['created_max'])) {
			$data['created'] = true;
		}

		$data['filename'] = empty($data['filename']) ? '' : strtolower($data['filename']);
		$data['extension'] = empty($data['extension']) ? '' : strtolower($data['extension']);
		$data['filename_extension'] = !empty($data['filename']) || !empty($data['extension']);
		return parent::parseCriteria($data);
	}

	public function fileTagCondition($data = array()) {
		$TaxonomiesUploadedFile = ClassRegistry::init('TaxonomiesUploadedFile');
		$TaxonomiesUploadedFile->Behaviors->attach('Search.Searchable');
		$query = $TaxonomiesUploadedFile->getQuery('all', array(
			'fields' => array($TaxonomiesUploadedFile->escapeField('uploaded_file_id')),
			'conditions' => array($TaxonomiesUploadedFile->escapeField('taxonomy_id') => $data['tags']),
		));

		return $query;
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

	public function canDownloadFolderAsZip($folderId){
		$canDownload = false;
		if (!$this->exists($folderId)) {
			throw new NotFoundException(__d('uploader', 'Invalid Folder Id #%s', $folderId));
		}

		if (!$this->hasAny(array('id' => $folderId, 'is_folder' => 1))) {
			throw new InvalidArgumentException(__d('uploader', 'You Cannot download a file as Zip archive'));
		}

		$maxLimit = Configure::read('sd.config.maxDownloadableZipSize');
		$files = $this->children($folderId);


		$totalSize = 0;
		$totalSize = array_sum(Hash::extract($files, '{n}.UploadedFile.size'));
		$canDownload = ($totalSize <= $maxLimit) && !empty($files);
		return $canDownload;
	}

	public function createZip($folderId) {
		$zipfile = tempnam(null, 'Uploader');
		StorageManager::config('Zip', array(
				'adapterOptions' => array($zipfile),
				'adapterClass' => '\Gaufrette\Adapter\Zip',
				'class' => '\Gaufrette\Filesystem'
		));
		$files = $this->_getFoldersPath($folderId);
		$shouldSluggifyFilename = Configure::read('sd.slugFilenameWhenExport');
		foreach ($files as $f) {
			if ($f['remote_path'] != null) {
				$content = StorageManager::adapter($f['adapter'])->read($f['remote_path']);
				$filename = $shouldSluggifyFilename ? $this->sluggifyFilename($f['real_path']) : $f['real_path'];
				StorageManager::adapter('Zip')->write($this->__fixNameForZipFile($filename), $content);
			}
		}
		$content = file_get_contents($zipfile);
		unlink($zipfile);
		return $content;
	}

	private function __fixNameForZipFile($name) {
		// To be improved in #5656 because so far it removes accented chars
		setlocale(LC_CTYPE, "en_US.utf8");
		return iconv('UTF-8', 'ASCII//TRANSLIT', $name);
	}

	public function rename($data) {
		$fileInfos = $this->findById($data[$this->alias]['id']);

		if (!$fileInfos['UploadedFile']['is_folder']) {
			return false;
		}

		$data['UploadedFile'] = array_merge($fileInfos['UploadedFile'], $data['UploadedFile']);

		return $this->save($data);
	}

	public function removeFolder($folderId, $userId) {
		$canDelete = $success = false;

		$folder = $this->findById($folderId);
		$userData = $this->User->find('first', array(
			'conditions' => array('User.id' => $userId),
			'noRoleChecking' => true
		));

		$this->__throwExceptionsIfNeeded($userData, $folder, $userId, $folderId);

		$canDelete = ClassRegistry::init('Permission')->check(
			array('model' => 'User', 'foreign_key' => $userId),
			array('model' => 'UploadedFile', 'foreign_key' => $folderId),
			'delete'
		);

		if ($canDelete) {
			$success = $this->delete($folderId);
			$this->getEventManager()->dispatch(new CakeEvent(
				'Model.UploadedFile.afterRemoveData',
				$this,
				array()
			));
		}

		return $success;
	}

	private function __throwExceptionsIfNeeded($userData, $folderData, $userId, $folderId) {
		if (empty($userData)) {
			throw new NotFoundException(__d('uploader', 'Invalid user with id#%s', $userId));
		}
		if (empty($folderData)) {
			throw new NotFoundException(__d('uploader', 'Invalid Folder with id#%s', $folderId));
		}
		if (!$folderData[$this->alias]['is_folder']) {
			throw new InvalidArgumentException(__d('uploader', 'Current id %s is not a folder', $folderId));
		}
	}

	public function removeFile($fileId, $fileStorageId, $userId) {
		$success = false;

		$file = $this->findById($fileId);
		$this->id = $fileId;

		$this->__throwExceptionsIfNeededForFile($file, $userId, $fileId);

		$canDelete = ClassRegistry::init('Permission')->check(
			array('model' => 'User', 'foreign_key' => $userId),
			array('model' => 'UploadedFile', 'foreign_key' => $fileId),
			'delete'
		);

		if ($canDelete) {
			$version = $this->field('current_version');
			$filename = $this->field('filename');
			$fileSize = $this->field('size');
			$path = $this->_getPathFile($file['UploadedFile']['user_id'], $fileId, $version, $filename);

			$fileStorageSize = $this->FileStorage->field(
				'filesize',
				array('id' => $fileStorageId)
			);

			$success = $this->FileStorage->delete($fileStorageId);

			$this->saveField('size', $fileSize - $fileStorageSize);

			$fileStorage = $this->FileStorage->find('first', array(
				'conditions' => array('FileStorage.foreign_key' => $file['UploadedFile']['id'])
			));

			if(empty($fileStorage)){
				$success = $this->delete($fileId);
				$this->_deleteFileFolderInRemote($path);

			}
			$this->getEventManager()->dispatch(new CakeEvent(
				'Model.UploadedFile.afterRemoveData',
				$this,
				array()
			));
		}

		return $success;
	}

	private function __throwExceptionsIfNeededForFile($fileData, $userId, $fileId) {
		$userData = $this->User->find('first', array(
			'conditions' => array('User.id' => $userId),
			'noRoleChecking' => true
		));

		if (empty($userData)) {
			throw new NotFoundException(__d('uploader', 'Invalid user with id#%s', $userId));
		}
		if (empty($fileData)) {
			throw new NotFoundException(__d('uploader', 'Invalid File with id#%s', $fileId));
		}
	}

/////////////////////////
/// Methods for files ///
/////////////////////////
	public function sluggifyFilename($filenamewithExtension) {
		$temporaryFilename = explode('.', strrev($filenamewithExtension));
		$extension = null;

		if (!empty($temporaryFilename) && $temporaryFilename[0] !== strrev($filenamewithExtension)) {
			$extension = strrev($temporaryFilename[0]);
			unset($temporaryFilename[0]);
			$filename = strrev(implode('.', $temporaryFilename));
		} else {
			$filename = $filenamewithExtension;
		}

		$normalizedFilename = Inflector::slug($filename, '-');

		if (!empty($extension)) {
			$normalizedFilename .= '.' . $extension;
		}

		return $normalizedFilename;
	}

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
		$data['UploadedFile']['user_id'] = $userId;
		$data['UploadedFile']['parent_id'] = $parentId;
		$data['UploadedFile']['current_version'] = 1;
		$data['UploadedFile']['mime_type'] = $fileInfos['type'];
		$data['UploadedFile']['size'] = $fileInfos['size'];
		return $this->save($data);
	}

	protected function _saveFileStorage($path, $userId, $fileInfos, $version) {
		$this->FileStorage->create();
		$data['FileStorage']['foreign_key'] = $this->id;
		$data['FileStorage']['model'] = get_class($this);
		$data['FileStorage']['path'] = $path;
		$data['FileStorage']['user_id'] = $userId;
		$data['FileStorage']['adapter'] = Configure::read('FileStorage.adapter');
		$data['FileStorage']['mime_type'] = $fileInfos['type'];
		$data['FileStorage']['filesize'] = $fileInfos['size'];
		$data['FileStorage']['file_version'] = $version;
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

	protected function _uploadNewFileVersion($data, $parentId, $originalFilename) {
		$fileInfos = $data['file'];
		$isValid = true;

		if (!is_null($originalFilename)) {
			$isValid = $this->_isNewVersionValidFile($data['file']['name'], $originalFilename);
			if (!$isValid) {
				$this->FileStorage->invalidate('file', __d('uploader', 'Le fichier doit avoir la même extension que le fichier d\'origine'));
			}
			$fileInfos['name'] = $originalFilename;
		}

		if ($isValid) {
			$originalFileInfos = $this->_findByFilenameParent_id($fileInfos['name'], $parentId);
			$this->id = $originalFileInfos['UploadedFile']['id'];
			$userId = $originalFileInfos['UploadedFile']['user_id'];
			$version = $originalFileInfos['UploadedFile']['current_version'] + 1;
			$size = $originalFileInfos['UploadedFile']['size'] + $fileInfos['size'];

			$path = $this->_sendFileOnRemote($userId, $version, $fileInfos);

			$this->Behaviors->unload('Acl');
			$this->Behaviors->unload('RowLevelAcl');
			$this->saveField('current_version', $version, array('callback' => false));
			$this->saveField('size', $size);
			$this->id = $originalFileInfos['UploadedFile']['id'];

			return $this->_saveFileStorage($path, $userId, $fileInfos, $version);
		}
		return false;
	}

	protected function _uploadNewFile($data, $userId, $parentId) {
		$fileInfos = $data['file'];
		$version = 1;

		if ($this->_saveUploadedFile($fileInfos, $userId, $parentId)) {
			$path = $this->_sendFileOnRemote($userId, $version, $fileInfos);
			return $this->_saveFileStorage($path, $userId, $fileInfos, $version);
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
				$this->FileStorage->invalidate('file', __d('uploader', 'La taille du fichier dépasse la limite autorisée'));
				break;
			case UPLOAD_ERR_NO_FILE:
				$this->FileStorage->invalidate('file', __d('uploader', 'Aucun fichier n\'a été uploadé'));
				break;
			default:
				$this->FileStorage->invalidate('file', __d('uploader', 'Il y a eu une erreur pendant l\'upload de votre fichier'));
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
				$result = $this->_uploadNewFileVersion($data, $parentId, $originalFilename);
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

	protected function _deleteFileFolderInRemote($path) {
		$dir = preg_replace('/\/[^\/]*$/', '', $path);
		StorageManager::adapter(Configure::read('FileStorage.adapter'))->delete($dir);
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
