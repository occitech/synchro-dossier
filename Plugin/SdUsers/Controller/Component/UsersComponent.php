<?php

App::uses('Component', 'Controller');

class UsersComponent extends Component {

	protected $_blacklistedRole = array('Occitech', 'Public', 'Registered');

	public function beforeRender(&$Controller) {
		if ($Controller instanceof UsersController) {
			if ($Controller->action == 'admin_add') {
				$Controller->viewVars['roles'] = array_diff($Controller->viewVars['roles'], $this->_blacklistedRole);
			}
		}
	}
}