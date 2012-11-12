<?php $this->extend('/Common/admin_index'); ?>

<?php echo $this->Form->create('User', array('url' => array('controller' => 'sd_users', 'action' => 'process'))); ?>
<table cellpadding="0" cellspacing="0">
<?php
	$tableHeaders =  $this->Html->tableHeaders(array(
		$this->Paginator->sort('username', __('Username')),
		$this->Paginator->sort('name', __('Name')),
		$this->Paginator->sort('firstname', __('Firstname')),
		$this->Paginator->sort('email', __('Email')),
		$this->Paginator->sort('role', __('Role')),
		__('Actions'),
	));
	echo $tableHeaders;
	$rows = array();
	foreach ($users as $user) {
		$actions  = $this->Html->link(__('Mail'), 'mailto:' . $user['User']['email']);
		$actions .= ' ' . $this->Html->link(__('Edit'), array('action' => 'edit', $user['User']['id']));
		$actions .= ' ' . $this->Croogo->adminRowActions($user['User']['id']);
		$actions .= ' ' . $this->Form->postLink(__('Delete'), array(
			'action' => 'delete',
			$user['User']['id'],
		), null, __('Are you sure?'));

		$rows[] = array(
			$user['User']['username'],
			$user['Profile']['name'],
			$user['Profile']['firstname'],
			$user['User']['email'],
			$user['Role']['title'],
			$actions,
		);
	}

	echo $this->Html->tableCells($rows);
	echo $tableHeaders;
?>
</table>