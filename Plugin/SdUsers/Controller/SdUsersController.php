<?php
App::uses('SdUsersAppController', 'SdUsers.Controller');
App::uses('SdUser', 'SdUsers.Model');

class SdUsersController extends SdUsersAppController {

	public $uses = array('SdUsers.SdUser');
	public $components = array('SdUsers.Roles');

	public function admin_index() {
		if ($this->Auth->user('role_id') == Configure::read('sd.Admin.roleId')) {
			$this->paginate['SdUser'] = array('findType' => 'createdBy', 'creatorId' => $this->Auth->user('id'));
		} else {
			$this->paginate = array();
		}
		$this->set('users', $this->paginate());
	}

	public function admin_add() {
		if (!empty($this->request->data)) {
			$userId = $this->Auth->user('id');
			$roleId = $this->Auth->user('role_id');
			if ($this->SdUser->add($this->request->data, $userId, $roleId)) {
				$this->Session->setFlash(__('The User has been saved'), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The User could not be saved. Please, try again.'), 'default', array('class' => 'error'));
				unset($this->request->data['SdUser']['password']);
			}
		}
		$this->set('roles', $this->SdUser->Role->find('list'));
	}

	public function admin_edit($userId) {
		if ($this->request->data) {
			$roleId = $this->Auth->user('role_id');
			if ($this->SdUser->edit($this->request->data, $roleId)) {
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

	public function admin_delete($id = null) {
		$httpCode = null;
		if (is_null($id)) {
			$httpCode = 404;
		} else {
			if ($this->SdUser->delete($id)) {
				$this->Session->setFlash(__('User deleted'), 'default', array('class' => 'success'));
			} else {
				$this->Session->setFlash(__('User cannot be deleted'), 'default', array('class' => 'error'));
			}
		}
		$this->redirect(array('action' => 'index'), $httpCode);
	}
}
