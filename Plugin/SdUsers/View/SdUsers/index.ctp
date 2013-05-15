<?php
	$this->start('navbar');
		echo $this->element('SynchroDossier.navbar'); 
	$this->end();
?>

<?php echo $this->Form->create('User', array('url' => array('controller' => 'sd_users', 'action' => 'process'))); ?>
<div class="row-fluid">
	<div class="span12">
		<table class="table table-striped">
			<?php
				$tableHeaders =  $this->Html->tableHeaders(array(
					$this->Paginator->sort('name', __d('sd_users', 'Nom')),
					$this->Paginator->sort('firstname', __d('sd_users', 'Prénom')),
					$this->Paginator->sort('email', __d('sd_users', 'Email')),
					$this->Paginator->sort('role', __d('sd_users', 'Role')),
					__d('sd_users', 'Actions'),
				));
				echo $tableHeaders;
				$rows = array();
				foreach ($users as $user) :
					$actions  = $this->Html->link(
						'',
						'mailto:' . $user['User']['email'],
						array('icon' => 'envelope', 'tooltip' => __d('sd_users', 'Envoyer un email à cet utilisateur'))
					);
					$actions .= ' ' . $this->Croogo->adminRowActions($user['User']['id']);
					if ($can['canUpdateUser']($user['User'])) :
						$actions .= ' ' . $this->Html->link(
							'',
							array('action' => 'edit', $user['User']['id']),
							array('icon' => 'pencil', 'tooltip' => __d('sd_users', 'Modifier cet utilisateur'))
						);
						$actions .= ' ' . $this->Form->postLink(
							'',
							array('action' => 'delete',	$user['User']['id']),
							array('icon' => 'remove', 'tooltip' => __d('sd_users', 'Supprimer cet utilisateur'), 'class' => 'red'),
							__d('sd_users', 'Are you sure?')
						);

					endif;

					$rows[] = array(
						$user['Profile']['name'],
						$user['Profile']['firstname'],
						$user['User']['email'],
						$user['Role']['title'],
						$actions,
					);
				endforeach;

				echo $this->Html->tableCells($rows);
				echo $tableHeaders;
			?>
		</table>
	</div>
</div>