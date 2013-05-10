<?php

App::uses('AppHelper', 'View/Helper');
App::uses('SdUser', 'SdUsers.Model');
class SdUsersHelper extends AppHelper {

public $helpers = array('Session');
private $__adminRolesIds = array(
	SdUser::ROLE_OCCITECH_ID,
	SdUser::ROLE_SUPERADMIN_ID,
	SdUser::ROLE_ADMIN_ID,
);

private $__userRolesIds = array(SdUser::ROLE_UTILISATEUR_ID);


/**
 * Check if the user logged in is an admin or not
 * @param  array $userData   array return by a Model::find() or Auth::user().
 * @param  boolean $strict   true, check user is admin only, otherwise return true even if user is superadmin.
 * @return boolean
 */
	public function isAdmin($userData = array(), $strict = false) {
		$isAdmin = false;
		if (empty($userData)) {
			$userData = $this->Session->read('Auth.User');
		}

		if (!empty($userData)) {
			if (array_key_exists('User', $userData)) {
				$userData = $userData['User'];
			}
			if ($strict) {
				$isAdmin = $userData['role_id'] === SdUser::ROLE_ADMIN_ID;
			} else {
				$isAdmin = in_array($userData['role_id'], $this->__adminRolesIds);
			}
		}

		return $isAdmin;
	}

}