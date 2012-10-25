<?php

Croogo::hookAdminTab('Users/admin_add', 'Profil', 'sdUsers.admin_tab_user');
Croogo::hookAdminTab('Users/admin_edit', 'Profil', 'sdUsers.admin_tab_user');

// Information about Sd roles
$OccitechId = 1;
$SuperAdminRoleId = 4;
$AdminRoleId = 5;
$UtilisateurRoleId = 6;

Configure::write('sd.Occitech.roleId', $OccitechId);
Configure::write('sd.' . $OccitechId . '.roleAlias', 'sdSuperAdmin');
Configure::write(
	'sd.' . $OccitechId . '.authorizeRoleCreation',
	array($OccitechId, $SuperAdminRoleId, $AdminRoleId, $UtilisateurRoleId)
);

Configure::write('sd.SuperAdmin.roleId', $SuperAdminRoleId);
Configure::write('sd.' . $SuperAdminRoleId . '.roleAlias', 'sdSuperAdmin');
Configure::write(
	'sd.' . $SuperAdminRoleId . '.authorizeRoleCreation',
	array($SuperAdminRoleId, $AdminRoleId, $UtilisateurRoleId)
);

Configure::write('sd.Admin.roleId', $AdminRoleId);
Configure::write('sd.' . $AdminRoleId . '.roleAlias', 'sdAdmin');
Configure::write('sd.' . $AdminRoleId . '.authorizeRoleCreation', array($AdminRoleId, $UtilisateurRoleId));

Configure::write('sd.Utilisateur.roleId', $UtilisateurRoleId);
Configure::write('sd.' . $UtilisateurRoleId . '.roleAlias', 'sdUtilisateur');
Configure::write('sd.' . $UtilisateurRoleId . '.authorizeRoleCreation', array());

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
