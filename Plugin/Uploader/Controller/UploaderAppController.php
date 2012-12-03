<?php

class UploaderAppController extends AppController {

	public function beforeFilter() {
		$this->loadModel('Uploader.UploaderAclAco');
		$this->loadModel('Uploader.UploaderAclAro');
		$this->loadModel('Permission');
	}

	public function beforeRender() {
		$this->helpers[] = 'Uploader.Acl';
		$this->helpers[] = 'Plupload.Plupload';

		$userRights = $this->UploadedFile->User->getAllRights($this->Auth->user('id'));
		$can = $this->UploaderAclAco->getRightsCheckFunctions($this->Auth->user());
		$this->set(compact('userRights', 'can'));

		$folderId = isset($this->request->params['pass'][0]) ? $this->request->params['pass'][0] : null;

		$this->set(compact('folderId'));
	}
}