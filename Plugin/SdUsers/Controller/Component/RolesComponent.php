<?php

App::uses('Component', 'Controller');

class RolesComponent extends Component {

	protected function __restrictAllowedRoles(&$Controller) {
		$roleId = $Controller->Auth->user('role_id');
		$whiteListRole = Configure::read('sd.' . $roleId . '.authorizeRoleCreation');
		$whiteListRole = array_flip($whiteListRole);
		$Controller->viewVars['roles'] = array_intersect_key($Controller->viewVars['roles'], $whiteListRole);
	}

	public function beforeRender(Controller $Controller) {
		if ($Controller instanceof SdUsersController) {
			if (in_array($Controller->action, array('add', 'edit'))) {
				$this->__restrictAllowedRoles($Controller);
			}
		}
	}
}
