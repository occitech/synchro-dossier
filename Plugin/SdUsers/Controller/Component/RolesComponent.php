<?php

App::uses('Component', 'Controller');

class RolesComponent extends Component {

	public function beforeRender(&$Controller) {
		if ($Controller instanceof SdUsersController) {
			// Change the role list in function of user role
			if (in_array($Controller->action, array('admin_add', 'admin_edit'))) {
				$roleId = $Controller->Auth->user('role_id');
				$whiteListRole = Configure::read('sd.' . $roleId . '.authorizeRoleCreation');
				$whiteListRole = array_flip($whiteListRole);
				$Controller->viewVars['roles'] = array_intersect_key($Controller->viewVars['roles'], $whiteListRole);
			}
		}
	}
}