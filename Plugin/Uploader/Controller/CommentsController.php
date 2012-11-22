<?php

App::uses('UploaderAppController', 'Uploader.Controller');

class CommentsController extends UploaderAppController {

	public $uses = array('Comments.Comment', 'Uploader.UploadedFile');

	public function add($fileId) {
		if (!$this->UploadedFile->exists($fileId)) {
			$this->redirect($this->referer(), 404);
		}

		$file = $this->UploadedFile->findById($fileId);
		if ($this->request->is('post')) {
			$this->request->data['Comment']['model'] = 'UploadedFile';
			$this->request->data['Comment']['foreign_key'] = $fileId;
			$this->request->data['Comment']['name'] = $this->Auth->user('username');
			$this->request->data['Comment']['email'] = 'azmimik@gmail.com';
			if ($this->Comment->save($this->request->data)) {
				$this->redirect(array(
					'controller' => 'files',
					'action' => 'browse',
					$file['ParentUploadedFile']['id']
				));
			}
		}
		$this->set(compact('file'));
	}
}