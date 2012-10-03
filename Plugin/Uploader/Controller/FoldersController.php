<?php
App::uses('UploaderAppController', 'Uploader.Controller');
/**
 * Folders Controller
 *
 * @property Folder $Folder
 */
class FoldersController extends UploaderAppController {

	public $actsAs = array('Tree');

	public function index($id) {
		$folders = $this->Folder->findById($id);
		$this->set('folders', $folders);
	}

	public function create($parentId) {
		if ($this->Auth->user('id') != null) {
			if ($this->request->data) {
				if (!$this->Folder->add($this->request->data, $parentId)) {
					$this->Session->setFlash(__('Impossible to create this folder. Contact the administrator'));
				}
				$this->redirect(array('action' => 'index', $parentId));
			}
		} else {
			$this->redirect(array('plugin' => 'users', 'controller' => 'users', 'action' => 'login'));
		}
	}

	public function all() {
		$folders = $this->Folder->find('all');
		$allFoldersPath = array(0 => DS);
		foreach ($folders as $folder) {
			$folder = $folder['Folder'];
			$allFoldersPath[$folder['id']] = $allFoldersPath[$folder['parent_id']] . $folder['name'] . DS;	
		}
		debug($allFoldersPath);
	}
}
