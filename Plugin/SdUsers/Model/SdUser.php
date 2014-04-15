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
		'superAdmin' => true,
		'admin' => true
	);

	public $belongsTo = array(
		'Role' => array(
			'className' => 'Role',
			'foreignKey' => 'role_id',
		),
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

	public function addCollaborator($data, $creatorId) {
		$this->__ensureUserCanAssignRole($creatorId, $data[$this->alias]['role_id']);

		$userId = $this->__idOfUniqueUser($data, $creatorId);
		return !empty($userId) && $this->__markAsCollaboratorOf($userId, $creatorId);
	}

	private function __idOfUniqueUser($data, $creatorId) {
		$userEmail = $data[$this->alias]['email'];
		if (!$this->hasAny(array('email' => $userEmail))) {
			$res = $this->__createNewUserForCreator($data, $creatorId);
		}

		return $this->field('id', array(
			$this->escapeField('email') => $userEmail
		));
	}

	private function __ensureUserCanAssignRole($userId, $roleId) {
		$creatorRoleId = $this->find('first', array(
				'conditions' => array($this->escapeField() => $userId),
				'noRoleChecking' => true,
				'fields' => array('role_id'),
				'recursive' => -1,
			)
		);

		$allowedRoleIds = Configure::read('sd.' .  $creatorRoleId['User']['role_id'] . '.authorizeRoleCreation');
		if (!in_array($roleId, $allowedRoleIds)) {
			throw new UnauthorizedException(__d(
				'sd_users',
				'The role %s cannot create users with role %s',
				$creatorRoleId,
				$roleId
			));
		}
	}

	private function __createNewUserForCreator($data, $creatorId) {
		$this->create();
		$data[$this->alias]['role_id'] = intval($data[$this->alias]['role_id']);
		$data[$this->alias]['activation_key'] = md5(uniqid());
		$data[$this->alias]['status'] = 1;
		if (empty($data[$this->alias]['username'])) {
			$data[$this->alias]['username'] = strtolower(sprintf('%s%s', $data['Profile']['name'], $data['Profile']['firstname']));
		}
		$data[$this->alias]['name'] = sprintf('%s %s', $data['Profile']['firstname'], $data['Profile']['name']);
		return $this->saveAssociated($data);
	}

	private function __isCollaboratorWith($userId, $parentId) {
		$parentRole = $this->find('first', array(
				'conditions' => array($this->escapeField() => $parentId),
				'noRoleChecking' => true,
				'fields' => array('role_id'),
				'recursive' => -1,
			)
		);
		if ($parentRole['User']['role_id'] == self::ROLE_OCCITECH_ID || $parentRole['User']['role_id'] == self::ROLE_SUPERADMIN_ID) {
			return true;
		}

		return $this->UsersCollaboration->hasAny(array('user_id' => $userId, 'parent_id' => $parentId));
	}

	public function removeCollaborator($userId, $parentId) {
		$success = $this->UsersCollaboration->deleteAll(array('user_id' => $userId, 'parent_id' => $parentId, ));
		if ($success) {
			$this->deleteAroAcoForParentFolder($userId, $parentId);
		}
		return $success;
	}

	public function deleteAroAcoForParentFolder($userId, $parentId) {
		$folderOfParent = ClassRegistry::init('Uploader.UploadedFile')->find('all', array(
			'conditions' => array('UploadedFile.user_id' => $parentId),
			'fields' => array('id'),
			'recursive' => -1
		));

		$userAroId = $this->Aro->find('first', array(
			'noRoleChecking' => '',
			'conditions' => array(
				'Aro.model' => 'User',
				'Aro.foreign_key' => $userId
			),
		));

		$foldersAco = $this->Aro->Aco->find('all', array(
			'noRoleChecking' => true,
			'conditions' => array('foreign_key' => Hash::extract($folderOfParent, '{n}.UploadedFile.id')),
			'recursive' => -1
		));

		$ArosAcoModel = ClassRegistry::init('ArosAco');
		foreach ($foldersAco as $aco) {
			$ArosAcoModel->deleteAll(array(
				'aco_id' => $aco['Aco']['id'],
				'aro_id' => $userAroId['Aro']['id']
			));
		}

	}

	public function editCollaborator($data, $creatorId) {
		$roleId = intval($data[$this->alias]['role_id']);

		$this->__ensureUserCanAssignRole($creatorId, $roleId);
		$this->__ensureIsCollaboratorOf($data[$this->alias][$this->primaryKey], $creatorId);

		$data[$this->alias]['role_id'] = $roleId;
		if ($data[$this->alias]['password'] == '') {
			unset($data[$this->alias]['password']);
		}
		return $this->saveAssociated($data);
	}

	private function __ensureIsCollaboratorOf($userId, $collaboratorId) {
		if (!$this->__isCollaboratorWith($userId, $collaboratorId)) {
			throw new UnauthorizedException(__d(
				'sd_users',
				'The user %s is not collaborator with %s',
				$userId,
				$collaboratorId
			));
		}
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
			$query['contain'] = array('Profile', 'Role');
			return $query;
		}
		return $results;
	}

	protected function _findAdmin($state, $query, $results = array()) {
		if ($state == 'before') {
			$query['conditions'][$this->alias . '.role_id'] = Configure::read('sd.Admin.roleId');
			$query['contain'] = array('Profile', 'Role');
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

	public function transmitRight($oldAdminId, $newAdminId) {
		$ArosAcoModel = ClassRegistry::init('ArosAco');

		$oldAro = $this->Aro->find('first', array(
			'fields' => array('id'),
			'recursive' => -1,
			'conditions' => array(
				'foreign_key' => $oldAdminId,
				'model' => 'User'
			)
		));
		$newAro = $this->Aro->find('first', array(
			'fields' => array('id'),
			'recursive' => -1,
			'conditions' => array(
				'foreign_key' => $newAdminId,
				'model' => 'User'
			)
		));

		$saved = $ArosAcoModel->updateAll(
			array('aro_id' => $newAro['Aro']['id']),
			array('aro_id' => $oldAro['Aro']['id'])
		);
		return $saved;
	}

	private function __markAsCollaboratorOf($userId, $collaboratorId) {
		if ($this->__isCollaboratorWith($userId, $collaboratorId)) {
			return true;
		}

		return (bool) $this->UsersCollaboration->save(array(
			'user_id' => $userId,
			'parent_id' => $collaboratorId
		));
	}

}
