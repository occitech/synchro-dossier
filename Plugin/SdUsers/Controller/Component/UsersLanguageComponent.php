<?php

App::uses('Component', 'Controller');

class UsersLanguageComponent extends Component {

	public function startup(Controller $controller) {
		$sessionConfig = array('lang' => Configure::read('Config.language'));
		if ($controller->Session->check('Config')) {
			$sessionConfig = array_merge(
				$sessionConfig,
				$controller->Session->read('Config')
			);
		}

		if ($controller->Auth->loggedIn() && !$this->__isAdminAction($controller->request)) {
			$userId = $controller->Auth->user('id');
			$controller->loadModel('SdUsers.Profile');
			$userSetting = $controller->Profile->find(
				'first',
				array(
					'conditions' => array($controller->Profile->escapeField('user_id') => $userId),
					'contain' => 'Language',
					'fields' => array('Language.alias')
				)
			);

            $language = empty($userSetting['Language']['alias']) ?"fra" : $userSetting['Language']['alias'];

			$controller->set('userLang', $language);
			$sessionConfig['lang'] = $language;
		}

		$controller->Session->write('Config', $sessionConfig);
		Configure::write('Config.language', $sessionConfig['lang']);

		$controller->loadModel('Settings.Language');
		$languages = $controller->Language->find('all', array('fields' => array('native', 'id')));
		$languages = Hash::combine($languages, '{n}.Language.id', '{n}.Language.native');
		$controller->set('languages', $languages);
	}

	private function __isAdminAction(CakeRequest $request) {
		return $request->param('prefix') === 'admin';
	}

}
