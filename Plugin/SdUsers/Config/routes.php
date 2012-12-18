<?php

CroogoRouter::connect('/users/login', array(
	'plugin' => 'users', 'controller' => 'users', 'action' => 'login'
));

CroogoRouter::connect('/admin/users/users/login', array(
	'plugin' => 'users', 'controller' => 'users', 'action' => 'login'
));

Router::promote();