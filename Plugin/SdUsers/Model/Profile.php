<?php
App::uses('SdUsersAppModel', 'SdUsers.Model');
/**
 * Profile Model
 *
 * @property User $User
 */
class Profile extends SdUsersAppModel {

	public $belongsTo = array(
		'User' => array('className' => 'Users.User')
	);

	public $validate = array(
		'name' => array(
			'rule' => 'notEmpty',
			'message' => 'Ce champ ne peut pas être laissé vide'
		),
		'firstname' => array(
			'rule' => 'notEmpty',
			'message' => 'Ce champ ne peut pas être laissé vide'
		),
		'society' => array(
			'rule' => 'notEmpty',
			'message' => 'Ce champ ne peut pas être laissé vide'
		)
	);
}
