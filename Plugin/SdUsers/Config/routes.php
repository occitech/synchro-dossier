<?php

CroogoRouter::connect('/users/login', array(
	'plugin' => 'users', 'controller' => 'users', 'action' => 'login'
));

CroogoRouter::connect('/users', array(
	'plugin' => 'sd_users', 'controller' => 'sd_users', 'action' => 'index'
));

CroogoRouter::connect('/users/edit', array(
	'plugin' => 'sd_users', 'controller' => 'sd_users', 'action' => 'edit'
));

CroogoRouter::connect('/users/add', array(
	'plugin' => 'sd_users', 'controller' => 'sd_users', 'action' => 'add'
));
