<?php $this->extend('/Common/admin_index'); ?>
<?php echo $this->Form->create('SdUser', array('url' => array('controller' => 'sd_users', 'action' => 'process'))); ?>
<table cellpadding="0" cellspacing="0">
<?php
	$tableHeaders =  $this->Html->tableHeaders(array(
		'',
		$this->Paginator->sort('username'),
		$this->Paginator->sort('name'),
		$this->Paginator->sort('firstname'),
		$this->Paginator->sort('email'),
		__('Actions'),
	));
	echo $tableHeaders;

	$rows = array();
	foreach ($users as $user) {
		$actions  = $this->Html->link(__('Edit'), array('action' => 'edit', $user['SdUser']['id']));
		$actions .= ' ' . $this->Croogo->adminRowActions($user['SdUser']['id']);
		$actions .= ' ' . $this->Layout->processLink(__('Delete'),
			'#SdUser' . $user['SdUser']['id'] . 'Id',
			null, __('Are you sure?'));

		$rows[] = array(
			$this->Form->checkbox('SdUser.'.$user['SdUser']['id'].'.id'),
			$user['SdUser']['username'],
			$user['Profile']['name'],
			$user['Profile']['firstname'],
			$user['SdUser']['email'],
			$actions,
		);
	}

	echo $this->Html->tableCells($rows);
	echo $tableHeaders;
?>
</table>