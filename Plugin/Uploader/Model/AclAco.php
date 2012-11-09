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
		$this->contain(array('Aro.model = "User"'));
		$result = $this->find(
			'first',
			array('conditions' => array(
				$this->alias . '.model' => $model,
				$this->alias . '.foreign_key' => $foreignKey,
			))
		);

		return $result;
	}
}
