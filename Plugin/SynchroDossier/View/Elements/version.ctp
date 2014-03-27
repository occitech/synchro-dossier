<?php
/**
 * Displays information about the current version of the application
 */
	$basePath = APP . DS;
	$appVersion = array_fill_keys(array('version', 'revision', 'timestamp'), '');

	if (file_exists($basePath . 'VERSION.txt')) {
		$appVersion['timestamp'] = filemtime($basePath . 'VERSION.txt');
		$appVersion['version'] = file($basePath . 'VERSION.txt');
		$appVersion['version'] = trim($appVersion['version'][0]);
	}

	if (file_exists($basePath . 'REVISION')) {
		$appVersion['revision'] = file_get_contents($basePath . 'REVISION');
	}
	$isAdmin = CakeSession::read('Auth.User.role_id') == SdUser::ROLE_SUPERADMIN_ID || CakeSession::read('Auth.User.role_id') == SdUser::ROLE_OCCITECH_ID;
?>
	<small>
		<?php
			$versionText = $appVersion['version'];
			if ($isAdmin) :
				$versionText .= sprintf(
					' - %s<!-- %s -->',
					date('d/m/Y', (int) $appVersion['timestamp']),
					$appVersion['revision']
				);
				printf('(v%s)', $versionText);
			endif;
		?>
	</small>
