<?php

App::uses('UploadedFile', 'Uploader.Model');
App::uses('Aco', 'Uploader.Model');

class FilesController extends UploaderAppController {

	public $uses = array('Uploader.UploadedFile', 'Uploader.AclAco');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Security->unlockedActions = 'upload';
	}

	protected function _addRight($acoId, $action = '*', $aroId = null) {
		if (is_null($aro)) {
			$aroId = $this->Auth->user('id');
		}
		$this->Acl->allow(
			array('model' => 'UploadedFile', 'foreign_key' => $acoId),
			array('model' => 'User', 'foreign_key' => $aroId),
			$action
		);
	}

	public function rights($folderId) {
		if ($this->UploadedFile->isRootFolder($folderId)) {
			$acos = $this->AclAco->getRights('UploadedFile', $folderId);
			$this->set(compact('acos'));
		} else {
			$this->Session->setFlash(__('Vous ne pouvez pas donner de droit à ce dossier'));
		}
	} 

	public function browse($folderId = null) {
		$this->helpers[] = 'Uploader.File';
		$this->helpers[] = 'Time';

		$this->UploadedFile->recursive = 2;
		if (is_null($folderId)) {
			$files = $this->UploadedFile->find('rootDirectories'); 
		} else {
			$files = $this->UploadedFile->findAllByParent_id($folderId);
		}
		$parent = $this->UploadedFile->findById($folderId);
		$parentId = ($parent) ? $parent['ParentUploadedFile']['id'] : null;
		$this->set(compact('files', 'folderId', 'parentId'));
	}

	public function view($id) {
		$this->UploadedFile->recursive = 3;
		$file = $this->UploadedFile->findById($id);
		$this->set('file', $file);
	}

	public function createSharing() {
		if ($this->request->is('post')) {
			if ($this->UploadedFile->addSharing($this->request->data, $this->Auth->user('id'))) {
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

	public function rename($parentId, $id) {
		if ($this->request->is('put')) {
			if ($this->UploadedFile->rename($id, $this->request->data)) {
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
		if ($this->request->is('post')) {
			$uploadOk = $this->UploadedFile->upload(
				$this->request->data,
				$this->Auth->user('id'),
				$folderId,
				$originalFilename
			);
			if ($uploadOk) {
				$this->redirect(array('controller' => 'files', 'action' => 'browse', $folderId));
			}
		}
	}

	public function download($fileStorageId = null) {
		list($content, $filename) = $this->UploadedFile->download($fileStorageId);
		$this->response->download($filename);
		$this->response->body($content);
		$this->response->send();
	}
}