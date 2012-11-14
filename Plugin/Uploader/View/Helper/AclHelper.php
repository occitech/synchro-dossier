<?php

App::uses('Helper', 'View');

class AclHelper extends Helper {

	private $__userRights;
	private $__userId;

	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);

		$this->__userId = $View->viewVars['userRights']['User']['id'];
		$this->__userRights = $View->viewVars['userRights']['Aro']['Aco'];
	}

	public function userCan($uploadedFileAco, $action = 'read') {
		$userCan = false;
		$action = '_' . $action;

		if ($this->__userId == 1) {
			return true;
		}

		foreach ($this->__userRights as $aco) {
			if ($aco['lft'] <= $uploadedFileAco['lft'] && $aco['rght'] >= $uploadedFileAco['rght']) {
				if (isset($aco['Permission'][$action])) {
					$userCan = $aco['Permission'][$action] == 1;
				}
			}
		}

		return $userCan;
	}

	public function userCanChangeRight($loggedInUserId, $loggedInUserRoleId, $aroUserId, $aroRoleId, $folderCreatorId) {
		$hasRightToChangeRight = true;

		if ($aroRoleId == Configure::read('sd.Admin.roleId')) {
			if ($loggedInUserRoleId == Configure::read('sd.Admin.roleId')) {
				if ($folderCreatorId != $loggedInUserId) {
					$hasRightToChangeRight = false;
				}
			}
		}

		if ($loggedInUserId == $aroUserId) {
			$hasRightToChangeRight = false;
		}

		return $hasRightToChangeRight;
	}
}