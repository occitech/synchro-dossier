<?php

App::uses('CakeEventManager', 'Event');
App::uses('SdQuotaBehavior', 'SynchroDossier.Model/Behavior');

$callback = new SdQuotaBehavior();

CakeEventManager::instance()->attach(array($callback, 'beforeUpload'), 'Model.UploadedFile.beforeUpload');

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