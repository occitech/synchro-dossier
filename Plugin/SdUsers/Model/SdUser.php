<?php

App::uses('User', 'Users.Model');

class SdUser extends User {

	const ROLE_OCCITECH_ID		= 1;
	const ROLE_SUPERADMIN_ID	= 4;
	const ROLE_ADMIN_ID			= 5;
	const ROLE_UTILISATEUR_ID	= 6;

	public $displayField = 'username';

	public $useTable = 'users';

	public $order = 'User.name ASC';

	public $alias = 'User';

	public $findMethods = array(
		'createdBy' =>  true,
		'superAdmin' => true
	);

	public $belongsTo = array(
		'Role' => array(
			'className' => 'Role',
			'foreignKey' => 'role_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Creator' => array(
			'className' => 'SdUsers.User',
			'foreignKey' => 'creator_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public $hasOne = array(
		'Profile' => array(
			'className' => 'SdUsers.Profile',
			'dependent' => true
		),
		'Aro' => array(
			'className' => 'Aro',
			'foreignKey' => 'foreign_key'
		)
	);

	public $validate = array(
		'email' => array(
			'email' => array(
				'rule' => 'email',
				'message' => 'Please provide a valid email address.',
				'last' => true,
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'Email address already in use.',
				'last' => true,
				'required' => true
			),
		),
		'password' => array(
			'rule' => array('minLength', 6),
			'message' => 'Passwords must be at least 6 characters long.',
		),
		'verify_password' => array(
			'rule' => 'validIdentical',
		),
	);



	protected function _addValidateRuleAboutRole($creatorRoleId) {
		$this->validator()->add('role_id', array(
			'rule' => array('inList', Configure::read('sd.' .  $creatorRoleId . '.authorizeRoleCreation')),
			'message' => __('Vous ne pouvez pas créer d\'utilisateur de ce groupe')
		));
	}

	public function beforeFind($queryData) {
		if (!array_key_exists('noRoleChecking', $queryData)) {
			$queryData['conditions'] = Hash::merge((array) $queryData['conditions'], array(
				$this->alias . '.role_id !=' =>  Configure::read('sd.Occitech.roleId')
			));
		} else {
			unset($queryData['noRoleChecking']);
		}
		return $queryData;
	}

	public function add($data, $creatorId, $creatorRoleId) {
		$this->_addValidateRuleAboutRole($creatorRoleId);
		$this->create();
		$data[$this->alias]['role_id'] = intval($data[$this->alias]['role_id']);
		$data[$this->alias]['activation_key'] = md5(uniqid());
		$data[$this->alias]['creator_id'] = $creatorId;
		$data[$this->alias]['status'] = 1;
		if (empty($data[$this->alias]['username'])) {
			$data[$this->alias]['username'] = strtolower(sprintf('%s.%s', $data[$this->Profile->alias]['name'], $data[$this->Profile->alias]['firstname']));
		}
		$data[$this->alias]['name'] = sprintf('%s %s', $data[$this->Profile->alias]['name'], $data[$this->Profile->alias]['firstname']);
		return $this->saveAssociated($data);
	}

	public function edit($data, $creatorRoleId) {
		$this->_addValidateRuleAboutRole($creatorRoleId);
		$data[$this->alias]['role_id'] = intval($data[$this->alias]['role_id']);
		return $this->saveAssociated($data);
	}

	protected function _findCreatedBy($state, $query, $results = array()) {
		if (empty($query['creatorId'])) {
			trigger_error('The key "creatorId" is mandatory');
		}

		if ($state == 'before') {
			$query['conditions'][$this->alias . '.creator_id'] = $query['creatorId'];
			return $query;
		}
		return $results;
	}

	protected function _findSuperAdmin($state, $query, $results = array()) {
		if ($state == 'before') {
			$query['conditions'][$this->alias . '.role_id'] = Configure::read('sd.SuperAdmin.roleId');
			return $query;
		}
		return $results;
	}

	public function getAllRights($userId) {

		$result = $this->find('first', array(
			'noRoleChecking' => '',
			'conditions' => array($this->alias . '.id' => $userId),
			'contain' => array(
				'Aro' => array('conditions' => 'Aro.model = "User"'),
				'Aro.Aco'
			)
		));

		$aro = $this->Aro->find('first', array('conditions' => array(
			'Aro.model' => 'Role',
			'Aro.foreign_key' => $result['User']['role_id']
		)));

		if ($userId == 1) {
			$result['Aro']['Aco'] = array();
		} else {
			$result['Aro']['Aco'] = array_merge(array_values($result['Aro']['Aco']), array_values($aro['Aco']));
		}

		return $result;
	}

	public function changePassword($id, $oldPassword, $newPassword, $newPasswordConfirmation) {
		$this->id = $id;
		$user = $this->find('first', array(
			'conditions' => array($this->escapefield() => $id),
			'noRoleChecking' => true
		));

		if (empty($user)) {
			throw new NotFoundException(__('User #%s was not found', $id));
		}

		if (empty($newPassword)) {
			throw new InvalidArgumentException(__('Empty password is not allowed'));
		}

		$success = false;
		$oldPasswordSaved = $user[$this->alias]['password'];

		if ($oldPasswordSaved === Security::hash($oldPassword, null, true)) {
			if ($newPassword === $newPasswordConfirmation) {
				$success = (bool) $this->saveField('password', $newPassword, true);
			}
		}
		return $success;
	}

	public function parentNode() {
	    return null;
	}
}
