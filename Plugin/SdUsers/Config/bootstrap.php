<?php

Croogo::hookComponent('Users', 'SdUsers.Users');
Croogo::hookBehavior('User', 'SdUsers.User');

Croogo::hookAdminTab('Users/admin_add', 'Profil', 'sdUsers.admin_tab_user');
Croogo::hookAdminTab('Users/admin_edit', 'Profil', 'sdUsers.admin_tab_user');