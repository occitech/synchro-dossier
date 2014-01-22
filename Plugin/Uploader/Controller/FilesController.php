<?php

App::uses('UploaderAppController', 'Uploader.Controller');
App::uses('UploadedFile', 'Uploader.Model');
App::uses('UploaderAclAco', 'Uploader.Model');

class FilesController extends UploaderAppController {

	public $uses = array('Uploader.UploadedFile');
	public $components = array(
		'Plupload.Plupload',
		'Search.Prg',
		'RowLevelAcl' => array(
			'className' => 'Acl.RowLevelAcl',
			'settings' => array(
				'actionMap' => array(
					'browse' 					=> 'read',
					'preview' 					=> 'read',
					'allFilesUploadedInBatch'	=> 'read',
					'createFolder' 				=> 'create',
					'delete' 					=> 'delete',
					'rename' 					=> 'create',
					'downloadZipFolder' 		=> 'read',
					'upload' 					=> 'create',
					'download' 					=> 'read',
					'rights'					=> 'change_right',
					'removeRight'				=> 'change_right',
					'toggleRight'				=> 'change_right'
				),
			)
		)
	);

	public $paginate;

	private $__messageFlashDownloadNotAvailable;
	private $__listRights = array('read', 'create', 'delete');

	public function beforeFilter() {
		parent::beforeFilter();
		if (empty($this->__messageFlashDownloadNotAvailable)) {
			$this->__messageFlashDownloadNotAvailable =__d(
				'uploader',
				'You cannot download this folder as a zip archive (Max Size Exceeded).<br>Please fill an email to %s to get an access to your zip archive.',
				Configure::read('Site.email')
			);
		}
		$this->Security->unlockedActions = array('upload', 'rename', 'find', 'browse', 'allFilesUploadedInBatch');
	}

	public function beforeRender() {
		parent::beforeRender();

		$folderId = isset($this->request->params['pass'][0]) ? $this->request->params['pass'][0] : null;

		$uploadUrl = Router::url(array(
			'plugin' => 'uploader',
			'controller' => 'files',
			'action' => 'upload',
			$folderId
		));

		$this->Plupload->setUploaderOptions(array(
			'locale' => 'fr',
			'runtimes' => 'html5',
			'filters' => array(),
			'browse_button' => 'browse',
			'drop_element' => 'drop-area',
			'container' => 'plupload',
			'url' => $uploadUrl,
			'callback_url' => Router::url(array('action' => 'allFilesUploadedInBatch', $folderId))
		));
	}

	public function rights($folderId) {
		if ($this->UploadedFile->isRootFolder($folderId)) {
			$folder = $this->UploadedFile->findById($folderId);
			$superAdmins = $this->UploadedFile->User->find('superAdmin');
			$acos = $this->UploaderAclAco->getArosOfFolder('UploadedFile', $folderId);
			$usersNotInFolder = $this->UploaderAclAro->getUserNotInFolder($folderId);
			$this->set(compact('acos', 'superAdmins', 'folder', 'usersNotInFolder'));
		} else {
			$rootId = $this->UploadedFile->getRootFolderId($folderId);
			$this->Session->setFlash(
				__d('uploader', 'Vous ne pouvez pas donner de droit à un sous dossier. Nous vous avons donc redirigé sur la page permettant de donner les droits au dossier racine. Les droits s\'appliqueront aussi au sous dossier'),
				'default',
				array('class' => 'alert alert-info')
			);
			$this->redirect(array('action' => 'rights', $rootId));
		}
	}

	protected function _removeRight($acoId, $aroId) {
		return $this->UploaderAclAco->ArosAco->deleteAll(array(
			'aco_id' => $acoId,
			'aro_id' => $aroId
		));
	}

	protected function _checkRight($uploadedFileId, $userId, $action) {
		return $this->Acl->check(
			array('model' => 'User', 'foreign_key' => $userId),
			array('model' => 'UploadedFile', 'foreign_key' => $uploadedFileId),
			$action
		);
	}

	protected function _allowRight($uploadedFileId, $userId, $action) {
		foreach ($this->__listRights as $right) {
			$this->Permission->allow(
				array('model' => 'User', 'foreign_key' => $userId),
				array('model' => 'UploadedFile', 'foreign_key' => $uploadedFileId),
				$right
			);
			if ($right == $action) {
				break;
			}
		}
	}

