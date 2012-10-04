<?php

App::uses('UploadedFile', 'Uploader.Model');

class FilesController extends UploaderAppController {

	public $uses = array('Uploader.UploadedFile');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Security->unlockedActions = 'upload';
	}

/**
 * Affiche le contenu d'un dossier. Permet de naviguer dans l'arborescence
 */
	public function browse($folderId) {
		$data = $this->UploadedFile->generateTreeList(null, null, '{n}.UploadedFile.filename', '_');
		$files = $this->UploadedFile->findById($folderId);
		$this->set('files', $files);
	}

/**
 * Affiche la liste des versions d'un fichier
 */
	public function view($id) {
		$this->UploadedFile->recursive = 3;
		$file = $this->UploadedFile->findById($id);
		$this->set('file', $file);
	}

/**
 * Créer un dossier
 */
	public function createFolder($parentId) {
		if ($this->Auth->user('id') != null) {
			if ($this->request->data) {
				if (!$this->UploadedFile->addFolder($this->request->data, $parentId, $this->Auth->user('id'))) {
					$this->Session->setFlash(__('Impossible to create this folder. Contact the administrator'));
				}
				$this->redirect(array('action' => 'browse', $parentId));
			}
		} else {
			$this->redirect(array('plugin' => 'users', 'controller' => 'users', 'action' => 'login'));
		}
	}

/**
 * Pour le moment sert juste à vérifier que l'on peut bien reconstruire
 * l'arborescence d'un dossier.
 */
	public function downloadZipFolder($folderId) {
		debug($this->UploadedFile->getFoldersPath($folderId));
	}

/**
 * Upload d'un fichier
 *
 * $filename : For special case where the the uploaded file can hasn't the same name that on 
 * 	           the remote server. In that case $filename contains the real name of the file
 *
 */
	public function upload($folderId, $filename = null) {
		if ($this->Auth->user('id') != null) {
			if ($this->request->data) {
				if (!is_null($filename)) {
					$this->request->data['FileStorage']['file']['name'] = $filename;
				}
				try {
					$this->UploadedFile->upload($this->request->data, $this->Auth->user('id'), $folderId);					
				} catch (Exception $e) {
					echo $e;
				}
				$this->redirect(array('controller' => 'files', 'action' => 'browse', $folderId));
			}
		} else {
			$this->redirect(array('plugin' => 'users', 'controller' => 'users', 'action' => 'login'));
		}
	}

	public function download($fileStorageId = null) {
		list($content, $filename) = $this->UploadedFile->download($fileStorageId);
		$this->response->download($filename);
		$this->response->body($content);
		$this->response->send();
	}
}