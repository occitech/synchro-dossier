<?php
App::uses('SdUsersAppModel', 'SdUsers.Model');
/**
 * Profile Model
 *
 * @property User $User
 */
class Profile extends SdUsersAppModel {

	public $belongsTo = array(
		'User' => array('className' => 'Users.User'),
		'Language' => array('className' => 'Settings.Language')
	);

	public $validate = array(
		'name' => array(
			'rule' => 'notEmpty',
			'message' => 'Ce champ ne peut pas être laissé vide'
		)
	);

	public function __construct($id = false, $table = null, $ds = null) {
		$this->validate = array(
			'name' => array(
				'rule' => 'notEmpty',
				'message' => __d('SdUsers', 'Ce champ ne peut pas être laissé vide')
			)
		);

		parent::__construct($id, $table, $ds);
	}

}
