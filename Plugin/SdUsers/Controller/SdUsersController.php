<?php
App::uses('SdUsersAppController', 'SdUsers.Controller');
App::uses('SdUser', 'SdUsers.Model');

class SdUsersController extends SdUsersAppController {

	public $uses = array('SdUsers.SdUser');
	public $components = array('SdUsers.Roles');

	public function admin_index() {
		$this->loadModel('Uploader.UploaderAclAco');
		$can = $this->UploaderAclAco->getRightsCheckFunctions($this->Auth->user());
		$users = $this->paginate();
		$this->set(compact('users', 'can'));
	}

	public function admin_add() {
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

	public function admin_edit($userId) {
		if ($this->request->data) {
			$roleId = $this->Auth->user('role_id');
			if ($this->SdUser->edit($this->request->data, $roleId)) {
				$this->Session->setFlash(__('L\'utilisateur à été mis à jour'), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('L\'utilisateur ne peux pas être modifié'), 'default', array('class' => 'error'));
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
				$this->Session->setFlash(__('L\'utilisateur à été supprimé'), 'default', array('class' => 'success'));
			} else {
				$this->Session->setFlash(__('L\'utilisateur ne peux pas être supprimé'), 'default', array('class' => 'error'));
			}
		}
		$this->redirect(array('action' => 'index'), $httpCode);
	}

	public function profile($id) {
		$user = $this->SdUser->find('first', array('conditions' => array('User.id' => $id),'noRoleChecking' => true));
		$returnUrl = array('plugin' => 'uploader', 'controller' => 'files', 'action' => 'browse');
		$isAdmin = $this->Auth->user('Role.title') == ROLE_USER_ID;
		$UploadedFile = ClassRegistry::init('Uploader.UploadedFile');

		if (empty($user)) {
			$this->Session->setFlash(__('User #%s Not Found', $id));
			$this->redirect($returnUrl, 404);
		}

		if ($this->Auth->user('id') != $user['User']['id'] && $this->Auth->user('Role.title') == ROLE_USER_ID) {
			$this->Session->setFlash(__('You cannot access others users profiles'));
			$this->redirect($returnUrl, 403);
		}

		$this->set('title_for_layout', __('Your Profile'));
		$this->helpers[] = 'Uploader.UploaderAcl';
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->SdUser->save($this->request->data)) {
				$flashMessage = __('User informations successfully updated');
			} else {
				$flashMessage = __('A problem occurs when updating user informations. Please retry.');
			}
			$this->Session->setFlash($flashMessage);
			$this->redirect($returnUrl);
		}

		$folders = $UploadedFile->getThreadedAllFolders();

		$this->set(compact('isAdmin', 'user', 'folders'));
	}
}
