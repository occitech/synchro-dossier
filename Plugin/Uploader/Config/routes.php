<?php

// Basic
CroogoRouter::connect('/upload', array(
	'plugin' => 'uploader', 'controller' => 'files', 'action' => 'upload'
));
CroogoRouter::connect('/browse/*', array(
	'plugin' => 'uploader', 'controller' => 'files', 'action' => 'browse'
));
CroogoRouter::connect('/view/*', array(
	'plugin' => 'uploader', 'controller' => 'folders', 'action' => 'view'
));