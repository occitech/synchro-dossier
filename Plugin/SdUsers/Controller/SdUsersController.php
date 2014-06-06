<?php
App::uses('SdUsersAppController', 'SdUsers.Controller');
App::uses('SdUser', 'SdUsers.Model');
App::uses('UplaodedFile', 'Uploader.Model');

class SdUsersController extends SdUsersAppController {

	public $uses = array('SdUsers.SdUser', 'Uploader.UploadedFile');
	public $components = array('SdUsers.Roles','Security');
	public $paginate;
	public $helpers = array(
		'Form' => array('className' => 'Croogo.CroogoForm'),
		'Html' => array('className' => 'Croogo.CroogoHtml')
	);

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Security->unlockedActions = array_merge($this->Security->unlockedActions, array('deleteAdmin'));
	}

	public function beforeRender() {
		$this->helpers[] = 'Uploader.UploaderAcl';
		$userRights = array();
		if ($this->Auth->user('id')) {
			$userRights = $this->SdUser->getAllRights($this->Auth->user('id'));
		}

		$this->set(compact('userRights'));
	}

	public function index() {
		$this->loadModel('Uploader.UploaderAclAco');
		$can = $this->UploaderAclAco->getRightsCheckFunctions($this->Auth->user());
		$this->paginate['findType'] = 'visibleBy';
		$this->paginate['userId'] = $this->Auth->user('id');
		$this->paginate['contain'] = array('Role', 'Profile');
		$this->paginate['limit'] = 10;

		$users = $this->paginate();
		$admins = $this->SdUser->find('admin');
		$admins = Hash::combine($this->SdUser->find('admin'), '{n}.User.id', '{n}.User.username');

		$this->set(compact('users', 'can', 'admins'));
	}

	public function add() {
		if (!empty($this->request->data)) {
			$messages = $this->__getUserCreationFlashMessage($this->request->data['User']['email']);
			if ($this->SdUser->addCollaborator($this->request->data, $this->Auth->user('id'))) {
				$this->Session->setFlash($messages['success'], 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash($messages['fail'], 'default', array('class' => 'error'));
				unset($this->request->data['User']['password']);
			}
		}
		$this->set('roles', $this->SdUser->Role->find('list'));
	}

	private function __getUserCreationFlashMessage($email) {
		$messages = array();
		if(!empty($this->SdUser->hasAny(array('email' => $email)))) {
			$messages['success'] = __d('sd_users', 'Un utilisateur avec la même adresse email existe déjà, il a été ajouté à votre liste');
			$messages['fail'] = __d('sd_users', 'Un utilisateur avec la même adresse email est déjà présent dans votre liste');
		} else {
			$messages['success'] = __d('sd_users', 'L\'utilisateur a été enregistré');
			$messages['fail'] = __d('sd_users', 'L\'utilisateur ne peux pas être ajouté');
		}
		return $messages;
	}

	public function edit($userId) {
		$returnUrl = array('plugin' => 'uploader', 'controller' => 'files', 'action' => 'browse');
		if ($this->request->data) {
			if ($this->SdUser->editCollaborator($this->request->data, $this->Auth->user('id'))) {
				$this->Session->setFlash(__d('sd_users', 'L\'utilisateur a été mis à jour'), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__d('sd_users', 'L\'utilisateur ne peux pas être modifié'), 'default', array('class' => 'error'));
			}
		} else {
			$this->request->data = $this->SdUser->find('first', array(
				'conditions' => array('User.' . $this->SdUser->primaryKey => $userId),
				'noRoleChecking' => true
			));

			if ($this->Auth->user('role_id') == SdUser::ROLE_UTILISATEUR_ID) {
				$this->Session->setFlash(__d('sd_users', 'You cannot access this page'));
				$this->redirect($returnUrl, 403);
			}

			$this->__preventAdminToEditSuperiorRole($this->request->data['User'], __d('sd_users', 'You don\'t have the rights to edit this user'), $returnUrl);
		}
		$this->set('roles', $this->SdUser->Role->find('list'));
	}

	public function deleteAdmin() {
		if ($this->request->is('post')) {
			$httpCode = null;
			$this->SdUser->id = $this->request->data['User']['old_admin_id'];
			$success = true;
			if ($this->SdUser->field('role_id') == SdUser::ROLE_ADMIN_ID){
				if (!empty($this->request->data['User']['new_admin_id'])) {
					$this->SdUser->transmitRight($this->request->data['User']['old_admin_id'], $this->request->data['User']['new_admin_id']);
				}
				$success = $this->SdUser->delete();
			} else {
				$success = false;
			}

			if ($success) {
				$this->Session->setFlash(__d('sd_users', 'L\'utilisateur a été supprimé'), 'default', array('class' => 'success'));
			} else {
				$this->Session->setFlash(__d('sd_users', 'L\'utilisateur ne peux pas être supprimé'), 'default', array('class' => 'error'));
			}
		}
		$this->redirect(array('action' => 'index'), $httpCode);
	}

	public function delete($id = null) {
		$httpCode = null;
		if (is_null($id)) {
			$httpCode = 404;
		} else {
			$success = true;

			if($this->Auth->user('role_id') == SdUser::ROLE_ADMIN_ID && $this->SdUser->field('role_id') == SdUser::ROLE_UTILISATEUR_ID){
				$success = $this->SdUser->removeCollaborator($id, $this->Auth->user('id'));
			} else {
				$success = $this->SdUser->delete($id);
			}

			if ($success) {
				$this->Session->setFlash(__d('sd_users', 'L\'utilisateur a été supprimé'), 'default', array('class' => 'success'));
			} else {
				$this->Session->setFlash(__d('sd_users', 'L\'utilisateur ne peux pas être supprimé'), 'default', array('class' => 'error'));
			}
		}
		$this->redirect(array('action' => 'index'), $httpCode);
	}

	private function __preventAdminToEditSuperiorRole($user, $message, $returnUrl) {
		if ($this->Auth->user('role_id') == SdUser::ROLE_ADMIN_ID && (
			$user['role_id'] == SdUser::ROLE_SUPERADMIN_ID ||
			$user['role_id'] == SdUser::ROLE_OCCITECH_ID)) {
			$this->Session->setFlash($message);
			$this->redirect($returnUrl, 403);
		}
	}

	private function __preventUserToAccessNotAllowedPersonnalpages($user, $returnUrl) {
		if (empty($user)) {
			$this->Session->setFlash(__d('sd_users', 'User #%s Not Found', $id));
			$this->redirect($returnUrl, 404);
		}

		if ($this->Auth->user('id') != $user['User']['id'] && $this->Auth->user('role_id') == SdUser::ROLE_UTILISATEUR_ID) {
			$this->Session->setFlash(__d('sd_users', 'You cannot access others users profiles'));
			$this->redirect($returnUrl, 403);
		}
	}

	public function alert($id) {
		$this->set('title_for_layout', __d('sd_users', 'Notifications'));

		$user = $this->SdUser->find('first', array('conditions' => array('User.id' => $id),'noRoleChecking' => true));
		$returnUrl = array('plugin' => 'uploader', 'controller' => 'files', 'action' => 'browse');

		$this->__preventUserToAccessNotAllowedPersonnalpages($user, $returnUrl);
		$this->__preventAdminToEditSuperiorRole($user['User'], __d('sd_users', 'You don\'t have the rights to edit this profile'), $returnUrl);

		$this->paginate = array(
			'conditions' => array('UploadedFile.is_folder' => true, 'UploadedFile.parent_id' => null),
			'recursive' => -1,
			'contain' => array('Aco.Aro.Permission')
		);

		$folders = $this->paginate('UploadedFile');
		$emailsAlerts = $this->__getUserAlertEmails($user['User']['id'] , $folders);
		$this->set(compact('user', 'folders', 'emailsAlerts'));
	}

	public function profile($id) {
		$this->set('title_for_layout', __d('sd_users', 'Your Profile'));
		$this->helpers[] = 'Uploader.UploaderAcl';

		$user = $this->SdUser->find('first', array('conditions' => array('User.id' => $id),'noRoleChecking' => true));
		$returnUrl = array('plugin' => 'uploader', 'controller' => 'files', 'action' => 'browse');

		$this->__preventUserToAccessNotAllowedPersonnalpages($user, $returnUrl);
		$this->__preventAdminToEditSuperiorRole($user['User'], __d('sd_users', 'You don\'t have the rights to edit this profile'), $returnUrl);

		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['User']['name'] = sprintf('%s %s', $this->request->data['Profile']['firstname'], $this->request->data['Profile']['name']);
			if ($this->SdUser->saveAssociated($this->request->data)) {
				$this->Session->write('Auth', $this->SdUser->read(null, $this->Auth->User('id')));
				$flashMessage = __d('sd_users', 'User informations successfully updated');
				$class = array('class' => 'success');
			} else {
				$flashMessage = __d('sd_users', 'A problem occurs when updating user informations. Please retry.');
				$class = array('class' => 'error');
			}
			$this->Session->setFlash($flashMessage, 'default', $class);
			$this->redirect(array('action' => 'profile', $id));
		}

		$this->set(compact('user'));
	}



	public function manageAlertEmail($userId, $folderId, $register = true) {
		$SdAlertEmail = ClassRegistry::init('SynchroDossier.sdAlertEmail');
		$data = array('user_id' => $userId, 'uploaded_file_id' => $folderId);

		if ($register) {
			$_methodToCall = 'save';
		} else {
			$_methodToCall = 'deleteAll';
		}

		$success = $SdAlertEmail->{$_methodToCall}($data);
		$folderName = ClassRegistry::init('Uploader.UploadedFile')->field('filename', array('id' => $folderId));

		if ($success) {
			$class = array('class' => 'success');
			if ($register) {
				$messageFlash = __d('sd_users', 'You are successfully subscribed to email alert for folder %s (#%s)', $folderName, $folderId);
			} else {
				$messageFlash = __d('sd_users', 'You have successfully cancel your subscription to folder %s (#%s)', $folderName, $folderId);
			}
		} else {
			$class = array('class' => 'error');
			if ($register) {
				$messageFlash = __d('sd_users', 'Something went wrong when subscribing to folder %s (#%s)', $folderName, $folderId);
			} else {
				$messageFlash = __d('sd_users', 'Something went wrong when canceling your subscription to folder %s (#%s)', $folderName, $folderId);
			}
		}


		$this->Session->setFlash($messageFlash, 'default', $class);
		$this->redirect(array('action' => 'alert', $userId));
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
				$class = array('class' => 'error');
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

	public function forgot(){
		$this->set('title_for_layout', __d('croogo', 'Forgot Password'));

		if (!empty($this->request->data) && isset($this->request->data['User']['email'])) {
			$this->SdUser->contain(array('Profile'));
			$user = $this->SdUser->findByEmail($this->request->data['User']['email']);
			if (!isset($user['User']['id'])) {
				$this->Session->setFlash(__d('croogo', 'Invalid email.'), 'default', array('class' => 'error'));
				$this->redirect(array('action' => 'forgot'));
			}

			$this->SdUser->id = $user['User']['id'];
			$activationKey = md5(uniqid());
			$this->SdUser->saveField('activation_key', $activationKey);
			$this->set(compact('user', 'activationKey'));

			$emailSent = $this->_sendEmail(
				array(Configure::read('Site.title'), $this->__getSenderEmail()),
				$user['User']['email'],
				__d('sd_users', '%s - Reset Password', Configure::read('Site.title')),
				'SdUsers.forgot_password',
				'reset password',
				$this->theme,
				compact('user','activationKey')
			);

			if ($emailSent) {
				$this->Session->setFlash(__d('croogo', 'An email has been sent with instructions for resetting your password.'), 'default', array('class' => 'success'));
				$this->redirect(array('plugin' => 'users', 'controller' => 'users', 'action' => 'login'));
			} else {
				$this->Session->setFlash(__d('croogo', 'An error occurred. Please try again.'), 'default', array('class' => 'error'));
			}
		}
	}

	public function reset($email = null, $key = null) {
		$this->set('title_for_layout', __d('croogo', 'Reset Password'));

		if ($email == null || $key == null) {
			$this->Session->setFlash(__d('croogo', 'An error occurred.'), 'default', array('class' => 'error'));
			$this->redirect(array('action' => 'login'));
		}

		$user = $this->SdUser->find('first', array(
			'conditions' => array(
				'User.email' => $email,
				'User.activation_key' => $key,
			),
		));
		if (!isset($user['User']['id'])) {
			$this->Session->setFlash(__d('croogo', 'An error occurred.'), 'default', array('class' => 'error'));
			$this->redirect(array('plugin' => 'users', 'controller' => 'users', 'action' => 'login'));
		}

		if (!empty($this->request->data) && isset($this->request->data['User']['password'])) {
			$this->SdUser->id = $user['User']['id'];
			$user['User']['activation_key'] = md5(uniqid());
			$user['User']['password'] = $this->request->data['User']['password'];
			$user['User']['verify_password'] = $this->request->data['User']['verify_password'];
			$options = array('fieldList' => array('password', 'verify_password', 'activation_key'));
			if ($this->SdUser->save($user['User'], $options)) {
				$this->Session->setFlash(__d('croogo', 'Your password has been reset successfully.'), 'default', array('class' => 'success'));
				$this->redirect(array('plugin' => 'users', 'controller' => 'users', 'action' => 'login'));
			} else {
				$this->Session->setFlash(__d('croogo', 'An error occurred. Please try again.'), 'default', array('class' => 'error'));
			}
		}

		$this->set(compact('user', 'email', 'key'));
	}


	/**
	 * Convenience method to send email
	 * @param  string $from      email sender
	 * @param  string $to        email receiver
	 * @param  string $subject   subject
	 * @param  string $template  template to use
	 * @param  string $theme     theme to use
	 * @param  array  $viewVars   vars to use inside template
	 * @param  string $emailType user activiation, reset password, use in log message when failing.
	 * @return boolean			 True if email was sent false otherwise.
	 */
		protected function _sendEmail($from, $to, $subject, $template, $emailType, $theme = null, $viewVars = null) {
			if (is_null($theme)) {
				$theme = $this->theme;
			}
			$success = false;

			try {

				$email = new CakeEmail();
				$email->from($from[1], $from[0]);
				$email->to($to);
				$email->emailFormat('both');
				$email->subject($subject);
				$email->template($template);
				$email->viewVars($viewVars);
				$email->theme($theme);

				$success = $email->send();
			} catch (SocketException $e) {
				$this->log(sprintf('Error sending %s notification : %s', $emailType, $e->getMessage()));
			}

			return $success;
		}

	private function __getUserAlertEmails($userId, $folders){
		return  ClassRegistry::init('SynchroDossier.SdAlertEmail')->find('all', array(
			'conditions' => array(
				'SdAlertEmail.user_id' => $userId,
				'SdAlertEmail.uploaded_file_id' => Hash::extract($folders, '{n}.UploadedFile.id')
			)
		));
	}

	private function __getSenderEmail(){
		return Configure::read('Site.email');
	}
}
