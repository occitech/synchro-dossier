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

	public function getUserCreationFlashMessage($data, $creatorId) {
		$messages = array();
		$userId = $this->field('id', array('email' => $data[$this->alias]['email']));

		if(!empty($userId)) {
			$messages['success'] = __d('sd_users', 'Un utilisateur avec la même adresse email existe déjà, il a été ajouté à votre liste');
			$messages['fail'] = __d('sd_users', 'Un utilisateur avec la même adresse email est déjà présent dans votre liste');
		} else {
			$messages['success'] = __d('sd_users', 'L\'utilisateur à été enregistré');
			$messages['fail'] = __d('sd_users', 'L\'utilisateur ne peux pas être ajouté');
		}
		return $messages;
	}

	public function add($data, $creatorId, $creatorRoleId) {
		$userId = $this->field('id', array('email' => $data[$this->alias]['email']));

		if(!empty($userId) && !$this->__isCollaboratorWith($userId, $creatorId)) {
			return $this->__addCollaboration($userId, $creatorId);
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
			if ($userRole == self::ROLE_ADMIN_ID) {
				$query['conditions'][$this->escapeField() . ' !='] = $query['userId'];
				$query['joins'][] = array(
					'alias' => 'Collaboration',
					'table' => 'users_collaborations',
					'type' => 'INNER',
					'conditions' => array('AND' => array(
						'User.id = Collaboration.user_id',
						'parent_id =' . $query['userId']

				)));
			} else if ($userRole == self::ROLE_SUPERADMIN_ID || $userRole == self::ROLE_OCCITECH_ID) {
				$query['conditions'][$this->escapeField() . ' !='] = $query['userId'];
			}
			return $query;
		}

		if($userRole == self::ROLE_UTILISATEUR_ID) {
			return array();
		}

		return $results;
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

	private function __addCollaboration($userId, $creatorId) {
		return (bool)$this->UsersCollaboration->save(array(
			'user_id' => $userId,
			'parent_id' => $creatorId
		));
	}

}
