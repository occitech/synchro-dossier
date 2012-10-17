<?php
class UserBehavior extends ModelBehavior {

	protected $_blacklistedRole = array('SuperAdmin', 'Admin', 'Utilisateur');

	public function beforeValidate(Model $Model) {
		if ($Model instanceof User) {
			$Model->validator()->add('role_id', 'inList', array(
				'rule' => array('inList', $this->_blacklistedRole),
				'message' => __('Vous ne pouvez pas créer d\'utilisateur de ce groupe')
			));
		}
	}
}