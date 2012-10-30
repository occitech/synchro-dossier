<?php

/**
 * Files routes
 */
CroogoRouter::connect('/upload', array(
	'plugin' => 'uploader', 'controller' => 'files', 'action' => 'upload'
));

CroogoRouter::connect('/browse/*', array(
	'plugin' => 'uploader', 'controller' => 'files', 'action' => 'browse'
));

CroogoRouter::connect('/', array(
	'plugin' => 'uploader', 'controller' => 'files', 'action' => 'browse'
));
Router::promote();