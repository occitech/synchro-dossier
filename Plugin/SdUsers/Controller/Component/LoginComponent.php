<?php

App::uses('Component', 'Controller');

class LoginComponent extends Component {

	public function beforeRender(Controller $Controller) {
		if ($Controller instanceof UsersController) {
			if (in_array($Controller->action, array('login'))) {
				$Controller->view = 'SdUsers.SdUsers/login';
			}
		}
	}
}
