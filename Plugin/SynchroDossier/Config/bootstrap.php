<?php

App::uses('CakeEventManager', 'Event');
App::uses('SdQuotaManager', 'SynchroDossier.Lib');

CakeEventManager::instance()->attach(new SdQuotaManager());

Croogo::hookComponent('*', 'SynchroDossier.SynchroDossier');

$adminMenu = array(
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