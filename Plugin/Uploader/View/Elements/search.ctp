<?=
$this->Form->create(
	'UploadedFile',
	array(
		'url' => array('controller' => 'files', 'action' => 'find', $folderId),
		'inputDefaults' => array('required' => false)
	)
) .
$this->Form->input(
	'parent_id',
	array(
		'label' => __d('uploader', 'Rechercher dans ce dossier'),
		'type' => 'checkbox',
		'value' => $folderId,
		'checked' => true
	)
) .
$this->Form->input(
	'filename',
	array('label' => false, 'placeholder' => __d('uploader', 'Nom du fichier'), 'class' => 'span8 input-small', 'div' => false)
) .
$this->Form->input(
	'extension',
	array('label' => false, 'placeholder' => __d('uploader', 'Ext'), 'class' => 'span3 input-mini', 'div' => false)
)
?>
<a data-toggle="collapse" href="#advancedSearch">
	<?= __d('uploader', 'Recherche avancée'); ?>
</a>

<div id="advancedSearch" class="accordion-body collapse">
	<?php if ($can['canCreateUser']()): ?>
	<?=
	$this->Form->input(
		'username',
		array('label' => __d('uploader', 'Auteur du fichier'), 'placeholder' => __d('uploader', 'Utilisateur'), 'class' => 'span12', 'empty' => __('Tous'))
	)
	?>
	<?php endif ?>
	<?=
	$this->Form->input(
		'size_min',
		array('label' => false, 'placeholder' => __d('uploader', '1 Mo'), 'class' => 'span6 placeholder-size', 'div' => false, 'title' => 'Poids en Mo')
	) .
	$this->Form->input(
		'size_max',
		array('label' => false, 'placeholder' => __d('uploader', '5 Mo'), 'class' => 'span6 placeholder-size', 'div' => false, 'title' => 'Poids en Mo')
	) .
	$this->Form->input(
		'created_min',
		array('label' => false, 'placeholder' => __d('uploader', 'Date min'), 'class' => 'span6 placeholder-size', 'id' => 'search-from', 'div' => false)
	) .
	$this->Form->input(
		'created_max',
		array('label' => false, 'placeholder' => __d('uploader', 'Date max'), 'class' => 'span6 placeholder-size', 'id' => 'search-to', 'div' => false)
	) .
	$this->Chosen->select(
		'tags',
		$terms,
		array('multiple' => true, 'data-placeholder' => __d('uploader', 'File tags'), 'class' => 'span12')
	)
	?>
</div>
 <?=
$this->Form->submit(__d('uploader', 'Rechercher'), array('class' => 'btn span12', 'div' => false)) .
$this->Form->end();
?>

