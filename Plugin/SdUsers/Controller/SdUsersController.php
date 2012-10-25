<?php
App::uses('SdUsersAppController', 'SdUsers.Controller');
App::uses('SdUser', 'SdUsers.Model');

class SdUsersController extends SdUsersAppController {

	public $uses = array('SdUsers.SdUser');

	public function admin_index() {
		if ($this->Auth->user('role_id') == Configure::read('sd.Admin.roleId')) {
			$this->paginate = $this->SdUser->getPaginateByCreatorId($this->Auth->user('id'));
		} else {
			$this->paginate = $this->SdUser->getPaginateAll();
		}
		// debug($this->paginate('SdUser'));
		$this->set('users', $this->paginate('SdUser'));
	}

	public function admin_edit($userId) {
		if ($this->request->data) {
			if ($this->SdUser->saveAssociated($this->request->data)) {
				$this->Session->setFlash(__('The User has been saved'), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The User could not be saved. Please, try again.'), 'default', array('class' => 'error'));
			}
		} else {
			$this->request->data = $this->SdUser->read(null, $userId);
		}
		$this->set('roles', $this->SdUser->Role->find('list'));
	}
}
