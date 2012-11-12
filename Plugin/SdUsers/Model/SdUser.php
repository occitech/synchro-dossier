<?php

App::uses('User', 'Users.Model');

class SdUser extends User {

	public $displayField = 'name';

	public $useTable = 'users';
	
	public $order = 'User.name ASC';
	
	public $alias = 'User';

	public $findMethods = array('createdBy' =>  true);

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
		)
	);

	public $validate = array(
		'username' => array(
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'The username has already been taken.',
				'last' => true,
			),
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'This field cannot be left blank.',
				'last' => true,
				'required' => true
			),
		),
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

	public function add($data, $creatorId, $creatorRoleId) {
		$this->_addValidateRuleAboutRole($creatorRoleId);
		$this->create();
		$data[$this->alias]['role_id'] = intval($data[$this->alias]['role_id']);
		$data[$this->alias]['activation_key'] = md5(uniqid());
		$data[$this->alias]['creator_id'] = $creatorId;
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
}
