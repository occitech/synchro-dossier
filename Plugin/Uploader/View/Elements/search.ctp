<?=
$this->Form->create(
	'UploadedFile',
	array('url' => array('controller' => 'files', 'action' => 'find', $folderId))
) .
$this->Form->input(
	'parent_id',
	array(
		'label' => false,
		'multiple' => 'checkbox',
		'options' => array($folderId => __d('uploader', 'Rechercher dans ce dossier')),
	)
) .
$this->Form->input(
	'filename',
	array('label' => false, 'placeholder' => __d('uploader', 'Nom du fichier'), 'class' => 'span8', 'div' => false)
) .
$this->Form->input(
	'extension',
	array('label' => false, 'placeholder' => __d('uploader', 'Ext'), 'class' => 'span4', 'div' => false)
) .
$this->Form->input(
	'is_folder',
	array(
		'label' => false,
		'multiple' => 'checkbox',
		'options' => array(0 => __d('uploader', 'Fichiers'), 1 => __d('uploader', 'Dossiers')),
	)
) .
$this->Form->input(
	'username',
	array('label' => false, 'placeholder' => __d('uploader', 'Utilisateur'), 'class' => 'span12')
) .
$this->Form->input(
	'size_min',
	array('label' => false, 'placeholder' => __d('uploader', 'Taille min - ko'), 'class' => 'span6 placeholder-size', 'div' => false)
) .
$this->Form->input(
	'size_max',
	array('label' => false, 'placeholder' => __d('uploader', 'Taille max - ko'), 'class' => 'span6 placeholder-size', 'div' => false)
) .
$this->Form->input(
	'created_min',
	array('label' => false, 'placeholder' => __d('uploader', 'Date min'), 'class' => 'span6 placeholder-size', 'id' => 'search-from', 'div' => false)
) .
$this->Form->input(
	'created_max',
	array('label' => false, 'placeholder' => __d('uploader', 'Date max'), 'class' => 'span6 placeholder-size', 'id' => 'search-to', 'div' => false)
) .
$this->Form->submit(__d('uploader', 'Rechercher'), array('class' => 'btn span12', 'div' => false)) .
$this->Form->end();
?>