<?php
$resetUrl = Router::url(array(
    'plugin' => 'sd_users',
    'controller' => 'sd_users',
    'action' => 'reset',
    $user['User']['email'],
    $activationKey,
), true);

$homeUrl = Router::url(array(
	'plugin' => 'uploader',
	'controller' => 'files',
	'action' => 'browse'
), true);
?>
<?= __d(
    'sd_users',
    'Bonjour %s,',
    $user['User']['name']
);?>

<?= __d(
    'sd_users',
    'Une demande de nouveau mot de passe a été envoyée depuis %s (%s)',
    Configure::read('Site.title'),
    $homeUrl
);?>.<p>

<?= __d(
    'sd_users',
    'Si vous n\'avez pas demander à changer de mot de passe, ignorez ce message et contactez l\'administrateur du site %s',
    Configure::read('Site.email')
);?>

<?= __d(
'sd_users',
'Vous devez suivre le lien suivant pour réinitialiser votre mot de passe : %s',
$resetUrl
);?>

<?=__d('sd_users','Vous pourrez ensuite personnaliser votre mot de passe dans votre compte.');?>

<?= __d('sd_users','Merci,');?>

<?= Configure::read('Site.title') . '-' . $homeUrl; ?>