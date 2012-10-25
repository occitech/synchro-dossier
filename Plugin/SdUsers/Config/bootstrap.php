<?php

Croogo::hookAdminTab('Users/admin_add', 'Profil', 'sdUsers.admin_tab_user');
Croogo::hookAdminTab('Users/admin_edit', 'Profil', 'sdUsers.admin_tab_user');

// Information about Sd roles
Configure::write('sd.SuperAdmin.roleId', 4);
Configure::write('sd.SuperAdmin.roleAlias', 'sdSuperAdmin');

Configure::write('sd.Admin.roleId', 5);
Configure::write('sd.Admin.roleAlias', 'sdAdmin');

Configure::write('sd.Utilisateur.roleId', 6);
Configure::write('sd.Utilisateur.roleAlias', 'sdUtilisateur');

// Admin menu
CroogoNav::add('sdUsers', array(
	'title' => __('Users'),
	'url' => array(
		'admin' => true,
		'plugin' => 'sd_users',
		'controller' => 'sd_users',
		'action' => 'index',
	),
	'children' => array(
		'list' => array(
			'title' => __('List'),
			'url' => array(
				'admin' => true,
				'plugin' => 'sd_users',
				'controller' => 'sd_users',
				'action' => 'index',
			),
		),
		'add' => array(
			'title' => __('Add'),
			'url' => array(
				'admin' => true,
				'plugin' => 'sd_users',
				'controller' => 'sd_users',
				'action' => 'add',
			),
		),
	),
));
