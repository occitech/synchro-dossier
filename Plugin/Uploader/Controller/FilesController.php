<?php

App::uses('UploadedFile', 'Uploader.Model');
App::uses('UploaderAclAco', 'Uploader.Model');

class FilesController extends UploaderAppController {

	public $uses = array('Uploader.UploadedFile', 'Uploader.UploaderAclAco', 'Permission');

	public $components = array(
		'Plupload.Plupload',
		'RowLevelAcl' => array(
			'className' => 'Acl.RowLevelAcl',
			'settings' => array(
				'actionMap' => array(
					'browse' 			=> 'read',
					'createFolder' 		=> 'create',
					'rename' 			=> 'create',
					'downloadZipFolder' => 'read',
					'upload' 			=> 'create',
					'download' 			=> 'read',
					'rights'			=> 'change_right',
					'removeRight'		=> 'change_right',
					'toggleRight'		=> 'change_right'
				),
			)
		)
	);

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Security->unlockedActions = 'upload';
		$this->Security->unlockedActions = 'rename';
	}

	public function beforeRender() {
		$this->helpers[] = 'Uploader.Acl';
		$this->helpers[] = 'Plupload.Plupload';
		$userRights = $this->UploadedFile->User->getAllRights($this->Auth->user('id'));
		$can = $this->UploaderAclAco->getRightsCheckFunctions($this->Auth->user());
		$this->set(compact('userRights', 'can'));

		$folderId = isset($this->request->params['pass'][0]) ? $this->request->params['pass'][0] : null;

		$this->set(compact('folderId'));

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
			'url' => $uploadUrl
		));
	}

	public function rights($folderId) {
		if ($this->UploadedFile->isRootFolder($folderId)) {
			$folder = $this->UploadedFile->findById($folderId);
			$superAdmins = $this->UploadedFile->User->find('superAdmin');
			$acos = $this->UploaderAclAco->getRights('UploadedFile', $folderId);
			$users = $this->UploadedFile->User->find('list');
			$this->set(compact('acos', 'users', 'superAdmins', 'folder'));
		} else {
			$this->Session->setFlash(__('Vous ne pouvez pas donner de droit à ce dossier'));
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
		$this->Permission->allow(
			array('model' => 'User', 'foreign_key' => $userId),
			array('model' => 'UploadedFile', 'foreign_key' => $uploadedFileId),
			$action
		);		
	}

	protected function _denyRight($uploadedFileId, $userId, $action) {
		$this->Acl->deny(
			array('model' => 'User', 'foreign_key' => $userId),
			array('model' => 'UploadedFile', 'foreign_key' => $uploadedFileId),
			$action
		);		
	}

	public function removeRight($acoId, $aroId) {
		$result = $this->_removeRight($acoId, $aroId);
		if (!$result) {
			$this->Session->setFlash(__('There was an error while deleting the right. Thank you try again or contact an administrator'));
		}
		$this->redirect($this->referer());
	}

	public function toggleRight($uploadedFileId, $userId = null, $action = 'read') {
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
						'user' => array('id' => $userId),
						'model' => 'Permission',
						'foreign_key' => $this->Permission->id
					)
				));
			}

		} else {
			$this->Session->setFlash(__('Given information are incorrect')); 
		}
		$this->redirect($this->referer());
	}

	public function browse($folderId = null) {
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
			$files = $this->UploadedFile->find('rootDirectories'); 
		} else {
			$files = $this->UploadedFile->findAllByParent_id($folderId);
		}
		$parent = $this->UploadedFile->findById($folderId);
		$parentId = ($parent) ? $parent['ParentUploadedFile']['id'] : null;
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
				$this->Session->setFlash(__('Folder correctly created'));
				$this->redirect(array('action' => 'browse', $parentId));
			} else {
				$this->Session->setFlash(__('There are errors in the data sent by the form'));
			}
		}
	}

	public function createFolder($parentId) {
		if ($this->request->is('post')) {
			if ($this->UploadedFile->addFolder($this->request->data, $parentId, $this->Auth->user('id'))) {
				$this->Session->setFlash(__('Sub-folder correctly created'));
				$this->redirect(array('action' => 'browse', $parentId));
			} else {
				$this->Session->setFlash(__('There are errors in the data sent by the form'));
			}
		}
	}

	public function rename($parentId = null, $id = null) {
		if ($this->request->is('put')) {
			if ($this->UploadedFile->rename($this->request->data)) {
				$this->redirect(array('action' => 'browse', $parentId));
			}
		} else {
			$this->request->data = $this->UploadedFile->findById($id);
		}
	}

	public function downloadZipFolder($folderId) {
		$folder = $this->UploadedFile->findById($folderId);
		if (!empty($folder)) {		
			$this->response->download($folder['UploadedFile']['filename'] . '.zip');
			$this->response->body($this->UploadedFile->createZip($folderId));
			$this->response->send();
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
		if ($this->Plupload->isPluploadRequest()) {
			list($uploadFinished, $response, $file) = $this->Plupload->upload();
			if ($uploadFinished) {
				$uploadOk = $this->UploadedFile->upload($file, $this->Auth->user(), $folderId, $originalFilename);
				if (!$uploadOk) {
					$error = $this->UploadedFile->FileStorage->invalidFields();
					if (isset($error['file'][0])) {
						$response = $this->Plupload->generateJsonMessage(array(
							'error' => array('code' => 104, 'message' => $error['file'][0]) 
						));
					}
				}
			}
			die($response);
		}
		$this->set(compact('folderId'));
	}

	public function allFilesUploadedInBatch() {
		$this->getEventManager()->dispatch(new CakeEvent(
				'Controller.Files.allFilesUploadedInBatch',
				$this,
				array('user' => $this->Auth->user())
		));
	}

	public function download($fileStorageId = null) {
		list($content, $filename) = $this->UploadedFile->download($fileStorageId);
		$this->response->download($filename);
		$this->response->body($content);
		$this->response->send();
	}
}