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
		'visibleBy' =>  true,
		'superAdmin' => true
	);

	public $belongsTo = array(
		'Role' => array(
			'className' => 'Role',
			'foreignKey' => 'role_id',
		),
		'Creator' => array(
			'className' => 'SdUsers.User',
			'foreignKey' => 'creator_id',
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

	public $hasAndBelongsToMany = array(
		'Collaboration' => array(
			'className' => 'SdUsers.SdUser',
			'joinTable' => 'users_collaborations',
			'foreign_key' => 'user_id',
			'associationForeignKey' => 'parent_id',
			'unique' => true,
		)
	);

	public function __construct($id = false, $table = null, $ds = null) {
		$this->validate = array(
			'email' => array(
				'email' => array(
					'rule' => 'email',
					'message' => __d('sd_users', 'Please provide a valid email address.'),
					'last' => true,
				),
				'isUnique' => array(
					'rule' => 'isUnique',
					'message' => __d('sd_users', 'Email address already in use.'),
					'last' => true,
					'required' => true
				),
			),
			'password' => array(
				'rule' => array('minLength', 6),
				'message' => __d('sd_users', 'Passwords must be at least 6 characters long.'),
			),
			'verify_password' => array(
				'rule' => 'validIdentical',
			),
		);

		parent::__construct($id, $table, $ds);
	}

	protected function _addValidateRuleAboutRole($creatorRoleId) {
		$this->validator()->add('role_id', array(
			'rule' => array('inList', Configure::read('sd.' .  $creatorRoleId . '.authorizeRoleCreation')),
			'message' => __d('sd_users', 'Vous ne pouvez pas créer d\'utilisateur de ce groupe')
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
		$userId = $this->getUserIdIfAlreadyRegistered($data[$this->alias]['email']);

		if(!empty($userId) && !$this->__isCollaboratorWith($userId, $creatorId)) {
			return $this->__markAsCollaboratorOf($userId, $creatorId);
		}

		$this->_addValidateRuleAboutRole($creatorRoleId);
		$this->create();
		$data[$this->alias]['role_id'] = intval($data[$this->alias]['role_id']);
		$data[$this->alias]['activation_key'] = md5(uniqid());
		$data[$this->alias]['creator_id'] = $creatorId;
		$data[$this->alias]['status'] = 1;
		if (empty($data[$this->alias]['username'])) {
			$data[$this->alias]['username'] = strtolower(sprintf('%s%s', $data[$this->Profile->alias]['name'], $data[$this->Profile->alias]['firstname']));
		}
		$data[$this->alias]['name'] = sprintf('%s %s', $data[$this->Profile->alias]['name'], $data[$this->Profile->alias]['firstname']);
		$data[$this->UsersCollaboration->alias]['parent_id'] = $creatorId;
		return $this->saveAssociated($data);
	}

	public function getUserIdIfAlreadyRegistered($email) {
		return $userId = $this->field('id', array('email' => $email));
	}

	private function __isCollaboratorWith($userId, $parentId) {
		return $this->UsersCollaboration->hasAny(array('user_id' => $userId, 'parent_id' => $parentId));
	}

	public function edit($data, $creatorRoleId) {
		$this->_addValidateRuleAboutRole($creatorRoleId);
		$data[$this->alias]['role_id'] = intval($data[$this->alias]['role_id']);
		if ($data[$this->alias]['password'] == '') {
			unset($data[$this->alias]['password']);
		}
		return $this->saveAssociated($data);
	}

	protected function _findVisibleBy($state, $query, $results = array()) {
		if (empty($query['userId'])) {
			trigger_error('The key "userId" is mandatory');
		}

		$userRole = $this->field('role_id', array('id' => $query['userId']));
		if ($state == 'before') {
			$query['conditions'][$this->escapeField() . ' !='] = $query['userId'];
			if ($userRole == self::ROLE_ADMIN_ID) {
				$query['recursive'] = -1;
				$childrenOfMine = $this->UsersCollaboration->find('all', array(
					'conditions' => array('parent_id' => $query['userId']),
					'fields' => array('user_id')
				));
				$query['conditions'][]['OR'] = array(
					  $this->escapeField() => Hash::extract($childrenOfMine, '{n}.UsersCollaboration.user_id'),
					  $this->escapefield('role_id') => self::ROLE_ADMIN_ID,
				);
			}
			return $query;
		} else {
			return $userRole == self::ROLE_UTILISATEUR_ID ? array() : $results;
		}
	}

	protected function _findSuperAdmin($state, $query, $results = array()) {
		if ($state == 'before') {
			$query['conditions'][$this->alias . '.role_id'] = Configure::read('sd.SuperAdmin.roleId');
			$query['contain'] = array('Profile', 'Role', 'Creator');
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
			throw new NotFoundException(__d('sd_users', 'User #%s was not found', $id));
		}

		if (empty($newPassword)) {
			throw new InvalidArgumentException(__d('sd_users', 'Empty password is not allowed'));
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

	private function __markAsCollaboratorOf($userId, $collaboratorId) {
		return (bool)$this->UsersCollaboration->save(array(
			'user_id' => $userId,
			'parent_id' => $collaboratorId
		));
	}

}
