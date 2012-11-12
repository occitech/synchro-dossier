<?php

App::uses('AclNode', 'Model');

class AclAco extends AclNode {

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

	public function getRights($model, $foreignKey) {
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
					'Aro.User.Role'
				)
			)
		);

		return $result;
	}
}
