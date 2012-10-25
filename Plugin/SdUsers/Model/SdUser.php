<?php
App::uses('SdUsersAppModel', 'SdUsers.Model');

class SdUser extends SdUsersAppModel {

	public $displayField = 'name';

	public $useTable = 'users';

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

	public function add($data, $creatorId) {
		$this->create();
		$data['SdUser']['activation_key'] = md5(uniqid());
		$data['SdUser']['creator_id'] = $creatorId;
		return $this->saveAssociated($data);
	}

	public function getPaginateAll() {
		return array();
	}

	public function getPaginateByCreatorId($creatorId) {
		return array(
			'conditions' => array('SdUser.creator_id' => $creatorId)
		);
	}
}
