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

	public function beforeRender() {
		$this->helpers[] = 'Uploader.UploaderAcl';

		$userRights = $this->SdUser->getAllRights($this->Auth->user('id'));
		$this->set(compact('userRights'));
	}

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
				$this->Session->setFlash(__d('sd_users', 'L\'utilisateur à été enregistré'), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__d('sd_users', 'L\'utilisateur ne peux pas être ajouté'), 'default', array('class' => 'error'));
				unset($this->request->data['User']['password']);
			}
		}
		$this->set('roles', $this->SdUser->Role->find('list'));
	}

	public function edit($userId) {
		if ($this->request->data) {
			$roleId = $this->Auth->user('role_id');
			if ($this->SdUser->edit($this->request->data, $roleId)) {
				$this->Session->setFlash(__d('sd_users', 'L\'utilisateur à été mis à jour'), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__d('sd_users', 'L\'utilisateur ne peux pas être modifié'), 'default', array('class' => 'error'));
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
				$this->Session->setFlash(__d('sd_users', 'L\'utilisateur à été supprimé'), 'default', array('class' => 'success'));
			} else {
				$this->Session->setFlash(__d('sd_users', 'L\'utilisateur ne peux pas être supprimé'), 'default', array('class' => 'error'));
			}
		}
		$this->redirect(array('action' => 'index'), $httpCode);
	}

	public function profile($id) {
		$this->set('title_for_layout', __d('sd_users', 'Your Profile'));
		$this->helpers[] = 'Uploader.UploaderAcl';

		$user = $this->SdUser->find('first', array('conditions' => array('User.id' => $id),'noRoleChecking' => true));
		$returnUrl = array('plugin' => 'uploader', 'controller' => 'files', 'action' => 'browse');
		$isAdmin = $this->Auth->user('User.role_id') != SdUser::ROLE_UTILISATEUR_ID;


		if (empty($user)) {
			$this->Session->setFlash(__d('sd_users', 'User #%s Not Found', $id));
			$this->redirect($returnUrl, 404);
		}

		if ($this->Auth->user('id') != $user['User']['id'] && $this->Auth->user('Role.title') == SdUser::ROLE_UTILISATEUR_ID) {
			$this->Session->setFlash(__d('sd_users', 'You cannot access others users profiles'));
			$this->redirect($returnUrl, 403);
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->SdUser->saveAssociated($this->request->data)) {
				$flashMessage = __d('sd_users', 'User informations successfully updated');
				$class = array('class' => 'success');
			} else {
				$flashMessage = __d('sd_users', 'A problem occurs when updating user informations. Please retry.');
				$class = array('class' => 'error');
			}
			$this->Session->setFlash($flashMessage, 'default', $class);
			$this->redirect(array('action' => 'profile', $id));
		}

		$folders = $this->__getFolders();
		$emailsAlerts = $this->__getUserAlertEmails($user['User']['id'] , $folders);
		$this->set(compact('isAdmin', 'user', 'folders', 'emailsAlerts'));
	}

	public function manageAlertEmail($userId, $folderId, $register = true) {
		$SdAlertEmail = ClassRegistry::init('SynchroDossier.sdAlertEmail');
		$_methodToCall = '';
		$data = array('user_id' => $userId, 'uploaded_file_id' => $folderId);

		if ($register) {
			$_methodToCall = 'save';
		} else {
			$_methodToCall = 'deleteAll';
		}

		$success = $SdAlertEmail->{$_methodToCall}($data);

		if ($success) {
			if ($register) {
				$messageFlash = __d('sd_users', 'You are successfully subscribed to email alert for folder #%s', $folderId);
			} else {
				$messageFlash = __d('sd_users', 'You have successfully cancel your subscription to folder #%s', $folderId);
			}
		} else {
			if ($register) {
				$messageFlash = __d('sd_users', 'Something went wrong when subscribing to folder #%s ', $folderId);
			} else {
				$messageFlash = __d('sd_users', 'Something went wrong when canceling your subscription to folder #%s ', $folderId);
			}
		}

		$this->Session->setFlash($messageFlash);
		$this->redirect(array('action' => 'profile', $userId));
	}

	public function changeUserPassword() {
		if ($this->request->is('post') || $this->request->is('put')) {
			$userId = $this->request->data['User']['id'];
			$oldPassword = $this->request->data['User']['oldPassword'];
			$newPassword = $this->request->data['User']['newPassword'];
			$confirmationPassword = $this->request->data['User']['confirmationPassword'];

			$success = $this->SdUser->changePassword($userId, $oldPassword, $newPassword, $confirmationPassword);

			if ($success) {
				$messageFlash = __d('sd_users', 'Password has been successfully changed');
				$class = array('class' => 'success');
			} else {
				$messageFlash = __d('sd_users', 'Something went wrong when updating password');
				$class = array('class' => 'success');
			}

			$this->Session->setFlash($messageFlash, 'default', $class);

			$this->redirect(array(
				'plugin' => 'sd_users',
				'controller' => 'sd_users',
				'action' => 'profile',
				$userId
			));
		} else {
			$this->redirect($this->referer(), 405);
		}

	}

	private function __getFolders() {
		return ClassRegistry::init('Uploader.UploadedFile')->find('all', array(
			'conditions' => array('UploadedFile.is_folder' => true),
			'recursive' => -1,
			'contain' => array('Aco.Aro.Permission'),

		));

	}

	private function __getUserAlertEmails($userId, $folders){
		return  ClassRegistry::init('SynchroDossier.SdAlertEmail')->find('all', array(
			'conditions' => array(
				'SdAlertEmail.user_id' => $userId,
				'SdAlertEmail.uploaded_file_id' => Hash::extract($folders, '{n}.UploadedFile.id')
			)
		));
	}
}