	protected function _denyRight($uploadedFileId, $userId, $action) {
		foreach (array_reverse($this->__listRights) as $right) {
			$this->Acl->deny(
				array('model' => 'User', 'foreign_key' => $userId),
				array('model' => 'UploadedFile', 'foreign_key' => $uploadedFileId),
				$right
			);

			if ($right == $action) {
				break;
			}
		}
	}

	public function removeRight($folderId, $acoId, $aroId) {
		$result = $this->_removeRight($acoId, $aroId);
		if (!$result) {
			$this->Session->setFlash(__d('uploader', 'There was an error while deleting the right. Thank you try again or contact an administrator'), 'default', array('class' => 'alert alert-danger'));
		}
		$this->redirect($this->referer());
	}

	public function toggleRight($uploadedFileId, $userId = null, $action = 'read') {
		$listRights = array('read', 'create', 'delete');

		$isNewUserRight = false;
		if (isset($this->request->data['User']['user_id'])) {
			$userId = $this->request->data['User']['user_id'];
			$isNewUserRight = true;
		}

		if ($this->UploadedFile->exists($uploadedFileId) &&
			$this->UploadedFile->User->exists($userId)) {

			$isRightActive = $this->_checkRight($uploadedFileId, $userId, $action);

			if (!($isNewUserRight && $isRightActive)) {
				$method = ($isRightActive) ? '_denyRight' : '_allowRight';

				$this->{$method}($uploadedFileId, $userId, $action);

				$this->getEventManager()->dispatch(new CakeEvent(
					'Controller.FilesController.afterChangeRight',
					$this,
					array(
						'method' => $method,
						'user' => array('id' => $userId),
						'model' => 'Permission',
						'foreign_key' => $this->Permission->id
					)
				));
			}

		} else {
			$this->Session->setFlash(__d('uploader', 'Given information are incorrect'), 'default', array('class' => 'alert alert-danger'));
		}
		$this->redirect($this->referer());
	}

	public function browse($folderId = null) {
		$folderId = $folderId === 'null' ? null : $folderId;

		$this->helpers[] = 'Uploader.File';
		$this->helpers[] = 'Time';

		$this->UploadedFile->recursive = 2;

		$this->UploadedFile->bindModel(array(
			'hasOne' => array(
				'Aco' => array(
					'className' => 'Aco',
					'foreignKey' => 'foreign_key'
				)
			)
		));

		$this->UploadedFile->order = 'UploadedFile.is_folder DESC';
		if (is_null($folderId)) {
			$this->paginate = array('findType' => 'rootDirectories');
		} else {
			$this->paginate = array(
				'conditions' => array('UploadedFile.parent_id' => $folderId),
				'contain' => array('User', 'Aco', 'FileStorage'),
				'group' => 'Aco.foreign_key'
			);
		}
		$this->paginate['limit'] = 30;

		$files = $this->paginate();
		foreach ($files as &$file) {
			if ($file['UploadedFile']['is_folder']) {
				$file['UploadedFile']['downloadable'] = $this->UploadedFile->canDownloadFolderAsZip($file['UploadedFile']['id']);
			}
		}

		$parent = $this->UploadedFile->findById($folderId);
		$parentId = ($parent) ? $parent['ParentUploadedFile']['id'] : null;
		$superAdmins = $this->UploadedFile->User->find('superAdmin');
		$this->set(compact('files', 'folderId', 'parentId', 'superAdmins'));
	}

	public function find() {
		$this->Prg->commonProcess();
		$this->paginate['conditions'] = array($this->UploadedFile->parseCriteria($this->passedArgs));
		$files = $this->paginate();
		$parentId = null;
		$folderId = null;
		$this->request->data['UploadedFile'] = $this->passedArgs;
		$this->set(compact('files', 'folderId', 'parentId'));
	}

