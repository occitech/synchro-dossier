<?php

App::uses('Component', 'Controller');

class RolesComponent extends Component {

	protected $_blacklistedRole = array('Occitech', 'Public', 'Registered');

	public function beforeRender(&$Controller) {
		if ($Controller instanceof SdUsersController) {
			if (in_array($Controller->action, array('admin_add', 'admin_edit'))) {
				$Controller->viewVars['roles'] = array_diff($Controller->viewVars['roles'], $this->_blacklistedRole);
			}
		}
	}
}