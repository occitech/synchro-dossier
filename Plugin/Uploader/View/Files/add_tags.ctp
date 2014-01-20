<?= $this->Form->create('Tags') ?>
	<?= $this->Form->input('Tags.tags', array('label' => __d('uploader', 'Tags list (separated by commas)'), 'class' => 'span5')) ?>
	<?= $this->Form->submit(__d('uploader', 'Submit tags'), array('class' => 'btn')) ?>
<?= $this->Form->end() ?>
