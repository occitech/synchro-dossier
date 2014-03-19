<?php
$resetLink = $this->Html->link(__d('sd_users', 'réinitialiser votre mot de passe'), Router::url(array(
    'plugin' => 'sd_users',
    'controller' => 'sd_users',
    'action' => 'reset',
    $user['User']['email'],
    $activationKey,
), true));

$mailToLink = '<a href="mailto:' . Configure::read('Site.email') . '">' . __d('sd_users', 'l\'administrateur du site') . '</a>';

$homeLink = $this->Html->link(Configure::read('Site.title'), Router::url(array(
	'plugin' => 'uploader',
	'controller' => 'files',
	'action' => 'browse'
), true));
?>
<p><?= __d(
    'sd_users',
    'Bonjour %s,',
    $user['Profile']['full_name']
);?></p>

<p><?= __d(
    'sd_users',
    'Une demande de nouveau mot de passe a été envoyé depuis %s',
    $homeLink
);?>.<p>

<p><?= __d(
    'sd_users',
    'Si vous n\'avez pas demander à changer de mot de passe, ignorez ce message et contactez %s',
    $mailToLink
);?></p>

<p><?= __d(
    'sd_users',
    'Vous devez suivre le lien pour %s',
    $resetLink
);?>
</p>

<p><?=__d('sd_users','Vous pourrez ensuite personnaliser votre mot de passe dans votre compte.');?></p>

<p><?=__d('sd_users','Merci,');?></p>

<p> - <?= $homeLink; ?></p>