<?php

App::uses('AclNode', 'Model');

class UploaderAclAro extends AclNode {

	public $useTable = 'aros';

	public $alias = 'Aro';

	public $actsAs = array('Containable');

	public $belongsTo = array(
		'ParentAro' => array(
			'className' => 'Aro',
			'foreignKey' => 'parent_id'
		)
	);

	public $hasMany = array(
		'ChildAro' => array(
			'className' => 'Aro',
			'foreignKey' => 'parent_id',
			'dependent' => false
		)
	);

	public $hasAndBelongsToMany = array(
		'Aco' => array(
			'className' => 'Aco',
			'joinTable' => 'aros_acos',
			'foreignKey' => 'aro_id',
			'associationForeignKey' => 'aco_id',
			'unique' => 'keepExisting'
		)
	);

	public function getUserNotInFolder($folderId) {
		$this->bindModel(array(
			'belongsTo' => array(
				'User' => array(
					'className' => 'SdUsers.SdUser',
					'foreignKey' => 'foreign_key'
 				) 
			)
		));

		$aros = $this->find('all', array('conditions' => array(
			'Aro.model' => 'User',
			'User.role_id !=' => array(
				Configure::read('sd.SuperAdmin.roleId'),
				Configure::read('sd.Occitech.roleId')
			),
		)));

		$users = array();

		foreach ($aros as $aro) {
			$addUser = true;
			if (!empty($aro['Aco'])) {
				foreach ($aro['Aco'] as $aco) {
					if ($aco['model'] == 'UploadedFile' && $aco['foreign_key'] == $folderId) {
						$addUser = false;
					}
				}
			}

			if ($addUser && !is_null($aro['User']['username'])) {
				$users[$aro['User']['id']] = $aro['User']['username']; 
			}
		}

		return $users;
	}
}