	/**
	 * @todo : To move in SynchroDossier plugin ?
	 */
	public function createSharing() {
		if ($this->request->is('post')) {
			if ($this->UploadedFile->addSharing($this->request->data, $this->Auth->user())) {
				$this->Acl->allow(
					array('model' => 'User', 'foreign_key' => Configure::write('sd.SuperAdmin.roleId')),
					array('model' => 'UploadedFile', 'foreign_key' => $this->UploadedFile->id)
				);
				$this->Session->setFlash(__d('uploader', 'Le dossier a correctement été créé'), 'default', array('class' => 'alert'));
			} else {
				$errors = $this->UploadedFile->invalidFields();
				$errorMessage = '';
				foreach ($errors as $error) {
					$errorMessage .= implode('<br> ', array_unique($error));
				}
				$this->Session->setFlash(__d('uploader', 'Il y a des erreurs dans les données du formulaire') . '<br>' . $errorMessage, 'default', array('class' => 'alert alert-danger'));
			}
		}
		$this->redirect(array('action' => 'browse'));
	}

	public function createFolder($parentId) {
		if ($this->request->is('post')) {
			if ($this->UploadedFile->addFolder($this->request->data, $parentId, $this->Auth->user('id'))) {
				$this->Session->setFlash(__d('uploader', 'Le dossier a correctement été créé'), 'default', array('class' => 'alert'));
			} else {
				$this->Session->setFlash(__d('uploader', 'Il y a des erreurs dans les données du formulaire'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$this->redirect(array('action' => 'browse', $parentId));
	}

	public function rename($parentId = null, $id = null) {
		if ($this->request->is('put')) {
			if ($this->UploadedFile->rename($this->request->data)) {
			}
		}
		$this->redirect(array('action' => 'browse', $parentId));
	}

	public function downloadZipFolder($folderId) {
		if ($this->UploadedFile->canDownloadFolderAsZip($folderId)) {
			$folder = $this->UploadedFile->findById($folderId);
			if (!empty($folder)) {
				$this->response->download($folder['UploadedFile']['filename'] . '.zip');
				$this->response->body($this->UploadedFile->createZip($folderId));
				$this->response->send();
			}
		} else {
			$messageFlash =
			$this->Session->setFlash(nl2br($this->__messageFlashDownloadNotAvailable));
			$this->redirect(array('action' => 'browse'));
		}
	}

/**
 * Upload d'un fichier
 *
 * @param $originalFilename : Correspond au nom original du fichier pour le cas ou
 * l'utilisateur upload une nouvelle version du fichier en passant par "Uploader une nouvelle version"
 *
 */
	public function upload($folderId, $originalFilename = null) {
		$folderId = $folderId === 'null' ? null : $folderId;
		if (!is_null($originalFilename)) {
			$decodeFileName = $this->_decodeCustomBase64($originalFilename);
		} else {
			$decodeFileName = $originalFilename;
		}
		if ($this->Plupload->isPluploadRequest()) {
			list($uploadFinished, $response, $file) = $this->Plupload->upload();
			if ($uploadFinished) {
				$uploadOk = $this->UploadedFile->upload($file, $this->Auth->user(), $folderId, $decodeFileName);
				if (!$uploadOk) {
					$error = $this->UploadedFile->FileStorage->invalidFields();
					if (isset($error['file'][0])) {
						$response = $this->Plupload->generateJsonMessage(array(
							'error' => array('code' => 104, 'message' => $error['file'][0])
						));
					}
				}
			}
		} elseif($this->request->is('post')) {
			$uploadOk = $this->UploadedFile->upload(
				$this->request->data['UploadedFile'],
				$this->Auth->user(),
				$folderId,
				$decodeFileName
			);
			if (!$uploadOk) {
				$error = $this->UploadedFile->FileStorage->invalidFields();
				if (isset($error['file'][0])) {
					$response = $this->Session->setFlash($error['file'][0], 'default', array('class' => 'alert alert-danger'));
				}
			}
			$this->redirect(array('action' => 'browse', $folderId));
		}
		$this->set(compact('folderId'));
	}

	public function allFilesUploadedInBatch($folderId) {
		$this->getEventManager()->dispatch(new CakeEvent(
				'Controller.Files.allFilesUploadedInBatch',
				$this,
				array('user' => $this->Auth->user())
		));
		$this->autoRender = false;
		$this->redirect(array('action' => 'browse', $folderId));
	}

	public function download($fileStorageId = null) {
		list($content, $filename, $mimeType) = $this->UploadedFile->download($fileStorageId);
		$this->response->download($filename);
		$this->response->body($content);
		$this->response->type($mimeType);
		$this->response->send();
		return $this->response;
	}

	public function preview($uploadedFileId = null) {
		list($content, $filename, $mimeType) = $this->UploadedFile->downloadLatestVersion($uploadedFileId);
		$this->set(compact('content', 'mimeType'));
		$this->layout = false;
	}

	public function deleteFolder($folderId) {
		$this->UploadedFile->id = $folderId;
		$folderName = $this->UploadedFile->field('filename');
		if ($this->UploadedFile->removeFolder($folderId, $this->Auth->user('id'))) {
			$messageFlash = __d('uploader', 'Folder "%s" was successfully deleted', $folderName);
			$class = array('class' => 'success');
		} else {
			$messageFlash = __d('uploader', 'You cannot delete folder "%s"', $folderName);
			$class = array('class' => 'error');
		}

		$this->Session->setFlash($messageFlash, 'default', $class);
		$this->redirect(array('action' => 'browse'));
	}


	public function deleteFile($fileId, $fileStorageId) {
		$fileStorage = $this->UploadedFile->FileStorage->find('first', array(
			'conditions' => array(
				'FileStorage.id' => $fileStorageId
			),
		));
		$this->UploadedFile->id = $fileStorage['FileStorage']['foreign_key'];
		$file = $this->UploadedFile->findById($fileId);
		if ($this->UploadedFile->removeFile($fileId, $fileStorageId, $this->Auth->user('id'))) {
			$messageFlash = __d('uploader', 'The file "%s" was successfully deleted', $file['UploadedFile']['filename']);
			$class = array('class' => 'success');
		} else {
			$messageFlash = __d('uploader', 'You cannot delete file "%s"', $file['UploadedFile']['filename']);
			$class = array('class' => 'error');
		}

		$this->Session->setFlash($messageFlash, 'default', $class);
		$this->redirect(array('action' => 'browse'));
	}

	protected function _decodeCustomBase64($encodedString, $replacementCaracters = array('-' => '/', '_' => '=')) {
		$tmp = str_replace(array_keys($replacementCaracters), array_values($replacementCaracters), $encodedString);
		return base64_decode($tmp);
	}

	public function addTags($fileId) {
		if ($this->request->is('ajax')) {
			$this->layout = null;
		}

		if ($this->request->is('post')) {
			if (!empty($this->request->data['Tags']['tags'])) {
				$data = array(
					'FileTag' => array(),
					'UploadedFile' => array('id' => $fileId),
				);
				$tags = array_map('trim', explode(',', $this->request->data['Tags']['tags']));

				foreach ($tags as $tag) {
					$termId = $this->UploadedFile->FileTag->Term->saveAndGetId(array(
						'title' => $tag,
						'slug' => Inflector::slug(strtolower($tag), '-'),
					));

					if ($taxonomyId = $this->UploadedFile->FileTag->termInScope($termId)) {
						array_push($data['FileTag'], array('taxonomy_id' => $taxonomyId));
					} else {
						$taxonomy = $this->UploadedFile->FileTag->save(array('FileTag' => array('term_id' => $termId)));
						if ($taxonomy) {
							array_push($data['FileTag'], array('taxonomy_id' => $this->UploadedFile->FileTag->getLastInsertId()));
						}
					}
				}

				if ($this->UploadedFile->save($data)) {
					$messageFlash = __d('uploader', 'Tags successfuly added to file');
					$class = array('class' => 'success');
				} else {
					$messageFlash = __d('uploader', 'There was an error while tagging the file');
					$class = array('class' => 'error');
				}
				$this->Session->setFlash($messageFlash, 'default', $class);

				$file = $this->UploadedFile->findById($fileId);
				$this->redirect(array(
					'controller' => 'files',
					'action' => 'browse',
					$file['ParentUploadedFile']['id']
				));
			}
		} else {
			$file = $this->UploadedFile->find('first', array(
				'contain' => array('FileTag', 'FileTag.Term'),
				'conditions' => array(
					$this->UploadedFile->escapeField() => $fileId
				)
			));

			$this->request->data['Tags']['tags'] = implode(', ', Hash::extract($file, 'FileTag.{n}.Term.title'));
		}
	}

}
