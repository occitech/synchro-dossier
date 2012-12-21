<?php

CroogoRouter::connect('/users/login', array(
	'plugin' => 'users', 'controller' => 'users', 'action' => 'login'
));

CroogoRouter::connect('/users/login', array(
	'admin' => true, 'plugin' => 'users', 'controller' => 'users', 'action' => 'login'
));

