<?php

class UserBehavior extends ModelBehavior {

	protected $_blacklistedRole = array('4', '5', '6');

	public function setup(Model $Model) {
		if ($Model instanceof User) {
			$Model->order = $Model->alias . '.name ASC';
			$Model->bindModel(array('hasOne' => array(
				'Profile' => array(
					'className' => 'SdUsers.Profile',
					'dependent' => true
				)
			)), false);
		}
	}

	public function beforeValidate(Model $Model) {
		if ($Model instanceof User) {
			$Model->validator()->add('role_id', array(
				'rule' => array('inList', $this->_blacklistedRole),
				'message' => __('Vous ne pouvez pas créer d\'utilisateur de ce groupe')
			));
		}
	}
}