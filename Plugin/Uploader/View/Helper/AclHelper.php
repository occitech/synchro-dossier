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

	public function userCan($uploadedFileId, $action = 'read') {
		if ($this->__userId == 1) {
			// special case of admin ...
			return true;
		}

		$action = '_' . $action;
		foreach ($this->__userRights as $aco) {
			if ($aco['foreign_key'] == $uploadedFileId && $aco['model'] == 'UploadedFile') {
				if (isset($aco['Permission'][$action])) {
					return $aco['Permission'][$action] == 1;
				}
			}
		}
		return false;
	}
}