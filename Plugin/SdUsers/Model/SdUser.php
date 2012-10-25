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

	public function getPaginateAll() {
		return array();
	}

	public function getPaginateByCreatorId($creatorId) {
		return array(
			'conditions' => array('SdUser.creator_id' => $creatorId)
		);
	}
}
