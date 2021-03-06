<?php

App::uses('AclNode', 'Model');

class UploaderAclAco extends AclNode {

	public $useTable = 'acos';

	public $alias = 'Aco';

	public $actsAs = array('Containable');

	public $belongsTo = array(
		'ParentAco' => array(
			'className' => 'Aco',
			'foreignKey' => 'parent_id'
		)
	);

	public $hasMany = array(
		'ChildAco' => array(
			'className' => 'Aco',
			'foreignKey' => 'parent_id',
			'dependent' => false
		)
	);

	public $hasAndBelongsToMany = array(
		'Aro' => array(
			'className' => 'Aro',
			'joinTable' => 'aros_acos',
			'foreignKey' => 'aco_id',
			'associationForeignKey' => 'aro_id',
			'unique' => 'keepExisting'
		)
	);

	public function getArosOfFolder($model, $foreignKey) {
		$this->Aro->bindModel(array(
			'belongsTo' => array(
				'User' => array(
					'className' => 'SdUsers.SdUser',
					'foreignKey' => 'foreign_key'
				)
			)
		));

		$result = $this->find(
			'first',
			array(
				'conditions' => array(
					$this->alias . '.model' => $model,
					$this->alias . '.foreign_key' => $foreignKey,
				),
				'contain' => array(
					'Aro' => array('conditions' => 'Aro.model = "User"'),
					'Aro.User.Role',
					'Aro.User.Profile',
				)
			)
		);

		return $result;
	}

	public function getRightsCheckFunctions($userData) {
		$functions = array();

		$functions['canChangeRight'] = $this->__canChangeRight($userData);

		$functions['canCreateUser'] = $this->__canCreateUser($userData);

		$functions['canUpdateUser'] = $this->__canUpdateUser($userData);

		return $functions;
	}

	public function can($userData, $action, $params = array()) {
		$can = false;

		$functions = $this->getRightsCheckFunctions($userData);
		if (array_key_exists($action, $functions)) {
			$can = call_user_func_array($functions[$action], (array) $params);
		}

		return $can;
	}

	private function __canChangeRight($userData) {
		$func = function ($userId, $userRoleId, $folderCreatorId) use ($userData) {
			$hasRightToChangeRight = true;

			if ($userRoleId == Configure::read('sd.Admin.roleId')) {
				if ($userData['role_id'] == Configure::read('sd.Admin.roleId')) {
					if ($folderCreatorId != $userData['id']) {
						$hasRightToChangeRight = false;
					}
				}
			}

			if ($userData['id'] == $userId) {
				$hasRightToChangeRight = false;
			}

			return $hasRightToChangeRight;
		};

		return $func;
	}

	private function __canCreateUser($userData) {
		$func = function () use ($userData) {
			$can = false;

			$authorizeRoles = array(
				Configure::read('sd.Occitech.roleId'),
				Configure::read('sd.SuperAdmin.roleId'),
				Configure::read('sd.Admin.roleId')
			);

			if (in_array($userData['role_id'], $authorizeRoles)) {
				$can = true;
			}

			return $can;
		};

		return $func;
	}

	private function __canUpdateUser($userData) {
		$func = function ($ressource) use ($userData) {
			$can = true;
			if ($userData['role_id'] == Configure::read('sd.Admin.roleId')) {
				$can = $ressource['role_id'] == Configure::read('sd.Utilisateur.roleId');
			}
			return $can;
		};

		return $func;
	}
}
