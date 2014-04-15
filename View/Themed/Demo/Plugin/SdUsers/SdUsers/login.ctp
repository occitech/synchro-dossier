<div class="alert alert-block">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<p>
	<?php echo implode('<br/>', array(
		__d('sd_users', 'Cet espace de démonstration n\'est pas destiné à être utilisé pour des échanges réels entre partenaires.'),
		__d('sd_users', 'Nous ne sommes aucunement tenus d\'assurer un quelconque niveau de qualité de service sur cet espace.'),
		__d('sd_users', 'Notre responsabilité ne saurait être engagée quant à l\'utilisation de cet espace de démonstration.'),
		)); ?>
		<br /><br />
	   <?php echo implode('<br/>', array(
		__d('sd_users', 'En utilisant cet espace de démonstration, vous reconnaissez avoir été prévenu que les contenus que vous utiliserez dessus seront accessibles aux autres personnes accédant également à cette démonstration dans la même journée.'),
		__d('sd_users', 'Cet espace est réinitialisé toutes les nuits (fichiers et utilisateurs).'),
		)); ?>
	</p>
</div>
<div class="users form">
	<div class="alert alert-info">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<h3><?php echo __('Identifiant de démonstration :') ;?></h3>
		<dl class="dl-horizontal">
			<dt><?php echo __d('sd_users', 'Adresse email') ?></dt>
			<dd>superadmin@synchro-dossier.fr</dd>
			<dt><?php echo __d('sd_users', 'Mot de passe'); ?></dt>
			<dd>password</dd>
		</dl>
	</div>
	<h2><?php echo __d('sd_users', 'Connexion utilisateur'); ?></h2>
	<?php echo $this->Form->create('User', array('url' => array('controller' => 'users', 'action' => 'login')));?>
		<fieldset>
		<?php
			echo $this->Form->input('email', array('label' => __d('sd_users', 'Adresse email')));
			echo $this->Form->input('password', array('label' => __d('sd_users', 'Mot de passe')));
		?>
		</fieldset>
		<?php echo $this->Form->end(array(
			'label' => __d('sd_users', 'Se connecter'),
			'class' => 'btn',
			'div' => array('class' => 'actions'),
			'after' =>
				 $this->Html->link(
					__d('sd_users', 'Mot de passe oublié ?'),
					array('plugin' => 'sd_users', 'controller' => 'sd_users', 'action' => 'forgot'),
					array('class' => 'btn-small')
				)
		));?>
</div>