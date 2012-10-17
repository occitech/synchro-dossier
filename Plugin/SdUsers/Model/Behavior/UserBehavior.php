<?php
class UserBehavior extends ModelBehavior {

	protected $_blacklistedRole = array('3', '4', '5');

	public function beforeValidate(Model $Model) {
		if ($Model instanceof User) {
			$Model->validator()->add('role_id', array(
				'rule' => array('inList', $this->_blacklistedRole),
				'message' => __('Vous ne pouvez pas créer d\'utilisateur de ce groupe')
			));
		}
	}
}