<?php

App::uses('Helper', 'View');

class UploaderAclHelper extends Helper {

	private $__userRights = array();
	private $__userId = array();

	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);

		if (!empty($View->viewVars['userRights'])) {
			$this->__userId = $View->viewVars['userRights']['User']['id'];
			$this->__userRights = $View->viewVars['userRights']['Aro']['Aco'];
		}
	}

	public function userCanCreateSharing() {
		$userCan = false;

		if ($this->__userId == 1) {
			return true;
		}

		$userCan = $userCan || ClassRegistry::init('Permission')->check(
			array('model' => 'User', 'foreign_key' => $this->__userId),
			'createSharing'
		);

		return $userCan;
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
					if ($aco['Permission'][$action] != 0) {
						$userCan = $aco['Permission'][$action] == 1;
					}
				}
			}
		}

		return $userCan;
	}

	public function userCanCreateSubdirectory($uploadedFileAco) {
		return $this->userCan($uploadedFileAco['Aco'], 'create');
	}

	public function userCanShareDirectory($uploadedFileAco) {
		return $this->userCan($uploadedFileAco['Aco'], 'change_right');
	}

}
