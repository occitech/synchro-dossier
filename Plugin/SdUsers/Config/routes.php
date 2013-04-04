<?php

CroogoRouter::connect('/users/login', array(
	'plugin' => 'users', 'controller' => 'users', 'action' => 'login'
));

CroogoRouter::connect('/sdusers', array(
	'plugin' => 'sd_users', 'controller' => 'sd_users', 'action' => 'index'
));

CroogoRouter::connect('/sdusers/edit', array(
	'plugin' => 'sd_users', 'controller' => 'sd_users', 'action' => 'edit'
));

CroogoRouter::connect('/sdusers/add', array(
	'plugin' => 'sd_users', 'controller' => 'sd_users', 'action' => 'add'
));
