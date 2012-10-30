<?php

App::uses('StorageManager', 'FileStorage.Lib');

Configure::write('FileStorage.adapter', 'Local');
Configure::write('FileStorage.testFolder', APP . 'tmp' . DS . 'tests' . DS . 'Uploader');
Configure::write('FileStorage.filePattern', '{user_id}/{file_id}/{version}-{filename}');

StorageManager::config(
	'Local',
	array(
		'adapterOptions' => array(APP . DS . WEBROOT_DIR . DS . 'uploads', true),
		'adapterClass' => '\Gaufrette\Adapter\Local',
		'class' => '\Gaufrette\Filesystem'
	)
);

StorageManager::config(
	'RemoteFtp',
	array(
		'adapterOptions' => array(
			'/home/email/synchrodossier',
			'37.59.122.142',
			'email',
			's4fHfO4kDks'
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