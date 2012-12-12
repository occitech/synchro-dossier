<?php $this->extend('/Common/admin_index'); ?>

<?php echo $this->Form->create('User', array('url' => array('controller' => 'sd_users', 'action' => 'process'))); ?>
<div class="row-fluid">
	<div class="span12">
		<table class="table table-striped">
			<?php
				$tableHeaders =  $this->Html->tableHeaders(array(
					$this->Paginator->sort('username', __('Nom d\'utilisateur')),
					$this->Paginator->sort('name', __('Nom')),
					$this->Paginator->sort('firstname', __('Prénom')),
					$this->Paginator->sort('email', __('Email')),
					$this->Paginator->sort('role', __('Role')),
					__('Actions'),
				));
				echo $tableHeaders;
				$rows = array();
				foreach ($users as $user) :
					$actions  = $this->Html->link(
						'',
						'mailto:' . $user['User']['email'],
						array('icon' => 'envelope', 'tooltip' => __('Envoyer un email à cet utilisateur'))
					);
					$actions .= ' ' . $this->Croogo->adminRowActions($user['User']['id']);
					if ($can['canUpdateUser']($user['User'])) {
						$actions .= ' ' . $this->Html->link(
							'',
							array('action' => 'edit', $user['User']['id']),
							array('icon' => 'pencil', 'tooltip' => __('Modifier cet utilisateur'))
						);
						$actions .= ' ' . $this->Form->postLink(
							'',
							array('action' => 'delete',	$user['User']['id']),
							array('icon' => 'remove', 'tooltip' => __('Supprimer cet utilisateur'), 'class' => 'red'),
							__('Are you sure?')
						);

					}

					$rows[] = array(
						$user['User']['username'],
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