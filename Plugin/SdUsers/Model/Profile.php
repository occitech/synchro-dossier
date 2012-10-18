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
}
