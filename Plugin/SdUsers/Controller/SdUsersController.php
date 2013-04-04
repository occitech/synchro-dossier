<?php
App::uses('SdUsersAppController', 'SdUsers.Controller');
App::uses('SdUser', 'SdUsers.Model');

class SdUsersController extends SdUsersAppController {

	public $uses = array('SdUsers.SdUser');
	public $components = array('SdUsers.Roles');
	public $helpers = array(
		'Form' => array('className' => 'Croogo.CroogoForm'),
		'Html' => array('className' => 'Croogo.CroogoHtml')
	);

	public function index() {
		$this->loadModel('Uploader.UploaderAclAco');
		$can = $this->UploaderAclAco->getRightsCheckFunctions($this->Auth->user());
		$users = $this->paginate();
		$this->set(compact('users', 'can'));
	}

	public function add() {
		if (!empty($this->request->data)) {
			$userId = $this->Auth->user('id');
			$roleId = $this->Auth->user('role_id');
			if ($this->SdUser->add($this->request->data, $userId, $roleId)) {
				$this->Session->setFlash(__('L\'utilisateur à été enregistré'), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('L\'utilisateur ne peux pas être ajouté'), 'default', array('class' => 'error'));
				unset($this->request->data['User']['password']);
			}
		}
		$this->set('roles', $this->SdUser->Role->find('list'));
	}

	public function edit($userId) {
		if ($this->request->data) {
			$roleId = $this->Auth->user('role_id');
			if ($this->SdUser->edit($this->request->data, $roleId)) {
				$this->Session->setFlash(__('L\'utilisateur à été mis à jour'), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('L\'utilisateur ne peux pas être modifié'), 'default', array('class' => 'error'));
			}
		} else {
			$this->request->data = $this->SdUser->find('first', array(
				'conditions' => array('User.' . $this->SdUser->primaryKey => $userId),
				'noRoleChecking' => true
			));			
		}
		$this->set('roles', $this->SdUser->Role->find('list'));
	}

	public function delete($id = null) {
		$httpCode = null;
		if (is_null($id)) {
			$httpCode = 404;
		} else {
			if ($this->SdUser->delete($id)) {
				$this->Session->setFlash(__('L\'utilisateur à été supprimé'), 'default', array('class' => 'success'));
			} else {
				$this->Session->setFlash(__('L\'utilisateur ne peux pas être supprimé'), 'default', array('class' => 'error'));
			}
		}
		$this->redirect(array('action' => 'index'), $httpCode);
	}
}
