<?php

App::uses('StorageManager', 'FileStorage.Lib');

Configure::write('FileStorage.adapter', 'Local');
Configure::write('FileStorage.testFolder', APP . 'tmp' . DS . 'tests' . DS . 'Uploader');
Configure::write('FileStorage.filePattern', '{user_id}/{file_id}/{version}-{filename}');

Configure::write('sd.uploadedFileRootAco.alias', 'uploadedFileAco');

StorageManager::config(
	'Local',
	array(
		'adapterOptions' => array(APP . DS . 'uploads', true),
		'adapterClass' => '\Gaufrette\Adapter\Local',
		'class' => '\Gaufrette\Filesystem'
	)
);

StorageManager::config(
	'RemoteFtp',
	array(
		'adapterOptions' => array(
			'/home/synchrodossier',
			'localhost',
			'synchrodossier',
			'synchrodossier'
		),
		'adapterClass' => '\Gaufrette\Adapter\Ftp',
		'class' => '\Gaufrette\Filesystem'
	)
);


StorageManager::config(
	'Test',
	array(
		'adapterOptions' => array(Configure::read('FileStorage.testFolder'), true),
		'adapterClass' => '\Gaufrette\Adapter\Local',
		'class' => '\Gaufrette\Filesystem'
	)
);

CakePlugin::load('Chosen');
CakePlugin::load('Plupload');