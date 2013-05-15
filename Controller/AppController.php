<?php

App::uses('CroogoAppController', 'Croogo.Controller');

class AppController extends CroogoAppController {

public function beforeFilter() {
	parent::beforeFilter();
	$sessionConfig = array_merge(
		array('lang'=> Configure::read('Config.language')),
		$this->Session->read('Config')
	);
	if ($this->Auth->loggedIn()) {
		$userId = $this->Auth->user('id');
		$this->loadModel('SdUsers.Profile', $userId);
		$userSetting = $this->Profile->find(
			'first',
			array(
				'conditions' => array($this->Profile->escapeField('user_id') => $userId),
				'contain' => 'Language',
				'fields' => array('Language.alias')
			)
		);
		if (isset($userSetting['Language'])) {
			$sessionConfig['lang'] = $userSetting['Language']['alias'];
		}
	}
	$this->Session->write('Config', $sessionConfig);
	Configure::write('Config.language', $sessionConfig['lang']);
	$this->loadModel('Settings.Language');
	$languages = $this->Language->find('all', array('fields' => array('title', 'id')));
	$languages = Hash::combine($languages, '{n}.Language.id', '{n}.Language.title');
	$this->set('languages', $languages);
}

}
