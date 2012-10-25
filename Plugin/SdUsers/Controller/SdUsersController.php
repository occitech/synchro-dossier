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
		$this->set('users', $this->paginate('SdUser'));
	}
}
