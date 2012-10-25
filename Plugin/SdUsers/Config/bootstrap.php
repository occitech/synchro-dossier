<?php

Croogo::hookComponent('Users', 'SdUsers.Users');
Croogo::hookBehavior('User', 'SdUsers.User');

Croogo::hookAdminTab('Users/admin_add', 'Profil', 'sdUsers.admin_tab_user');
Croogo::hookAdminTab('Users/admin_edit', 'Profil', 'sdUsers.admin_tab_user');

// Information about Sd roles
Configure::write('sd.SuperAdmin.roleId', 4);
Configure::write('sd.SuperAdmin.roleAlias', 'sdSuperAdmin');

Configure::write('sd.Admin.roleId', 5);
Configure::write('sd.Admin.roleAlias', 'sdAdmin');

Configure::write('sd.Utilisateur.roleId', 6);
Configure::write('sd.Utilisateur.roleAlias', 'sdUtilisateur');