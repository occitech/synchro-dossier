<?php

App::uses('CakeEventManager', 'Event');
App::uses('SdQuotaManager', 'SynchroDossier.Lib');
App::uses('SdAlertEmailManager', 'SynchroDossier.Lib');
App::uses('SdModeBoxManager', 'SynchroDossier.Lib');
App::uses('SdRightsManager', 'SynchroDossier.Lib');
App::uses('SdUploaderManager', 'SynchroDossier.Lib');


CakeEventManager::instance()->attach(new SdQuotaManager());
CakeEventManager::instance()->attach(new SdAlertEmailManager());
CakeEventManager::instance()->attach(new SdModeBoxManager());
CakeEventManager::instance()->attach(new SdRightsManager());
CakeEventManager::instance()->attach(new SdUploaderManager());

Croogo::hookComponent('*', 'SynchroDossier.SynchroDossier');

$authConfig = array(
	'all' => array(
		'userModel' => 'SdUsers.SdUser',
		'fields' => array(
			'username' => 'email',
			'password' => 'password',
		),
		'scope' => array(
			'User.status' => 1,
		),
	),
	'Form',
);

Configure::write('Acl.Auth.authenticate', $authConfig);

$adminMenu = array(
	'icon' => array('file', 'large'),
	'title' => __('SynchroDossier'),
	'url' => array(
		'admin' => true,
		'plugin' => 'synchro_dossier',
		'controller' => 'sd_informations',
		'action' => 'index',
	),
	'children' => array(
		'list' => array(
			'title' => __('Quota'),
			'url' => array(
				'admin' => true,
				'plugin' => 'synchro_dossier',
				'controller' => 'sd_informations',
				'action' => 'quota',
			),
		),
	),
);

CroogoNav::add('synchro', $adminMenu);

Configure::write('sd.mail.quotaExceeded.subject', __('Synchro-Dossier - Quota dépassé'));
Configure::write('sd.mail.quotaExceeded.from', array('admin@synchro-dossier.fr' => 'Synchro Dossier'));
Configure::write('sd.mail.alertEmailNewUpload.subject', __('Synchro-Dossier - Nouveaux fichiers envoyés'));
Configure::write('sd.mail.alertEmailNewUpload.from', array('admin@synchro-dossier.fr' => 'Synchro Dossier'));

Configure::write('sd.config.useSsl', true);
Configure::write('sd.config.useModeBox', true);

Configure::write('Asset.timestamp', 'force');