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
		'options' => array($folderId => __('Rechercher dans ce dossier')),
	)
) .
$this->Form->input(
	'filename',
	array('label' => false, 'placeholder' => __('Nom du fichier'), 'class' => 'span8', 'div' => false)
) .
$this->Form->input(
	'extension',
	array('label' => false, 'placeholder' => __('Ext'), 'class' => 'span4', 'div' => false)
) .
$this->Form->input(
	'is_folder',
	array(
		'label' => false,
		'multiple' => 'checkbox',
		'options' => array(0 => __('Fichiers'), 1 => __('Dossiers')),
	)
) .
$this->Form->input(
	'username',
	array('label' => false, 'placeholder' => __('Utilisateur'), 'class' => 'span12')
) .
$this->Form->input(
	'size_min',
	array('label' => false, 'placeholder' => __('Taille min - ko'), 'class' => 'span6 placeholder-size', 'div' => false)
) .
$this->Form->input(
	'size_max',
	array('label' => false, 'placeholder' => __('Taille max - ko'), 'class' => 'span6 placeholder-size', 'div' => false)
) .
$this->Form->input(
	'created_min',
	array('label' => false, 'placeholder' => __('Date min'), 'class' => 'span6 placeholder-size', 'id' => 'search-from', 'div' => false)
) .
$this->Form->input(
	'created_max',
	array('label' => false, 'placeholder' => __('Date max'), 'class' => 'span6 placeholder-size', 'id' => 'search-to', 'div' => false)
) .
$this->Form->submit(__('Rechercher'), array('class' => 'btn span12', 'div' => false)) .
$this->Form->end();
?>