<?php
$config = array(
	'EventHandlers' => array(
		'SynchroDossier.SdAlertEmailManager' => array(
			'options' => array('priority' => 1)
		),
		'SynchroDossier.SdQuotaManager',
		'SynchroDossier.SdModeBoxManager',
		'SynchroDossier.SdRightsManager',
		'SynchroDossier.SdUploaderManager',
	)
);
